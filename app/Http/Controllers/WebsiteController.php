<?php

namespace App\Http\Controllers;

use App\Services\WebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WebsiteController extends Controller
{
    public function __construct(private WebsiteContent $content) {}

    public function home(): View
    {
        return view('pages.home');
    }

    public function products(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', Rule::in($this->content->get()['categories'])],
        ]);
        $query = trim($filters['q'] ?? '');
        $category = $filters['category'] ?? '';
        $products = collect($this->content->get()['products'])->filter(function (array $product) use ($query, $category) {
            $searchable = implode(' ', [$product['name'], $product['brand'], $product['summary'], ...$product['types']]);

            return ($category === '' || $product['group'] === $category)
                && ($query === '' || mb_stripos($searchable, $query) !== false);
        });

        return view('pages.products', compact('products', 'query', 'category'));
    }

    public function product(string $slug): View
    {
        $products = $this->content->get()['products'];
        abort_unless(isset($products[$slug]), 404);
        $product = $products[$slug];
        $related = collect($products)->except($slug)->sortByDesc(fn (array $item) => $item['group'] === $product['group'])->take(3);

        return view('pages.product', compact('product', 'slug', 'related'));
    }

    public function brand(string $slug): View
    {
        $company = $this->content->get();
        abort_unless(isset($company['brands'][$slug]), 404);
        $brandInfo = $company['brands'][$slug];
        $products = collect($company['products'])->where('brand_slug', $slug);
        $brand = $brandInfo['name'];

        return view('pages.brand', compact('products', 'brand', 'brandInfo'));
    }

    public function contact(Request $request): View
    {
        $input = $request->validate(['product' => ['nullable', 'string', 'max:180']]);
        $company = $this->content->get();
        $product = $company['products'][$input['product'] ?? ''] ?? null;
        $subject = 'Permintaan informasi'.($product ? ': '.$product['name'].' - '.$product['brand'] : ' produk dan solusi');
        $emailLink = 'mailto:'.$company['emails'][0].'?'.http_build_query([
            'subject' => $subject,
            'body' => 'Yth. '.$company['name'].",\r\n\r\n".$subject."\r\n\r\nNama: \r\nPerusahaan: \r\nTelepon: \r\nKebutuhan: \r\nPesan: \r\n",
        ], '', '&', PHP_QUERY_RFC3986);

        return view('pages.contact', compact('product', 'emailLink'));
    }

    public function sitemap()
    {
        $urls = collect(['home', 'about', 'products.index', 'brands.index', 'solutions', 'contact'])->map(fn ($name) => route($name));
        foreach ($this->content->get()['products'] as $slug => $product) {
            $urls->push(route('products.show', $slug));
        }
        foreach ($this->content->get()['brands'] as $slug => $brand) {
            $urls->push(route('brands.show', $slug));
        }

        return response()->view('sitemap', ['urls' => $urls->unique()], 200)->header('Content-Type', 'application/xml');
    }
}
