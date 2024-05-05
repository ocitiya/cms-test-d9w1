<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromQuery, WithHeadings, WithStyles, ShouldAutoSize {
    use Exportable;

    protected $s = null;
    protected $categoryId = null;
    
    public function search(?string $search) {
        $this->s = $search;
        return $this;
    }

    public function category(?int $categoryId) {
        $this->categoryId = $categoryId;
        return $this;
    }

    public function query() {
        return Product::when($this->s, function($q) {
            $q->where("products.name", "LIKE", "%".$this->s."%");
        })
        ->when($this->categoryId, function($q) {
            $q->where("products.category_id", $this->categoryId);
        })
        ->leftJoin('categories AS c', 'c.id', '=', 'products.category_id')
        ->select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY products.id)'),
            'products.name',
            'c.name AS `category_name`',
            'products.purchase_price',
            'products.selling_price',
            'products.stock'
        );
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Produk',
            'Kategori Produk',
            'Harga Beli (Rp)',
            'Harga Jual (Rp)',
            'Stok'
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']]
        ];
    }

    public function map($row): array
    {
        return [
            $row->No ?? 'No Data',
            $row->name ?? 'No Data',
            $row->category_name ?? 'No Data',
            $row->purchase_price ?? 'No Data',
            $row->selling_price ?? 'No Data',
            $row->stock ?? 'No Data'
        ];
    }

}
