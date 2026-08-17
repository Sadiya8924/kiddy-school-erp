<?php

namespace App\Http\Controllers;

use App\Models\Yayasan;
use App\Models\School;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LandingYayasanController extends Controller
{
    /**
     * Tampilkan landing page profil publik per yayasan.
     * Diakses via: /y/{kode_yayasan}
     */
    public function show(string $kode_yayasan)
    {
        $yayasan = Yayasan::where('kode_yayasan', $kode_yayasan)
            ->where('status', 'aktif')
            ->with('schools')
            ->firstOrFail();

        // Statistik ringkas per sekolah
        $schools = $yayasan->schools->where('status', 'aktif')->map(function ($school) {
            $school->jumlah_siswa = Siswa::where('school_id', $school->id)
                ->where('status', 'aktif')
                ->count();
            return $school;
        });

        $schoolIds = $schools->pluck('id')->toArray();
        $announcements = \App\Models\Announcement::whereIn('school_id', $schoolIds)
            ->latest()
            ->take(3)
            ->get();

        $total_siswa   = $schools->sum('jumlah_siswa');
        $total_sekolah = $schools->count();

        return view('landing_yayasan', compact(
            'yayasan',
            'schools',
            'total_siswa',
            'total_sekolah',
            'announcements'
        ));
    }

    /**
     * GET /y/{kode_yayasan}/daftar
     * Halaman khusus pendaftaran PPDB siswa baru & registrasi akun wali murid yayasan ini.
     */
    public function pendaftaranPage(Request $request, string $kode_yayasan)
    {
        $yayasan = Yayasan::where('kode_yayasan', $kode_yayasan)
            ->where('status', 'aktif')
            ->with('schools')
            ->firstOrFail();

        $schools = $yayasan->schools->where('status', 'aktif')->values();
        $selectedSchoolId = $request->query('school_id');

        return view('landing_yayasan_pendaftaran', compact('yayasan', 'schools', 'selectedSchoolId'));
    }

    /**
     * POST /y/{kode_yayasan}/register
     * Daftar akun wali murid baru yang terikat ke yayasan ini.
     */
    public function register(Request $request, string $kode_yayasan)
    {
        $yayasan = Yayasan::where('kode_yayasan', $kode_yayasan)
            ->where('status', 'aktif')
            ->firstOrFail();

        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
        ], [
            'name.required'          => 'Nama lengkap wajib diisi.',
            'email.required'         => 'Alamat email wajib diisi.',
            'email.unique'           => 'Email ini sudah terdaftar. Silakan login.',
            'password.required'      => 'Kata sandi wajib diisi.',
            'password.min'           => 'Kata sandi minimal 8 karakter.',
            'password.confirmed'     => 'Konfirmasi sandi tidak cocok.',
        ]);

        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'wali_murid',
            'yayasan_id' => $yayasan->id,
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success',
            'Akun berhasil dibuat! Selamat datang di portal ' . $yayasan->nama_yayasan . '.'
        );
    }
}
