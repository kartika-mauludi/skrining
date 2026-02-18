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
          Schema::table('siswas', function(Blueprint $table) {
            $table->dropUnique(['nis']);
             $table->dropUnique(['no_absen']);

            // 2️⃣ Ubah jadi nullable
            $table->unsignedBigInteger('nis')
                  ->nullable()
                  ->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {

            // Balikkan jadi not nullable
            $table->unsignedBigInteger('nis')
                  ->nullable(false)
                  ->change();

            // Tambahkan lagi foreign key
            $table->string('nis')->unique();
            $table->integer('no_absen')->unique();
        });
        
    }
};
