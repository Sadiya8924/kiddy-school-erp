<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Semester;
use App\Models\PerkembanganFisik;
use Illuminate\Support\Facades\Auth;

class PerkembanganFisikController extends Controller
{
    // 1. Menampilkan daftar siswa (DENGAN FILTER KELAS & PENCARIAN)
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        $semesterAktif = \App\Models\Semester::where('is_active', true)->first();

        // Tarik data kelas untuk dropdown filter (khusus admin/kepsek)
        $list_kelas = \App\Models\Kelas::where('school_id', $schoolId)->get();

        // Tarik data siswa dari sekolah yang sedang aktif
        $query = \App\Models\Siswa::where('school_id', $schoolId)->where('status', 'aktif');

        // --- LOGIKA FILTER KELAS ---
        if ($user->role === 'guru' && $user->kelas_id) {
            // Guru cuma bisa lihat kelasnya sendiri
            $query->whereHas('kelas', function($q) use ($user, $semesterAktif) {
                $q->where('anggota_kelas.kelas_id', $user->kelas_id);
                if ($semesterAktif) {
                    $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                }
            });
        } else {
            // Admin / Kepsek bisa filter berdasarkan dropdown kelas
            if ($request->filled('kelas_id') && $request->kelas_id !== 'all') {
                $query->whereHas('kelas', function($q) use ($request, $semesterAktif) {
                    $q->where('anggota_kelas.kelas_id', $request->kelas_id);
                    if ($semesterAktif) {
                        $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                    }
                });
            }
        }

        // --- FILTER PENCARIAN NAMA (Berlaku untuk semua) ---
        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }

        $siswas = $query->get();
        return view('perkembangan_fisik.index', compact('siswas', 'list_kelas'));
    }

    // 2. Menampilkan grafik dan form input untuk 1 siswa
    public function show($id)
    {
        $user = Auth::user();
        
        // --- SWITCHER ---
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        
        // Proteksi tambahan: Pastikan siswa ini beneran ada di sekolah yang sedang dipantau
        $siswa = Siswa::where('id', $id)->where('school_id', $schoolId)->firstOrFail();
        
        $riwayat = PerkembanganFisik::where('siswa_id', $id)
                    ->orderBy('tgl_periksa', 'asc')
                    ->get();

        return view('perkembangan_fisik.show', compact('siswa', 'riwayat'));
    }

    // 3. Proses Simpan Data (POST)
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required',
            'tgl_periksa' => 'required|date',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
        ]);

        $siswa = Siswa::findOrFail($request->siswa_id);

        PerkembanganFisik::insert([
            'school_id' => $siswa->school_id, // Otomatis ngikutin ID sekolah siswanya, jadi 100% aman
            'siswa_id' => $request->siswa_id,
            'tgl_periksa' => $request->tgl_periksa,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'lingkar_kepala' => $request->lingkar_kepala,
            'catatan' => $request->catatan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data perkembangan berhasil ditambahkan!');
    }
}