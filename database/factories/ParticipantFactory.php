<?php

namespace Database\Factories;

use App\Models\Association;
use App\Models\Event;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Participant>
 */
class ParticipantFactory extends Factory
{
    protected $model = Participant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $event = Event::has('hikes')->inRandomOrder()->first();
        $hike = $event->hikes()->inRandomOrder()->first();

        if (! $hike) {
            throw new \Exception('Hike not found');
        }

        $association = Association::pluck('id')->random();

        $startTime = $this->faker->optional()->time();
        $endTime = '00:00:00';
        $totalTime = '00:00:00';
        $number = 0;

        if ($startTime) {
            $status = $this->faker->randomElement(['started', 'completed', 'abandoned']);

            if ($status == 'completed') {
                $randomDuration = $this->faker->numberBetween(10, 60 * 60 * 5);

                $start = Carbon::parse($startTime);
                $end = $start->copy()->addSeconds($randomDuration);
                $endTime = $end->format('H:i:s');
                $totalTime = $start->diff($end)->format('%H:%i:%s');
            }

            if ($hike->current_number <= $hike->number_end) {
                $number = max($hike->current_number, $hike->number_start);
                $number++;
            } elseif ($hike->current_number <= $hike->number_end_extra) {
                $number = max($hike->current_number, $hike->number_start_extra);
                $number++;
            }
        } else {
            $startTime = '00:00:00';
            $status = 'absent';
        }

        if ($hike->current_number < $number) {
            $hike->current_number = $number;
            $hike->save();
        }

        return [
            'event_id' => $event,
            'hike_id' => $hike,
            'name' => $this->faker->name(),
            'city' => $this->faker->city(),
            'association_id' => $association,
            'is_student' => $this->faker->boolean(),
            'age' => $this->faker->numberBetween(10, 80),
            'phone' => $this->faker->phoneNumber(),
            'number' => $number,
            'start_time' => $startTime,
            'finish_time' => $endTime,
            'completion_time' => $totalTime,
            'tshirt' => $this->faker->randomElement(['S', 'M', 'L', 'XL', 'No']),
            'entry_fee' => $this->faker->randomFloat(2, 0, 100),
            'status' => $status,
        ];
    }
}
