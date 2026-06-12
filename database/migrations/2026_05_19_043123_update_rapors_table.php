<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('rapors', function (Blueprint $table) {
            // Hapus kolom manual yang lama
            $table->dropColumn(['semester', 'tahun_ajaran']); 
            
            // Tambahkan FK ke tabel semesters
            $table->foreignId('semester_id')->nullable()->after('siswa_id')->constrained('semesters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapors', function (Blueprint $table) {
            //
        });
    }
};
