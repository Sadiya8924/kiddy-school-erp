<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            ['name' => 'Data Siswa', 'route_name' => 'data-siswa', 'icon' => 'solar:users-group-rounded-bold-duotone', 'category' => 'MANAJEMEN', 'role_access' => 'admin,guru'],
            ['name' => 'Manajemen Kelas', 'route_name' => 'kelas.index', 'icon' => 'solar:globus-bold-duotone', 'category' => 'MANAJEMEN', 'role_access' => 'admin,guru'],
            ['name' => 'Perkembangan Fisik', 'route_name' => 'perkembangan.index', 'icon' => 'solar:graph-up-bold-duotone', 'category' => 'MANAJEMEN', 'role_access' => 'guru'],
            
            ['name' => 'Absensi Harian', 'route_name' => 'absensi.harian', 'icon' => 'solar:calendar-mark-bold-duotone', 'category' => 'PRESENSI', 'role_access' => 'guru'],
            ['name' => 'Rekap Absensi', 'route_name' => 'absensi.rekap', 'icon' => 'solar:clipboard-list-bold-duotone', 'category' => 'PRESENSI', 'role_access' => 'guru'],

            ['name' => 'Isi Rapor', 'route_name' => 'rapor.isi', 'icon' => 'solar:file-text-bold-duotone', 'category' => 'RAPOR', 'role_access' => 'admin,guru'],
            ['name' => 'Cetak Rapor', 'route_name' => 'rapor.cetak', 'icon' => 'solar:printer-bold-duotone', 'category' => 'RAPOR', 'role_access' => 'admin,guru'],
            ['name' => 'Pengaturan Sekolah', 'route_name' => 'pengaturan', 'icon' => 'solar:settings-bold-duotone', 'category' => 'SISTEM', 'role_access' => 'admin'],
            ['name' => 'Kenaikan Kelas', 'route_name' => 'kenaikan.index', 'icon' => 'solar:double-alt-arrow-up-bold-duotone', 'category' => 'SISTEM', 'role_access' => 'admin'],

            ['name' => 'Pengumuman', 'route_name' => 'pengumuman.index', 'icon' => 'solar:bell-bing-bold-duotone', 'category' => 'BERANDA', 'role_access' => 'admin,guru'],
        ];

        foreach ($modules as $moduleData) {
            $module = Module::updateOrCreate(
                ['route_name' => $moduleData['route_name']],
                $moduleData
            );

            School::query()->each(function (School $school) use ($module) {
                $school->modules()->syncWithoutDetaching([
                    $module->id => ['is_active_for_guru' => true],
                ]);
            });
        }
    }
}
