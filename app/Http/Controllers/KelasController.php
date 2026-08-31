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
        $activeYayasanId = $user->getUserYayasanId() ?: session('active_yayasan_id');
        
        $semesterAktif = Semester::where('is_active', true)->first();

        // Tarik data kelas dan jumlah siswa (tambahkan relasi school)
        $query = Kelas::with('school')->withCount(['siswas' => function($q) use ($semesterAktif) {
            if ($semesterAktif) {
                $q->where('anggota_kelas.semester_id', $semesterAktif->id)
                  ->where('siswas.status', 'aktif');
            }
        }]);
        
        // JIKA BUKAN SUPER ADMIN MODE PUSAT, FILTER BERDASARKAN SCHOOL_ID
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        } elseif (!$user->isOwner() && $activeYayasanId) {
            $query->whereHas('school', function($q) use ($activeYayasanId) {
                $q->where('yayasan_id', $activeYayasanId);
            });
        }

        $list_kelas = $query->get();
        
        // Kirim data sekolah buat dropdown di Modal Tambah (khusus Super Admin)
        $schools = $user->isSuperAdmin() 
            ? \App\Models\School::where('status', 'aktif')
                ->when(!$user->isOwner() && $activeYayasanId, function($q) use ($activeYayasanId) {
                    $q->where('yayasan_id', $activeYayasanId);
                })
                ->get() 
            : [];
        $active_school_id = $schoolId;

        return view('kelas.index', compact('list_kelas', 'schools', 'active_school_id')); 
    }

    // 2. SIMPAN KELAS BARU
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            // Super admin wajib milih sekolah pas bikin kelas
            'school_id' => Auth::user()->isSuperAdmin() ? 'required|exists:schools,id' : 'nullable' 
        ]);

        $schoolId = Auth::user()->isSuperAdmin() ? $request->school_id : Auth::user()->school_id;

        Kelas::create([
            'school_id' => $schoolId,
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $request->tingkat,
        ]);

        return back()->with('success', 'Kelas baru berhasil dibuat!');
    }

    // 2.5 UPDATE DATA KELAS (NAMA & TINGKAT)
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        if (!Auth::user()->isSuperAdmin() && $kelas->school_id != Auth::user()->school_id) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah kelas ini.');
        }

        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
        ]);

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $request->tingkat,
        ]);

        return back()->with('success', 'Data kelas berhasil diperbarui!');
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
        
        // FIX: Beri akses VIP untuk Super Admin. Selain itu, cek school_id.
        if (!Auth::user()->isSuperAdmin() && $kelas->school_id != Auth::user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $semesterAktif = Semester::where('is_active', true)->first();

        $siswas = Siswa::with('kelasAktif')->whereHas('kelas', function($q) use ($id, $semesterAktif) {
            $q->where('anggota_kelas.kelas_id', $id);
            if ($semesterAktif) {
                $q->where('anggota_kelas.semester_id', $semesterAktif->id);
            }
        })->where('status', 'aktif')->get();

        // Tarik daftar kelas lain (di sekolah yang sama) untuk fitur Pindah Kelas Massal
        $list_kelas = Kelas::where('school_id', $kelas->school_id)->where('id', '!=', $id)->get();

        return view('kelas.show', compact('kelas', 'siswas', 'list_kelas'));
    }

    // FUNGSI BARU: KELUARKAN SISWA DARI KELAS
    public function removeStudent($kelas_id, $siswa_id)
    {
        $semesterAktif = Semester::where('is_active', true)->first();
        if ($semesterAktif) {
            // Hapus data dari tabel jembatan (anggota_kelas) saja, biodata siswa tetap aman
            AnggotaKelas::where('kelas_id', $kelas_id)
                        ->where('siswa_id', $siswa_id)
                        ->where('semester_id', $semesterAktif->id)
                        ->delete();
        }
        return back()->with('success', 'Siswa berhasil dikeluarkan dari kelas ini.');
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

// 7. SIMPAN ABSENSI SISWA (MULTI-STATUS)
    public function storeAbsensi(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $user = Auth::user();
        
        // Ambil school_id yang benar (Dukung Super Admin & Guru biasa)
        $school_id = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;

        // Loop semua input status yang dikirim dari form
        foreach ($request->all() as $key => $value) {
            // Cek apakah key inputannya berawalan 'status_'
            if (strpos($key, 'status_') === 0) {
                // Ekstrak ID siswa dari nama input (contoh: status_12 -> 12)
                $siswa_id = str_replace('status_', '', $key);
                
                // Simpan atau Update data absensi tanpa pakai semester_id
                \App\Models\Absensi::updateOrCreate(
                    [
                        'siswa_id' => $siswa_id,
                        'tanggal'  => $tanggal,
                    ],
                    [
                        'school_id' => $school_id,
                        'status'    => strtolower($value) // Pastikan huruf kecil (hadir, sakit, izin, alfa)
                    ]
                );
            }
        }
        
        return back()->with('success', 'Presensi tanggal ' . date('d/m/Y', strtotime($tanggal)) . ' berhasil disimpan!');
    }
}