<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rapor_template_items', function (Blueprint $table) {
            $table->json('opsi_pilihan')->nullable()->after('tipe');
        });
    }

    public function down()
    {
        Schema::table('rapor_template_items', function (Blueprint $table) {
            $table->dropColumn('opsi_pilihan');
        });
    }
};
