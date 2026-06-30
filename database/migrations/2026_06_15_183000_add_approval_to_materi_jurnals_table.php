<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('materi_jurnals', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('school_id')->comment('Guru yang upload');
            $table->enum('status', ['Menunggu ACC', 'Disetujui', 'Ditolak'])->default('Menunggu ACC')->after('tanggal_dibuat');
            $table->text('catatan_penolakan')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('materi_jurnals', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'status', 'catatan_penolakan']);
        });
    }
};
