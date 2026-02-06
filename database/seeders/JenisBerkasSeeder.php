<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisBerkasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $berkas = [
            // Wajib
            ['kode' => 'RAPOR_1', 'nama_berkas' => 'Rapor Semester 1', 'urutan' => 1, 'is_required' => true],
            ['kode' => 'RAPOR_2', 'nama_berkas' => 'Rapor Semester 2', 'urutan' => 2, 'is_required' => true],
            ['kode' => 'RAPOR_3', 'nama_berkas' => 'Rapor Semester 3', 'urutan' => 3, 'is_required' => true],
            ['kode' => 'RAPOR_4', 'nama_berkas' => 'Rapor Semester 4', 'urutan' => 4, 'is_required' => true],
            ['kode' => 'RAPOR_5', 'nama_berkas' => 'Rapor Semester 5', 'urutan' => 5, 'is_required' => true],
            ['kode' => 'RAPOR_6', 'nama_berkas' => 'Rapor Semester 6', 'urutan' => 6, 'is_required' => true],
            ['kode' => 'IJAZAH', 'nama_berkas' => 'Ijazah', 'urutan' => 7, 'is_required' => true],
            ['kode' => 'TRANSKIP_NILAI', 'nama_berkas' => 'Transkip Nilai', 'urutan' => 8, 'is_required' => true],
            ['kode' => 'KK', 'nama_berkas' => 'Kartu Keluarga', 'urutan' => 9, 'is_required' => true],
            ['kode' => 'SURAT_PERNYATAAN_SEHAT', 'nama_berkas' => 'Surat Pernyataan Keaslian Hasil Uji Kesehatan', 'urutan' => 10, 'is_required' => true],
            ['kode' => 'HASIL_TES_KESEHATAN', 'nama_berkas' => 'Berkas Hasil Uji Kesehatan', 'urutan' => 11, 'is_required' => true],
            ['kode' => 'PAS_FOTO', 'nama_berkas' => 'Pas Foto 3x4', 'urutan' => 12, 'is_required' => true],
            
            // Opsional
            ['kode' => 'KTP_PESERTA', 'nama_berkas' => 'KTP (jika memiliki)', 'urutan' => 13, 'is_required' => false],
            ['kode' => 'SKTM', 'nama_berkas' => 'SKTM (Surat Keterangan Tidak Mampu)', 'urutan' => 14, 'is_required' => false],
            ['kode' => 'SURAT_YATIM', 'nama_berkas' => 'Surat Keterangan Yatim/Piatu', 'urutan' => 15, 'is_required' => false],
            ['kode' => 'SERTIFIKAT_HAFIZ', 'nama_berkas' => 'Sertifikat Hafiz Quran', 'urutan' => 16, 'is_required' => false],
            ['kode' => 'SERTIFIKAT_LOMBA', 'nama_berkas' => 'Sertifikat Perlombaan Tingkat Kab/Kota', 'urutan' => 17, 'is_required' => false],
        ];

        foreach ($berkas as $bk) {
            $bk['created_at'] = now();
            $bk['updated_at'] = now();
            DB::table('jenis_berkas')->insert($bk);
        }
    }
}
