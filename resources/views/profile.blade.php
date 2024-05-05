@extends('layouts.app')

@section('title')
    Profile
@endsection

@section('content')
<nav class="flex  py-3 rounded-lg mb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li>
            <div class="flex items-center">
                <span href="#" class="text-gray-800 text-lg font-medium">Profil</span>
            </div>
        </li>
    </ol>
</nav>

<img src="{{ asset('images/Frame-98700.png') }}" alt="Avatar" class="h-24 w-24">

<div class="text-2xl font-bold mb-5 mt-3">
    {{ $name }}
</div>

<div class="flex gap-5">
    <div class="w-8/12">
        <div class="font-semibold text-sm">Nama Kandidat</div>
        <div class="relative mt-2 flex">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" class="fill-gray-500">
                    <path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480v58q0 59-40.5 100.5T740-280q-35 0-66-15t-52-43q-29 29-65.5 43.5T480-280q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480v58q0 26 17 44t43 18q26 0 43-18t17-44v-58q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93h200v80H480Zm0-280q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Z"/>
                </svg>
            </div>
            <input readonly type="text" class="ring-gray-300 block w-full rounded-md border-0 py-1.5 pl-10 pr-2 text-gray-900 ring-1 ring-inset placeholder:text-gray-400 sm:text-sm sm:leading-6" value="{{ $name }}">
        </div>
    </div>

    <div class="w-4/12">
        <div class="font-semibold text-sm">Posisi Kandidat</div>
        <div class="relative mt-2 flex">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" class="fill-gray-500">
                    <path d="M320-240 80-480l240-240 57 57-184 184 183 183-56 56Zm320 0-57-57 184-184-183-183 56-56 240 240-240 240Z"/>
                </svg>
            </div>
            <input readonly type="text" class="ring-gray-300 block w-full rounded-md border-0 py-1.5 pl-10 pr-2 text-gray-900 ring-1 ring-inset placeholder:text-gray-400 sm:text-sm sm:leading-6" value="{{ $position }}">
        </div>
    </div>
</div>
@endsection
