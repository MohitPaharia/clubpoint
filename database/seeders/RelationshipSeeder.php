<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class RelationshipSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $clubs = Club::where('approved', true)->get();
        $events = Event::all();

        // Seed club members
        foreach ($clubs as $club) {
            // Add 5-15 random members to each club
            $memberCount = rand(5, 15);
            $randomMembers = $users->random(min($memberCount, $users->count()));
            
            foreach ($randomMembers as $member) {
                // Don't add owner as member (they're already the owner)
                if ($member->id !== $club->owner_id) {
                    $club->members()->attach($member->id);
                }
            }
        }

        // Seed club join requests
        foreach ($clubs->random(10) as $club) {
            // Add 1-5 join requests per club
            $requestCount = rand(1, 5);
            $requesters = $users
                ->whereNotIn('id', $club->members->pluck('id'))
                ->random(min($requestCount, 5));
            
            foreach ($requesters as $requester) {
                if ($requester->id !== $club->owner_id) {
                    $club->joinRequests()->attach($requester->id);
                }
            }
        }

        // Seed blocked users (club blocks user)
        foreach ($clubs->random(5) as $club) {
            $blockedCount = rand(1, 3);
            $blockedUsers = $users->random(min($blockedCount, 3));
            
            foreach ($blockedUsers as $blockedUser) {
                $club->blockedUsers()->attach($blockedUser->id);
            }
        }

        // Seed user blocked clubs (user blocks club)
        foreach ($users->random(10) as $user) {
            $blockedCount = rand(1, 3);
            $blockedClubs = $clubs->random(min($blockedCount, 3));
            
            foreach ($blockedClubs as $blockedClub) {
                $user->blockedClubs()->attach($blockedClub->id);
            }
        }

        // Seed event participants
        foreach ($events as $event) {
            // Add 5-20 participants to each event
            $participantCount = rand(5, 20);
            $participants = $users->random(min($participantCount, $users->count()));
            
            foreach ($participants as $participant) {
                $event->participants()->attach($participant->id);
            }
        }
    }
}