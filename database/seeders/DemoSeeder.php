<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Buat Super Admin (Akses Global)
        User::create([
            'name' => 'Super Admin Master',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'school_id' => null,
        ]);

        // Daftar Sekolah untuk Demo
        $schools = [
            [
                'nama' => 'TK Ceria Malang',
                'npsn' => '20260001',
                'admin_email' => 'admin.ceria@gmail.com'
            ],
            [
                'nama' => 'TK Permata Bunda',
                'npsn' => '20260002',
                'admin_email' => 'admin.permata@gmail.com'
            ],
        ];

        foreach ($schools as $s) {
            // 2. Buat Sekolah
            $school = School::create([
                'nama_sekolah' => $s['nama'],
                'npsn' => $s['npsn'],
                'alamat' => $faker->address,
                'status' => 'aktif',
                'tahun_ajaran_aktif' => '2025/2026',
                'semester_aktif' => 'Genap',
                'is_rapor_open' => true
            ]);

            // 3. Buat Admin Sekolah
            User::create([
                'school_id' => $school->id,
                'name' => 'Admin ' . $s['nama'],
                'email' => $s['admin_email'],
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]);

            // 4. Buat 2 Kelas per Sekolah
            $kelasA = Kelas::create([
                'school_id' => $school->id,
                'nama_kelas' => 'Kelompok A (Bintang)',
                'tingkat' => 'TK A'
            ]);

            $kelasB = Kelas::create([
                'school_id' => $school->id,
                'nama_kelas' => 'Kelompok B (Matahari)',
                'tingkat' => 'TK B'
            ]);

            // 5. Buat 2 Guru per Sekolah (1 Guru per Kelas)
            $guruData = [
                ['name' => 'Guru Kelas A S.Pd', 'kelas' => $kelasA->id, 'email' => 'guru_a.' . $s['npsn'] . '@ceria.com'],
                ['name' => 'Guru Kelas B S.Pd', 'kelas' => $kelasB->id, 'email' => 'guru_b.' . $s['npsn'] . '@ceria.com'],
            ];

            foreach ($guruData as $g) {
                User::create([
                    'school_id' => $school->id,
                    'kelas_id' => $g['kelas'],
                    'name' => $g['name'],
                    'email' => $g['email'],
                    'password' => Hash::make('password'),
                    'role' => 'guru'
                ]);
            }

            // 6. Buat 10 Siswa per Kelas (Total 20 per Sekolah)
            $allKelas = [$kelasA, $kelasB];
            foreach ($allKelas as $k) {
                for ($i = 1; $i <= 10; $i++) {
                    $gender = ($i % 2 == 0) ? 'Laki-laki' : 'Perempuan'; // Mencegah truncation error
                    Siswa::create([
                        'school_id' => $school->id,
                        'kelas_id' => $k->id,
                        'nama_lengkap' => $faker->name($gender == 'Laki-laki' ? 'male' : 'female'),
                        'nik' => $faker->unique()->numerify('3507############'),
                        'nama_ortu' => $faker->name,
                        'tempat_lahir' => 'Malang',
                        'tgl_lahir' => '2020-05-08',
                        'jenis_kelamin' => $gender,
                        'status' => 'aktif',
                        'tinggi_badan' => rand(100, 115),
                        'berat_badan' => rand(15, 22)
                    ]);
                }
            }
        }
    }
}