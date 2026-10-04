<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\ProductImageSeeder;
use Database\Seeders\WebsiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_has_local_reference_images_with_a_clear_illustration_note(): void
    {
        $this->withoutVite();
        $this->seed(WebsiteContentSeeder::class);
        $response = $this->get('/products')->assertOk();
        foreach (Product::all() as $product) {
            $this->assertFileExists(public_path($product->image));
            $response->assertSee(asset($product->image), false);
        }
        $response->assertSee('loading="lazy"', false)->assertSee('Ilustrasi oil boom, bukan foto produk BLU-C.');
        $this->get('/products/oil-spill-response')->assertSee('Ilustrasi oil boom, bukan foto produk BLU-C.');
    }

    public function test_reference_seeder_fills_only_empty_matching_products_and_cms_images_take_precedence(): void
    {
        $this->withoutVite();
        $this->seed(WebsiteContentSeeder::class);
        $motor = Product::where('slug', 'electric-motors-generators')->firstOrFail();
        $motor->update(['image' => 'media/custom-owner-photo.jpg']);
        $pump = Product::where('slug', 'hose-pump')->firstOrFail();
        $pump->update(['image' => null]);
        $this->seed(ProductImageSeeder::class);
        $this->assertSame('media/custom-owner-photo.jpg', $motor->fresh()->image);
        $this->assertSame(config('product_images.hose-pump.image'), $pump->fresh()->image);
        $this->get('/products?q=Wolong')->assertSee('media/custom-owner-photo.jpg')->assertDontSee('Foto referensi.');
        $motor->update(['image' => null]);
        $this->get('/products?q=Wolong')->assertSee('Foto belum tersedia')->assertDontSee('wolong-motor.png');
        $motor->update(['brand_id' => $pump->brand_id]);
        $this->seed(ProductImageSeeder::class);
        $this->assertNull($motor->fresh()->image);
    }
}
