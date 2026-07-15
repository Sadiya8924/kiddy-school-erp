<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('no_kk')->nullable()->after('nik'); // Tambah No KK
            $table->string('lintang')->nullable()->after('koordinat_rumah'); // Tambah Garis Lintang
            $table->string('bujur')->nullable()->after('lintang'); // Tambah Garis Bujur
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['no_kk', 'lintang', 'bujur']);
        });
    }
};