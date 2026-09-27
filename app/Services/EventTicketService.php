<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EventTicketService
{
    /**
     * Handle ticket purchase for an event.
     *
     * @throws \Exception
     */
    public function purchaseTicket(Event $event, User $user): void
    {
        DB::transaction(function () use ($event, $user) {

            if ($event->club->members->contains($user)) {
                throw new \Exception("Members of clubs do not require a ticket!");
            }

            // 1. Already participating?
            if ($event->participants()->where('user_id', $user->id)->exists()) {
                throw new \Exception("User already purchased a ticket for this event.");
            }

            // 2. Check user balance
            if ($user->credit < $event->ticket_cost) {
                throw new \Exception("Insufficient balance.");
            }

            // 3. Deduct user's credit
            $user->decrement('credit', $event->ticket_cost);

            // 4. Add funds to the club
            $event->club->increment('funds', $event->ticket_cost);

            // 5. Register user as event participant
            $event->participants()->attach($user->id);
        });
    }
}
