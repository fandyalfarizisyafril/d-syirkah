<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TaxonomyController;
use App\Http\Middleware\RequireAdmin;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authenticate'])->middleware('throttle:20,1')->name('authenticate');
    Route::middleware([RequireAdmin::class, 'auth.session'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::view('account', 'admin.account')->name('account');
        Route::put('account/password', [AuthController::class, 'password'])->name('password');
        Route::resource('products', ProductController::class)->except('show');
        Route::get('content/{section}', [ContentController::class, 'edit'])->name('content.edit');
        Route::put('content/{section}', [ContentController::class, 'update'])->name('content.update');
        Route::resource('inquiries', InquiryController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::get('{type}', [TaxonomyController::class, 'index'])->where('type', 'brands|categories')->name('taxonomies.index');
        Route::get('{type}/create', [TaxonomyController::class, 'create'])->where('type', 'brands|categories')->name('taxonomies.create');
        Route::post('{type}', [TaxonomyController::class, 'store'])->where('type', 'brands|categories')->name('taxonomies.store');
        Route::get('{type}/{id}/edit', [TaxonomyController::class, 'edit'])->where('type', 'brands|categories')->whereNumber('id')->name('taxonomies.edit');
        Route::put('{type}/{id}', [TaxonomyController::class, 'update'])->where('type', 'brands|categories')->whereNumber('id')->name('taxonomies.update');
        Route::delete('{type}/{id}', [TaxonomyController::class, 'destroy'])->where('type', 'brands|categories')->whereNumber('id')->name('taxonomies.destroy');
    });
});
