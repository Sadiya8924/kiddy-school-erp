<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Super Admin') — TK Ceria</title>
  <link rel="stylesheet" href="/template2/css/styles.min.css" />
  <link rel="stylesheet" href="/template2/css/icons/tabler-icons/tabler-icons.css" />
  
  <!-- ========================================================= -->
    <!-- TEMA "SOLID & CRISP" (Sesuai Referensi Terbaru)           -->
    <!-- ========================================================= -->
    <!-- Import Font 'Inter' biar tulisan lebih "bakuh" -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @php
        $currentYayasan = null;
        if (auth()->check()) {
            $user = auth()->user();
            if (!empty($user->yayasan_id)) {
                $currentYayasan = \App\Models\Yayasan::find($user->yayasan_id);
            } elseif (!empty($user->school_id)) {
                $school = \App\Models\School::find($user->school_id);
                if ($school && $school->yayasan_id) {
                    $currentYayasan = \App\Models\Yayasan::find($school->yayasan_id);
                }
            }
            if (!$currentYayasan) {
                $sessionYayasanId = session('active_yayasan_id');
                if ($sessionYayasanId) {
                    $currentYayasan = \App\Models\Yayasan::find($sessionYayasanId);
                }
                if (!$currentYayasan) {
                    $currentYayasan = \App\Models\Yayasan::first();
                }
            }
        }

        $landingContent = [];
        if ($currentYayasan) {
            $rawContent = $currentYayasan->landing_content ?? $currentYayasan->landing_page_content ?? [];
            $landingContent = is_array($rawContent) ? $rawContent : (json_decode($rawContent, true) ?? []);
        }
        $templateKey = $landingContent['template'] ?? 'ocean';

        $themeConfigMap = [
            'ocean' => [
                'primary'      => '#2563eb',
                'primary_dark' => '#0f172a',
                'accent'       => '#f59e0b',
                'surface'      => '#f8fafc',
                'pill_bg'      => '#eff6ff',
                'pill_border'  => '#dbeafe',
                'gradient'     => 'linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%)',
            ],
            'ceria' => [
                'primary'      => '#EF9F27',
                'primary_dark' => '#7A5310',
                'accent'       => '#F0997B',
                'surface'      => '#FDFBF7',
                'pill_bg'      => '#FFF7ED',
                'pill_border'  => '#FFEDD5',
                'gradient'     => 'linear-gradient(135deg, #7A5310 0%, #D4537E 60%, #EF9F27 100%)',
            ],
            'trustworthy' => [
                'primary'      => '#185FA5',
                'primary_dark' => '#0B2545',
                'accent'       => '#60A5FA',
                'surface'      => '#F4F6F9',
                'pill_bg'      => '#F0F9FF',
                'pill_border'  => '#E0F2FE',
                'gradient'     => 'linear-gradient(135deg, #0B2545 0%, #134074 60%, #185FA5 100%)',
            ],
            'natural' => [
                'primary'      => '#639922',
                'primary_dark' => '#1C3309',
                'accent'       => '#FAC775',
                'surface'      => '#F6F3EC',
                'pill_bg'      => '#F7FEE7',
                'pill_border'  => '#ECFCCB',
                'gradient'     => 'linear-gradient(135deg, #1C3309 0%, #355312 60%, #4D7C0F 100%)',
            ],
            'sunset' => [
                'primary'      => '#E11D48',
                'primary_dark' => '#4C0519',
                'accent'       => '#F59E0B',
                'surface'      => '#FFFDFB',
                'pill_bg'      => '#FFF1F2',
                'pill_border'  => '#FFE4E6',
                'gradient'     => 'linear-gradient(135deg, #4C0519 0%, #9F1239 50%, #E11D48 100%)',
            ],
            'emerald' => [
                'primary'      => '#059669',
                'primary_dark' => '#064E3B',
                'accent'       => '#FBBF24',
                'surface'      => '#F4FBF7',
                'pill_bg'      => '#ECFDF5',
                'pill_border'  => '#D1FAE5',
                'gradient'     => 'linear-gradient(135deg, #064E3B 0%, #047857 60%, #10B981 100%)',
            ],
            'violet' => [
                'primary'      => '#7C3AED',
                'primary_dark' => '#3B0764',
                'accent'       => '#EC4899',
                'surface'      => '#FAF5FF',
                'pill_bg'      => '#F3E8FF',
                'pill_border'  => '#E9D5FF',
                'gradient'     => 'linear-gradient(135deg, #3B0764 0%, #6D28D9 60%, #8B5CF6 100%)',
            ],
            'minimal' => [
                'primary'      => '#334155',
                'primary_dark' => '#0F172A',
                'accent'       => '#0EA5E9',
                'surface'      => '#F8FAFC',
                'pill_bg'      => '#F1F5F9',
                'pill_border'  => '#E2E8F0',
                'gradient'     => 'linear-gradient(135deg, #0F172A 0%, #1E293B 60%, #334155 100%)',
            ],
            'amber' => [
                'primary'      => '#D97706',
                'primary_dark' => '#78350F',
                'accent'       => '#10B981',
                'surface'      => '#FFFBEB',
                'pill_bg'      => '#FEF3C7',
                'pill_border'  => '#FDE68A',
                'gradient'     => 'linear-gradient(135deg, #78350F 0%, #B45309 60%, #F59E0B 100%)',
            ],
            'cyan' => [
                'primary'      => '#0891B2',
                'primary_dark' => '#164E63',
                'accent'       => '#A855F7',
                'surface'      => '#F0FDFA',
                'pill_bg'      => '#CCFBF1',
                'pill_border'  => '#99F6E4',
                'gradient'     => 'linear-gradient(135deg, #164E63 0%, #0E7490 60%, #06B6D4 100%)',
            ],
            'rose' => [
                'primary'      => '#E11D48',
                'primary_dark' => '#881337',
                'accent'       => '#FB7185',
                'surface'      => '#FFF1F2',
                'pill_bg'      => '#FFE4E6',
                'pill_border'  => '#FECDD3',
                'gradient'     => 'linear-gradient(135deg, #881337 0%, #BE123C 60%, #FB7185 100%)',
            ],
            'midnight' => [
                'primary'      => '#38BDF8',
                'primary_dark' => '#030712',
                'accent'       => '#818CF8',
                'surface'      => '#0F172A',
                'pill_bg'      => '#1E293B',
                'pill_border'  => '#334155',
                'gradient'     => 'linear-gradient(135deg, #030712 0%, #0F172A 60%, #1E293B 100%)',
            ],
        ];

        $cfg = $themeConfigMap[$templateKey] ?? $themeConfigMap['ocean'];
    @endphp

    <style>
        :root {
            --primary-blue: {{ $cfg['primary'] }};       /* Primary Theme Accent */
            --primary-blue-soft: {{ $cfg['pill_bg'] }};  /* Background soft muda */
            --primary-blue-border: {{ $cfg['pill_border'] }};/* Border muda */
            --primary-blue-dark: {{ $cfg['primary_dark'] }};  /* Biru gelap pekat */
            --text-dark: #0f172a;          
            --text-muted: #64748b;         
            --bg-body: #f8fafc;            
            --border-color: {{ $cfg['pill_border'] }};       
            --theme-hero-gradient: {{ $cfg['gradient'] }};
        }

        body, .page-wrapper {
            background-color: var(--bg-body) !important;
            color: var(--text-dark);
            font-family: 'Inter', sans-serif !important;
            -webkit-font-smoothing: antialiased; /* Kunci biar tulisan tajam! */
        }

        /* Force Welcome Banner and Header Cards to use active template theme gradient */
        .hero-welcome-card,
        .hero-banner-card,
        .card[style*="linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%)"],
        .card[style*="linear-gradient(135deg,#0f172a 0%,#1e3a8a 50%,#2563eb 100%)"] {
            background: var(--theme-hero-gradient) !important;
        }

        /* Override hardcoded card border-top colors to adopt active template accent */
        .card[style*="border-top: 3.5px solid #3b82f6"],
        .card[style*="border-top: 3.5px solid #2563eb"],
        .card[style*="border-top: 3px solid #3b82f6"],
        .card[style*="border-top: 3px solid #2563eb"],
        .card[style*="border-top:3.5px solid #3b82f6"],
        .card[style*="border-top:3.5px solid #2563eb"] {
            border-top-color: var(--primary-blue) !important;
        }

        h1, h2, h3, h4, h5, h6 { font-weight: 600 !important; color: var(--text-dark); }
        .text-muted { color: var(--text-muted) !important; }

        .card {
            border: 1px solid var(--border-color) !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            border-radius: 12px !important;
            background-color: #ffffff;
            margin-bottom: 1.25rem;
        }

        /* Custom Fractional Spacing & Utility Fixes for Layout & Cards */
        .gap-3\.5 { gap: 1.25rem !important; }
        .gap-2\.5 { gap: 0.875rem !important; }
        .gap-4 { gap: 1.5rem !important; }
        .mb-3\.5 { margin-bottom: 1.25rem !important; }
        .mt-3\.5 { margin-top: 1.25rem !important; }
        .p-3\.5 { padding: 1.25rem !important; }
        .px-3\.5 { padding-left: 1.25rem !important; padding-right: 1.25rem !important; }
        .py-3\.5 { padding-top: 1.25rem !important; padding-bottom: 1.25rem !important; }
        .mb-0\.5 { margin-bottom: 0.25rem !important; }
        .mb-1\.5 { margin-bottom: 0.5rem !important; }
        .mb-2\.5 { margin-bottom: 0.875rem !important; }

        /* Ensure card elements and card wrappers always maintain vertical separation */
        .container-fluid > div > .card + .card,
        .container-fluid > div > .card + form,
        .container-fluid > div > form + .card,
        .container-fluid > div > .card + .row,
        .container-fluid > div > .row + .card,
        .container-fluid > div > .card + .table-responsive,
        .container-fluid > div > .card + div:not(.row) {
            margin-top: 0.5rem;
        }
        
        .row:has(> .col-12 > .card), .row:has(> [class*="col-"]) {
            margin-bottom: 0.5rem;
        }

        /* Dynamic Theme Brand Logo & Icon Helpers */
        .theme-brand-title {
            color: var(--primary-blue-dark, var(--primary-blue)) !important;
        }
        .theme-brand-bg-soft {
            background-color: var(--primary-blue-soft) !important;
        }
        .theme-brand-icon, .theme-text-primary {
            color: var(--primary-blue) !important;
        }

        /* Override Bootstrap text-primary & bg-primary utilities to follow active theme */
        .text-primary { color: var(--primary-blue) !important; }
        .bg-primary { background-color: var(--primary-blue) !important; }
        .bg-primary-subtle { background-color: var(--primary-blue-soft) !important; }
        .text-primary.text-opacity-80 { color: var(--primary-blue) !important; opacity: 0.8; }
        .border-primary { border-color: var(--primary-blue) !important; }
        .btn-outline-primary {
            color: var(--primary-blue) !important;
            border-color: var(--primary-blue) !important;
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-blue) !important;
            color: #fff !important;
        }
        a.text-primary, a.text-primary:visited { color: var(--primary-blue) !important; }
        .badge.bg-primary, .badge.bg-primary-subtle.text-primary {
            background-color: var(--primary-blue-soft) !important;
            color: var(--primary-blue) !important;
        }
        .progress-bar.bg-primary { background-color: var(--primary-blue) !important; }
        .card-clickable:hover { border-color: var(--primary-blue-border, #bfdbfe) !important; }

        /* ============================================================ */
        /* SIDEBAR HOVER — override template hardcoded #2952ff blue     */
        /* ============================================================ */
        .sidebar-nav ul .sidebar-item .sidebar-link:hover {
            background-color: var(--primary-blue-soft) !important;
            color: var(--primary-blue) !important;
        }
        .sidebar-nav ul .sidebar-item .sidebar-link:hover iconify-icon,
        .sidebar-nav ul .sidebar-item .sidebar-link:hover .ti {
            color: var(--primary-blue) !important;
        }

        /* ============================================================ */
        /* NAVBAR TOP-BAR ICON HOVER — override template #2952ff blue   */
        /* ============================================================ */
        .nav-icon-hover:hover::before {
            background-color: var(--primary-blue-soft) !important;
        }
        .app-header .navbar .navbar-nav.quick-links .nav-item:hover .nav-link {
            color: var(--primary-blue) !important;
        }
        .app-header .navbar .navbar-nav.quick-links .nav-item:hover .nav-link:before {
            background: var(--primary-blue-soft) !important;
        }

        /* Override Bootstrap CSS custom props so template components inherit theme */
        :root {
            --bs-primary: var(--primary-blue);
            --bs-primary-bg-subtle: var(--primary-blue-soft);
            --bs-primary-border-subtle: var(--primary-blue-border);
            --bs-nav-pills-link-active-bg: var(--primary-blue);
            --bs-nav-pills-link-active-color: #ffffff;
            --bs-nav-link-color: var(--primary-blue);
            --bs-nav-link-hover-color: var(--primary-blue-dark, var(--primary-blue));
        }

        /* ============================================================ */
        /* NAV-PILLS & TABS THEME OVERRIDE                              */
        /* ============================================================ */
        .nav-pills .nav-link {
            color: var(--primary-blue) !important;
            border-color: var(--primary-blue-border, #bfdbfe);
        }
        .nav-pills .nav-link iconify-icon,
        .nav-pills .nav-link i {
            color: inherit !important;
        }
        .nav-pills .nav-link:hover {
            color: var(--primary-blue-dark, var(--primary-blue)) !important;
            background-color: var(--primary-blue-soft) !important;
        }
        .nav-pills .nav-link.active,
        .nav-pills .show > .nav-link {
            background-color: var(--primary-blue) !important;
            color: #ffffff !important;
            border-color: var(--primary-blue) !important;
        }
        .nav-pills .nav-link.active iconify-icon,
        .nav-pills .nav-link.active i {
            color: #ffffff !important;
        }

        /* Sidebar Styling (Lebih Rapi & Tegas) */
        .left-sidebar {
            width: 265px !important;
        }
        .body-wrapper {
            margin-left: 265px !important;
        }
        @media (max-width: 1199.98px) {
            .body-wrapper {
                margin-left: 0 !important;
            }
        }

        .sidebar-nav ul .sidebar-item > .sidebar-link {
            font-weight: 500;
            color: #475569;
            display: flex;
            align-items: center;
            padding: 9px 12px !important;
            font-size: 13px !important;
            border-radius: 10px !important;
            white-space: normal !important;
            line-height: 1.3;
            margin-bottom: 3px;
        }
        .sidebar-nav ul .sidebar-item.selected > .sidebar-link,
        .sidebar-nav ul .sidebar-item > .sidebar-link.active {
            background-color: var(--primary-blue-soft) !important;
            color: var(--primary-blue-dark) !important;
            font-weight: 600 !important;
            border-radius: 10px !important;
        }
        .sidebar-nav ul .sidebar-item.selected > .sidebar-link iconify-icon,
        .sidebar-nav ul .sidebar-item > .sidebar-link.active iconify-icon {
            color: var(--primary-blue-dark) !important;
        }
        .nav-small-cap {
            color: #94a3b8 !important;
            font-weight: 600 !important;
            font-size: 11px !important;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Tombol Utama */
        .btn-primary { 
            background-color: var(--primary-blue) !important; 
            border-color: var(--primary-blue) !important; 
            font-weight: 500 !important;
        }
        .btn-primary:hover { 
            background-color: var(--primary-blue-dark) !important; 
        }

        /* Header Tabel */
        .table thead th {
            font-size: 11px;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Pagination SVG Overflow Fix */
        .pagination svg {
            width: 1rem !important;
            height: 1rem !important;
            max-width: 100%;
            display: inline-block;
        }
        .pagination {
            margin-bottom: 0 !important;
        }
    </style>
</head>
<body>

  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

      @include('layouts.partials.sidebar')

      <div class="body-wrapper">

        <header class="app-header">
          <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                  <i class="ti ti-menu-2"></i>
                </a>
              </li>
            </ul>

            <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
              <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

                <li class="nav-item d-none d-md-block me-4 border-end pe-4">
                    <form action="{{ route('superadmin.switch_school') }}" method="POST" id="formSwitchSchool" class="d-flex align-items-center gap-2 m-0">
                        @csrf
                        <iconify-icon icon="solar:buildings-bold-duotone" class="fs-6 text-primary"></iconify-icon>
                        <select name="active_school_id" class="form-select form-select-sm border-primary text-primary fw-bold shadow-sm" onchange="document.getElementById('formSwitchSchool').submit()" style="min-width: 200px;">
                            <option value="">-- Mode Pusat (Semua Data) --</option>
                            @php
                                $swYayasanId = auth()->user()->getUserYayasanId() ?: session('active_yayasan_id');
                                $swSchoolsQuery = \App\Models\School::where('status', 'aktif');
                                if (!auth()->user()->isOwner() || $swYayasanId) {
                                    if ($swYayasanId) {
                                        $swSchoolsQuery->where('yayasan_id', $swYayasanId);
                                    }
                                }
                            @endphp
                            @foreach($swSchoolsQuery->get() as $school)
                                <option value="{{ $school->id }}" {{ session('active_school_id') == $school->id ? 'selected' : '' }}>
                                    Pantau: {{ $school->nama_sekolah }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </li>

                <li class="nav-item dropdown">
                  <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center gap-2">
                      <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                           style="width:35px;height:35px;font-size:14px;">
                        {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                      </div>
                      <span class="d-none d-md-block fw-semibold text-dark small">
                        {{ auth()->user()->name ?? 'User' }}
                      </span>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up">
                    <div class="message-body">
                      <p class="mb-1 px-3 py-1 text-muted small">
                        <span class="badge bg-danger-subtle text-danger">Super Admin</span>
                      </p>
                      <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                          <i class="ti ti-logout fs-6"></i> Logout
                        </button>
                      </form>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </nav>
        </header>

        <div class="container-fluid">

          {{-- Flash messages will be handled by SweetAlert2 --}}

          @yield('content')

          <!-- Unified Clean Footer -->
          <div class="py-3 text-center mt-4 border-top border-light">
            @php
                $currentSchoolId = session('active_school_id');
                $activeSchool = $currentSchoolId ? \App\Models\School::find($currentSchoolId) : null;
                if ($activeSchool) {
                    $footerName = $activeSchool->nama_sekolah;
                } elseif (isset($currentYayasan) && $currentYayasan) {
                    $footerName = $currentYayasan->nama_yayasan;
                } else {
                    $footerName = \App\Models\Setting::get('site_title', 'Kiddy School ERP');
                }
            @endphp
            <p class="mb-0 text-muted" style="font-size: 12px; font-weight: 500;">
              © {{ date('Y') }} {{ $footerName }} — Kiddy School ERP v2.0
            </p>
          </div>
        </div>

      </div>
  </div> 
  
  <script src="/template2/libs/jquery/dist/jquery.min.js"></script>
  <script src="/template2/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/template2/js/sidebarmenu.js"></script>
  <script src="/template2/js/app.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const togglers = document.querySelectorAll(".sidebartoggler");
      togglers.forEach(function (toggler) {
        toggler.addEventListener("click", function (e) {
          e.preventDefault();
          document.body.classList.toggle("sidebar-hidden");
        });
      });
    });

    $(document).ready(function() {
        // Sweep form onsubmit confirmations
        $('form[onsubmit*="confirm"]').each(function() {
            let form = $(this);
            let onsubmitAttr = form.attr('onsubmit');
            let match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
            let message = match ? match[1] : "Apakah Anda yakin ingin menghapus data ini?";
            
            form.removeAttr('onsubmit');
            form.on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.off('submit').submit();
                    }
                });
            });
        });

        // Sweep button/link onclick confirmations
        $('[onclick*="confirm"]').each(function() {
            let element = $(this);
            let onclickAttr = element.attr('onclick');
            let match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
            let message = match ? match[1] : "Apakah Anda yakin?";
            
            element.removeAttr('onclick');
            element.on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (element.is('button') || element.is('input')) {
                            element.closest('form').submit();
                        } else if (element.is('a')) {
                            window.location.href = element.attr('href');
                        }
                    }
                });
            });
        });

        // Global Flash Messages (Session Success/Error)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#2563eb'
            });
        @endif
    });
  </script>

  @stack('scripts')
</body>
</html>