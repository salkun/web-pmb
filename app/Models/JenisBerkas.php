<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBerkas extends Model
{
    use HasFactory;

    protected $table = 'jenis_berkas';

    protected $fillable = [
        'nama_berkas',
        'kode',
        'is_required',
        'keterangan',
        'urutan',
        'is_active'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'urutan' => 'integer'
    ];

    public function berkas()
    {
        return $this->hasMany(BerkasMahasiswa::class);
    }
}
