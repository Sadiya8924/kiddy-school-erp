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
        // 0. Ensure all database migrations are executed first so all tables exist
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
            'yayasan_registrations',
            'rapor_template_items',
            'rapor_templates',
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
        // 2. SEED YAYASANS (2 TENANTS + LANDING CONTENT)
        // ----------------------------------------------------
        $yayasan1LandingContent = [
            'template' => 'ocean',
            'tagline' => 'Membangun Karakter Islami, Cerdas & Ceria Sejak Dini',
            'deskripsi' => 'Yayasan Pendidikan Al-Hikmah 1 berkomitmen memberikan pendidikan usia dini (TK & PAUD) yang holistik, berbasis nilai-nilai akhlak mulia dan kreativitas modern.',
            'kontak_email' => 'info@alhikmah.sch.id',
            'kontak_telepon' => '0341-551122',
            'kontak_alamat' => 'Jl. Veteran No. 45, Malang, Jawa Timur',
        ];

        $yayasan1Id = DB::table('yayasans')->insertGetId([
            'nama_yayasan' => 'Yayasan Pendidikan Al-Hikmah 1',
            'kode_yayasan' => 'YYS-001',
            'penanggung_jawab' => 'Prof. Dr. H. M. Syaifuddin, M.Pd',
            'alamat' => 'Jl. Veteran No. 45, Malang',
            'telepon' => '0341-551122',
            'logo_yayasan' => null,
            'landing_content' => json_encode($yayasan1LandingContent),
            'status' => 'aktif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $yayasan2LandingContent = [
            'template' => 'ceria',
            'tagline' => 'Pendidikan Ceria, Menyenangkan & Berkualitas',
            'deskripsi' => 'Membina generasi mandiri, kreatif, dan berbudi pekerti luhur dengan metode belajar sentra.',
            'kontak_email' => 'admin@binainsani.com',
            'kontak_telepon' => '0341-778899',
            'kontak_alamat' => 'Jl. Soekarno Hatta No. 88, Malang',
        ];

        $yayasan2Id = DB::table('yayasans')->insertGetId([
            'nama_yayasan' => 'Yayasan Bina Insani Ceria',
            'kode_yayasan' => 'YYS-002',
            'penanggung_jawab' => 'Hj. Siti Aminah, S.Pd.I',
            'alamat' => 'Jl. Soekarno Hatta No. 88, Malang',
            'telepon' => '0341-778899',
            'logo_yayasan' => null,
            'landing_content' => json_encode($yayasan2LandingContent),
            'status' => 'aktif',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Seed Registrasi Yayasan Baru (untuk tes modul Owner SaaS ACC/Reject pendaftaran yayasan)
        if (DB::getSchemaBuilder()->hasTable('yayasan_registrations')) {
            DB::table('yayasan_registrations')->insert([
                [
                    'nama_yayasan' => 'Yayasan Tunas Harapan Bangsa',
                    'kode_yayasan' => null,
                    'penanggung_jawab' => 'Drs. H. Bambang Irawan, M.Si',
                    'email_kontak' => 'kontak@tunasharapan.org',
                    'telepon' => '081234567890',
                    'alamat' => 'Jl. Pahlawan Trip No. 12, Malang',
                    'lintang' => -7.9654000,
                    'bujur' => 112.6321000,
                    'nomor_akta_pendirian' => 'AHU-001234.AH.01.04.2026',
                    'nomor_sk_kemenkumham' => 'SK-KEMENKUMHAM-2026/08/01',
                    'npwp_yayasan' => '98.765.432.1-654.000',
                    'status' => 'menunggu',
                    'catatan_owner' => null,
                    'diproses_at' => null,
                    'created_at' => $now->copy()->subDays(1),
                    'updated_at' => $now->copy()->subDays(1),
                ],
                [
                    'nama_yayasan' => 'Yayasan Nurul Fikri Cendekia',
                    'kode_yayasan' => 'YYS-003',
                    'penanggung_jawab' => 'K.H. Abdullah Gymnastiar, M.Ag',
                    'email_kontak' => 'info@nurulfikri.sch.id',
                    'telepon' => '081398765432',
                    'alamat' => 'Jl. Danau Toba No. 44, Sawojajar, Malang',
                    'lintang' => -7.9782000,
                    'bujur' => 112.6514000,
                    'nomor_akta_pendirian' => 'AHU-005678.AH.01.04.2026',
                    'nomor_sk_kemenkumham' => 'SK-KEMENKUMHAM-2026/08/05',
                    'npwp_yayasan' => '91.234.567.8-654.000',
                    'status' => 'disetujui',
                    'catatan_owner' => 'Dokumen legalitas lengkap dan terverifikasi sah.',
                    'diproses_at' => $now->copy()->subHours(12),
                    'created_at' => $now->copy()->subDays(3),
                    'updated_at' => $now->copy()->subHours(12),
                ],
            ]);
        }

        // ----------------------------------------------------
        // 3. SEED SCHOOLS (INCLUDING NEW TK: TK TERATAI)
        // ----------------------------------------------------
        // TK 1 - Yayasan 1
        $school1Id = DB::table('schools')->insertGetId([
            'yayasan_id' => $yayasan1Id,
            'nama_sekolah' => 'TK Permata Al-Hikmah',
            'npsn' => '20101111',
            'alamat' => 'Jl. Veteran No. 45A, Malang',
            'telepon' => '0341-551123',
            'status' => 'aktif',
            'is_rapor_open' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // TK 2 - Yayasan 1
        $school2Id = DB::table('schools')->insertGetId([
            'yayasan_id' => $yayasan1Id,
            'nama_sekolah' => 'TK Matahari Al-Hikmah',
            'npsn' => '20101112',
            'alamat' => 'Jl. Mawar No. 12, Malang',
            'telepon' => '0341-551124',
            'status' => 'aktif',
            'is_rapor_open' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // TK 3 (BARU) - Yayasan 1: TK Teratai Al-Hikmah
        $schoolTerataiId = DB::table('schools')->insertGetId([
            'yayasan_id' => $yayasan1Id,
            'nama_sekolah' => 'TK Teratai Al-Hikmah (Cabang Baru)',
            'npsn' => '20101113',
            'alamat' => 'Jl. Ijen No. 10, Klojen, Malang',
            'telepon' => '0341-551125',
            'status' => 'aktif',
            'is_rapor_open' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // TK 4 - Yayasan 2
        $school3Id = DB::table('schools')->insertGetId([
            'yayasan_id' => $yayasan2Id,
            'nama_sekolah' => 'TK Bintang Insani',
            'npsn' => '20202221',
            'alamat' => 'Jl. Soekarno Hatta No. 88B, Malang',
            'telepon' => '0341-778890',
            'status' => 'aktif',
            'is_rapor_open' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // TK 5 - Yayasan 2
        $school4Id = DB::table('schools')->insertGetId([
            'yayasan_id' => $yayasan2Id,
            'nama_sekolah' => 'TK Cendekia Insani',
            'npsn' => '20202222',
            'alamat' => 'Jl. Melati No. 5, Malang',
            'telepon' => '0341-778891',
            'status' => 'aktif',
            'is_rapor_open' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Custom Domains
        if (DB::getSchemaBuilder()->hasTable('domains')) {
            DB::table('domains')->insert([
                ['school_id' => $school1Id, 'domain' => 'tkpermata.sch.id', 'is_verified' => true, 'created_at' => $now, 'updated_at' => $now],
                ['school_id' => $school2Id, 'domain' => 'tkmatahari.sch.id', 'is_verified' => true, 'created_at' => $now, 'updated_at' => $now],
                ['school_id' => $schoolTerataiId, 'domain' => 'tkteratai.sch.id', 'is_verified' => true, 'created_at' => $now, 'updated_at' => $now],
                ['school_id' => $school3Id, 'domain' => 'tkbintang.sch.id', 'is_verified' => true, 'created_at' => $now, 'updated_at' => $now],
                ['school_id' => $school4Id, 'domain' => 'tkcendekia.sch.id', 'is_verified' => true, 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // ----------------------------------------------------
        // 4. SEED USERS ACROSS ALL ROLES
        // ----------------------------------------------------

        // A. Owner SaaS Platform
        $ownerId = DB::table('users')->insertGetId([
            'name' => 'Owner Platform SaaS',
            'email' => 'owner@yayasan.com',
            'password' => $defaultPassword,
            'role' => 'owner',
            'no_hp' => '081111111111',
            'yayasan_id' => null,
            'school_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // B. Tenant 1 - Yayasan Al-Hikmah 1 Users
        $superadmin1Id = DB::table('users')->insertGetId([
            'name' => 'Super Admin Al-Hikmah',
            'email' => 'superadmin1@alhikmah.com',
            'password' => $defaultPassword,
            'role' => 'super_admin',
            'no_hp' => '081222222221',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $school1Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // TK Permata Staff
        $admin1Id = DB::table('users')->insertGetId([
            'name' => 'Admin TK Permata',
            'email' => 'admin1@tkpermata.sch.id',
            'password' => $defaultPassword,
            'role' => 'admin',
            'no_hp' => '081333333331',
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
            'no_hp' => '081444444441',
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
            'no_hp' => '081555555551',
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
            'no_hp' => '081555555552',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $school1Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // TK Matahari Staff
        $admin2Id = DB::table('users')->insertGetId([
            'name' => 'Admin TK Matahari',
            'email' => 'admin2@tkmatahari.sch.id',
            'password' => $defaultPassword,
            'role' => 'admin',
            'no_hp' => '081333333332',
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
            'no_hp' => '081444444442',
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
            'no_hp' => '081555555553',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $school2Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // TK Teratai (CABANG BARU) Staff
        $adminTerataiId = DB::table('users')->insertGetId([
            'name' => 'Admin TK Teratai',
            'email' => 'admin.teratai@alhikmah.com',
            'password' => $defaultPassword,
            'role' => 'admin',
            'no_hp' => '081333333335',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $schoolTerataiId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $kepsekTerataiId = DB::table('users')->insertGetId([
            'name' => 'Hj. Farida Hanum, M.Pd (Kepsek Teratai)',
            'email' => 'kepsek.teratai@alhikmah.com',
            'password' => $defaultPassword,
            'role' => 'kepsek',
            'no_hp' => '081444444445',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $schoolTerataiId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $guruTerataiId = DB::table('users')->insertGetId([
            'name' => 'Fitria Anggraini, S.Pd (Guru Teratai TK-A)',
            'email' => 'guru.teratai@alhikmah.com',
            'password' => $defaultPassword,
            'role' => 'guru',
            'no_hp' => '081555555555',
            'yayasan_id' => $yayasan1Id,
            'school_id' => $schoolTerataiId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // C. Tenant 2 - Yayasan Bina Insani Users
        $superadmin2Id = DB::table('users')->insertGetId([
            'name' => 'Super Admin Bina Insani',
            'email' => 'superadmin2@binainsani.com',
            'password' => $defaultPassword,
            'role' => 'super_admin',
            'no_hp' => '081222222222',
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
            'no_hp' => '081333333333',
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
            'no_hp' => '081444444443',
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
            'no_hp' => '081555555556',
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
            'no_hp' => '081555555557',
            'yayasan_id' => $yayasan2Id,
            'school_id' => $school3Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $admin4Id = DB::table('users')->insertGetId([
            'name' => 'Admin TK Cendekia',
            'email' => 'admin4@tkcendekia.sch.id',
            'password' => $defaultPassword,
            'role' => 'admin',
            'no_hp' => '081333333334',
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
            'no_hp' => '081444444444',
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
            'no_hp' => '081555555558',
            'yayasan_id' => $yayasan2Id,
            'school_id' => $school4Id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Link Kepsek User to Schools
        DB::table('schools')->where('id', $school1Id)->update(['kepala_sekolah_user_id' => $kepsek1Id]);
        DB::table('schools')->where('id', $school2Id)->update(['kepala_sekolah_user_id' => $kepsek2Id]);
        DB::table('schools')->where('id', $schoolTerataiId)->update(['kepala_sekolah_user_id' => $kepsekTerataiId]);
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

        $activeSemesterId = $semGanjilId;

        // ----------------------------------------------------
        // 6. SEED CLASSES (ROMBEL)
        // ----------------------------------------------------
        // TK Permata
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

        // TK Matahari
        $kelas2AId = DB::table('kelas')->insertGetId([
            'school_id' => $school2Id,
            'nama_kelas' => 'TK-A Melati',
            'tingkat' => 'TK-A',
            'kelompok' => 'A',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('users')->where('id', $guru2Id)->update(['kelas_id' => $kelas2AId]);

        // TK Teratai (Cabang Baru)
        $kelasTerataiAId = DB::table('kelas')->insertGetId([
            'school_id' => $schoolTerataiId,
            'nama_kelas' => 'TK-A Mawar Teratai',
            'tingkat' => 'TK-A',
            'kelompok' => 'A',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('users')->where('id', $guruTerataiId)->update(['kelas_id' => $kelasTerataiAId]);

        $kelasTerataiBId = DB::table('kelas')->insertGetId([
            'school_id' => $schoolTerataiId,
            'nama_kelas' => 'TK-B Melati Teratai',
            'tingkat' => 'TK-B',
            'kelompok' => 'B',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // TK Bintang
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

        // TK Cendekia
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
        // 7. SEED STUDENTS - A. ACTIVE STUDENTS IN CLASSES
        // ----------------------------------------------------
        $firstNamesLaki = ['Aditya', 'Bima', 'Cakra', 'Dafa', 'Erlangga', 'Faris', 'Gibran', 'Hafiz', 'Irfan', 'Jovian', 'Kenzo', 'Lutfi', 'Muhammad', 'Naufal', 'Oky', 'Pradipta', 'Raffi', 'Satria', 'Taufik', 'Zayyan'];
        $firstNamesPerempuan = ['Alya', 'Bella', 'Citra', 'Dina', 'Eka', 'Fiona', 'Gita', 'Hania', 'Intan', 'Jasmine', 'Keisha', 'Laras', 'Nabila', 'Olivia', 'Putri', 'Qonita', 'Rania', 'Sasa', 'Tania', 'Zahra'];
        $lastNames = ['Pratama', 'Wibowo', 'Kurniawan', 'Santoso', 'Hidayat', 'Saputra', 'Wijaya', 'Nugraha', 'Setiawan', 'Utama', 'Rahmadani', 'Ramadhan', 'Lestari', 'Putri', 'Sari', 'Kusuma', 'Kuswandari', 'Mahendra', 'Firmansyah', 'Syahputra'];

        $classesList = [
            ['id' => $kelas1AId, 'school_id' => $school1Id, 'prefix' => '1A', 'count' => 20, 'yys_id' => $yayasan1Id],
            ['id' => $kelas1BId, 'school_id' => $school1Id, 'prefix' => '1B', 'count' => 20, 'yys_id' => $yayasan1Id],
            ['id' => $kelas2AId, 'school_id' => $school2Id, 'prefix' => '2A', 'count' => 15, 'yys_id' => $yayasan1Id],
            ['id' => $kelasTerataiAId, 'school_id' => $schoolTerataiId, 'prefix' => 'TER', 'count' => 10, 'yys_id' => $yayasan1Id],
            ['id' => $kelas3AId, 'school_id' => $school3Id, 'prefix' => '3A', 'count' => 15, 'yys_id' => $yayasan2Id],
            ['id' => $kelas3BId, 'school_id' => $school3Id, 'prefix' => '3B', 'count' => 15, 'yys_id' => $yayasan2Id],
            ['id' => $kelas4AId, 'school_id' => $school4Id, 'prefix' => '4A', 'count' => 15, 'yys_id' => $yayasan2Id],
        ];

        $studentCountGlobal = 1;

        foreach ($classesList as $cls) {
            $clsId = $cls['id'];
            $schId = $cls['school_id'];
            $prefix = $cls['prefix'];
            $yysId = $cls['yys_id'];
            $totalCount = $cls['count'];

            for ($i = 1; $i <= $totalCount; $i++) {
                $isLaki = ($i % 2 === 1);
                $gender = $isLaki ? 'Laki-laki' : 'Perempuan';
                $fnList = $isLaki ? $firstNamesLaki : $firstNamesPerempuan;
                $fn = $fnList[($i - 1) % count($fnList)];
                $ln = $lastNames[($i - 1) % count($lastNames)];
                $fullName = "$fn $ln ($prefix-$i)";

                $nik = sprintf("3573%02d%06d%04d", $schId, $studentCountGlobal, $i);
                $noKK = sprintf("3573%02d%06d0001", $schId, $studentCountGlobal);
                $nisn = sprintf("0019%02d%04d", $schId, $studentCountGlobal);
                $noInduk = sprintf("IND-%d-%03d", $schId, $studentCountGlobal);

                $parentEmail = "ortu.{$prefix}.{$i}@gmail.com";
                $parentName = "Bpk/Ibu $ln";
                $parentPhone = '0812' . sprintf('%08d', $studentCountGlobal);

                // Create Parent User account
                $parentUserId = DB::table('users')->insertGetId([
                    'name' => $parentName,
                    'email' => $parentEmail,
                    'no_hp' => $parentPhone,
                    'password' => $defaultPassword,
                    'role' => 'wali_murid',
                    'school_id' => $schId,
                    'yayasan_id' => $yysId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Create Student
                $siswaId = DB::table('siswas')->insertGetId([
                    'school_id' => $schId,
                    'nama_lengkap' => $fullName,
                    'program_pilihan' => 'TK Pagi (Tahun: 2026/2027)',
                    'nik' => $nik,
                    'no_kk' => $noKK,
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
                    'hp' => $parentPhone,
                    'alamat' => "Jl. Raya Veteran No. $i, Malang",
                    'rt' => sprintf('%02d', ($i % 10) + 1),
                    'rw' => '03',
                    'dusun' => 'Ketawanggede',
                    'kelurahan' => 'Ketawanggede',
                    'kecamatan' => 'Lowokwaru',
                    'kode_pos' => '65145',
                    'agama' => 'Islam',
                    'anak_ke' => ($i % 3) + 1,
                    'jml_saudara' => 2,
                    'jenis_tinggal' => 'Bersama Orang Tua',
                    'alat_transportasi' => 'Kendaraan Pribadi',
                    'jarak_rumah' => '1.5 km',
                    'tinggi_badan' => 95 + ($i % 15),
                    'berat_badan' => 15 + ($i % 8),
                    'lingkar_kepala' => '48',
                    'koordinat_rumah' => '-7.95' . sprintf('%04d', $i) . ', 112.61' . sprintf('%04d', $i),
                    'lintang' => -7.9500000 - ($i * 0.001),
                    'bujur' => 112.6100000 + ($i * 0.001),
                    'user_id' => $parentUserId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Link Student to Class (Rombel)
                DB::table('anggota_kelas')->insert([
                    'kelas_id' => $clsId,
                    'siswa_id' => $siswaId,
                    'semester_id' => $activeSemesterId,
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

                // Daily Attendance (Past 5 days)
                for ($d = 1; $d <= 5; $d++) {
                    $statuses = ['Hadir', 'Hadir', 'Hadir', 'Hadir', 'Izin', 'Sakit'];
                    $st = $statuses[($i + $d) % count($statuses)];
                    DB::table('absensis')->insert([
                        'school_id' => $schId,
                        'siswa_id' => $siswaId,
                        'tanggal' => now()->subDays(6 - $d)->format('Y-m-d'),
                        'status' => $st,
                        'keterangan' => ($st !== 'Hadir') ? 'Keterangan ' . $st . ' anak' : 'Hadir tepat waktu di kelas',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                // Physical Development Check (Perkembangan Fisik)
                DB::table('perkembangan_fisiks')->insert([
                    'school_id' => $schId,
                    'siswa_id' => $siswaId,
                    'tgl_periksa' => now()->subDays(15)->format('Y-m-d'),
                    'tinggi_badan' => 95 + ($i % 15),
                    'berat_badan' => 15 + ($i % 8),
                    'lingkar_kepala' => 48,
                    'catatan' => 'Pertumbuhan fisik anak sangat baik, nafsu makan bagus, dan motorik lincah.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Rapor Entry
                $raporStatus = ($i % 3 === 0) ? 'draft' : 'Approved';
                DB::table('rapors')->insert([
                    'school_id' => $schId,
                    'siswa_id' => $siswaId,
                    'semester_id' => $activeSemesterId,
                    'status' => $raporStatus,
                    'nilai_json' => json_encode([
                        'agama' => 'Sangat Baik (BSB) - Hafalan doa dan surat pendek lancar',
                        'motorik' => 'Berkembang Sesuai Harapan (BSH) - Keterampilan gunting tempel baik',
                        'kognitif' => 'Sangat Baik (BSB) - Mengenal angka dan pola geometri',
                        'bahasa' => 'Berkembang Sesuai Harapan (BSH) - Komunikasi lisan lancar',
                        'sosial_emosional' => 'Sangat Baik (BSB) - Mandiri dan mau berbagi dengan teman',
                        'catatan_guru' => 'Pertahankan semangat belajar ananda di rumah dan di sekolah.',
                    ]),
                    'sakit' => ($i % 4 === 0) ? 1 : 0,
                    'izin' => ($i % 5 === 0) ? 1 : 0,
                    'alfa' => 0,
                    'tinggi_badan' => 95 + ($i % 15),
                    'berat_badan' => 15 + ($i % 8),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $studentCountGlobal++;
            }
        }

        // Demo Multi-Child Parent: Wali murid 1A-1 juga punya anak di 1B-1
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
        // 8. SEED CALON SISWA BARU / INDEN (STATUS 'menunggu' - BELUM DI-ACC ADMIN)
        // ----------------------------------------------------
        $calonSiswas = [
            [
                'school_id' => $school1Id,
                'nama_lengkap' => 'Rizky Ananda Pratama',
                'program_pilihan' => 'TK Pagi (Tahun: 2026/2027)',
                'nik' => '3573012005210001',
                'no_kk' => '3573012005210001',
                'nisn' => null,
                'no_induk' => null,
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Malang',
                'tgl_lahir' => '2021-06-12',
                'status' => 'menunggu',
                'nama_ayah' => 'Bapak Agus Pratama',
                'nama_ibu' => 'Ibu Rina Susanti',
                'nama_ortu' => 'Agus Pratama',
                'email' => 'ortu.calon.rizky@gmail.com',
                'hp' => '081234567001',
                'alamat' => 'Jl. Bendungan Sigura-gura No. 15, Malang',
                'tinggi_badan' => 98,
                'berat_badan' => 16,
                'lingkar_kepala' => 48,
                'yys_id' => $yayasan1Id,
            ],
            [
                'school_id' => $school1Id,
                'nama_lengkap' => 'Siti Zulaikha Rahmah',
                'program_pilihan' => 'TK Siang (Tahun: 2026/2027)',
                'nik' => '3573012005210002',
                'no_kk' => '3573012005210002',
                'nisn' => null,
                'no_induk' => null,
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Malang',
                'tgl_lahir' => '2021-08-20',
                'status' => 'menunggu',
                'nama_ayah' => 'Bapak Ahmad Ridwan',
                'nama_ibu' => 'Ibu Nurul Hidayah',
                'nama_ortu' => 'Nurul Hidayah',
                'email' => 'ortu.calon.zulaikha@gmail.com',
                'hp' => '081234567002',
                'alamat' => 'Jl. Sumbersari Gang 5 No. 8, Malang',
                'tinggi_badan' => 96,
                'berat_badan' => 15,
                'lingkar_kepala' => 47,
                'yys_id' => $yayasan1Id,
            ],
            [
                'school_id' => $school1Id,
                'nama_lengkap' => 'Farhan Maulana Yusuf',
                'program_pilihan' => 'PAUD (Tahun: 2026/2027)',
                'nik' => '3573012005210003',
                'no_kk' => '3573012005210003',
                'nisn' => null,
                'no_induk' => null,
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Malang',
                'tgl_lahir' => '2022-02-14',
                'status' => 'menunggu',
                'nama_ayah' => 'Bapak Yusuf Santoso',
                'nama_ibu' => 'Ibu Marlina',
                'nama_ortu' => 'Yusuf Santoso',
                'email' => 'ortu.calon.farhan@gmail.com',
                'hp' => '081234567003',
                'alamat' => 'Jl. Gajayana No. 22, Malang',
                'tinggi_badan' => 90,
                'berat_badan' => 13,
                'lingkar_kepala' => 46,
                'yys_id' => $yayasan1Id,
            ],
            [
                'school_id' => $school1Id,
                'nama_lengkap' => 'Aisyah Zahira Putri (Inden 2027/2028)',
                'program_pilihan' => 'TK Pagi (Tahun: 2027/2028)',
                'nik' => '3573012005210004',
                'no_kk' => '3573012005210004',
                'nisn' => null,
                'no_induk' => null,
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Malang',
                'tgl_lahir' => '2022-10-05',
                'status' => 'menunggu',
                'nama_ayah' => 'Bapak Hendra Gunawan',
                'nama_ibu' => 'Ibu Ratna Dewi',
                'nama_ortu' => 'Hendra Gunawan',
                'email' => 'ortu.calon.aisyah@gmail.com',
                'hp' => '081234567004',
                'alamat' => 'Jl. Candi Mendut No. 3, Malang',
                'tinggi_badan' => 88,
                'berat_badan' => 12,
                'lingkar_kepala' => 45,
                'yys_id' => $yayasan1Id,
            ],
            [
                'school_id' => $schoolTerataiId,
                'nama_lengkap' => 'Bilqis Khumairah (Calon Murid Teratai)',
                'program_pilihan' => 'TK Pagi (Tahun: 2026/2027)',
                'nik' => '3573052005210001',
                'no_kk' => '3573052005210001',
                'nisn' => null,
                'no_induk' => null,
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Malang',
                'tgl_lahir' => '2021-07-19',
                'status' => 'menunggu',
                'nama_ayah' => 'Bapak Mansyur Hidayat',
                'nama_ibu' => 'Ibu Halimah',
                'nama_ortu' => 'Mansyur Hidayat',
                'email' => 'ortu.calon.bilqis@gmail.com',
                'hp' => '081234567005',
                'alamat' => 'Jl. Kawi No. 18, Klojen, Malang',
                'tinggi_badan' => 97,
                'berat_badan' => 15,
                'lingkar_kepala' => 47,
                'yys_id' => $yayasan1Id,
            ],
        ];

        foreach ($calonSiswas as $cs) {
            $parentUserId = DB::table('users')->insertGetId([
                'name' => $cs['nama_ortu'],
                'email' => $cs['email'],
                'no_hp' => $cs['hp'],
                'password' => $defaultPassword,
                'role' => 'wali_murid',
                'school_id' => $cs['school_id'],
                'yayasan_id' => $cs['yys_id'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $calonSiswaId = DB::table('siswas')->insertGetId([
                'school_id' => $cs['school_id'],
                'nama_lengkap' => $cs['nama_lengkap'],
                'program_pilihan' => $cs['program_pilihan'],
                'nik' => $cs['nik'],
                'no_kk' => $cs['no_kk'],
                'nisn' => $cs['nisn'],
                'no_induk' => $cs['no_induk'],
                'jenis_kelamin' => $cs['jenis_kelamin'],
                'tempat_lahir' => $cs['tempat_lahir'],
                'tgl_lahir' => $cs['tgl_lahir'],
                'status' => $cs['status'],
                'nama_ayah' => $cs['nama_ayah'],
                'nama_ibu' => $cs['nama_ibu'],
                'nama_ortu' => $cs['nama_ortu'],
                'email' => $cs['email'],
                'hp' => $cs['hp'],
                'alamat' => $cs['alamat'],
                'tinggi_badan' => $cs['tinggi_badan'],
                'berat_badan' => $cs['berat_badan'],
                'lingkar_kepala' => $cs['lingkar_kepala'],
                'user_id' => $parentUserId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('student_guardian')->insert([
                'siswa_id' => $calonSiswaId,
                'user_id' => $parentUserId,
                'peran' => 'ayah',
                'is_wali_utama' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            // Catatan: Calon siswa status 'menunggu' TIDAK dimasukkan ke anggota_kelas!
        }

        // ----------------------------------------------------
        // 9. SEED SISWA AKTIF YANG BELUM MENENTUKAN KELAS (BELUM MASUK ROMBEL)
        // ----------------------------------------------------
        $unassignedSiswas = [
            [
                'school_id' => $school1Id,
                'nama_lengkap' => 'Muhammad Kenzo Pratama (Belum Ada Rombel)',
                'program_pilihan' => 'TK Pagi (Tahun: 2026/2027)',
                'nik' => '3573012005210901',
                'no_kk' => '3573012005210901',
                'nisn' => '0019019001',
                'no_induk' => 'IND-1-901',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Malang',
                'tgl_lahir' => '2021-04-10',
                'status' => 'aktif', // Status sudah aktif, tapi belum punya kelas
                'nama_ayah' => 'Bapak Eko Pratama',
                'nama_ibu' => 'Ibu Linda',
                'nama_ortu' => 'Eko Pratama',
                'email' => 'ortu.unassigned.kenzo@gmail.com',
                'hp' => '081299990001',
                'alamat' => 'Jl. Borobudur No. 25, Malang',
                'yys_id' => $yayasan1Id,
            ],
            [
                'school_id' => $school1Id,
                'nama_lengkap' => 'Nayla Az-Zahra Kusuma (Belum Ada Rombel)',
                'program_pilihan' => 'TK Pagi (Tahun: 2026/2027)',
                'nik' => '3573012005210902',
                'no_kk' => '3573012005210902',
                'nisn' => '0019019002',
                'no_induk' => 'IND-1-902',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Malang',
                'tgl_lahir' => '2021-07-25',
                'status' => 'aktif', // Status sudah aktif, tapi belum punya kelas
                'nama_ayah' => 'Bapak Surya Kusuma',
                'nama_ibu' => 'Ibu Maya Kusuma',
                'nama_ortu' => 'Maya Kusuma',
                'email' => 'ortu.unassigned.nayla@gmail.com',
                'hp' => '081299990002',
                'alamat' => 'Jl. Kalpataru No. 14, Malang',
                'yys_id' => $yayasan1Id,
            ],
            [
                'school_id' => $school1Id,
                'nama_lengkap' => 'Arkananta Bagaskara (Belum Ada Rombel)',
                'program_pilihan' => 'TK Siang (Tahun: 2026/2027)',
                'nik' => '3573012005210903',
                'no_kk' => '3573012005210903',
                'nisn' => '0019019003',
                'no_induk' => 'IND-1-903',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Malang',
                'tgl_lahir' => '2021-03-18',
                'status' => 'aktif',
                'nama_ayah' => 'Bapak Bagas Surya',
                'nama_ibu' => 'Ibu Anisa',
                'nama_ortu' => 'Bagas Surya',
                'email' => 'ortu.unassigned.arkan@gmail.com',
                'hp' => '081299990003',
                'alamat' => 'Jl. Sukarno Hatta Indah No. 7, Malang',
                'yys_id' => $yayasan1Id,
            ],
            [
                'school_id' => $schoolTerataiId,
                'nama_lengkap' => 'Genta Al-Ghifari (Teratai - Belum Ada Rombel)',
                'program_pilihan' => 'TK Pagi (Tahun: 2026/2027)',
                'nik' => '3573052005210901',
                'no_kk' => '3573052005210901',
                'nisn' => '0019059001',
                'no_induk' => 'IND-5-901',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Malang',
                'tgl_lahir' => '2021-05-30',
                'status' => 'aktif',
                'nama_ayah' => 'Bapak M. Ghifari',
                'nama_ibu' => 'Ibu Zahra',
                'nama_ortu' => 'M. Ghifari',
                'email' => 'ortu.unassigned.genta@gmail.com',
                'hp' => '081299990005',
                'alamat' => 'Jl. Semeru No. 41, Malang',
                'yys_id' => $yayasan1Id,
            ],
        ];

        foreach ($unassignedSiswas as $us) {
            $parentUserId = DB::table('users')->insertGetId([
                'name' => $us['nama_ortu'],
                'email' => $us['email'],
                'no_hp' => $us['hp'],
                'password' => $defaultPassword,
                'role' => 'wali_murid',
                'school_id' => $us['school_id'],
                'yayasan_id' => $us['yys_id'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $unassignedId = DB::table('siswas')->insertGetId([
                'school_id' => $us['school_id'],
                'nama_lengkap' => $us['nama_lengkap'],
                'program_pilihan' => $us['program_pilihan'],
                'nik' => $us['nik'],
                'no_kk' => $us['no_kk'],
                'nisn' => $us['nisn'],
                'no_induk' => $us['no_induk'],
                'jenis_kelamin' => $us['jenis_kelamin'],
                'tempat_lahir' => $us['tempat_lahir'],
                'tgl_lahir' => $us['tgl_lahir'],
                'status' => $us['status'], // 'aktif'
                'nama_ayah' => $us['nama_ayah'],
                'nama_ibu' => $us['nama_ibu'],
                'nama_ortu' => $us['nama_ortu'],
                'email' => $us['email'],
                'hp' => $us['hp'],
                'alamat' => $us['alamat'],
                'tinggi_badan' => 97,
                'berat_badan' => 15,
                'lingkar_kepala' => 48,
                'user_id' => $parentUserId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('student_guardian')->insert([
                'siswa_id' => $unassignedId,
                'user_id' => $parentUserId,
                'peran' => 'ayah',
                'is_wali_utama' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            // PENTING: Siswa aktif ini TIDAK di-insert ke anggota_kelas!
            // Sehingga di menu Admin/Proses Kelas akan muncul berstatus "Belum Ada Kelas / Perlu Penataan Rombel".
        }

        // ----------------------------------------------------
        // 10. SEED SISWA LULUS / ALUMNI (STATUS 'lulus')
        // ----------------------------------------------------
        DB::table('siswas')->insert([
            [
                'school_id' => $school1Id,
                'nama_lengkap' => 'Almarhumah Salsabila (Alumni 2025/2026)',
                'program_pilihan' => 'TK Pagi (Tahun: 2025/2026)',
                'nik' => '3573012005200001',
                'no_kk' => '3573012005200001',
                'nisn' => '0018010001',
                'no_induk' => 'IND-1-AL01',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Malang',
                'tgl_lahir' => '2020-02-10',
                'status' => 'lulus',
                'nama_ayah' => 'Bpk. Hendro',
                'nama_ibu' => 'Ibu Siti',
                'nama_ortu' => 'Hendro',
                'email' => 'alumni1@gmail.com',
                'hp' => '081987654321',
                'alamat' => 'Jl. Candi Kalasan No. 2, Malang',
                'tinggi_badan' => 110,
                'berat_badan' => 19,
                'lingkar_kepala' => 50,
                'user_id' => null,
                'created_at' => $now->copy()->subYears(1),
                'updated_at' => $now->copy()->subYears(1),
            ]
        ]);

        // ----------------------------------------------------
        // 11. SEED MATERI PEMBELAJARAN (APPROVED, PENDING, REJECTED)
        // ----------------------------------------------------

        // A. MATERI SUDAH DISETUJUI KEPSEK (Approved / Published)
        $materi1Id = DB::table('materis')->insertGetId([
            'school_id' => $school1Id,
            'judul' => 'Pengenalan Huruf Hijaiyah & Doa Harian',
            'deskripsi' => 'Materi pembelajaran mengenal huruf hijaiyah dasar alif ba ta dan doa sebelum makan.',
            'mata_pelajaran' => 'Agama & Moral',
            'kelas' => 'TK-A',
            'created_by' => $guruA1Id,
            'status' => 'Approved',
            'approved_by' => $kepsek1Id,
            'approved_at' => $now->copy()->subDays(3),
            'rejection_note' => null,
            'created_at' => $now->copy()->subDays(4),
            'updated_at' => $now->copy()->subDays(3),
        ]);

        $materi2Id = DB::table('materis')->insertGetId([
            'school_id' => $school1Id,
            'judul' => 'Mewarnai & Keterampilan Motorik Halus',
            'deskripsi' => 'Modul panduan menggunting, menempel bentuk geometri, dan memadukan warna indah.',
            'mata_pelajaran' => 'Seni & Motorik',
            'kelas' => 'TK-B',
            'created_by' => $guruB1Id,
            'status' => 'Approved',
            'approved_by' => $kepsek1Id,
            'approved_at' => $now->copy()->subDays(2),
            'rejection_note' => null,
            'created_at' => $now->copy()->subDays(3),
            'updated_at' => $now->copy()->subDays(2),
        ]);

        $materi3Id = DB::table('materis')->insertGetId([
            'school_id' => $school1Id,
            'judul' => 'Sains Cilik: Eksperimen Mencampur Warna Dasar',
            'deskripsi' => 'Kegiatan eksperimen seru mencampur warna merah, kuning, dan biru menggunakan air dan pewarna makanan.',
            'mata_pelajaran' => 'Kognitif & Sains',
            'kelas' => 'TK-A',
            'created_by' => $guruA1Id,
            'status' => 'Approved',
            'approved_by' => $kepsek1Id,
            'approved_at' => $now->copy()->subDays(1),
            'rejection_note' => null,
            'created_at' => $now->copy()->subDays(2),
            'updated_at' => $now->copy()->subDays(1),
        ]);

        $materiTeratai1Id = DB::table('materis')->insertGetId([
            'school_id' => $schoolTerataiId,
            'judul' => 'Mengenal Rukun Islam & Cerita Nabi Pilihan',
            'deskripsi' => 'Pembelajaran nilai agama melalui lagu-lagu islami ceria dan kisah tauladan Nabi Muhammad SAW.',
            'mata_pelajaran' => 'Agama & Moral',
            'kelas' => 'TK-A',
            'created_by' => $guruTerataiId,
            'status' => 'Approved',
            'approved_by' => $kepsekTerataiId,
            'approved_at' => $now->copy()->subDays(1),
            'rejection_note' => null,
            'created_at' => $now->copy()->subDays(2),
            'updated_at' => $now->copy()->subDays(1),
        ]);

        // B. MATERI BELUM DISETUJUI (Pending - Menunggu Approval Kepsek)
        $materiPending1Id = DB::table('materis')->insertGetId([
            'school_id' => $school1Id,
            'judul' => 'Pengenalan Angka 1-10 Lewat Lagu & Gerak Tubuh',
            'deskripsi' => 'Materi berhitung interaktif menggunakan flashcard bergambar buah-buahan dan lagu angka ceria.',
            'mata_pelajaran' => 'Kognitif & Berhitung',
            'kelas' => 'TK-A',
            'created_by' => $guruA1Id,
            'status' => 'Pending',
            'approved_by' => null,
            'approved_at' => null,
            'rejection_note' => null,
            'created_at' => $now->copy()->subHours(5),
            'updated_at' => $now->copy()->subHours(5),
        ]);

        $materiPending2Id = DB::table('materis')->insertGetId([
            'school_id' => $school1Id,
            'judul' => 'Kreativitas Origami: Melipat Kertas Bentuk Hewan Lucu',
            'deskripsi' => 'Panduan langkah demi langkah melipat kertas origami menjadi bentuk anjing, kucing, dan katak melompat.',
            'mata_pelajaran' => 'Seni Budaya & Prakarya',
            'kelas' => 'TK-B',
            'created_by' => $guruB1Id,
            'status' => 'Pending',
            'approved_by' => null,
            'approved_at' => null,
            'rejection_note' => null,
            'created_at' => $now->copy()->subHours(3),
            'updated_at' => $now->copy()->subHours(3),
        ]);

        $materiPendingTerataiId = DB::table('materis')->insertGetId([
            'school_id' => $schoolTerataiId,
            'judul' => 'Edukasi Kebersihan Diri & Mencuci Tangan 6 Langkah',
            'deskripsi' => 'Praktik mencuci tangan bersih dengan sabun air mengalir dan menjaga kebersihan kuku bagi anak usia dini.',
            'mata_pelajaran' => 'Fisik Motorik & Kesehatan',
            'kelas' => 'TK-A',
            'created_by' => $guruTerataiId,
            'status' => 'Pending',
            'approved_by' => null,
            'approved_at' => null,
            'rejection_note' => null,
            'created_at' => $now->copy()->subHours(2),
            'updated_at' => $now->copy()->subHours(2),
        ]);

        // C. MATERI DITOLAK / REVISI (Rejected - dengan catatan Kepsek)
        $materiRejectedId = DB::table('materis')->insertGetId([
            'school_id' => $school1Id,
            'judul' => 'Pengenalan Huruf Alfabet Lanjutan (M-Z)',
            'deskripsi' => 'Modul membaca cepat suku kata konsonan dan vokal lanjutan.',
            'mata_pelajaran' => 'Bahasa Indonesia',
            'kelas' => 'TK-A',
            'created_by' => $guruA1Id,
            'status' => 'Rejected',
            'approved_by' => $kepsek1Id,
            'approved_at' => $now->copy()->subDays(1),
            'rejection_note' => 'Mohon tambahkan media audio pelafalan huruf dan lembar kerja mewarnai yang lebih menarik untuk anak usia 4-5 tahun.',
            'created_at' => $now->copy()->subDays(2),
            'updated_at' => $now->copy()->subDays(1),
        ]);

        // Sinkronkan materi approved ke tabel materi_jurnals untuk modul Jurnal
        $approvedMateris = \App\Models\Materi::withoutGlobalScopes()->where('status', 'Approved')->get();
        foreach ($approvedMateris as $m) {
            \App\Models\MateriJurnal::syncFromLms($m);
        }

        // ----------------------------------------------------
        // 12. SEED JURNAL HARIAN GURU (YANG SUDAH TERISI LENGKAP)
        // ----------------------------------------------------
        DB::table('jurnal_harians')->insert([
            [
                'school_id' => $school1Id,
                'kelas_id' => $kelas1AId,
                'materi_id' => $materi1Id,
                'user_id' => $guruA1Id,
                'tanggal' => now()->subDays(3)->format('Y-m-d'),
                'tema' => 'Aku Hamba Allah',
                'kegiatan' => 'Mengenal anggota tubuh, melafalkan doa sebelum makan, dan belajar huruf hijaiyah alif ba ta bersama-sama.',
                'catatan' => 'Semua anak sangat antusias dan ceria. Hafalan surat Al-Fatihah dan doa sebelum makan berjalan lancar.',
                'created_at' => $now->copy()->subDays(3),
                'updated_at' => $now->copy()->subDays(3),
            ],
            [
                'school_id' => $school1Id,
                'kelas_id' => $kelas1BId,
                'materi_id' => $materi2Id,
                'user_id' => $guruB1Id,
                'tanggal' => now()->subDays(2)->format('Y-m-d'),
                'tema' => 'Lingkunganku & Sekolahku',
                'kegiatan' => 'Mewarnai gambar gedung sekolah dan membuat kerajinan bentuk rumah menggunakan kertas lipat warna-warni.',
                'catatan' => 'Kemampuan motorik halus anak-anak mengalami peningkatan pesat saat memegang gunting dan mengelem kertas.',
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ],
            [
                'school_id' => $school1Id,
                'kelas_id' => $kelas1AId,
                'materi_id' => $materi3Id,
                'user_id' => $guruA1Id,
                'tanggal' => now()->subDays(1)->format('Y-m-d'),
                'tema' => 'Eksplorasi Alam & Warna',
                'kegiatan' => 'Eksperimen mencampur cat warna primer merah dan kuning menjadi jingga, serta biru dan kuning menjadi hijau.',
                'catatan' => 'Anak-anak sangat takjub melihat perubahan warna yang terjadi. Setiap anak berhasil membuat kartu warna sendiri.',
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subDays(1),
            ],
            [
                'school_id' => $schoolTerataiId,
                'kelas_id' => $kelasTerataiAId,
                'materi_id' => $materiTeratai1Id,
                'user_id' => $guruTerataiId,
                'tanggal' => now()->subDays(1)->format('Y-m-d'),
                'tema' => 'Kisah Tauladan Sahabat Nabi',
                'kegiatan' => 'Mendengarkan dongeng panggung boneka kisah kebaikan hati sahabat nabi dan bernyanyi lagu rukun islam.',
                'catatan' => 'Siswa kelas TK-A Mawar Teratai sangat tertib dan menyimak dongeng dengan seksama.',
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subDays(1),
            ],
        ]);

        // ----------------------------------------------------
        // 13. SEED MEDIA KEGIATAN & DOKUMENTASI FOTO
        // ----------------------------------------------------
        if (DB::getSchemaBuilder()->hasTable('media_kegiatans')) {
            $media1Id = DB::table('media_kegiatans')->insertGetId([
                'school_id' => $school1Id,
                'user_id' => $guruA1Id,
                'judul' => 'Kegiatan Senam Pagi Ceria & Berkebun Bersama',
                'deskripsi' => 'Dokumentasi kegiatan senam pagi bersama di halaman sekolah dan belajar merawat tanaman bunga.',
                'file_path' => 'landing/hero.jpg',
                'file_type' => 'image',
                'tanggal_kegiatan' => now()->subDays(3)->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $media2Id = DB::table('media_kegiatans')->insertGetId([
                'school_id' => $school1Id,
                'user_id' => $guruB1Id,
                'judul' => 'Lomba Mewarnai & Keterampilan Seni Anak',
                'deskripsi' => 'Kegiatan unjuk bakat seni mewarnai pemandangan alam menyambut Hari Kemerdekaan.',
                'file_path' => 'landing/hero.jpg',
                'file_type' => 'image',
                'tanggal_kegiatan' => now()->subDays(1)->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $sampleSiswas = DB::table('siswas')->where('school_id', $school1Id)->where('status', 'aktif')->limit(10)->get();
            foreach ($sampleSiswas as $s) {
                DB::table('media_kegiatan_siswas')->insert([
                    'media_kegiatan_id' => $media1Id,
                    'siswa_id' => $s->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                DB::table('media_kegiatan_siswas')->insert([
                    'media_kegiatan_id' => $media2Id,
                    'siswa_id' => $s->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // ----------------------------------------------------
        // 14. SEED ANNOUNCEMENTS (PENGUMUMAN SEKOLAH)
        // ----------------------------------------------------
        DB::table('announcements')->insert([
            [
                'school_id' => $school1Id,
                'kelas_id' => null,
                'judul' => 'Undangan Pertemuan Wali Murid & Sosialisasi Kurikulum',
                'isi' => 'Diberitahukan kepada seluruh bapak/ibu wali murid TK Permata Al-Hikmah untuk menghadiri pertemuan silaturahmi dan sosialisasi program pembelajaran semester ganjil pada hari Sabtu pukul 08.30 WIB di Aula Utama.',
                'target' => 'wali_murid',
                'tanggal_kegiatan' => now()->addDays(5)->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'school_id' => $school1Id,
                'kelas_id' => null,
                'judul' => 'Pemberitahuan Pemeriksaan Kesehatan Gigi & Tumbuh Kembang',
                'isi' => 'Puskesmas setempat akan mengadakan posyandu sekolah dan pemeriksaan kesehatan gigi serta status gizi gratis untuk seluruh siswa.',
                'target' => 'semua',
                'tanggal_kegiatan' => now()->addDays(8)->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'school_id' => $schoolTerataiId,
                'kelas_id' => null,
                'judul' => 'Penyambutan Siswa Baru TK Teratai Al-Hikmah',
                'isi' => 'Selamat datang kepada seluruh peserta didik baru dan wali murid di cabang baru TK Teratai Al-Hikmah! Mari bersama wujudkan generasi yang berakhlak mulia dan berprestasi.',
                'target' => 'semua',
                'tanggal_kegiatan' => now()->addDays(3)->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'school_id' => $school3Id,
                'kelas_id' => null,
                'judul' => 'Kegiatan Outbound & Field Trip Edukasi Petik Buah',
                'isi' => 'Pengumuman kegiatan edukasi di luar kelas untuk siswa TK-A dan TK-B ke Kebun Edukasi Agro Wisata.',
                'target' => 'wali_murid',
                'tanggal_kegiatan' => now()->addDays(12)->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // ----------------------------------------------------
        // 15. SEED SYSTEM SETTINGS
        // ----------------------------------------------------
        $settings = [
            'site_title' => 'Yayasan Pendidikan Al-Hikmah 1',
            'site_subtitle' => 'Sistem Informasi ERP Manajemen Pendidikan TK & PAUD Modern',
            'welcome_text' => 'Selamat Datang di Portal Resmi Yayasan Al-Hikmah 1',
            'phone' => '0341-551122',
            'email' => 'info@alhikmah.sch.id',
            'hero_image' => '',
        ];

        // ----------------------------------------------------
        // 16. SEED RAPOR TEMPLATE (KURIKULUM MERDEKA PAUD/TK)
        // ----------------------------------------------------
        $tplId = DB::table('rapor_templates')->insertGetId([
            'nama_template' => 'Kurikulum Merdeka PAUD / TK (Standar Nasional)',
            'is_active' => true,
            'opsi_mulok' => json_encode(['BB', 'MB', 'BSH', 'BSB']),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // BAB I: Nilai Agama dan Budi Pekerti
        $bab1Id = DB::table('rapor_template_items')->insertGetId([
            'template_id' => $tplId,
            'parent_id' => null,
            'is_bab' => 1,
            'label' => 'I. Nilai Agama dan Budi Pekerti',
            'tipe' => null,
            'urutan' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('rapor_template_items')->insert([
            [
                'template_id' => $tplId,
                'parent_id' => $bab1Id,
                'is_bab' => 0,
                'label' => 'Capaian Pembelajaran Nilai Agama & Moral',
                'tipe' => 'sub_judul',
                'urutan' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_id' => $tplId,
                'parent_id' => $bab1Id,
                'is_bab' => 0,
                'label' => 'Praktik Ibadah, Hafalan Doa Harian & Pengenalan Huruf Hijaiyah',
                'tipe' => 'textarea',
                'urutan' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_id' => $tplId,
                'parent_id' => $bab1Id,
                'is_bab' => 0,
                'label' => 'Pembiasaan Akhlak Mulia, Menghargai Sesama & Perilaku Baik',
                'tipe' => 'textarea',
                'urutan' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // BAB II: Jati Diri & Sosial Emosional
        $bab2Id = DB::table('rapor_template_items')->insertGetId([
            'template_id' => $tplId,
            'parent_id' => null,
            'is_bab' => 1,
            'label' => 'II. Jati Diri, Sosial Emosional & Fisik Motorik',
            'tipe' => null,
            'urutan' => 5,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('rapor_template_items')->insert([
            [
                'template_id' => $tplId,
                'parent_id' => $bab2Id,
                'is_bab' => 0,
                'label' => 'Perkembangan Emosional & Kemandirian Anak',
                'tipe' => 'sub_judul',
                'urutan' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_id' => $tplId,
                'parent_id' => $bab2Id,
                'is_bab' => 0,
                'label' => 'Kemandirian, Tanggung Jawab & Interaksi Sosial dengan Teman Sebaya',
                'tipe' => 'textarea',
                'urutan' => 7,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_id' => $tplId,
                'parent_id' => $bab2Id,
                'is_bab' => 0,
                'label' => 'Keterampilan Fisik Motorik Kasar & Motorik Halus (Menggunting, Melipat, Menggambar)',
                'tipe' => 'textarea',
                'urutan' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // BAB III: Dasar Literasi, Sains, Matematika & Seni
        $bab3Id = DB::table('rapor_template_items')->insertGetId([
            'template_id' => $tplId,
            'parent_id' => null,
            'is_bab' => 1,
            'label' => 'III. Dasar-Dasar Literasi, Sains, Matematika & Rekayasa',
            'tipe' => null,
            'urutan' => 9,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('rapor_template_items')->insert([
            [
                'template_id' => $tplId,
                'parent_id' => $bab3Id,
                'is_bab' => 0,
                'label' => 'Eksplorasi Kognitif, Bahasa & Daya Cipta',
                'tipe' => 'sub_judul',
                'urutan' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_id' => $tplId,
                'parent_id' => $bab3Id,
                'is_bab' => 0,
                'label' => 'Kemampuan Bahasa Verbal, Minat Membaca & Pemahaman Cerita',
                'tipe' => 'textarea',
                'urutan' => 11,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_id' => $tplId,
                'parent_id' => $bab3Id,
                'is_bab' => 0,
                'label' => 'Eksplorasi Konsep Angka, Bentuk Geometri, Sains Sederhana & Karya Seni',
                'tipe' => 'textarea',
                'urutan' => 12,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // BAB IV: Muatan Lokal & Karakter
        $bab4Id = DB::table('rapor_template_items')->insertGetId([
            'template_id' => $tplId,
            'parent_id' => null,
            'is_bab' => 1,
            'label' => 'IV. Penilaian Muatan Lokal & Karakter Khas Sekolah',
            'tipe' => null,
            'urutan' => 13,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('rapor_template_items')->insert([
            [
                'template_id' => $tplId,
                'parent_id' => $bab4Id,
                'is_bab' => 0,
                'label' => 'Muatan Budaya & Bahasa Daerah',
                'tipe' => 'sub_judul',
                'urutan' => 14,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_id' => $tplId,
                'parent_id' => $bab4Id,
                'is_bab' => 0,
                'label' => 'Kemampuan Berkomunikasi Bahasa Daerah / Tata Krama Sederhana',
                'tipe' => 'radio',
                'urutan' => 15,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_id' => $tplId,
                'parent_id' => $bab4Id,
                'is_bab' => 0,
                'label' => 'Apresiasi Seni Tradisional, Tarian Daerah & Lagu Tradisional Anak',
                'tipe' => 'radio',
                'urutan' => 16,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_id' => $tplId,
                'parent_id' => $bab4Id,
                'is_bab' => 0,
                'label' => 'Pendidikan Karakter & Kedisiplinan',
                'tipe' => 'sub_judul',
                'urutan' => 17,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_id' => $tplId,
                'parent_id' => $bab4Id,
                'is_bab' => 0,
                'label' => 'Kedisiplinan Berwudhu & Praktik Sholat Berjamaah',
                'tipe' => 'radio',
                'urutan' => 18,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_id' => $tplId,
                'parent_id' => $bab4Id,
                'is_bab' => 0,
                'label' => 'Kerapian Diri, Kebersihan Perlengkapan Belajar & Makan Mandiri',
                'tipe' => 'radio',
                'urutan' => 19,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
