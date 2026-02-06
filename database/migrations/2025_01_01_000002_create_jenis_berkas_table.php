<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisBerkasTable extends Migration
{
    public function up()
    {
        Schema::create('jenis_berkas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_berkas', 100);
            $table->string('kode', 50)->unique();
            $table->boolean('is_required')->default(true);
            $table->text('keterangan')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('urutan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_berkas');
    }
}
