<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $clubs = Club::where('approved', true)->get();
        $users = User::all();

        // Create 3-5 events for each approved club
        foreach ($clubs as $club) {
            $eventCount = rand(3, 5);
            
            for ($i = 0; $i < $eventCount; $i++) {
                // Host can be the club owner or a random member
                $host = rand(0, 1) === 0 ? $club->owner : $users->random();

                Event::factory()->create([
                    'club_id' => $club->id,
                    'host_id' => $host->id,
                ]);
            }
        }

        // Create some free events
        Event::factory(10)->free()->create([
            'club_id' => $clubs->random()->id,
            'host_id' => $users->random()->id,
        ]);
    }
}