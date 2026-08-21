<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MasterFreshDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Ensure all database migrations are executed first so tables exist
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);

        // 1. Disable Foreign Key Checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // List all tables to clear
        $tables = [
            'media_kegiatan_siswas',
            'media_kegiatans',
            'record_revisions',
            'domains',
            'audit_logs',
            'student_guardian',
            'arsip_rapors',
            'rapors',
            'perkembangan_fisiks',
            'jurnal_harians',
            'absensis',
            'materi_jurnals',
            'materis',
            'announcements',
            'anggota_kelas',
            'siswas',
            'kelas',
            'semesters',
            'users',
            'schools',
            'yayasans',
            'settings'
        ];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = now();
        $defaultPassword = Hash::make('password');

        // ----------------------------------------------------
        // 2. SEED 2 YAYASANS (2 TENANTS)
        // ----------------------------------------------------
        $yayasan1Id = DB::table('yayasans')->insertGetId([
            'nama_yayasan' => 'Yayasan Pendidikan Al-Hikmah 2',
            'kode_yayasan' => 'YYS-002',
            'alamat' => 'Jl. Veteran No. 45, Malang',
            'telepon' => '0341-551122',
            'status' => 'aktif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $yayasan2Id = DB::table('yayasans')->insertGetId([
            'nama_yayasan' => 'Yayasan Bina Insani Ceria',
            'kode_yayasan' => 'YYS-001',
            'alamat' => 'Jl. Soekarno Hatta No. 88, Malang',
            'telepon' => '0341-778899',
            'status' => 'aktif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ----------------------------------------------------
        // 3. SEED SCHOOLS (4 TKs TOTAL)
        // ----------------------------------------------------
        $school1Id = DB::table('schools')->insertGetId([
            'yayasan_id' => $yayasan1Id,
            'nama_sekolah' => 'TK Permata Al-Hikmah',
            'npsn' => '20101111',
            'alamat' => 'Jl. Veteran No. 45A, Malang',
            'telepon' => '0341-551123',
            'status' => 'aktif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $school2Id = DB::table('schools')->insertGetId([
            'yayasan_id' => $yayasan1Id,
            'nama_sekolah' => 'TK Matahari Al-Hikmah',
            'npsn' => '20101112',
            'alamat' => 'Jl. Mawar No. 12, Malang',
            'telepon' => '0341-551124',
            'status' => 'aktif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $school3Id = DB::table('schools')->insertGetId([
            'yayasan_id' => $yayasan2Id,
            'nama_sekolah' => 'TK Bintang Insani',
            'npsn' => '20202221',
            'alamat' => 'Jl. Soekarno Hatta No. 88B, Malang',
            'telepon' => '0341-778890',
            'status' => 'aktif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $school4Id = DB::table('schools')->insertGetId([
            'yayasan_id' => $yayasan2Id,
            'nama_sekolah' => 'TK Cendekia Insani',
            'npsn' => '20202222',
            'alamat' => 'Jl. Melati No. 5, Malang',
            'telepon' => '0341-778891',
            'status' => 'aktif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Custom Domains
        if (DB::getSchemaBuilder()->hasTable('domains')) {
            DB::table('domains')->insert([
                ['school_id' => $school1Id, 'domain' => 'tkpermata.sch.id', 'is_verified' => true, 'created_at' => $now, 'updated_at' => $now],
                ['school_id' => $school2Id, 'domain' => 'tkmatahari.sch.id', 'is_verified' => true, 'created_at' => $now, 'updated_at' => $now],
                ['school_id' => $school3Id, 'domain' => 'tkbintang.sch.id', 'is_verified' => true, 'created_at' => $now, 'updated_at' => $now],
                ['school_id' => $school4Id, 'domain' => 'tkcendekia.sch.id', 'is_verified' => true, 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // ----------------------------------------------------
        // 4. SEED USERS FOR ALL ROLES
        // ----------------------------------------------------

        // A. Owner Platform SaaS
        $ownerId = DB::table('users')->insertGetId([
            'name' => 'Owner Platform SaaS',
            'email' => 'owner@yayasan.com',
            'password' => $defaultPassword,
            'role' => 'owner',
            'yayasan_id' => null,
            'school_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // B. Tenant 1 Users
        $superadmin1Id = DB::table('users')->insertGetId([
            'name' => 'Super Admin Al-Hikmah',
            'email' => 'superadmin1@alhikmah.com',
            'password' => $defaultPassword,
            'role' => 'super_admin',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $school1Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $admin1Id = DB::table('users')->insertGetId([
            'name' => 'Admin TK Permata',
            'email' => 'admin1@tkpermata.sch.id',
            'password' => $defaultPassword,
            'role' => 'admin',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $school1Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $kepsek1Id = DB::table('users')->insertGetId([
            'name' => 'Dr. Hj. Rahmawati, M.Pd (Kepsek Permata)',
            'email' => 'kepsek@tk.com',
            'password' => $defaultPassword,
            'role' => 'kepsek',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $school1Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $guruA1Id = DB::table('users')->insertGetId([
            'name' => 'Naning Setyowati, S.Pd (Guru TK-A)',
            'email' => 'guru.a1@tkpermata.sch.id',
            'password' => $defaultPassword,
            'role' => 'guru',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $school1Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $guruB1Id = DB::table('users')->insertGetId([
            'name' => 'Siti Nurhaliza, S.Pd (Guru TK-B)',
            'email' => 'guru.b1@tkpermata.sch.id',
            'password' => $defaultPassword,
            'role' => 'guru',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $school1Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // School 2 Users
        $admin2Id = DB::table('users')->insertGetId([
            'name' => 'Admin TK Matahari',
            'email' => 'admin2@tkmatahari.sch.id',
            'password' => $defaultPassword,
            'role' => 'admin',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $school2Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $kepsek2Id = DB::table('users')->insertGetId([
            'name' => 'Drs. Bambang Hariyanto, M.M (Kepsek Matahari)',
            'email' => 'kepsek2@tkmatahari.sch.id',
            'password' => $defaultPassword,
            'role' => 'kepsek',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $school2Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $guru2Id = DB::table('users')->insertGetId([
            'name' => 'Dewi Lestari, S.Pd (Guru Matahari)',
            'email' => 'guru2@tkmatahari.sch.id',
            'password' => $defaultPassword,
            'role' => 'guru',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $school2Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // C. Tenant 2 Users
        $superadmin2Id = DB::table('users')->insertGetId([
            'name' => 'Super Admin Bina Insani',
            'email' => 'superadmin2@binainsani.com',
            'password' => $defaultPassword,
            'role' => 'super_admin',
            'yayasan_id' => $yayasan2Id,
            'school_id' => $school3Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $admin3Id = DB::table('users')->insertGetId([
            'name' => 'Admin TK Bintang',
            'email' => 'admin3@tkbintang.sch.id',
            'password' => $defaultPassword,
            'role' => 'admin',
            'yayasan_id' => $yayasan2Id,
            'school_id' => $school3Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $kepsek3Id = DB::table('users')->insertGetId([
            'name' => 'Hj. Anisa Fitriani, M.Pd (Kepsek Bintang)',
            'email' => 'kepsek3@tkbintang.sch.id',
            'password' => $defaultPassword,
            'role' => 'kepsek',
            'yayasan_id' => $yayasan2Id,
            'school_id' => $school3Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $guruA3Id = DB::table('users')->insertGetId([
            'name' => 'Rina Gunawan, S.Pd (Guru Bintang TK-A)',
            'email' => 'guru.a3@tkbintang.sch.id',
            'password' => $defaultPassword,
            'role' => 'guru',
            'yayasan_id' => $yayasan2Id,
            'school_id' => $school3Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $guruB3Id = DB::table('users')->insertGetId([
            'name' => 'Budi Santoso, S.Pd (Guru Bintang TK-B)',
            'email' => 'guru.b3@tkbintang.sch.id',
            'password' => $defaultPassword,
            'role' => 'guru',
            'yayasan_id' => $yayasan2Id,
            'school_id' => $school3Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // School 4 Users
        $admin4Id = DB::table('users')->insertGetId([
            'name' => 'Admin TK Cendekia',
            'email' => 'admin4@tkcendekia.sch.id',
            'password' => $defaultPassword,
            'role' => 'admin',
            'yayasan_id' => $yayasan2Id,
            'school_id' => $school4Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $kepsek4Id = DB::table('users')->insertGetId([
            'name' => 'Ahmad Fauzi, M.Pd (Kepsek Cendekia)',
            'email' => 'kepsek4@tkcendekia.sch.id',
            'password' => $defaultPassword,
            'role' => 'kepsek',
            'yayasan_id' => $yayasan2Id,
            'school_id' => $school4Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $guru4Id = DB::table('users')->insertGetId([
            'name' => 'Maya Indah, S.Pd (Guru Cendekia)',
            'email' => 'guru4@tkcendekia.sch.id',
            'password' => $defaultPassword,
            'role' => 'guru',
            'yayasan_id' => $yayasan2Id,
            'school_id' => $school4Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Link Kepsek FK to schools
        DB::table('schools')->where('id', $school1Id)->update(['kepala_sekolah_user_id' => $kepsek1Id]);
        DB::table('schools')->where('id', $school2Id)->update(['kepala_sekolah_user_id' => $kepsek2Id]);
        DB::table('schools')->where('id', $school3Id)->update(['kepala_sekolah_user_id' => $kepsek3Id]);
        DB::table('schools')->where('id', $school4Id)->update(['kepala_sekolah_user_id' => $kepsek4Id]);

        // ----------------------------------------------------
        // 5. SEED SEMESTERS
        // ----------------------------------------------------
        $semGanjilId = DB::table('semesters')->insertGetId([
            'tahun_ajaran' => '2026/2027',
            'jenis' => 'Ganjil',
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $semGenapId = DB::table('semesters')->insertGetId([
            'tahun_ajaran' => '2026/2027',
            'jenis' => 'Genap',
            'is_active' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $semesterIds = [
            $school1Id => $semGanjilId,
            $school2Id => $semGanjilId,
            $school3Id => $semGanjilId,
            $school4Id => $semGanjilId,
        ];

        // ----------------------------------------------------
        // 6. SEED CLASSES (ROMBEL)
        // ----------------------------------------------------
        $kelas1AId = DB::table('kelas')->insertGetId([
            'school_id' => $school1Id,
            'nama_kelas' => 'TK-A Mangga',
            'tingkat' => 'TK-A',
            'kelompok' => 'A',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('users')->where('id', $guruA1Id)->update(['kelas_id' => $kelas1AId]);

        $kelas1BId = DB::table('kelas')->insertGetId([
            'school_id' => $school1Id,
            'nama_kelas' => 'TK-B Apel',
            'tingkat' => 'TK-B',
            'kelompok' => 'B',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('users')->where('id', $guruB1Id)->update(['kelas_id' => $kelas1BId]);

        $kelas2AId = DB::table('kelas')->insertGetId([
            'school_id' => $school2Id,
            'nama_kelas' => 'TK-A Melati',
            'tingkat' => 'TK-A',
            'kelompok' => 'A',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('users')->where('id', $guru2Id)->update(['kelas_id' => $kelas2AId]);

        $kelas3AId = DB::table('kelas')->insertGetId([
            'school_id' => $school3Id,
            'nama_kelas' => 'TK-A Bintang Kecil',
            'tingkat' => 'TK-A',
            'kelompok' => 'A',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('users')->where('id', $guruA3Id)->update(['kelas_id' => $kelas3AId]);

        $kelas3BId = DB::table('kelas')->insertGetId([
            'school_id' => $school3Id,
            'nama_kelas' => 'TK-B Bintang Kejora',
            'tingkat' => 'TK-B',
            'kelompok' => 'B',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('users')->where('id', $guruB3Id)->update(['kelas_id' => $kelas3BId]);

        $kelas4AId = DB::table('kelas')->insertGetId([
            'school_id' => $school4Id,
            'nama_kelas' => 'TK-A Cerdas',
            'tingkat' => 'TK-A',
            'kelompok' => 'A',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('users')->where('id', $guru4Id)->update(['kelas_id' => $kelas4AId]);

        // ----------------------------------------------------
        // 7. SEED STUDENTS (EXACTLY 20 STUDENTS PER CLASS = 120 TOTAL)
        // ----------------------------------------------------

        $firstNamesLaki = ['Aditya', 'Bima', 'Cakra', 'Dafa', 'Erlangga', 'Faris', 'Gibran', 'Hafiz', 'Irfan', 'Jovian', 'Kenzo', 'Lutfi', 'Muhammad', 'Naufal', 'Oky', 'Pradipta', 'Raffi', 'Satria', 'Taufik', 'Zayyan'];
        $firstNamesPerempuan = ['Alya', 'Bella', 'Citra', 'Dina', 'Eka', 'Fiona', 'Gita', 'Hania', 'Intan', 'Jasmine', 'Keisha', 'Laras', 'Nabila', 'Olivia', 'Putri', 'Qonita', 'Rania', 'Sasa', 'Tania', 'Zahra'];
        $lastNames = ['Pratama', 'Wibowo', 'Kurniawan', 'Santoso', 'Hidayat', 'Saputra', 'Wijaya', 'Nugraha', 'Setiawan', 'Utama', 'Rahmadani', 'Ramadhan', 'Lestari', 'Putri', 'Sari', 'Kusuma', 'Kuswandari', 'Mahendra', 'Firmansyah', 'Syahputra'];

        $classesList = [
            ['id' => $kelas1AId, 'school_id' => $school1Id, 'prefix' => '1A'],
            ['id' => $kelas1BId, 'school_id' => $school1Id, 'prefix' => '1B'],
            ['id' => $kelas2AId, 'school_id' => $school2Id, 'prefix' => '2A'],
            ['id' => $kelas3AId, 'school_id' => $school3Id, 'prefix' => '3A'],
            ['id' => $kelas3BId, 'school_id' => $school3Id, 'prefix' => '3B'],
            ['id' => $kelas4AId, 'school_id' => $school4Id, 'prefix' => '4A'],
        ];

        $studentCountGlobal = 1;

        foreach ($classesList as $cls) {
            $clsId = $cls['id'];
            $schId = $cls['school_id'];
            $prefix = $cls['prefix'];

            for ($i = 1; $i <= 20; $i++) {
                $isLaki = ($i % 2 === 1);
                $gender = $isLaki ? 'Laki-laki' : 'Perempuan';
                $fnList = $isLaki ? $firstNamesLaki : $firstNamesPerempuan;
                $fn = $fnList[($i - 1) % count($fnList)];
                $ln = $lastNames[($i - 1) % count($lastNames)];
                $fullName = "$fn $ln ($prefix-$i)";

                $nik = sprintf("3573%02d%06d%04d", $schId, $studentCountGlobal, $i);
                $nisn = sprintf("0019%02d%04d", $schId, $studentCountGlobal);
                $noInduk = sprintf("IND-%d-%03d", $schId, $studentCountGlobal);

                $parentEmail = "ortu.{$prefix}.{$i}@gmail.com";
                $parentName = "Bpk/Ibu $ln";

                // Create Parent User account
                $parentUserId = DB::table('users')->insertGetId([
                    'name' => $parentName,
                    'email' => $parentEmail,
                    'password' => $defaultPassword,
                    'role' => 'wali_murid',
                    'school_id' => $schId,
                    'yayasan_id' => ($schId <= 2) ? $yayasan1Id : $yayasan2Id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Create Student
                $siswaId = DB::table('siswas')->insertGetId([
                    'school_id' => $schId,
                    'nama_lengkap' => $fullName,
                    'nik' => $nik,
                    'nisn' => $nisn,
                    'no_induk' => $noInduk,
                    'jenis_kelamin' => $gender,
                    'tempat_lahir' => 'Malang',
                    'tgl_lahir' => '2021-05-' . sprintf('%02d', ($i % 28) + 1),
                    'status' => 'aktif',
                    'nama_ayah' => "Bapak $ln",
                    'nama_ibu' => "Ibu $fn",
                    'nama_ortu' => $parentName,
                    'email' => $parentEmail,
                    'hp' => '0812' . sprintf('%08d', $studentCountGlobal),
                    'alamat' => "Jl. Raya Kedungkandang No. $i, Malang",
                    'tinggi_badan' => 95 + ($i % 15),
                    'berat_badan' => 15 + ($i % 8),
                    'lingkar_kepala' => '48',
                    'user_id' => $parentUserId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Link Student to Class
                DB::table('anggota_kelas')->insert([
                    'kelas_id' => $clsId,
                    'siswa_id' => $siswaId,
                    'semester_id' => $semesterIds[$schId],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Link Student to Guardian Pivot
                DB::table('student_guardian')->insert([
                    'siswa_id' => $siswaId,
                    'user_id' => $parentUserId,
                    'peran' => 'ayah',
                    'is_wali_utama' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // ----------------------------------------------------
                // 8. SEED OPERATIONAL CRUD DATA PER STUDENT
                // ----------------------------------------------------

                // A. Attendance (Absensi) for past 5 days
                for ($d = 1; $d <= 5; $d++) {
                    $statuses = ['Hadir', 'Hadir', 'Hadir', 'Hadir', 'Izin', 'Sakit'];
                    $st = $statuses[($i + $d) % count($statuses)];
                    DB::table('absensis')->insert([
                        'school_id' => $schId,
                        'siswa_id' => $siswaId,
                        'tanggal' => now()->subDays(6 - $d)->format('Y-m-d'),
                        'status' => $st,
                        'keterangan' => ($st !== 'Hadir') ? 'Keterangan ' . $st : 'Hadir tepat waktu',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                // B. Physical Development (Perkembangan Fisik)
                DB::table('perkembangan_fisiks')->insert([
                    'school_id' => $schId,
                    'siswa_id' => $siswaId,
                    'tgl_periksa' => now()->subDays(15)->format('Y-m-d'),
                    'tinggi_badan' => 95 + ($i % 15),
                    'berat_badan' => 15 + ($i % 8),
                    'lingkar_kepala' => 48,
                    'catatan' => 'Pertumbuhan fisik sangat baik dan proporsional.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // C. Rapor Entry
                DB::table('rapors')->insert([
                    'school_id' => $schId,
                    'siswa_id' => $siswaId,
                    'semester_id' => $semesterIds[$schId],
                    'status' => 'Approved',
                    'nilai_json' => json_encode([
                        'agama' => 'Sangat Baik (BSB)',
                        'motorik' => 'Berkembang Sesuai Harapan (BSH)',
                        'kognitif' => 'Sangat Baik (BSB)',
                        'bahasa' => 'Berkembang Sesuai Harapan (BSH)',
                        'sosial_emosional' => 'Sangat Baik (BSB)',
                    ]),
                    'sakit' => 1,
                    'izin' => 1,
                    'alfa' => 0,
                    'tinggi_badan' => 95 + ($i % 15),
                    'berat_badan' => 15 + ($i % 8),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $studentCountGlobal++;
            }
        }

        // Demo Multi-Child Parent
        // Connect parent of 1A-1 to also be guardian of 1B-1
        $parentDemoMulti = DB::table('users')->where('email', 'ortu.1A.1@gmail.com')->first();
        $studentDemo1B1 = DB::table('siswas')->where('nama_lengkap', 'LIKE', '%(1B-1)%')->first();
        if ($parentDemoMulti && $studentDemo1B1) {
            DB::table('student_guardian')->insert([
                'siswa_id' => $studentDemo1B1->id,
                'user_id' => $parentDemoMulti->id,
                'peran' => 'ibu',
                'is_wali_utama' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ----------------------------------------------------
        // 9. SEED JOURNALS, MATERI, MEDIA & ANNOUNCEMENTS
        // ----------------------------------------------------

        // A. Learning Materials (Materi)
        $materi1Id = DB::table('materis')->insertGetId([
            'school_id' => $school1Id,
            'judul' => 'Pengenalan Huruf Hijaiyah & Doa Harian',
            'deskripsi' => 'Materi pembelajaran mengenal huruf hijaiyah dasar alif ba ta dan doa sebelum makan.',
            'mata_pelajaran' => 'Agama & Moral',
            'kelas' => 'TK-A',
            'created_by' => $guruA1Id,
            'status' => 'Approved',
            'approved_by' => $kepsek1Id,
            'approved_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $materi2Id = DB::table('materis')->insertGetId([
            'school_id' => $school1Id,
            'judul' => 'Mewarnai & Keterampilan Motorik Halus',
            'deskripsi' => 'Modul panduan menggunting, menempel, dan mewarnai bentuk geometri.',
            'mata_pelajaran' => 'Seni & Motorik',
            'kelas' => 'TK-B',
            'created_by' => $guruB1Id,
            'status' => 'Approved',
            'approved_by' => $kepsek1Id,
            'approved_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // B. Daily Journals (Jurnal Harian)
        DB::table('jurnal_harians')->insert([
            [
                'school_id' => $school1Id,
                'kelas_id' => $kelas1AId,
                'materi_id' => $materi1Id,
                'user_id' => $guruA1Id,
                'tanggal' => now()->subDays(2)->format('Y-m-d'),
                'tema' => 'Aku Hamba Allah',
                'kegiatan' => 'Mengenal anggota tubuh dan melafalkan doa sebelum makan bersama anak-anak.',
                'catatan' => 'Semua anak sangat antusias mengikuti kegiatan hafalan.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'school_id' => $school1Id,
                'kelas_id' => $kelas1BId,
                'materi_id' => $materi2Id,
                'user_id' => $guruB1Id,
                'tanggal' => now()->subDays(1)->format('Y-m-d'),
                'tema' => 'Lingkunganku',
                'kegiatan' => 'Mewarnai gambar sekolah dan membuat kerajinan dari kertas lipat.',
                'catatan' => 'Motorik halus anak-anak mengalami peningkatan pesat.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        // C. Activity Media (Media Kegiatan)
        if (DB::getSchemaBuilder()->hasTable('media_kegiatans')) {
            $mediaId = DB::table('media_kegiatans')->insertGetId([
                'school_id' => $school1Id,
                'user_id' => $guruA1Id,
                'judul' => 'Kegiatan Senam Pagi & Berkebun Bersama',
                'deskripsi' => 'Dokumentasi senam pagi bersama di halaman sekolah.',
                'file_path' => 'landing/hero.jpg',
                'file_type' => 'image',
                'tanggal_kegiatan' => now()->subDays(3)->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $sampleSiswas = DB::table('siswas')->where('school_id', $school1Id)->limit(5)->get();
            foreach ($sampleSiswas as $s) {
                DB::table('media_kegiatan_siswas')->insert([
                    'media_kegiatan_id' => $mediaId,
                    'siswa_id' => $s->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // D. Announcements (Pengumuman)
        DB::table('announcements')->insert([
            [
                'school_id' => $school1Id,
                'kelas_id' => null,
                'judul' => 'Undangan Rapat Wali Murid & Pembagian Seragam Baru',
                'isi' => 'Diberitahukan kepada seluruh wali murid TK Permata Al-Hikmah untuk menghadiri pertemuan pada hari Sabtu besok.',
                'target' => 'wali_murid',
                'tanggal_kegiatan' => now()->addDays(5)->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'school_id' => $school3Id,
                'kelas_id' => null,
                'judul' => 'Kegiatan Outbound & Field Trip Ke Kebun Teh',
                'isi' => 'Pengumuman kegiatan edukasi luar kelas untuk kelas TK-A dan TK-B.',
                'target' => 'semua',
                'tanggal_kegiatan' => now()->addDays(10)->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        // E. Settings
        $settings = [
            'site_title' => 'Yayasan Pendidikan Al-Hikmah 2',
            'site_subtitle' => 'Sistem Informasi ERP Manajemen Pendidikan TK & PAUD Modern',
            'welcome_text' => 'Selamat Datang di Portal Resmi Yayasan Al-Hikmah 2',
            'phone' => '0341-551122',
            'email' => 'info@alhikmah.sch.id',
            'hero_image' => '',
        ];

        foreach ($settings as $k => $v) {
            DB::table('settings')->updateOrInsert(
                ['key' => $k],
                ['value' => $v, 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
