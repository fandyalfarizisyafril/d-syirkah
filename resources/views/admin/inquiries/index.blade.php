@extends('admin.layout')
@section('title', 'Inquiry')
@section('content')
<form method="get" class="admin-filters"><x-admin.field name="q" label="Nama, perusahaan, atau email" type="search" :value="request('q')" /><x-admin.field name="status" label="Status" type="select" :options="['' => 'Semua status'] + \App\Models\Inquiry::STATUSES" :value="request('status')" /><button class="a-button secondary"><x-icon name="search" />Cari</button><a href="{{ route('admin.inquiries.index') }}">Reset</a></form>
@include('admin.inquiries.table')
@include('admin.partials.pagination', ['items' => $inquiries])
@endsection
