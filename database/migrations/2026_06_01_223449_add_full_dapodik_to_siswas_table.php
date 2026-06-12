<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('siswas', function (Blueprint $table) {
            // Cek satu-satu biar gak nabrak kalau kolomnya udah ada
            if (!Schema::hasColumn('siswas', 'agama')) $table->string('agama')->nullable();
            if (!Schema::hasColumn('siswas', 'anak_ke')) $table->string('anak_ke')->nullable();
            if (!Schema::hasColumn('siswas', 'jml_saudara')) $table->string('jml_saudara')->nullable();
            
            if (!Schema::hasColumn('siswas', 'jenis_tinggal')) $table->string('jenis_tinggal')->nullable();
            if (!Schema::hasColumn('siswas', 'alat_transportasi')) $table->string('alat_transportasi')->nullable();
            if (!Schema::hasColumn('siswas', 'telepon')) $table->string('telepon')->nullable();
            if (!Schema::hasColumn('siswas', 'hp')) $table->string('hp')->nullable();
            if (!Schema::hasColumn('siswas', 'email')) $table->string('email')->nullable();
            if (!Schema::hasColumn('siswas', 'jarak_rumah')) $table->string('jarak_rumah')->nullable();
            if (!Schema::hasColumn('siswas', 'lingkar_kepala')) $table->string('lingkar_kepala')->nullable();

            // Data Ayah
            if (!Schema::hasColumn('siswas', 'nama_ayah')) $table->string('nama_ayah')->nullable();
            if (!Schema::hasColumn('siswas', 'nik_ayah')) $table->string('nik_ayah', 20)->nullable();
            if (!Schema::hasColumn('siswas', 'tahun_lahir_ayah')) $table->string('tahun_lahir_ayah', 4)->nullable();
            if (!Schema::hasColumn('siswas', 'pendidikan_ayah')) $table->string('pendidikan_ayah')->nullable();
            if (!Schema::hasColumn('siswas', 'pekerjaan_ayah')) $table->string('pekerjaan_ayah')->nullable();
            if (!Schema::hasColumn('siswas', 'penghasilan_ayah')) $table->string('penghasilan_ayah')->nullable();

            // Data Ibu
            if (!Schema::hasColumn('siswas', 'nama_ibu')) $table->string('nama_ibu')->nullable();
            if (!Schema::hasColumn('siswas', 'nik_ibu')) $table->string('nik_ibu', 20)->nullable();
            if (!Schema::hasColumn('siswas', 'tahun_lahir_ibu')) $table->string('tahun_lahir_ibu', 4)->nullable();
            if (!Schema::hasColumn('siswas', 'pendidikan_ibu')) $table->string('pendidikan_ibu')->nullable();
            if (!Schema::hasColumn('siswas', 'pekerjaan_ibu')) $table->string('pekerjaan_ibu')->nullable();
            if (!Schema::hasColumn('siswas', 'penghasilan_ibu')) $table->string('penghasilan_ibu')->nullable();
        });
    }

    public function down() {
    }
};