<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'jenis_kelamin',
        'program_studi',
        'tempat_lahir',
        'tanggal_lahir',
        'asal_sekolah',
        'tahun_kelulusan',
        'alamat',
        'email_aktif',
        'no_hp_aktif',
        'foto_profil',
        'is_complete',
        'completed_at'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_kelulusan' => 'integer',
        'is_complete' => 'boolean',
        'completed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
