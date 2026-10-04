<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public static function publicPages(): array
    {
        return array_map(fn ($path) => [$path], ['/', '/about', '/products', '/brands', '/solutions', '/contact']);
    }

    #[DataProvider('publicPages')]
    public function test_public_pages_render_with_metadata_and_company_contact(string $path): void
    {
        $this->get($path)->assertOk()->assertSee('name="description"', false)
            ->assertSee('rel="canonical"', false)->assertSee('doni.rahmat@smartomoro.com')
            ->assertDontSee('ApexBuild')->assertDontSee('Lorem ipsum');
    }

    public function test_all_catalog_details_and_brand_links_are_reachable(): void
    {
        foreach (config('company.products') as $slug => $product) {
            $this->get('/products/'.$slug)->assertOk()->assertSee($product['name'])
                ->assertSee(route('contact', ['product' => $slug]));
            $this->get('/brands/'.$product['brand_slug'])->assertOk()->assertSee($product['brand'])
                ->assertSee(route('products.show', $slug));
        }
    }

    public function test_public_header_is_shared_across_pages_and_keeps_active_navigation(): void
    {
        foreach (['/' => 'home', '/about' => 'about', '/products' => 'products.index', '/products/electric-motors-generators' => 'products.index', '/brands' => 'brands.index', '/brands/wolong' => 'brands.index', '/solutions' => 'solutions', '/contact' => 'contact'] as $path => $activeRoute) {
            $this->get($path)->assertOk()
                ->assertSee('class="round-contact"', false)
                ->assertSee('href="'.route($activeRoute).'"  aria-current="page"', false)
                ->assertDontSee('class="utility-bar"', false)
                ->assertDontSee('header-cta', false);
        }
    }

    public function test_invalid_slugs_return_the_custom_404(): void
    {
        foreach (['/products/not-found', '/brands/not-found', '/products/electric-motors-generators.name', '/missing-page'] as $path) {
            $this->get($path)->assertNotFound()->assertSee('Halaman ini tidak tersedia.');
        }
    }

    public function test_search_matches_brand_and_product_type_case_insensitively(): void
    {
        $this->get('/products?q=wOLong')->assertOk()->assertViewHas('products', fn ($products) => $products->keys()->all() === ['electric-motors-generators']);
        $this->get('/products?q=PTFE')->assertOk()->assertViewHas('products', fn ($products) => $products->keys()->all() === ['industrial-hose']);
    }

    public function test_search_and_category_filters_are_combined_and_have_an_empty_state(): void
    {
        $this->get('/products?category=Mechanical')->assertOk()->assertViewHas('products', fn ($products) => $products->count() === 2);
        $this->get('/products?q=Wolong&category=Mechanical')->assertOk()->assertSee('Produk tidak ditemukan');
        $this->get('/products?q=0')->assertOk()->assertSee('Produk tidak ditemukan');
    }

    public function test_query_input_is_validated_and_escaped(): void
    {
        $this->getJson('/products?q[]=motor')->assertUnprocessable()->assertJsonValidationErrors('q');
        $this->getJson('/products?category=unknown')->assertUnprocessable()->assertJsonValidationErrors('category');
        $this->get('/products?q='.urlencode('<script>alert(1)</script>'))->assertOk()->assertDontSee('<script>alert(1)</script>', false);
        $this->getJson('/contact?product[]=invalid')->assertUnprocessable()->assertJsonValidationErrors('product');
    }

    public function test_contact_email_keeps_product_context_and_does_not_claim_submission(): void
    {
        $response = $this->get('/contact?product=chemical-metering-pump')->assertOk()->assertSee('Chemical Metering Pump')->assertSee('Qdos');
        $link = $response->viewData('emailLink');
        $this->assertStringStartsWith('mailto:doni.rahmat@smartomoro.com?', $link);
        parse_str(parse_url($link, PHP_URL_QUERY), $query);
        $this->assertSame('Permintaan informasi: Chemical Metering Pump - Qdos', $query['subject']);
        $this->assertStringContainsString('Chemical Metering Pump - Qdos', $query['body']);
        $this->get('/contact?product=unknown')->assertOk()->assertViewHas('product', null);
    }

    public function test_private_legal_numbers_are_not_rendered_until_published(): void
    {
        config(['company.legal' => [['name' => 'NIB', 'value' => 'PRIVATE-NUMBER-123', 'public' => false]]]);
        $this->get('/about')->assertOk()->assertDontSee('PRIVATE-NUMBER-123');
        config(['company.legal.0.public' => true]);
        $this->get('/about')->assertOk()->assertSee('PRIVATE-NUMBER-123');
    }

    public function test_optional_specifications_and_datasheet_are_rendered_only_when_available(): void
    {
        $this->get('/products/air-compressor')->assertOk()->assertDontSee('download>', false);
        config(['company.products.air-compressor.specifications' => ['Model' => 'Test model']]);
        config(['company.products.air-compressor.datasheet' => 'datasheets/test.pdf']);
        $this->get('/products/air-compressor')->assertOk()->assertSee('Test model')->assertSee('datasheets/test.pdf');
    }

    public function test_sitemap_is_valid_xml_and_includes_all_details(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml');
        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $this->assertCount(20, $xml->url);
        foreach (config('company.products') as $slug => $product) {
            $response->assertSee(route('products.show', $slug));
        }
    }

    public function test_robots_points_to_the_sitemap_and_unknown_products_are_not_indexed(): void
    {
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap: '.route('sitemap'));
        $this->get('/products/not-found')->assertNotFound()->assertSee('name="robots" content="noindex,follow"', false);
    }
}
