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
        Schema::create('angket_soals', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('angket_id')->constrained('angkets')->cascadeOnDelete();
            $table->integer('sequence');
            $table->text('soal');
            $table->enum('tipe_soal', ['radio', 'checkbox', 'text']);
            $table->json('detail_tipe_soal')->nullable();
            $table->integer('bobot')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angket_soals');
    }
};
