<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Yayasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $active_yayasan_id = $user->getUserYayasanId() ?: session('active_yayasan_id');

        $query = School::with('yayasan');

        if (!$user->isOwner() || $active_yayasan_id) {
            if ($active_yayasan_id) {
                $query->where('yayasan_id', $active_yayasan_id);
            }
        }

        $schools = $query->latest()->paginate(10);
        return view('superadmin.schools.index', compact('schools'));
    }

    public function create()
    {
        $user = Auth::user();
        $active_yayasan_id = $user->getUserYayasanId() ?: session('active_yayasan_id');

        $yayasans = Yayasan::where('status', 'aktif')
            ->when(!$user->isOwner() && $active_yayasan_id, function($q) use ($active_yayasan_id) {
                return $q->where('id', $active_yayasan_id);
            })
            ->get();

        return view('superadmin.schools.create', compact('yayasans'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $active_yayasan_id = $user->getUserYayasanId() ?: session('active_yayasan_id');

        $request->validate([
            'yayasan_id'         => 'nullable|exists:yayasans,id',
            'nama_sekolah'       => 'required|string|max:255',
            'npsn'               => 'nullable|string|max:20',
            'alamat'             => 'nullable|string',
            'kepala_sekolah'     => 'nullable|string|max:255',
            'telepon'            => 'nullable|string|max:20',
            'tahun_ajaran_aktif' => 'nullable|string|max:20',
            'semester_aktif'     => 'nullable|string|max:20',
            'max_indent_years'   => 'nullable|integer|min:1|max:10',
            'kuota_tk_pagi'      => 'nullable|integer|min:0',
            'kuota_tk_siang'     => 'nullable|integer|min:0',
            'kuota_paud'         => 'nullable|integer|min:0',
            'logo_sekolah'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sekolah'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'             => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->except(['logo_sekolah', 'foto_sekolah']);

        // Jika yayasan_id kosong atau non-owner, pakai yayasan milik user
        if (empty($data['yayasan_id']) || !$user->isOwner()) {
            $data['yayasan_id'] = $active_yayasan_id ?: 1;
        }

        if ($request->hasFile('logo_sekolah')) {
            $data['logo_sekolah'] = $request->file('logo_sekolah')->store('logos', 'public');
        }

        if ($request->hasFile('foto_sekolah')) {
            $data['foto_sekolah'] = $request->file('foto_sekolah')->store('foto_sekolah', 'public');
        }

        School::create($data);
        return redirect()->route('superadmin.schools.index')->with('success', 'Sekolah berhasil ditambahkan!');
    }

    public function edit(School $school)
    {
        $user = Auth::user();
        $active_yayasan_id = $user->getUserYayasanId() ?: session('active_yayasan_id');

        if (!$user->isOwner() && $active_yayasan_id && $school->yayasan_id != $active_yayasan_id) {
            abort(403, 'Akses ditolak.');
        }

        $yayasans = Yayasan::where('status', 'aktif')
            ->when(!$user->isOwner() && $active_yayasan_id, function($q) use ($active_yayasan_id) {
                return $q->where('id', $active_yayasan_id);
            })
            ->get();

        return view('superadmin.schools.edit', compact('school', 'yayasans'));
    }

    public function update(Request $request, School $school)
    {
        $user = Auth::user();
        $active_yayasan_id = $user->getUserYayasanId() ?: session('active_yayasan_id');

        if (!$user->isOwner() && $active_yayasan_id && $school->yayasan_id != $active_yayasan_id) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'yayasan_id'         => 'nullable|exists:yayasans,id',
            'nama_sekolah'       => 'required|string|max:255',
            'npsn'               => 'nullable|string|max:20',
            'alamat'             => 'nullable|string',
            'kepala_sekolah'     => 'nullable|string|max:255',
            'telepon'            => 'nullable|string|max:20',
            'tahun_ajaran_aktif' => 'nullable|string|max:20',
            'semester_aktif'     => 'nullable|string|max:20',
            'max_indent_years'   => 'nullable|integer|min:1|max:10',
            'kuota_tk_pagi'      => 'nullable|integer|min:0',
            'kuota_tk_siang'     => 'nullable|integer|min:0',
            'kuota_paud'         => 'nullable|integer|min:0',
            'logo_sekolah'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sekolah'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'             => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->except(['logo_sekolah', 'foto_sekolah']);

        if (!$user->isOwner() && $active_yayasan_id) {
            $data['yayasan_id'] = $active_yayasan_id;
        }

        if ($request->hasFile('logo_sekolah')) {
            if ($school->logo_sekolah) Storage::disk('public')->delete($school->logo_sekolah);
            $data['logo_sekolah'] = $request->file('logo_sekolah')->store('logos', 'public');
        }

        if ($request->hasFile('foto_sekolah')) {
            if ($school->foto_sekolah) Storage::disk('public')->delete($school->foto_sekolah);
            $data['foto_sekolah'] = $request->file('foto_sekolah')->store('foto_sekolah', 'public');
        }

        $school->update($data);
        return redirect()->route('superadmin.schools.index')->with('success', 'Sekolah berhasil diupdate!');
    }

    public function destroy(School $school)
    {
        $user = Auth::user();
        $active_yayasan_id = $user->getUserYayasanId() ?: session('active_yayasan_id');

        if (!$user->isOwner() && $active_yayasan_id && $school->yayasan_id != $active_yayasan_id) {
            abort(403, 'Akses ditolak.');
        }

        if ($school->logo_sekolah) Storage::disk('public')->delete($school->logo_sekolah);
        if ($school->foto_sekolah) Storage::disk('public')->delete($school->foto_sekolah);
        $school->delete();
        return redirect()->route('superadmin.schools.index')->with('success', 'Sekolah berhasil dihapus!');
    }

    public function show(School $school)
    {
        $user = Auth::user();
        $active_yayasan_id = $user->getUserYayasanId() ?: session('active_yayasan_id');

        if (!$user->isOwner() && $active_yayasan_id && $school->yayasan_id != $active_yayasan_id) {
            abort(403, 'Akses ditolak.');
        }

        $users = $school->users()->whereNotIn('role', ['super_admin', 'owner'])->get();
        return view('superadmin.schools.show', compact('school', 'users'));
    }
}