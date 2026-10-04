<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WebsiteContentSeeder extends Seeder
{
    public function run(): void
    {
        if (SiteContent::whereKey('company')->exists()) {
            return;
        }
        DB::transaction(function () {
            $source = config('company');
            foreach ($source['products'] as $slug => $data) {
                $brand = Brand::firstOrCreate(['slug' => $data['brand_slug']], ['name' => $data['brand'], 'description' => $data['summary']]);
                $category = Category::firstOrCreate(['name' => $data['group']], ['slug' => Str::slug($data['group'])]);
                Product::firstOrCreate(['slug' => $slug], [...Arr::except($data, ['brand', 'brand_slug', 'group']), 'image' => $data['image'] ?? config('product_images.'.$slug.'.image'), 'brand_id' => $brand->id, 'category_id' => $category->id]);
            }
            foreach (['focus', 'values', 'legal'] as $key) {
                SiteContent::firstOrCreate(['key' => $key], ['data' => $source[$key]]);
            }
            $solutions = collect($source['solutions'])->map(fn ($s) => [...Arr::except($s, 'products'), 'product_ids' => Product::whereIn('slug', $s['products'])->pluck('id')->all()])->all();
            SiteContent::firstOrCreate(['key' => 'solutions'], ['data' => $solutions]);
            SiteContent::create(['key' => 'company', 'data' => [
                ...Arr::except($source, ['products', 'focus', 'values', 'legal', 'solutions']),
                'wordmark_top' => 'SYIRKAH MANDIRI', 'wordmark_bottom' => 'ARTOMORO', 'location' => 'Pekanbaru, Indonesia',
            ]]);
        });
    }
}
