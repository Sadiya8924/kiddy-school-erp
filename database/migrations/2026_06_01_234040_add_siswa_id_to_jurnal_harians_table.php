<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('jurnal_harians', function (Blueprint $table) {
            $table->foreignId('siswa_id')->nullable()->after('kelas_id');
        });
    }
    public function down() {
        Schema::table('jurnal_harians', function (Blueprint $table) {
            $table->dropColumn('siswa_id');
        });
    }
};