<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('school_modules', function (Blueprint $table) {
            // Menambahkan 3 kolom baru dengan nilai default true (1) alias On
            // after() digunakan agar posisi kolom rapi di sebelah kolom is_active_for_guru
            $table->boolean('is_active_for_admin')->default(true)->after('is_active_for_guru');
            $table->boolean('is_active_for_kepsek')->default(true)->after('is_active_for_admin');
            $table->boolean('is_active_for_wali_murid')->default(true)->after('is_active_for_kepsek');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_modules', function (Blueprint $table) {
            // Menghapus kolom jika sewaktu-waktu kita melakukan php artisan migrate:rollback
            $table->dropColumn([
                'is_active_for_admin', 
                'is_active_for_kepsek', 
                'is_active_for_wali_murid'
            ]);
        });
    }
};