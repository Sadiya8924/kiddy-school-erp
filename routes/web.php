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
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ProsesKelasController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\SiswaController; 
use App\Http\Controllers\RaporTemplateController; 
use App\Http\Controllers\ArsipRaporController;
use App\Http\Controllers\JurnalHarianController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MateriController;
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| PUBLIC & AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('pengumuman.index');
Route::get('/', function (\Illuminate\Http\Request $request) {
    $schools = DB::table('schools')->where('status', 'aktif')->get();
    return view('welcome', compact('schools'));
});
Route::get('/pendaftaran-siswa', [SiswaController::class, 'createPendaftaran'])->name('pendaftaran');
Route::post('/pendaftaran-siswa', [SiswaController::class, 'storePendaftaran'])->name('pendaftaran.store');

Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', fn() => view('auth.register'));
Route::post('/register', [AuthController::class, 'register']);


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 1. DASHBOARD UMUM
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        if ($user->role === 'super_admin') {
            if (session('active_school_id')) {
                $current_school_id = session('active_school_id');
            } else {
                return redirect('/superadmin/dashboard');
            }
        } else if ($user->role == 'wali_murid') {
            $anak = \App\Models\Siswa::where('email', $user->email)->get();
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
    })->name('dashboard');

    // 2. AKSES SUPER ADMIN ONLY
    Route::middleware('role:super_admin')->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', function () {
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
        })->name('dashboard');

        Route::post('/switch-school', function (\Illuminate\Http\Request $request) {
            if ($request->active_school_id) session(['active_school_id' => $request->active_school_id]);
            else session()->forget('active_school_id'); 
            return back()->with('success', 'Berhasil mengubah sekolah yang dipantau.');
        })->name('switch_school');

        Route::resource('schools', SchoolController::class);
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

        Route::get('/absensi-harian', function () {
            $user = auth()->user();
            $current_school_id = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
            $semesterAktif = Semester::where('is_active', true)->first();
            $query = Siswa::where('school_id', $current_school_id)->where('status', 'aktif');
            if ($user->kelas_id && !$user->isSuperAdmin()) {
                $query->whereHas('kelas', function($q) use ($user, $semesterAktif) {
                    $q->where('anggota_kelas.kelas_id', $user->kelas_id);
                    if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                });
            }
            $siswas = $query->get();
            return view('absensi.absensi_harian', compact('siswas'));
        })->name('absensi.harian');

        Route::get('/rekap-absensi', function (\Illuminate\Http\Request $request) {
            $user = auth()->user();
            $current_school_id = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
            $semesterAktif = Semester::where('is_active', true)->first();
            $mulai = $request->query('tgl_mulai');
            $selesai = $request->query('tgl_selesai');
            $rekap = []; $range_tanggal = [];
            if ($mulai && $selesai) {
                $period = new \DatePeriod(new \DateTime($mulai), new \DateInterval('P1D'), (new \DateTime($selesai))->modify('+1 day'));
                foreach ($period as $date) $range_tanggal[] = $date->format('Y-m-d');
                $query = Siswa::where('school_id', $current_school_id)->where('status', 'aktif');
                if ($user->kelas_id && !$user->isSuperAdmin()) {
                    $query->whereHas('kelas', function($q) use ($user, $semesterAktif) {
                        $q->where('anggota_kelas.kelas_id', $user->kelas_id);
                        if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                    });
                }
                $siswas = $query->get();
                foreach ($siswas as $siswa) {
                    $data_absen = [];
                    foreach ($range_tanggal as $tgl) {
                        $cek = \App\Models\Absensi::where('siswa_id', $siswa->id)->where('tanggal', $tgl)->first();
                        $data_absen[$tgl] = $cek ? $cek->status : '-';
                    }
                    $rekap[] = ['nama' => $siswa->nama_lengkap, 'absen' => $data_absen];
                }
            }
            return view('absensi.rekap', compact('rekap', 'mulai', 'selesai', 'range_tanggal'));
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
        Route::delete('/jurnal-harian/materi/{id}', [JurnalHarianController::class, 'destroyMateri'])->name('jurnal.materi.destroy');
        Route::post('/jurnal-harian/siswa', [JurnalHarianController::class, 'storeJurnalSiswa'])->name('jurnal.siswa.store');
        Route::delete('/jurnal-harian/siswa/{id}', [JurnalHarianController::class, 'destroyJurnalSiswa'])->name('jurnal.siswa.destroy');
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
        Route::get('/settings/landing-page', [SettingController::class, 'editLandingPage'])->name('settings.landing.edit');
        Route::post('/settings/landing-page', [SettingController::class, 'updateLandingPage'])->name('settings.landing.update');
        
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
            if ($user->isSuperAdmin()) {
                if (session('active_school_id')) $current_school_id = session('active_school_id');
                else return redirect('/superadmin/dashboard')->with('error', 'Silakan pilih sekolah yang ingin dipantau terlebih dahulu di pojok kanan atas.');
            } else {
                $current_school_id = $user->school_id;
            }

            $school = \App\Models\School::findOrFail($current_school_id);
            $gurus = User::where('role', 'guru')->where('school_id', $current_school_id)->get();
            $list_kelas = Kelas::where('school_id', $current_school_id)->get(); 
            $semesters = Semester::orderBy('id', 'desc')->get();

            $belumPermanen = 0;
            $semesterAktif = Semester::where('is_active', true)->first();
            
            if ($semesterAktif) {
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
            return back()->with('success', 'Sukses mengaktifkan Tahun Ajaran baru!');
        })->name('pengaturan.semester.aktifkan');

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

    // 5. AKSES WALI MURID (Ortu)
    Route::middleware('role:wali_murid')->group(function () {
        Route::get('/rapor-anak/{id?}', function($id = null) {
            $user = auth()->user();
            if ($id) {
                $siswa = \App\Models\Siswa::where('id', $id)->where('email', $user->email)->firstOrFail();
            } else {
                $siswa = \App\Models\Siswa::where('email', $user->email)->first();
                if (!$siswa) return redirect('/dashboard')->with('error', 'Belum ada data anak.');
            }
            $semesterAktif = \App\Models\Semester::where('is_active', true)->first();
            $rapor = $semesterAktif ? \App\Models\Rapor::where('siswa_id', $siswa->id)->where('semester_id', $semesterAktif->id)->first() : null;
            return view('walimurid.rapor', compact('siswa', 'rapor', 'semesterAktif'));
        })->name('walimurid.rapor');
        
        Route::get('/perkembangan-anak/{id?}', function($id = null) {
            $user = auth()->user();
            if ($id) {
                $siswa = \App\Models\Siswa::where('id', $id)->where('email', $user->email)->firstOrFail();
            } else {
                $siswa = \App\Models\Siswa::where('email', $user->email)->first();
                if (!$siswa) return redirect('/dashboard')->with('error', 'Belum ada data anak.');
            }
            $riwayat = \App\Models\PerkembanganFisik::where('siswa_id', $siswa->id)->orderBy('tgl_periksa', 'desc')->get();
            return view('walimurid.perkembangan', compact('siswa', 'riwayat'));
        })->name('walimurid.perkembangan');
        
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