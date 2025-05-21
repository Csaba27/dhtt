<?php

namespace Database\Seeders\Dhtt;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Események seed.
     */
    public function run(): void
    {
        Event::factory()->count(2)->create();
    }
}
