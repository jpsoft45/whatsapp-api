<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chatroom extends Model
{
    protected $fillable = ['name', 'max_members'];
    public function users()
    {
        return $this->belongsToMany(User::class, 'chatroom_user');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
