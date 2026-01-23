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
        Schema::create('angket_hasils', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('angket_id')->constrained('angkets')->restrictOnDelete();
            $table->foreignId('sekolah_id')->constrained('sekolahs')->restrictOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->restrictOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->restrictOnDelete();
            $table->datetime('datetime')->default(now());
            $table->enum('indikasi_siswa', ['pelaku', 'korban']);
            $table->enum('level_bullying', ['aman', 'rentan', 'parah']);
            $table->enum('lokasi_kejadian', ['sosmed', 'game', 'lingkungan kelas', 'lainnya']);
            $table->string('lokasi_kejadian_tambahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angket_hasils');
    }
};
