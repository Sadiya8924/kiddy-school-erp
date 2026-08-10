<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MultitenantTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create 2nd Yayasan
        $yayasanId = DB::table('yayasans')->insertGetId([
            'nama_yayasan' => 'Yayasan Al-Falah Malang',
            'singkatan' => 'YAF',
            'alamat' => 'Jl. Merdeka No. 10, Malang',
            'telepon' => '0341-112233',
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Create 2nd School under 2nd Yayasan
        $schoolId = DB::table('schools')->insertGetId([
            'yayasan_id' => $yayasanId,
            'nama_sekolah' => 'TK Al-Falah 1',
            'npsn' => '20199988',
            'alamat' => 'Jl. Merdeka No. 12, Malang',
            'telepon' => '0341-112244',
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Create Admin for 2nd School
        DB::table('users')->insert([
            'name' => 'Admin TK Al-Falah',
            'email' => 'admin@alfalah.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'school_id' => $schoolId,
            'yayasan_id' => $yayasanId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Create Students for 2nd School
        for ($i = 1; $i <= 5; $i++) {
            DB::table('siswas')->insert([
                'school_id' => $schoolId,
                'nama_lengkap' => "Siswa Al-Falah $i",
                'nik' => "357301010101000$i",
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
