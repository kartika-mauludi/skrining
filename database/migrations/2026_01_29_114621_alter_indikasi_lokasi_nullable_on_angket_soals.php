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
            $table->enum('indikasi_siswa', ['pelaku', 'korban'])
                  ->nullable()
                  ->change();

            $table->enum('lokasi_kejadian', ['sosmed', 'game', 'lingkungan kelas', 'lainnya'])
                  ->nullable()
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('angket_soals', function (Blueprint $table) {
            $table->enum('indikasi_siswa', ['pelaku', 'korban'])
                  ->nullable(false)
                  ->change();

            $table->enum('lokasi_kejadian', ['sosmed', 'game', 'lingkungan kelas', 'lainnya'])
                  ->nullable(false)
                  ->change();
        });
    }
};
