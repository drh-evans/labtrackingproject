<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

// Event for message being sent
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chatroom.' . $this->message->chatroom_id),
        ];
    }

    public function broadcastWith() : array
    {
        return [
            'id'         => $this->message->id,
            'body'       => $this->message->body,
            'created_at' => $this->message->created_at->toTimeString(),
            'user' => [
                'id'   => $this->message->user->id,
                'name' => $this->message->user->name,
            ],
        ]; 
    }
}
