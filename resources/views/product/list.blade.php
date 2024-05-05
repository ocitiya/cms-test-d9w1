@extends('layouts.app')

@section('title')
    Products
@endsection

@section('content')
<nav class="flex py-3 rounded-lg mb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li>
            <div class="flex items-center">
                <span href="#" class="text-gray-800 text-lg font-medium">Daftar Produk</span>
            </div>
        </li>
    </ol>
</nav>

@error('success')
    <x-alert type="success" :message="$message"/>
@enderror

<div class="flex justify-between items-center">
    <div class="flex gap-5 items-center">
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" class="fill-gray-500">
                    <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/>
                </svg>
            </div>
            <input type="text" name="text" id="search" class="form-input form-icon" placeholder="Cari barang">
        </div>

        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" class="fill-gray-500">
                    <path d="M440-183v-274L200-596v274l240 139Zm80 0 240-139v-274L520-457v274Zm-40-343 237-137-237-137-237 137 237 137ZM160-252q-19-11-29.5-29T120-321v-318q0-22 10.5-40t29.5-29l280-161q19-11 40-11t40 11l280 161q19 11 29.5 29t10.5 40v318q0 22-10.5 40T800-252L520-91q-19 11-40 11t-40-11L160-252Zm320-228Z"/>
                </svg>
            </div>
            <select id="category-select" name="category" class="form-select form-icon">
                <option selected value="">Semua</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button id="export-button" class="hover:bg-green-700 bg-green-600 transition rounded-md text-white flex justify-center items-center text-sm gap-1 h-9 px-2">
            <img src="{{ asset('images/MicrosoftExcelLogo.png') }}" width="20px" height="20px">
            <span>Export Excel</span>
        </button>

        <a href="{{ route('product.add') }}" class="hover:bg-red-600 bg-current transition rounded-md text-white flex justify-center items-center text-sm gap-1 h-9 px-2">
            <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" class="fill-white">
                <path d="M440-280h80v-160h160v-80H520v-160h-80v160H280v80h160v160Zm40 200q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/>
            </svg>
            <span>Tambah Produk</span>
        </a>
    </div>
</div>

<div id="table">
    <div class="mt-5">Loading ...</div>
    {{-- Dynamic content --}}
</div>

<div class="flex justify-between items-center">
    <div id="table-info" class="text-sm">
        {{-- Dynamic content --}}
    </div>

    <div id="table-pagination">
        {{-- Dynamic content --}}
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        const tableAjaxURL = "{{ route('product.data.list') }}";
        const deleteURL = "{{ route('product.delete', '') }}";
        const csrfToken = "{{ csrf_token() }}";
        const exportURL = "{{ route('product.export') }}";

        const table = document.querySelector('#table');
        const paginationElement = document.querySelector('#table-pagination');
        const paginationInfo = document.querySelector('#table-info');
        const categorySelect = document.querySelector('#category-select');
        const exportButton = document.querySelector('#export-button');

        let searchTimeout;
        const searchInput = document.querySelector('#search');

        let maxPages = 1;
        let maxRows = 0;
        let page = 1;
        let limit = 10;

        let selectedCategory = "";
        let search = "";

        const columns = [
            { data: 'no', name: 'No', className: 'text-center' },
            { data: 'image', name: 'Image', className: 'text-center', render: (value) => {
                return `<img src="{{ asset('storage/products') }}/${value}" width="100px">`;
            }},
            { data: 'name', name: 'Nama Produk' },
            { data: 'category', name: 'Kategori Produk', render: (value) => {
                return value.name;
            } },
            { data: 'purchase_price', name: 'Harga Beli (Rp)', className: 'text-right', render: (value) => {
                return currencyFormat(value);   
            }},
            { data: 'selling_price', name: 'Harga Jual (Rp)', className: 'text-right', render: (value) => {
                return currencyFormat(value);
            }},
            { data: 'stock', name: 'Stok Produk', className: 'text-right', render: (value) => {
                return currencyFormat(value, false);
            }},
            { data: 'id', name: 'Aksi', className: 'text-center', render: (id, row) => {
                return `
                    <div class="flex gap-2 items-center">
                        <a href="{{ route('product.edit', '') }}/${id}" type="button">
                            <img src="{{ asset('images/edit.png') }}">
                        </a>
                        <button type="button" class="delete-button" data-id="${id}" data-name="${row.name}">
                            <img src="{{ asset('images/delete.png') }}">    
                        </button>
                    </div>
                `;
            }}
        ];

        const dataTable = (data, columns) => {
            const newTable = generateTable(data, columns, {
                className: 'w-full text-md rounded mb-4 mt-5 border border-gray-300 border-collapse',
                tHClassName: 'bg-gray-200 p-2 text-sm py-2',
                tDClassName: 'p-2 text-sm py-2'
            });
            table.innerHTML = newTable.outerHTML;

            const pagination = generatePagination(page, maxPages);
            paginationElement.innerHTML = pagination.outerHTML;

            paginationInfo.innerHTML = `Show ${data.length} from ${maxRows}`;
        }

        const getData = () => {
            let url = tableAjaxURL;
            url = addQueryParam(url, 'page', page)
            url = addQueryParam(url, 'limit', limit)

            if (selectedCategory) url = addQueryParam(url, 'category_id', selectedCategory);
            if (search) url = addQueryParam(url, 'search', search);

            fetch(url)
                .then(res => res.json())
                .then(res => {
                    const { data, pagination } = res.data
                    maxPages = pagination.maxPage;
                    page = pagination.page
                    maxRows = pagination.maxRows;

                    let startRow = (page - 1) * limit + 1;
                    data.map(item => {
                        item.no = startRow;
                        startRow += 1;
                    });

                    dataTable(data, columns);
                });
        }

        const deleteProduct = (id) => {
            const formData = new FormData();
            formData.append('_token', csrfToken);

            fetch(`${deleteURL}/${id}`, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(({ success, message, data }) => {
                Swal.fire({
                    icon: success ? 'success' : 'error',
                    text: message
                });

                if (success) getData();
            });
        }

        categorySelect.addEventListener('change', function() {
            selectedCategory = this.value;
            getData();
        });

        searchInput.addEventListener('keyup', function() {
            search = this.value;
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                getData();
            }, 1000);
        });

        paginationElement.addEventListener('click', function(e) {
            if (e.target.classList.contains('change-page') || e.target.parentElement.classList.contains('change-page')) {
                const newPage = e.target.closest('.change-page').getAttribute('data-page');
                page = newPage;
                getData();
            }
        });

        table.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-button') || e.target.parentElement.classList.contains('delete-button')) {
                const id = e.target.closest('.delete-button').getAttribute('data-id');
                const name = e.target.closest('.delete-button').getAttribute('data-name');
                
                Swal.fire({
                    text: `Ingin menghapus produk ${name}?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya"
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteProduct(id);
                    }
                });
            }
        });
        
        exportButton.addEventListener('click', function() {
            let url = exportURL;
            if (selectedCategory) url = addQueryParam(url, 'category_id', selectedCategory);
            if (search) url = addQueryParam(url, 'search', search);

            window.open(url, '_blank');
        });

        document.addEventListener('DOMContentLoaded', function() {
            getData();
        });
    </script>
@endsection
