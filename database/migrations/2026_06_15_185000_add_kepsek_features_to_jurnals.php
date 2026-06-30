<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jurnal_harians', function (Blueprint $table) {
            $table->text('catatan_kepsek')->nullable()->after('catatan_khusus')->comment('Catatan pengawasan dari Kepala Sekolah');
        });
    }

    public function down()
    {
        Schema::table('jurnal_harians', function (Blueprint $table) {
            $table->dropColumn('catatan_kepsek');
        });
    }
};
