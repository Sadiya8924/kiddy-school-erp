<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('kepala_sekolah')->nullable()->after('alamat');
            $table->string('telepon')->nullable()->after('kepala_sekolah');
            $table->string('tahun_ajaran_aktif')->nullable()->after('telepon');
            $table->string('semester_aktif')->nullable()->after('tahun_ajaran_aktif');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['kepala_sekolah', 'telepon', 'tahun_ajaran_aktif', 'semester_aktif']);
        });
    }
};