@unless(request()->routeIs('home'))
<div class="utility-bar">
    <div class="container utility-inner"><span>{{ $company['location'] ?? 'Pekanbaru, Riau' }} <span class="utility-divider">/</span> Industrial Supply & Technical Solutions</span><a href="tel:{{ $company['phone_uri'] }}"><x-icon name="phone" />{{ $company['phone'] }}</a></div>
</div>
@endunless
<header class="site-header">
    <div class="container header-inner">
        <a class="wordmark" href="{{ route('home') }}" aria-label="{{ $company['name'] }} - Beranda"><span class="brand-mark" aria-hidden="true">@if(request()->routeIs('home'))<x-icon name="factory" />@else SA<span></span>@endif</span><span>{{ $company['wordmark_top'] ?? 'SYIRKAH MANDIRI' }}<strong>{{ $company['wordmark_bottom'] ?? 'ARTOMORO' }}</strong></span></a>
        <nav class="main-nav" id="main-navigation" aria-label="Navigasi utama">
            @foreach(['home' => 'Beranda', 'about' => 'Tentang Kami', 'products.index' => 'Produk', 'brands.index' => 'Brand', 'solutions' => 'Solusi', 'contact' => 'Kontak'] as $name => $label)
                @php($active = request()->routeIs(str_replace('.index', '.*', $name)))
                <a href="{{ route($name) }}" @if($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </nav>
        @if(request()->routeIs('home'))
            <x-round-contact />
        @else
            <a class="button button-small header-cta" href="{{ route('contact') }}">Hubungi Kami <x-icon name="arrow-up-right" /></a>
        @endif
        <button class="menu-toggle icon-button" type="button" aria-expanded="false" aria-controls="main-navigation" aria-label="Buka navigasi" title="Buka navigasi"><x-icon name="menu" /></button>
    </div>
</header>
