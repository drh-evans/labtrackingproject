<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'module_id',
        'created_by',
        'task_name',
        'description',
        'total_points',
        'fm_due_date',
        'hm_due_date',
    ];

    protected $casts = [
        'fm_due_date' => 'datetime',
        'hm_due_date' => 'datetime',
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function module() {
        return $this->belongsTo(Module::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'lab_task_users')->withTimestamps();
    }

    public function completions() {
        return $this->hasMany(LabTaskUser::class, 'lab_task_id');
    }

    // Helper function to get how much marks are getting awarded.
    public function getMarksType(string $type) {
        $marks = match ($type) {
            'half' => $this->total_points / 2.0,
            'full' => $this->total_points,
            default => 0.0,
        };
        return $marks;
    }
}
