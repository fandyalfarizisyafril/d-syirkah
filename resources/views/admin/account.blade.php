@extends('admin.layout')
@section('title', 'Akun Saya')
@section('content')
<section class="editor-section narrow-form"><h2>{{ auth()->user()->name }}</h2><p class="muted">{{ auth()->user()->email }}</p><form method="post" action="{{ route('admin.password') }}">@csrf @method('PUT')<x-admin.field name="current_password" label="Password saat ini" type="password" autocomplete="current-password" required /><x-admin.field name="password" label="Password baru (minimal 12 karakter, huruf dan angka)" type="password" autocomplete="new-password" required minlength="12" /><x-admin.field name="password_confirmation" label="Konfirmasi password baru" type="password" autocomplete="new-password" required minlength="12" /><button class="a-button"><x-icon name="save" />Perbarui Password</button></form></section>
@endsection
