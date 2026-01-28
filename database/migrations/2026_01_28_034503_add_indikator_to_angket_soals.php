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
        Schema::table('angket_soals', function (Blueprint $table) {
             $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
             $table->foreignId('sekolah_id')->constrained('sekolahs')->cascadeOnDelete();
             $table->enum('indikasi_siswa', ['pelaku', 'korban']);
             $table->enum('lokasi_kejadian', ['sosmed', 'game', 'lingkungan kelas', 'lainnya']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angket_soals', function (Blueprint $table) {
            //
        });
    }
};
