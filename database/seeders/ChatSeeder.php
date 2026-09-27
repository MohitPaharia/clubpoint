<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        $clubs = Club::where('approved', true)->with('members')->get();
        $allUsers = User::all();

        foreach ($clubs as $club) {
            // Get members or use random users if club has no members yet
            $users = $club->members->count() > 0 
                ? $club->members 
                : $allUsers->random(5);

            // Create 10-20 chat messages per club
            $messageCount = rand(10, 20);
            
            for ($i = 0; $i < $messageCount; $i++) {
                Chat::factory()->create([
                    'club_id' => $club->id,
                    'user_id' => $users->random()->id,
                ]);
            }
        }
    }
}