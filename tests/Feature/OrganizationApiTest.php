<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Models\OrganizationPhone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем тестовые данные вручную
        $this->building = Building::create([
            'address' => 'Тестовый адрес',
            'latitude' => 55.751244,
            'longitude' => 37.618423
        ]);

        $this->parentActivity = Activity::create([
            'name' => 'Родительская деятельность',
            'parent_id' => null
        ]);

        $this->childActivity = Activity::create([
            'name' => 'Дочерняя деятельность',
            'parent_id' => $this->parentActivity->id
        ]);

        $this->organization = Organization::create([
            'name' => 'ООО Рога и Копыта',
            'building_id' => $this->building->id
        ]);

        // Создаем телефоны с обязательным phone_number
        OrganizationPhone::create([
            'organization_id' => $this->organization->id,
            'phone_number' => '+79991112233'
        ]);

        OrganizationPhone::create([
            'organization_id' => $this->organization->id,
            'phone_number' => '+79994445566'
        ]);

        // Привязываем активности
        $this->organization->activities()->attach([
            $this->parentActivity->id,
            $this->childActivity->id
        ]);
    }

    public function test_get_organization_by_id()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get("/api/organizations/{$this->organization->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $this->organization->id,
                'name' => 'ООО Рога и Копыта',
                'building_id' => $this->building->id
            ]);
    }

    public function test_unified_filter()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get('/api/organizations?name=Рога');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['name' => 'ООО Рога и Копыта']);
    }

    public function test_empty_results()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get('/api/organizations?name=Несуществующая');

        $response->assertStatus(200)
            ->assertJsonCount(0);
    }

    public function test_invalid_parameters()
    {

        $response = $this->withHeaders([
            'X-API-Key' => 'test-api-key-123',
            'Accept' => 'application/json'
        ])->get('/api/organizations?latitude=invalid');

        $response->assertStatus(422);


        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->json('GET', '/api/organizations?latitude=invalid');

        $response->assertStatus(422);
    }

    public function test_organization_not_found()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get('/api/organizations/99999');

        $response->assertStatus(404);
    }
}
