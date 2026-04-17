<?php

namespace App\Models;

use App\Observers\QueueEntryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([QueueEntryObserver::class])]
class QueueEntry extends Model
{
    protected $fillable = [
        'queue_id',
        'student_id',
        'queue_position',
        'assistance_type',
        'location',
        'task',
        'information',
        'joined_at',
        'left_at',
    ];

    protected $casts = [
        'left_at'=>'datetime',
        'joined_at'=>'datetime',
    ];

    public function queue() {
        return $this->belongsTo(Queue::class);
    }

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }
}
