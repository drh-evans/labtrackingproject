<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatroom extends Model
{
    use HasFactory;
    protected $fillable = ['module_id', 'name', 'is_open'];

    public function module() {
        return $this->belongsTo(Module::class);
    }

    public function message() {
        return $this->hasMany(Message::class);
    }
}
