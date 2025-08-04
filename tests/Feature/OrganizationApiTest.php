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

    protected Organization $organization;
    protected Building $building;
    protected Activity $parentActivity;
    protected Activity $childActivity;

    protected function setUp(): void
    {
        parent::setUp();

        $this->building = Building::factory()->create([
            'address' => 'Тестовый адрес',
            'latitude' => 55.751244,
            'longitude' => 37.618423
        ]);

        $this->parentActivity = Activity::factory()->create([
            'name' => 'Родительская деятельность',
            'parent_id' => null
        ]);

        $this->childActivity = Activity::factory()->create([
            'name' => 'Дочерняя деятельность',
            'parent_id' => $this->parentActivity->id
        ]);

        $this->organization = Organization::factory()
            ->has(OrganizationPhone::factory()->count(2)->state(fn () => ['phone_number' => fake()->e164PhoneNumber()]))
            ->create([
                'name' => 'ООО Рога и Копыта',
                'building_id' => $this->building->id,
            ]);

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
            ->assertJsonFragment([
                'id' => $this->organization->id,
                'name' => 'ООО Рога и Копыта',
            ])
            ->assertJsonFragment([
                'address' => $this->building->address,
                'latitude' => $this->building->latitude,
                'longitude' => $this->building->longitude,
            ])
            ->assertJsonFragment(['name' => 'Родительская деятельность'])
            ->assertJsonFragment(['name' => 'Дочерняя деятельность']);
    }

    public function test_unified_filter()
    {
        $testCases = [
            [
                'params' => ['filter[name]' => 'Рога'],
                'expected_count' => 1,
                'assertions' => [['name' => 'ООО Рога и Копыта']]
            ],
            [
                'params' => ['filter[building_id]' => $this->building->id],
                'expected_count' => 1,
                'assertions' => [['building_id' => $this->building->id]]
            ],
            [
                'params' => ['filter[activity_id]' => $this->parentActivity->id],
                'expected_count' => 1,
                'assertions' => [['id' => $this->organization->id]]
            ],
            [
                'params' => [
                    'filter[activity_id]' => $this->parentActivity->id,
                    'filter[include_descendants]' => true
                ],
                'expected_count' => 1,
                'assertions' => [['id' => $this->organization->id]]
            ],
            [
                'params' => [
                    'filter[latitude]' => 55.751244,
                    'filter[longitude]' => 37.618423,
                    'filter[radius]' => 1
                ],
                'expected_count' => 1,
                'assertions' => [['id' => $this->organization->id]]
            ],
            [
                'params' => [
                    'filter[min_lat]' => 55.74,
                    'filter[max_lat]' => 55.76,
                    'filter[min_lng]' => 37.61,
                    'filter[max_lng]' => 37.62
                ],
                'expected_count' => 1,
                'assertions' => [['id' => $this->organization->id]]
            ],
            [
                'params' => [
                    'filter[name]' => 'Рога',
                    'filter[building_id]' => $this->building->id,
                    'filter[activity_id]' => $this->parentActivity->id
                ],
                'expected_count' => 1,
                'assertions' => [['id' => $this->organization->id]]
            ]
        ];

        foreach ($testCases as $testCase) {
            $url = '/api/organizations?' . http_build_query($testCase['params']);

            $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
                ->get($url);

            $response->assertStatus(200)
                ->assertJsonCount($testCase['expected_count']);

            foreach ($testCase['assertions'] as $fragment) {
                $response->assertJsonFragment($fragment);
            }
        }
    }

    public function test_empty_results()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get('/api/organizations?filter[name]=Несуществующая');

        $response->assertStatus(200)
            ->assertJsonCount(0);
    }

    public function test_invalid_parameters()
    {
        $response = $this->withHeaders([
            'X-API-Key' => 'test-api-key-123',
            'Accept' => 'application/json'
        ])->get('/api/organizations?filter[latitude]=invalid');

        $response->assertStatus(422);
    }

    public function test_organization_not_found()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get('/api/organizations/99999');

        $response->assertStatus(404);
    }
}
