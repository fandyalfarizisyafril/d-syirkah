@extends('admin.layout')
@section('title', $title)
@section('content')
<form method="post" action="{{ route('admin.content.update', $section) }}">@csrf @method('PUT')
@if($section === 'company')
<div class="editor-grid"><section class="editor-section"><h2>Profil Perusahaan</h2>
@foreach(['name' => 'Nama perusahaan', 'tagline' => 'Tagline', 'description' => 'Deskripsi SEO', 'wordmark_top' => 'Nama logo baris atas', 'wordmark_bottom' => 'Nama logo baris bawah'] as $key => $label)<x-admin.field :name="'data['.$key.']'" :label="$label" :value="$data[$key] ?? ''" required />@endforeach
<x-admin.field name="data[profile]" label="Profil perusahaan" type="textarea" rows="8" :value="$data['profile'] ?? ''" required /></section>
<section class="editor-section"><h2>Kontak</h2><x-admin.field name="data[location]" label="Kota / Lokasi" :value="$data['location'] ?? ''" required /><x-admin.field name="data[address]" label="Alamat lengkap" type="textarea" :value="$data['address'] ?? ''" required /><x-admin.field name="data[phone]" label="Telepon (kode negara +62)" :value="$data['phone'] ?? ''" required /><x-admin.field name="data[emails][0]" label="Email penawaran" type="email" :value="$data['emails'][0] ?? ''" required /><x-admin.field name="data[emails][1]" label="Email administrasi" type="email" :value="$data['emails'][1] ?? ''" required /></section></div>
@else
<section data-repeater><div class="subheading"><h2>{{ $title }}</h2><button class="a-button secondary" type="button" data-add-row><x-icon name="plus" />Tambah Item</button></div><div data-rows>@foreach(old('items', $data) as $index => $row)@include('admin.partials.content-row')@endforeach</div><template>@include('admin.partials.content-row', ['index' => '__INDEX__', 'row' => []])</template></section>
@endif
<div class="form-actions"><button class="a-button"><x-icon name="save" />Simpan Perubahan</button></div>
</form>
@endsection
