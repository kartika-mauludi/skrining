<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->integer('no_absen')->unique();
            $table->string('nis', 20)->unique();
            $table->string('nama_lengkap', 200);
            $table->date('tgl_lahir');
            $table->string('tempat_lahir', 100);
            $table->text('alamat')->nullable();
            $table->string('nama_wali', 200)->nullable();
            $table->string('no_tlp_wali', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
