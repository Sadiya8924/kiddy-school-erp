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
        Schema::create('perkembangan_fisiks', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel siswas
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->date('tgl_periksa');
            $table->decimal('berat_badan', 5, 2); // Contoh: 15.50 kg
            $table->decimal('tinggi_badan', 5, 2); // Contoh: 110.2 cm
            $table->decimal('lingkar_kepala', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perkembangan_fisiks');
    }
};
