<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $landingContent = $yayasan->landing_content ?? [];
        $template       = $landingContent['template'] ?? 'ocean';
        $siteTitle      = !empty($landingContent['site_title']) ? $landingContent['site_title'] : $yayasan->nama_yayasan;

        $themeMap = [
            'ceria' => [
                'primary'      => '#EF9F27',
                'primary_dark' => '#7A5310',
                'accent'       => '#F0997B',
                'surface'      => '#FDFBF7',
                'hero_bg'      => 'linear-gradient(135deg, #7A5310 0%, #D4537E 60%, #EF9F27 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #EF9F27, #F0997B)',
                'btn_cta'      => 'linear-gradient(135deg, #EF9F27 0%, #D4537E 100%)',
                'btn_cta_text' => '#FFFFFF',
            ],
            'trustworthy' => [
                'primary'      => '#185FA5',
                'primary_dark' => '#0B2545',
                'accent'       => '#2563EB',
                'surface'      => '#F4F6F9',
                'hero_bg'      => 'linear-gradient(135deg, #0B2545 0%, #134074 60%, #185FA5 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #0B2545, #185FA5)',
                'btn_cta'      => 'linear-gradient(135deg, #185FA5 0%, #1D4ED8 100%)',
                'btn_cta_text' => '#FFFFFF',
            ],
            'natural' => [
                'primary'      => '#639922',
                'primary_dark' => '#1C3309',
                'accent'       => '#D97706',
                'surface'      => '#F6F3EC',
                'hero_bg'      => 'linear-gradient(135deg, #1C3309 0%, #355312 60%, #4D7C0F 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #355312, #639922)',
                'btn_cta'      => 'linear-gradient(135deg, #639922 0%, #355312 100%)',
                'btn_cta_text' => '#FFFFFF',
            ],
            'ocean' => [
                'primary'      => '#2563EB',
                'primary_dark' => '#1B3A8C',
                'accent'       => '#FCD34D',
                'surface'      => '#FFFFFF',
                'hero_bg'      => 'linear-gradient(135deg, #1B3A8C 0%, #2D55E8 60%, #4F8BFF 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #1E40AF, #3B82F6)',
                'btn_cta'      => 'linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%)',
                'btn_cta_text' => '#FFFFFF',
            ],
        ];

        $theme = $themeMap[$template] ?? $themeMap['ocean'];
    @endphp

    <title>Login Portal — {{ $siteTitle }}</title>
    <meta name="description" content="Masuk ke portal mandiri {{ $siteTitle }} untuk staf, guru, kepala sekolah, dan wali murid.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & Iconify & SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        :root {
            --primary:       {{ $theme['primary'] }};
            --primary-dark:  {{ $theme['primary_dark'] }};
            --accent:        {{ $theme['accent'] }};
            --surface:       {{ $theme['surface'] }};
            --hero-bg:       {{ $theme['hero_bg'] }};
            --avatar-bg:     {{ $theme['avatar_bg'] }};
            --btn-cta:       {{ $theme['btn_cta'] }};
            --btn-cta-text:  {{ $theme['btn_cta_text'] }};
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--surface);
            color: #1E293B;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        /* ── Top Bar Header ───────────────────────────── */
        .site-header {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 12px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 6px rgba(0,0,0,0.04);
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #0F172A;
            font-weight: 800;
            font-size: 16px;
        }
        .header-brand-avatar {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: var(--avatar-bg);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            font-size: 15px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .header-brand-avatar img {
            width: 100%; height: 100%; object-fit: cover;
        }

        .header-nav-menu {
            display: flex;
            align-items: center;
            gap: 24px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .header-nav-menu a {
            text-decoration: none;
            color: #475569;
            font-size: 13.5px;
            font-weight: 600;
            transition: color 0.2s;
        }
        .header-nav-menu a:hover {
            color: var(--primary);
        }

        .btn-portal-outline {
            border: 1.5px solid #CBD5E1;
            color: #475569;
            font-weight: 700;
            font-size: 13px;
            padding: 8px 18px;
            border-radius: 100px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-portal-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: #F8FAFC;
        }

        .btn-daftar-now {
            background: var(--btn-cta);
            color: var(--btn-cta-text) !important;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 20px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
            transition: all 0.25s;
        }
        .btn-daftar-now:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.25);
        }

        /* ── Main Split Screen Layout ────────────────── */
        .login-split-container {
            flex: 1;
            display: flex;
            min-height: calc(100vh - 61px);
        }

        /* ── Left Hero Panel (60%) ────────────────────── */
        .hero-panel-left {
            flex: 1.25;
            background: var(--hero-bg);
            color: #FFFFFF;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .hero-panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        /* Decorative Floating Elements */
        .floating-decor {
            position: absolute;
            pointer-events: none;
            user-select: none;
        }

        .hero-welcome-badge {
            font-size: 36px;
            margin-bottom: 12px;
        }
        .hero-heading {
            font-size: clamp(28px, 3.5vw, 42px);
            font-weight: 800;
            color: #FFFFFF;
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-bottom: 14px;
        }
        .hero-subtext {
            font-size: 15px;
            color: rgba(255,255,255,0.85);
            line-height: 1.65;
            margin-bottom: 36px;
            max-width: 520px;
        }

        /* Feature Cards Grid (Translucent Pills) */
        .feature-cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            max-width: 560px;
            position: relative;
            z-index: 10;
        }
        .feature-pill-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 16px 18px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            transition: all 0.25s ease;
        }
        .feature-pill-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        .feature-icon-box {
            font-size: 22px;
            line-height: 1;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .feature-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 3px;
        }
        .feature-desc {
            font-size: 11.5px;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.45;
            margin: 0;
        }

        /* ── Right Form Panel (40%) ───────────────────── */
        .form-panel-right {
            flex: 0.85;
            background: #FFFFFF;
            padding: 50px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: -10px 0 30px rgba(0,0,0,0.03);
            max-width: 540px;
        }

        .form-title {
            font-size: 26px;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 6px;
            letter-spacing: -0.02em;
        }
        .form-subtitle {
            font-size: 13.5px;
            color: #64748B;
            margin-bottom: 24px;
        }

        .demo-alert-box {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 12px;
            color: #92400E;
        }

        .form-label-custom {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
        }
        .form-control-custom {
            border-radius: 10px;
            border: 1.5px solid #CBD5E1;
            padding: 12px 16px;
            font-size: 14px;
            width: 100%;
            transition: all 0.2s;
            outline: none;
        }
        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0,0,0,0.06);
        }

        .btn-login-submit {
            background: var(--btn-cta);
            color: var(--btn-cta-text) !important;
            font-weight: 800;
            font-size: 15px;
            padding: 14px;
            border-radius: 12px;
            border: none;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
            transition: all 0.25s;
            cursor: pointer;
            margin-top: 8px;
        }
        .btn-login-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(0,0,0,0.25);
        }

        /* Quick Demo Accounts Grid */
        .demo-accounts-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 16px;
            margin-top: 24px;
        }
        .demo-accounts-title {
            font-size: 11px;
            font-weight: 800;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }
        .btn-quick-fill {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 8px 10px;
            text-align: left;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-quick-fill:hover {
            border-color: var(--primary);
            background: #F1F5F9;
        }
        .btn-quick-fill .role-name {
            font-size: 11.5px;
            font-weight: 700;
            color: #1E293B;
            display: block;
        }
        .btn-quick-fill .email-text {
            font-size: 10.5px;
            color: #64748B;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 992px) {
            .hero-panel-left { display: none; }
            .login-split-container { justify-content: center; min-height: auto; padding: 24px 12px; }
            .form-panel-right { max-width: 100%; width: 100%; padding: 32px 20px; box-shadow: none; border-radius: 20px; border: 1px solid #E2E8F0; }
            .header-nav-menu { display: none; }
            .site-header { padding: 10px 16px; }
        }
        @media (max-width: 576px) {
            .demo-grid { grid-template-columns: 1fr; }
            .btn-portal-outline, .btn-daftar-now { font-size: 11.5px; padding: 6px 12px; }
            .header-brand { font-size: 13.5px; }
            .header-brand-avatar { width: 30px; height: 30px; font-size: 12px; }
        }
    </style>
</head>
<body>

    <!-- ── Top Bar Header ───────────────────────────── -->
    <header class="site-header">
        <a href="{{ route('yayasan.landing', $yayasan->kode_yayasan) }}" class="header-brand">
            <div class="header-brand-avatar">
                @if(!empty($landingContent['site_logo']))
                    <img src="{{ asset($landingContent['site_logo']) }}" alt="Logo">
                @elseif($yayasan->logo_yayasan)
                    <img src="{{ asset('storage/' . $yayasan->logo_yayasan) }}" alt="Logo">
                @else
                    {{ strtoupper(substr($siteTitle, 0, 2)) }}
                @endif
            </div>
            <span>{{ $siteTitle }}</span>
        </a>

        <ul class="header-nav-menu">
            <li><a href="{{ route('yayasan.landing', $yayasan->kode_yayasan) }}#hero">Beranda</a></li>
            <li><a href="{{ route('yayasan.landing', $yayasan->kode_yayasan) }}#tentang">Tentang Kami</a></li>
            <li><a href="{{ route('yayasan.pendaftaran', $yayasan->kode_yayasan) }}">Pendaftaran</a></li>
            <li><a href="{{ route('yayasan.landing', $yayasan->kode_yayasan) }}#footer">Hubungi Kami</a></li>
        </ul>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('yayasan.login', $yayasan->kode_yayasan) }}" class="btn-portal-outline">
                Login Portal
            </a>
            <a href="{{ route('yayasan.pendaftaran', $yayasan->kode_yayasan) }}" class="btn-daftar-now">
                Daftar Sekarang
            </a>
        </div>
    </header>

    <!-- ── Main Split Screen ────────────────────────── -->
    <main class="login-split-container">
        
        <!-- Left Panel: Hero Banner (60%) -->
        <div class="hero-panel-left">
            <!-- Floating Decorative Particles -->
            <div class="floating-decor" style="top: 10%; right: 12%;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="var(--accent)"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <div class="floating-decor" style="bottom: 18%; right: 10%;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="rgba(255,255,255,0.7)"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <div class="floating-decor" style="bottom: 8%; left: 45%;">
                <svg width="120" height="70" viewBox="0 0 130 80" fill="none">
                    <circle cx="40" cy="35" r="26" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.25)" stroke-width="2.5" />
                    <circle cx="40" cy="35" r="14" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.2)" stroke-width="2" />
                    <rect x="62" y="32" width="50" height="8" rx="4" fill="rgba(255,255,255,0.2)" />
                    <rect x="100" y="40" width="10" height="8" rx="3" fill="rgba(255,255,255,0.2)" />
                    <circle cx="40" cy="35" r="5" fill="var(--accent)" opacity="0.8" />
                </svg>
            </div>

            <div class="hero-welcome-badge">👋</div>
            <h2 class="hero-heading">Selamat Datang Kembali!</h2>
            <p class="hero-subtext">
                Masuk ke Sistem Informasi {{ $siteTitle }} untuk mengelola data dan perkembangan sekolah.
            </p>

            <div class="feature-cards-grid">
                <div class="feature-pill-card">
                    <div class="feature-icon-box">📊</div>
                    <div>
                        <div class="feature-title">Informasi Akademik</div>
                        <p class="feature-desc">Pantau nilai, perkembangan, dan laporan</p>
                    </div>
                </div>
                <div class="feature-pill-card">
                    <div class="feature-icon-box">🔔</div>
                    <div>
                        <div class="feature-title">Pemberitahuan Sistem</div>
                        <p class="feature-desc">Terima notifikasi presensi dan pengumuman</p>
                    </div>
                </div>
                <div class="feature-pill-card">
                    <div class="feature-icon-box">📢</div>
                    <div>
                        <div class="feature-title">Jurnal &amp; Materi</div>
                        <p class="feature-desc">Kelola jurnal harian dan materi pembelajaran</p>
                    </div>
                </div>
                <div class="feature-pill-card">
                    <div class="feature-icon-box">🔗</div>
                    <div>
                        <div class="feature-title">Terintegrasi</div>
                        <p class="feature-desc">Platform multi-sekolah yang aman dan handal</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Form Section (40%) -->
        <div class="form-panel-right">
            <h2 class="form-title">Masuk Sistem</h2>
            <p class="form-subtitle">Masuk ke akun portal {{ $siteTitle }} Anda.</p>

            {{-- Demo / Beta Alert --}}
            <div class="demo-alert-box">
                <iconify-icon icon="solar:danger-triangle-bold" class="fs-5 flex-shrink-0" style="color: #D97706;"></iconify-icon>
                <div>
                    <strong>Lingkungan Demo / Beta</strong><br>
                    Data pada halaman ini hanya untuk pengujian. Jangan gunakan password pribadi nyata.
                </div>
            </div>

            {{-- Wrong Yayasan Portal Alert --}}
            @if(session('wrong_yayasan'))
                @php $wy = session('wrong_yayasan'); @endphp
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #FEF2F2; border: 1.5px solid #FCA5A5 !important; border-left: 5px solid #EF4444 !important;">
                    <div class="card-body p-3.5">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle p-2 bg-danger bg-opacity-10 text-danger flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <iconify-icon icon="solar:shield-warning-bold-duotone" class="fs-4"></iconify-icon>
                            </div>
                            <div>
                                <h6 class="fw-bold text-danger mb-1" style="font-size: 14px;">🚫 Portal Yayasan Tidak Sesuai!</h6>
                                <p class="text-dark small mb-2.5" style="font-size: 12.5px; line-height: 1.55;">
                                    Akun <strong class="text-danger">{{ $wy['user_email'] }}</strong> terdaftar di <strong class="text-primary">{{ $wy['correct_yayasan_name'] }}</strong>, bukan di portal <strong>{{ $wy['portal_yayasan_name'] }}</strong>.
                                </p>
                                <a href="{{ $wy['correct_yayasan_url'] }}" class="btn btn-danger btn-sm rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-2 shadow-xs" style="font-size: 12px;">
                                    <iconify-icon icon="solar:arrow-right-bold"></iconify-icon>
                                    Pindah ke Portal {{ $wy['correct_yayasan_name'] }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Portal Yayasan Tidak Sesuai!',
                                html: `Akun <b>{{ $wy['user_email'] }}</b> terdaftar di <b>{{ $wy['correct_yayasan_name'] }}</b>.<br><br>Silakan masuk melalui portal yayasan Anda.`,
                                confirmButtonText: '➡️ Pindah ke Portal {{ $wy['correct_yayasan_name'] }}',
                                confirmButtonColor: '#EF4444',
                                showCancelButton: true,
                                cancelButtonText: 'Tutup',
                                customClass: {
                                    popup: 'rounded-4'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ $wy['correct_yayasan_url'] }}";
                                }
                            });
                        }
                    });
                </script>
            @endif

            {{-- Flash Alert Messages --}}
            @if(session('error'))
                <div class="alert alert-danger border-0 rounded-3 py-2.5 px-3 mb-3 small d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:danger-bold" class="fs-5 text-danger"></iconify-icon>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <input type="hidden" name="kode_yayasan" value="{{ $yayasan->kode_yayasan }}">

                <div class="mb-3">
                    <label class="form-label-custom" for="login-email">Email</label>
                    <input type="email" name="email" id="login-email" class="form-control-custom" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom" for="login-password">Password</label>
                    <div class="position-relative">
                        <input type="password" name="password" id="login-password" class="form-control-custom pe-5" placeholder="Kata sandi Anda" required>
                        <button type="button" onclick="togglePasswordVisibility()" class="btn position-absolute top-50 end-0 translate-middle-y text-muted border-0 me-1">
                            <iconify-icon icon="solar:eye-bold" id="eye-icon" class="fs-5"></iconify-icon>
                        </button>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember-me" checked>
                        <label class="form-check-label text-muted small font-semibold" for="remember-me" style="font-size: 12.5px;">
                            Ingat Saya
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-decoration-none small fw-bold" style="font-size: 12.5px; color: var(--primary);">
                        Lupa Password
                    </a>
                </div>

                <button type="submit" class="btn-login-submit">
                    Login
                </button>
            </form>

            {{-- Demo Accounts Quick Fill --}}
            <div class="demo-accounts-card">
                <div class="demo-accounts-title">
                    <iconify-icon icon="solar:bolt-bold" class="text-warning fs-6"></iconify-icon>
                    Demo Akun Tersedia di Database:
                </div>
                <div class="demo-grid">
                    <button type="button" class="btn-quick-fill" onclick="fillCredentials('ortu.1A.1@gmail.com')">
                        <span class="role-name">👪 Wali Murid</span>
                        <span class="email-text">ortu.1A.1@gmail.com</span>
                    </button>
                    <button type="button" class="btn-quick-fill" onclick="fillCredentials('guru.a1@tkpermata.sch.id')">
                        <span class="role-name">👨‍🏫 Guru TK</span>
                        <span class="email-text">guru.a1@tkpermata.sch.id</span>
                    </button>
                    <button type="button" class="btn-quick-fill" onclick="fillCredentials('admin1@tkpermata.sch.id')">
                        <span class="role-name">🛠️ Admin TK</span>
                        <span class="email-text">admin1@tkpermata.sch.id</span>
                    </button>
                    <button type="button" class="btn-quick-fill" onclick="fillCredentials('kepsek1@tkpermata.sch.id')">
                        <span class="role-name">🏫 Kepsek</span>
                        <span class="email-text">kepsek1@tkpermata.sch.id</span>
                    </button>
                    <button type="button" class="btn-quick-fill" onclick="fillCredentials('superadmin1@alhikmah.com')">
                        <span class="role-name">👑 Super Admin</span>
                        <span class="email-text">superadmin1@alhikmah.com</span>
                    </button>
                    <button type="button" class="btn-quick-fill" onclick="fillCredentials('owner@demo.com')">
                        <span class="role-name">🔑 Owner SaaS</span>
                        <span class="email-text">owner@demo.com</span>
                    </button>
                </div>
                <div class="text-muted text-center mt-2" style="font-size: 10.5px;">
                    Password bawaan semua akun: <strong>password</strong>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small mb-0" style="font-size: 13px;">
                    Belum punya akun?  
                    <a href="{{ route('yayasan.pendaftaran', $yayasan->kode_yayasan) }}" class="fw-bold text-decoration-none ms-1" style="color: var(--primary);">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        </div>

    </main>

    <script>
        function fillCredentials(email) {
            document.getElementById('login-email').value = email;
            document.getElementById('login-password').value = 'password';
        }

        function togglePasswordVisibility() {
            const input = document.getElementById('login-password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('icon', 'solar:eye-closed-bold');
            } else {
                input.type = 'password';
                icon.setAttribute('icon', 'solar:eye-bold');
            }
        }
    </script>
</body>
</html>
