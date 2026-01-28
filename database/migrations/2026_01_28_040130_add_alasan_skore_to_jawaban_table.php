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
        Schema::table('jawaban', function (Blueprint $table) {
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            // alasan untuk jawaban lokal
            $table->string('alasan');
            // skor untuk jawaban soal range
            $table->integer('skor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawaban', function (Blueprint $table) {
            //
        });
    }
};
