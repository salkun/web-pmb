<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddMissingBerkasTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Shift existing orders down to make space.
        // Existing order 6+ will be shifted by 3.
        DB::table('jenis_berkas')->where('urutan', '>=', 6)->increment('urutan', 3);

        DB::table('jenis_berkas')->insert([
            [
                'kode' => 'RAPOR_6',
                'nama_berkas' => 'Rapor Semester 6',
                'is_required' => true,
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'IJAZAH',
                'nama_berkas' => 'Ijazah',
                'is_required' => true,
                'urutan' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'TRANSKIP_NILAI',
                'nama_berkas' => 'Transkip Nilai',
                'is_required' => true,
                'urutan' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('jenis_berkas')->whereIn('kode', ['RAPOR_6', 'IJAZAH', 'TRANSKIP_NILAI'])->delete();
        
        // Restore order
        DB::table('jenis_berkas')->where('urutan', '>', 5)->decrement('urutan', 3);
    }
}
