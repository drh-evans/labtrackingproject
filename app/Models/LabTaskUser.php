<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTaskUser extends Model
{
    protected $fillable = [
        "lab_task_id",
        "user_id",
        "marks_awarded",
        "signed_off_by",
        "completed_at",
    ];

    public function labTask(){
        return $this->belongsTo(LabTask::class);
    }

    public function student() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function signer() {
        return $this->belongsTo(User::class, 'signed_off_by');
    }
}
