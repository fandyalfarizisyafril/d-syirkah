@extends('layouts.app')
@section('title', 'Brand & Principal')
@section('description', 'Temukan brand dan principal dalam katalog industrial equipment '.$company['name'].'.')
@section('content')
<x-page-heading eyebrow="Brand & Principal" title="Produk dari brand global." description="Kenali brand dalam portofolio produk kami dan temukan equipment yang sesuai dengan kebutuhan industri Anda." />
<section class="section"><div class="container brand-grid">@foreach($company['brands'] as $slug => $brand)<article class="brand-card">@if($brand['logo'])<img class="brand-logo" src="{{ asset($brand['logo']) }}" alt="Logo {{ $brand['name'] }}" width="160" height="80" loading="lazy">@endif<h2 class="brand-name">{{ $brand['name'] }}</h2><p>{{ $brand['description'] }}</p><a class="text-link" href="{{ route('brands.show', $slug) }}">Lihat Produk {{ $brand['name'] }} <x-icon name="arrow-up-right" /></a></article>@endforeach</div></section>
@include('partials.contact-cta')
@endsection
