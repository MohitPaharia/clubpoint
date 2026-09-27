<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\Event;
use App\Services\EventTicketService;

class EventController extends Controller
{
    public function show(Club $club, Event $event)
    {
        return view('club.event.show', [
            'event' => $event
        ]);
    }

    public function create(Club $club) 
    {
        return view('club.event.create', [
            'club' => $club
        ]);
    }

    public function store(Club $club, Request $request) 
    {
        $validated = $request->validate([
            'name'        => 'required|max:50',
            'description' => 'required|max:255',
            'address'     => 'required|max:255',
            'event_date'  => 'required|date|after_or_equal:today',
            'event_time'  => 'required|date_format:H:i',
            'host_id'     => 'required|exists:users,id',
            'city_id'     => 'required|exists:cities,id',
            'state_id'    => 'required|exists:states,id',
            'country_id'  => 'required|exists:countries,id',
            'ticket_cost' => 'nullable|numeric|min:0'
        ]);
    
        $event = Event::create([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'address'     => $validated['address'],
            'event_date'  => $validated['event_date'],
            'event_time'  => $validated['event_time'],
            'club_id'     => $club->id,
            'host_id'     => $validated['host_id'],
            'city_id'     => $validated['city_id'],
            'state_id'    => $validated['state_id'],
            'country_id'  => $validated['country_id'],
            'ticket_cost' => $validated['ticket_cost'] ?? 50.00,
        ]);
    
        return redirect()->route('club.event.show', [$club->id, $event->id]);
    }

    public function update(Request $request, Club $club, Event $event) 
    {
        $validated = $request->validate([
            'name'        => 'required|max:50',
            'description' => 'required|max:255',
            'address'     => 'required|max:255',
            'host_id'     => 'required|exists:users,id',
            'city_id'     => 'required|exists:cities,id',
            'state_id'    => 'required|exists:states,id',
            'country_id'  => 'required|exists:countries,id',
            'ticket_cost' => 'nullable|numeric|min:0'
        ]);
        
        $event->update($validated);

        return back()->with('success', 'Event updated!');
    }

    public function delete(Club $club, Event $event)
    {
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event has been removed from the club!'
        ]);
    }

    /**
     * ---------------------------------------------------
     * Purchase a ticket for the event
     * ---------------------------------------------------
     */
    public function purchaseTicket(Club $club, Event $event, EventTicketService $service)
    {
        $user = auth()->user();

        try {
            $service->purchaseTicket($event, $user);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket purchased successfully!'
        ]);
    }
}
