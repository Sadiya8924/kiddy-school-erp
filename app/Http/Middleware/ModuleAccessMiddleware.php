<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ModuleAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $moduleRoute): Response
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Pastikan user ada (sudah login)
        if (!$user) {
            return redirect('/login');
        }

        // Super Admin bypass semua pengecekan modul
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Cek apakah modul ini terdaftar dan aktif di sekolah user
        $hasModule = $user->school->modules()
            ->where('route_name', $moduleRoute)
            ->first();

        if (!$hasModule) {
            abort(403, 'Sekolah Anda belum berlangganan fitur ini.');
        }

        // Jika user adalah Guru, cek apakah Admin mengizinkan fitur ini tampil
        if ($user->isGuru() && !$hasModule->pivot->is_active_for_guru) {
            abort(403, 'Akses fitur ini dinonaktifkan oleh Admin sekolah.');
        }

        return $next($request);
    }
}