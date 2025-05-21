<?php

namespace Database\Seeders\Dhtt;

use App\Models\HikeType;
use Illuminate\Database\Seeder;

class HikeTypeSeeder extends Seeder
{
    /**
     * Túratípusok seed.
     */
    public function run(): void
    {
        HikeType::insert([
            [
                'id' => 1,
                'name' => 'Gyalogos',
                'description' => 'Gyalogos túra',
            ],
            [
                'id' => 2,
                'name' => 'Kerékpáros',
                'description' => 'Kerékpáros túra',
            ],
        ]);
    }
}
