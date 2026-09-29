@extends('layouts.app')
@section('title', 'Solusi Industri')
@section('description', 'Solusi penggerak, fluid handling, utilitas industri, dan oil spill response untuk mendukung keberlangsungan operasi industri.')
@section('content')
<x-page-heading eyebrow="Solusi Industri" title="Berawal dari kebutuhan operasi." description="Dari pemilihan equipment hingga kebutuhan pengadaan, kami membantu menghubungkan kebutuhan teknis Anda dengan kategori produk yang relevan." />
<section class="section"><div class="container solution-list">@foreach($company['solutions'] as $solution)<article class="solution-row"><span class="index">0{{ $loop->iteration }}</span><div><x-icon :name="$solution['icon']" /><h2>{{ $solution['name'] }}</h2><p>{{ $solution['description'] }}</p></div><div class="solution-products">@foreach($solution['products'] as $slug)<a href="{{ route('products.show', $slug) }}"><span>{{ $company['products'][$slug]['name'] }}<small>{{ $company['products'][$slug]['brand'] }}</small></span><x-icon name="arrow-up-right" /></a>@endforeach</div></article>@endforeach</div></section>
<section class="section section-soft"><div class="container"><p class="eyebrow">Our Value</p><h2>Fokus pada hasil operasional.</h2><div class="value-grid">@foreach($company['values'] as $value)<div><h3>{{ $value['name'] }}</h3><p>{{ $value['description'] }}</p></div>@endforeach</div></div></section>
@include('partials.contact-cta')
@endsection
