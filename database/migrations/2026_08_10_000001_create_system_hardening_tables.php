<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TM-10: Audit Logs Table
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->unsignedBigInteger('school_id')->nullable();
                $table->string('action'); // e.g. 'ambil_alih_sekolah', 'edit_nilai', 'void_pembayaran'
                $table->text('description')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->timestamps();
            });
        }

        // 2. TM-32: Custom Domains Table
        if (!Schema::hasTable('domains')) {
            Schema::create('domains', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id');
                $table->string('domain')->unique();
                $table->boolean('is_verified')->default(true);
                $table->timestamps();
            });
        }

        // 3. TM-30: Record Revisions Table (Audit trail for transactional updates)
        if (!Schema::hasTable('record_revisions')) {
            Schema::create('record_revisions', function (Blueprint $table) {
                $table->id();
                $table->string('revisable_type');
                $table->unsignedBigInteger('revisable_id');
                $table->foreignId('user_id')->nullable()->constrained('users');
                $table->string('field');
                $table->text('old_value')->nullable();
                $table->text('new_value')->nullable();
                $table->text('reason')->nullable();
                $table->timestamps();
            });
        }

        // 4. TM-28: Student Guardian Pivot Table
        if (!Schema::hasTable('student_guardian')) {
            Schema::create('student_guardian', function (Blueprint $table) {
                $table->id();
                $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('peran')->default('wali'); // 'ayah', 'ibu', 'wali'
                $table->boolean('is_wali_utama')->default(true);
                $table->timestamps();
            });
        }

        // 5. TM-22: Media Kegiatan Tables
        if (!Schema::hasTable('media_kegiatans')) {
            Schema::create('media_kegiatans', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id');
                $table->foreignId('user_id')->constrained('users');
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->string('file_path');
                $table->string('file_type')->default('image'); // 'image', 'video'
                $table->date('tanggal_kegiatan');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('media_kegiatan_siswas')) {
            Schema::create('media_kegiatan_siswas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('media_kegiatan_id')->constrained('media_kegiatans')->onDelete('cascade');
                $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
                $table->timestamps();
            });
        }

        // 6. TM-23: Add kepala_sekolah_user_id FK to schools table if not present
        if (Schema::hasTable('schools') && !Schema::hasColumn('schools', 'kepala_sekolah_user_id')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->unsignedBigInteger('kepala_sekolah_user_id')->nullable()->after('status');
            });
        }

        // 7. Semesters Table
        if (!Schema::hasTable('semesters')) {
            Schema::create('semesters', function (Blueprint $table) {
                $table->id();
                $table->string('tahun_ajaran');
                $table->string('jenis')->default('Ganjil');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 8. Anggota Kelas Table
        if (!Schema::hasTable('anggota_kelas')) {
            Schema::create('anggota_kelas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
                $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
                $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('media_kegiatan_siswas');
        Schema::dropIfExists('media_kegiatans');
        Schema::dropIfExists('student_guardian');
        Schema::dropIfExists('record_revisions');
        Schema::dropIfExists('domains');
        Schema::dropIfExists('audit_logs');
    }
};
