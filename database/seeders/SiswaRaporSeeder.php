<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class SiswaRaporSeeder extends Seeder
{
    public function run()
    {
        // Panggil Faker dengan settingan bahasa Indonesia
        $faker = Faker::create('id_ID');

        // Kita bikin 10 data anak TK sekaligus!
        for($i = 1; $i <= 10; $i++){
            
            // 1. Masukin Data Siswa dan simpan ID-nya
            $siswaId = DB::table('siswas')->insertGetId([
                'nama_lengkap' => $faker->firstName() . ' ' . $faker->lastName(),
                'nik' => $faker->nik(),
                'tempat_lahir' => $faker->city(),
                'tgl_lahir' => $faker->dateTimeBetween('-6 years', '-5 years')->format('Y-m-d'),
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'nama_ayah' => $faker->name('male'),
                'nama_ibu' => $faker->name('female'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // 2. Langsung buatin data Rapornya otomatis nyambung ke Siswa tadi!
            DB::table('rapors')->insert([
                'siswa_id' => $siswaId,
                'semester' => '2 (Genap)',
                'tahun_ajaran' => '2025/2026',
                'sakit' => rand(0, 3), // Acak sakit 0 sampai 3 hari
                'izin' => rand(0, 2),
                'alfa' => 0,
                'tinggi_badan' => $faker->randomFloat(1, 95, 110), // Acak tinggi badan anak TK
                'berat_badan' => $faker->randomFloat(1, 14, 20),
                'nilai_agama_dan_budi_pekerti' => 'Ananda sudah mampu melafalkan doa sehari-hari dengan sangat baik dan tertib.',
                'jati_diri' => 'Ananda sangat mandiri, suka membantu teman, dan percaya diri saat tampil di depan kelas.',
                'literasi_sains_seni' => 'Ananda sudah mulai mengenali huruf abjad dan angka 1-10 dengan baik.',
                'kokurikuler' => 'Ananda aktif dan bersemangat mengikuti kegiatan senam ceria setiap hari Jumat.',
                'refleksi_ortu' => 'Alhamdulillah, perkembangan kemandirian ananda sangat pesat di rumah.',
                'info_perkembangan' => 'Tumbuh kembang ananda sangat baik dan sesuai dengan tahapan usianya.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}