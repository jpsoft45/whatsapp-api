<?php

namespace App\Http\Controllers;

use App\Models\Chatroom;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
   // Send message
   public function store(Request $request, $chatroomId)
   {
       $request->validate([
           'type' => 'required|in:text,attachment',
           'message' => 'required_if:type,text',
           'attachment' => 'required_if:type,attachment|file',
       ]);

       $chatroom = Chatroom::findOrFail($chatroomId);

       if (!$chatroom->users()->where('user_id', $request->user()->id)->exists()) {
           return response()->json(['message' => 'Not a member of this chatroom'], 403);
       }

       $data = [
           'chatroom_id' => $chatroomId,
           'user_id' => $request->user()->id,
           'message_type' => $request->type,
       ];
    //    dd($request->type);
       if ($request->type == "text") {
        // dd("here");
           $data['message_content'] = $request->message;
       }
       if ($request->type == 'attachment') {
        $file = $request->file('attachment');
        $extension = $file->getClientOriginalExtension();

        $folder = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) ? 'picture' : 'video';

        $path = $file->storeAs($folder, $file->getClientOriginalName(), 'public');

        $data['attachment_path'] = $path;
    }
    // dd($data);
       $message = Message::create($data);

       // Broadcast message using Laravel Reverb
       event(new \App\Events\MessageSent($message));

       return response()->json($message, 201);
   }

   // List messages
   public function index(Request $request, $chatroomId)
   {
       $chatroom = Chatroom::findOrFail($chatroomId);

       if (!$chatroom->users()->where('user_id', $request->user()->id)->exists()) {
           return response()->json(['message' => 'Not a member of this chatroom'], 403);
       }

       $messages = $chatroom->messages()->with('user')->get();

       return response()->json($messages);
   }
}
