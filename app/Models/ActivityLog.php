<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    public $timestamps = false; // Custom created_at only

    protected $fillable = [
        'user_id',
        'username',
        'activity_type',
        'description',
        'url',
        'method',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
