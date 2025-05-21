<?php

namespace Database\Seeders;

use Database\Seeders\Dhtt\EventHikeSeeder;
use Database\Seeders\Dhtt\EventSeeder;
use Database\Seeders\Dhtt\HikeSeeder;
use Database\Seeders\Dhtt\ParticipantSeeder;
use Illuminate\Database\Seeder;

class DhttFakerSeeder extends Seeder
{
    /**
     * Dhtt faker adatok generálása.
     */
    public function run(): void
    {
        $this->command->info('Faker adatok generálása...');
        $this->call([
            EventSeeder::class,
            HikeSeeder::class,
            EventHikeSeeder::class,
            ParticipantSeeder::class,
        ]);
    }
}
