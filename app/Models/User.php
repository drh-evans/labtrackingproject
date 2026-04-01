<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PhpParser\Node\Expr\Cast\Bool_;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $role): bool {
        return $this->roles()->where('name', $role)->exists();
    }

    public function modules() {
        return $this->belongsToMany(Module::class);
    }

    public function createdLabTasks() {
        return $this->hasMany(LabTask::class, 'created_by');
    }

    public function completedLabTasks() {
        return $this->hasMany(LabTaskUser::class, 'user_id');
    }

    public function signOffs() {
        return $this->hasMany(LabTaskUser::class, 'signed_off_by');
    }

    public function queueEntries() {
        return $this->hasMany(QueueEntry::class, 'student_id');
    }

    public function messages() {
        return $this->hasMany(Message::class, 'user_id');
    }
}
