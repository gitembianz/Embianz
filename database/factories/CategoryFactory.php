<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'parrent' => $this->faker->word(),
            'long_description' => $this->faker->realText($maxNbChars = 50),
            'short_description' => $this->faker->realText($maxNbChars = 10),
            'sequence' => $this->faker->numberBetween(1, 3),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'createdby' => 'admin',
            'lastmodifiedby' => $this->faker->name(),

        ];
    }
}
