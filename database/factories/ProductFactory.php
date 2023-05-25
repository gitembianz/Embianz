<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
            'short_description' => $this->faker->paragraph(1),
            'long_description' => $this->faker->paragraph(3),
            'quantity' => $this->faker->numberBetween(1, 100),
            'product_status' => $this->faker->randomElement(['active', 'inactive','low stock']),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'created_by' => 'admin',
            'last_modified_by' => 'admin',
            'seo_title' => $this->faker->word(),
        ];
    }
}
