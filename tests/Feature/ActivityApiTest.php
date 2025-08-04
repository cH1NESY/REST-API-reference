<?php

namespace Tests\Feature;

use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем тестовые данные вручную
        $this->parentActivity = Activity::create([
            'name' => 'Родительская деятельность',
            'parent_id' => null
        ]);

        $this->childActivity = Activity::create([
            'name' => 'Дочерняя деятельность',
            'parent_id' => $this->parentActivity->id
        ]);
    }

    public function test_get_all_activities()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get('/api/activities');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['name' => 'Родительская деятельность'])
            ->assertJsonFragment(['name' => 'Дочерняя деятельность']);
    }

    public function test_get_activity_by_id()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get("/api/activities/{$this->parentActivity->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $this->parentActivity->id,
                'name' => 'Родительская деятельность'
            ]);
    }

    public function test_get_activity_tree()
    {
        $response = $this->withHeaders(['X-API-Key' => 'test-api-key-123'])
            ->get('/api/activities/tree');

        $response->assertStatus(200)
            ->assertJsonCount(1) // Только родительские активности
            ->assertJsonFragment(['name' => 'Родительская деятельность'])
            ->assertJsonPath('0.children.0.name', 'Дочерняя деятельность');
    }
}
