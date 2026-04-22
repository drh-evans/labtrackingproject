<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;
    protected $fillable = [
        'is_open',
    ];

    public function module() {
        return $this->belongsTo(Module::class);
    }

    public function queueEntries() {
        return $this->hasMany(QueueEntry::class);
    }

    public function reorderPositions()
    {
        $entries = $this->queueEntries()
        ->whereNull('left_at')
        ->orderBy('joined_at')
        ->get();

        foreach ($entries as $index => $entry) {
            $entry->update(['queue_position' => $index + 1]);
        }
    }
}
