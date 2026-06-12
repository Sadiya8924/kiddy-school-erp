<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PerkembanganFisikSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = DB::table('siswas')->get(['id', 'school_id']);

        foreach ($siswas as $siswa) {
            for ($i = 5; $i >= 0; $i--) {
                $bulan = Carbon::now()->subMonths($i);
                DB::table('perkembangan_fisiks')->insert([
                    'school_id'     => $siswa->school_id,
                    'siswa_id'      => $siswa->id,
                    'tgl_periksa'   => $bulan->format('Y-m-d'),
                    'berat_badan'   => 15 + (5 - $i) + rand(0, 5) / 10,
                    'tinggi_badan'  => 100 + (5 - $i) + rand(0, 9) / 10,
                    'lingkar_kepala'=> 48 + (rand(0, 10) / 10),
                    'catatan'       => 'Pertumbuhan bulan ke-' . (6 - $i) . ' normal.',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }
}