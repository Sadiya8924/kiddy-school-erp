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
    // 1. HALAMAN UTAMA (LIST MATERI / CARD WITH FILTER)
    public function index(Request $request)
    {
        MateriJurnal::syncAll();

        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        $pageType = $request->route() ? ($request->route()->getName() == 'lms.index' ? 'lms' : 'jurnal') : 'jurnal';
        
        $query = MateriJurnal::where('school_id', $schoolId);

        // Cari kelas berdasarkan Role
        if ($user->role === 'guru' && $user->kelas_id) {
            $list_kelas = Kelas::where('id', $user->kelas_id)->get();
            $kelompoks = $list_kelas->pluck('kelompok')->filter()->toArray();
            $query->whereIn('kelompok', $kelompoks);
        } else {
            $list_kelas = Kelas::where('school_id', $schoolId)->get();
        }

        // FILTER TANGGAL
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_dibuat', $request->tanggal);
        }

        // FILTER KELOMPOK
        if ($request->filled('kelompok') && $request->kelompok != 'semua') {
            $query->where('kelompok', $request->kelompok);
        }

        // FILTER SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_materi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $materis = $query->latest('tanggal_dibuat')->latest('id')->get();

        // Count total filled journals per materi
        foreach ($materis as $m) {
            $m->total_jurnal = JurnalHarian::where('materi_id', $m->id)->count();
        }

        // Stats summary
        $stats = [
            'total' => MateriJurnal::where('school_id', $schoolId)->count(),
            'today' => MateriJurnal::where('school_id', $schoolId)->whereDate('tanggal_dibuat', date('Y-m-d'))->count(),
            'kelompok_a' => MateriJurnal::where('school_id', $schoolId)->where('kelompok', 'A')->count(),
            'kelompok_b' => MateriJurnal::where('school_id', $schoolId)->where('kelompok', 'B')->count(),
        ];

        return view('jurnal.index', compact('materis', 'list_kelas', 'pageType', 'stats'));
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

        $mj = MateriJurnal::create([
            'school_id'      => $schoolId,
            'kelompok'       => $request->kelompok,
            'judul_materi'   => $request->judul_materi,
            'deskripsi'      => $request->deskripsi,
            'tanggal_dibuat' => $request->tanggal_dibuat,
        ]);

        \App\Models\Materi::withoutGlobalScopes()->updateOrCreate(
            ['id' => $mj->id],
            [
                'school_id'      => $schoolId,
                'judul'          => $request->judul_materi,
                'deskripsi'      => $request->deskripsi,
                'mata_pelajaran' => 'Kegiatan Jurnal',
                'kelas'          => "Kelompok {$request->kelompok}",
                'created_by'     => Auth::id(),
                'status'         => 'Published',
            ]
        );

        return back()->with('success', 'Materi Jurnal berhasil ditambahkan!');
    }

    public function destroyMateri($id)
    {
        MateriJurnal::where('id', $id)->delete();
        \App\Models\Materi::withoutGlobalScopes()->where('id', $id)->delete();
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
        if ($request->hasFile('foto')) {
            $fotoPath = $this->processAndCompressFoto($request->file('foto'));
        }
        if ($request->hasFile('video')) {
            $videoPath = $this->processVideo($request->file('video'));
        }

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
            $jurnals = JurnalHarian::with('materi', 'siswa')->where('school_id', $schoolId)
                ->where(function($q) {
                    $q->whereNotNull('foto')->orWhereNotNull('video');
                })->get();

            $hasFiles = false;

            foreach ($jurnals as $j) {
                $folder = $j->siswa_id ? 'Jurnal_Siswa/' . ($j->siswa->nama_lengkap ?? 'Unknown') : 'Jurnal_Umum';
                $prefix = $j->tanggal . ' - ' . ($j->materi->judul_materi ?? 'Materi');

                if ($j->foto) {
                    $fotoFile = storage_path('app/' . $j->foto);
                    if (!file_exists($fotoFile)) {
                        $fotoFile = storage_path('app/public/' . $j->foto);
                    }
                    if (file_exists($fotoFile)) {
                        $ext = pathinfo($fotoFile, PATHINFO_EXTENSION) ?: 'jpg';
                        $zip->addFile($fotoFile, $folder . '/' . $prefix . ' - Foto.' . $ext);
                        $hasFiles = true;
                    }
                }
                if ($j->video) {
                    $videoFile = storage_path('app/' . $j->video);
                    if (!file_exists($videoFile)) {
                        $videoFile = storage_path('app/public/' . $j->video);
                    }
                    if (file_exists($videoFile)) {
                        $ext = pathinfo($videoFile, PATHINFO_EXTENSION) ?: 'mp4';
                        $zip->addFile($videoFile, $folder . '/' . $prefix . ' - Video.' . $ext);
                        $hasFiles = true;
                    }
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

    // SECURE SERVE MEDIA FROM PRIVATE STORAGE
    public function serveMedia($type, $filename)
    {
        $filename = basename($filename);
        $type = in_array($type, ['foto', 'video']) ? $type : 'foto';

        // Check private app storage first, then fallback to public disk for legacy files
        $path = storage_path('app/jurnal_media/' . $type . '/' . $filename);
        if (!file_exists($path)) {
            $path = storage_path('app/public/jurnal_media/' . $type . '/' . $filename);
        }

        if (!file_exists($path)) {
            abort(404);
        }

        $mimeType = @mime_content_type($path) ?: ($type === 'video' ? 'video/mp4' : 'image/jpeg');

        return response()->file($path, [
            'Content-Type'  => $mimeType,
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }

    // HELPER: PROCESS & COMPRESS FOTO WITH HASHED FILENAME
    private function processAndCompressFoto($file)
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            $extension = 'jpg';
        }

        $hashName = md5(uniqid(rand(), true) . microtime()) . '.' . $extension;
        $targetDir = storage_path('app/jurnal_media/foto');

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $destination = $targetDir . '/' . $hashName;
        $sourcePath = $file->getRealPath();

        if (extension_loaded('gd') && function_exists('imagecreatefromstring')) {
            $imageInfo = @getimagesize($sourcePath);
            if ($imageInfo) {
                $mime = $imageInfo['mime'];
                $image = null;
                switch ($mime) {
                    case 'image/jpeg':
                    case 'image/jpg':
                        $image = @imagecreatefromjpeg($sourcePath);
                        break;
                    case 'image/png':
                        $image = @imagecreatefrompng($sourcePath);
                        break;
                    case 'image/webp':
                        $image = @imagecreatefromwebp($sourcePath);
                        break;
                    default:
                        $image = @imagecreatefromstring(file_get_contents($sourcePath));
                        break;
                }

                if ($image) {
                    if (function_exists('exif_read_data') && in_array($mime, ['image/jpeg', 'image/jpg'])) {
                        $exif = @exif_read_data($sourcePath);
                        if (!empty($exif['Orientation'])) {
                            switch ($exif['Orientation']) {
                                case 8: $image = imagerotate($image, 90, 0); break;
                                case 3: $image = imagerotate($image, 180, 0); break;
                                case 6: $image = imagerotate($image, -90, 0); break;
                            }
                        }
                    }

                    $origWidth = imagesx($image);
                    $origHeight = imagesy($image);
                    $maxWidth = 1200;

                    if ($origWidth > $maxWidth) {
                        $newWidth = $maxWidth;
                        $newHeight = (int) ($origHeight * ($maxWidth / $origWidth));
                    } else {
                        $newWidth = $origWidth;
                        $newHeight = $origHeight;
                    }

                    $newImage = imagecreatetruecolor($newWidth, $newHeight);

                    if ($mime === 'image/png') {
                        imagealphablending($newImage, false);
                        imagesavealpha($newImage, true);
                        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                        imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
                    }

                    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

                    if ($mime === 'image/png') {
                        imagepng($newImage, $destination, 7);
                    } elseif ($mime === 'image/webp') {
                        imagewebp($newImage, $destination, 75);
                    } else {
                        imagejpeg($newImage, $destination, 75);
                    }

                    imagedestroy($image);
                    imagedestroy($newImage);

                    return 'jurnal_media/foto/' . $hashName;
                }
            }
        }

        $file->move($targetDir, $hashName);
        return 'jurnal_media/foto/' . $hashName;
    }

    // HELPER: PROCESS VIDEO WITH HASHED FILENAME
    private function processVideo($file)
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'mp4');
        $hashName = md5(uniqid(rand(), true) . microtime()) . '.' . $extension;
        $targetDir = storage_path('app/jurnal_media/video');

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $file->move($targetDir, $hashName);
        return 'jurnal_media/video/' . $hashName;
    }

    // 6. AKSES ORANG TUA (WALI MURID)
    public function waliMuridMateri(Request $request)
    {
        $user = Auth::user();
        
        // Ambil semua anak
        $anak = \App\Models\Siswa::where('email', $user->email)->orWhere('user_id', $user->id)->get();
        if ($anak->isEmpty()) {
            return redirect('/dashboard')->with('error', 'Belum ada data anak.');
        }

        if (!$request->route('kode_yayasan')) {
            $sch = \App\Models\School::find($anak->first()->school_id);
            $yayasan = $sch ? \App\Models\Yayasan::find($sch->yayasan_id) : \App\Models\Yayasan::where('status', 'aktif')->first();
            if ($yayasan) {
                return redirect()->route('yayasan.walimurid.materi', array_merge(['kode_yayasan' => $yayasan->encrypted_token], $request->query()));
            }
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