<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Club;

class ChatController extends Controller
{
    public function store(Request $request, Club $club)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $chat = Chat::create([
            'message' => $validated['message'],
            'user_id' => auth()->id(),
            'club_id' => $club->id,
        ]);

        $chat = Chat::with('user')->find($chat->id);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $chat->id,
                'user_id' => $chat->user_id,
                'user_name' => $chat->user->first_name . ' ' . $chat->user->last_name,
                'message' => $chat->message,
                'created_at' => $chat->created_at
            ]
        ]);
    }

    public function pollChats(Request $request, Club $club)
    {
        // Check if user is a member or owner
        if (!$club->members()->where('user_id', auth()->id())->exists() && auth()->id() !== $club->owner_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    
        $afterId = $request->query('after', 0);
        
        $newMessages = $club->chats()
            ->where('id', '>', $afterId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($chat) {
                return [
                    'id' => $chat->id,
                    'user_id' => $chat->user_id,
                    'user_name' => $chat->user->first_name . ' ' . $chat->user->last_name,
                    'message' => $chat->message,
                    'created_at' => $chat->created_at
                ];
            });
        
        return response()->json([
            'success' => true,
            'messages' => $newMessages
        ]);
    }

    public function delete(Chat $chat)
    {
        if (auth()->id() !== $chat->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to perform this action!'
            ]);
        }

        $chat->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
