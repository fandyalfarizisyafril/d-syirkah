<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->withoutVite();
        $response = $this->get('/');

        $response->assertOk()->assertSee('PT. Syirkah Mandiri')->assertDontSee('ApexBuild');
    }
}
