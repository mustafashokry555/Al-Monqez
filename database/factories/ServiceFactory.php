<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'brief' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'image' => "services/" . $this->faker->image("public/uploads/services", 500, 500, 'service', false),
            'displayed' => 1
        ];
    }
}
