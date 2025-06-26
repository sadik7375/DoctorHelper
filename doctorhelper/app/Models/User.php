<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'doctor_id', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // JWT methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Relationships
    public function staff()
    {
        return $this->hasMany(User::class, 'doctor_id')->where('role', 'staff');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}


