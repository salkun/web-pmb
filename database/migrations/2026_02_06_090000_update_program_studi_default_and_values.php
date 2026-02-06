<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateProgramStudiDefaultAndValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update existing records
        DB::table('profiles')
            ->where('program_studi', 'Teknologi Radiologi D4')
            ->orWhere('program_studi', 'Teknologi Radiologi')
            ->update(['program_studi' => 'Teknik Radiologi Pencitraan D4']);

        // Update default value for future records
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('program_studi', 100)->default('Teknik Radiologi Pencitraan D4')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert default value
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('program_studi', 100)->default('Teknologi Radiologi D4')->change();
        });

        // Revert existing records (optional, might be dangerous if we want to keep new data, but for completeness)
        DB::table('profiles')
            ->where('program_studi', 'Teknik Radiologi Pencitraan D4')
            ->update(['program_studi' => 'Teknologi Radiologi D4']);
    }
}
