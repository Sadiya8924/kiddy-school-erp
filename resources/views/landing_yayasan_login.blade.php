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
            'ocean' => [
                'primary'      => '#1E40AF',
                'primary_dark' => '#1B3A8C',
                'accent'       => '#FCD34D',
                'surface'      => '#FFFFFF',
                'hero_bg'      => 'linear-gradient(135deg, #1B3A8C 0%, #2D55E8 60%, #4F8BFF 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #1E40AF, #3B82F6)',
                'btn_cta'      => 'linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%)',
                'btn_cta_text' => '#FFFFFF',
            ],
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
            'sunset' => [
                'primary'      => '#DC2626',
                'primary_dark' => '#4C0519',
                'accent'       => '#F59E0B',
                'surface'      => '#FFFDFB',
                'hero_bg'      => 'linear-gradient(135deg, #4C0519 0%, #9F1239 50%, #E11D48 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #9F1239, #E11D48)',
                'btn_cta'      => 'linear-gradient(135deg, #E11D48 0%, #BE123C 100%)',
                'btn_cta_text' => '#FFFFFF',
            ],
            'emerald' => [
                'primary'      => '#059669',
                'primary_dark' => '#064E3B',
                'accent'       => '#FBBF24',
                'surface'      => '#F4FBF7',
                'hero_bg'      => 'linear-gradient(135deg, #064E3B 0%, #047857 60%, #10B981 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #064E3B, #059669)',
                'btn_cta'      => 'linear-gradient(135deg, #059669 0%, #047857 100%)',
                'btn_cta_text' => '#FFFFFF',
            ],
            'violet' => [
                'primary'      => '#7C3AED',
                'primary_dark' => '#3B0764',
                'accent'       => '#EC4899',
                'surface'      => '#FAF5FF',
                'hero_bg'      => 'linear-gradient(135deg, #3B0764 0%, #6D28D9 60%, #8B5CF6 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #5B21B6, #7C3AED)',
                'btn_cta'      => 'linear-gradient(135deg, #7C3AED 0%, #6D28D9 100%)',
                'btn_cta_text' => '#FFFFFF',
            ],
            'minimal' => [
                'primary'      => '#334155',
                'primary_dark' => '#0F172A',
                'accent'       => '#0EA5E9',
                'surface'      => '#F8FAFC',
                'hero_bg'      => 'linear-gradient(135deg, #0F172A 0%, #1E293B 60%, #334155 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #1E293B, #475569)',
                'btn_cta'      => 'linear-gradient(135deg, #334155 0%, #1E293B 100%)',
                'btn_cta_text' => '#FFFFFF',
            ],
            'amber' => [
                'primary'      => '#D97706',
                'primary_dark' => '#78350F',
                'accent'       => '#10B981',
                'surface'      => '#FFFBEB',
                'hero_bg'      => 'linear-gradient(135deg, #78350F 0%, #B45309 60%, #F59E0B 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #92400E, #D97706)',
                'btn_cta'      => 'linear-gradient(135deg, #D97706 0%, #B45309 100%)',
                'btn_cta_text' => '#FFFFFF',
            ],
            'cyan' => [
                'primary'      => '#0891B2',
                'primary_dark' => '#164E63',
                'accent'       => '#A855F7',
                'surface'      => '#F0FDFA',
                'hero_bg'      => 'linear-gradient(135deg, #164E63 0%, #0E7490 60%, #06B6D4 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #155E75, #0891B2)',
                'btn_cta'      => 'linear-gradient(135deg, #0891B2 0%, #0E7490 100%)',
                'btn_cta_text' => '#FFFFFF',
            ],
            'rose' => [
                'primary'      => '#E11D48',
                'primary_dark' => '#881337',
                'accent'       => '#FB7185',
                'surface'      => '#FFF1F2',
                'hero_bg'      => 'linear-gradient(135deg, #881337 0%, #BE123C 60%, #FB7185 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #9F1239, #E11D48)',
                'btn_cta'      => 'linear-gradient(135deg, #E11D48 0%, #BE123C 100%)',
                'btn_cta_text' => '#FFFFFF',
            ],
            'midnight' => [
                'primary'      => '#38BDF8',
                'primary_dark' => '#030712',
                'accent'       => '#818CF8',
                'surface'      => '#0F172A',
                'hero_bg'      => 'linear-gradient(135deg, #030712 0%, #0F172A 60%, #1E293B 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #0F172A, #38BDF8)',
                'btn_cta'      => 'linear-gradient(135deg, #38BDF8 0%, #0284C7 100%)',
                'btn_cta_text' => '#0F172A',
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
            min-height: 100vh;
            position: relative;
        }

        /* ── Floating Back to Home Button ──────────── */
        .btn-back-home {
            position: absolute;
            top: 24px;
            left: 28px;
            z-index: 100;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 100px;
            color: #FFFFFF;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.12);
            transition: all 0.25s ease;
        }
        .btn-back-home:hover {
            background: rgba(255, 255, 255, 0.35);
            color: #FFFFFF;
            transform: translateX(-4px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
        @media (max-width: 992px) {
            .btn-back-home {
                background: #FFFFFF;
                color: #1E293B;
                border: 1.5px solid #E2E8F0;
                box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                top: 16px;
                left: 16px;
            }
            .btn-back-home:hover {
                background: #F8FAFC;
                color: var(--primary);
                border-color: var(--primary);
                transform: translateX(-3px);
            }
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

        /* Feature Cards Grid */
        .feature-cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            max-width: 580px;
            position: relative;
            z-index: 10;
        }
        .feature-pill-card {
            background: rgba(255, 255, 255, 0.92);
            border: 2px solid rgba(255, 255, 255, 1);
            border-radius: 16px;
            padding: 18px 16px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25), 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        .feature-pill-card:hover {
            background: #FFFFFF;
            transform: translateY(-5px) scale(1.03);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.3), 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .feature-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .feature-title {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 3px;
            letter-spacing: -0.01em;
        }
        .feature-desc {
            font-size: 11.5px;
            color: #475569;
            line-height: 1.5;
            margin: 0;
            font-weight: 500;
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

        /* ── GSAP Animated Decorations ───────────── */
        .gsap-orb {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            opacity: 0;
            will-change: transform, opacity;
        }
        .gsap-orb-1 {
            width: 300px; height: 300px;
            background: radial-gradient(circle at 40% 40%, rgba(255,255,255,0.18), rgba(255,255,255,0.03));
            border: 1.5px solid rgba(255,255,255,0.2);
            top: -80px; right: -80px;
        }
        .gsap-orb-2 {
            width: 180px; height: 180px;
            background: radial-gradient(circle, rgba(255,255,255,0.12), transparent);
            border: 1px solid rgba(255,255,255,0.15);
            bottom: 8%; right: 6%;
        }
        .gsap-orb-3 {
            width: 100px; height: 100px;
            background: var(--accent);
            filter: blur(30px);
            bottom: 18%; left: 6%;
            opacity: 0;
        }
        .gsap-orb-4 {
            width: 60px; height: 60px;
            background: var(--accent);
            filter: blur(18px);
            top: 30%; left: 3%;
            opacity: 0;
        }
        /* Floating particles */
        .hero-particle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            opacity: 0;
            will-change: transform, opacity;
        }
        .gsap-star-1, .gsap-star-2, .gsap-star-3 {
            position: absolute;
            pointer-events: none;
            opacity: 0;
            will-change: transform, opacity;
        }
        .gsap-star-1 { top: 12%; right: 14%; }
        .gsap-star-2 { bottom: 18%; right: 10%; }
        .gsap-star-3 { top: 42%; left: 5%; }
        /* Icon box shimmer */
        .feature-icon-box {
            position: relative;
            overflow: hidden;
        }
        .feature-icon-box::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 40%;
            height: 200%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
            transform: skewX(-20deg);
            opacity: 0;
        }
        .hero-content-inner {
            position: relative;
            z-index: 10;
            will-change: transform;
        }
        .welcome-icon-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px; height: 60px;
            border-radius: 18px;
            background: rgba(255,255,255,0.18);
            border: 1.5px solid rgba(255,255,255,0.35);
            margin-bottom: 20px;
            font-size: 28px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.3);
        }
    </style>
</head>
<body>

    <!-- ── Main Split Screen ────────────────────────── -->
    <main class="login-split-container">
        
        <!-- Tombol Navigasi Kembali ke Beranda -->
        <a href="{{ route('yayasan.landing', $yayasan->kode_yayasan) }}" class="btn-back-home">
            <iconify-icon icon="solar:alt-arrow-left-bold" style="font-size: 18px;"></iconify-icon>
            <span>Kembali ke Beranda</span>
        </a>
        
        <!-- Left Panel: Hero Banner (60%) -->
        <div class="hero-panel-left" id="hero-panel">

            <!-- GSAP animated orb decorations -->
            <div class="gsap-orb gsap-orb-1" id="orb1"></div>
            <div class="gsap-orb gsap-orb-2" id="orb2"></div>
            <div class="gsap-orb gsap-orb-3" id="orb3"></div>
            <div class="gsap-orb gsap-orb-4" id="orb4"></div>

            <!-- Star accent icons -->
            <div class="gsap-star-1" id="star1">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="var(--accent)"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <div class="gsap-star-2" id="star2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="rgba(255,255,255,0.7)"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <div class="gsap-star-3" id="star3">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="var(--accent)" opacity="0.6"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <!-- Floating particles (injected by JS) -->
            <div id="particles-container"></div>

            <!-- Hero content -->
            <div class="hero-content-inner">
                <div class="welcome-icon-wrap" id="welcome-icon">
                    <iconify-icon icon="solar:hand-stars-bold-duotone" style="font-size: 26px; color: #fff;"></iconify-icon>
                </div>
                <h2 class="hero-heading" id="hero-heading">Selamat Datang Kembali!</h2>
                <p class="hero-subtext" id="hero-subtext">
                    Masuk ke Sistem Informasi {{ $siteTitle }} untuk mengelola data dan perkembangan sekolah.
                </p>

                <div class="feature-cards-grid" id="feature-grid">
                    <div class="feature-pill-card">
                        <div class="feature-icon-box">
                            <iconify-icon icon="solar:chart-2-bold-duotone" style="font-size:24px;color:#fff;"></iconify-icon>
                        </div>
                        <div>
                            <div class="feature-title">Informasi Akademik</div>
                            <p class="feature-desc">Pantau nilai, perkembangan, dan laporan</p>
                        </div>
                    </div>
                    <div class="feature-pill-card">
                        <div class="feature-icon-box">
                            <iconify-icon icon="solar:bell-bing-bold-duotone" style="font-size:24px;color:#fff;"></iconify-icon>
                        </div>
                        <div>
                            <div class="feature-title">Pemberitahuan Sistem</div>
                            <p class="feature-desc">Terima notifikasi presensi dan pengumuman</p>
                        </div>
                    </div>
                    <div class="feature-pill-card">
                        <div class="feature-icon-box">
                            <iconify-icon icon="solar:notebook-bold-duotone" style="font-size:24px;color:#fff;"></iconify-icon>
                        </div>
                        <div>
                            <div class="feature-title">Jurnal &amp; Materi</div>
                            <p class="feature-desc">Kelola jurnal harian dan materi pembelajaran</p>
                        </div>
                    </div>
                    <div class="feature-pill-card">
                        <div class="feature-icon-box">
                            <iconify-icon icon="solar:link-circle-bold-duotone" style="font-size:24px;color:#fff;"></iconify-icon>
                        </div>
                        <div>
                            <div class="feature-title">Terintegrasi</div>
                            <p class="feature-desc">Platform multi-sekolah yang aman dan handal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Form Section (40%) -->
        <div class="form-panel-right">
            <h2 class="form-title">Masuk Sistem</h2>
            <p class="form-subtitle">Masuk ke akun portal {{ $siteTitle }} Anda.</p>

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

            {{-- Quick Demo Accounts Grid for this Specific Yayasan --}}
            @if(isset($demo_accounts) && array_filter($demo_accounts))
            <div class="demo-accounts-card">
                <div class="demo-accounts-title">
                    <iconify-icon icon="solar:bolt-bold" style="color: var(--primary);"></iconify-icon>
                    <span>Demo Akun {{ $siteTitle }}:</span>
                </div>
                <div class="demo-grid">
                    @if(!empty($demo_accounts['wali_murid']))
                    <button type="button" onclick="fillCredentials('{{ $demo_accounts['wali_murid']->email }}')" class="btn-quick-fill">
                        <span class="role-name">👨‍👩‍👧 Wali Murid</span>
                        <span class="email-text">{{ $demo_accounts['wali_murid']->email }}</span>
                    </button>
                    @endif
                    @if(!empty($demo_accounts['guru']))
                    <button type="button" onclick="fillCredentials('{{ $demo_accounts['guru']->email }}')" class="btn-quick-fill">
                        <span class="role-name">👩‍🏫 Guru TK</span>
                        <span class="email-text">{{ $demo_accounts['guru']->email }}</span>
                    </button>
                    @endif
                    @if(!empty($demo_accounts['admin']))
                    <button type="button" onclick="fillCredentials('{{ $demo_accounts['admin']->email }}')" class="btn-quick-fill">
                        <span class="role-name">🏢 Admin TK</span>
                        <span class="email-text">{{ $demo_accounts['admin']->email }}</span>
                    </button>
                    @endif
                    @if(!empty($demo_accounts['kepsek']))
                    <button type="button" onclick="fillCredentials('{{ $demo_accounts['kepsek']->email }}')" class="btn-quick-fill">
                        <span class="role-name">🎓 Kepsek</span>
                        <span class="email-text">{{ $demo_accounts['kepsek']->email }}</span>
                    </button>
                    @endif
                    @if(!empty($demo_accounts['super_admin']))
                    <button type="button" onclick="fillCredentials('{{ $demo_accounts['super_admin']->email }}')" class="btn-quick-fill" style="grid-column: span 2;">
                        <span class="role-name">⚙️ Super Admin {{ $siteTitle }}</span>
                        <span class="email-text">{{ $demo_accounts['super_admin']->email }}</span>
                    </button>
                    @endif
                </div>
            </div>
            @endif

            <div class="text-center mt-4">
                <p class="text-muted small mb-0" style="font-size: 13.5px;">
                    Belum punya akun?  
                    <a href="{{ route('yayasan.pendaftaran', $yayasan->kode_yayasan) }}" class="fw-bold text-decoration-none ms-1" style="color: var(--primary);">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- GSAP Core + Plugins -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
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

        /* ═══════════════════════════════════════════════════
           GSAP PREMIUM ANIMATION SYSTEM — Theme-Aware
        ═══════════════════════════════════════════════════ */
        document.addEventListener('DOMContentLoaded', function () {
            const panel = document.getElementById('hero-panel');
            if (!panel || typeof gsap === 'undefined') return;

            // ── Read active theme colors from CSS vars ──
            const style    = getComputedStyle(document.documentElement);
            const primary  = style.getPropertyValue('--primary').trim();
            const accent   = style.getPropertyValue('--accent').trim();

            // ═══════════════════════════════════════
            //  1. MASTER TIMELINE — entrance sequence
            // ═══════════════════════════════════════
            const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

            // Panel slides in from left
            tl.fromTo(panel,
                { x: -40, opacity: 0 },
                { x: 0, opacity: 1, duration: 0.6 }
            )

            // Orbs pop in with bounce
            .fromTo(['#orb1', '#orb2'],
                { scale: 0, opacity: 0 },
                { scale: 1, opacity: 1, duration: 0.7, stagger: 0.12, ease: 'back.out(1.5)' },
                '-=0.35'
            )

            // Accent glow orbs fade in
            .fromTo(['#orb3', '#orb4'],
                { scale: 0.3, opacity: 0 },
                { scale: 1, opacity: 0.45, duration: 0.8, stagger: 0.1, ease: 'power2.out' },
                '-=0.5'
            )

            // Welcome icon drops in
            .fromTo('#welcome-icon',
                { y: -30, opacity: 0, scale: 0.6, rotation: -15 },
                { y: 0, opacity: 1, scale: 1, rotation: 0, duration: 0.55, ease: 'back.out(2)' },
                '-=0.3'
            )

            // Heading sweeps up
            .fromTo('#hero-heading',
                { y: 30, opacity: 0, skewX: -3 },
                { y: 0, opacity: 1, skewX: 0, duration: 0.5, ease: 'power3.out' },
                '-=0.2'
            )

            // Subtext fades in
            .fromTo('#hero-subtext',
                { y: 20, opacity: 0 },
                { y: 0, opacity: 1, duration: 0.45 },
                '-=0.25'
            )

            // Feature cards pop in with stagger + bounce
            .fromTo('#feature-grid .feature-pill-card',
                { y: 30, opacity: 0, scale: 0.88 },
                {
                    y: 0, opacity: 1, scale: 1,
                    duration: 0.5,
                    stagger: { amount: 0.35, from: 'start' },
                    ease: 'back.out(1.8)',
                    clearProps: 'all'
                },
                '-=0.2'
            )

            // Stars appear
            .fromTo(['#star1', '#star2', '#star3'],
                { scale: 0, opacity: 0 },
                { scale: 1, opacity: 1, duration: 0.4, stagger: 0.08, ease: 'back.out(2)' },
                '-=0.4'
            );

            // ═══════════════════════════════════════
            //  2. LOOPING AMBIENT ANIMATIONS
            // ═══════════════════════════════════════

            // Orb 1 — slow drift, large circle
            gsap.to('#orb1', {
                x: 20, y: -30,
                duration: 9,
                repeat: -1, yoyo: true,
                ease: 'sine.inOut',
                delay: 0.7
            });

            // Orb 2 — medium drift, opposite direction
            gsap.to('#orb2', {
                x: -16, y: 22,
                duration: 6.5,
                repeat: -1, yoyo: true,
                ease: 'sine.inOut',
                delay: 1.2
            });

            // Accent glow orb 3 — pulse brightness
            gsap.to('#orb3', {
                scale: 1.35,
                opacity: 0.65,
                x: 10, y: -18,
                duration: 4.5,
                repeat: -1, yoyo: true,
                ease: 'sine.inOut',
                delay: 0.5
            });

            // Accent glow orb 4 — pulse brightness (offset)
            gsap.to('#orb4', {
                scale: 1.5,
                opacity: 0.55,
                x: -8, y: 14,
                duration: 5.2,
                repeat: -1, yoyo: true,
                ease: 'sine.inOut',
                delay: 1.8
            });

            // Stars — continuous rotation & bob
            gsap.to('#star1', { rotation: '+=360', duration: 16, repeat: -1, ease: 'none', delay: 1 });
            gsap.to('#star2', { y: -12, duration: 3.5, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 0.5 });
            gsap.to('#star3', { y: 10, rotation: '-=180', duration: 5, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 1.5 });

            // Welcome icon — breathing pulse
            gsap.to('#welcome-icon', {
                scale: 1.08,
                boxShadow: '0 0 30px rgba(255,255,255,0.3)',
                duration: 2.2,
                repeat: -1, yoyo: true,
                ease: 'sine.inOut',
                delay: 1.5
            });

            // Content inner — slow gentle float (reduced motion)
            gsap.to('#hero-panel .hero-content-inner', {
                y: -7,
                duration: 6,
                repeat: -1, yoyo: true,
                ease: 'sine.inOut',
                delay: 2
            });

            // ═══════════════════════════════════════
            //  3. ICON BOX SHIMMER — sequential sweep
            // ═══════════════════════════════════════
            const iconBoxes = document.querySelectorAll('.feature-icon-box');
            iconBoxes.forEach((box, i) => {
                const shimmer = box.querySelector('::after');
                gsap.to(box, {
                    '--shimmer-x': '150%',
                    duration: 0.6,
                    delay: 1.2 + i * 0.25,
                    ease: 'power2.inOut',
                    repeat: -1,
                    repeatDelay: 3.5 + i * 0.5
                });
                // Glow pulse on icon boxes
                gsap.to(box, {
                    boxShadow: `0 6px 24px ${accent}55`,
                    duration: 1.8,
                    repeat: -1, yoyo: true,
                    ease: 'sine.inOut',
                    delay: 1.5 + i * 0.4
                });
            });

            // ═══════════════════════════════════════
            //  4. FLOATING PARTICLES (theme-colored)
            // ═══════════════════════════════════════
            const container = document.getElementById('particles-container');
            if (container) {
                for (let i = 0; i < 12; i++) {
                    const dot = document.createElement('div');
                    const size = Math.random() * 5 + 3;
                    const isAccent = i % 3 === 0;
                    dot.className = 'hero-particle';
                    dot.style.cssText = [
                        `width:${size}px`, `height:${size}px`,
                        `background:${isAccent ? accent : 'rgba(255,255,255,0.6)'}`,
                        `left:${Math.random() * 90}%`,
                        `top:${Math.random() * 90}%`,
                        `border-radius:50%`
                    ].join(';');
                    container.appendChild(dot);

                    // Each particle floats independently
                    gsap.to(dot, {
                        opacity: Math.random() * 0.6 + 0.2,
                        y: (Math.random() - 0.5) * 40,
                        x: (Math.random() - 0.5) * 20,
                        duration: Math.random() * 4 + 3,
                        repeat: -1, yoyo: true,
                        ease: 'sine.inOut',
                        delay: Math.random() * 2
                    });
                }
            }

            // ═══════════════════════════════════════
            //  5. MOUSE PARALLAX on hero panel
            // ═══════════════════════════════════════
            panel.addEventListener('mousemove', function(e) {
                const rect  = panel.getBoundingClientRect();
                const cx    = rect.width  / 2;
                const cy    = rect.height / 2;
                const dx    = (e.clientX - rect.left - cx) / cx;
                const dy    = (e.clientY - rect.top  - cy) / cy;

                gsap.to('#orb1', { x: dx * 18, y: dy * 12, duration: 1, ease: 'power1.out', overwrite: 'auto' });
                gsap.to('#orb2', { x: dx * -12, y: dy * -8, duration: 1.2, ease: 'power1.out', overwrite: 'auto' });
                gsap.to('#orb3', { x: dx * 22, y: dy * 16, duration: 0.8, ease: 'power1.out', overwrite: 'auto' });
                gsap.to('#star1', { x: dx * -10, y: dy * -7, duration: 1, ease: 'power1.out', overwrite: 'auto' });
                gsap.to('#star2', { x: dx * 8, y: dy * 6, duration: 1.3, ease: 'power1.out', overwrite: 'auto' });
                gsap.to('.hero-content-inner', { x: dx * 5, y: dy * 4, duration: 1.5, ease: 'power1.out', overwrite: 'auto' });
            });

            panel.addEventListener('mouseleave', function() {
                gsap.to(['#orb1','#orb2','#orb3','#star1','#star2','.hero-content-inner'], {
                    x: 0, y: 0, duration: 1.2, ease: 'power2.out', overwrite: 'auto'
                });
            });

            // ═══════════════════════════════════════
            //  6. CARD HOVER micro-animations
            // ═══════════════════════════════════════
            document.querySelectorAll('.feature-pill-card').forEach(card => {
                const iconBox = card.querySelector('.feature-icon-box');
                card.addEventListener('mouseenter', () => {
                    gsap.to(card, { y: -6, scale: 1.03, duration: 0.3, ease: 'back.out(2)' });
                    if (iconBox) gsap.to(iconBox, { scale: 1.15, rotation: 8, duration: 0.35, ease: 'back.out(2)' });
                });
                card.addEventListener('mouseleave', () => {
                    gsap.to(card, { y: 0, scale: 1, duration: 0.35, ease: 'power2.out' });
                    if (iconBox) gsap.to(iconBox, { scale: 1, rotation: 0, duration: 0.35, ease: 'power2.out' });
                });
            });
        });
    </script>
</body>
</html>
