<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150', 'company' => 'required|string|max:180',
            'email' => 'required|email|max:200', 'phone' => ['required', 'regex:/^\+?[0-9 ()-]{8,25}$/'],
            'product_id' => ['nullable', 'integer', Rule::exists('products', 'id')->where('published', true)],
            'requirements' => 'required|string|max:3000', 'message' => 'nullable|string|max:5000',
            'website' => 'nullable|string|max:0',
        ], [
            'required' => 'Kolom :attribute wajib diisi.', 'email.email' => 'Masukkan alamat email yang valid.',
            'phone.regex' => 'Masukkan nomor telepon yang valid.', 'max' => 'Kolom :attribute melebihi batas yang diizinkan.',
            'product_id.exists' => 'Produk yang dipilih tidak tersedia.', 'website.max' => 'Permintaan tidak dapat diproses.',
        ], ['name' => 'nama', 'company' => 'perusahaan', 'email' => 'email', 'phone' => 'telepon', 'requirements' => 'kebutuhan', 'message' => 'pesan']);
        unset($data['website']);
        $product = isset($data['product_id']) ? Product::with('brand')->find($data['product_id']) : null;
        $data['product_name'] = $product ? $product->name.' - '.$product->brand->name : null;
        Inquiry::create($data);

        return redirect()->route('contact')->with('inquiry_sent', 'Permintaan Anda sudah diterima. Tim kami akan menghubungi Anda melalui kontak yang diberikan.');
    }
}
