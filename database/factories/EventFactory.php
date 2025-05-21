<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $registration_start = $this->faker->dateTimeBetween('-2 years');
        $start = Carbon::create($registration_start);
        $registration_end = $this->faker->dateTimeBetween($start->addDay(), '+1 month');
        $end = Carbon::create($registration_end);
        $date = $this->faker->dateTimeBetween(
            $end->addDay(),
            $end->addMonths(3)
        );
        $discount_until = $this->faker->dateTimeBetween($registration_start, $registration_end);
        $year = $date->format('Y');
        $date = $date->format('Y-m-d');

        $name = 'Dél-Hargita Teljesítménytúra '.$year;
        $description = $name.' leírás';
        $invitation = $name.' meghívó';
        $rules = $name.' szabályzat';
        $short_name = 'DHTT '.$year;
        $status = $this->faker->numberBetween(0, 4);
        $active = $status == 4 ? 0 : $this->faker->boolean();

        return [
            'short_name' => $short_name,
            'name' => $name,
            'year' => $year,
            'date' => $date,
            'location' => $this->faker->city(),
            'entry_fee' => $this->faker->numberBetween(0, 100),
            'discount1' => $this->faker->numberBetween(0, 50),
            'discount2' => $this->faker->numberBetween(0, 50),
            'registration_start' => $registration_start,
            'registration_end' => $registration_end,
            'registration_discount_until' => $discount_until,
            'organizer_name' => $this->faker->name(),
            'organizer_email' => $this->faker->safeEmail(),
            'organizer_phone' => $this->faker->phoneNumber(),
            'invitation' => $invitation,
            'description' => $description,
            'rules' => $rules,
            'status' => $status,
            'active' => $active,
            'show' => 1,
        ];
    }
}
