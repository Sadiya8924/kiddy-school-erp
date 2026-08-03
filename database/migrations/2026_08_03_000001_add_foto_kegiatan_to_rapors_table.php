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
        if (!Schema::hasColumn('rapors', 'foto_kegiatan')) {
            Schema::table('rapors', function (Blueprint $table) {
                $table->json('foto_kegiatan')->nullable()->after('nilai_json');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('rapors', 'foto_kegiatan')) {
            Schema::table('rapors', function (Blueprint $table) {
                $table->dropColumn('foto_kegiatan');
            });
        }
    }
};
