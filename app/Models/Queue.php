<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'lab_task_id',
        'is_open',
    ];

    public function labTask() {
        return $this->belongsTo(LabTask::class);
    }

    public function queueEntries() {
        return $this->hasMany(QueueEntry::class)
        ->orderBy('position');
    }
}
