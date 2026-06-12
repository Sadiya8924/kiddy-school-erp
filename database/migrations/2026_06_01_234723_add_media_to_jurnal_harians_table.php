<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('jurnal_harians', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('catatan');
            $table->string('video')->nullable()->after('foto');
        });
    }
    public function down() {
        Schema::table('jurnal_harians', function (Blueprint $table) {
            $table->dropColumn(['foto', 'video']);
        });
    }
};