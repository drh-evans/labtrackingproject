<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{

    protected $fillable = [
        'module_code',
        'module_name',
        'module_leader_id',
        'year_taught',
        'year_group',
        'bg_image_url'
    ];
    
    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function labTasks() {
        return $this->hasMany(LabTask::class);
    }

    public function queue() {
        return $this->hasOne(Queue::class);
    }

    public function chatroom() {
        return $this->hasOne(Chatroom::class);
    }

    public function leader() {
        return $this->belongsTo(User::class, 'module_leader_id');
    }
}
