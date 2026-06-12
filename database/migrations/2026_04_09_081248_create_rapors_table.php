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
        Schema::create('rapors', function (Blueprint $table) {
            $table->id();

            //relasi foreign key ke siswa
            $table->foreignId('siswa_id')->constrained()->onDelete('cascade');

            $table->string('semester');
            $table->string('tahun_ajaran');

            //absensi
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('alfa')->default(0);
            $table->float('tinggi_badan')->nullable();
            $table->float('berat_badan')->nullable();

            //Narasi
            $table->text('nilai_agama_dan_budi_pekerti')->nullable();
            $table->text('jati_diri')->nullable();
            $table->text('literasi_sains_seni')->nullable();
            $table->text('kokurikuler')->nullable();
            $table->text('refleksi_ortu')->nullable();
            $table->text('info_perkembangan')->nullable();

            $table->timestamps();
        });
    }
};
