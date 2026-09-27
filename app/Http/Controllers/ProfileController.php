<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('profile');
    }

    public function deleteUser(Request $request) {
        $user = $request->user();
        $user->delete();
        return redirect('/');
    }
}
