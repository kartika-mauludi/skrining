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
            $table->string('tipe_soal', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angket_soal', function (Blueprint $table) {
             $table->enum('tipe_soal', ['radio', 'checkbox', 'text'])->change();
        });
    }
};
