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
         // Drop foreign key dulu
            $table->dropForeign(['sekolah_id']);
            $table->dropForeign(['guru_id']);

            // Alter kolom
            $table->foreignId('sekolah_id')
                ->nullable()
                ->change();

            $table->foreignId('guru_id')
                ->nullable()
                ->change();

         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
