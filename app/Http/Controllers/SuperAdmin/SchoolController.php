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
        $active_yayasan_id = session('active_yayasan_id');

        $query = School::with('yayasan');

        if ($user->isOwner()) {
            if ($active_yayasan_id) {
                $query->where('yayasan_id', $active_yayasan_id);
            }
        } elseif ($user->yayasan_id) {
            $query->where('yayasan_id', $user->yayasan_id);
        }

        $schools = $query->latest()->paginate(10);
        return view('superadmin.schools.index', compact('schools'));
    }

    public function create()
    {
        $yayasans = Yayasan::where('status', 'aktif')->get();
        return view('superadmin.schools.create', compact('yayasans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'yayasan_id'         => 'nullable|exists:yayasans,id',
            'nama_sekolah'       => 'required|string|max:255',
            'npsn'               => 'nullable|string|max:20',
            'alamat'             => 'nullable|string',
            'kepala_sekolah'     => 'nullable|string|max:255',
            'telepon'            => 'nullable|string|max:20',
            'tahun_ajaran_aktif' => 'nullable|string|max:20',
            'semester_aktif'     => 'nullable|string|max:20',
            'logo_sekolah'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'             => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->except('logo_sekolah');

        // Jika yayasan_id kosong, pakai active_yayasan_id atau user's yayasan_id atau default 1
        if (empty($data['yayasan_id'])) {
            $data['yayasan_id'] = session('active_yayasan_id') ?? Auth::user()->yayasan_id ?? 1;
        }

        if ($request->hasFile('logo_sekolah')) {
            $data['logo_sekolah'] = $request->file('logo_sekolah')->store('logos', 'public');
        }

        School::create($data);
        return redirect()->route('superadmin.schools.index')->with('success', 'Sekolah berhasil ditambahkan!');
    }

    public function edit(School $school)
    {
        $yayasans = Yayasan::where('status', 'aktif')->get();
        return view('superadmin.schools.edit', compact('school', 'yayasans'));
    }

    public function update(Request $request, School $school)
    {
        $request->validate([
            'yayasan_id'         => 'nullable|exists:yayasans,id',
            'nama_sekolah'       => 'required|string|max:255',
            'npsn'               => 'nullable|string|max:20',
            'alamat'             => 'nullable|string',
            'kepala_sekolah'     => 'nullable|string|max:255',
            'telepon'            => 'nullable|string|max:20',
            'tahun_ajaran_aktif' => 'nullable|string|max:20',
            'semester_aktif'     => 'nullable|string|max:20',
            'logo_sekolah'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'             => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->except('logo_sekolah');

        if ($request->hasFile('logo_sekolah')) {
            if ($school->logo_sekolah) Storage::disk('public')->delete($school->logo_sekolah);
            $data['logo_sekolah'] = $request->file('logo_sekolah')->store('logos', 'public');
        }

        $school->update($data);
        return redirect()->route('superadmin.schools.index')->with('success', 'Sekolah berhasil diupdate!');
    }

    public function destroy(School $school)
    {
        if ($school->logo_sekolah) Storage::disk('public')->delete($school->logo_sekolah);
        $school->delete();
        return redirect()->route('superadmin.schools.index')->with('success', 'Sekolah berhasil dihapus!');
    }

    public function show(School $school)
    {
        $users = $school->users()->whereNotIn('role', ['super_admin', 'owner'])->get();
        return view('superadmin.schools.show', compact('school', 'users'));
    }
}