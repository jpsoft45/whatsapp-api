<?php

namespace App\Http\Controllers;

use App\Models\Chatroom;
use Illuminate\Http\Request;

class ChatroomController extends Controller
{
    // Create chatroom
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'max_members' => 'required|integer|min:1',
        ]);

        $chatroom = Chatroom::create($validated);

        return response()->json($chatroom, 201);
    }

    // List chatrooms
    public function index()
    {
        $chatrooms = Chatroom::all();

        return response()->json($chatrooms);
    }

    // Enter chatroom
    public function enter(Request $request, $id)
    {
        $chatroom = Chatroom::findOrFail($id);

        if ($chatroom->users()->count() >= $chatroom->max_members) {
            return response()->json(['message' => 'Chatroom is full'], 403);
        }

        $chatroom->users()->attach($request->user()->id);

        return response()->json(['message' => 'Entered chatroom']);
    }

    // Leave chatroom
    public function leave(Request $request, $id)
    {
        $chatroom = Chatroom::findOrFail($id);

        $chatroom->users()->detach($request->user()->id);

        return response()->json(['message' => 'Left chatroom']);
    }
}
