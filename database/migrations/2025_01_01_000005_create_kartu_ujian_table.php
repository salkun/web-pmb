<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKartuUjianTable extends Migration
{
    public function up()
    {
        Schema::create('kartu_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->foreignId('pengaturan_ujian_id')->constrained('pengaturan_ujian')->onDelete('restrict');
            $table->string('nomor_peserta', 20)->unique();
            $table->string('ruangan', 50)->nullable();
            $table->string('nomor_kursi', 10)->nullable();
            $table->string('barcode', 255)->nullable();
            $table->timestamp('generated_at');
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamp('downloaded_at')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamps();

            $table->index('nomor_peserta');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kartu_ujian');
    }
}
