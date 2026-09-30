@extends('layouts.app')
@section('title', 'Tentang Kami')
@section('description', $company['description'])
@section('content')
<x-page-heading eyebrow="Tentang Perusahaan" :title="$company['name']" :description="$company['tagline']" />
<section class="section"><div class="container intro-grid"><div><p class="eyebrow">Company Profile</p><h2>Partner pengadaan<br>dan solusi teknis.</h2></div><div><p class="lead">{{ $company['profile'] }}</p><p>Kami berfokus pada penyediaan industrial equipment dan dukungan kebutuhan teknis. Setiap kebutuhan dimulai dari pemahaman aplikasi, kondisi operasi, dan spesifikasi yang diperlukan.</p><a class="text-link" href="{{ route('products.index') }}">Lihat Produk & Solusi <x-icon name="arrow-up-right" /></a></div></div></section>
<section class="section section-soft" id="focus"><div class="container"><p class="eyebrow">Our Focus</p><h2>Bidang fokus perusahaan.</h2><div class="focus-grid">@foreach($company['focus'] as $focus)<article><x-icon :name="$focus['icon']" /><h3>{{ $focus['name'] }}</h3><p>{{ $focus['description'] }}</p></article>@endforeach</div></div></section>
<section class="section"><div class="container"><p class="eyebrow">Our Value</p><h2>Yang menjadi prioritas kami.</h2><div class="value-grid">@foreach($company['values'] as $value)<div><span class="index">0{{ $loop->iteration }}</span><h3>{{ $value['name'] }}</h3><p>{{ $value['description'] }}</p></div>@endforeach</div></div></section>
<section class="section section-soft" id="legal"><div class="container intro-grid"><div><p class="eyebrow">Informasi Legal</p><h2>Administrasi perusahaan.</h2></div><div><p>Untuk kebutuhan verifikasi vendor dan administrasi pengadaan, hubungi tim kami mengenai dokumen perusahaan.</p><dl class="spec-list">@foreach($company['legal'] as $document)<div><dt>{{ $document['name'] }}</dt><dd>{{ $document['public'] && filled($document['value']) ? $document['value'] : 'Hubungi tim administrasi' }}</dd></div>@endforeach</dl><a class="text-link" href="mailto:{{ $company['emails'][1] }}">Hubungi Administrasi <x-icon name="arrow-up-right" /></a></div></div></section>
@include('partials.contact-cta')
@endsection
