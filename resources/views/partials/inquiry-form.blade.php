@if($company['cms_ready'])
<section class="section section-soft" id="inquiry"><div class="container public-inquiry"><p class="eyebrow">Inquiry Produk</p><h2>Kirim kebutuhan Anda.</h2>
@if(session('inquiry_sent'))<div class="inquiry-success" role="status">{{ session('inquiry_sent') }}</div>@endif
@if($errors->any())<div class="form-error" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="post" action="{{ route('inquiry.store') }}">@csrf
<div class="inquiry-fields"><x-admin.field name="name" label="Nama" required maxlength="150" autocomplete="name" /><x-admin.field name="company" label="Perusahaan" required maxlength="180" autocomplete="organization" /><x-admin.field name="email" label="Email" type="email" required maxlength="200" autocomplete="email" /><x-admin.field name="phone" label="Nomor telepon" type="tel" required maxlength="25" autocomplete="tel" /></div>
<x-admin.field name="product_id" label="Produk" type="select" :options="['' => 'Kebutuhan umum'] + collect($company['products'])->mapWithKeys(fn($item) => [$item['id'] => $item['name'].' - '.$item['brand']])->all()" :value="$product['id'] ?? ''" />
<x-admin.field name="requirements" label="Kebutuhan / Spesifikasi" type="textarea" rows="4" required maxlength="3000" /><x-admin.field name="message" label="Pesan tambahan" type="textarea" rows="3" maxlength="5000" />
<div class="inquiry-trap" aria-hidden="true"><label for="inquiry-website">Website</label><input id="inquiry-website" name="website" tabindex="-1" autocomplete="off"></div>
<button class="button" type="submit">Kirim Inquiry <x-icon name="send" /></button></form></div></section>
@endif
