<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Masuk Admin | Artomoro</title>@vite(['resources/css/admin.css', 'resources/js/admin.js'])</head>
<body class="admin-login"><main class="login-panel"><a class="admin-brand" href="{{ route('home') }}"><span>SA</span><strong>ARTOMORO<small>ADMINISTRASI WEBSITE</small></strong></a><h1>Masuk Admin</h1>
@if($errors->any())<div class="notice error" role="alert">{{ $errors->first() }}</div>@endif
<form method="post" action="{{ route('admin.authenticate') }}">@csrf
    <x-admin.field name="email" label="Email" type="email" autocomplete="username" required autofocus />
    <x-admin.field name="password" label="Password" type="password" autocomplete="current-password" required />
    <button class="a-button" type="submit"><x-icon name="log-in" />Masuk</button>
</form><a class="back-link" href="{{ route('home') }}"><x-icon name="arrow-left" />Kembali ke website</a></main></body></html>
