<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Club;
use App\Models\Event;
use App\Models\Chat;

class ClubController extends Controller
{
    public function dashboard(Club $club) 
    {
        $club->load([
            'members', 
            'events', 
            'joinRequests', 
            'chats.user',
            'owner',
            'city',
            'state'
        ]);

        return view('club.dashboard', [
            'club' => $club
        ]);
    }

    public function create(Request $request)
    {
        return view('club.create');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        $club = Club::create([
            'name' => $request->name,
            'description'=> $request->description,
            'owner_id' => auth()->id(),
            'country_id' => $request->country,
            'state_id'=> $request->state,
            'city_id' => $request->city
        ]);   
        
        $club->members()->attach(auth()->id());

        return redirect()->route('club.dashboard', [
            'club' => $club->id
        ]);
    }
    public function sendJoinRequest(Club $club)
    {
        $user = auth()->user();

        if ($club->members()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You are already a member of this club.'
            ], 400);
        }

        if ($club->joinRequests()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already sent a join request.'
            ], 400);
        }

        $club->joinRequests()->attach($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Join request sent! The club owner will review it soon.'
        ]);
    }

    public function acceptJoinRequest(Club $club, User $user) 
    {
        $club->members()->attach($user->id);
        $club->joinRequests()->detach($user->id);
        return response()->json([
            'success' => true,
            'message' => $user->first_name . ' ' . $user->last_name . ' joined the Club!'
        ]);
    }

    public function rejectJoinRequest(Club $club, User $user)
    {
        $club->joinRequests()->detach($user->id);
        return response()->json([
            'success' => true,
            'message' => 'Join request rejected!'
        ]);
    }

    public function blockUser(Club $club, User  $user)
    {
        $club->blockedUsers()->attach($user->id);
        $club->joinRequests()->detach($user->id);   
        $club->members()->detach($user->id);
        return response()->json([
            'success' => true,
            'message' => $user->first_name . ' ' . $user->last_name . ' is blocked from Club!'
        ]);
    }

    public function unblockUser(Club $club, User $user)
    {
        $club->blockedUsers()->detach($user->id);
        return response()->json([
            'success' => true,
            'message' => $user->first_name . ' ' . $user->last_name . ' is unblocked from Club!'
        ]);
    }

    public function removeMember(Club $club, User $user) 
    {
        // Allow removal if the authenticated user is either:
        // 1. The club owner (removing someone else)
        // 2. The user themselves (leaving the club)
        if (auth()->user()->id != $user->id && auth()->user()->id != $club->owner->id) {
            return response()->json([
                'success' => false,
                'message' => 'Not Allowed.'
            ], 400);
        }

        // Prevent owner from removing themselves
        if (auth()->user()->id === $club->owner->id && $user->id === $club->owner->id) {
            return response()->json([
                'success' => false,
                'message' => 'Club owners cannot leave their own club.'
            ], 400);
        }

        $club->members()->detach($user->id);

        if (auth()->user()->id !== $user->id) {
            return redirect('/');
        }

        return response()->json([
            'success' => true,
            'message' => $user->first_name . ' ' . $user->last_name . ' is removed from Club!'
        ]);
    }
}
