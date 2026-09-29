@extends('layouts.app')
@section('body_class', 'homepage')
@section('content')
@php
    $brandCount = collect($company['products'])->pluck('brand_slug')->unique()->count();
    $slides = [
        ['name' => 'Fokus Kami', 'description' => $company['profile']],
        ...$company['values'],
    ];
    $services = [
        ['title' => 'Engineering Solutions', 'description' => 'Dukungan pemilihan equipment dan solusi teknis yang sesuai dengan kebutuhan aplikasi serta spesifikasi Anda.', 'image' => 'engineering-planning.jpg', 'alt' => 'Ilustrasi tim engineering meninjau gambar teknis di lapangan', 'icon' => 'drafting-compass', 'url' => route('solutions')],
        ['title' => 'Industrial Equipment', 'description' => 'Motor, pompa, hose, teknologi vibrasi, dan air compressor untuk mendukung keberlangsungan operasi industri.', 'image' => 'industrial-facility.jpg', 'alt' => 'Ilustrasi equipment pada fasilitas produksi industri', 'icon' => 'factory', 'url' => route('products.index')],
        ['title' => 'Spare Parts & Pengadaan', 'description' => 'Menghubungkan kebutuhan equipment dan spare parts Anda dengan produk dari global OEM dan principal.', 'image' => 'engineering-partnership.jpg', 'alt' => 'Ilustrasi koordinasi dua tenaga profesional di lokasi proyek', 'icon' => 'handshake', 'url' => route('contact')],
    ];
@endphp
<x-ruler-divider />

<section class="design-hero">
    <div class="container">
        <p class="eyebrow">{{ $company['name'] }}</p>
        <div class="design-hero-heading">
            <h1>Industrial Supply &<br><span>Technical Solutions</span></h1>
            <div class="design-hero-description">
                <p>Equipment, spare parts, dan solusi engineering terintegrasi untuk mendukung kebutuhan operasional industri Anda.</p>
                <a class="text-link" href="{{ route('products.index') }}">Jelajahi Produk <x-icon name="arrow-up-right" /></a>
            </div>
        </div>
        <div class="service-pills" aria-label="Bidang fokus perusahaan">
            @foreach($company['focus'] as $focus)
                <a href="{{ route('about') }}#focus">{{ str_replace(' & Prevention', '', $focus['name']) }}</a>
            @endforeach
        </div>

        <div class="hero-showcase">
            <div class="showcase-backdrop" aria-hidden="true"><span>+</span><span>+</span></div>
            <figure class="showcase-photo">
                <img src="{{ asset('images/engineering-team.jpg') }}" alt="Ilustrasi tim engineer meninjau gambar teknis di lokasi proyek" width="512" height="279" fetchpriority="high">
                <figcaption>Engineering & integrated technical solutions <span>Foto ilustrasi</span></figcaption>
            </figure>
            <dl class="showcase-statistics" aria-label="Cakupan produk dan bidang perusahaan">
                <div><dt>Kategori Produk</dt><dd>{{ str_pad(count($company['products']), 2, '0', STR_PAD_LEFT) }}</dd></div>
                <div><dt>Brand & Principal</dt><dd>{{ str_pad($brandCount, 2, '0', STR_PAD_LEFT) }}</dd></div>
                <div><dt>Bidang Fokus</dt><dd>{{ str_pad(count($company['focus']), 2, '0', STR_PAD_LEFT) }}</dd></div>
            </dl>
        </div>
    </div>
</section>

<x-ruler-divider />

<section class="design-section design-about" id="about">
    <div class="container">
        <div class="about-heading">
            <div>
                <p class="eyebrow">Tentang Perusahaan</p>
                <h2><span>Partner yang Tepat</span><br>untuk Kebutuhan Industri</h2>
                <p class="about-description">PT. Syirkah Mandiri Artomoro hadir untuk kebutuhan industrial equipment, spare parts, dan engineering solutions di Indonesia.</p>
            </div>
            <div class="about-heading-action">
                @include('partials.crane-decoration')
                <a class="button" href="{{ route('about') }}">Kenali Kami <span class="button-arrow"><x-icon name="arrow-right" /></span></a>
            </div>
        </div>
        <div class="about-showcase">
            <figure class="about-photo">
                <img src="{{ asset('images/engineering-partnership.jpg') }}" alt="Ilustrasi dua tenaga profesional berkoordinasi di lokasi proyek" width="512" height="279" loading="lazy">
                <figcaption class="focus-badge"><strong>{{ count($company['focus']) }}</strong><span>Bidang Fokus<br>Terintegrasi</span></figcaption>
                <span class="photo-credit">Foto ilustrasi</span>
            </figure>
            <section class="mission-card" aria-label="Fokus dan nilai perusahaan" aria-roledescription="carousel" data-values-carousel>
                <div class="mission-slides" aria-live="polite" aria-atomic="true">
                    @foreach($slides as $slide)
                        <div class="mission-slide" data-value-slide role="group" aria-label="{{ $loop->iteration }} dari {{ count($slides) }}">
                            <p>{{ $slide['description'] }}</p>
                            <h3>{{ $slide['name'] }}</h3>
                        </div>
                    @endforeach
                </div>
                <div class="mission-controls" data-carousel-controls hidden>
                    <span data-carousel-status></span>
                    <div>
                        <button type="button" class="icon-button" data-previous aria-label="Nilai perusahaan sebelumnya" title="Nilai perusahaan sebelumnya"><x-icon name="arrow-left" /></button>
                        <button type="button" class="icon-button" data-next aria-label="Nilai perusahaan berikutnya" title="Nilai perusahaan berikutnya"><x-icon name="arrow-right" /></button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>

<x-ruler-divider />

<section class="design-section design-services" id="services">
    <div class="container">
        <div class="centered-heading">
            <p class="eyebrow">Produk & Solusi</p>
            <h2>Solusi yang Sesuai<br><span>Kebutuhan Anda</span></h2>
        </div>
        <div class="service-grid">
            @foreach($services as $service)
                <article class="service-card">
                    <div class="service-photo">
                        <img src="{{ asset('images/'.$service['image']) }}" alt="{{ $service['alt'] }}" width="512" height="320" loading="lazy">
                        <span class="service-icon"><x-icon :name="$service['icon']" /></span>
                    </div>
                    <div class="service-copy">
                        <h3><a href="{{ $service['url'] }}">{{ $service['title'] }}</a></h3>
                        <p>{{ $service['description'] }}</p>
                        <a class="text-link" href="{{ $service['url'] }}" aria-label="Selengkapnya tentang {{ $service['title'] }}">Selengkapnya <x-icon name="arrow-right" /></a>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="services-action"><a class="button" href="{{ route('products.index') }}">Lihat Semua Produk <span class="button-arrow"><x-icon name="arrow-right" /></span></a></div>
        <nav class="home-category-links" aria-label="Kategori produk">
            @foreach($company['products'] as $slug => $product)
                <a href="{{ route('products.show', $slug) }}">{{ $product['name'] }} <x-icon name="arrow-up-right" /></a>
            @endforeach
        </nav>
    </div>
</section>

<x-ruler-divider />

<section class="design-section design-brands" id="brands">
    <div class="container">
        <div class="brand-heading"><p class="eyebrow">Brand & Principal</p><a class="text-link" href="{{ route('brands.index') }}">Kenali Brand <x-icon name="arrow-up-right" /></a></div>
        <h2 class="sr-only">Brand dalam portofolio produk</h2>
        <div class="brand-row">
            @foreach($company['products'] as $product)
                <a href="{{ route('brands.show', $product['brand_slug']) }}">{{ $product['brand'] }}</a>
            @endforeach
        </div>
    </div>
</section>

<x-ruler-divider />

<section class="design-section process-teaser" id="process">
    <div class="container">
        <div><p class="eyebrow">Kebutuhan & Solusi</p><h2>Mari Diskusikan<br><span>Kebutuhan Anda</span></h2></div>
        <a class="button" href="{{ route('contact') }}">Hubungi Kami <span class="button-arrow"><x-icon name="arrow-right" /></span></a>
    </div>
</section>
@endsection
