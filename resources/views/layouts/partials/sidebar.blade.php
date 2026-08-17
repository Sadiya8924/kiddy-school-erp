<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between px-4 py-3">
            <a href="/dashboard" class="text-nowrap logo-img text-decoration-none">
                <h4 class="fw-bold text-primary mb-0 text-truncate" style="max-width: 190px;" title="{{ \App\Models\Setting::get('site_title', 'TK CERIA') }}">
                    <iconify-icon icon="solar:diploma-bold-duotone" class="fs-6 align-middle me-1"></iconify-icon> {{ strtoupper(\App\Models\Setting::get('site_title', 'TK CERIA')) }}
                </h4>
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
                    // Tarik hak akses HANYA untuk Admin, Guru, & Kepsek (karena terikat sekolah)
                    if ($user && in_array($user->role, ['admin', 'guru', 'kepsek'])) {
                        $role_column = 'is_active_for_' . $user->role;
                        
                        $allowed_modules = \Illuminate\Support\Facades\DB::table('school_modules')
                            ->join('modules', 'school_modules.module_id', '=', 'modules.id')
                            ->where('school_modules.school_id', $user->school_id)
                            ->where('school_modules.' . $role_column, 1)
                            ->pluck('modules.name')
                            ->toArray();
                    }

                    // Fungsi penentu menu muncul atau tidak
                    $hasMenu = function($menuName) use ($allowed_modules, $user) {
                        if (!$user) return false;
                        if ($user->isSuperAdmin() || $user->isOwner()) return true; // Super admin & owner bebas
                        if ($user->role === 'wali_murid') return true; // Wali murid bebas
                        
                        // Cek untuk Admin, Guru, Kepsek
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
                    <a class="sidebar-link {{ request()->is('dashboard') || request()->is('superadmin/dashboard') ? 'active' : '' }}" 
                       href="{{ $user && $user->isSuperAdmin() ? '/superadmin/dashboard' : '/dashboard' }}">
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
                        <a class="sidebar-link {{ request()->is('settings/landing-page*') ? 'active' : '' }}" href="{{ route('settings.landing.edit') }}">
                            <span><iconify-icon icon="solar:monitor-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Edit Landing Page Utama</span>
                        </a>
                    </li>
                    {{-- Owner: Pendaftaran Yayasan Baru --}}
                    <li class="sidebar-item">
                        @php $regCount = \App\Models\YayasanRegistration::where('status','menunggu')->count(); @endphp
                        <a class="sidebar-link {{ request()->is('owner/yayasan-registrations*') ? 'active' : '' }}" href="{{ route('owner.yayasan_reg.index') }}">
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
                        <a class="sidebar-link {{ request()->is('superadmin/landing-page*') ? 'active' : '' }}" href="{{ route('superadmin.landing.edit') }}">
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
                        <a class="sidebar-link {{ request()->is('owner/yayasans*') ? 'active' : '' }}" href="{{ route('owner.yayasans.index') }}">
                            <span><iconify-icon icon="solar:buildings-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Data Yayasan</span>
                        </a>
                    </li>
                    @endif
                    @if(!$user->isOwner())
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('superadmin/schools*') ? 'active' : '' }}" href="{{ route('superadmin.schools.index') }}">
                            <span><iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Data Sekolah (TK)</span>
                        </a>
                    </li>
                    @endif
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('superadmin/users*') ? 'active' : '' }}" href="{{ route('superadmin.users.index') }}">
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
                            <a class="sidebar-link {{ request()->is('pengaturan*') ? 'active' : '' }}" href="/pengaturan">
                                <span><iconify-icon icon="solar:settings-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Pengaturan Sekolah</span>
                            </a>
                        </li>
                    @endif
                    
                    @if($hasMenu('Manajemen Kelas'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('kelas*') ? 'active' : '' }}" href="/kelas">
                            <span><iconify-icon icon="solar:black-hole-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Data Kelas</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Data Siswa'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('data-siswa*') ? 'active' : '' }}" href="/data-siswa">
                            <span><iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Data Siswa</span>
                        </a>
                    </li>
                    @endif

                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                        <span class="hide-menu">KEGIATAN & PRESENSI</span>
                    </li>
                    
                    @if($user->role == 'guru' || $user->isSuperAdmin())
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('materi/guru*') ? 'active' : '' }}" href="{{ route('materi.guru.index') }}">
                            <span><iconify-icon icon="solar:document-add-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Manajemen Materi</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array($user->role, ['kepsek', 'admin', 'super_admin']))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('materi/persetujuan*') ? 'active' : '' }}" href="{{ route('materi.kepsek.index') }}">
                            <span><iconify-icon icon="solar:shield-check-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Persetujuan Materi</span>
                        </a>
                    </li>
                    @endif
                    @if($hasMenu('Jurnal Harian'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('jurnal-harian*') ? 'active' : '' }}" href="/jurnal-harian">
                            <span><iconify-icon icon="solar:book-bookmark-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Jurnal Harian</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Absensi Harian'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('absensi-harian*') ? 'active' : '' }}" href="/absensi-harian">
                            <span><iconify-icon icon="solar:user-check-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Presensi Harian</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Rekap Absensi'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('rekap-absensi*') ? 'active' : '' }}" href="/rekap-absensi">
                            <span><iconify-icon icon="solar:document-text-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Rekap Presensi</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Perkembangan Fisik'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('perkembangan-fisik*') ? 'active' : '' }}" href="/perkembangan-fisik">
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
                        <a class="sidebar-link {{ request()->is('isi-rapor*') ? 'active' : '' }}" href="/isi-rapor">
                            <span><iconify-icon icon="solar:pen-new-round-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Isi Rapor</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Cetak Rapor'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('cetak-rapor*') ? 'active' : '' }}" href="/cetak-rapor">
                            <span><iconify-icon icon="solar:printer-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Cetak Rapor</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Arsip Rapor'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('arsip-rapor*') ? 'active' : '' }}" href="/arsip-rapor">
                            <span><iconify-icon icon="solar:folder-with-files-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Arsip Rapor</span>
                        </a>
                    </li>
                    @endif

                    @if($hasMenu('Pengumuman'))
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('pengumuman*') ? 'active' : '' }}" href="/pengumuman">
                            <span><iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Pengumuman Sekolah</span>
                        </a>
                    </li>
                    @endif

                    @if(in_array($user->role, ['super_admin', 'admin']))
                        @if($hasMenu('Template Rapor'))
                        <li class="sidebar-item mt-2">
                            <a class="sidebar-link {{ request()->is('admin/template*') ? 'active' : '' }}" href="/admin/template">
                                <span><iconify-icon icon="solar:document-add-bold-duotone" class="fs-6"></iconify-icon></span>
                                <span class="hide-menu">Template Rapor</span>
                            </a>
                        </li>
                        @endif
                        
                        @if($hasMenu('Kenaikan Kelas'))
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->is('kenaikan-kelas*') ? 'active' : '' }}" href="/kenaikan-kelas">
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
                        <a class="sidebar-link" href="/rapor-anak">
                            <span><iconify-icon icon="solar:notebook-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Rapor Anak</span>
                        </a>
                    </li>
                    @endif
                    
                    @if($hasMenu('Laporan Fisik'))
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/perkembangan-anak">
                            <span><iconify-icon icon="solar:pulse-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Laporan Fisik</span>
                        </a>
                    </li>
                    @endif

                    
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('materi.ortu.index') ? 'active' : '' }}" href="{{ route('materi.ortu.index') }}">
                            <span><iconify-icon icon="solar:book-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Materi Pelajaran</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('materi-anak*') ? 'active' : '' }}" href="{{ route('walimurid.materi') }}">
                            <span><iconify-icon icon="solar:notebook-bold-duotone" class="fs-6"></iconify-icon></span>
                            <span class="hide-menu">Jurnal Harian Anak</span>
                        </a>
                    </li>

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