<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Http\Helper;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductController extends Controller {
    public function list(Request $request): JsonResponse {
        try {
            $page = $request->query('page') ?? 1;
            $limit = $request->query('limit') ?? 10;
            $categoryId = $request->query('category_id') ?? null;
            $search = $request->query('search') ?? null;

            $model = Product::when($categoryId, function ($q) use ($categoryId) {
                $q->where("category_id", $categoryId);
            })->when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%".$search."%");
            })->with('category');
            $data = Helper::paginate($model, $page, $limit);
    
            return Helper::jsonResponse(true, null, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, $e->getMessage());
        }
    }

    public function store(Request $request, $id = null): RedirectResponse {
        $path = null;

        try {
            $validator = Validator::make($request->all(), [
                'id' => ['nullable', 'numeric'],
                'category_id' => ['required', 'numeric', function ($attribute, $value, $fail) use ($request) {
                    if (!Category::find($value)) $fail("Kategori tidak ditemukan"); 
                }],
                'name' => ['required', 'max:255'],
                'purchase_price' => ['required', 'regex:/^\d{1,3}(,\d{3})*(\.\d{1,2})?$/'],
                'stock' => ['required', 'regex:/^\d{1,3}(,\d{3})*$/', function ($attribute, $value, $fail) use ($request) {
                    if (!is_numeric($value)) {
                        $fail("Format stok harus berupa angka atau karakter");
                    }
                }],
                'image' => [
                    'file',
                    function ($attribute, $value, $fail) {
                        $allowedExtensions = ['jpg', 'jpeg', 'png'];
                        $extension = $value->getClientOriginalExtension();
                        if (!in_array($extension, $allowedExtensions)) {
                            $fail("Hanya menerima file dengan format: " . implode(', ', $allowedExtensions) . ".");
                        }
                    },
                    'max:100'
                ],
            ], [
                'id.numeric' => 'Format id tidak sesuai',
                'category_id.required' => 'Silahkan pilih kategori',
                'category_id.numeric' => 'Data kategori tidak valid',
                'name.required' => 'Silahkan isi nama barang',
                'name.max' => 'Tidak bisa melebihi 255 karakter',
                'purchase_price.required' => 'Silahkan isi harga beli',
                'selling_price.required' => 'Silahkan isi harga jual',
                'stock.required' => 'Silahkan isi stok',
                'image.mimes' => 'Hanya menerima file jpg atau png',
                'image.max' => 'Maksimal ukuran gambar adalah 100KB'
            ]);
    
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = now()->format('YmdHis') . '_' . Str::random(4) . '.' . $image->getClientOriginalExtension();
                if (!Storage::exists('public/products')) Storage::makeDirectory('public/products');
                $path = $image->storeAs('public/products', $filename);
            } else {
                if (!$id) return back()->withErrors(['image' => 'Silahkan masukkan gambar produk!'])->withInput();
            }

            $product = $id ? Product::find($id) : new Product();
            $product->category_id = $request->input('category_id');
            $product->name = $request->input('name');

            $purchasePrice = Helper::currency2Float($request->input('purchase_price'));

            $product->purchase_price = $purchasePrice;
            $product->selling_price = $purchasePrice * 1.3;
            $product->stock = Helper::currency2Float($request->input('stock'));

            if ($id && $request->hasFile('image')) {
                $oldPath = "public/products/" . $product->image;
                if (Storage::exists($oldPath)) {
                    $imageDeleted = Storage::delete($oldPath);
                    if (!$imageDeleted) throw new Exception("Gambar tidak bisa dihapus");
                }
            }

            if ($request->hasFile('image')) $product->image = $filename;
            $save = $product->save();

            if (!$save) {
                if (isset($path) && Storage::exists($path)) Storage::delete($path);
                throw new Exception("Tidak bisa menyimpan data!");
            }
    
            return redirect()->route('product.list')->with('success', 'Berhasil menyimpan data produk!');
        } catch (Exception $e) {
            if ($path) if (isset($path) && Storage::exists($path)) Storage::delete($path);
            return back()->withErrors([
                'error' => $e->getMessage(),
            ])->withInput();
        }
    }

    public function delete(Request $request, $id): JsonResponse {
        try {
            DB::beginTransaction();
            $product = Product::find($id);
            $deleted = $product->delete();

            if (!$deleted) throw new Exception("Produk {$product->name} tidak bisa dihapus!");

            $filename = $product->image;
            $path = "public/products/" . $filename;
            if (Storage::exists($path)) {
                $imageDeleted = Storage::delete($path);
                if (!$imageDeleted) throw new Exception("Gambar tidak bisa dihapus");
            }

            DB::commit();
            return Helper::jsonResponse(true, "Produk {$product->name} berhasil dihapus!");
        } catch (Exception $e) {
            DB::rollBack();
            return Helper::jsonResponse(false, $e->getMessage());
        }
    }

    public function export(Request $request): BinaryFileResponse {
        $categoryId = $request->query('category_id') ?? null;
        $search = $request->query('search') ?? null;

        return (new ProductsExport)
            ->category($categoryId)
            ->search($search)
            ->download('products.xlsx');
    }
}
