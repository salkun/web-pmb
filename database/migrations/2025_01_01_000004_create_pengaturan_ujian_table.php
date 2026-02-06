<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengaturanUjianTable extends Migration
{
    public function up()
    {
        Schema::create('pengaturan_ujian', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_akademik', 9);
            $table->integer('gelombang');
            $table->date('tanggal_ujian');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('tempat_ujian', 255);
            $table->text('alamat_lengkap');
            $table->integer('kuota')->default(0);
            $table->text('peraturan_ujian')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->index('is_active');
            $table->unique(['tahun_akademik', 'gelombang']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengaturan_ujian');
    }
}
