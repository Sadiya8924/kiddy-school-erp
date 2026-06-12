<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin (tidak punya school_id)
        User::create([
            'school_id' => null,
            'name'      => 'Super Admin',
            'email'     => 'superadmin@tkceria.com',
            'password'  => bcrypt('password'),
            'role'      => 'super_admin',
        ]);

        // 2. Buat sekolah demo
        $school = School::create([
            'nama_sekolah'       => 'TK Permata',
            'npsn'               => '12345678',
            'alamat'             => 'Jl. Pendidikan No. 1, Pasuruan',
            'kepala_sekolah'     => 'Ibu Siti Aminah',
            'telepon'            => '08123456789',
            'tahun_ajaran_aktif' => '2025/2026',
            'semester_aktif'     => 'Genap',
            'status'             => 'aktif',
            'is_rapor_open'      => 1, // Buka akses rapor default
        ]);

        // =========================================================================
        // WAJIB: BIKIN SEMESTER AKTIF UNTUK SISTEM BARU KITA
        // =========================================================================
        DB::table('semesters')->insert([
            'tahun_ajaran' => '2025/2026',
            'jenis'        => 'Genap',
            'is_active'    => 1, // INI KUNCI UTAMA SISTEM RAPOR
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // 3. Admin sekolah
        User::create([
            'school_id' => $school->id,
            'name'      => 'Admin TK Permata',
            'email'     => 'admin@tk.com',
            'password'  => bcrypt('password'),
            'role'      => 'admin',
        ]);

        // 4. Guru
        User::create([
            'school_id' => $school->id,
            'name'      => 'Naning',
            'email'     => 'naning@tk.com',
            'password'  => bcrypt('password'),
            'role'      => 'guru',
        ]);

        // 5. Wali Murid
        User::create([
            'school_id' => $school->id,
            'name'      => 'Wali Murid',
            'email'     => 'ortu@tk.com',
            'password'  => bcrypt('password'),
            'role'      => 'wali_murid',
        ]);

        // 6. Panggil Semua Seeder Bawaan Kamu!
        $this->call([
            ModuleSeeder::class, 
            SiswaSeeder::class,
            PerkembanganFisikSeeder::class,
            AbsensiSeeder::class,
        ]);
    }
}