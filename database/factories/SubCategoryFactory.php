<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubCategory>
 */
class SubCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sub_category_type' => $this->faker->boolean(50),
            'location_type' => $this->faker->boolean(50),
            'name' => $this->faker->word,
            'image' => "sub-categories/" . $this->faker->image("public/uploads/sub-categories", 500, 500, 'sub category', false),
            'displayed' => 1
        ];
    }
}
