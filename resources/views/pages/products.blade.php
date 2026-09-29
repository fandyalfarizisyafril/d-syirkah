@extends('layouts.app')
@section('title', 'Produk & Solusi')
@section('description', 'Jelajahi motor, teknologi vibrasi, pompa, hose, air compressor, dan equipment oil spill response dari tujuh brand dalam katalog kami.')
@section('content')
<x-page-heading eyebrow="Katalog Produk" title="Produk & Solusi" description="Industrial equipment dan spare parts untuk kebutuhan penggerak, fluida, utilitas, serta perlindungan lingkungan." />
<section class="section catalog-section"><div class="container">
    <form class="catalog-filters" action="{{ route('products.index') }}" method="get" role="search">
        <div class="field search-field"><label for="product-search">Cari produk atau brand</label><div class="search-input"><x-icon name="search" /><input id="product-search" name="q" type="search" value="{{ $query }}" maxlength="100" placeholder="Motor, pompa, Wolong..."></div></div>
        <div class="field"><label for="category">Bidang produk</label><select name="category" id="category"><option value="">Semua bidang</option>@foreach(['Electrical', 'Mechanical', 'Fluid Handling', 'Environmental'] as $option)<option @selected($category === $option) value="{{ $option }}">{{ $option }}</option>@endforeach</select></div>
        <button class="button" type="submit"><x-icon name="search" />Cari</button>
        @if($query !== '' || $category !== '')<a class="reset-link" href="{{ route('products.index') }}">Reset</a>@endif
    </form>
    @if($errors->any())<div class="form-error" role="alert">{{ $errors->first() }}</div>@endif
    <div class="catalog-summary"><span>{{ $products->count() }} kategori produk</span><span>Equipment / Spare parts / Technical solutions</span></div>
    <h2 class="sr-only">Kategori produk</h2>
    @if($products->isNotEmpty())<div class="product-grid">@foreach($products as $slug => $product)<x-product-card :product="$product" :slug="$slug" />@endforeach</div>@else<div class="empty-state"><x-icon name="search-x" /><h2>Produk tidak ditemukan</h2><p>Tidak ada produk yang cocok dengan pencarian Anda.</p><a class="button" href="{{ route('products.index') }}">Lihat Semua Produk <x-icon name="arrow-right" /></a></div>@endif
</div></section>
@include('partials.contact-cta')
@endsection
