<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClubFactory extends Factory
{
    protected $model = Club::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Club',
            'description' => fake()->paragraph(),
            'approved' => fake()->boolean(70), // 70% approved
            'funds' => fake()->randomFloat(2, 0, 50000),
            'owner_id' => User::factory(),
            'country_id' => null, // Set manually or via seeder
            'state_id' => null,
            'city_id' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'approved' => true,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'approved' => false,
        ]);
    }
}