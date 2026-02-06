<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBerkasMahasiswaTable extends Migration
{
    public function up()
    {
        Schema::create('berkas_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jenis_berkas_id')->constrained('jenis_berkas')->onDelete('restrict');
            $table->string('file_path', 255);
            $table->string('file_name', 255);
            $table->string('file_original_name', 255);
            $table->integer('file_size');
            $table->string('mime_type', 50);
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('uploaded_at');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->index('status');
            $table->unique(['user_id', 'jenis_berkas_id'], 'unique_user_berkas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('berkas_mahasiswa');
    }
}
