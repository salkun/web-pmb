<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelulusanTable extends Migration
{
    public function up()
    {
        Schema::create('kelulusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->foreignId('pengaturan_ujian_id')->constrained('pengaturan_ujian')->onDelete('restrict');
            $table->enum('status', ['lulus', 'tidak_lulus', 'pending'])->default('pending');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->dateTime('tanggal_pengumuman')->nullable();
            $table->boolean('is_published')->default(false);
            $table->foreignId('diumumkan_oleh')->nullable()->constrained('users');
            $table->timestamps();

            $table->index('status');
            $table->index('is_published');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelulusan');
    }
}
