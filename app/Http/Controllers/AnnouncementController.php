<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    // Semua role bisa lihat daftar pengumuman
    public function index()
    {
        $user = Auth::user();
        $pengumuman = Announcement::where('school_id', $user->school_id)->latest()->get();
        return view('pengumuman.index', compact('pengumuman'));
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