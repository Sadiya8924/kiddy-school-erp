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
        // MAGIC: OTOMATIS HITUNG ABSENSI & AMBIL DATA FISIK DARI DATABASE
        // ================================================================
        $sakit = Absensi::where('siswa_id', $request->siswa_id)->where('status', 'sakit')->count();
        $izin = Absensi::where('siswa_id', $request->siswa_id)->where('status', 'izin')->count();
        $alfa = Absensi::where('siswa_id', $request->siswa_id)->where('status', 'alfa')->count();

        $fisik = PerkembanganFisik::where('siswa_id', $request->siswa_id)->latest('tgl_periksa')->first();
        $tb = $fisik ? $fisik->tinggi_badan : 0;
        $bb = $fisik ? $fisik->berat_badan : 0;

        // Simpan / Update Database
        Rapor::updateOrCreate(
            ['siswa_id' => $request->siswa_id, 'semester_id' => $semesterAktif->id],
            [
                'school_id' => $user->school_id,
                'status' => $statusSimpan,
                'template_id' => $templateAktif ? $templateAktif->id : null,
                'nilai_json' => $nilaiData, 
                
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
    public function show($id)
    {
        $user = auth()->user();
        $semesterAktif = Semester::where('is_active', true)->first();
        
        $siswa = Siswa::where('id', $id)
                    ->where('school_id', $user->school_id)
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