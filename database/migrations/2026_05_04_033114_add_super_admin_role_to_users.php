<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Kita cuma nambahin 'super_admin' dan TETAP pakai 'wali_murid'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'guru', 'wali_murid') DEFAULT 'wali_murid'");
    }

    public function down(): void
    {
        // Kembalikan ke asal jika di-rollback
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru', 'wali_murid') DEFAULT 'wali_murid'");
    }
};