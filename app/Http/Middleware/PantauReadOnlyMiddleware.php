<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PantauReadOnlyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && ($user->role === 'super_admin' || $user->role === 'platform_admin' || $user->role === 'owner')) {
            $activeSchool = session('active_school_id');
            $isTakeover = session('takeover_active', false);

            // If in Pantau mode (monitoring another school) and attempting a mutating request without explicit takeover
            if ($activeSchool && !$isTakeover && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                // Allow switch-school and takeover toggle routes
                if ($request->routeIs('superadmin.switch_school') || $request->routeIs('superadmin.takeover') || $request->routeIs('logout')) {
                    return $next($request);
                }

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Mode Pantau Aktif (Read-Only). Aktifkan mode "Ambil Alih" untuk melakukan perubahan data.'
                    ], 403);
                }

                return back()->with('error', 'Mode Pantau Aktif (Read-Only). Aktifkan mode "Ambil Alih" jika Anda perlu melakukan perubahan data sekolah ini.');
            }
        }

        return $next($request);
    }
}
