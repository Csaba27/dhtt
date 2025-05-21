<?php

namespace Database\Factories;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Template>
 */
class TemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'type' => $this->faker->randomElement(['description', 'rules', 'invitation', 'terms']),
            'content' => $this->faker->paragraph,
            'is_active' => $this->faker->boolean,
        ];
    }
}
