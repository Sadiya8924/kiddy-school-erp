<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MateriController extends Controller
{
    // === 1. AKSES GURU ===
    public function indexGuru(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        
        $materis = Materi::where('school_id', $schoolId)
                    ->where('created_by', $user->id)
                    ->latest()
                    ->get();

        $stats = [
            'total' => $materis->count(),
            'pending' => $materis->where('status', 'Pending')->count(),
            'approved' => $materis->whereIn('status', ['Approved', 'Published'])->count(),
            'rejected' => $materis->where('status', 'Rejected')->count(),
        ];

        return view('materi.guru_index', compact('materis', 'stats'));
    }

    public function createGuru()
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        $list_kelas = \App\Models\Kelas::where('school_id', $schoolId)->get();
        
        return view('materi.guru_create', compact('list_kelas'));
    }

    public function storeGuru(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'mata_pelajaran' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|max:20480',
        ]);

        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        
        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('materi_files', 'public');
        }

        $materi = Materi::create([
            'school_id' => $schoolId,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'mata_pelajaran' => $request->mata_pelajaran,
            'kelas' => $request->kelas,
            'file' => $path,
            'created_by' => $user->id,
            'status' => 'Pending'
        ]);

        \App\Models\MateriJurnal::syncFromLms($materi);

        return redirect()->route('materi.guru.index')->with('success', 'Materi berhasil dikirim dan menunggu persetujuan Kepala Sekolah.');
    }

    // === 2. AKSES KEPALA SEKOLAH ===
    public function indexKepsek(Request $request)
    {
        $schoolId = Auth::user()->isSuperAdmin() ? session('active_school_id') : Auth::user()->school_id;
        
        $query = Materi::with('creator')->where('school_id', $schoolId);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $materis = $query->latest()->get();
        $pendingCount = Materi::where('school_id', $schoolId)->where('status', 'Pending')->count();

        return view('materi.kepsek_index', compact('materis', 'pendingCount'));
    }

    public function approve(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);
        $materi->update([
            'status' => 'Published',
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
            'rejection_note' => null
        ]);

        \App\Models\MateriJurnal::syncFromLms($materi);

        return back()->with('success', 'Materi disetujui dan berhasil dipublikasikan!');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['rejection_note' => 'required|string']);
        
        $materi = Materi::findOrFail($id);
        $materi->update([
            'status' => 'Rejected',
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
            'rejection_note' => $request->rejection_note
        ]);

        \App\Models\MateriJurnal::where('id', $id)->delete();

        return back()->with('success', 'Materi ditolak dan dikembalikan ke guru.');
    }

    // === 3. AKSES ORANG TUA ===
    public function indexOrtu(Request $request)
    {
        $user = Auth::user();
        
        // Ambil SEMUA data anak dari user ini
        $anak = \App\Models\Siswa::where('email', $user->email)->get();
        if ($anak->isEmpty()) {
            return redirect('/dashboard')->with('error', 'Belum ada data anak.');
        }

        $schoolIds = [];
        $kelasNames = [];
        
        // Kumpulkan semua ID sekolah dan string nama kelas aktif dari anak-anak
        foreach ($anak as $siswa) {
            $schoolIds[] = $siswa->school_id;
            $kelasAktif = $siswa->kelasAktif()->first();
            if ($kelasAktif) {
                // String ini harus SAMA PERSIS dengan value yang disimpan oleh form guru_create.blade.php
                $kelasNames[] = "{$kelasAktif->nama_kelas} (Kelompok {$kelasAktif->kelompok})";
            }
        }
        
        $schoolIds = array_unique($schoolIds);
        $kelasNames = array_unique($kelasNames);
        
        // Tarik materi berdasarkan kelas-kelas anak tersebut
        $materis = Materi::with('creator')
            ->whereIn('school_id', $schoolIds)
            ->whereIn('kelas', $kelasNames)
            ->where('status', 'Published')
            ->latest()
            ->get();
            
        return view('materi.ortu_index', compact('materis', 'anak'));
    }
}
