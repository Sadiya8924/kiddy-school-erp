<aside class="left-sidebar">
    <div>
        @php
            $sidebarYayasan = null;
            if (auth()->check()) {
                $u = auth()->user();
                if (!empty($u->yayasan_id)) {
                    $sidebarYayasan = \App\Models\Yayasan::find($u->yayasan_id);
                } elseif (!empty($u->school_id)) {
                    $sch = \App\Models\School::find($u->school_id);
                    if ($sch && $sch->yayasan_id) {
                        $sidebarYayasan = \App\Models\Yayasan::find($sch->yayasan_id);
                    }
                } elseif ($u->role == 'wali_murid') {
                    $anakSiswa = \App\Models\Siswa::where('email', $u->email)->orWhere('user_id', $u->id)->first();
                    if ($anakSiswa && $anakSiswa->school_id) {
                        $sch = \App\Models\School::find($anakSiswa->school_id);
                        if ($sch && $sch->yayasan_id) {
                            $sidebarYayasan = \App\Models\Yayasan::find($sch->yayasan_id);
                        }
                    }
                }
                if (!$sidebarYayasan) {
                    $sessId = session('active_yayasan_id');
                    if ($sessId) {
                        $sidebarYayasan = \App\Models\Yayasan::find($sessId);
                    }
                }
                if (!$sidebarYayasan) {
                    $sidebarYayasan = \App\Models\Yayasan::where('status', 'aktif')->first();
                }
            }
            $sidebarTitle = $sidebarYayasan ? $sidebarYayasan->nama_yayasan : \App\Models\Setting::get('site_title', 'TK CERIA');
            $sidebarYayasanToken = $sidebarYayasan ? $sidebarYayasan->encrypted_token : null;

            $encLink = function($url) use ($sidebarYayasanToken) {
                if (!$sidebarYayasanToken || empty($url) || $url === 'javascript:void(0)') return $url;
                if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
                    $path = parse_url($url, PHP_URL_PATH);
                    $query = parse_url($url, PHP_URL_QUERY);
                    if ($path) $url = $path . ($query ? '?' . $query : '');
                }
                if (str_starts_with($url, '/y/')) return $url;
                if (str_starts_with($url, '/d/')) return $url;
                return '/y/' . $sidebarYayasanToken . (str_starts_with($url, '/') ? $url : '/' . $url);
            };
        @endphp

        <div class="brand-logo d-flex align-items-center justify-content-between px-3 py-3 border-bottom border-light">
            <a href="{{ $encLink('/dashboard') }}" class="logo-img text-decoration-none d-flex align-items-center gap-2 overflow-hidden me-2">
                <div class="rounded-circle theme-brand-bg-soft theme-text-primary p-2 d-flex align-items-center justify-content-center shrink-0" style="width: 36px; height: 36px;">
                    <iconify-icon icon="solar:diploma-bold-duotone" class="fs-5 theme-brand-icon"></iconify-icon>
                </div>
                <div class="lh-sm overflow-hidden">
                    <span class="fw-bold d-block theme-brand-title text-uppercase text-truncate" style="font-size: 13px; letter-spacing: -0.01em;" title="{{ $sidebarTitle }}">
                        {{ $sidebarTitle }}
                    </span>
                    <span class="text-muted d-block text-truncate" style="font-size: 10.5px; font-weight: 500;">Portal ERP Yayasan</span>
                </div>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                @php 
                    $user = Auth::user(); 
                    $akademik_roles = ['owner', 'super_admin', 'admin', 'guru', 'kepsek'];

                    $allowed_modules = [];
                    $userSchoolId = $user ? $user->school_id : null;
                    if ($user && !$userSchoolId && $user->role === 'wali_murid') {
                        $anakSiswa = \App\Models\Siswa::where('email', $user->email)->orWhere('user_id', $user->id)->first();
                        if ($anakSiswa) {
                            $userSchoolId = $anakSiswa->school_id;
                        }
                    }
                    if ($user && !$userSchoolId) {
                        $userSchoolId = session('active_school_id');
                    }

                    // Tarik hak akses untuk Admin, Guru, Kepsek, & Wali Murid
                    if ($user && in_array($user->role, ['admin', 'guru', 'kepsek', 'wali_murid']) && $userSchoolId) {
                        $role_column = 'is_active_for_' . $user->role;
                        
                        $allowed_modules = \Illuminate\Support\Facades\DB::table('school_modules')
                            ->join('modules', 'school_modules.module_id', '=', 'modules.id')
                            ->where('school_modules.school_id', $userSchoolId)
                            ->where('school_modules.' . $role_column, 1)
                            ->pluck('modules.name')
                            ->toArray();
                    }

                    // Fungsi penentu menu muncul atau tidak
                    $hasMenu = function($menuName) use ($allowed_modules, $user) {
                        if (!$user) return false;
                        if ($user->isSuperAdmin() || $user->isOwner()) return true; // Super admin & owner bebas
                        
                        // Cek untuk Admin, Guru, Kepsek, & Wali Murid
                        foreach ($allowed_modules as $allowed) {
                            if (strtolower(trim($allowed)) == strtolower(trim($menuName))) {
                                return true;
                            }
                        }
                        return false;
                    };
                @endphp

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">BERANDA</span>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('dashboard') || request()->is('d/*') || request()->is('superadmin/dashboard') || request()->is('superadmin/d/*') ? 'active' : '' }}" 
                       href="{{ $sidebarYayasan ? ($user->isSuperAdmin() ? route('superadmin.dashboard.encrypted', $sidebarYayasan->encrypted_token) : route('dashboard.encrypted', $sidebarYayasan->encrypted_token)) : ($user->isSuperAdmin() ? route('superadmin.dashboard') : route('dashboard')) }}">
                        <span><iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                @if($user && ($user->isOwner() || $user->isPureSuperAdmin()))
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                        <span class="hide-menu">WEBSITE</span>
                    </li>

                    @if($user->isOwner())
                    {{-- Owner: edit landing page utama/global --}}
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('settings/landing-page*') ? 'active' : '' }}" href="{{ $encLink(route('settings.landing.edit')) }}">
                            <span><iconify-icon icon="solar:monitor-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Edit Landing Page Utama</span>
                        </a>
                    </li>
                    {{-- Owner: Pendaftaran Yayasan Baru --}}
                    <li class="sidebar-item">
                        @php $regCount = \App\Models\YayasanRegistration::where('status','menunggu')->count(); @endphp
                        <a class="sidebar-link {{ request()->is('owner/yayasan-registrations*') ? 'active' : '' }}" href="{{ $encLink(route('owner.yayasan_reg.index')) }}">
                            <span><iconify-icon icon="solar:buildings-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">
                                Pendaftaran Yayasan
                                @if($regCount > 0)
                                    <span class="badge rounded-pill ms-1" style="background:#f59e0b; color:#fff; font-size:10px;">{{ $regCount }}</span>
                                @endif
                            </span>
                        </a>
                    </li>
                    @endif

                    @if($user->isPureSuperAdmin())
                    {{-- Super Admin: edit landing page yayasan sendiri --}}
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('superadmin/landing-page*') ? 'active' : '' }}" href="{{ $encLink(route('superadmin.landing.edit')) }}">
                            <span><iconify-icon icon="solar:monitor-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Edit Landing Page Yayasan</span>
                        </a>
                    </li>
                    @endif
                @endif


                @if($user && $user->isSuperAdmin())
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                        <span class="hide-menu">MASTER DATA PUSAT</span>
                    </li>
                    @if($user->isOwner())
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*owner/yayasans*') ? 'active' : '' }}" href="{{ $encLink(route('owner.yayasans.index')) }}">
                            <span><iconify-icon icon="solar:buildings-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Data Yayasan</span>
                        </a>
                    </li>
                    @endif
                    @if(!$user->isOwner())
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*superadmin/schools*') ? 'active' : '' }}" href="{{ $encLink(route('superadmin.schools.index')) }}">
                            <span><iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Data Sekolah (TK)</span>
                        </a>
                    </li>
                    @endif
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*superadmin/users*') ? 'active' : '' }}" href="{{ $sidebarYayasan ? route('superadmin.users.encrypted', $sidebarYayasan->encrypted_token) : $encLink(route('superadmin.users.index')) }}">
                            <span><iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Manajemen User</span>
                        </a>
                    </li>
                @endif

                @if($user && in_array($user->role, $akademik_roles))
                    
                    <li class="nav-small-cap mt-3">
                        <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                        <span class="hide-menu">DATA AKADEMIK</span>
                    </li>
                    
                    @if($hasMenu('Pengaturan Sekolah'))
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->is('*pengaturan*') ? 'active' : '' }}" href="{{ $encLink('/pengaturan') }}">
                                <span><iconify-icon icon="solar:settings-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Pengaturan Sekolah</span>
                            </a>
                        </li>
                    @endif
                    
                    @if($hasMenu('Manajemen Kelas'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*kelas*') && !request()->is('*kenaikan-kelas*') ? 'active' : '' }}" href="{{ $encLink('/kelas') }}">
                            <span><iconify-icon icon="solar:black-hole-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Data Kelas</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Data Siswa'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*data-siswa*') ? 'active' : '' }}" href="{{ $encLink('/data-siswa') }}">
                            <span><iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Data Siswa</span>
                        </a>
                    </li>
                    @endif

                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                        <span class="hide-menu">KEGIATAN & PRESENSI</span>
                    </li>
                    
                    @if(($user->role == 'guru' || $user->isSuperAdmin()) && $hasMenu('Manajemen Materi'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*materi/guru*') ? 'active' : '' }}" href="{{ $encLink(route('materi.guru.index')) }}">
                            <span><iconify-icon icon="solar:document-add-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Manajemen Materi</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array($user->role, ['kepsek', 'admin', 'super_admin']) && $hasMenu('Persetujuan Materi'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*materi/persetujuan*') ? 'active' : '' }}" href="{{ $encLink(route('materi.kepsek.index')) }}">
                            <span><iconify-icon icon="solar:shield-check-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Persetujuan Materi</span>
                        </a>
                    </li>
                    @endif
                    @if($hasMenu('Jurnal Harian'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*jurnal-harian*') ? 'active' : '' }}" href="{{ $encLink('/jurnal-harian') }}">
                            <span><iconify-icon icon="solar:book-bookmark-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Jurnal Harian</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Absensi Harian'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*absensi-harian*') ? 'active' : '' }}" href="{{ $encLink('/absensi-harian') }}">
                            <span><iconify-icon icon="solar:user-check-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Presensi Harian</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Rekap Absensi'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*rekap-absensi*') ? 'active' : '' }}" href="{{ $encLink('/rekap-absensi') }}">
                            <span><iconify-icon icon="solar:document-text-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Rekap Presensi</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Perkembangan Fisik'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*perkembangan-fisik*') ? 'active' : '' }}" href="{{ $encLink('/perkembangan-fisik') }}">
                            <span><iconify-icon icon="solar:pulse-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Perkembangan Fisik</span>
                        </a>
                    </li>
                    @endif

                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                        <span class="hide-menu">E-RAPOR & ARSIP</span>
                    </li>
                    
                    @if($hasMenu('Isi Rapor'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*isi-rapor*') ? 'active' : '' }}" href="{{ $encLink('/isi-rapor') }}">
                            <span><iconify-icon icon="solar:pen-new-round-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Isi Rapor</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Cetak Rapor'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*cetak-rapor*') ? 'active' : '' }}" href="{{ $encLink('/cetak-rapor') }}">
                            <span><iconify-icon icon="solar:printer-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Cetak Rapor</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Arsip Rapor'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*arsip-rapor*') ? 'active' : '' }}" href="{{ $encLink('/arsip-rapor') }}">
                            <span><iconify-icon icon="solar:folder-with-files-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Arsip Rapor</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Pengumuman'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('*pengumuman*') ? 'active' : '' }}" href="{{ $encLink('/pengumuman') }}">
                            <span><iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Pengumuman Sekolah</span>
                        </a>
                    </li>
                    @endif

                    @if(in_array($user->role, ['super_admin', 'admin']))
                        @if($hasMenu('Template Rapor'))
                        <li class="sidebar-item mt-2">
                            <a class="sidebar-link {{ request()->is('*admin/template*') ? 'active' : '' }}" href="{{ $encLink('/admin/template') }}">
                                <span><iconify-icon icon="solar:document-add-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Template Rapor</span>
                            </a>
                        </li>
                        @endif
                        
                        @if($hasMenu('Kenaikan Kelas'))
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->is('*kenaikan-kelas*') ? 'active' : '' }}" href="{{ $encLink('/kenaikan-kelas') }}">
                                <span><iconify-icon icon="solar:square-double-alt-arrow-up-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Kenaikan Kelas</span>
                            </a>
                        </li>
                        @endif
                    @endif
                @endif

                @if($user && $user->role == 'wali_murid')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                        <span class="hide-menu">AKADEMIK ANAK</span>
                    </li>
                    
                    @if($hasMenu('Rapor Anak'))
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ $encLink('/rapor-anak') }}">
                            <span><iconify-icon icon="solar:notebook-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Rapor Anak</span>
                        </a>
                    </li>
                    @endif
                    
                    @if($hasMenu('Laporan Fisik') || $hasMenu('Perkembangan Fisik'))
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ $encLink('/perkembangan-anak') }}">
                            <span><iconify-icon icon="solar:pulse-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Laporan Fisik</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Manajemen Materi') || $hasMenu('Materi Pelajaran'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('materi.ortu.index') ? 'active' : '' }}" href="{{ $encLink(route('materi.ortu.index')) }}">
                            <span><iconify-icon icon="solar:book-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Materi Pelajaran</span>
                        </a>
                    </li>
                    @endif
                    
                    @if($hasMenu('Jurnal Harian') || $hasMenu('Jurnal Harian Anak'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('materi-anak*') ? 'active' : '' }}" href="{{ $encLink(route('walimurid.materi')) }}">
                            <span><iconify-icon icon="solar:notebook-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Jurnal Harian Anak</span>
                        </a>
                    </li>
                    @endif

                    <li class="sidebar-item mt-3">
                        <a class="sidebar-link text-primary fw-bold" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalTautkanAnak">
                            <span><iconify-icon icon="solar:user-plus-bold-duotone" class="fs-6 text-primary"></iconify-icon></span>
                            <span class="hide-menu">Tautkan Anak (NISN)</span>
                        </a>
                    </li>
                @endif
                
            </ul>
        </nav>
    </div>
</aside>