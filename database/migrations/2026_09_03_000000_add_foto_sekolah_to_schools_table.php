<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('schools') && !Schema::hasColumn('schools', 'foto_sekolah')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->string('foto_sekolah')->nullable()->after('logo_sekolah');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('schools') && Schema::hasColumn('schools', 'foto_sekolah')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->dropColumn('foto_sekolah');
            });
        }
    }
};
