<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Services\CmsMedia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxonomyController extends Controller
{
    private function model(string $type): string
    {
        return match ($type) {
            'brands' => Brand::class, 'categories' => Category::class, default => abort(404)
        };
    }

    public function index(string $type)
    {
        return view('admin.taxonomies.index', ['type' => $type, 'items' => $this->model($type)::withCount('products')->orderBy('name')->paginate(20)]);
    }

    public function create(string $type)
    {
        $model = $this->model($type);

        return view('admin.taxonomies.form', ['type' => $type, 'item' => new $model]);
    }

    public function edit(string $type, int $id)
    {
        return view('admin.taxonomies.form', ['type' => $type, 'item' => $this->model($type)::findOrFail($id)]);
    }

    public function store(Request $request, string $type, CmsMedia $media)
    {
        $model = $this->model($type);

        return $this->persist($request, $type, new $model, $media);
    }

    public function update(Request $request, string $type, int $id, CmsMedia $media)
    {
        return $this->persist($request, $type, $this->model($type)::findOrFail($id), $media);
    }

    private function persist(Request $request, string $type, $item, CmsMedia $media)
    {
        $rules = [
            'name' => ['required', 'string', 'max:120', ...($type === 'categories' ? [Rule::unique('categories')->ignore($item)] : [])],
            'slug' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique($type)->ignore($item)],
        ];
        if ($type === 'brands') {
            $rules += ['description' => 'nullable|string|max:3000', 'logo' => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:2048', 'remove_logo' => 'sometimes|boolean'];
        }
        $data = $request->validate($rules);
        $data['updated_by'] = $request->user()->id;
        $media->save($item, $data, $request, $type === 'brands' ? ['logo'] : []);

        return redirect()->route('admin.taxonomies.index', $type)->with('status', 'Data berhasil disimpan.');
    }

    public function destroy(string $type, int $id, CmsMedia $media)
    {
        $item = $this->model($type)::findOrFail($id);
        if ($item->products()->exists()) {
            return back()->withErrors(['delete' => 'Data masih digunakan oleh produk. Pindahkan atau hapus produk terkait terlebih dahulu.']);
        }
        $path = $type === 'brands' ? $item->logo : null;
        $item->delete();
        $media->delete($path);

        return back()->with('status', 'Data berhasil dihapus.');
    }
}
