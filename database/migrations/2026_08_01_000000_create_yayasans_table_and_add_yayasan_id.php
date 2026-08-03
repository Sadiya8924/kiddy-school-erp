<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buat tabel yayasans jika belum ada
        if (!Schema::hasTable('yayasans')) {
            Schema::create('yayasans', function (Blueprint $table) {
                $table->id();
                $table->string('nama_yayasan');
                $table->string('kode_yayasan')->nullable()->unique();
                $table->string('penanggung_jawab')->nullable();
                $table->string('telepon')->nullable();
                $table->text('alamat')->nullable();
                $table->string('logo_yayasan')->nullable();
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->timestamps();
            });

            // Seed default Yayasan pertama
            DB::table('yayasans')->insert([
                'id' => 1,
                'nama_yayasan' => 'Yayasan Utama',
                'kode_yayasan' => 'YYS-001',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Tambah kolom yayasan_id ke tabel schools jika belum ada
        if (Schema::hasTable('schools') && !Schema::hasColumn('schools', 'yayasan_id')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->foreignId('yayasan_id')->nullable()->after('id')->constrained('yayasans')->nullOnDelete();
            });

            // Hubungkan semua sekolah eksisting ke Yayasan ID 1
            DB::table('schools')->whereNull('yayasan_id')->update(['yayasan_id' => 1]);
        }

        // 3. Tambah kolom yayasan_id ke tabel users jika belum ada
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'yayasan_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('yayasan_id')->nullable()->after('school_id')->constrained('yayasans')->nullOnDelete();
            });

            // Hubungkan semua user eksisting (selain owner yang belum ada) ke Yayasan ID 1
            DB::table('users')->whereNull('yayasan_id')->update(['yayasan_id' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'yayasan_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['yayasan_id']);
                $table->dropColumn('yayasan_id');
            });
        }

        if (Schema::hasTable('schools') && Schema::hasColumn('schools', 'yayasan_id')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->dropForeign(['yayasan_id']);
                $table->dropColumn('yayasan_id');
            });
        }

        Schema::dropIfExists('yayasans');
    }
};
