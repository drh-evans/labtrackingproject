<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueEntry extends Model
{
    protected $fillable = [
        'queue_id',
        'student_id',
        'position',
        'joined_at',
    ];

    public function queue() {
        return $this->belongsTo(Queue::class);
    }

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }
}
