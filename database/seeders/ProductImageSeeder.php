<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('product_images') as $slug => $reference) {
            if (! is_file(public_path($reference['image']))) {
                continue;
            }
            Product::where('slug', $slug)->whereNull('image')
                ->whereHas('brand', fn ($query) => $query->where('slug', $reference['brand']))
                ->update(['image' => $reference['image']]);
        }
    }
}
