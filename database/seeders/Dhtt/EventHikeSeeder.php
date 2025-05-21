<?php

namespace Database\Seeders\Dhtt;

use App\Models\Event;
use App\Models\Hike;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventHikeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Eseményekhez túrák hozzárendelése
     */
    public function run(): void
    {
        $events = Event::all();
        $hikes = Hike::all();
        $hikesCount = $hikes->count();

        if (! $events->count() || ! $hikesCount) {
            $this->command->error('Nincsenek események vagy túrák a kapcsolatok betöltéséhez!');

            return;
        }

        $hikes = collect($hikes->modelKeys());

        foreach ($events as $event) {
            $randCount = rand(1, $hikesCount);
            $randomHikes = $hikes->random($randCount);
            $event->hikes()->sync($randomHikes);
        }

        // $this->command->info('Események-Túrák kapcsolat adatok feltöltve!');
    }
}
