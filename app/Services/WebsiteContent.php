<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteContent;
use Illuminate\Support\Facades\Schema;

class WebsiteContent
{
    public const ICONS = ['cog', 'zap', 'activity', 'flask-conical', 'droplets', 'cable', 'wind', 'shield-check', 'drafting-compass', 'gauge', 'factory'];

    public function get(): array
    {
        if (request()->attributes->has('website_content')) {
            return request()->attributes->get('website_content');
        }
        $company = config('company');
        $company['brands'] = collect($company['products'])->mapWithKeys(fn ($p) => [$p['brand_slug'] => ['name' => $p['brand'], 'description' => $p['summary'], 'logo' => null]])->all();
        $company['categories'] = collect($company['products'])->pluck('group')->unique()->values()->all();
        $company['cms_ready'] = false;
        if (Schema::hasTable('site_contents')) {
            $contents = SiteContent::all()->keyBy('key');
            if ($contents->has('company')) {
                $company = array_replace($company, $contents['company']->data);
                $records = Product::with(['brand', 'category'])->where('published', true)->orderBy('id')->get();
                $company['products'] = $records->mapWithKeys(fn ($p) => [$p->slug => [
                    ...$p->only(['id', 'name', 'icon', 'summary', 'description', 'types', 'applications', 'requirements', 'specifications', 'image', 'datasheet']),
                    'brand' => $p->brand->name, 'brand_slug' => $p->brand->slug, 'group' => $p->category->name,
                ]])->all();
                $company['brands'] = Brand::orderBy('id')->get()->mapWithKeys(fn ($b) => [$b->slug => $b->only(['name', 'description', 'logo'])])->all();
                $company['categories'] = Category::orderBy('id')->pluck('name')->all();
                foreach (['focus', 'values', 'legal'] as $section) {
                    $company[$section] = $contents->get($section)?->data ?? [];
                }
                $company['solutions'] = collect($contents->get('solutions')?->data ?? [])->map(fn ($s) => [
                    ...$s, 'products' => $records->whereIn('id', $s['product_ids'] ?? [])->pluck('slug')->all(),
                ])->all();
                $company['cms_ready'] = true;
            }
        }
        request()->attributes->set('website_content', $company);

        return $company;
    }
}
