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
    private function findYayasan(string $kode_yayasan, bool &$isEncrypted = false): Yayasan
    {
        $decryptedCode = Yayasan::decryptToken($kode_yayasan);
        if ($decryptedCode) {
            $isEncrypted = true;
            $targetCode = $decryptedCode;
        } else {
            $isEncrypted = false;
            $targetCode = $kode_yayasan;
        }

        $code = strtoupper(trim($targetCode));
        return Yayasan::where(function($q) use ($code) {
                $q->where('kode_yayasan', $code)
                  ->orWhere('kode_yayasan', strtolower($code));
                
                if (in_array($code, ['YYS-002', 'YYS-2', 'YAH2'])) {
                    $q->orWhereIn('kode_yayasan', ['YYS-002', 'YAH2']);
                } elseif (in_array($code, ['YYS-001', 'YYS-1', 'YBIC'])) {
                    $q->orWhereIn('kode_yayasan', ['YYS-001', 'YBIC']);
                }
            })
            ->where('status', 'aktif')
            ->firstOrFail();
    }

    /**
     * Tampilkan landing page profil publik per yayasan.
     * Diakses via: /y/{kode_yayasan}
     */
    public function show(string $kode_yayasan)
    {
        $isEncrypted = false;
        $yayasan = $this->findYayasan($kode_yayasan, $isEncrypted);

        // Jika diakses menggunakan kode unencrypted (misal /y/YAH2), redirect paksa ke URL terenkripsi
        if (!$isEncrypted && $kode_yayasan !== $yayasan->encrypted_token) {
            return redirect()->route('yayasan.landing', $yayasan->encrypted_token);
        }

        $yayasan->load('schools');

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

        $total_siswa   = Siswa::whereIn('school_id', $schoolIds)->where('status', 'aktif')->count();
        $total_sekolah = $schools->count();
        $total_guru    = User::where(function($q) use ($yayasan, $schoolIds) {
                            $q->where('yayasan_id', $yayasan->id);
                            if (!empty($schoolIds)) {
                                $q->orWhereIn('school_id', $schoolIds);
                            }
                        })
                        ->whereIn('role', ['guru', 'kepsek', 'admin'])
                        ->count();
        $total_kelas   = \App\Models\Kelas::whereIn('school_id', $schoolIds)->count();
        $total_alumni  = Siswa::whereIn('school_id', $schoolIds)->where('status', 'alumni')->count();

        return view('landing_yayasan', compact(
            'yayasan',
            'schools',
            'total_siswa',
            'total_sekolah',
            'total_guru',
            'total_kelas',
            'total_alumni',
            'announcements'
        ));
    }

    /**
     * GET /y/{kode_yayasan}/daftar
     * Halaman khusus pendaftaran PPDB siswa baru & registrasi akun wali murid yayasan ini.
     */
    public function pendaftaranPage(Request $request, string $kode_yayasan)
    {
        $isEncrypted = false;
        $yayasan = $this->findYayasan($kode_yayasan, $isEncrypted);

        // Jika diakses menggunakan kode unencrypted, redirect ke URL terenkripsi
        if (!$isEncrypted) {
            return redirect()->route('yayasan.pendaftaran', array_merge(['kode_yayasan' => $yayasan->encrypted_token], $request->query()));
        }

        $yayasan->load('schools');

        $schools = $yayasan->schools->where('status', 'aktif')->values();
        $selectedSchoolId = $request->query('school_id');

        return view('landing_yayasan_pendaftaran', compact('yayasan', 'schools', 'selectedSchoolId'));
    }

    /**
     * GET /y/{kode_yayasan}/login
     * Halaman login khusus portal per yayasan dengan tema & branding mengikuti template landing page yayasan.
     */
    public function loginPage(Request $request, string $kode_yayasan)
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        $isEncrypted = false;
        $yayasan = $this->findYayasan($kode_yayasan, $isEncrypted);

        // Jika diakses menggunakan kode unencrypted (misal /y/YAH2/login), redirect paksa ke URL terenkripsi
        if (!$isEncrypted) {
            return redirect()->route('yayasan.login', $yayasan->encrypted_token);
        }

        // Tarik data akun demo yang terdaftar di database khusus untuk yayasan ini
        $demo_accounts = [
            'super_admin' => \App\Models\User::where('yayasan_id', $yayasan->id)->where('role', 'super_admin')->first(),
            'admin'       => \App\Models\User::where('yayasan_id', $yayasan->id)->where('role', 'admin')->first(),
            'kepsek'      => \App\Models\User::where('yayasan_id', $yayasan->id)->where('role', 'kepsek')->first(),
            'guru'        => \App\Models\User::where('yayasan_id', $yayasan->id)->where('role', 'guru')->first(),
            'wali_murid'  => \App\Models\User::where('yayasan_id', $yayasan->id)->where('role', 'wali_murid')->first(),
        ];

        return view('landing_yayasan_login', compact('yayasan', 'demo_accounts'));
    }

    /**
     * POST /y/{kode_yayasan}/register
     * Daftar akun wali murid baru yang terikat ke yayasan ini.
     */
    public function register(Request $request, string $kode_yayasan)
    {
        $yayasan = $this->findYayasan($kode_yayasan);

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
