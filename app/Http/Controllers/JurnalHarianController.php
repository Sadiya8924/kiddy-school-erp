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
    public function lmsIndex(Request $request)
    {
        return $this->renderIndex('lms');
    }

    public function jurnalIndex(Request $request)
    {
        return $this->renderIndex('jurnal');
    }

    private function renderIndex($pageType)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        
        // Fetch semua kelas untuk dropdown form
        $list_kelas = Kelas::where('school_id', $schoolId)->get();

        // Cari materi berdasarkan Role
        if ($user->role === 'guru' && $user->kelas_id) {
            $my_kelas = $list_kelas->firstWhere('id', $user->kelas_id);
            $kelompoks = $my_kelas && $my_kelas->kelompok ? [$my_kelas->kelompok] : [];
            
            $query = MateriJurnal::where('school_id', $schoolId);
            
            if ($pageType === 'jurnal') {
                $query->whereIn('kelompok', $kelompoks)->where('status', 'Disetujui');
            } else {
                $query->where(function($q) use ($user, $kelompoks) {
                    $q->where('user_id', $user->id)
                      ->orWhere(function($q2) use ($kelompoks) {
                          $q2->whereIn('kelompok', $kelompoks)->where('status', 'Disetujui');
                      });
                });
            }
            $materis = $query->latest('tanggal_dibuat')->get();
        } else {
            $query = MateriJurnal::where('school_id', $schoolId);
            if ($pageType === 'jurnal') {
                $query->where('status', 'Disetujui');
            }
            $materis = $query->latest('tanggal_dibuat')->get();
        }

        return view('jurnal.index', compact('materis', 'list_kelas', 'pageType'));
    }

    // 2. GURU / ADMIN BIKIN MATERI BARU
    public function storeMateri(Request $request)
    {
        $request->validate([
            'kelas_ids'           => 'required|array|min:1',
            'judul_materi'        => 'required|string|max:255',
            'tujuan_pembelajaran' => 'required|string',
            'file_materi'         => 'nullable|file|mimes:pdf,jpg,jpeg,png,mp4|max:20480',
            'status_action'       => 'required|in:DRAFT,Menunggu ACC',
            'tanggal_tayang'      => 'nullable|date',
        ]);

        $schoolId = Auth::user()->isSuperAdmin() ? session('active_school_id') : Auth::user()->school_id;
        
        // Auto-approve if Kepsek/Admin, otherwise use the action from the button
        $status = in_array(Auth::user()->role, ['admin', 'kepsek', 'super_admin']) ? 'Disetujui' : $request->status_action;

        $filePath = null;
        if ($request->hasFile('file_materi')) {
            $filePath = $request->file('file_materi')->store('materi_media', 'public');
        }

        // Get default kelompok from first selected class to maintain backward compatibility
        $firstKelas = Kelas::find($request->kelas_ids[0]);

        $materi = MateriJurnal::create([
            'school_id'           => $schoolId,
            'user_id'             => Auth::id(),
            'kelompok'            => $firstKelas ? $firstKelas->kelompok : 'A',
            'judul_materi'        => $request->judul_materi,
            'deskripsi'           => $request->deskripsi,
            'tujuan_pembelajaran' => $request->tujuan_pembelajaran,
            'file_materi'         => $filePath,
            'tanggal_dibuat'      => now()->toDateString(),
            'tanggal_tayang'      => $request->tanggal_tayang,
            'status'              => $status,
        ]);

        $materi->kelas_targets()->sync($request->kelas_ids);

        $message = $status == 'DRAFT' ? 'Materi berhasil disimpan sebagai Draft.' : 'Materi berhasil ditambahkan dan Menunggu ACC Kepsek!';
        return back()->with('success', $message);
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

        $readReceipts = \DB::table('materi_read_receipts')
            ->join('users', 'materi_read_receipts.user_id', '=', 'users.id')
            ->where('materi_id', $materi_id)
            ->select('users.name', 'materi_read_receipts.read_at')
            ->get();

        $comments = \DB::table('materi_comments')
            ->join('users', 'materi_comments.user_id', '=', 'users.id')
            ->where('materi_id', $materi_id)
            ->select('materi_comments.*', 'users.name')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('jurnal.show', compact('materi', 'kelas', 'siswas', 'jurnals', 'readReceipts', 'comments'));
    }

    // 4. SIMPAN JURNAL (FOTO & KEGIATAN PER ANAK)
    public function storeJurnalSiswa(Request $request)
    {
        $request->validate([
            'materi_id' => 'required',
            'siswa_id'  => 'required',
            'kelas_id'  => 'required',
            'presensi'  => 'required|in:Hadir,Izin,Sakit,Alpa',
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

        // Allow Kepsek to update only catatan_kepsek without changing other fields
        $jurnalData = [
            'school_id' => $schoolId,
            'user_id'   => Auth::id(), // Still the one who filled it, or we shouldn't overwrite user_id if Kepsek edits.
            'kelas_id'  => $request->kelas_id,
            'tema'      => $materi->judul_materi, 
            'tanggal'   => $materi->tanggal_dibuat,
            'presensi'  => $request->presensi,
            'mood'      => $request->mood,
            'kegiatan'  => $request->kegiatan,
            'catatan'   => $request->catatan,
            'catatan_khusus' => $request->catatan_khusus,
        ];

        // Jika foto/video diupload, tambahkan ke data yang diupdate
        if ($fotoPath) $jurnalData['foto'] = $fotoPath;
        if ($videoPath) $jurnalData['video'] = $videoPath;

        // Jika Kepsek yang mengedit, tambahkan catatan_kepsek dan jangan ubah user_id pembuat jurnal
        if (Auth::user()->role === 'kepsek' && $request->has('catatan_kepsek')) {
            $jurnalData['catatan_kepsek'] = $request->catatan_kepsek;
            unset($jurnalData['user_id']); // Biarkan user_id tetap milik guru
        }

        JurnalHarian::updateOrCreate(
            [
                'materi_id' => $request->materi_id,
                'siswa_id'  => $request->siswa_id,
            ],
            $jurnalData
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

    // 6. ACC / TOLAK MATERI OLEH KEPSEK
    public function accMateri($id)
    {
        $materi = MateriJurnal::findOrFail($id);
        
        // Edge Case: Kepsek ACC materi yang sudah ditarik guru (menjadi DRAFT)
        if ($materi->status === 'DRAFT') {
            return back()->with('error', 'Tindakan diblokir: Materi ini sudah ditarik oleh guru.');
        }

        $materi->update(['status' => 'Disetujui', 'catatan_penolakan' => null]);
        
        // TODO: Trigger Notifikasi ke Guru dan Wali Murid
        
        return back()->with('success', "Materi '{$materi->judul_materi}' telah disetujui dan sekarang dapat diakses oleh wali murid kelompok {$materi->kelompok}.");
    }

    public function tolakMateri(Request $request, $id)
    {
        $request->validate(
            ['catatan_penolakan' => 'required|string|min:10'],
            ['catatan_penolakan.required' => 'Mohon isi catatan penolakan agar guru dapat melakukan revisi.', 'catatan_penolakan.min' => 'Catatan penolakan minimal 10 karakter.']
        );
        $materi = MateriJurnal::findOrFail($id);
        $materi->update(['status' => 'Ditolak', 'catatan_penolakan' => $request->catatan_penolakan]);
        return back()->with('success', 'Materi berhasil ditolak dengan memberikan catatan.');
    }

    // 7. HALAMAN WALI MURID
    public function waliMuridMateri()
    {
        $user = Auth::user();
        $siswa = \App\Models\Siswa::where('school_id', $user->school_id)->where('email', $user->email)->first();
        if (!$siswa) { $siswa = \App\Models\Siswa::where('school_id', $user->school_id)->first(); }
        if (!$siswa) return "Belum ada data siswa di sekolah ini.";

        $semesterAktif = Semester::where('is_active', true)->first();
        $kelasSiswa = null;
        if ($semesterAktif) {
            $anggota = \DB::table('anggota_kelas')->where('siswa_id', $siswa->id)->where('semester_id', $semesterAktif->id)->first();
            if ($anggota) $kelasSiswa = Kelas::find($anggota->kelas_id);
        }
        
        if (!$kelasSiswa) return "Siswa belum memiliki kelas aktif.";

        $materis = MateriJurnal::where('school_id', $user->school_id)
            ->where('kelompok', $kelasSiswa->kelompok)
            ->where('status', 'Disetujui')
            ->latest('tanggal_dibuat')
            ->get();

        $jurnals = JurnalHarian::where('siswa_id', $siswa->id)
            ->whereIn('materi_id', $materis->pluck('id'))
            ->get()->keyBy('materi_id');

        $readReceipts = \DB::table('materi_read_receipts')
            ->where('user_id', $user->id)
            ->pluck('materi_id')->toArray();
            
        $comments = \DB::table('materi_comments')
            ->join('users', 'materi_comments.user_id', '=', 'users.id')
            ->select('materi_comments.*', 'users.name')
            ->orderBy('created_at', 'asc')
            ->get()->groupBy('materi_id');

        return view('walimurid.materi', compact('siswa', 'kelasSiswa', 'materis', 'jurnals', 'readReceipts', 'comments'));
    }

    // 8. INTERAKSI WALI MURID
    public function markMateriRead($id)
    {
        $materi = MateriJurnal::findOrFail($id);
        
        \DB::table('materi_read_receipts')->updateOrInsert(
            ['materi_id' => $materi->id, 'user_id' => Auth::id()],
            ['read_at' => now(), 'created_at' => now(), 'updated_at' => now()]
        );

        return back()->with('success', 'Terima kasih, materi telah ditandai sudah dibaca.');
    }

    public function storeMateriComment(Request $request, $id)
    {
        $request->validate(['komentar' => 'required|string|max:1000']);
        
        $materi = MateriJurnal::findOrFail($id);
        
        \DB::table('materi_comments')->insert([
            'materi_id' => $materi->id,
            'user_id' => Auth::id(),
            'komentar' => $request->komentar,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}