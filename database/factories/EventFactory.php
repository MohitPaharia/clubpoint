<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'name' => fake()->catchPhrase() . ' Event',
            'description' => fake()->paragraph(),
            'address' => fake()->address(),
            'club_id' => Club::factory(),
            'host_id' => User::factory(),
            'country_id' => null, // Set manually or via seeder
            'state_id' => null,
            'city_id' => null,
            'ticket_cost' => fake()->randomFloat(2, 10, 500),
        ];
    }

    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'ticket_cost' => 0.00,
        ]);
    }
}