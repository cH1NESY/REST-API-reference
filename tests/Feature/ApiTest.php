<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Organization;
use App\Models\Building;
use App\Models\Activity;

class ApiTest extends TestCase
{
    public function test_api_key_authentication()
    {
        // Test without API key
        $response = $this->get('/api/buildings');
        $response->assertStatus(401);

        // Test with invalid API key
        $response = $this->withHeaders(['X-API-Key' => 'invalid-key'])
            ->get('/api/buildings');
        $response->assertStatus(401);

        // Test with valid API key
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get('/api/buildings');
        $response->assertStatus(200);
    }

    public function test_get_organization()
    {
        $organization = Organization::first();
        
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get("/api/organizations/{$organization->id}");
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'building_id',
                'building',
                'phones',
                'activities'
            ]);
    }

    public function test_get_organizations_by_building()
    {
        $building = Building::first();
        
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get("/api/organizations/building/{$building->id}");
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'building_id',
                    'phones',
                    'activities'
                ]
            ]);
    }

    public function test_search_organizations_by_name()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get('/api/organizations/search?name=Рога');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'building_id',
                    'building',
                    'phones',
                    'activities'
                ]
            ]);
    }

    public function test_get_activities_tree()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get('/api/activities/tree');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'parent_id',
                    'level',
                    'children'
                ]
            ]);
    }
} 