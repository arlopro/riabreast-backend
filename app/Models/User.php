<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['role', 'email', 'password', 'pin', 'data'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'pin' => 'hashed',
        'data' => 'array',
        'role' => RoleEnum::class,
    ];

    public function rehabSessions()
    {
        return $this->hasMany(RehabSession::class);
    }

    public function rehabProgresses()
    {
        return $this->hasMany(RehabUserProgress::class);
    }

    public function currentProgress()
    {
        return $this->rehabProgresses()->where('is_active', true)->first();
    }

}
