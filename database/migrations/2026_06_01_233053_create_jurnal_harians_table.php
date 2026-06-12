<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('jurnal_harians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id');
            $table->foreignId('kelas_id');
            $table->foreignId('user_id'); // Guru yang mengajar/mengisi
            $table->date('tanggal');
            $table->string('tema');
            $table->text('kegiatan');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('jurnal_harians');
    }
};