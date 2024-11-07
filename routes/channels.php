<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chatroom.{chatroomId}', function ($user, $chatroomId) {
    return $user->chatrooms()->where('chatroom_id', $chatroomId)->exists();
});
