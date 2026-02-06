<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanUjian extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_ujian';

    protected $fillable = [
        'tahun_akademik',
        'gelombang',
        'tanggal_ujian',
        'waktu_mulai',
        'waktu_selesai',
        'tempat_ujian',
        'alamat_lengkap',
        'kuota',
        'peraturan_ujian',
        'is_active'
    ];

    protected $casts = [
        'tanggal_ujian' => 'date',
        // 'waktu_mulai' => 'datetime:H:i', // Format time in UI usually
        // 'waktu_selesai' => 'datetime:H:i',
        'kuota' => 'integer',
        'gelombang' => 'integer',
        'is_active' => 'boolean'
    ];

    public function kartuUjian()
    {
        return $this->hasMany(KartuUjian::class);
    }
}
