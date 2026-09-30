@php
    $company = $company ?? app(\App\Services\WebsiteContent::class)->get();
    $pageTitle = trim($__env->yieldContent('title', $company['name']));
    $description = trim($__env->yieldContent('description', $company['description']));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}{{ $pageTitle !== $company['name'] ? ' | '.$company['name'] : '' }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="theme-color" content="{{ request()->routeIs('home') ? '#0b192e' : '#202d31' }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $company['name'] }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/industrial-facility.jpg') }}">
    @if(request()->hasAny(['q', 'category', 'product']) || request()->route() === null || $__env->hasSection('noindex'))
        <meta name="robots" content="noindex,follow">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org', '@type' => 'Organization', 'name' => $company['name'],
        'url' => route('home'), 'telephone' => $company['phone_uri'], 'email' => $company['emails'][0],
        'address' => $company['address'],
    ], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
</head>
<body class="@yield('body_class')">
    <a class="skip-link" href="#main">Langsung ke konten</a>
    @include('partials.header')
    <main id="main" tabindex="-1">@yield('content')</main>
    @include('partials.footer')
</body>
</html>
