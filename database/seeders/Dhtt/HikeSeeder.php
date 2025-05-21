<?php

namespace Database\Seeders\Dhtt;

use App\Models\Hike;
use Illuminate\Database\Seeder;

class HikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hike::factory()->count(5)->create();
    }
}
