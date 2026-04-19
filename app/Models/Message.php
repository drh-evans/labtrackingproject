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

    public function getDisplayName(User $messageViewer) {

    // if the message's originator is the same as the viewer, it is the viewer
        if($this->user_id === $messageViewer->id) {
            return 'Me';
        }

        $senderIsStaff = $this->user->hasRole('Lecturer') || 
        $this->user->hasRole('Teaching Assistant') || 
        $this->user->hasRole('Admin');

        $viewerIsStaff = $messageViewer->hasRole('Lecturer') || 
        $messageViewer->hasRole('Teaching Assistant') || 
        $messageViewer->hasRole('Admin');

        // if the sender is a member of staff, everyone can see their name
        // otherwise, if the viewer is a member of staff they can see everyones names
        if($senderIsStaff || $viewerIsStaff) {
            return $this->user->name;
        }

        return 'Anonymous Student';
    }
}
