@extends('layouts.app')
@section('title', 'Halaman Tidak Ditemukan')
@section('noindex', 'true')
@section('content')
<section class="section"><div class="container empty-state"><p class="eyebrow">404 / Halaman Tidak Ditemukan</p><h1>Halaman ini tidak tersedia.</h1><p>Temukan produk yang Anda butuhkan melalui katalog kami.</p><div class="actions"><a class="button" href="{{ route('products.index') }}">Katalog Produk <x-icon name="arrow-right" /></a><a class="button button-outline" href="{{ route('home') }}">Kembali ke Beranda</a></div></div></section>
@endsection
