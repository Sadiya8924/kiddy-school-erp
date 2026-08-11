<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Policies\SiswaPolicy;
use App\Policies\KelasPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * TM-04: Daftarkan Policy secara eksplisit untuk mencegah IDOR.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // TM-04: IDOR Protection — Siswa hanya bisa diakses oleh role yang berwenang
        Gate::policy(Siswa::class, SiswaPolicy::class);

        // TM-04: IDOR Protection — Kelas hanya bisa diakses oleh role yang berwenang
        Gate::policy(Kelas::class, KelasPolicy::class);
    }
}
