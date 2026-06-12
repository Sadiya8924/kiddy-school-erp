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
        Schema::create('semesters', function (Blueprint $table) {
            $table->id(); // INI PK
            $table->string('tahun_ajaran'); // Contoh: "2025/2026"
            $table->enum('jenis', ['Ganjil', 'Genap']);
            $table->boolean('is_active')->default(false); // Admin yang kontrol
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
