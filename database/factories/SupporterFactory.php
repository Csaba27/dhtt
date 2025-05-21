<?php

namespace Database\Factories;

use App\Models\Supporter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Supporter>
 */
class SupporterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'image_url' => $this->faker->imageUrl(),
            'link' => $this->faker->url,
            'is_local' => false,
        ];
    }
}
