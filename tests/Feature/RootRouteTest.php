<?php

namespace Tests\Feature;

use Tests\TestCase;

class RootRouteTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_root_route(): void
    {
        $response = $this->get('/api/');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => "Fullstack Challenge 🏅 - Dictionary"
        ]);
    }
}
