<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Semester;
use App\Models\Rapor;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RaporController;
use App\Http\Controllers\PerkembanganFisikController;
use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\SuperAdmin\UserController as SuperUserController;
use App\Http\Controllers\SuperAdmin\ModuleManagementController;
use App\Http\Controllers\Owner\YayasanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ProsesKelasController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\SiswaController; 
use App\Http\Controllers\RaporTemplateController; 
use App\Http\Controllers\ArsipRaporController;
use App\Http\Controllers\JurnalHarianController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\LandingYayasanController;
use App\Http\Controllers\YayasanRegistrationController;
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| PUBLIC & AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('pengumuman.index');
Route::get('/auto-seed-db', function () {
    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'MasterFreshDatabaseSeeder', '--force' => true]);
    return response()->json([
        'status' => 'success',
        'message' => 'Database berhasil dihapus bersih dan diisi ulang dengan data master fresh!',
        'summary' => [
            'tenants' => '2 Yayasan (Yayasan Al-Hikmah 2 & Yayasan Bina Insani Ceria)',
            'schools' => '4 Sekolah TK (TK Permata, TK Matahari, TK Bintang, TK Cendekia)',
            'classes' => '6 Kelas Rombel',
            'students' => '120 Siswa Aktif (Tepat 20 anak per kelas)',
            'users' => 'Owner SaaS, 2 Super Admin, 4 Admin, 4 Kepsek, 6 Guru, 120 Wali Murid',
            'password_all_users' => 'password',
            'features_populated' => ['Absensi 5 hari', 'Jurnal Harian', 'Perkembangan Fisik', 'Materi Pelajaran', 'Rapor Nilai', 'Media Kegiatan', 'Pengumuman', 'Domains', 'Settings']
        ]
    ]);
});

// Landing page utama SaaS Yayasan
Route::get('/', [YayasanRegistrationController::class, 'landing'])->name('home');
Route::get('/fitur', [YayasanRegistrationController::class, 'fitur'])->name('saas.fitur');
Route::get('/cara-kerja', [YayasanRegistrationController::class, 'caraKerja'])->name('saas.cara_kerja');
Route::get('/keamanan', [YayasanRegistrationController::class, 'keamanan'])->name('saas.keamanan');
Route::get('/testimoni', [YayasanRegistrationController::class, 'testimoni'])->name('saas.testimoni');
Route::get('/faq', [YayasanRegistrationController::class, 'faq'])->name('saas.faq');

// Halaman form pendaftaran yayasan baru
Route::get('/daftar-yayasan', [YayasanRegistrationController::class, 'showForm'])->name('yayasan.daftar');
Route::post('/daftar-yayasan', [YayasanRegistrationController::class, 'store'])->name('yayasan.register.store')->middleware('throttle:5,1');
Route::get('/pendaftaran-siswa', [SiswaController::class, 'createPendaftaran'])->name('pendaftaran');
Route::post('/pendaftaran-siswa', [SiswaController::class, 'storePendaftaran'])->name('pendaftaran.store');
Route::match(['get', 'post'], '/api/check-parent-email', [SiswaController::class, 'checkParentEmail'])->name('api.check_parent_email');

// Landing page publik per yayasan — diakses via /y/{kode_yayasan}
Route::get('/y/{kode_yayasan}', [LandingYayasanController::class, 'show'])->name('yayasan.landing');
// Halaman khusus pendaftaran PPDB siswa & registrasi wali murid per yayasan
Route::get('/y/{kode_yayasan}/daftar', [LandingYayasanController::class, 'pendaftaranPage'])->name('yayasan.pendaftaran');
// Halaman login khusus portal per yayasan dengan tema kustom
Route::get('/y/{kode_yayasan}/login', [LandingYayasanController::class, 'loginPage'])->name('yayasan.login');
// Daftar akun wali murid terikat ke yayasan tertentu
Route::post('/y/{kode_yayasan}/register', [LandingYayasanController::class, 'register'])->name('yayasan.register')->middleware('throttle:5,1');

Route::get('/login', function (\Illuminate\Http\Request $request) {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
    $schools = DB::table('schools')->where('status', 'aktif')->get();
    $yayasans = \App\Models\Yayasan::where('status', 'aktif')
        ->withCount('schools')
        ->latest()
        ->get()
        ->map(function ($y) {
            $y->login_url = $y->encrypted_login_url;
            return $y;
        });

    return view('welcome', compact('schools', 'settings', 'yayasans'));
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

// /register tidak lagi tersedia — alihkan langsung ke pendaftaran yayasan (/daftar-yayasan)
Route::get('/register', function () {
    return redirect('/daftar-yayasan');
})->name('register');

// TM-34: Password Reset — GET = halaman form, POST = proses kirim email
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request')->middleware('guest');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:3,1');

// TM-35: Legal Pages
Route::get('/kebijakan-privasi', function() {
    return view('legal.privacy');
})->name('legal.privacy');

Route::get('/syarat-ketentuan', function() {
    return view('legal.terms');
})->name('legal.terms');

// TM-32: Check domain endpoint for Caddy/Nginx TLS provisioning
Route::get('/check-domain', function(\Illuminate\Http\Request $request) {
    $domain = $request->query('domain');
    $exists = DB::table('domains')->where('domain', $domain)->where('is_verified', true)->exists();
    return $exists ? response('OK', 200) : response('Forbidden', 403);
});

// TM-05 & TM-20: Storage fallback asset server
Route::get('/storage/{path}', function($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*')->name('storage.fallback');

Route::get('/about', function (\Illuminate\Http\Request $request) {
    $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
    $schools = DB::table('schools')->where('status', 'aktif')->get();
    return view('welcome', compact('schools', 'settings'));
});

Route::get('/contact', function (\Illuminate\Http\Request $request) {
    $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
    $schools = DB::table('schools')->where('status', 'aktif')->get();
    return view('welcome', compact('schools', 'settings'));
});

Route::get('/registration', function (\Illuminate\Http\Request $request) {
    $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
    $schools = DB::table('schools')->where('status', 'aktif')->get();
    return view('welcome', compact('schools', 'settings'));
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // 1. DASHBOARD UMUM
    // 1. DASHBOARD UMUM
    $generalDashboardClosure = function (\Illuminate\Http\Request $request, ?string $token = null) {
        $user = Auth::user();
        if ($token) {
            $decryptedCode = \App\Models\Yayasan::decryptToken($token);
            if ($decryptedCode) {
                $foundY = \App\Models\Yayasan::where('id', $decryptedCode)->orWhere('kode_yayasan', $decryptedCode)->first();
                if ($foundY) {
                    session(['active_yayasan_id' => $foundY->id]);
                }
            }
        } else {
            if ($user->isSuperAdmin() || $user->isPlatformAdmin()) {
                $yId = session('active_school_id') ? null : ($user->getUserYayasanId() ?: session('active_yayasan_id'));
                if ($yId) {
                    $yObj = \App\Models\Yayasan::find($yId);
                    if ($yObj) {
                        return redirect()->route('superadmin.dashboard.encrypted', $yObj->encrypted_token);
                    }
                }
                return redirect()->route('superadmin.dashboard');
            } else {
                $yId = $user->getUserYayasanId();
                if ($yId) {
                    $yObj = \App\Models\Yayasan::find($yId);
                    if ($yObj) {
                        return redirect()->route('dashboard.encrypted', $yObj->encrypted_token);
                    }
                }
            }
        }

        if ($user->isSuperAdmin()) {
            if (session('active_school_id')) {
                $current_school_id = session('active_school_id');
            } else {
                $yId = session('active_yayasan_id') ?: $user->yayasan_id;
                if ($yId) {
                    $yObj = \App\Models\Yayasan::find($yId);
                    if ($yObj) {
                        return redirect()->route('superadmin.dashboard.encrypted', $yObj->encrypted_token);
                    }
                }
                return redirect()->route('superadmin.dashboard');
            }
        } else if ($user->role == 'wali_murid') {
            $anak = \App\Models\Siswa::where('email', $user->email)
                            ->orWhere('user_id', $user->id)
                            ->get();
            $school_ids = $anak->pluck('school_id')->unique();
            $pengumuman = \App\Models\Announcement::whereIn('school_id', $school_ids)
                            ->whereIn('target', ['semua', 'wali_murid'])
                            ->whereDate('tanggal_kegiatan', '>=', Carbon::today())
                            ->latest()->take(4)->get();
            return view('walimurid.dashboard', compact('anak', 'pengumuman')); 
        } else {
            $current_school_id = $user->school_id;
        }

        $school = \App\Models\School::findOrFail($current_school_id);
        $semesterAktif = Semester::where('is_active', true)->first();

        $pengumuman = \App\Models\Announcement::where('school_id', $current_school_id)
            ->whereDate('tanggal_kegiatan', '>=', Carbon::today())
            ->where(function($q) use ($user) {
                if ($user->role == 'guru' && $user->kelas_id) {
                    $q->whereNull('kelas_id')->orWhere('kelas_id', $user->kelas_id); 
                } else {
                    $q->whereNull('kelas_id');
                }
            })->latest()->get();

        $kelas_stats = Kelas::where('school_id', $current_school_id)->get()->map(function($k) use ($semesterAktif) {
            $k->jumlah_siswa = \App\Models\AnggotaKelas::where('kelas_id', $k->id)
                ->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))->count();
            return $k;
        });

        $guru_kelas = null;
        if ($user->role == 'guru' && $user->kelas_id) {
            $guru_kelas = Kelas::find($user->kelas_id);
            if($guru_kelas) {
                $guru_kelas->jumlah_siswa = \App\Models\AnggotaKelas::where('kelas_id', $user->kelas_id)
                    ->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))->count();
            }
        }

        $school->total_siswa_aktif = Siswa::where('school_id', $current_school_id)->where('status', 'aktif')->count();
        $school->total_kelas = $kelas_stats->count();

        return view('dashboard', compact('school', 'pengumuman', 'semesterAktif', 'kelas_stats', 'guru_kelas'));
    };

    Route::get('/d/{token}', $generalDashboardClosure)->name('dashboard.encrypted');
    Route::get('/dashboard', $generalDashboardClosure)->name('dashboard');

    // 1B. AKSES OWNER ONLY (MANAJEMEN YAYASAN)
    Route::middleware('role:owner')->prefix('owner')->name('owner.')->group(function () {
        Route::resource('yayasans', YayasanController::class);
        Route::post('/switch-yayasan', [YayasanController::class, 'switchYayasan'])->name('switch_yayasan');
    });

    // 1C. OWNER ONLY — Edit landing page utama/global
    Route::middleware('role:owner')->group(function () {
        Route::get('/settings/landing-page', [SettingController::class, 'editLandingPage'])->name('settings.landing.edit');
        Route::post('/settings/landing-page', [SettingController::class, 'updateLandingPage'])->name('settings.landing.update');

        // Manajemen Pendaftaran Yayasan Baru
        Route::get('/owner/yayasan-registrations', [\App\Http\Controllers\YayasanRegistrationController::class, 'index'])->name('owner.yayasan_reg.index');
        Route::post('/owner/yayasan-registrations/{id}/approve', [\App\Http\Controllers\YayasanRegistrationController::class, 'approve'])->name('owner.yayasan_reg.approve');
        Route::post('/owner/yayasan-registrations/{id}/reject', [\App\Http\Controllers\YayasanRegistrationController::class, 'reject'])->name('owner.yayasan_reg.reject');
    });

    // 2. AKSES SUPER ADMIN & OWNER
    Route::middleware('role:owner,super_admin')->prefix('superadmin')->name('superadmin.')->group(function () {
        $superAdminDashboardClosure = function (\Illuminate\Http\Request $request, ?string $token = null) {
            $user = Auth::user();
            if ($token) {
                $decryptedCode = \App\Models\Yayasan::decryptToken($token);
                if ($decryptedCode) {
                    $foundY = \App\Models\Yayasan::where('id', $decryptedCode)->orWhere('kode_yayasan', $decryptedCode)->first();
                    if ($foundY) {
                        session(['active_yayasan_id' => $foundY->id]);
                    }
                }
            } else {
                $yId = $user->getUserYayasanId() ?: session('active_yayasan_id');
                if ($yId) {
                    $yObj = \App\Models\Yayasan::find($yId);
                    if ($yObj) {
                        return redirect()->route('superadmin.dashboard.encrypted', $yObj->encrypted_token);
                    }
                }
            }

            $totalSchools  = \App\Models\School::count();
            $activeSchools = \App\Models\School::where('status', 'aktif')->count();
            $totalUsers    = \App\Models\User::whereNot('role', 'super_admin')->count();
            $totalSiswa    = DB::table('siswas')->count();
            $schools       = \App\Models\School::latest()->get();
            $pengumuman_global = \App\Models\Announcement::join('schools', 'announcements.school_id', '=', 'schools.id')
                                ->whereDate('announcements.tanggal_kegiatan', '>=', Carbon::today())
                                ->select('announcements.*', 'schools.nama_sekolah')
                                ->latest('announcements.created_at')->take(5)->get();
            return view('superadmin.dashboard', compact('totalSchools', 'activeSchools', 'totalUsers', 'totalSiswa', 'schools', 'pengumuman_global'));
        };

        Route::get('/d/{token}', $superAdminDashboardClosure)->name('dashboard.encrypted');
        Route::get('/dashboard', $superAdminDashboardClosure)->name('dashboard');

        Route::post('/switch-school', function (\Illuminate\Http\Request $request) {
            if ($request->active_school_id) session(['active_school_id' => $request->active_school_id]);
            else session()->forget('active_school_id'); 
            return back()->with('success', 'Berhasil mengubah sekolah yang dipantau.');
        })->name('switch_school');

        Route::resource('schools', SchoolController::class);
        Route::get('/users/y/{token}', [SuperUserController::class, 'index'])->name('users.encrypted');
        Route::resource('users', SuperUserController::class);
        Route::get('/schools/{id}/modules', [ModuleManagementController::class, 'manage'])->name('schools.modules');
        Route::post('/schools/{id}/modules', [ModuleManagementController::class, 'update'])->name('schools.modules.update');
    });

    // 3A. AKADEMIK & KBM - VIEW DATA
    Route::middleware('role:super_admin,admin,guru,kepsek')->group(function () {
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/{id}', [KelasController::class, 'show'])->name('kelas.show');
        Route::get('/data-siswa', [SiswaController::class, 'index'])->name('data-siswa');
        Route::get('/siswa/{id}', [SiswaController::class, 'show'])->name('siswa.show');
        Route::get('/manajemen-lms', [MateriController::class, 'indexGuru'])->name('lms.index');
        Route::get('/jurnal-harian', [JurnalHarianController::class, 'index'])->name('jurnal.index');
        Route::get('/jurnal-harian/materi/{materi_id}/kelas/{kelas_id}', [JurnalHarianController::class, 'showKelas'])->name('jurnal.kelas.show');
        Route::get('/jurnal-harian/download-zip', [JurnalHarianController::class, 'downloadZip'])->name('jurnal.zip');
        Route::get('/perkembangan-physic', [PerkembanganFisikController::class, 'index'])->name('perkembangan.index');
        Route::get('/perkembangan-fisik', [PerkembanganFisikController::class, 'index']); 
        Route::get('/perkembangan-fisik/{id}', [PerkembanganFisikController::class, 'show'])->name('perkembangan.show');
        Route::get('/rapor/{id}', [RaporController::class, 'show'])->name('rapor.detail');
        Route::get('/arsip-rapor', [ArsipRaporController::class, 'index'])->name('arsip.index');

        Route::get('/absensi-harian', function (\Illuminate\Http\Request $request) {
            $user = auth()->user();
            $current_school_id = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
            $semesterAktif = Semester::where('is_active', true)->first();
            $query = Siswa::where('school_id', $current_school_id)->where('status', 'aktif');

            if ($user->role === 'guru' && $user->kelas_id) {
                $query->whereHas('kelas', function($q) use ($user, $semesterAktif) {
                    $q->where('anggota_kelas.kelas_id', $user->kelas_id);
                    if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                });
                $list_kelas = collect();
            } else {
                $list_kelas = Kelas::where('school_id', $current_school_id)->get();
                if ($request->has('kelas_id') && $request->kelas_id != '' && $request->kelas_id != 'all') {
                    $query->whereHas('kelas', function($q) use ($request, $semesterAktif) {
                        $q->where('anggota_kelas.kelas_id', $request->kelas_id);
                        if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                    });
                }
            }

            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%");
                });
            }

            $siswas = $query->with('kelas')->get();
            return view('absensi.absensi_harian', compact('siswas', 'list_kelas'));
        })->name('absensi.harian');

        Route::get('/rekap-absensi', function (\Illuminate\Http\Request $request) {
            $user = auth()->user();
            $current_school_id = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
            $semesterAktif = Semester::where('is_active', true)->first();
            $school = \App\Models\School::find($current_school_id);
            
            $mulai = $request->query('tgl_mulai', date('Y-m-01'));
            $selesai = $request->query('tgl_selesai', date('Y-m-d'));
            $kelas_id = $request->query('kelas_id');
            $search = $request->query('search');

            if ($kelas_id && $kelas_id != 'all') {
                $target_kelas = Kelas::find($kelas_id);
                if ($target_kelas && $target_kelas->school) {
                    $school = $target_kelas->school;
                }
            }
            if (!$school && $user->school) {
                $school = $user->school;
            }
            if (!$school) {
                $school = \App\Models\School::first();
            }

            $list_kelas = Kelas::where('school_id', $school->id ?? $current_school_id)->get();

            $rekap = []; $range_tanggal = [];
            if ($mulai && $selesai) {
                $period = new \DatePeriod(new \DateTime($mulai), new \DateInterval('P1D'), (new \DateTime($selesai))->modify('+1 day'));
                foreach ($period as $date) {
                    // Exclude Weekend / Tanggal Merah (Sabtu=6, Minggu=7)
                    if ($date->format('N') < 6) {
                        $range_tanggal[] = $date->format('Y-m-d');
                    }
                }
                $query = Siswa::where('school_id', $school->id ?? $current_school_id)->where('status', 'aktif');

                if ($user->role === 'guru' && $user->kelas_id) {
                    $query->whereHas('kelas', function($q) use ($user, $semesterAktif) {
                        $q->where('anggota_kelas.kelas_id', $user->kelas_id);
                        if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                    });
                } elseif ($kelas_id && $kelas_id != 'all') {
                    $query->whereHas('kelas', function($q) use ($kelas_id, $semesterAktif) {
                        $q->where('anggota_kelas.kelas_id', $kelas_id);
                        if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                    });
                }

                if ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%");
                    });
                }

                $siswas = $query->with('kelas')->get();
                foreach ($siswas as $siswa) {
                    $data_absen = [];
                    $total_h = 0; $total_i = 0; $total_s = 0; $total_a = 0;
                    foreach ($range_tanggal as $tgl) {
                        $cek = \App\Models\Absensi::where('siswa_id', $siswa->id)->where('tanggal', $tgl)->first();
                        $st = $cek ? $cek->status : '-';
                        $data_absen[$tgl] = $st;
                        if ($st === 'hadir') $total_h++;
                        elseif ($st === 'izin') $total_i++;
                        elseif ($st === 'sakit') $total_s++;
                        elseif ($st === 'alfa') $total_a++;
                    }
                    $rekap[] = [
                        'siswa_id' => $siswa->id,
                        'nama' => $siswa->nama_lengkap,
                        'nik' => $siswa->nik,
                        'nisn' => $siswa->nisn ?? $siswa->nik,
                        'kelas' => $siswa->kelas->first()->nama_kelas ?? '-',
                        'absen' => $data_absen,
                        'total_h' => $total_h,
                        'total_i' => $total_i,
                        'total_s' => $total_s,
                        'total_a' => $total_a,
                    ];
                }
            }
            return view('absensi.rekap', compact('rekap', 'mulai', 'selesai', 'range_tanggal', 'list_kelas', 'kelas_id', 'search', 'school'));
        })->name('absensi.rekap');

        Route::get('/isi-rapor', function (\Illuminate\Http\Request $request) {
            $user = auth()->user();
            $current_school_id = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
            $semesterAktif = Semester::where('is_active', true)->first();
            $query = Siswa::where('school_id', $current_school_id)->where('status', 'aktif');
            if ($user->role === 'guru' && $user->kelas_id) {
                $query->whereHas('kelas', function($q) use ($user, $semesterAktif) {
                    $q->where('anggota_kelas.kelas_id', $user->kelas_id);
                    if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                });
                $list_kelas = []; 
            } else {
                $list_kelas = Kelas::where('school_id', $current_school_id)->get();
                if ($request->has('kelas_id') && $request->kelas_id != '' && $request->kelas_id != 'all') {
                    $query->whereHas('kelas', function($q) use ($request, $semesterAktif) {
                        $q->where('anggota_kelas.kelas_id', $request->kelas_id);
                        if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                    });
                }
            }
            $siswas = $query->get();
            foreach ($siswas as $siswa) {
                $siswa->absensi_sakit = \App\Models\Absensi::where('siswa_id', $siswa->id)->where('status', 'sakit')->count();
                $siswa->absensi_izin = \App\Models\Absensi::where('siswa_id', $siswa->id)->where('status', 'izin')->count();
                $siswa->absensi_alfa = \App\Models\Absensi::where('siswa_id', $siswa->id)->where('status', 'alfa')->count();
                $perkembangan = \App\Models\PerkembanganFisik::where('siswa_id', $siswa->id)->latest('tgl_periksa')->first();
                $siswa->tinggi_badan_db = $perkembangan ? $perkembangan->tinggi_badan : null;
                $siswa->berat_badan_db = $perkembangan ? $perkembangan->berat_badan : null;
                $siswa->rapor_aktif = $semesterAktif ? Rapor::where('siswa_id', $siswa->id)->where('semester_id', $semesterAktif->id)->first() : null;

                $jurnalsWithFoto = \App\Models\JurnalHarian::where('siswa_id', $siswa->id)
                    ->whereNotNull('foto')
                    ->where('foto', '!=', '')
                    ->select('id', 'tanggal', 'tema', 'kegiatan', 'foto')
                    ->orderBy('tanggal', 'desc')
                    ->get();

                $siswa->jurnal_fotos = $jurnalsWithFoto->map(function($j) {
                    return [
                        'id' => $j->id,
                        'tanggal' => $j->tanggal ? \Carbon\Carbon::parse($j->tanggal)->translatedFormat('d M Y') : '',
                        'tema' => $j->tema,
                        'kegiatan' => \Illuminate\Support\Str::limit($j->kegiatan, 60),
                        'foto' => $j->foto,
                        'foto_url' => $j->foto_url
                    ];
                })->values();
            }
            return view('rapor.isi_rapor', compact('siswas', 'list_kelas'));
        })->name('rapor.isi');

        Route::get('/cetak-rapor', function (\Illuminate\Http\Request $request) {
            $user = auth()->user();
            $current_school_id = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
            $semesterAktif = Semester::where('is_active', true)->first();
            $query = Siswa::where('school_id', $current_school_id)->where('status', 'aktif');
            if ($user->role === 'guru' && $user->kelas_id) {
                $query->whereHas('kelas', function($q) use ($user, $semesterAktif) {
                    $q->where('anggota_kelas.kelas_id', $user->kelas_id);
                    if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                });
                $list_kelas = []; 
            } else {
                $list_kelas = Kelas::where('school_id', $current_school_id)->get();
                if ($request->has('kelas_id') && $request->kelas_id != '' && $request->kelas_id != 'all') {
                    $query->whereHas('kelas', function($q) use ($request, $semesterAktif) {
                        $q->where('anggota_kelas.kelas_id', $request->kelas_id);
                        if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                    });
                }
            }
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%");
                });
            }
            $siswas = $query->with('kelas')->get();
            return view('rapor.cetak_rapor', compact('siswas', 'list_kelas'));
        })->name('rapor.cetak');
    });

    // 3B. AKADEMIK & KBM - ACTION/UBAH DATA
    Route::middleware('role:super_admin,admin,guru')->group(function () {
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store'); 
        Route::post('/kelas/{id}/move', [KelasController::class, 'moveStudents'])->name('kelas.move'); 
        Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy'); 
        Route::delete('/kelas/{kelas_id}/siswa/{siswa_id}', [KelasController::class, 'removeStudent'])->name('kelas.remove_student');
        Route::post('/data-siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::post('/data-siswa/set-kelas', [SiswaController::class, 'setKelas'])->name('siswa.set-kelas');
        Route::post('/data-siswa/import-nisn', [SiswaController::class, 'importNisn'])->name('siswa.import-nisn');
        Route::post('/data-siswa/tautkan-ortu', [SiswaController::class, 'tautkanOrtu'])->name('siswa.tautkan-ortu');
        Route::post('/siswa/{id}/terima', [SiswaController::class, 'terimaPendaftaran'])->name('siswa.terima');
        Route::post('/jurnal-harian/materi', [JurnalHarianController::class, 'storeMateri'])->name('jurnal.materi.store');
        Route::get('/jurnal-harian/materi/{id}', [JurnalHarianController::class, 'show'])->name('jurnal.show');
        Route::get('/jurnal-media/{type}/{filename}', [JurnalHarianController::class, 'serveMedia'])->name('jurnal.media');
        // TM-30: Route destroy SENGAJA TIDAK ADA untuk data transaksional.
        // Jurnal, Absensi, Perkembangan, Rapor tidak boleh dihapus permanen.
        // Koreksi dilakukan via EDIT (audit trail di record_revisions).
        // --- ROUTE DELETE BERIKUT DINONAKTIFKAN ---
        // Route::delete('/jurnal-harian/materi/{id}', [...]);
        // Route::delete('/jurnal-harian/siswa/{id}', [...]);
        // (perkembangan-fisik delete juga tidak didaftarkan)
        Route::post('/jurnal-harian/{id}/comment', [JurnalHarianController::class, 'storeMateriComment'])->name('jurnal.comment.store');
        Route::post('/perkembangan-fisik', [PerkembanganFisikController::class, 'store'])->name('perkembangan.store');
        Route::post('/isi-rapor', [RaporController::class, 'store']);
        Route::post('/arsip-rapor', [ArsipRaporController::class, 'store'])->name('arsip.store');
        Route::delete('/arsip-rapor/{id}', [ArsipRaporController::class, 'destroy'])->name('arsip.destroy');
        Route::post('/absensi-harian', [KelasController::class, 'storeAbsensi'])->name('absensi.store');
        
        // Manajemen Materi (Guru)
        Route::get('/materi/guru', [MateriController::class, 'indexGuru'])->name('materi.guru.index');
        Route::get('/materi/guru/create', [MateriController::class, 'createGuru'])->name('materi.guru.create');
        Route::post('/materi/guru', [MateriController::class, 'storeGuru'])->name('materi.guru.store');
    });

    // 4. AKSES ADMIN & SUPER ADMIN ONLY
    Route::middleware('role:super_admin,admin')->group(function () {
        // Landing page yayasan — hanya super_admin (non-owner) yang bisa edit
        Route::get('/superadmin/landing-page', [SettingController::class, 'editYayasanLanding'])->name('superadmin.landing.edit');
        Route::post('/superadmin/landing-page', [SettingController::class, 'updateYayasanLanding'])->name('superadmin.landing.update');
        
        Route::get('/kenaikan-kelas', [ProsesKelasController::class, 'index'])->name('kenaikan.index');
        Route::post('/kenaikan-kelas/proses', [ProsesKelasController::class, 'proses'])->name('kenaikan.proses');
        Route::get('/admin/template', [RaporTemplateController::class, 'index']);
        Route::post('/admin/template', [RaporTemplateController::class, 'store']);
        Route::post('/admin/template/{id}/activate', [RaporTemplateController::class, 'activate']);
        Route::post('/admin/template/{id}/opsi-mulok', [RaporTemplateController::class, 'updateOpsiMulok']);
        Route::get('/admin/template/{id}', [RaporTemplateController::class, 'show']);
        Route::post('/admin/template/{id}/items', [RaporTemplateController::class, 'storeItem']);
        Route::delete('/admin/template/items/{id}', [RaporTemplateController::class, 'destroyItem']);
        Route::post('/rapor/toggle-akses', [RaporController::class, 'toggleAkses'])->name('rapor.toggle');
        Route::post('/pengumuman', [AnnouncementController::class, 'store'])->name('pengumuman.store');
        Route::delete('/pengumuman/{id}', [AnnouncementController::class, 'destroy'])->name('pengumuman.destroy');

        Route::get('/pengaturan', function () {
            $user = auth()->user();
            $current_school_id = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;

            if ($current_school_id) {
                $school = \App\Models\School::find($current_school_id);
                $gurus = User::where('role', 'guru')->where('school_id', $current_school_id)->get();
                $list_kelas = Kelas::where('school_id', $current_school_id)->get();
            } else {
                $school = null;
                $gurus = collect();
                $list_kelas = collect();
            }

            $semesters = Semester::orderBy('id', 'desc')->get();
            $belumPermanen = 0;
            $semesterAktif = Semester::where('is_active', true)->first();
            
            if ($semesterAktif && $current_school_id) {
                $totalSiswaAktif = Siswa::whereHas('kelas', function($q) use ($semesterAktif) {
                    $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                })->where('school_id', $current_school_id)->where('status', 'aktif')->count();

                $totalRaporPermanen = Rapor::where('school_id', $current_school_id)->where('semester_id', $semesterAktif->id)->where('status', 'permanen')->count();
                $belumPermanen = $totalSiswaAktif - $totalRaporPermanen;
                if ($belumPermanen < 0) $belumPermanen = 0;
            }
            return view('pengaturan', compact('school', 'gurus', 'list_kelas', 'semesters', 'belumPermanen'));
        })->name('pengaturan');

        // RUTE SIMPAN PENGATURAN KUOTA
        Route::post('/pengaturan/update-pendaftaran', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'school_id' => 'required',
                'kuota_tk_pagi' => 'required|integer',
                'kuota_tk_siang' => 'required|integer',
                'kuota_paud' => 'required|integer',
                'max_indent_years' => 'required|integer|min:1|max:10',
            ]);
            \App\Models\School::where('id', $request->school_id)->update([
                'kuota_tk_pagi' => $request->kuota_tk_pagi,
                'kuota_tk_siang' => $request->kuota_tk_siang,
                'kuota_paud' => $request->kuota_paud,
                'max_indent_years' => $request->max_indent_years,
            ]);
            return back()->with('success', 'Pengaturan pendaftaran & kuota berhasil diperbarui!');
        })->name('pengaturan.update-pendaftaran');

        Route::post('/pengaturan/semester', function (\Illuminate\Http\Request $request) {
            $request->validate(['tahun_ajaran' => 'required', 'jenis' => 'required']);
            Semester::create(['tahun_ajaran' => $request->tahun_ajaran, 'jenis' => $request->jenis, 'is_active' => false]);
            return back()->with('success', 'Semester baru berhasil dibuat!');
        })->name('pengaturan.semester.store');

        Route::post('/pengaturan/semester/aktifkan', function (\Illuminate\Http\Request $request) {
            Semester::query()->update(['is_active' => false]);
            $sem = Semester::findOrFail($request->semester_id);
            $sem->update(['is_active' => true]);
            session(['active_semester_id' => $sem->id]);
            return back()->with('success', 'Sukses mengaktifkan Tahun Ajaran baru!');
        })->name('pengaturan.semester.aktifkan');

        Route::post('/pengaturan/semester/switch', function (\Illuminate\Http\Request $request) {
            $request->validate(['semester_id' => 'required|exists:semesters,id']);
            session(['active_semester_id' => $request->semester_id]);
            return back()->with('success', 'Berhasil beralih konteks semester!');
        })->name('pengaturan.semester.switch');

        Route::post('/pengaturan/update-guru', function (\Illuminate\Http\Request $request) {
            $request->validate(['user_id' => 'required', 'kelas_id' => 'nullable']);
            User::findOrFail($request->user_id)->update(['kelas_id' => $request->kelas_id]);
            return back()->with('success', 'Penugasan kelas Wali Guru berhasil diperbarui!');
        })->name('pengaturan.update-guru');

        Route::post('/pengaturan/menu-access', [KelasController::class, 'updateMenuAccess'])->name('pengaturan.update-menu-access');
    });

    // AKSES KEPSEK & ADMIN
    Route::middleware('role:super_admin,admin,kepsek')->group(function () {
        Route::get('/materi/persetujuan', [MateriController::class, 'indexKepsek'])->name('materi.kepsek.index');
        Route::post('/materi/{id}/approve', [MateriController::class, 'approve'])->name('materi.approve');
        Route::post('/materi/{id}/reject', [MateriController::class, 'reject'])->name('materi.reject');
    });

    // 5. AKSES WALI MURID (Ortu Multi-Anak TM-18 & TM-37)
    Route::middleware('role:wali_murid')->group(function () {
        Route::post('/switch-child', [\App\Http\Controllers\WaliMuridController::class, 'switchChild'])->name('switch-child');
        Route::post('/tautkan-anak', [\App\Http\Controllers\WaliMuridController::class, 'storeTautkanAnak'])->name('tautkan-anak');
        Route::get('/rapor-anak/{id?}', [\App\Http\Controllers\WaliMuridController::class, 'rapor'])->name('walimurid.rapor');
        Route::get('/perkembangan-anak/{id?}', [\App\Http\Controllers\WaliMuridController::class, 'perkembangan'])->name('walimurid.perkembangan');
        Route::get('/jurnal-anak/{id?}', [\App\Http\Controllers\WaliMuridController::class, 'jurnal'])->name('walimurid.jurnal');
        
        Route::get('/materi-anak', [JurnalHarianController::class, 'waliMuridMateri'])->name('walimurid.materi');
        Route::post('/materi-anak/{id}/read', [JurnalHarianController::class, 'markMateriRead'])->name('walimurid.materi.read');
        Route::post('/materi-anak/{id}/comment', [JurnalHarianController::class, 'storeMateriComment'])->name('walimurid.materi.comment');

        // Materi Publik (Orang Tua)
        Route::get('/materi', [MateriController::class, 'indexOrtu'])->name('materi.ortu.index');
    });

    Route::get('/magic-fix-menu', function() {
        // 1. Update menu yang sudah ada biar Admin & Kepsek bisa ikut akses
        DB::table('modules')->whereIn('name', ['Data Siswa', 'Manajemen Kelas', 'Isi Rapor', 'Cetak Rapor', 'Pengumuman'])
        ->update(['role_access' => 'admin,guru,kepsek']);

        DB::table('modules')->whereIn('name', ['Perkembangan Fisik', 'Absensi Harian', 'Rekap Absensi'])
        ->update(['role_access' => 'admin,guru,kepsek']);

        DB::table('modules')->whereIn('name', ['Pengaturan Sekolah', 'Kenaikan Kelas'])
        ->update(['role_access' => 'admin,kepsek']);

        // 2. Tambahkan menu-menu yang sebelumnya Ghaib / Belum ada di DB
        $missing = [
            ['name' => 'Manajemen Materi', 'route_name' => 'lms.index', 'icon' => 'solar:folder-with-files-bold-duotone', 'category' => 'KEGIATAN', 'role_access' => 'admin,guru,kepsek'],
            ['name' => 'Jurnal Harian', 'route_name' => 'jurnal.index', 'icon' => 'solar:book-bookmark-bold-duotone', 'category' => 'KEGIATAN', 'role_access' => 'admin,guru,kepsek'],
            ['name' => 'Arsip Rapor', 'route_name' => 'arsip.index', 'icon' => 'solar:folder-with-files-bold-duotone', 'category' => 'RAPOR', 'role_access' => 'admin,guru,kepsek'],
            ['name' => 'Template Rapor', 'route_name' => 'template.index', 'icon' => 'solar:document-add-bold-duotone', 'category' => 'SISTEM', 'role_access' => 'admin,kepsek'],
            ['name' => 'Rapor Anak', 'route_name' => 'walimurid.rapor', 'icon' => 'solar:notebook-bold-duotone', 'category' => 'AKADEMIK ANAK', 'role_access' => 'wali_murid'],
            ['name' => 'Laporan Fisik', 'route_name' => 'walimurid.perkembangan', 'icon' => 'solar:pulse-bold-duotone', 'category' => 'AKADEMIK ANAK', 'role_access' => 'wali_murid'],
        ];

        foreach($missing as $m) {
            $exists = DB::table('modules')->where('name', $m['name'])->first();
            if(!$exists) {
                $m['created_at'] = now();
                $m['updated_at'] = now();
                DB::table('modules')->insert($m);
            }
        }

        // 3. Sambungkan semua menu ke sekolah dan NYALAKAN (ON) semua toggle-nya!
        $schools = DB::table('schools')->get();
        $modules = DB::table('modules')->get();
        foreach($schools as $school) {
            foreach($modules as $mod) {
                $exists = DB::table('school_modules')
                    ->where('school_id', $school->id)
                    ->where('module_id', $mod->id)
                    ->first();
                    
                if(!$exists) {
                    DB::table('school_modules')->insert([
                        'school_id' => $school->id,
                        'module_id' => $mod->id,
                        'is_active_for_guru' => 1,
                        'is_active_for_admin' => 1,
                        'is_active_for_kepsek' => 1,
                        'is_active_for_wali_murid' => 1,
                    ]);
                } else {
                    DB::table('school_modules')->where('id', $exists->id)->update([
                        'is_active_for_guru' => 1,
                        'is_active_for_admin' => 1,
                        'is_active_for_kepsek' => 1,
                        'is_active_for_wali_murid' => 1,
                    ]);
                }
            }
        }
        return "🔥 DATABASE MENU BERHASIL DIPERBAIKI! Silakan kembali ke web dan refresh halamannya.";
    });
});