@props(['product', 'slug'])
<article class="product-card">
    <a class="product-card-media" href="{{ route('products.show', $slug) }}" aria-label="Lihat gambar dan detail {{ $product['name'] }}">
        @if($product['image'] ?? null)
            <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" width="640" height="480" loading="lazy" decoding="async">
        @else
            <span class="product-card-placeholder"><x-icon :name="$product['icon']" /><span>Foto belum tersedia</span></span>
        @endif
    </a>
    <div class="product-card-body">
    <div class="product-card-top"><p class="small-label">{{ $product['group'] }}</p><span class="product-brand">{{ $product['brand'] }}</span></div>
    <h3><a href="{{ route('products.show', $slug) }}">{{ $product['name'] }}</a></h3>
    <p>{{ $product['summary'] }}</p>
    <x-product-image-note :image="$product['image'] ?? null" />
    <a class="text-link" href="{{ route('products.show', $slug) }}" aria-label="Detail {{ $product['name'] }}">Lihat Produk <x-icon name="arrow-up-right" /></a>
    </div>
</article>
