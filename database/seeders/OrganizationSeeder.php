<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\OrganizationPhone;
use App\Models\Activity;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get activities for relationships
        $food = Activity::where('name', 'Еда')->first();
        $meatProducts = Activity::where('name', 'Мясная продукция')->first();
        $dairyProducts = Activity::where('name', 'Молочная продукция')->first();
        $automobiles = Activity::where('name', 'Автомобили')->first();
        $parts = Activity::where('name', 'Запчасти')->first();

        // Organization 1 - Food company
        $org1 = Organization::create([
            'name' => 'ООО "Рога и Копыта"',
            'building_id' => 1
        ]);

        // Add phones for org1
        OrganizationPhone::create([
            'phone_number' => '2-222-222',
            'organization_id' => $org1->id
        ]);

        OrganizationPhone::create([
            'phone_number' => '3-333-333',
            'organization_id' => $org1->id
        ]);

        // Add activities for org1
        $org1->activities()->attach([$food->id, $meatProducts->id]);

        // Organization 2 - Dairy company
        $org2 = Organization::create([
            'name' => 'ООО "Молочный Мир"',
            'building_id' => 2
        ]);

        // Add phones for org2
        OrganizationPhone::create([
            'phone_number' => '8-923-666-13-13',
            'organization_id' => $org2->id
        ]);

        // Add activities for org2
        $org2->activities()->attach([$food->id, $dairyProducts->id]);

        // Organization 3 - Auto parts company
        $org3 = Organization::create([
            'name' => 'ООО "АвтоЗапчасти"',
            'building_id' => 3
        ]);

        // Add phones for org3
        OrganizationPhone::create([
            'phone_number' => '4-444-444',
            'organization_id' => $org3->id
        ]);

        OrganizationPhone::create([
            'phone_number' => '5-555-555',
            'organization_id' => $org3->id
        ]);

        // Add activities for org3
        $org3->activities()->attach([$automobiles->id, $parts->id]);

        // Organization 4 - Mixed company
        $org4 = Organization::create([
            'name' => 'ООО "Универсал"',
            'building_id' => 4
        ]);

        // Add phones for org4
        OrganizationPhone::create([
            'phone_number' => '6-666-666',
            'organization_id' => $org4->id
        ]);

        // Add activities for org4
        $org4->activities()->attach([$food->id, $automobiles->id]);

        // Organization 5 - Another food company
        $org5 = Organization::create([
            'name' => 'ООО "Свежие Продукты"',
            'building_id' => 5
        ]);

        // Add phones for org5
        OrganizationPhone::create([
            'phone_number' => '7-777-777',
            'organization_id' => $org5->id
        ]);

        OrganizationPhone::create([
            'phone_number' => '8-888-888',
            'organization_id' => $org5->id
        ]);

        // Add activities for org5
        $org5->activities()->attach([$food->id, $dairyProducts->id]);
    }
}
