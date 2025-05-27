<?php
namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_application_boots_successfully(): void
    {
        $response = $this->get('/');

        // A aplicação deve redirecionar corretamente (indica que está funcionando)
        $response->assertStatus(302);
    }
}
