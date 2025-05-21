<?php

namespace Database\Factories;

use App\Models\Hike;
use App\Models\HikeType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hike>
 */
class HikeFactory extends Factory
{
    protected $model = Hike::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hikeType = HikeType::all()->random();
        $distances = [];
        for ($i = 1; $i <= 20; $i++) {
            $distances[] = $i * 5;
        }
        $distance = $this->faker->randomElement($distances);
        $name = $hikeType->name.' '.$distance.'km';
        $randDate = $this->faker->dateTimeBetween('-3 years');
        $route = 'egy - ketto - harom  - egy - ketto - harom - egy - ketto - harom - egy - ketto - harom';
        $number_start = 1;
        $number_end = $this->faker->numberBetween(50, $number_start + 150);

        if ($this->faker->boolean(30)) {
            $number_start_extra = $this->faker->numberBetween($number_end + 1, $number_end + 100);
            $number_end_extra = $this->faker->numberBetween($number_start_extra + 1, $number_start_extra + 50);
        } else {
            $number_start_extra = 0;
            $number_end_extra = 0;
        }

        return [
            'hike_type_id' => $hikeType->getKey(),
            'name' => $name,
            'year' => $randDate->format('y'),
            'route' => $route,
            'distance' => $distance,
            'time_limit' => $this->faker->randomFloat(1, 1, 24),
            'elevation' => '+/-500',
            'number_start' => $number_start,
            'number_end' => $number_end,
            'number_start_extra' => $number_start_extra,
            'number_end_extra' => $number_end_extra,
            'current_number' => 0,
        ];
    }
}
