<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurnalHarian;
use App\Models\MateriJurnal;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JurnalHarianController extends Controller
{
    // 1. HALAMAN UTAMA (LIST MATERI / CARD)
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        $pageType = $request->route() ? ($request->route()->getName() == 'lms.index' ? 'lms' : 'jurnal') : 'jurnal';
        
        // Cari kelas & materi berdasarkan Role
        if ($user->role === 'guru' && $user->kelas_id) {
            $list_kelas = Kelas::where('id', $user->kelas_id)->get();
            $kelompoks = $list_kelas->pluck('kelompok')->filter()->toArray();
            $materis = MateriJurnal::where('school_id', $schoolId)
                        ->whereIn('kelompok', $kelompoks)
                        ->latest('tanggal_dibuat')
                        ->get();
        } else {
            $list_kelas = Kelas::where('school_id', $schoolId)->get();
            $materis = MateriJurnal::where('school_id', $schoolId)
                        ->latest('tanggal_dibuat')
                        ->get();
        }

        return view('jurnal.index', compact('materis', 'list_kelas', 'pageType'));
    }

    // 2. ADMIN BIKIN MATERI BARU
    public function storeMateri(Request $request)
    {
        $request->validate([
            'kelompok'       => 'required|in:A,B,PAUD',
            'judul_materi'   => 'required|string|max:255',
            'tanggal_dibuat' => 'required|date',
        ]);

        $schoolId = Auth::user()->isSuperAdmin() ? session('active_school_id') : Auth::user()->school_id;

        MateriJurnal::create([
            'school_id'      => $schoolId,
            'kelompok'       => $request->kelompok,
            'judul_materi'   => $request->judul_materi,
            'deskripsi'      => $request->deskripsi,
            'tanggal_dibuat' => $request->tanggal_dibuat,
        ]);

        return back()->with('success', 'Materi Jurnal berhasil ditambahkan!');
    }

    public function destroyMateri($id)
    {
        MateriJurnal::findOrFail($id)->delete();
        return back()->with('success', 'Materi berhasil dihapus!');
    }

    // 3. HALAMAN ISI JURNAL PER SISWA DALAM 1 KELAS & MATERI
    public function showKelas($materi_id, $kelas_id)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        
        $materi = MateriJurnal::findOrFail($materi_id);
        $kelas = Kelas::findOrFail($kelas_id);
        $semesterAktif = Semester::where('is_active', true)->first();

        // Validasi keamanan: Guru cuma bisa buka kelasnya sendiri
        if ($user->role === 'guru' && $user->kelas_id != $kelas_id) {
            return redirect('/jurnal-harian')->with('error', 'Akses ditolak! Anda bukan guru di kelas ini.');
        }

        // Ambil data siswa di kelas tersebut
        $siswas = Siswa::where('school_id', $schoolId)
            ->whereHas('kelas', function($q) use ($kelas_id, $semesterAktif) {
                $q->where('anggota_kelas.kelas_id', $kelas_id);
                if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
            })->get();

        // Ambil jurnal yang sudah diisi untuk materi ini
        $jurnals = JurnalHarian::where('materi_id', $materi_id)
            ->where('kelas_id', $kelas_id)
            ->get()
            ->keyBy('siswa_id');

        return view('jurnal.show', compact('materi', 'kelas', 'siswas', 'jurnals'));
    }

    // 4. SIMPAN JURNAL (FOTO & KEGIATAN PER ANAK)
    public function storeJurnalSiswa(Request $request)
    {
        $request->validate([
            'materi_id' => 'required',
            'siswa_id'  => 'required',
            'kelas_id'  => 'required',
            'kegiatan'  => 'required|string',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:5120', 
            'video'     => 'nullable|mimes:mp4,mov,avi|max:20480', 
        ]);

        $materi = MateriJurnal::findOrFail($request->materi_id);
        $schoolId = Auth::user()->isSuperAdmin() ? session('active_school_id') : Auth::user()->school_id;
        
        $fotoPath = null;
        $videoPath = null;
        if ($request->hasFile('foto')) $fotoPath = $request->file('foto')->store('jurnal_media/foto', 'public');
        if ($request->hasFile('video')) $videoPath = $request->file('video')->store('jurnal_media/video', 'public');

        JurnalHarian::updateOrCreate(
            [
                'materi_id' => $request->materi_id,
                'siswa_id'  => $request->siswa_id,
            ],
            [
                'school_id' => $schoolId,
                'user_id'   => Auth::id(),
                'kelas_id'  => $request->kelas_id,
                'tema'      => $materi->judul_materi, 
                'tanggal'   => $materi->tanggal_dibuat,
                'kegiatan'  => $request->kegiatan,
                'catatan'   => $request->catatan,
                'foto'      => $fotoPath ?? DB::raw('foto'),
                'video'     => $videoPath ?? DB::raw('video'),
            ]
        );

        return back()->with('success', 'Jurnal siswa berhasil disimpan!');
    }

    public function destroyJurnalSiswa($id)
    {
        JurnalHarian::findOrFail($id)->delete();
        return back()->with('success', 'Jurnal siswa berhasil dihapus!');
    }

    // 5. DOWNLOAD ARSIP ZIP MEDIA
    public function downloadZip(Request $request)
    {
        $zip = new \ZipArchive;
        $fileName = 'Arsip_Media_Jurnal_' . date('Y-m-d_H-i') . '.zip';
        $zipPath = public_path($fileName);
        
        $schoolId = Auth::user()->isSuperAdmin() ? session('active_school_id') : Auth::user()->school_id;

        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            $jurnals = JurnalHarian::with('materi')->where('school_id', $schoolId)
                ->where(function($q) {
                    $q->whereNotNull('foto')->orWhereNotNull('video');
                })->get();

            $hasFiles = false;

            foreach ($jurnals as $j) {
                $folder = $j->siswa_id ? 'Jurnal_Siswa/' . ($j->siswa->nama_lengkap ?? 'Unknown') : 'Jurnal_Umum';
                $prefix = $j->tanggal . ' - ' . ($j->materi->judul_materi ?? 'Materi');

                if ($j->foto && file_exists(storage_path('app/public/' . $j->foto))) {
                    $zip->addFile(storage_path('app/public/' . $j->foto), $folder . '/' . $prefix . ' - Foto.jpg');
                    $hasFiles = true;
                }
                if ($j->video && file_exists(storage_path('app/public/' . $j->video))) {
                    $zip->addFile(storage_path('app/public/' . $j->video), $folder . '/' . $prefix . ' - Video.mp4');
                    $hasFiles = true;
                }
            }
            $zip->close();

            if ($hasFiles) {
                return response()->download($zipPath)->deleteFileAfterSend(true);
            } else {
                if (file_exists($zipPath)) unlink($zipPath);
                return back()->with('error', 'Belum ada media (foto/video) yang diupload untuk diarsipkan.');
            }
        }
        return back()->with('error', 'Gagal membuat file ZIP.');
    }

    // 6. AKSES ORANG TUA (WALI MURID)
    public function waliMuridMateri(Request $request)
    {
        $user = Auth::user();
        
        // Ambil semua anak
        $anak = \App\Models\Siswa::where('email', $user->email)->get();
        if ($anak->isEmpty()) {
            return redirect('/dashboard')->with('error', 'Belum ada data anak.');
        }

        $siswaIds = $anak->pluck('id')->toArray();
        
        // Ambil Jurnal Harian khusus untuk anak-anak ini
        $jurnals = JurnalHarian::with(['materi', 'siswa'])
                    ->whereIn('siswa_id', $siswaIds)
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return view('jurnal.ortu_jurnal', compact('jurnals', 'anak'));
    }

    public function markMateriRead(Request $request, $id)
    {
        // Fitur read receipt
        return back();
    }

    public function storeMateriComment(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string'
        ]);

        DB::table('jurnal_comments')->insert([
            'jurnal_id' => $id,
            'user_id' => Auth::id(),
            'komentar' => $request->komentar,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}