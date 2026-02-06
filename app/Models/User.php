<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'no_pendaftaran',
        'password',
        'role',
        'is_active',
        'last_login',
        'reset_token',
        'reset_token_expired'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'reset_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
        'reset_token_expired' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    // Relations
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function berkas()
    {
        return $this->hasMany(BerkasMahasiswa::class);
    }

    public function kartuUjian()
    {
        return $this->hasOne(KartuUjian::class);
    }

    public function kelulusan()
    {
        return $this->hasOne(Kelulusan::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}