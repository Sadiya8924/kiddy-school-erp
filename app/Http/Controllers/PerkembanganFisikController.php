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
    // 1. Menampilkan daftar siswa (DIFILTER SESUAI KELAS GURU & SEMESTER AKTIF)
    public function index()
    {
        $user = Auth::user();
        
        // --- LOGIKA SAKTI SWITCHER ---
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        
        $semesterAktif = Semester::where('is_active', true)->first();

        // Tarik data siswa dari sekolah yang sedang aktif
        $query = Siswa::where('school_id', $schoolId)->where('status', 'aktif');

        // Jika yang login adalah Guru dan dia punya kelas
        if ($user->role === 'guru' && $user->kelas_id) {
            $query->whereHas('kelas', function($q) use ($user, $semesterAktif) {
                $q->where('anggota_kelas.kelas_id', $user->kelas_id);
                if ($semesterAktif) {
                    $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                }
            });
        }

        $siswas = $query->get();
        return view('perkembangan_fisik.index', compact('siswas'));
    }

    // 2. Menampilkan grafik dan form input untuk 1 siswa
    public function show($id)
    {
        $user = Auth::user();
        
        // --- LOGIKA SAKTI SWITCHER ---
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