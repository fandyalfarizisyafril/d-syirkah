@props(['image'])
@php($reference = collect(config('product_images'))->firstWhere('image', $image))
@if($image && $reference)
    <p class="product-image-note">{{ $reference['caption'] ?? 'Foto referensi. Model dikonfirmasi sesuai kebutuhan.' }}</p>
@endif
