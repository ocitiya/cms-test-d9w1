<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {
    public function product(Request $request): Response {
        $data = [
            "categories" => Category::get()
        ];
        return response()->view('product.list', $data);
    }

    public function productAdd(Request $request): Response {
        $data = [
            "categories" => Category::get()
        ];
        
        return response()->view('product.form', $data);
    }
    
    public function productEdit(Request $request, $id): Response {
        $data = [
            "categories" => Category::get(),
            "product" => Product::find($id)
        ];
        
        return response()->view('product.form', $data);
    }

    public function profile(Request $request): Response {
        $userId = Auth::user()->id;
        $data = Account::find($userId);

        return response()->view('profile', $data);
    }
}
