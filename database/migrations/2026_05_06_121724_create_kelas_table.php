<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('nama_kelas'); // Contoh: "TK A Semangka"
            $table->string('tingkat')->nullable(); // Contoh: "TK A", "TK B", "KB"
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('kelas');
    }
};