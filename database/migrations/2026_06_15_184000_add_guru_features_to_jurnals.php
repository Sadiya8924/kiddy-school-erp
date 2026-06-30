<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('materi_jurnals', function (Blueprint $table) {
            $table->text('tujuan_pembelajaran')->nullable()->after('deskripsi');
            $table->string('file_materi')->nullable()->after('tujuan_pembelajaran');
            $table->date('tanggal_tayang')->nullable()->after('tanggal_dibuat');
        });

        Schema::create('materi_kelas_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained('materi_jurnals')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('jurnal_harians', function (Blueprint $table) {
            $table->enum('presensi', ['Hadir', 'Izin', 'Sakit', 'Alpa'])->default('Hadir')->after('tanggal');
            $table->string('mood')->nullable()->after('presensi');
            $table->text('catatan_khusus')->nullable()->after('catatan');
        });
    }

    public function down()
    {
        Schema::table('jurnal_harians', function (Blueprint $table) {
            $table->dropColumn(['presensi', 'mood', 'catatan_khusus']);
        });

        Schema::dropIfExists('materi_kelas_targets');

        Schema::table('materi_jurnals', function (Blueprint $table) {
            $table->dropColumn(['tujuan_pembelajaran', 'file_materi', 'tanggal_tayang']);
        });
    }
};
