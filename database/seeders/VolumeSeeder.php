<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VolumeSeeder extends Seeder
{
    public function run(): void
    {
        $school = DB::table('schools')->first();
        if (!$school) return;

        $batch = [];
        for ($i = 1; $i <= 100; $i++) {
            $batch[] = [
                'school_id' => $school->id,
                'nama_lengkap' => "Siswa Uji Performa #$i",
                'nik' => '3573' . str_pad($i, 12, '0', STR_PAD_LEFT),
                'nisn' => '009' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'program_pilihan' => 'TK Pagi (Tahun: 2026/2027)',
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'tempat_lahir' => 'Malang',
                'tgl_lahir' => '2020-05-15',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('siswas')->insert($batch);
    }
}
