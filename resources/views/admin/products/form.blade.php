@extends('admin.layout')
@section('title', $product->exists ? 'Edit Produk' : 'Tambah Produk')
@section('actions')<a class="a-button secondary" href="{{ route('admin.products.index') }}"><x-icon name="arrow-left" />Daftar Produk</a>@endsection
@section('content')
@if($brands->isEmpty() || $categories->isEmpty())<div class="notice error">Tambahkan brand dan kategori sebelum menyimpan produk.</div>@endif
<form method="post" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
@csrf @if($product->exists) @method('PUT') @endif
<div class="editor-grid"><div>
    <section class="editor-section"><h2>Informasi Produk</h2><x-admin.field name="name" label="Nama produk" :value="$product->name" required maxlength="180" /><x-admin.field name="slug" label="Slug URL" :value="$product->slug" required pattern="[a-z0-9]+(-[a-z0-9]+)*" maxlength="180" />
    <div class="form-grid"><x-admin.field name="brand_id" label="Brand" type="select" :options="['' => 'Pilih brand'] + $brands->pluck('name', 'id')->all()" :value="$product->brand_id" required /><x-admin.field name="category_id" label="Kategori / Bidang" type="select" :options="['' => 'Pilih kategori'] + $categories->pluck('name', 'id')->all()" :value="$product->category_id" required /></div>
    <x-admin.field name="summary" label="Ringkasan" type="textarea" rows="2" :value="$product->summary" required maxlength="300" /><x-admin.field name="description" label="Deskripsi" type="textarea" rows="6" :value="$product->description" required maxlength="10000" /></section>
    <section class="editor-section"><h2>Aplikasi & Kebutuhan</h2>@foreach(['types' => 'Jenis produk (satu per baris)', 'applications' => 'Aplikasi (satu per baris)', 'requirements' => 'Parameter kebutuhan (satu per baris)'] as $field => $label)<x-admin.field :name="$field" :label="$label" type="textarea" rows="3" :value="implode(PHP_EOL, $product->$field ?? [])" />@endforeach</section>
    <section class="editor-section" data-repeater><div class="subheading"><h2>Spesifikasi Teknis</h2><button type="button" class="a-button secondary" data-add-row><x-icon name="plus" />Tambah Parameter</button></div>
    @php($specs = old('specifications', collect($product->specifications ?? [])->map(fn($value, $label) => compact('label', 'value'))->values()->all()))
    <div data-rows>@foreach($specs as $index => $row)@include('admin.partials.spec-row')@endforeach</div><template>@include('admin.partials.spec-row', ['index' => '__INDEX__', 'row' => []])</template></section>
</div><div>
    <section class="editor-section"><h2>Publikasi</h2><x-admin.toggle name="published" label="Tampilkan di website" :value="$product->published" /><x-admin.field name="icon" label="Ikon kategori" type="select" :options="array_combine($icons, $icons)" :value="$product->icon" /></section>
    <section class="editor-section"><h2>Media Produk</h2>@if($product->image)<img class="asset-preview" src="{{ asset($product->image) }}" alt="{{ $product->name }}"><x-admin.toggle name="remove_image" label="Hapus gambar saat disimpan" />@endif<x-admin.field name="image" label="Gambar JPG, PNG, WebP (maks. 2 MB)" type="file" accept="image/jpeg,image/png,image/webp" />
    @if($product->datasheet)<a class="asset-link" href="{{ asset($product->datasheet) }}"><x-icon name="download" />Datasheet saat ini</a><x-admin.toggle name="remove_datasheet" label="Hapus datasheet saat disimpan" />@endif<x-admin.field name="datasheet" label="Datasheet PDF (maks. 2 MB)" type="file" accept="application/pdf" /></section>
    @if($product->exists)<p class="muted">Terakhir disimpan {{ $product->updated_at->format('d M Y H:i') }}</p>@endif
</div></div>
<div class="form-actions"><button class="a-button" type="submit"><x-icon name="save" />Simpan Produk</button><a href="{{ route('admin.products.index') }}">Batal</a></div>
</form>
@endsection
