<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SplitSuratKesehatanRequirements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Find old record
        $oldBerkas = DB::table('jenis_berkas')->where('kode', 'SURAT_KESEHATAN')->first();

        if ($oldBerkas) {
            // Delete dependent uploads first to avoid FK constraint error
            DB::table('berkas_mahasiswa')->where('jenis_berkas_id', $oldBerkas->id)->delete();
            
            // Remove old record
            DB::table('jenis_berkas')->where('id', $oldBerkas->id)->delete();
        }

        // Shift existing orders down to make space if needed, or just insert.
        // Let's just insert with order 7 and 8 (assuming Pas Foto starts at 9 now).
        // To be safe, let's bump everything >= 7 by 1.
        DB::table('jenis_berkas')->where('urutan', '>=', 8)->increment('urutan', 1);

        DB::table('jenis_berkas')->insert([
            [
                'kode' => 'SURAT_PERNYATAAN_SEHAT',
                'nama_berkas' => 'Surat Pernyataan Keaslian Hasil Uji Kesehatan',
                'is_required' => true,
                'urutan' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'HASIL_TES_KESEHATAN',
                'nama_berkas' => 'Berkas Hasil Uji Kesehatan',
                'is_required' => true,
                'urutan' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    public function down()
    {
        DB::table('jenis_berkas')->where('kode', 'SURAT_PERNYATAAN_SEHAT')->delete();
        DB::table('jenis_berkas')->where('kode', 'HASIL_TES_KESEHATAN')->delete();
        
        // Restore old
        DB::table('jenis_berkas')->insert([
            'kode' => 'SURAT_KESEHATAN',
            'nama_berkas' => 'Surat Kesehatan/MCU',
            'is_required' => true,
            'urutan' => 7,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Restore order
        DB::table('jenis_berkas')->where('urutan', '>=', 9)->decrement('urutan', 1);
    }
}
