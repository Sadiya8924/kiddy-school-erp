<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Gate;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Siswa;
use App\Policies\SiswaPolicy;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'module' => \App\Http\Middleware\ModuleAccessMiddleware::class,
            'pantau.readonly' => \App\Http\Middleware\PantauReadOnlyMiddleware::class,
            'resolve.tenant' => \App\Http\Middleware\ResolveTenantFromHost::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // TM-04: Daftarkan Policy IDOR untuk model Siswa
        Gate::policy(Siswa::class, SiswaPolicy::class);
    })->create();
