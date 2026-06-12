<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('no_induk', 50)->nullable()->after('nisn');
        });
    }
    public function down() {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('no_induk');
        });
    }
};