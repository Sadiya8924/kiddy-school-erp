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
        // Cek dulu, kalau kolom nisn belum ada, baru dibikin (biar nggak error kalau telanjur kebuat)
        if (!Schema::hasColumn('siswas', 'nisn')) {
            Schema::table('siswas', function (Blueprint $table) {
                $table->string('nisn', 50)->nullable();
            });
        }

        // Bikin kolom status di tabel rapors (langsung taruh di urutan paling belakang aja biar aman)
        if (!Schema::hasColumn('rapors', 'status')) {
            Schema::table('rapors', function (Blueprint $table) {
                $table->string('status', 50)->default('draft');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('siswas', 'nisn')) {
            Schema::table('siswas', function (Blueprint $table) {
                $table->dropColumn('nisn');
            });
        }

        if (Schema::hasColumn('rapors', 'status')) {
            Schema::table('rapors', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};