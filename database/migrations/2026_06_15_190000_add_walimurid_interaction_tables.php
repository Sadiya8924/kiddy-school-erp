<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel untuk mencatat "Sudah Dibaca" oleh Wali Murid
        Schema::create('materi_read_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained('materi_jurnals')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Wali murid yang membaca');
            $table->timestamp('read_at')->useCurrent();
            $table->timestamps();
        });

        // Komentar/Tanya Jawab pada Materi
        Schema::create('materi_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained('materi_jurnals')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Bisa wali murid atau guru');
            $table->text('komentar');
            $table->timestamps();
        });

        // Komentar/Balasan dua arah pada Jurnal Harian Siswa
        Schema::create('jurnal_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurnal_id')->constrained('jurnal_harians')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('komentar');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jurnal_comments');
        Schema::dropIfExists('materi_comments');
        Schema::dropIfExists('materi_read_receipts');
    }
};
