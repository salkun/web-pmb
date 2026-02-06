<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuUjian extends Model
{
    use HasFactory;

    protected $table = 'kartu_ujian';

    protected $fillable = [
        'user_id',
        'pengaturan_ujian_id',
        'nomor_peserta',
        'ruangan',
        'nomor_kursi',
        'barcode',
        'generated_at',
        'generated_by',
        'downloaded_at',
        'download_count'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'downloaded_at' => 'datetime',
        'download_count' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengaturanUjian()
    {
        return $this->belongsTo(PengaturanUjian::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
