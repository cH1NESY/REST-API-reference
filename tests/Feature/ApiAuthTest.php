<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    public function test_api_key_authentication()
    {
        // Test without API key
        $response = $this->get('/api/organizations/1');
        $response->assertStatus(401);

        // Test with invalid API key
        $response = $this->withHeaders(['X-API-Key' => 'invalid-key'])
            ->get('/api/organizations/1');
        $response->assertStatus(401);

        // Test with valid API key
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get('/api/organizations/1');
        $response->assertStatus(200);
    }
}
