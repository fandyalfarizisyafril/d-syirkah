<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class WebsiteController extends Controller
{
    public function home(): View
    {
        return view('pages.home');
    }

    public function products(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'in:Electrical,Mechanical,Fluid Handling,Environmental'],
        ]);
        $query = trim($filters['q'] ?? '');
        $category = $filters['category'] ?? '';
        $products = collect(config('company.products'))->filter(function (array $product) use ($query, $category) {
            $searchable = implode(' ', [$product['name'], $product['brand'], $product['summary'], ...$product['types']]);

            return ($category === '' || $product['group'] === $category)
                && ($query === '' || mb_stripos($searchable, $query) !== false);
        });

        return view('pages.products', compact('products', 'query', 'category'));
    }

    public function product(string $slug): View
    {
        $products = config('company.products');
        abort_unless(isset($products[$slug]), 404);
        $product = $products[$slug];
        $related = collect($products)->except($slug)->sortByDesc(fn (array $item) => $item['group'] === $product['group'])->take(3);

        return view('pages.product', compact('product', 'slug', 'related'));
    }

    public function brand(string $slug): View
    {
        $products = collect(config('company.products'))->where('brand_slug', $slug);
        abort_if($products->isEmpty(), 404);
        $brand = $products->first()['brand'];

        return view('pages.brand', compact('products', 'brand'));
    }

    public function contact(Request $request): View
    {
        $input = $request->validate(['product' => ['nullable', 'string', 'max:100']]);
        $product = config('company.products')[$input['product'] ?? ''] ?? null;
        $subject = 'Permintaan informasi'.($product ? ': '.$product['name'].' - '.$product['brand'] : ' produk dan solusi');
        $emailLink = 'mailto:'.config('company.emails.0').'?'.http_build_query([
            'subject' => $subject,
            'body' => "Yth. PT. Syirkah Mandiri Artomoro,\r\n\r\n".$subject."\r\n\r\nNama: \r\nPerusahaan: \r\nTelepon: \r\nKebutuhan: \r\nPesan: \r\n",
        ], '', '&', PHP_QUERY_RFC3986);

        return view('pages.contact', compact('product', 'emailLink'));
    }

    public function sitemap()
    {
        $urls = collect(['home', 'about', 'products.index', 'brands.index', 'solutions', 'contact'])->map(fn ($name) => route($name));
        foreach (config('company.products') as $slug => $product) {
            $urls->push(route('products.show', $slug), route('brands.show', $product['brand_slug']));
        }

        return response()->view('sitemap', ['urls' => $urls->unique()], 200)->header('Content-Type', 'application/xml');
    }
}
