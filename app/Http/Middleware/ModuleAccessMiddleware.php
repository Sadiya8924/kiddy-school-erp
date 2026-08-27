<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ModuleAccessMiddleware
{
    /**
     * Map URI patterns / route names to array of possible module names in the database.
     */
    protected array $routeToModuleNames = [
        'kelas' => ['Manajemen Kelas'],
        'data-siswa' => ['Data Siswa'],
        'siswa' => ['Data Siswa'],
        'materi/guru' => ['Manajemen Materi'],
        'materi/persetujuan' => ['Persetujuan Materi'],
        'materi' => ['Manajemen Materi', 'Materi Pelajaran'],
        'lms.index' => ['Manajemen Materi'],
        'jurnal-harian' => ['Jurnal Harian', 'Jurnal Harian Anak'],
        'jurnal-anak' => ['Jurnal Harian', 'Jurnal Harian Anak'],
        'absensi-harian' => ['Absensi Harian'],
        'rekap-absensi' => ['Rekap Absensi'],
        'perkembangan-fisik' => ['Perkembangan Fisik', 'Laporan Fisik'],
        'perkembangan-physic' => ['Perkembangan Fisik', 'Laporan Fisik'],
        'perkembangan-anak' => ['Laporan Fisik', 'Perkembangan Fisik'],
        'isi-rapor' => ['Isi Rapor'],
        'cetak-rapor' => ['Cetak Rapor'],
        'rapor-anak' => ['Rapor Anak'],
        'arsip-rapor' => ['Arsip Rapor'],
        'pengumuman' => ['Pengumuman'],
        'admin/template' => ['Template Rapor'],
        'kenaikan-kelas' => ['Kenaikan Kelas'],
        'pengaturan' => ['Pengaturan Sekolah'],
    ];

    public function handle(Request $request, Closure $next, ?string $moduleRoute = null): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Super Admin & Owner bypass semua pengecekan modul
        if ($user->isSuperAdmin() || $user->isOwner()) {
            return $next($request);
        }

        $role = $user->role;
        $roleColumn = 'is_active_for_' . $role;

        // Tentukan school_id
        $schoolId = $user->school_id;
        if (!$schoolId && $role === 'wali_murid') {
            $anakSiswa = \App\Models\Siswa::where('email', $user->email)->orWhere('user_id', $user->id)->first();
            if ($anakSiswa) {
                $schoolId = $anakSiswa->school_id;
            }
        }
        if (!$schoolId) {
            $schoolId = session('active_school_id');
        }
        if (!$schoolId && session('active_yayasan_id')) {
            $sch = \App\Models\School::where('yayasan_id', session('active_yayasan_id'))->first();
            if ($sch) $schoolId = $sch->id;
        }

        if (!$schoolId) {
            return $next($request);
        }

        // Cari nama modul dari parameter middleware atau dari URL path
        $targetModuleNames = null;
        if ($moduleRoute) {
            $targetModuleNames = [$moduleRoute];
        } else {
            $path = trim($request->path(), '/');
            // Bersihkan prefix /y/{kode_yayasan} jika ada
            if (preg_match('#^y/[^/]+/(.+)#', $path, $m)) {
                $path = $m[1];
            }
            foreach ($this->routeToModuleNames as $pattern => $moduleNames) {
                if (str_starts_with($path, $pattern)) {
                    $targetModuleNames = $moduleNames;
                    break;
                }
            }
        }

        if ($targetModuleNames) {
            // Cek apakah ada modul sejenis yang diaktifkan untuk role ini
            $moduleRecord = DB::table('school_modules')
                ->join('modules', 'school_modules.module_id', '=', 'modules.id')
                ->where('school_modules.school_id', $schoolId)
                ->where(function($q) use ($targetModuleNames) {
                    foreach ($targetModuleNames as $modName) {
                        $q->orWhere('modules.name', 'LIKE', '%' . $modName . '%')
                          ->orWhere('modules.route_name', 'LIKE', '%' . $modName . '%');
                    }
                })
                ->where('school_modules.' . $roleColumn, 1)
                ->first();

            // Jika tidak ada modul sejenis yang aktif untuk role ini, periksa apakah modul tersebut memang terdaftar di sekolah
            if (!$moduleRecord) {
                $anyModuleRegistered = DB::table('school_modules')
                    ->join('modules', 'school_modules.module_id', '=', 'modules.id')
                    ->where('school_modules.school_id', $schoolId)
                    ->where(function($q) use ($targetModuleNames) {
                        foreach ($targetModuleNames as $modName) {
                            $q->orWhere('modules.name', 'LIKE', '%' . $modName . '%')
                              ->orWhere('modules.route_name', 'LIKE', '%' . $modName . '%');
                        }
                    })
                    ->first();

                if ($anyModuleRegistered) {
                    abort(403, 'Akses fitur ini dinonaktifkan oleh Admin sekolah.');
                }
            }
        }

        return $next($request);
    }
}