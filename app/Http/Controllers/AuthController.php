<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $role = Auth::user()->role;

            return match($role) {
                'owner'       => redirect('/owner/yayasans'),
                'super_admin' => redirect('/superadmin/dashboard'),
                default       => redirect('/dashboard'),
            };
        }

        return back()->with('error', 'Email atau password salah');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // Tambahkan fungsi register ini
    public function register(Request $request)
    {
        // 1. Validasi input form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
        ], [
            'email.unique' => 'Email ini sudah terdaftar! Silakan login.',
            'password.confirmed' => 'Konfirmasi sandi tidak cocok.',
            'password.min' => 'Sandi minimal 8 karakter.'
        ]);

        // 2. Simpan user baru ke database
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role, 
        ]);

        // 3. Login otomatis
        Auth::login($user);

        // 4. Redirect ke Form Pendaftaran Anak
        return redirect('/dashboard')->with('success', 'Akun berhasil dibuat! Silakan daftarkan putra/putri Anda.');
    }
}