<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SiteContent;
use App\Services\WebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    public const SECTIONS = ['company' => 'Profil & Kontak', 'focus' => 'Bidang Fokus', 'values' => 'Nilai Perusahaan', 'solutions' => 'Solusi Industri', 'legal' => 'Legalitas'];

    public function edit(string $section)
    {
        abort_unless(isset(self::SECTIONS[$section]), 404);

        return view('admin.content', ['section' => $section, 'title' => self::SECTIONS[$section], 'data' => SiteContent::find($section)?->data ?? [], 'products' => Product::orderBy('name')->get(), 'icons' => WebsiteContent::ICONS]);
    }

    public function update(Request $request, string $section)
    {
        abort_unless(isset(self::SECTIONS[$section]), 404);
        if ($section === 'company') {
            $data = $request->validate([
                'data.name' => 'required|string|max:180', 'data.tagline' => 'required|string|max:200',
                'data.description' => 'required|string|max:300', 'data.profile' => 'required|string|max:5000',
                'data.address' => 'required|string|max:500', 'data.phone' => ['required', 'regex:/^\+?[0-9 ()-]{8,25}$/'],
                'data.emails' => 'required|array|size:2', 'data.emails.*' => 'required|email|max:200',
                'data.wordmark_top' => 'required|string|max:30', 'data.wordmark_bottom' => 'required|string|max:25', 'data.location' => 'required|string|max:100',
            ])['data'];
            $data['phone_uri'] = '+'.preg_replace('/\D/', '', $data['phone']);
        } else {
            $rules = ['items' => 'nullable|array|max:30', 'items.*.name' => 'required|string|max:120'];
            if ($section === 'legal') {
                $rules += ['items.*.value' => 'nullable|string|max:300', 'items.*.public' => 'required|boolean'];
            } else {
                $rules['items.*.description'] = 'required|string|max:3000';
            }
            if (in_array($section, ['focus', 'solutions'])) {
                $rules['items.*.icon'] = ['required', Rule::in(WebsiteContent::ICONS)];
            }
            if ($section === 'solutions') {
                $rules += ['items.*.product_ids' => 'nullable|array|max:100', 'items.*.product_ids.*' => 'integer|exists:products,id'];
            }
            $data = array_values($request->validate($rules)['items'] ?? []);
            foreach ($data as &$row) {
                if ($section === 'legal') {
                    $row['public'] = (bool) $row['public'];
                }
                if ($section === 'solutions') {
                    $row['product_ids'] = array_values(array_unique(array_map('intval', $row['product_ids'] ?? [])));
                }
            }
            unset($row);
        }
        SiteContent::updateOrCreate(['key' => $section], ['data' => $data, 'updated_by' => $request->user()->id]);

        return back()->with('status', 'Konten berhasil disimpan.');
    }
}
