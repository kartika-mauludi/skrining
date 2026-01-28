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
         Schema::table('sekolahs', function (Blueprint $table) {
            $table->string('nama_sekolah', 100)->change();
            $table->string('no_tlp', 30)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('sekolahs', function (Blueprint $table) {
            $table->string('nama_sekolah', 20)->change();
            $table->string('no_tlp', 20)->change();
        });
    }
};
