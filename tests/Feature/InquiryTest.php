<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\WebsiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(WebsiteContentSeeder::class);
    }

    private function payload(array $extra = []): array
    {
        return array_replace(['name' => 'Customer', 'company' => 'Example Company', 'email' => 'customer@example.test', 'phone' => '+628123456789', 'product_id' => Product::first()->id, 'requirements' => 'Two units', 'message' => 'Please contact us.', 'website' => ''], $extra);
    }

    public function test_public_inquiry_is_saved_with_context_and_can_be_managed_by_admin(): void
    {
        $this->post('/inquiry', $this->payload(['status' => 'closed', 'notes' => 'Untrusted']))->assertRedirect('/contact')->assertSessionHas('inquiry_sent');
        $inquiry = Inquiry::firstOrFail();
        $this->assertSame('new', $inquiry->status);
        $this->assertNull($inquiry->notes);
        $this->assertStringContainsString('Wolong', $inquiry->product_name);
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/admin/inquiries/'.$inquiry->id)->assertOk()->assertSee('Two units');
        $this->put('/admin/inquiries/'.$inquiry->id, ['status' => 'contacted', 'notes' => 'Called customer'])->assertSessionHasNoErrors();
        $this->assertSame('contacted', $inquiry->fresh()->status);
        $this->assertSame($admin->id, $inquiry->fresh()->updated_by);
        $this->put('/admin/inquiries/'.$inquiry->id, ['status' => 'invalid'])->assertSessionHasErrors('status');
        $this->get('/admin/inquiries?status=contacted&q=Example')->assertOk()->assertSee('Customer');
        Product::first()->delete();
        $this->assertNull($inquiry->fresh()->product_id);
        $this->assertStringContainsString('Wolong', $inquiry->fresh()->product_name);
        $this->delete('/admin/inquiries/'.$inquiry->id)->assertRedirect('/admin/inquiries');
        $this->assertDatabaseMissing('inquiries', ['id' => $inquiry->id]);
    }

    public function test_invalid_spam_and_unpublished_product_inquiries_are_rejected(): void
    {
        $this->post('/inquiry', [])->assertSessionHasErrors(['name', 'email', 'phone', 'requirements']);
        $this->post('/inquiry', $this->payload(['website' => 'spam.example']))->assertSessionHasErrors('website');
        $this->post('/inquiry', $this->payload(['email' => 'invalid', 'phone' => 'abc']))->assertSessionHasErrors(['email', 'phone']);
        Product::first()->update(['published' => false]);
        $this->post('/inquiry', $this->payload())->assertSessionHasErrors('product_id');
        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_inquiry_endpoint_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/inquiry', $this->payload())->assertRedirect();
        }
        $this->post('/inquiry', $this->payload())->assertTooManyRequests();
        $this->assertDatabaseCount('inquiries', 5);
    }

    public function test_contact_prefills_selected_product_and_private_inquiries_are_not_public(): void
    {
        $this->get('/contact?product=chemical-metering-pump')->assertOk()->assertSee('Kirim Inquiry');
        $slug = str_repeat('a', 180);
        Product::first()->update(['slug' => $slug]);
        $this->get('/contact?product='.$slug)->assertOk()->assertSee('Electric Motors');
        $this->post('/inquiry', $this->payload(['message' => '<script>alert(1)</script>']))->assertRedirect();
        $id = Inquiry::first()->id;
        $this->get('/admin/inquiries/'.$id)->assertRedirect('/admin/login');
        $this->actingAs(User::factory()->create(['is_admin' => true]))->get('/admin/inquiries/'.$id)->assertOk()->assertDontSee('<script>alert(1)</script>', false);
    }
}
