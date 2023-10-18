<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        $phone = '6' . fake()->randomElement(['5', '7', '8', '9']) . fake()->randomNumber(7, true);

        return [
            'name' => $name,
            'login' => strtoupper(substr(explode(' ', $name)[0], 0, 5)) . substr($phone, 0, 3),
            'phone' => $phone,
            'location' => fake()->city(),
            'nic' => fake()->randomNumber(9, true),
            'is_active' => fake()->boolean(60),
            'password' => 'password',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
