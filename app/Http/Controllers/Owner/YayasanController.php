<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Yayasan;
use App\Models\School;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class YayasanController extends Controller
{
    public function index()
    {
        $yayasans = Yayasan::withCount(['schools', 'users'])
            ->latest()
            ->paginate(10);

        foreach ($yayasans as $yayasan) {
            $schoolIds = $yayasan->schools->pluck('id');
            $yayasan->total_siswa = DB::table('siswas')->whereIn('school_id', $schoolIds)->count();
            $yayasan->total_kelas = DB::table('kelas')->whereIn('school_id', $schoolIds)->count();
        }

        return view('owner.yayasans.index', compact('yayasans'));
    }

    public function show(Yayasan $yayasan)
    {
        $semesterAktif = Semester::where('is_active', true)->first();

        // Ambil sekolah-sekolah di bawah yayasan ini
        $schools = School::where('yayasan_id', $yayasan->id)
            ->withCount(['users', 'siswas'])
            ->latest()
            ->get();

        // Untuk setiap sekolah, ambil data kelas beserta jumlah siswa
        foreach ($schools as $school) {
            $school->kelases = Kelas::where('school_id', $school->id)
                ->get()
                ->map(function ($kelas) use ($semesterAktif) {
                    $kelas->total_siswa = DB::table('anggota_kelas')
                        ->where('kelas_id', $kelas->id)
                        ->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
                        ->count();
                    
                    // Cari guru kelas jika ada
                    $kelas->guru = User::where('kelas_id', $kelas->id)->where('role', 'guru')->first();
                    return $kelas;
                });
        }

        $totalUsers = User::whereIn('school_id', $schools->pluck('id'))->orWhere('yayasan_id', $yayasan->id)->count();
        $totalSiswa = DB::table('siswas')->whereIn('school_id', $schools->pluck('id'))->count();
        $totalKelas = DB::table('kelas')->whereIn('school_id', $schools->pluck('id'))->count();

        return view('owner.yayasans.show', compact('yayasan', 'schools', 'totalUsers', 'totalSiswa', 'totalKelas'));
    }

    public function create()
    {
        return view('owner.yayasans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_yayasan'     => 'required|string|max:255',
            'kode_yayasan'     => 'nullable|string|max:50|unique:yayasans,kode_yayasan',
            'penanggung_jawab' => 'nullable|string|max:255',
            'telepon'          => 'nullable|string|max:20',
            'alamat'           => 'nullable|string',
            'logo_yayasan'     => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'status'           => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->except('logo_yayasan');

        if ($request->hasFile('logo_yayasan')) {
            $data['logo_yayasan'] = $request->file('logo_yayasan')->store('logos_yayasan', 'public');
        }

        Yayasan::create($data);

        return redirect()->route('owner.yayasans.index')->with('success', 'Yayasan berhasil ditambahkan!');
    }

    public function edit(Yayasan $yayasan)
    {
        return view('owner.yayasans.edit', compact('yayasan'));
    }

    public function update(Request $request, Yayasan $yayasan)
    {
        $request->validate([
            'nama_yayasan'     => 'required|string|max:255',
            'kode_yayasan'     => 'nullable|string|max:50|unique:yayasans,kode_yayasan,' . $yayasan->id,
            'penanggung_jawab' => 'nullable|string|max:255',
            'telepon'          => 'nullable|string|max:20',
            'alamat'           => 'nullable|string',
            'logo_yayasan'     => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'status'           => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->except('logo_yayasan');

        if ($request->hasFile('logo_yayasan')) {
            if ($yayasan->logo_yayasan) {
                Storage::disk('public')->delete($yayasan->logo_yayasan);
            }
            $data['logo_yayasan'] = $request->file('logo_yayasan')->store('logos_yayasan', 'public');
        }

        $yayasan->update($data);

        return redirect()->back()->with('success', 'Yayasan berhasil diperbarui!');
    }

    public function destroy(Yayasan $yayasan)
    {
        if ($yayasan->logo_yayasan) {
            Storage::disk('public')->delete($yayasan->logo_yayasan);
        }

        $yayasan->delete();

        return redirect()->route('owner.yayasans.index')->with('success', 'Yayasan berhasil dihapus!');
    }

    public function switchYayasan(Request $request)
    {
        if ($request->active_yayasan_id) {
            session(['active_yayasan_id' => $request->active_yayasan_id]);
            session()->forget('active_school_id');
        } else {
            session()->forget('active_yayasan_id');
            session()->forget('active_school_id');
        }

        return back()->with('success', 'Berhasil mengubah Yayasan yang dipantau.');
    }
}
