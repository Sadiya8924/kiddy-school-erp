<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\ArsipRapor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArsipRaporController extends Controller
{
    // Menampilkan halaman daftar arsip DENGAN MULTI FILTER
    public function index(Request $request)
    {
        $user = Auth::user();
        $semesterAktif = Semester::where('is_active', true)->first();
        $semesters = Semester::orderBy('id', 'desc')->get(); 

        // 1. Tangkap request filter dari tampilan
        $filter_kelas = $request->get('kelas_id', 'all');
        $filter_semester = $request->get('semester_id', $semesterAktif ? $semesterAktif->id : null);
        $status_arsip = $request->get('status_arsip', 'all');

        // Query Dasar
        $query = Siswa::where('school_id', $user->school_id)->where('status', 'aktif');

        // 2. Filter Kelas (Beda logic untuk Guru dan Admin)
        if ($user->role === 'guru' && $user->kelas_id) {
            $query->whereHas('kelas', function($q) use ($user) {
                $q->where('anggota_kelas.kelas_id', $user->kelas_id);
            });
            $list_kelas = [];
        } else {
            $list_kelas = Kelas::where('school_id', $user->school_id)->get();
            if ($filter_kelas != 'all') {
                $query->whereHas('kelas', function($q) use ($filter_kelas) {
                    $q->where('anggota_kelas.kelas_id', $filter_kelas);
                });
            }
        }

        // 3. Filter Status Arsip berdasarkan Semester yang dipilih
        if ($filter_semester && $status_arsip != 'all') {
            if ($status_arsip == 'sudah') {
                // Tampilkan siswa yang SUDAH punya arsip di semester ini
                $query->whereHas('arsipRapors', function($q) use ($filter_semester) {
                    $q->where('semester_id', $filter_semester);
                });
            } elseif ($status_arsip == 'belum') {
                // Tampilkan siswa yang BELUM punya arsip di semester ini
                $query->whereDoesntHave('arsipRapors', function($q) use ($filter_semester) {
                    $q->where('semester_id', $filter_semester);
                });
            }
        }

        $siswas = $query->with('arsipRapors.semester')->get();

        return view('arsip.index', compact(
            'siswas', 'list_kelas', 'semesters', 'semesterAktif', 
            'filter_kelas', 'filter_semester', 'status_arsip'
        ));
    }

    // Proses Simpan/Upload File (Hanya Admin)
    public function store(Request $request)
    {
        $request->validate([
            'semester_id' => 'required', 
            'file_arsip'  => 'required|file|mimes:pdf,zip,rar|max:10240', 
        ]);

        $semester_id = $request->semester_id;

        // 1. Cek berdasarkan semester_id saja (karena school_id belum ada di tabel)
        $arsipLama = ArsipRapor::where('semester_id', $semester_id)->first();

        // 2. Kalau ADA, hapus file lamanya dari folder storage secara fisik
        if ($arsipLama && $arsipLama->file_path) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($arsipLama->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($arsipLama->file_path);
            }
        }

        // 3. Upload file yang baru
        $filePath = $request->file('file_arsip')->store('arsip_rapor', 'public');

        // 4. Update data di database (Timpa path file lamanya dengan yang baru)
        ArsipRapor::updateOrCreate(
            [
                'semester_id' => $semester_id,
            ],
            [
                'file_path'   => $filePath,
                'user_id'     => \Illuminate\Support\Facades\Auth::id(), 
            ]
        );

        return back()->with('success', 'Arsip berhasil diunggah! File lama otomatis tergantikan.');
    }
    
    // Proses Hapus File (Hanya Admin)
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Hanya Admin yang dapat menghapus arsip.');
        }

        $arsip = ArsipRapor::findOrFail($id);
        
        if (Storage::disk('public')->exists($arsip->file_path)) {
            Storage::disk('public')->delete($arsip->file_path);
        }
        
        $arsip->delete();

        return back()->with('success', 'File arsip berhasil dihapus!');
    }
}