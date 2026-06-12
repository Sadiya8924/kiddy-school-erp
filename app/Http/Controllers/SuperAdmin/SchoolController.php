<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::latest()->paginate(10);
        return view('superadmin.schools.index', compact('schools'));
    }

    public function create()
    {
        return view('superadmin.schools.create');
    }

    public function store(Request $request)
    {
        $request->validate([
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
            $data['logo_sekolah'] = $request->file('logo_sekolah')->store('logos', 'public');
        }

        School::create($data);
        return redirect()->route('superadmin.schools.index')->with('success', 'Sekolah berhasil ditambahkan!');
    }

    public function edit(School $school)
    {
        return view('superadmin.schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $request->validate([
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
        $users = $school->users()->whereNot('role', 'super_admin')->get();
        return view('superadmin.schools.show', compact('school', 'users'));
    }
}