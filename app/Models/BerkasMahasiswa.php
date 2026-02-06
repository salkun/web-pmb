<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'berkas_mahasiswa';

    protected $fillable = [
        'user_id',
        'jenis_berkas_id',
        'file_path',
        'file_name',
        'file_original_name',
        'file_size',
        'mime_type',
        'status',
        'catatan_admin',
        'uploaded_at',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
        'file_size' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisBerkas()
    {
        return $this->belongsTo(JenisBerkas::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
