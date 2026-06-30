<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Update Enum Status Materi
        DB::statement("ALTER TABLE materi_jurnals MODIFY COLUMN status ENUM('DRAFT', 'Menunggu ACC', 'Disetujui', 'Ditolak') DEFAULT 'DRAFT'");

        // 2. Buat tabel Notifikasi (In-App)
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        DB::statement("ALTER TABLE materi_jurnals MODIFY COLUMN status ENUM('Menunggu ACC', 'Disetujui', 'Ditolak') DEFAULT 'Menunggu ACC'");
        Schema::dropIfExists('notifications');
    }
};
