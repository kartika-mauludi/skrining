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
            $table->foreignId('id_siswa_pelaku')->nullable()->constrained('siswas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawaban', function (Blueprint $table) {
            $table->dropForeign(['id_siswa_pelaku']);
            $table->dropColumn('id_siswa_pelaku');
        });
    }
};
