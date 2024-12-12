<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        City::factory()
            ->createMany([
                ['name' => 'Sofia'],
                ['name' => 'Stara Zagora'],
                ['name' => 'London'],
            ]);
    }
}
