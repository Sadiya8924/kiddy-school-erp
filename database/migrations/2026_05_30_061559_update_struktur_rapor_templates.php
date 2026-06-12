<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tambah opsi mulok global di tabel templates
        Schema::table('rapor_templates', function (Blueprint $table) {
            $table->string('opsi_mulok')->nullable()->after('is_active');
        });

        // Tambah sistem Parent (BAB) di tabel items
        Schema::table('rapor_template_items', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('template_id');
            $table->boolean('is_bab')->default(false)->after('parent_id');
        });
    }

    public function down()
    {
        Schema::table('rapor_templates', function (Blueprint $table) {
            $table->dropColumn('opsi_mulok');
        });
        Schema::table('rapor_template_items', function (Blueprint $table) {
            $table->dropColumn(['parent_id', 'is_bab']);
        });
    }
};