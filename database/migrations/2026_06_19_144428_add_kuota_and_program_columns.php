<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah pengaturan kuota di tabel sekolah
        Schema::table('schools', function (Blueprint $table) {
            $table->integer('kuota_tk_pagi')->default(25)->after('status');
            $table->integer('kuota_tk_siang')->default(25)->after('kuota_tk_pagi');
            $table->integer('kuota_paud')->default(25)->after('kuota_tk_siang');
        });

        // 2. Tambah kolom pilihan program di pendaftaran siswa
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('program_pilihan')->nullable()->after('nama_lengkap');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['kuota_tk_pagi', 'kuota_tk_siang', 'kuota_paud']);
        });
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('program_pilihan');
        });
    }
};