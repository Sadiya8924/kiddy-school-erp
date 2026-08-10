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

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $role = Auth::user()->role;

            return match($role) {
                'owner'                         => redirect('/owner/yayasans'),
                'super_admin', 'platform_admin' => redirect('/superadmin/dashboard'),
                default                         => redirect('/dashboard'),
            };
        }

        // TM-06: Uniform error message to prevent user enumeration
        return back()->with('error', 'Kombinasi email dan password tidak valid.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
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

        // TM-33: FORCED role wali_murid on server-side
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'wali_murid', // Server-side enforcement
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