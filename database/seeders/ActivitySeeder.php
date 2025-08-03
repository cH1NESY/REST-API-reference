<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Level 1 - Root activities
        $food = Activity::create([
            'name' => 'Еда',
            'level' => 1
        ]);

        $automobiles = Activity::create([
            'name' => 'Автомобили',
            'level' => 1
        ]);

        // Level 2 - Food children
        $meatProducts = Activity::create([
            'name' => 'Мясная продукция',
            'parent_id' => $food->id,
            'level' => 2
        ]);

        $dairyProducts = Activity::create([
            'name' => 'Молочная продукция',
            'parent_id' => $food->id,
            'level' => 2
        ]);

        // Level 2 - Automobile children
        $cargoVehicles = Activity::create([
            'name' => 'Грузовые',
            'parent_id' => $automobiles->id,
            'level' => 2
        ]);

        $passengerVehicles = Activity::create([
            'name' => 'Легковые',
            'parent_id' => $automobiles->id,
            'level' => 2
        ]);

        $parts = Activity::create([
            'name' => 'Запчасти',
            'parent_id' => $automobiles->id,
            'level' => 2
        ]);

        // Level 3 - Food grandchildren
        Activity::create([
            'name' => 'Свинина',
            'parent_id' => $meatProducts->id,
            'level' => 3
        ]);

        Activity::create([
            'name' => 'Говядина',
            'parent_id' => $meatProducts->id,
            'level' => 3
        ]);

        Activity::create([
            'name' => 'Молоко',
            'parent_id' => $dairyProducts->id,
            'level' => 3
        ]);

        Activity::create([
            'name' => 'Сыр',
            'parent_id' => $dairyProducts->id,
            'level' => 3
        ]);

        // Level 3 - Automobile grandchildren
        Activity::create([
            'name' => 'Грузовики',
            'parent_id' => $cargoVehicles->id,
            'level' => 3
        ]);

        Activity::create([
            'name' => 'Седаны',
            'parent_id' => $passengerVehicles->id,
            'level' => 3
        ]);

        Activity::create([
            'name' => 'Двигатели',
            'parent_id' => $parts->id,
            'level' => 3
        ]);

        Activity::create([
            'name' => 'Аксессуары',
            'parent_id' => $parts->id,
            'level' => 3
        ]);
    }
}
