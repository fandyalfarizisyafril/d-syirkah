@extends('layouts.app')
@section('title', 'Brand & Principal')
@section('description', 'Temukan kategori produk Wolong, OLI, Qdos, Bredel, Aflex, Tecbell, dan BLU-C di PT. Syirkah Mandiri Artomoro.')
@section('content')
<x-page-heading eyebrow="Brand & Principal" title="Produk dari brand global." description="Kenali brand dalam portofolio produk kami dan temukan equipment yang sesuai dengan kebutuhan industri Anda." />
<section class="section"><div class="container brand-grid">@foreach($company['products'] as $product)<article class="brand-card"><span class="brand-name">{{ $product['brand'] }}</span><h2>{{ $product['name'] }}</h2><p>{{ $product['summary'] }}</p><a class="text-link" href="{{ route('brands.show', $product['brand_slug']) }}">Lihat Produk {{ $product['brand'] }} <x-icon name="arrow-up-right" /></a></article>@endforeach</div></section>
@include('partials.contact-cta')
@endsection
