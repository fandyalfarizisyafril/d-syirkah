@extends('admin.layout')
@section('title', 'Dashboard')
@section('actions')<a class="a-button" href="{{ route('admin.products.create') }}"><x-icon name="plus" />Tambah Produk</a>@endsection
@section('content')
<div class="admin-stats">
    <a href="{{ route('admin.products.index') }}"><span>Total Produk</span><strong>{{ $productCount }}</strong><small>{{ $publishedCount }} dipublikasikan</small></a>
    <a href="{{ route('admin.products.index', ['state' => 'draft']) }}"><span>Draft Produk</span><strong>{{ $productCount - $publishedCount }}</strong><small>Belum ditampilkan publik</small></a>
    <a href="{{ route('admin.taxonomies.index', 'brands') }}"><span>Brand & Principal</span><strong>{{ $brandCount }}</strong><small>Dalam portofolio</small></a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'new']) }}"><span>Inquiry Baru</span><strong>{{ $newCount }}</strong><small>Menunggu tindak lanjut</small></a>
</div>
<div class="subheading"><h2>Inquiry Terbaru</h2><a href="{{ route('admin.inquiries.index') }}">Lihat semua <x-icon name="arrow-right" /></a></div>
@include('admin.inquiries.table', ['inquiries' => $recent])
@endsection
