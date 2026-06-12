<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan di sini tertulis 'users', bukan 'user'
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            
            $table->enum('role', ['admin', 'guru', 'wali_murid'])->default('wali_murid');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Di sini juga pastikan 'users'
        Schema::dropIfExists('users');
    }
};
