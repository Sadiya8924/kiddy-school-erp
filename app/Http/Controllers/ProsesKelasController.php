<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\AnggotaKelas;
use Illuminate\Support\Facades\Auth;

class ProsesKelasController extends Controller
{
    // MENAMPILKAN DAFTAR SISWA UNTUK NAIK KELAS
    public function index(Request $request)
    {
        $user = Auth::user();
        $kelas_id = $request->query('kelas_id');
        $list_kelas = Kelas::where('school_id', $user->school_id)->get();
        $semesterAktif = Semester::where('is_active', true)->first();
        
        // Ambil siswa AKTIF di kelas tersebut lewat tabel pivot (anggota_kelas)
        if ($kelas_id && $semesterAktif) {
            // TAMBAHAN TURBO: Gunakan with('kelasAktif') biar query lebih efisien
            $siswas = Siswa::with('kelasAktif')->whereHas('kelas', function($q) use ($kelas_id, $semesterAktif) {
                $q->where('anggota_kelas.kelas_id', $kelas_id)
                  ->where('anggota_kelas.semester_id', $semesterAktif->id);
            })->where('school_id', $user->school_id)->where('status', 'aktif')->get();
        } else {
            $siswas = collect();
        }

        return view('kenaikan_kelas', compact('list_kelas', 'siswas', 'kelas_id', 'semesterAktif'));
    }

    // MEMPROSES KENAIKAN KELAS / KELULUSAN
    public function proses(Request $request)
    {
        $keputusan = $request->input('keputusan'); 
        $tujuan_kelas = $request->input('tujuan_kelas'); 
        
        if (!$keputusan) {
            return back()->with('error', 'Tidak ada data siswa yang diproses.');
        }

        $semesterAktif = Semester::where('is_active', true)->first();
        if (!$semesterAktif) {
            return back()->with('error', 'Gagal! Tidak ada semester aktif. Harap atur semester terlebih dahulu.');
        }

        foreach ($keputusan as $siswa_id => $aksi) {
            $siswa = Siswa::find($siswa_id);
            if ($siswa) {
                if ($aksi == 'lulus') {
                    // Cukup ubah statusnya jadi lulus. Riwayat kelas lama biarkan aman di DB.
                    $siswa->update(['status' => 'lulus']);
                } elseif ($aksi == 'naik' || $aksi == 'tinggal') {
                    if (isset($tujuan_kelas[$siswa_id])) {
                        // Masukkan siswa ke kelas yang baru pada SEMESTER AKTIF
                        AnggotaKelas::updateOrCreate(
                            [
                                'siswa_id' => $siswa_id,
                                'semester_id' => $semesterAktif->id
                            ],
                            [
                                'kelas_id' => $tujuan_kelas[$siswa_id]
                            ]
                        );
                    }
                }
            }
        }

        return redirect('/kenaikan-kelas')->with('success', 'Proses kenaikan kelas / kelulusan berhasil dijalankan!');
    }
}