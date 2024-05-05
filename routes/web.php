<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\IsAuth;
use App\Http\Middleware\IsGuest;
use Illuminate\Support\Facades\Route;

Route::middleware([IsGuest::class])->group(function() {
    Route::get('/', [LoginController::class, 'login'])->name('login');
    Route::post("/login", [LoginController::class, 'authenticate'])->name('do.login');
});

Route::middleware([IsAuth::class])->group(function() {
    Route::get('/product', [DashboardController::class, 'product'])->name('product.list');
    Route::get('/product/add', [DashboardController::class, 'productAdd'])->name('product.add');
    Route::get('/product/edit/{id}', [DashboardController::class, 'productEdit'])->name('product.edit');
    Route::get('/product/data/list', [ProductController::class, 'list'])->name('product.data.list');
    Route::post('/product/store/{id?}', [ProductController::class, 'store'])->name('product.store');
    Route::post('/product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
    Route::get('/product/export', [ProductController::class, 'export'])->name('product.export');

    Route::get("/profile", [DashboardController::class, 'profile'])->name('profile');
    
    Route::get("/logout", [LoginController::class, 'onLogout'])->name('do.logout');
});


