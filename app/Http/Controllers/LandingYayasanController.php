<?php

namespace App\Http\Controllers;

use App\Models\Yayasan;
use App\Models\School;
use App\Models\Siswa;
use Illuminate\Http\Request;

class LandingYayasanController extends Controller
{
    /**
     * Tampilkan landing page publik per yayasan.
     * Diakses via: /y/{kode_yayasan}
     */
    public function show(string $kode_yayasan)
    {
        $yayasan = Yayasan::where('kode_yayasan', $kode_yayasan)
            ->where('status', 'aktif')
            ->with('schools')
            ->firstOrFail();

        // Statistik ringkas per sekolah
        $schools = $yayasan->schools->where('status', 'aktif')->map(function ($school) {
            $school->jumlah_siswa = Siswa::where('school_id', $school->id)
                ->where('status', 'aktif')
                ->count();
            return $school;
        });

        $total_siswa   = $schools->sum('jumlah_siswa');
        $total_sekolah = $schools->count();

        return view('landing_yayasan', compact(
            'yayasan',
            'schools',
            'total_siswa',
            'total_sekolah'
        ));
    }
}
