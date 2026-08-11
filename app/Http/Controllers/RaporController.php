<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rapor;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Semester;
use App\Models\PerkembanganFisik;
use Illuminate\Support\Facades\Auth;

class RaporController extends Controller
{
    // 1. Fungsi khusus Admin untuk Buka/Tutup Akses Pengisian Rapor
    public function toggleAkses(Request $request)
    {
        $school = Auth::user()->school;
        
        if ($school) {
            $semesterAktif = Semester::where('is_active', true)->first();

            if ($school->is_rapor_open) {
                $school->is_rapor_open = false;
                
                if ($semesterAktif) {
                    Rapor::where('school_id', $school->id)
                         ->where('semester_id', $semesterAktif->id)
                         ->where('status', 'draft')
                         ->update(['status' => 'permanen']);
                }
            } else {
                $school->is_rapor_open = true;
            }
            
            $school->save();
            $status_teks = $school->is_rapor_open ? 'DIBUKA' : 'DITUTUP';
            return back()->with('success', "Akses pengisian rapor berhasil $status_teks.");
        }

        return back()->with('error', 'Sekolah tidak ditemukan.');
    }

    // 2. MENYIMPAN DATA RAPOR (DRAFT / PERMANEN)
    public function store(Request $request)
    {
        $user = Auth::user();
        $semesterAktif = Semester::where('is_active', true)->first();
        $templateAktif = \App\Models\RaporTemplate::where('is_active', true)->first();
        
        if (!$semesterAktif) {
            return back()->with('error', 'Tidak ada semester aktif!');
        }

        // Ambil semua data inputan dinamis dari form (Narasi & Mulok)
        $nilaiData = $request->input('nilai', []); 
        
        // Cek tombol mana yang diklik guru (menggunakan request->has karena pakai name action_type / simpan_permanen)
        $statusSimpan = $request->has('action_type') && $request->action_type == 'permanen' ? 'permanen' : 
                        ($request->has('simpan_permanen') ? 'permanen' : 'draft');

        // ================================================================
        // OTOMATIS HITUNG ABSENSI & AMBIL DATA FISIK DARI DATABASE
        // ================================================================
        $sakit = Absensi::where('siswa_id', $request->siswa_id)->where('status', 'sakit')->count();
        $izin = Absensi::where('siswa_id', $request->siswa_id)->where('status', 'izin')->count();
        $alfa = Absensi::where('siswa_id', $request->siswa_id)->where('status', 'alfa')->count();

        $fisik = PerkembanganFisik::where('siswa_id', $request->siswa_id)->latest('tgl_periksa')->first();
        $tb = $fisik ? $fisik->tinggi_badan : 0;
        $bb = $fisik ? $fisik->berat_badan : 0;

        // Update Student Biodata if provided
        if ($request->has('biodata')) {
            $siswa = Siswa::findOrFail($request->siswa_id);
            $siswa->update($request->input('biodata'));
        }

        // Ambil foto kegiatan yang dipilih (opsional)
        $fotoKegiatan = $request->input('foto_kegiatan', []);
        if (!is_array($fotoKegiatan)) {
            $fotoKegiatan = [];
        }

        // Upload custom foto kegiatan jika ada tambahan file baru
        if ($request->hasFile('foto_kegiatan_custom')) {
            foreach ($request->file('foto_kegiatan_custom') as $file) {
                if ($file->isValid()) {
                    $hashName = md5(uniqid(rand(), true) . microtime()) . '.' . strtolower($file->getClientOriginalExtension() ?: 'jpg');
                    $targetDir = storage_path('app/jurnal_media/foto');
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }
                    $file->move($targetDir, $hashName);
                    $fotoKegiatan[] = 'jurnal_media/foto/' . $hashName;
                }
            }
        }

        // Simpan / Update Database
        Rapor::updateOrCreate(
            ['siswa_id' => $request->siswa_id, 'semester_id' => $semesterAktif->id],
            [
                'school_id' => session('active_school_id') ?? $user->school_id,
                'status' => $statusSimpan,
                'template_id' => $templateAktif ? $templateAktif->id : null,
                'nilai_json' => $nilaiData, 
                'foto_kegiatan' => array_values(array_unique($fotoKegiatan)),
                
                // Masukkan data yang sudah dihitung otomatis ke database
                'sakit' => $sakit,
                'izin' => $izin,
                'alfa' => $alfa,
                'tinggi_badan' => $tb,
                'berat_badan' => $bb,
            ]
        );

        $pesanTeks = $statusSimpan == 'permanen' ? 'disimpan PERMANEN' : 'disimpan SEMENTARA (Draft)';
        return redirect('/rapor/' . $request->siswa_id)->with('success', "Data Rapor berhasil $pesanTeks!");
    }

    // 3. MENAMPILKAN DETAIL RAPOR (CETAK)
    public function show(int|string $id)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }
        $semesterAktif = Semester::where('is_active', true)->first();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        
        $siswa = Siswa::where('id', $id)
                    ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
                    ->with('kelas')
                    ->firstOrFail();

        $rapor = null;
        if ($semesterAktif) {
            $rapor = Rapor::where('siswa_id', $id)
                        ->where('semester_id', $semesterAktif->id)
                        ->first();
            
            if ($rapor) {
                $rapor->tahun_ajaran = $semesterAktif->tahun_ajaran;
                $rapor->semester = $semesterAktif->jenis;
                $rapor->alpa = $rapor->alfa;
            }
        }

        $mulokData = $rapor && $rapor->mulok ? json_decode($rapor->mulok, true) : [];

        return view('rapor_detail', compact('rapor', 'siswa', 'mulokData'));
    }
}