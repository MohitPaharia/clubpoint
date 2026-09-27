<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;

class LocationController extends Controller
{
    public function countries(Request $request) 
    {
        return response()->json(['countries' => Country::all()]);
    }

    public function states(Country $country) 
    {
        return response()->json(['states' => $country->states]);
    }

    public function city(State $state) 
    {
        return response()->json(['cities' => $state->cities]);
    }
}
