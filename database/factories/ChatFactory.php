<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    protected $model = Chat::class;

    public function definition(): array
    {
        return [
            'message' => fake()->sentence(),
            'club_id' => Club::factory(),
            'user_id' => User::factory(),
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}