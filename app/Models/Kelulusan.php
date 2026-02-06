<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelulusan extends Model
{
    use HasFactory;

    protected $table = 'kelulusan';

    protected $fillable = [
        'user_id',
        'pengaturan_ujian_id',
        'status',
        'nilai',
        'catatan',
        'tanggal_pengumuman',
        'is_published',
        'diumumkan_oleh'
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
        'tanggal_pengumuman' => 'datetime',
        'is_published' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengaturanUjian()
    {
        return $this->belongsTo(PengaturanUjian::class);
    }

    public function announcer()
    {
        return $this->belongsTo(User::class, 'diumumkan_oleh');
    }
}
