@props(['product', 'slug'])
<article class="product-card">
    <div class="product-card-top"><span class="product-symbol"><x-icon :name="$product['icon']" /></span><span class="product-brand">{{ $product['brand'] }}</span></div>
    <p class="small-label">{{ $product['group'] }}</p>
    <h3><a href="{{ route('products.show', $slug) }}">{{ $product['name'] }}</a></h3>
    <p>{{ $product['summary'] }}</p>
    <a class="text-link" href="{{ route('products.show', $slug) }}" aria-label="Detail {{ $product['name'] }}">Lihat Produk <x-icon name="arrow-up-right" /></a>
</article>
