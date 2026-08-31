<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleManagementController extends Controller
{
    // Menampilkan halaman form checklist fitur
    public function manage($id)
    {
        $school = School::findOrFail($id);
        $user = auth()->user();
        $active_yayasan_id = $user->getUserYayasanId() ?: session('active_yayasan_id');

        if (!$user->isOwner() && $active_yayasan_id && $school->yayasan_id != $active_yayasan_id) {
            abort(403, 'Akses ditolak.');
        }

        $all_modules = Module::all();
        
        // Ambil ID dari modul-modul yang saat ini sedang aktif di sekolah tersebut
        $active_modules = $school->modules->pluck('id')->toArray();

        return view('superadmin.schools.manage_modules', compact('school', 'all_modules', 'active_modules'));
    }

    // Memproses data saat tombol "Simpan Perubahan" diklik
    public function update(Request $request, $id)
    {
        $school = School::findOrFail($id);
        $user = auth()->user();
        $active_yayasan_id = $user->getUserYayasanId() ?: session('active_yayasan_id');

        if (!$user->isOwner() && $active_yayasan_id && $school->yayasan_id != $active_yayasan_id) {
            abort(403, 'Akses ditolak.');
        }
        
        // Sinkronisasi data di tabel pivot school_modules.
        // Jika tidak ada checkbox yang dicentang (null), maka gunakan array kosong []
        $school->modules()->sync($request->modules ?? []);

        return redirect()->route('superadmin.schools.index')
                         ->with('success', 'Paket fitur untuk ' . $school->nama_sekolah . ' berhasil diperbarui!');
    }
}