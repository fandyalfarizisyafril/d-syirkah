<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Inquiry;
use App\Models\Product;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(), 'publishedCount' => Product::where('published', true)->count(),
            'brandCount' => Brand::count(), 'newCount' => Inquiry::where('status', 'new')->count(),
            'recent' => Inquiry::latest()->take(8)->get(),
        ]);
    }
}
