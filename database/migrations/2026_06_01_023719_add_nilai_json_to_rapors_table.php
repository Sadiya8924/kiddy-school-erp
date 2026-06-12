<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('rapors', function (Blueprint $table) {
            $table->json('nilai_json')->nullable()->after('template_id');
        });
    }
    public function down() {
        Schema::table('rapors', function (Blueprint $table) {
            $table->dropColumn('nilai_json');
        });
    }
};