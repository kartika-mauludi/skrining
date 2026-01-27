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
         Schema::table('angkets', function (Blueprint $table) {

            // Drop foreign key dulu
            $table->dropForeign(['sekolah_id']);
            $table->dropForeign(['kelas_id']);

            // Alter kolom
            $table->foreignId('sekolah_id')
                ->nullable()
                ->change();

            $table->foreignId('kelas_id')
                ->nullable()
                ->change();

            $table->string('akses_token')
                ->nullable()
                ->change();

            $table->enum('owner', ['admin', 'guru'])
                ->nullable()
                ->change();
        });

        // Tambahkan kembali foreign key dengan nullOnDelete
        Schema::table('angkets', function (Blueprint $table) {
            $table->foreign('sekolah_id')
                ->references('id')
                ->on('sekolahs')
                ->nullOnDelete();

            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angkets', function (Blueprint $table) {

            // Drop FK
            $table->dropForeign(['sekolah_id']);
            $table->dropForeign(['kelas_id']);

            // Balikin ke NOT NULL
            $table->foreignId('sekolah_id')
                ->nullable(false)
                ->change();

            $table->foreignId('kelas_id')
                ->nullable(false)
                ->change();

            $table->string('akses_token')
                ->nullable(false)
                ->change();

            $table->enum('owner', ['admin', 'guru'])
                ->nullable(false)
                ->default('admin')
                ->change();
        });

        // Restore FK cascade
        Schema::table('angkets', function (Blueprint $table) {
            $table->foreign('sekolah_id')
                ->references('id')
                ->on('sekolahs')
                ->cascadeOnDelete();

            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
                ->cascadeOnDelete();
        });
    }
};
