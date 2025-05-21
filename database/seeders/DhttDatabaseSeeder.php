<?php

namespace Database\Seeders;

use Database\Seeders\Dhtt\AssociationSeeder;
use Database\Seeders\Dhtt\CreateAdminSeeder;
use Database\Seeders\Dhtt\HikeTypeSeeder;
use Database\Seeders\Dhtt\SettingsSeeder;
use Database\Seeders\Dhtt\SupporterSeeder;
use Illuminate\Database\Seeder;

class DhttDatabaseSeeder extends Seeder
{
    /**
     * Dhtt seed.
     */
    public function run(): void
    {
        // Alap seederek
        $this->call([
            AssociationSeeder::class,
            HikeTypeSeeder::class,
            SupporterSeeder::class,
            SettingsSeeder::class,
            CreateAdminSeeder::class,
        ]);

        $this->command->info('Faker adatok generálásához: php artisan db:seed --class=DhttFakerSeeder');
    }
}
