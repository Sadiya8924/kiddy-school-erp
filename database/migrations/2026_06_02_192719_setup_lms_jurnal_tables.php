<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // 1. Tambah kelompok di tabel Kelas
        Schema::table('kelas', function (Blueprint $table) {
            $table->enum('kelompok', ['A', 'B', 'PAUD'])->nullable()->after('nama_kelas');
        });

        // 2. Bikin tabel Materi Jurnal (Kurikulum dari Admin)
        Schema::create('materi_jurnals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id');
            $table->enum('kelompok', ['A', 'B', 'PAUD']); // Materi ini buat kelompok mana
            $table->string('judul_materi'); // Dulu namanya "Tema"
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_dibuat');
            $table->timestamps();
        });

        // 3. Sambungin Jurnal Harian ke Materi
        Schema::table('jurnal_harians', function (Blueprint $table) {
            $table->foreignId('materi_id')->nullable()->after('kelas_id');
        });
    }

    public function down() {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('kelompok');
        });
        Schema::dropIfExists('materi_jurnals');
        Schema::table('jurnal_harians', function (Blueprint $table) {
            $table->dropColumn('materi_id');
        });
    }
};