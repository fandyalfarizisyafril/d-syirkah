<footer class="site-footer" id="contact">
    <div class="container footer-grid">
        <div><a class="wordmark footer-wordmark" href="{{ route('home') }}"><span class="brand-mark" aria-hidden="true">@if(request()->routeIs('home'))<x-icon name="factory" />@else SA<span></span>@endif</span><span>SYIRKAH MANDIRI<strong>ARTOMORO</strong></span></a><p>{{ $company['tagline'] }}</p><span class="footer-label">General Supplier & Technical Solutions</span></div>
        <div><h2>Perusahaan</h2><a href="{{ route('about') }}">Tentang Kami</a><a href="{{ route('brands.index') }}">Brand & Principal</a><a href="{{ route('solutions') }}">Solusi Industri</a><a href="{{ route('about') }}#legal">Informasi Legal</a></div>
        <div><h2>Produk & Kontak</h2><a href="{{ route('products.index') }}">Katalog Produk</a><a href="{{ route('contact') }}">Permintaan Penawaran</a><a href="tel:{{ $company['phone_uri'] }}">{{ $company['phone'] }}</a>@foreach($company['emails'] as $email)<a href="mailto:{{ $email }}">{{ $email }}</a>@endforeach</div>
        <div><h2>Pekanbaru, Indonesia</h2><address>{{ $company['address'] }}</address></div>
    </div>
    <div class="container footer-bottom"><span>&copy; {{ date('Y') }} {{ $company['name'] }}</span><span>Engineering. Supply. Solutions.</span></div>
</footer>
