<footer class="site-footer" id="contact">
    <div class="container footer-grid">
        <div><a class="wordmark footer-wordmark" href="{{ route('home') }}"><span class="brand-mark" aria-hidden="true"><x-icon name="factory" /></span><span>{{ $company['wordmark_top'] ?? 'SYIRKAH MANDIRI' }}<strong>{{ $company['wordmark_bottom'] ?? 'ARTOMORO' }}</strong></span></a><p>{{ $company['tagline'] }}</p><span class="footer-label">General Supplier & Technical Solutions</span></div>
        <div><h2>Perusahaan</h2><a href="{{ route('about') }}">Tentang Kami</a><a href="{{ route('brands.index') }}">Brand & Principal</a><a href="{{ route('solutions') }}">Solusi Industri</a><a href="{{ route('about') }}#legal">Informasi Legal</a></div>
        <div><h2>Produk & Kontak</h2><a href="{{ route('products.index') }}">Katalog Produk</a><a href="{{ route('contact') }}">Permintaan Penawaran</a><a href="tel:{{ $company['phone_uri'] }}">{{ $company['phone'] }}</a>@foreach($company['emails'] as $email)<a href="mailto:{{ $email }}">{{ $email }}</a>@endforeach</div>
        <div><h2>{{ $company['location'] ?? 'Pekanbaru, Indonesia' }}</h2><address>{{ $company['address'] }}</address></div>
    </div>
    <div class="container footer-bottom"><span>&copy; 2026 Fandy Alfarizi Syafril.</span><span>Engineering. Supply. Solutions.</span></div>
</footer>
