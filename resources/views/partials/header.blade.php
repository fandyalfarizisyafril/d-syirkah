<header class="site-header">
    <div class="container header-inner">
        <a class="wordmark" href="{{ route('home') }}" aria-label="{{ $company['name'] }} - Beranda"><span class="brand-mark" aria-hidden="true"><x-icon name="factory" /></span><span>{{ $company['wordmark_top'] ?? 'SYIRKAH MANDIRI' }}<strong>{{ $company['wordmark_bottom'] ?? 'ARTOMORO' }}</strong></span></a>
        <nav class="main-nav" id="main-navigation" aria-label="Navigasi utama">
            @foreach(['home' => 'Beranda', 'about' => 'Tentang Kami', 'products.index' => 'Produk', 'brands.index' => 'Brand', 'solutions' => 'Solusi', 'contact' => 'Kontak'] as $name => $label)
                @php($active = request()->routeIs(str_replace('.index', '.*', $name)))
                <a href="{{ route($name) }}" @if($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </nav>
        <x-round-contact />
        <button class="menu-toggle icon-button" type="button" aria-expanded="false" aria-controls="main-navigation" aria-label="Buka navigasi" title="Buka navigasi"><x-icon name="menu" /></button>
    </div>
</header>
<x-ruler-divider />
