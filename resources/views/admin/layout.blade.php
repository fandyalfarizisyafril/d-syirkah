<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', 'Dashboard') | Admin Artomoro</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>

<body class="admin-body">
    <a class="skip-link" href="#admin-main">Langsung ke konten</a>
    <aside class="admin-sidebar">
        <a class="admin-brand" href="{{ route('admin.dashboard') }}"><span>SA</span><strong>ARTOMORO<small>ADMINISTRASI WEBSITE</small></strong></a>
        <nav aria-label="Navigasi admin">
            @foreach([
            ['admin.dashboard', [], 'layout-dashboard', 'Dashboard'],
            ['admin.products.index', [], 'package', 'Produk'],
            ['admin.taxonomies.index', ['type' => 'brands'], 'tags', 'Brand & Principal'],
            ['admin.taxonomies.index', ['type' => 'categories'], 'layers', 'Kategori'],
            ['admin.inquiries.index', [], 'inbox', 'Inquiry'],
            ['admin.content.edit', ['section' => 'company'], 'building-2', 'Profil & Kontak'],
            ['admin.content.edit', ['section' => 'focus'], 'gauge', 'Bidang Fokus'],
            ['admin.content.edit', ['section' => 'values'], 'shield-check', 'Nilai Perusahaan'],
            ['admin.content.edit', ['section' => 'solutions'], 'cog', 'Solusi Industri'],
            ['admin.content.edit', ['section' => 'legal'], 'file-check', 'Legalitas'],
            ['admin.account', [], 'key-round', 'Akun Saya'],
            ] as [$route, $params, $icon, $label])
            @php($active = url()->current() === route($route, $params) || ($route === 'admin.products.index' && request()->routeIs('admin.products.*')) || ($route === 'admin.inquiries.index' && request()->routeIs('admin.inquiries.*')) || (isset($params['type']) && request()->route('type') === $params['type']))
            <a href="{{ route($route, $params) }}" @if($active) aria-current="page" @endif><x-icon :name="$icon" />{{ $label }}</a>
            @endforeach
        </nav>
    </aside>
    <div class="admin-workspace">
        <header class="admin-topbar"><a href="{{ route('home') }}" target="_blank" rel="noopener">Lihat Website <x-icon name="external-link" /></a>
            <div><span>{{ auth()->user()->name }}</span>
                <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="a-icon" title="Keluar" aria-label="Keluar"><x-icon name="log-out" /></button></form>
            </div>
        </header>
        <main id="admin-main" class="admin-main">
            <div class="admin-heading">
                <h1>@yield('title', 'Dashboard')</h1>
                <div>@yield('actions')</div>
            </div>
            @if(session('status'))<div class="notice success" role="status"><x-icon name="check" />{{ session('status') }}</div>@endif
            @if($errors->any())<div class="notice error" role="alert"><strong>Periksa kembali data Anda.</strong>
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>@endif
            @yield('content')
        </main>
    </div>
</body>

</html>