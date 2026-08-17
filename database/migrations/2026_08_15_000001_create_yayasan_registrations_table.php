<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yayasan_registrations', function (Blueprint $table) {
            $table->id();
            // Identitas Yayasan
            $table->string('nama_yayasan');
            $table->string('kode_yayasan')->nullable()->comment('Akan di-generate otomatis atau diisi manual oleh owner saat ACC');
            $table->string('penanggung_jawab');
            $table->string('email_kontak')->comment('Email untuk dihubungi');
            $table->string('telepon')->comment('Nomor telpon untuk dihubungi');
            $table->text('alamat');
            $table->decimal('lintang', 10, 7)->nullable()->comment('Koordinat latitude');
            $table->decimal('bujur', 10, 7)->nullable()->comment('Koordinat longitude');
            // Legalitas
            $table->string('nomor_akta_pendirian')->nullable();
            $table->string('nomor_sk_kemenkumham')->nullable();
            $table->string('npwp_yayasan')->nullable();
            // Status & Workflow
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_owner')->nullable()->comment('Catatan ACC atau alasan penolakan dari owner');
            $table->timestamp('diproses_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yayasan_registrations');
    }
};
