@extends('admin.layout')
@section('title', ($item->exists ? 'Edit ' : 'Tambah ').($type === 'brands' ? 'Brand' : 'Kategori'))
@section('content')
<form class="narrow-form" method="post" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.taxonomies.update', [$type, $item->id]) : route('admin.taxonomies.store', $type) }}">@csrf @if($item->exists) @method('PUT') @endif
<x-admin.field name="name" label="Nama" :value="$item->name" required maxlength="120" /><x-admin.field name="slug" label="Slug URL" :value="$item->slug" required pattern="[a-z0-9]+(-[a-z0-9]+)*" maxlength="120" />
@if($type === 'brands')<x-admin.field name="description" label="Deskripsi" type="textarea" :value="$item->description" maxlength="3000" />@if($item->logo)<img class="asset-preview" src="{{ asset($item->logo) }}" alt="Logo {{ $item->name }}"><x-admin.toggle name="remove_logo" label="Hapus logo saat disimpan" />@endif<x-admin.field name="logo" label="Logo JPG, PNG, WebP (maks. 2 MB)" type="file" accept="image/jpeg,image/png,image/webp" />@endif
<div class="form-actions"><button class="a-button"><x-icon name="save" />Simpan</button><a href="{{ route('admin.taxonomies.index', $type) }}">Batal</a></div></form>
@endsection
