@extends('layouts.app')
@section('title', $brand.' - Brand & Principal')
@section('description', 'Kategori produk '.$brand.' untuk kebutuhan industrial equipment melalui PT. Syirkah Mandiri Artomoro.')
@section('content')
<x-page-heading eyebrow="Brand & Principal" :title="$brand" :description="$products->first()['summary']" />
<section class="section"><div class="container"><div class="section-heading"><h2>Produk {{ $brand }}</h2><a class="text-link" href="{{ route('brands.index') }}"><x-icon name="arrow-left" />Semua Brand</a></div><div class="product-grid">@foreach($products as $slug => $product)<x-product-card :product="$product" :slug="$slug" />@endforeach</div></div></section>
@include('partials.contact-cta')
@endsection
