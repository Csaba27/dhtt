<?php

namespace Database\Seeders\Dhtt;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Database\Seeder;

class ParticipantSeeder extends Seeder
{
    /**
     * Résztvevők seed.
     */
    public function run(): void
    {
        $events = Event::has('hikes')->with('hikes')->get();

        if ($events->isEmpty()) {
            $this->command->error('Nincsenek események vagy túrák a résztvevők létrehozásához!');

            return;
        }

        // Minden eseményhez 50-50 résztvevőt adunk hozzá
        foreach ($events as $event) {
            Participant::factory()->count(50)->create([
                'event_id' => $event->id,
                'hike_id' => $event->hikes->random()->id,
            ]);
        }
    }
}
