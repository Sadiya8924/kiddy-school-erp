<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = DB::table('siswas')->get(['id', 'school_id']);
        if ($siswas->isEmpty()) return;

        $tanggalMulai = Carbon::now()->subDays(5);
        $statusEnum   = ['Hadir', 'Hadir', 'Hadir', 'Sakit', 'Izin', 'Alfa'];
        $dataAbsen    = [];

        for ($i = 0; $i < 5; $i++) {
            $tgl = $tanggalMulai->copy()->addDays($i)->format('Y-m-d');
            foreach ($siswas as $siswa) {
                $status      = $statusEnum[array_rand($statusEnum)];
                $dataAbsen[] = [
                    'school_id'   => $siswa->school_id,
                    'siswa_id'    => $siswa->id,
                    'tanggal'     => $tgl,
                    'status'      => $status,
                    'keterangan'  => $status !== 'Hadir' ? 'Keterangan ' . $status : null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
        }

        DB::table('absensis')->insert($dataAbsen);
    }
}