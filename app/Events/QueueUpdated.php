<?php

namespace App\Events;

use App\Models\Queue;
use App\Models\QueueEntry;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// Event for queue updates such as leaving, joining, and removing.
class QueueUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $id;
    public int $queueId;
    public int $studentId;
    public int $queuePosition;
    public string $joinedAt;
    /**
     * Create a new event instance.
     */
    public function __construct(QueueEntry $entry)
    {
        $this->id = $entry->id;
        $this->queueId = $entry->queue_id;
        $this->studentId = $entry->student_id;
        $this->queuePosition = $entry->queue_position;
        $this->joinedAt = $entry->joined_at;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('queues.'.$this->queueId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'queue-updated';
    }

    public function broadcastWith(): array
    {
        return [
            'queue' => [
                'id' => $this->id,
                'queue_id' => $this->queueId,
                'student_id' => $this->studentId,
                'queue_position' => $this->queuePosition,
                'joined_at' => $this->joinedAt,
            ]
        ];
    }
}