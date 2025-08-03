<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = [
            [
                'address' => 'г. Москва, ул. Ленина 1, офис 3',
                'latitude' => 55.7558,
                'longitude' => 37.6176
            ],
            [
                'address' => 'г. Москва, ул. Тверская 10, офис 5',
                'latitude' => 55.7600,
                'longitude' => 37.6100
            ],
            [
                'address' => 'г. Москва, ул. Арбат 15, офис 2',
                'latitude' => 55.7500,
                'longitude' => 37.5900
            ],
            [
                'address' => 'г. Москва, ул. Новый Арбат 20, офис 8',
                'latitude' => 55.7450,
                'longitude' => 37.5800
            ],
            [
                'address' => 'г. Москва, ул. Покровка 25, офис 12',
                'latitude' => 55.7650,
                'longitude' => 37.6500
            ]
        ];

        foreach ($buildings as $building) {
            Building::create($building);
        }
    }
}
