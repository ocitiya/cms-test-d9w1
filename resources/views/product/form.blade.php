@extends('layouts.app')

@section('title')
    Tambah Produk
@endsection

@section('content')
<nav class="flex py-3 rounded-lg mb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li>
            <div class="flex items-center">
                <a href="{{ route('product.list') }}" class="text-gray-400 hover:text-gray-900 text-lg font-medium">Daftar Produk</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                
                @if (isset($product))
                    <span class="text-gray-700 ml-1 md:ml-2 text-lg font-medium">Edit Produk</span>
                @else
                    <span class="text-gray-700 ml-1 md:ml-2 text-lg font-medium">Tambah Produk</span>
                @endif
            </div>
        </li>
    </ol>
</nav>

@error('error')
    <x-alert type="error" :message="$message"/>
@enderror

<form action="{{ route('product.store', isset($product) ? $product->id : '') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div>
        <div class="flex gap-5">
            <div class="w-4/12">
                <div class="font-semibold text-sm">Kategori</div>
                <div class="relative mt-2 flex">
                    <select required id="category-select" name="category_id" class="w-full form-select @error('category_id') form-error @enderror">
                        <option {{ old('category_id') && !isset($product) ? '' : 'selected' }} disabled value="">Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option {{ (old('category_id') ?: ($product->category_id ?? null)) == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('category_id')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="w-8/12">
                <div class="font-semibold text-sm">Nama barang</div>
                <div class="relative mt-2 flex">
                    <input required type="text" class="form-input @error('name') form-error @enderror" name="name" value="{{ old('name') ?: ($product->name ?? null) }}" placeholder="Masukkan nama barang">
                </div>
                @error('name')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="flex gap-5 mt-5">
            <div class="w-4/12">
                <div class="font-semibold text-sm">Harga Beli</div>
                <div class="relative mt-2 flex">
                    <input required id="purchase-price" name="purchase_price" type="text" class="form-input @error('purchase_price') form-error @enderror" placeholder="Masukkan harga beli" value="{{ old('purchase_price') ?: ($product->purchase_price ?? null) }}">
                </div>
                @error('purchase_price')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="w-4/12">
                <div class="font-semibold text-sm">Harga Jual</div>
                <div class="relative mt-2 flex">
                    <input required id="selling-price" name="selling_price" readonly type="text" class="form-input  @error('selling_price') form-error @enderror" placeholder="Masukkan harga jual" value="{{ old('selling_price') ?: ($product->selling_price ?? null) }}">
                </div>
                @error('selling_price')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="w-4/12">
                <div class="font-semibold text-sm">Stok Barang</div>
                <div class="relative mt-2 flex">
                    <input required id="stock" type="text" class="form-input @error('stock') form-error @enderror" name="stock" value="{{ old('stock') ?: ($product->stock ?? null) }}" placeholder="Masukkan stok barang">
                </div>
                @error('stock')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="flex gap-5 mt-5 justify-center">
            <div class="w-full">
                <div class="font-semibold text-sm">Upload Image</div>
                <div class="border border-dashed border-blue-500 relative rounded-md mt-2 flex justify-center items-center h-32">
                    <input type="file" name="image" multiple class="cursor-pointer relative block opacity-0 w-full h-full p-20 z-50" id="fileInput" accept=".jpg,.jpeg,.png">
                    <div class="absolute flex flex-col items-center font-semibold h-full items-center justify-center left-0 m-auto right-0 text-blue-500 top-0">
                        <img src="{{ asset('images/Image.png') }}" width="60px" height="60px" id="imagePreview">
                        <div id="uploadText">upload gambar disini</div>
                    </div>
                </div>
            </div>
        </div>
        @error('image')
            <div class="text-red-500 text-sm">{{ $message }}</div>
        @enderror

        <div class="flex justify-end gap-3 mt-10">
            <button id="back-button" type="button" class="btn-outlined-blue">
                Batalkan
            </button>

            <button type="submit" class="btn-blue">
                Simpan
            </button>
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
    const isEdit = "{{ isset($product) ? 'true' : 'false' }}";
    const fileInput = document.querySelector('#fileInput');
    const imagePreview = document.querySelector('#imagePreview');
    const uploadText = document.querySelector('#uploadText');
    const purchasePriceInput = document.querySelector('#purchase-price');
    const sellingPriceInput = document.querySelector('#selling-price');
    const stockInput = document.querySelector('#stock');
    const backButton = document.querySelector('#back-button');

    backButton.addEventListener('click', function() {
        window.history.back();
    });

    purchasePriceInput.addEventListener('keyup', function() {
        this.value = currencyFormat(this.value);

        const value = currency2Float(this.value);
        const sellingPrice = (value * 30 / 100) + value;

        sellingPriceInput.value = currencyFormat(sellingPrice);
    });

    stockInput.addEventListener('keyup', function() {
        this.value = currencyFormat(this.value, false);
    });

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            if (file.size <= 100 * 1024 && (file.type === 'image/jpeg' || file.type === 'image/png')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
                uploadText.textContent = file.name;
            } else {
                alert('Hanya bisa menerim file JPEG atau PNG saja dengan ukuran maksimal 100 KB.');
                this.value = null;
                imagePreview.src = '{{ asset('images/Image.png') }}';
                uploadText.textContent = 'upload gambar disini';
            }
        } else {
            imagePreview.src = '{{ asset('images/Image.png') }}';
            uploadText.textContent = 'upload gambar disini';
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (isEdit == "true") {
            purchasePriceInput.value = currencyFormat(purchasePriceInput.value);
            sellingPriceInput.value = currencyFormat(sellingPriceInput.value);
            stockInput.value = currencyFormat(stockInput.value);
        }
    });

</script>
@endsection