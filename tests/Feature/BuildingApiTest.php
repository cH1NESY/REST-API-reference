<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuildingApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->building = Building::create([
            'address' => 'ул. Тестовая, 123',
            'latitude' => 55.7558,
            'longitude' => 37.6176
        ]);

        // Создаем организации для этого здания
        Organization::create([
            'name' => 'Тестовая организация 1',
            'building_id' => $this->building->id
        ]);

        Organization::create([
            'name' => 'Тестовая организация 2',
            'building_id' => $this->building->id
        ]);
    }

    public function test_get_all_buildings()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get('/api/buildings');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['address' => 'ул. Тестовая, 123']);
    }

    public function test_get_building_by_id()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get("/api/buildings/{$this->building->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $this->building->id,
                'address' => 'ул. Тестовая, 123'
            ]);
    }

    public function test_get_nonexistent_building()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get('/api/buildings/99999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Building not found']);
    }
}
