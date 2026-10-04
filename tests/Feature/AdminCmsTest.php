<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteContent;
use App\Models\User;
use Database\Seeders\WebsiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCmsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(WebsiteContentSeeder::class);
        $this->admin = User::factory()->create(['is_admin' => true, 'password' => 'AdminTestPassword123!']);
    }

    private function payload(array $changes = []): array
    {
        return array_replace([
            'name' => 'Test Pump', 'slug' => 'test-pump', 'brand_id' => Brand::first()->id, 'category_id' => Category::first()->id,
            'icon' => 'cog', 'summary' => 'Test pump summary', 'description' => 'Test description',
            'types' => "Pump A\nPump B", 'applications' => 'Water treatment', 'requirements' => 'Flow rate',
            'specifications' => [['label' => 'Model', 'value' => 'QA-1']], 'published' => 1,
        ], $changes);
    }

    public function test_admin_routes_and_mutations_require_an_administrator(): void
    {
        foreach (['/admin', '/admin/products', '/admin/inquiries', '/admin/content/legal'] as $url) {
            $this->get($url)->assertRedirect('/admin/login');
        }
        $this->post('/admin/products', $this->payload())->assertRedirect('/admin/login');
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
        $this->post('/admin/products', $this->payload())->assertForbidden();
        $this->put('/admin/content/legal', ['items' => []])->assertForbidden();
        $this->assertDatabaseCount('products', 7);
    }

    public function test_only_admin_credentials_can_login_and_logout_invalidates_authentication(): void
    {
        $ordinary = User::factory()->create(['password' => 'OrdinaryPass123!']);
        $this->post('/admin/login', ['email' => $ordinary->email, 'password' => 'OrdinaryPass123!'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->post('/admin/login', ['email' => $this->admin->email, 'password' => 'AdminTestPassword123!'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->admin);
        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_login_attempts_are_limited_even_when_the_next_password_is_correct(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['email' => $this->admin->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        }
        $this->post('/admin/login', ['email' => $this->admin->email, 'password' => 'AdminTestPassword123!'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_every_admin_editor_renders_without_exposing_pages_to_indexing(): void
    {
        $this->actingAs($this->admin);
        foreach (['/admin', '/admin/products', '/admin/products/create', '/admin/products/1/edit', '/admin/brands', '/admin/brands/create', '/admin/brands/1/edit', '/admin/categories', '/admin/categories/create', '/admin/categories/1/edit', '/admin/content/company', '/admin/content/focus', '/admin/content/values', '/admin/content/solutions', '/admin/content/legal', '/admin/inquiries', '/admin/account'] as $url) {
            $this->get($url)->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        }
    }

    public function test_product_create_update_publish_delete_and_public_content_are_connected(): void
    {
        $this->actingAs($this->admin)->post('/admin/products', $this->payload())->assertSessionHasNoErrors();
        $product = Product::where('slug', 'test-pump')->firstOrFail();
        $this->assertSame(['Pump A', 'Pump B'], $product->types);
        $this->assertSame(['Model' => 'QA-1'], $product->specifications);
        $this->assertSame($this->admin->id, $product->updated_by);
        $this->get('/products/test-pump')->assertOk()->assertSee('QA-1');
        $this->put('/admin/products/'.$product->id, $this->payload(['name' => 'Edited Pump', 'published' => 0]))->assertSessionHasNoErrors();
        $this->get('/products/test-pump')->assertNotFound();
        $this->get('/products')->assertDontSee('Edited Pump');
        $this->get('/sitemap.xml')->assertDontSee('/products/test-pump');
        $this->delete('/admin/products/'.$product->id)->assertRedirect('/admin/products');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_duplicate_slugs_invalid_relations_and_duplicate_specification_labels_are_rejected(): void
    {
        $this->actingAs($this->admin)->post('/admin/products', $this->payload(['slug' => Product::first()->slug]))->assertSessionHasErrors('slug');
        $this->post('/admin/products', $this->payload(['brand_id' => 9999, 'category_id' => 9999]))->assertSessionHasErrors(['brand_id', 'category_id']);
        $this->post('/admin/products', $this->payload(['specifications' => [['label' => 'Model', 'value' => 'A'], ['label' => 'Model', 'value' => 'B']]]))->assertSessionHasErrors('specifications.0.label');
        $this->assertDatabaseCount('products', 7);
    }

    public function test_uploads_are_served_and_replaced_files_are_removed(): void
    {
        Storage::fake('local');
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jmX8AAAAASUVORK5CYII=');
        $this->actingAs($this->admin)->post('/admin/products', $this->payload([
            'image' => UploadedFile::fake()->createWithContent('pump.png', $png),
            'datasheet' => UploadedFile::fake()->createWithContent('pump.pdf', "%PDF-1.4\n1 0 obj\n<<>>\nendobj\n%%EOF"),
        ]))->assertSessionHasNoErrors();
        $product = Product::where('slug', 'test-pump')->firstOrFail();
        Storage::disk('local')->assertExists('cms/'.basename($product->image));
        $this->get('/'.$product->image)->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->get('/'.$product->datasheet)->assertDownload('datasheet.pdf');
        $this->get('/products/test-pump')->assertSee($product->image)->assertSee($product->datasheet);
        $this->get('/products?q=Test+Pump')->assertSee($product->image);
        $old = $product->image;
        $this->put('/admin/products/'.$product->id, $this->payload(['remove_image' => 1]))->assertSessionHasNoErrors();
        Storage::disk('local')->assertMissing('cms/'.basename($old));
        $this->get('/'.$old)->assertNotFound();
        $this->assertNull($product->fresh()->image);
    }

    public function test_script_svg_and_oversized_uploads_are_rejected(): void
    {
        Storage::fake('local');
        $fake = UploadedFile::fake()->createWithContent('image.jpg', '<?php echo 1;');
        $disguisedScript = new UploadedFile($fake->getPathname(), 'image.jpg', null, null, true);
        $this->actingAs($this->admin)->post('/admin/products', $this->payload(['image' => $disguisedScript]))->assertSessionHasErrors('image');
        $this->post('/admin/products', $this->payload(['image' => UploadedFile::fake()->createWithContent('image.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>')]))->assertSessionHasErrors('image');
        $this->post('/admin/products', $this->payload(['datasheet' => UploadedFile::fake()->create('large.pdf', 3000, 'application/pdf')]))->assertSessionHasErrors('datasheet');
        $this->assertCount(0, Storage::disk('local')->allFiles('cms'));
        $this->get('/media/not-a-valid-file.php')->assertNotFound();
    }

    public function test_brands_and_categories_are_editable_and_used_records_cannot_be_deleted(): void
    {
        $this->actingAs($this->admin)->delete('/admin/brands/1')->assertSessionHasErrors('delete');
        $this->delete('/admin/categories/1')->assertSessionHasErrors('delete');
        $this->put('/admin/brands/1', ['name' => 'Updated Brand', 'slug' => 'updated-brand', 'description' => 'New brand description'])->assertSessionHasNoErrors();
        $this->get('/products/electric-motors-generators')->assertSee('Updated Brand');
        $this->get('/brands/updated-brand')->assertOk()->assertSee('New brand description');
        $this->put('/admin/categories/1', ['name' => 'New Electrical', 'slug' => 'new-electrical'])->assertSessionHasNoErrors();
        $this->get('/products?category=New%20Electrical')->assertOk()->assertSee('Electric Motors');
        $this->post('/admin/brands', ['name' => 'Empty Brand', 'slug' => 'empty-brand'])->assertSessionHasNoErrors();
        $this->get('/brands/empty-brand')->assertOk();
        $brand = Brand::where('slug', 'empty-brand')->firstOrFail();
        $this->delete('/admin/brands/'.$brand->id)->assertSessionHasNoErrors();
        $this->get('/brands/empty-brand')->assertNotFound();
    }

    public function test_company_settings_and_legal_publication_are_reflected_on_the_website(): void
    {
        $data = SiteContent::findOrFail('company')->data;
        $data['name'] = 'Changed Company';
        $data['emails'][0] = 'quotes@example.test';
        $this->actingAs($this->admin)->put('/admin/content/company', ['data' => $data])->assertSessionHasNoErrors();
        $this->get('/')->assertSee('Changed Company');
        $this->get('/contact')->assertSee('quotes@example.test');
        $this->put('/admin/content/legal', ['items' => [['name' => 'NIB', 'value' => 'PRIVATE-001', 'public' => 0]]])->assertSessionHasNoErrors();
        $this->get('/about')->assertDontSee('PRIVATE-001');
        $this->put('/admin/content/legal', ['items' => [['name' => 'NIB', 'value' => 'PUBLIC-001', 'public' => 1]]])->assertSessionHasNoErrors();
        $this->get('/about')->assertSee('PUBLIC-001');
    }

    public function test_solution_links_survive_slug_edits_and_product_removal(): void
    {
        $product = Product::first();
        $this->actingAs($this->admin)->put('/admin/products/'.$product->id, $this->payload(['slug' => 'renamed-product']))->assertSessionHasNoErrors();
        $this->get('/solutions')->assertOk()->assertSee('/products/renamed-product');
        $this->delete('/admin/products/'.$product->id)->assertRedirect();
        $this->get('/solutions')->assertOk()->assertDontSee('/products/renamed-product');
    }

    public function test_repeated_content_is_validated_and_html_is_escaped(): void
    {
        $this->actingAs($this->admin)->put('/admin/content/focus', ['items' => [['name' => 'Test', 'description' => 'Test', 'icon' => '<script>']]])->assertSessionHasErrors('items.0.icon');
        $this->put('/admin/content/values', ['items' => [['name' => 'Value', 'description' => '<script>alert(1)</script>']]])->assertSessionHasNoErrors();
        $this->get('/about')->assertDontSee('<script>alert(1)</script>', false)->assertSee('&lt;script&gt;', false);
        $this->put('/admin/content/values', [])->assertSessionHasNoErrors();
        $this->get('/')->assertOk();
        $this->get('/admin/content/unsupported')->assertNotFound();
    }

    public function test_seeding_again_does_not_overwrite_admin_changes_or_restore_deleted_products(): void
    {
        Brand::first()->update(['name' => 'Keep this name']);
        Product::first()->delete();
        $this->seed(WebsiteContentSeeder::class);
        $this->assertDatabaseCount('products', 6);
        $this->assertSame('Keep this name', Brand::first()->name);
    }

    public function test_password_change_requires_current_password_and_updates_hash(): void
    {
        $this->actingAs($this->admin)->put('/admin/account/password', ['current_password' => 'wrong', 'password' => 'NewStrongPassword123!', 'password_confirmation' => 'NewStrongPassword123!'])->assertSessionHasErrors('current_password');
        $this->put('/admin/account/password', ['current_password' => 'AdminTestPassword123!', 'password' => 'NewStrongPassword123!', 'password_confirmation' => 'NewStrongPassword123!'])->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('NewStrongPassword123!', $this->admin->fresh()->password));
    }

    public function test_cli_admin_creation_does_not_overwrite_users_and_recovery_requires_admin_role(): void
    {
        $this->artisan('admin:create', ['email' => 'new-admin@example.test', '--generate-password' => true])->assertExitCode(0);
        $this->assertTrue(User::where('email', 'new-admin@example.test')->firstOrFail()->is_admin);
        $hash = $this->admin->password;
        $this->artisan('admin:create', ['email' => $this->admin->email, '--generate-password' => true])->assertExitCode(1);
        $this->assertSame($hash, $this->admin->fresh()->password);
        $ordinary = User::factory()->create();
        $this->artisan('admin:reset-password', ['email' => $ordinary->email, '--generate-password' => true])->assertExitCode(1);
        $this->artisan('admin:reset-password', ['email' => $this->admin->email, '--generate-password' => true])->assertExitCode(0);
        $this->assertNotSame($hash, $this->admin->fresh()->password);
    }
}
