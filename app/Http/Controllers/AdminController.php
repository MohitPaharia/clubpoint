<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\User;

class AdminController extends Controller
{
    public function index() {

        $unapprovedClubs = Club::where('approved', false)->get();
        $recentUsers     = $users = User::latest()->take(10)->get();

        return view('admin.dashboard', [
            'clubs' => $unapprovedClubs,
            'recent_users' => $recentUsers
        ]);
    }
    
    public function approveClub(Club $club) {
        $club->approve();
        return response()->json([
            'success' => true,
            'message' => 'Club approved!'
        ]);
    }

    public function rejectClub(Club $club) {
        $club->delete();
        return response()->json([
            'success' => true,
            'message' => 'Club Rejected!'
        ]);
    }

    public function removeUser(User $user) {
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User Deleted!'
        ]);
    }
}
