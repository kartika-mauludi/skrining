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
        Schema::table('hasil_scores', function (Blueprint $table) {
            $table->integer('skor_korban');
            $table->integer('skor_pelaku');
            $table->dropColumn('skor');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_scores', function (Blueprint $table) {
            $table->integer('skor');
        });
    }
};
