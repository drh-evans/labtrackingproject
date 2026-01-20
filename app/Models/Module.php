<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{

    protected $fillable = [
        'module_code',
        'module_name',
        'module_leader',
        'year_taught',
        'teaching_block',
    ];
    
    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function labTasks() {
        return $this->hasMany(LabTask::class);
    }
}
