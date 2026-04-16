<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Chatroom;
use App\Models\Queue;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('queues.{queueId}', function ($user, $queueId) {
    $queue = Queue::with('module.users')->findOrFail($queueId);

    $isMember = $queue->module->users->contains($user->id);
    $isAdmin = $user->hasRole("Admin");
    
    $isOpen = $queue->is_open;

    $canAccess = ($isMember || $isAdmin) && $isOpen;
    return $canAccess;
});

Broadcast::channel('chatroom.{chatroomId}', function ($user, $chatroomId) {
    $chatroom = Chatroom::with('module.users')->findOrFail($chatroomId);
    
    $isMember = $chatroom->module->users->contains($user->id);
    $isOpen = $chatroom->is_open;

    //TODO Reimplement the isopen bit
    $canAccess = $isMember; // && $isOpen;

    return $canAccess;
});
