<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\MessageObserver;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([MessageObserver::class])]
class Message extends Model
{
    protected $fillable = ['chatroom_id', 'user_id', 'body', 'edited_at'];

    public function chatroom() {
        return $this->belongsTo(Chatroom::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
