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
        if (!Schema::hasColumn('users', 'no_hp')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('no_hp', 50)->nullable()->after('email');
            });
        }

        // Pastikan kolom email nullable agar pendaftar bisa menggunakan nomor HP saja
        try {
            DB::statement('ALTER TABLE users MODIFY COLUMN email VARCHAR(255) NULL');
        } catch (\Exception $e) {
            // Ignore if already nullable or driver differences
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'no_hp')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('no_hp');
            });
        }
    }
};
