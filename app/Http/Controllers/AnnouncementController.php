<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\School;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    // Semua role bisa lihat daftar pengumuman
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $today = Carbon::today();

        if ($user->role === 'wali_murid') {
            $schoolIds = Siswa::where('email', $user->email)
                ->pluck('school_id')
                ->unique()
                ->values();

            $schools = School::whereIn('id', $schoolIds)->get();

            $pengumumanAktif = Announcement::whereIn('school_id', $schoolIds)
                ->whereIn('target', ['semua', 'wali_murid'])
                ->whereDate('tanggal_kegiatan', '>=', $today)
                ->orderBy('tanggal_kegiatan')
                ->latest()
                ->get()
                ->groupBy('school_id');

            $arsipPengumuman = Announcement::whereIn('school_id', $schoolIds)
                ->whereIn('target', ['semua', 'wali_murid'])
                ->whereDate('tanggal_kegiatan', '<', $today)
                ->orderByDesc('tanggal_kegiatan')
                ->latest()
                ->get()
                ->groupBy('school_id');

            return view('pengumuman.index', compact('schools', 'pengumumanAktif', 'arsipPengumuman'));
        }

        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;

        if (!$schoolId) {
            $school = null;
            $pengumumanAktif = collect();
            $arsipPengumuman = collect();
            return view('pengumuman.index', compact('school', 'pengumumanAktif', 'arsipPengumuman'));
        }

        $school = School::find($schoolId);

        $pengumumanAktif = Announcement::where('school_id', $schoolId)
            ->whereDate('tanggal_kegiatan', '>=', $today)
            ->orderBy('tanggal_kegiatan')
            ->latest()
            ->get();

        $arsipPengumuman = Announcement::where('school_id', $schoolId)
            ->whereDate('tanggal_kegiatan', '<', $today)
            ->orderByDesc('tanggal_kegiatan')
            ->latest()
            ->get();

        return view('pengumuman.index', compact('school', 'pengumumanAktif', 'arsipPengumuman'));
    }

    // Hanya Admin yang bisa simpan
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'tanggal_kegiatan' => 'required|date',
        ]);

        Announcement::create([
            'school_id' => Auth::user()->school_id,
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'target' => $request->target ?? 'semua',
        ]);

        return back()->with('success', 'Pengumuman berhasil diterbitkan!');
    }

    public function destroy($id)
    {
        Announcement::where('id', $id)->where('school_id', Auth::user()->school_id)->delete();
        return back()->with('success', 'Pengumuman dihapus!');
    }
}