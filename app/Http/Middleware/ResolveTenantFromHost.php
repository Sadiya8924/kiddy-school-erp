<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class ResolveTenantFromHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        // Skip default local/IP hosts
        if (!in_array($host, ['localhost', '127.0.0.1', '::1']) && Schema::hasTable('domains')) {
            $domainRecord = DB::table('domains')
                ->where('domain', $host)
                ->where('is_verified', true)
                ->first();

            if ($domainRecord) {
                $request->attributes->set('tenant_school_id', $domainRecord->school_id);
                Config::set('session.domain', '.' . $host);
                if (!session()->has('active_school_id')) {
                    session(['active_school_id' => $domainRecord->school_id]);
                }
            }
        }

        return $next($request);
    }
}
