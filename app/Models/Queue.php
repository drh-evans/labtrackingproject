<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'is_open',
    ];

    public function module() {
        return $this->belongsTo(Module::class);
    }

    public function queueEntries() {
        return $this->hasMany(QueueEntry::class)
        ->orderBy('position');
    }
}
