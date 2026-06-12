<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Semester;
use App\Models\AnggotaKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    // 1. TAMPILKAN DAFTAR KELAS & JUMLAH SISWA
    public function index()
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        
        $semesterAktif = Semester::where('is_active', true)->first();

        $list_kelas = Kelas::where('school_id', $schoolId)->withCount(['siswas' => function($q) use ($semesterAktif) {
            if ($semesterAktif) {
                $q->where('anggota_kelas.semester_id', $semesterAktif->id)
                  ->where('siswas.status', 'aktif');
            }
        }])->get();
        
        return view('kelas.index', compact('list_kelas')); 
    }

    // 2. SIMPAN KELAS BARU
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255'
        ]);

        Kelas::create([
            'school_id' => Auth::user()->school_id,
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $request->tingkat,
        ]);

        return back()->with('success', 'Kelas baru berhasil dibuat!');
    }

    // 3. PINDAHKAN SEMUA SISWA (MASSAL) KE KELAS LAIN
    public function moveStudents(Request $request, $id)
    {
        $request->validate(['target_kelas_id' => 'required|exists:kelas,id']);
        if ($id == $request->target_kelas_id) {
            return back()->with('error', 'Kelas tujuan tidak boleh sama.');
        }

        $semesterAktif = Semester::where('is_active', true)->first();
        if (!$semesterAktif) {
            return back()->with('error', 'Gagal! Tidak ada semester yang aktif saat ini.');
        }

        // UPDATE TABEL JEMBATAN: Ubah kelas siswa yang ada di kelas ini pada semester ini
        AnggotaKelas::where('kelas_id', $id)
            ->where('semester_id', $semesterAktif->id)
            ->update(['kelas_id' => $request->target_kelas_id]);

        return back()->with('success', 'Semua siswa berhasil dipindahkan untuk semester ini.');
    }

    // 4. HAPUS KELAS
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        
        // Cek apakah ada siswa di kelas ini pada semester aktif
        $semesterAktif = Semester::where('is_active', true)->first();
        $adaSiswa = AnggotaKelas::where('kelas_id', $id)
                                ->when($semesterAktif, function($q) use ($semesterAktif) {
                                    $q->where('semester_id', $semesterAktif->id);
                                })->exists();

        if ($adaSiswa) {
            return back()->with('error', 'Kelas tidak bisa dihapus karena masih ada siswa di dalamnya pada semester ini.');
        }

        $kelas->delete();
        return back()->with('success', 'Kelas berhasil dihapus.');
    }

    // 5. TAMPILKAN DETAIL KELAS (DAFTAR SISWA DI DALAMNYA)
    public function show($id)
    {
        $kelas = Kelas::findOrFail($id);
        if ($kelas->school_id != Auth::user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $semesterAktif = Semester::where('is_active', true)->first();

        // TAMBAHAN TURBO: Gunakan with('kelasAktif')
        $siswas = Siswa::with('kelasAktif')->whereHas('kelas', function($q) use ($id, $semesterAktif) {
            $q->where('anggota_kelas.kelas_id', $id);
            if ($semesterAktif) {
                $q->where('anggota_kelas.semester_id', $semesterAktif->id);
            }
        })->where('status', 'aktif')->get();

        return view('kelas.show', compact('kelas', 'siswas'));
    }

    // 6. UPDATE AKSES MODUL (MULTI-ROLE)
    public function updateMenuAccess(Request $request)
    {
        $user = Auth::user();
        
        // Cek sekolah mana yang lagi diedit (Dukung fitur Switcher Super Admin)
        $school_id = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        
        if (!$school_id) {
            return back()->with('error', 'Gagal! Sekolah belum dipilih.');
        }

        $role = $request->input('target_role'); // admin, kepsek, guru, wali_murid
        $column = 'is_active_for_' . $role;

        // Validasi Keamanan: Admin biasa HANYA boleh ngedit punya Guru
        if (!$user->isSuperAdmin() && $role !== 'guru') {
            return back()->with('error', 'Akses ditolak! Anda hanya berhak mengubah menu Guru.');
        }

        $module_ids = $request->input($column, []);

        // 1. Reset semua jadi 0 (Off) untuk role tersebut di sekolah ini
        \Illuminate\Support\Facades\DB::table('school_modules')
            ->where('school_id', $school_id)
            ->update([$column => 0]);

        // 2. Set jadi 1 (On) untuk menu yang dicentang
        if (!empty($module_ids)) {
            \Illuminate\Support\Facades\DB::table('school_modules')
                ->where('school_id', $school_id)
                ->whereIn('module_id', array_keys($module_ids))
                ->update([$column => 1]);
        }

        $nama_role = str_replace('_', ' ', strtoupper($role));
        return back()->with('success', "Hak akses menu untuk $nama_role berhasil diperbarui!");
    }
}