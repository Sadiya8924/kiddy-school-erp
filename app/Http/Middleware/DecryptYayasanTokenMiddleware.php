<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Yayasan;
use Symfony\Component\HttpFoundation\Response;

class DecryptYayasanTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $rawParam = $request->get('y') ?: $request->route('token');
        if ($rawParam) {
            $decryptedCode = Yayasan::decryptToken($rawParam);
            $targetCodeOrId = $decryptedCode ?: $rawParam;

            if (session('active_yayasan_code') !== $targetCodeOrId && session('active_yayasan_id') != $targetCodeOrId) {
                $foundY = Yayasan::where('id', $targetCodeOrId)
                    ->orWhere('kode_yayasan', $targetCodeOrId)
                    ->orWhere('kode_yayasan', strtolower($targetCodeOrId))
                    ->first();

                if ($foundY) {
                    session([
                        'active_yayasan_id'   => $foundY->id,
                        'active_yayasan_code' => $foundY->kode_yayasan,
                    ]);
                }
            }
        }

        return $next($request);
    }
}
