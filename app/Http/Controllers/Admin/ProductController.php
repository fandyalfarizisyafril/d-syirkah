<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteContent;
use App\Services\CmsMedia;
use App\Services\WebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['q' => 'nullable|string|max:100', 'state' => ['nullable', Rule::in(['published', 'draft'])]]);
        $products = Product::with(['brand', 'category'])->when($filters['q'] ?? null, fn ($q, $term) => $q->where('name', 'like', '%'.$term.'%'))
            ->when($filters['state'] ?? null, fn ($q, $state) => $q->where('published', $state === 'published'))->latest('id')->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return $this->form(new Product(['published' => true, 'icon' => 'cog', 'types' => [], 'applications' => [], 'requirements' => [], 'specifications' => []]));
    }

    public function edit(Product $product)
    {
        return $this->form($product);
    }

    private function form(Product $product)
    {
        return view('admin.products.form', ['product' => $product, 'brands' => Brand::orderBy('name')->get(), 'categories' => Category::orderBy('name')->get(), 'icons' => WebsiteContent::ICONS]);
    }

    public function store(Request $request, CmsMedia $media)
    {
        return $this->persist($request, new Product, $media);
    }

    public function update(Request $request, Product $product, CmsMedia $media)
    {
        return $this->persist($request, $product, $media);
    }

    private function persist(Request $request, Product $product, CmsMedia $media)
    {
        $data = $request->validate([
            'name' => 'required|string|max:180',
            'slug' => ['required', 'string', 'max:180', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('products')->ignore($product)],
            'brand_id' => 'required|integer|exists:brands,id', 'category_id' => 'required|integer|exists:categories,id',
            'icon' => ['required', Rule::in(WebsiteContent::ICONS)],
            'summary' => 'required|string|max:300', 'description' => 'required|string|max:10000',
            'types' => 'nullable|string|max:3000', 'applications' => 'nullable|string|max:3000', 'requirements' => 'nullable|string|max:3000',
            'specifications' => 'nullable|array|max:50', 'specifications.*.label' => 'required|string|max:100|distinct', 'specifications.*.value' => 'required|string|max:500',
            'published' => 'required|boolean',
            'image' => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:2048',
            'datasheet' => 'nullable|file|mimes:pdf|max:2048',
            'remove_image' => 'sometimes|boolean', 'remove_datasheet' => 'sometimes|boolean',
        ]);
        foreach (['types', 'applications', 'requirements'] as $field) {
            $data[$field] = array_values(array_filter(array_map('trim', preg_split('/\R/u', $data[$field] ?? '')), fn ($v) => $v !== ''));
        }
        $data['specifications'] = collect($data['specifications'] ?? [])->pluck('value', 'label')->all();
        $data['updated_by'] = $request->user()->id;
        $media->save($product, $data, $request, ['image', 'datasheet']);

        return redirect()->route('admin.products.edit', $product)->with('status', 'Produk berhasil disimpan.');
    }

    public function destroy(Request $request, Product $product, CmsMedia $media)
    {
        $paths = [$product->image, $product->datasheet];
        DB::transaction(function () use ($product, $request) {
            $solutions = SiteContent::whereKey('solutions')->lockForUpdate()->first();
            if ($solutions) {
                $solutions->update(['data' => collect($solutions->data)->map(fn ($s) => [...$s, 'product_ids' => array_values(array_diff($s['product_ids'] ?? [], [$product->id]))])->all(), 'updated_by' => $request->user()->id]);
            }
            $product->delete();
        });
        foreach ($paths as $path) {
            $media->delete($path);
        }

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil dihapus.');
    }
}
