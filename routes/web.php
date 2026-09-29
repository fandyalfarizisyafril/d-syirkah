<?php

use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebsiteController::class, 'home'])->name('home');
Route::view('/about', 'pages.about')->name('about');
Route::get('/products', [WebsiteController::class, 'products'])->name('products.index');
Route::get('/products/{slug}', [WebsiteController::class, 'product'])->name('products.show');
Route::view('/brands', 'pages.brands')->name('brands.index');
Route::get('/brands/{slug}', [WebsiteController::class, 'brand'])->name('brands.show');
Route::view('/solutions', 'pages.solutions')->name('solutions');
Route::get('/contact', [WebsiteController::class, 'contact'])->name('contact');
Route::get('/sitemap.xml', [WebsiteController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', fn () => response("User-agent: *\nAllow: /\nSitemap: ".route('sitemap')."\n", 200, ['Content-Type' => 'text/plain']));
