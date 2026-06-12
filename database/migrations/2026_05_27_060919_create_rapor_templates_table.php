<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rapor_templates', function (Blueprint $table) {
            $table->id();
            $table->string('nama_template');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('rapor_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('rapor_templates')->onDelete('cascade');
            $table->string('label');
            $table->enum('tipe', ['textarea', 'radio'])->default('textarea');
            $table->integer('urutan');
            $table->timestamps();
        });

        Schema::table('rapors', function (Blueprint $table) {
            $table->foreignId('template_id')->nullable()->constrained('rapor_templates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapor_templates');
    }
};
