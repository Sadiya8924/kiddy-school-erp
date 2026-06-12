<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('siswas', function (Blueprint $table) {
            // Kita tambahin alamat sekalian buat jaga-jaga kalau memang belum ada
            if (!Schema::hasColumn('siswas', 'alamat')) {
                $table->string('alamat')->nullable();
            }
            
            // Hapus ->after() biar otomatis ditaruh di akhir tabel
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('dusun')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kode_pos', 10)->nullable();
        });
    }

    public function down() {
        Schema::table('siswas', function (Blueprint $table) {
            if (Schema::hasColumn('siswas', 'alamat')) {
                $table->dropColumn('alamat');
            }
            $table->dropColumn(['rt', 'rw', 'dusun', 'kelurahan', 'kecamatan', 'kode_pos']);
        });
    }
};