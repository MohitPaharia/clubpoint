<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        // Create 20 clubs with existing users as owners
        foreach ($users->random(20) as $user) {
            Club::factory()->create([
                'owner_id' => $user->id,
            ]);
        }

        // Create 5 pending approval clubs
        foreach ($users->random(5) as $user) {
            Club::factory()->pending()->create([
                'owner_id' => $user->id,
            ]);
        }
    }
}