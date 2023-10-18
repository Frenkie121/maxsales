<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Store;
use App\Models\User;
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
    public function definition(): array
    {
        $name = fake()->unique()->word() . ' ' . fake()->unique()->word();

        return [
            'created_by' => fake()->numberBetween(1, 3),
            'brand_id' => fake()->numberBetween(1, 5),
            'category_id' => fake()->numberBetween(1, 5),
            'store_id' => fake()->numberBetween(1, 3),
            'name' => $name,
            'code' => strtoupper(substr(explode(' ', $name)[0], 0, 3) . '_' . substr(explode(' ', $name)[1], 0, 3)),
            'purchase_price' => fake()->numberBetween(500, 100000),
            'sale_price' => fake()->numberBetween(2500, 200000),
            'day_price' => fake()->numberBetween(2000, 250000),
            'night_price' => fake()->numberBetween(3000, 300000),
            'weekend_price' => fake()->numberBetween(4000, 400000),
            'bonus' => fake()->numberBetween(500, 1000),
        ];
    }
}
