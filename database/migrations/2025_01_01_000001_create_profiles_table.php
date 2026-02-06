<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nama_lengkap', 150);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('program_studi', 100)->default('Teknik Radiologi Pencitraan D4');
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->string('asal_sekolah', 150);
            $table->year('tahun_kelulusan');
            $table->text('alamat');
            $table->string('email_aktif', 100);
            $table->string('no_hp_aktif', 15);
            $table->string('foto_profil', 255)->nullable();
            $table->boolean('is_complete')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('email_aktif');
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
