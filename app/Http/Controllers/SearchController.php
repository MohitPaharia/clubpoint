<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Club;

class SearchController extends Controller
{
    public function clubs(Request $request)
    {
        $query = $request->get("query");

        $clubs = Club::withCount('members')->
            where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })->get();


        return response()->json($clubs);
    }

    public function users(Request $request)
    {
        $query = $request->get("query");

        $users = User::where(function($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%")
                  ->orWhere('id', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })->get();

        return response()->json($users);
    }

    public function events(Request $request)
    {
        $query = $request->get("query");

        $events = Event::with('club:id,name')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })->get();

        return response()->json($events);
    }
}
