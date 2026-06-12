<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil school pertama yang ada
        $schoolId = DB::table('schools')->value('id');

        DB::table('siswas')->insert([
            [
                'school_id'     => $schoolId,
                'nama_lengkap'  => 'Rania Putri',
                'nik'           => '3514012305200001',
                'nama_ortu'     => 'Bapak/Ibu Santoso',
                'tempat_lahir'  => 'Surabaya',
                'tgl_lahir'     => '2020-05-10',
                'jenis_kelamin' => 'Perempuan',
                'nama_ibu'      => 'Ibu Sari',
                'nama_ayah'     => 'Bapak Santoso',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'school_id'     => $schoolId,
                'nama_lengkap'  => 'Budi Santoso',
                'nik'           => '3514012305200002',
                'nama_ortu'     => 'Bapak/Ibu Harto',
                'tempat_lahir'  => 'Malang',
                'tgl_lahir'     => '2019-08-15',
                'jenis_kelamin' => 'Laki-laki',
                'nama_ibu'      => 'Ibu Dewi',
                'nama_ayah'     => 'Bapak Harto',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}