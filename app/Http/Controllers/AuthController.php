<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        // 1. Verifikasi kredensial dasar terlebih dahulu
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Kombinasi email dan password tidak valid.')->withInput($request->only('email'));
        }

        // 2. Validasi Portal & Peran Akses Login
        if ($request->filled('kode_yayasan')) {
            // A. Login via Portal Yayasan (/y/{kode_yayasan}/login)
            $portalYayasan = \App\Models\Yayasan::where('kode_yayasan', $request->kode_yayasan)->first();

            if ($portalYayasan && !in_array($user->role, ['owner', 'platform_admin'])) {
                $userYayasanId = $user->getUserYayasanId();

                if ($userYayasanId && (int)$userYayasanId !== (int)$portalYayasan->id) {
                    $correctYayasan = \App\Models\Yayasan::find($userYayasanId);
                    $correctYayasanName = $correctYayasan ? $correctYayasan->nama_yayasan : 'Yayasan Asal Anda';
                    $correctYayasanUrl  = $correctYayasan ? route('yayasan.login', $correctYayasan->encrypted_token) : '/login';

                    return back()->with('wrong_yayasan', [
                        'user_email'           => $user->email,
                        'user_name'            => $user->name,
                        'portal_yayasan_name'  => $portalYayasan->nama_yayasan,
                        'correct_yayasan_name' => $correctYayasanName,
                        'correct_yayasan_url'  => $correctYayasanUrl,
                    ])->withInput($request->only('email'));
                }
            }
        } else {
            // B. Login via Halaman Utama Platform SaaS (/login) - Khusus Owner & Platform Admin
            if (!in_array($user->role, ['owner', 'platform_admin'])) {
                $userYayasanId = $user->getUserYayasanId();
                if ($userYayasanId) {
                    $correctYayasan = \App\Models\Yayasan::find($userYayasanId);
                    $correctYayasanName = $correctYayasan ? $correctYayasan->nama_yayasan : 'Yayasan Anda';
                    $correctYayasanUrl  = $correctYayasan ? route('yayasan.login', $correctYayasan->encrypted_token) : '/login';

                    return back()->with('wrong_yayasan', [
                        'user_email'           => $user->email,
                        'user_name'            => $user->name,
                        'portal_yayasan_name'  => 'Platform Owner SaaS',
                        'correct_yayasan_name' => $correctYayasanName,
                        'correct_yayasan_url'  => $correctYayasanUrl,
                    ])->withInput($request->only('email'));
                }
            }
        }

        // 3. Eksekusi Login
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $role = $user->role;

        $userYayasanId = $user->getUserYayasanId();
        $userYayasan = $userYayasanId ? \App\Models\Yayasan::find($userYayasanId) : null;

        if ($role === 'owner') {
            return redirect('/owner/yayasans');
        } elseif (in_array($role, ['super_admin', 'platform_admin'])) {
            if ($userYayasan) {
                return redirect()->route('superadmin.dashboard.encrypted', $userYayasan->encrypted_token);
            }
            return redirect()->route('superadmin.dashboard');
        } else {
            if ($userYayasan) {
                return redirect()->route('dashboard.encrypted', $userYayasan->encrypted_token);
            }
            return redirect()->route('dashboard');
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $redirectUrl = '/login';

        if ($user && !in_array($user->role, ['owner', 'platform_admin'])) {
            $yayasanId = $user->getUserYayasanId();
            if ($yayasanId) {
                $yayasan = \App\Models\Yayasan::find($yayasanId);
                if ($yayasan) {
                    $redirectUrl = route('yayasan.login', $yayasan->encrypted_token);
                }
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($redirectUrl)->with('info', 'Anda telah berhasil keluar dari portal.');
    }

    public function register(Request $request)
    {
        // TM-33: Validate input form & consent
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'nullable|accepted',
        ], [
            'email.unique' => 'Email ini sudah terdaftar! Silakan login.',
            'password.confirmed' => 'Konfirmasi sandi tidak cocok.',
            'password.min' => 'Sandi minimal 8 karakter.',
            'terms.accepted' => 'Anda harus menyetujui Kebijakan Privasi dan Syarat & Ketentuan.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'wali_murid',
        ]);

        // TM-35: Track consent metadata
        session([
            'legal_consent_at' => now()->toIso8601String(),
            'legal_consent_ip' => $request->ip(),
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Akun berhasil dibuat! Silakan daftarkan putra/putri Anda.');
    }

    // TM-34: Password Reset Handler
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            // Generates token and stores in password_reset_tokens table
            $token = Str::random(64);
            \DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                ['token' => Hash::make($token), 'created_at' => now()]
            );

            // Return success message regardless to prevent user enumeration
            return back()->with('success', 'Jika email terdaftar, instruksi reset kata sandi telah dikirim.');
        }

        return back()->with('success', 'Jika email terdaftar, instruksi reset kata sandi telah dikirim.');
    }
}