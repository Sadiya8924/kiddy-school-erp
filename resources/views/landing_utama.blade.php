@php
    $platformName = \App\Models\Setting::get('saas_platform_name', 'Kiddy School ERP');
    $platformTagline = \App\Models\Setting::get('saas_platform_tagline', 'MULTI-TENANT PLATFORM');
    $platformLogo = \App\Models\Setting::get('site_logo');
    $headerCta = \App\Models\Setting::get('saas_header_cta_text', 'Daftar Sekarang');

    $heroBadge = \App\Models\Setting::get('saas_hero_badge', 'SaaS Multi-Tenant Terpercaya Khusus Yayasan & Sekolah Anak Usia Dini');
    $heroTitle1 = \App\Models\Setting::get('saas_hero_title_1', 'Solusi ERP Modern & Terpadu untuk');
    $heroTitleHighlight = \App\Models\Setting::get('saas_hero_title_highlight', 'Manajemen Yayasan TK & PAUD');
    $heroDesc = \App\Models\Setting::get('saas_hero_desc', 'Kelola seluruh cabang sekolah, ribuan siswa, e-rapor Kurikulum Merdeka, presensi harian, hingga portal mandiri orang tua dalam satu sistem cloud terintegrasi dengan jaminan isolasi data penuh.');
    $heroBtnPrimary = \App\Models\Setting::get('saas_hero_btn_primary', 'Daftarkan Yayasan Anda');
    $heroBtnSecondary = \App\Models\Setting::get('saas_hero_btn_secondary', 'Pelajari Fitur & Modul');

    $stat1Num = isset($total_yayasan) ? $total_yayasan . ' Yayasan' : \App\Models\Setting::get('saas_stat_1_num', '2 Yayasan');
    $stat1Lbl = \App\Models\Setting::get('saas_stat_1_label', 'Yayasan Terdaftar');
    $stat2Num = isset($total_sekolah) ? $total_sekolah . ' Cabang' : \App\Models\Setting::get('saas_stat_2_num', '4 Cabang');
    $stat2Lbl = \App\Models\Setting::get('saas_stat_2_label', 'Cabang Sekolah TK/PAUD');
    $stat3Num = isset($total_siswa) ? $total_siswa . ' Siswa' : \App\Models\Setting::get('saas_stat_3_num', '120 Siswa');
    $stat3Lbl = \App\Models\Setting::get('saas_stat_3_label', 'Siswa Aktif Terdaftar');
    $stat4Num = isset($total_guru) ? $total_guru . ' Pengajar' : \App\Models\Setting::get('saas_stat_4_num', '16 Pengajar');
    $stat4Lbl = \App\Models\Setting::get('saas_stat_4_label', 'Tenaga Pendidik & Staf');

    $step1Title = \App\Models\Setting::get('saas_step_1_title', '1. Daftarkan Yayasan');
    $step1Desc = \App\Models\Setting::get('saas_step_1_desc', 'Isi formulir identitas yayasan dan lokasi GPS melalui portal pendaftaran.');
    $step2Title = \App\Models\Setting::get('saas_step_2_title', '2. Verifikasi & Aktivasi');
    $step2Desc = \App\Models\Setting::get('saas_step_2_desc', 'Admin platform memverifikasi data dan akun Super Admin otomatis dibuat.');
    $step3Title = \App\Models\Setting::get('saas_step_3_title', '3. Atur Cabang & Guru');
    $step3Desc = \App\Models\Setting::get('saas_step_3_desc', 'Super Admin menambahkan cabang TK/PAUD, akun guru, dan kelas.');
    $step4Title = \App\Models\Setting::get('saas_step_4_title', '4. Siap Digunakan');
    $step4Desc = \App\Models\Setting::get('saas_step_4_desc', 'Sistem siap melayani PPDB, absensi, e-rapor, dan portal wali murid.');

    $ctaTitle = \App\Models\Setting::get('saas_cta_title', 'Siap Mentransformasi Digital Yayasan & Sekolah Anda?');
    $ctaDesc = \App\Models\Setting::get('saas_cta_desc', 'Bergabunglah bersama puluhan yayasan pendidikan lainnya yang telah menggunakan platform Kiddy School ERP.');
    $ctaBtn = \App\Models\Setting::get('saas_cta_btn_text', 'Daftarkan Yayasan Anda Sekarang');

    $footerAbout = \App\Models\Setting::get('saas_footer_about', 'Platform SaaS ERP Manajemen Yayasan TK & PAUD terintegrasi dengan arsitektur multi-tenant modern.');
    $contactEmail = \App\Models\Setting::get('saas_contact_email', 'support@kiddyschool.id');
    $contactPhone = \App\Models\Setting::get('saas_contact_phone', '+62 812-3456-7890');
    $contactAddress = \App\Models\Setting::get('saas_contact_address', 'Gedung Cyber Tech Lt. 5, Jakarta, Indonesia');

    if (!isset($yayasans)) {
        $yayasans = \App\Models\Yayasan::where('status', 'aktif')
            ->with(['schools' => function($q) {
                $q->where('status', 'aktif')->orderBy('nama_sekolah', 'asc');
            }])
            ->orderBy('nama_yayasan', 'asc')
            ->get();
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $platformName }} — Platform Manajemen Yayasan TK & PAUD Terpadu</title>
    <meta name="description" content="{{ $heroDesc }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & Iconify -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F8FAFC;
            color: #1E293B;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        /* ── Top Nav ────────────────────────────────── */
        .top-nav {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226,232,240,0.8);
            padding: 14px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 24px rgba(0,0,0,0.04);
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #0F172A;
            font-weight: 800;
            font-size: 17px;
            letter-spacing: -0.02em;
        }
        .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 20px;
            box-shadow: 0 4px 14px rgba(37,99,235,0.30);
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-links a {
            text-decoration: none;
            color: #64748B;
            font-size: 13.5px;
            font-weight: 600;
            letter-spacing: -0.01em;
            transition: color 0.2s;
            position: relative;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0; right: 0;
            height: 2px;
            background: #2563EB;
            border-radius: 2px;
            transform: scaleX(0);
            transition: transform 0.2s;
        }
        .nav-links a:hover { color: #2563EB; }
        .nav-links a:hover::after { transform: scaleX(1); }
        .btn-header-cta {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
            color: #fff !important;
            font-weight: 700;
            font-size: 13.5px;
            padding: 10px 24px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 16px rgba(37,99,235,0.35);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            border: none;
        }
        .btn-header-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37,99,235,0.45);
            color: #fff;
        }
        .btn-header-login {
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            color: #475569;
            font-weight: 700;
            font-size: 13.5px;
            padding: 9px 20px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-header-login:hover {
            border-color: #2563EB;
            color: #2563EB;
            background: #EFF6FF;
        }

        /* ── Hero Section ───────────────────────────── */
        .hero-section {
            background:
                radial-gradient(ellipse at 75% 10%, rgba(30,64,175,0.95) 0%, transparent 55%),
                radial-gradient(ellipse at 20% 80%, rgba(30,58,138,0.80) 0%, transparent 50%),
                #0B1120;
            padding: 108px 24px 96px;
            text-align: center;
            position: relative;
            overflow: hidden;
            color: #fff;
        }
        /* dot-grid overlay */
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.055) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }
        /* central ambient glow */
        .hero-glow {
            position: absolute;
            top: -80px; left: 50%;
            transform: translateX(-50%);
            width: 800px; height: 500px;
            background: radial-gradient(ellipse, rgba(59,130,246,0.18) 0%, transparent 68%);
            pointer-events: none;
            animation: heroPulse 7s ease-in-out infinite alternate;
        }
        @keyframes heroPulse {
            from { opacity: 0.6; transform: translateX(-50%) scale(0.95); }
            to   { opacity: 1;   transform: translateX(-50%) scale(1.12); }
        }
        /* floating decorative circles */
        .hero-shape {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }
        .hero-shape-1 {
            width: 380px; height: 380px;
            border: 1px solid rgba(96,165,250,0.08);
            top: -100px; right: -80px;
            animation: floatShape 12s ease-in-out infinite alternate;
        }
        .hero-shape-2 {
            width: 240px; height: 240px;
            border: 1px solid rgba(99,102,241,0.10);
            bottom: 20px; left: -60px;
            animation: floatShape 9s ease-in-out infinite alternate-reverse;
        }
        .hero-shape-3 {
            width: 100px; height: 100px;
            background: rgba(52,211,153,0.07);
            top: 25%; right: 7%;
            animation: floatShape 7s ease-in-out infinite alternate;
        }
        @keyframes floatShape {
            from { transform: translateY(0) rotate(0deg); }
            to   { transform: translateY(-28px) rotate(15deg); }
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.18);
            color: #93C5FD;
            border-radius: 100px;
            padding: 8px 22px;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            position: relative; z-index: 2;
            letter-spacing: 0.01em;
        }
        .hero-title {
            font-size: clamp(36px, 5.5vw, 62px);
            font-weight: 800;
            color: #FFFFFF;
            line-height: 1.10;
            letter-spacing: -0.04em;
            max-width: 900px;
            margin: 0 auto 22px;
            position: relative; z-index: 2;
        }
        .hero-title span {
            background: linear-gradient(135deg, #60A5FA 0%, #BAE0FF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-sub {
            font-size: 16.5px;
            color: #94A3B8;
            max-width: 660px;
            margin: 0 auto 44px;
            line-height: 1.82;
            position: relative; z-index: 2;
        }
        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 60px;
            position: relative; z-index: 2;
        }
        .btn-hero-main {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 50%, #1D4ED8 100%);
            color: #fff;
            font-weight: 700;
            font-size: 15.5px;
            padding: 16px 40px;
            border-radius: 100px;
            text-decoration: none;
            box-shadow: 0 8px 32px rgba(37,99,235,0.45);
            transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
            border: 1px solid rgba(255,255,255,0.18);
            display: inline-flex;
            align-items: center;
            gap: 9px;
            position: relative;
            overflow: hidden;
        }
        .btn-hero-main::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
            transition: left 0.5s;
        }
        .btn-hero-main:hover::before { left: 100%; }
        .btn-hero-main:hover {
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(37,99,235,0.55);
        }
        .btn-hero-outline {
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.22);
            color: rgba(255,255,255,0.90);
            font-weight: 600;
            font-size: 15.5px;
            padding: 15px 36px;
            border-radius: 100px;
            text-decoration: none;
            transition: all 0.25s;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .btn-hero-outline:hover {
            background: rgba(255,255,255,0.14);
            border-color: rgba(255,255,255,0.45);
            color: #fff;
            transform: translateY(-2px);
        }

        .hero-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            max-width: 880px;
            margin: 0 auto;
            border-top: 1px solid rgba(255,255,255,0.10);
            padding-top: 44px;
            position: relative; z-index: 2;
        }
        .hero-stat-card {
            text-align: center;
            padding: 0 24px;
            border-right: 1px solid rgba(255,255,255,0.08);
        }
        .hero-stat-card:last-child { border-right: none; }
        .hero-stat-card .num {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, #60A5FA, #93C5FD);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }
        .hero-stat-card .lbl {
            font-size: 12px;
            color: #64748B;
            margin-top: 7px;
            font-weight: 500;
            line-height: 1.45;
        }

        /* ── Client / Partner Logos (Dynamic Yayasan & TK) ─── */
        .clients-section {
            background: #FFFFFF;
            border-bottom: 1px solid #E8EDF5;
            padding: 46px 24px;
            text-align: center;
            position: relative;
            z-index: 30;
        }
        .clients-title {
            font-size: 11.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #94A3B8;
            margin-bottom: 8px;
        }
        .clients-subtitle {
            font-size: 13px;
            color: #64748B;
            margin-bottom: 24px;
            font-weight: 500;
        }
        .clients-logos {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .yayasan-wrapper {
            position: relative;
            display: inline-block;
        }
        .yayasan-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            border-radius: 100px;
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            font-size: 13.5px;
            font-weight: 700;
            color: #334155;
            cursor: pointer;
            user-select: none;
            transition: all 0.26s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
            outline: none;
        }
        .yayasan-badge:hover,
        .yayasan-wrapper.is-open .yayasan-badge {
            background: #EFF6FF;
            border-color: #93C5FD;
            color: #1D4ED8;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.12);
        }
        .yayasan-badge:focus-visible {
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }
        .yayasan-icon {
            font-size: 18px;
            color: #3B82F6;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }
        .yayasan-badge:hover .yayasan-icon,
        .yayasan-wrapper.is-open .yayasan-icon {
            transform: scale(1.1);
        }
        .yayasan-school-count {
            font-size: 11px;
            font-weight: 700;
            background: #E2E8F0;
            color: #475569;
            padding: 3px 8px;
            border-radius: 20px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .yayasan-badge:hover .yayasan-school-count,
        .yayasan-wrapper.is-open .yayasan-school-count {
            background: #DBEAFE;
            color: #1D4ED8;
        }
        .yayasan-chevron {
            font-size: 13px;
            color: #94A3B8;
            transition: transform 0.26s cubic-bezier(0.16, 1, 0.3, 1), color 0.2s ease;
            margin-left: 1px;
        }
        .yayasan-badge:hover .yayasan-chevron,
        .yayasan-wrapper.is-open .yayasan-chevron {
            transform: rotate(180deg);
            color: #2563EB;
        }
        /* Dropdown Popover */
        .yayasan-schools-popover {
            position: absolute;
            top: calc(100% + 12px);
            left: 50%;
            transform: translateX(-50%) translateY(8px);
            width: 320px;
            max-width: 90vw;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 20px;
            box-shadow: 0 20px 45px -8px rgba(15, 23, 42, 0.16), 0 6px 16px rgba(15, 23, 42, 0.06);
            text-align: left;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.24s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.24s cubic-bezier(0.16, 1, 0.3, 1),
                        visibility 0.24s;
        }
        .yayasan-schools-popover::before {
            content: '';
            position: absolute;
            top: -7px;
            left: 50%;
            transform: translateX(-50%) rotate(45deg);
            width: 14px;
            height: 14px;
            background: #FFFFFF;
            border-top: 1px solid #E2E8F0;
            border-left: 1px solid #E2E8F0;
            border-radius: 2px;
            z-index: 1;
        }
        .yayasan-wrapper:hover .yayasan-schools-popover,
        .yayasan-wrapper.is-open .yayasan-schools-popover {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
            pointer-events: auto;
        }
        .popover-header {
            padding: 16px 18px 12px;
            background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%);
            border-bottom: 1px solid #F1F5F9;
            border-radius: 20px 20px 0 0;
            position: relative;
            z-index: 2;
        }
        .popover-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #2563EB;
            background: #EFF6FF;
            padding: 3px 8px;
            border-radius: 6px;
            margin-bottom: 6px;
        }
        .popover-title {
            font-size: 14px;
            font-weight: 800;
            color: #0F172A;
            line-height: 1.3;
        }
        .popover-subtitle {
            font-size: 11.5px;
            color: #64748B;
            margin-top: 2px;
        }
        .popover-body {
            padding: 8px 10px;
            max-height: 240px;
            overflow-y: auto;
            position: relative;
            z-index: 2;
        }
        .popover-body::-webkit-scrollbar {
            width: 4px;
        }
        .popover-body::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 4px;
        }
        .school-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 10px;
            border-radius: 12px;
            transition: background 0.18s ease;
            text-decoration: none;
            color: inherit;
        }
        .school-item:hover {
            background: #F8FAFC;
        }
        .school-icon-wrap {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #EFF6FF;
            color: #2563EB;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            flex-shrink: 0;
            transition: transform 0.2s ease, background 0.2s ease;
        }
        .school-item:hover .school-icon-wrap {
            background: #DBEAFE;
            transform: scale(1.06);
        }
        .school-info {
            flex: 1;
            min-width: 0;
            text-align: left;
        }
        .school-name {
            font-size: 13px;
            font-weight: 700;
            color: #1E293B;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .school-meta {
            font-size: 11px;
            color: #94A3B8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 1px;
        }
        .school-tag {
            font-size: 10.5px;
            font-weight: 700;
            color: #16A34A;
            background: #DCFCE7;
            padding: 2px 7px;
            border-radius: 20px;
            flex-shrink: 0;
        }
        .school-empty {
            padding: 20px 14px;
            text-align: center;
            font-size: 12.5px;
            color: #94A3B8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .popover-footer {
            padding: 10px 14px 12px;
            border-top: 1px solid #F1F5F9;
            background: #FFFFFF;
            border-radius: 0 0 20px 20px;
            position: relative;
            z-index: 2;
        }
        .popover-cta-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            font-weight: 700;
            color: #2563EB;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 10px;
            background: #EFF6FF;
            transition: all 0.2s ease;
        }
        .popover-cta-btn:hover {
            background: #DBEAFE;
            color: #1D4ED8;
            transform: translateY(-1px);
        }

        /* ── How It Works (Alur Cara Kerja) ──────────── */
        .workflow-section {
            padding: 96px 24px;
            background: linear-gradient(180deg, #F8FAFC 0%, #ffffff 100%);
        }
        .section-header {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 60px;
        }
        .section-tag {
            display: inline-block;
            background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
            color: #2563EB;
            border: 1px solid #BFDBFE;
            font-size: 10.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 5px 16px;
            border-radius: 100px;
            margin-bottom: 16px;
        }
        .section-heading {
            font-size: 36px;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.03em;
            margin-bottom: 14px;
            line-height: 1.2;
        }
        .section-desc {
            font-size: 15.5px;
            color: #64748B;
            line-height: 1.75;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            max-width: 1140px;
            margin: 0 auto;
            position: relative;
        }
        /* horizontal connector line */
        .steps-grid::before {
            content: '';
            position: absolute;
            top: 46px;
            left: calc(12.5% + 24px);
            right: calc(12.5% + 24px);
            height: 2px;
            background: linear-gradient(90deg, #BFDBFE 0%, #60A5FA 50%, #BFDBFE 100%);
            z-index: 0;
            border-radius: 2px;
        }
        .step-card {
            background: #FFFFFF;
            border: 1.5px solid #E8EDF5;
            border-radius: 22px;
            padding: 34px 24px 30px;
            position: relative;
            transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
            box-shadow: 0 2px 12px rgba(0,0,0,0.03);
            margin: 0 10px;
            z-index: 1;
        }
        .step-card:hover {
            border-color: #60A5FA;
            box-shadow: 0 18px 44px rgba(37,99,235,0.10);
            transform: translateY(-7px);
        }
        .step-number {
            width: 50px; height: 50px;
            border-radius: 14px;
            background: linear-gradient(135deg, #3B82F6, #1D4ED8);
            color: #fff;
            font-size: 18px;
            font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 22px;
            box-shadow: 0 8px 20px rgba(37,99,235,0.30);
        }
        .step-title {
            font-size: 15.5px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 10px;
            letter-spacing: -0.01em;
        }
        .step-desc {
            font-size: 13px;
            color: #64748B;
            line-height: 1.7;
        }

        /* ── Modul Lengkap (Feature Breakdown) ──────── */
        .features-section {
            padding: 96px 24px;
            background: #FFFFFF;
            border-top: 1px solid #E8EDF5;
            border-bottom: 1px solid #E8EDF5;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 22px;
            max-width: 1140px;
            margin: 0 auto;
        }
        .feature-card {
            background: #FAFBFF;
            border: 1.5px solid #E8EDF5;
            border-radius: 22px;
            padding: 30px;
            transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
            position: relative;
            overflow: hidden;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--accent-gradient, linear-gradient(90deg, #2563EB, #60A5FA));
            border-radius: 22px 22px 0 0;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .feature-card:hover::before { opacity: 1; }
        .feature-card:hover {
            background: #FFFFFF;
            border-color: #C7D9F8;
            box-shadow: 0 18px 44px rgba(37,99,235,0.09);
            transform: translateY(-6px);
        }
        .feature-icon-wrapper {
            width: 58px; height: 58px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.07);
        }
        .feature-title {
            font-size: 17px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 10px;
            letter-spacing: -0.01em;
        }
        .feature-desc {
            font-size: 13.5px;
            color: #64748B;
            line-height: 1.72;
            margin-bottom: 18px;
        }
        .feature-points {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .feature-points li {
            font-size: 12.5px;
            color: #334155;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.5;
        }
        .feature-points li iconify-icon {
            color: #10B981;
            font-size: 16px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* ── Data Isolation & Security Section ──────── */
        .security-section {
            padding: 96px 24px;
            background: linear-gradient(145deg, #060D1F 0%, #0B1120 45%, #0F172A 100%);
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .security-section::before {
            content: '';
            position: absolute;
            top: -160px; right: -120px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(37,99,235,0.16) 0%, transparent 60%);
            pointer-events: none;
        }
        .security-section::after {
            content: '';
            position: absolute;
            bottom: -120px; left: -100px;
            width: 450px; height: 450px;
            background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 60%);
            pointer-events: none;
        }
        .security-section .section-header { position: relative; z-index: 1; }
        .security-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1140px;
            margin: 0 auto;
            position: relative; z-index: 1;
        }
        .security-card {
            background: rgba(255, 255, 255, 0.036);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 22px;
            padding: 32px;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
            position: relative;
            overflow: hidden;
        }
        .security-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.025) 0%, transparent 60%);
            border-radius: inherit;
        }
        .security-card:hover {
            background: rgba(255, 255, 255, 0.065);
            border-color: rgba(96,165,250,0.35);
            transform: translateY(-6px);
            box-shadow: 0 24px 48px rgba(0,0,0,0.35);
        }
        .security-icon {
            width: 54px; height: 54px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(37,99,235,0.18);
            border: 1px solid rgba(96,165,250,0.22);
            margin-bottom: 18px;
            position: relative; z-index: 1;
        }
        .security-title {
            font-size: 16.5px;
            font-weight: 700;
            color: #F1F5F9;
            margin-bottom: 10px;
            position: relative; z-index: 1;
            letter-spacing: -0.01em;
        }
        .security-desc {
            font-size: 13.5px;
            color: #64748B;
            line-height: 1.72;
            position: relative; z-index: 1;
        }

        /* ── Testimonials ────────────────────────────── */
        .testimonials-section {
            padding: 96px 24px;
            background: #F8FAFC;
        }
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 22px;
            max-width: 1140px;
            margin: 0 auto;
        }
        .testimonial-card {
            background: #FFFFFF;
            border: 1.5px solid #E8EDF5;
            border-radius: 22px;
            padding: 32px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        }
        /* large decorative quote mark */
        .testimonial-card::before {
            content: '\201C';
            position: absolute;
            top: 12px; right: 22px;
            font-size: 90px;
            font-family: Georgia, 'Times New Roman', serif;
            color: #EFF6FF;
            line-height: 1;
            pointer-events: none;
            font-weight: 900;
        }
        .testimonial-card:hover {
            border-color: #93C5FD;
            box-shadow: 0 18px 44px rgba(37,99,235,0.09);
            transform: translateY(-6px);
        }
        .testimonial-rating {
            display: flex;
            gap: 3px;
            margin-bottom: 18px;
        }
        .testimonial-rating iconify-icon {
            color: #F59E0B;
            font-size: 17px;
        }
        .testimonial-quote {
            font-size: 14px;
            color: #334155;
            line-height: 1.78;
            font-style: italic;
            margin-bottom: 24px;
            position: relative; z-index: 1;
            flex-grow: 1;
        }
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
            border-top: 1px solid #F1F5F9;
            padding-top: 20px;
        }
        .author-avatar {
            width: 46px; height: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563EB, #60A5FA);
            color: #fff;
            font-weight: 800;
            font-size: 13px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        }
        .author-name {
            font-size: 13.5px;
            font-weight: 700;
            color: #0F172A;
            letter-spacing: -0.01em;
        }
        .author-role {
            font-size: 11.5px;
            color: #64748B;
            margin-top: 3px;
        }

        /* ── FAQ Section ────────────────────────────── */
        .faq-section {
            padding: 96px 24px;
            background: #FFFFFF;
            border-top: 1px solid #E8EDF5;
        }
        .faq-accordion {
            max-width: 800px;
            margin: 0 auto;
        }
        .faq-item {
            background: #FAFBFF;
            border: 1.5px solid #E8EDF5;
            border-radius: 16px;
            margin-bottom: 10px;
            overflow: hidden;
            transition: border-color 0.25s, box-shadow 0.25s;
        }
        .faq-item.active {
            border-color: #93C5FD;
            box-shadow: 0 6px 24px rgba(37,99,235,0.08);
            background: #FFFFFF;
        }
        .faq-item.active .faq-question { color: #1D4ED8; }
        .faq-question {
            padding: 20px 24px;
            font-size: 15px;
            font-weight: 700;
            color: #1E293B;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            user-select: none;
            transition: color 0.2s;
            letter-spacing: -0.01em;
        }
        .faq-question iconify-icon {
            font-size: 20px;
            color: #94A3B8;
            transition: transform 0.35s cubic-bezier(0.16,1,0.3,1), color 0.2s;
            flex-shrink: 0;
        }
        .faq-item.active .faq-question iconify-icon {
            transform: rotate(180deg);
            color: #2563EB;
        }
        /* smooth max-height accordion */
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.16,1,0.3,1);
            padding: 0 24px;
            font-size: 14.5px;
            color: #475569;
            line-height: 1.78;
        }
        .faq-item.active .faq-answer {
            max-height: 500px;
            padding-bottom: 22px;
        }

        /* ── Bottom CTA Banner ──────────────────────── */
        .cta-banner-section {
            padding: 88px 24px;
            background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 55%, #1D4ED8 100%);
            color: #fff;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        /* animated scrolling dot pattern */
        .cta-banner-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 22px 22px;
            animation: dotsDrift 22s linear infinite;
        }
        @keyframes dotsDrift {
            from { background-position: 0 0; }
            to   { background-position: 44px 44px; }
        }
        .cta-banner-section .glow-top-right {
            position: absolute;
            top: -100px; right: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 60%);
            pointer-events: none;
        }
        .cta-banner-section .glow-bottom-left {
            position: absolute;
            bottom: -80px; left: -80px;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 60%);
            pointer-events: none;
        }
        .cta-banner-container {
            max-width: 760px;
            margin: 0 auto;
            position: relative; z-index: 1;
        }
        .cta-banner-title {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 16px;
            letter-spacing: -0.03em;
            line-height: 1.18;
        }
        .cta-banner-desc {
            font-size: 16.5px;
            color: rgba(255,255,255,0.75);
            margin-bottom: 38px;
            line-height: 1.78;
        }
        .btn-cta-large {
            background: #FFFFFF;
            color: #1E3A8A !important;
            font-weight: 800;
            font-size: 16px;
            padding: 18px 48px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 12px 36px rgba(0,0,0,0.28);
            transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
            border: none;
        }
        .btn-cta-large:hover {
            transform: translateY(-4px);
            box-shadow: 0 22px 48px rgba(0,0,0,0.38);
            background: #F0F7FF;
        }

        /* ── Site Footer ────────────────────────────── */
        .site-footer {
            background: #060D1F;
            color: #64748B;
            padding: 64px 24px 28px;
            border-top: 1px solid rgba(255,255,255,0.05);
            font-size: 13.5px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 44px;
            max-width: 1140px;
            margin: 0 auto 48px;
        }
        .footer-brand h4 {
            color: #F1F5F9;
            font-weight: 800;
            font-size: 17px;
            margin-bottom: 10px;
            letter-spacing: -0.02em;
        }
        .footer-brand p {
            line-height: 1.78;
            font-size: 13px;
            color: #475569;
            max-width: 290px;
        }
        .footer-social {
            display: flex;
            gap: 8px;
            margin-top: 20px;
        }
        .social-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            display: flex; align-items: center; justify-content: center;
            color: #475569;
            text-decoration: none;
            font-size: 17px;
            transition: all 0.22s;
        }
        .social-btn:hover {
            background: rgba(37,99,235,0.18);
            border-color: rgba(96,165,250,0.28);
            color: #60A5FA;
            transform: translateY(-2px);
        }
        .footer-col h5 {
            color: #CBD5E1;
            font-size: 11px;
            font-weight: 800;
            margin-bottom: 18px;
            text-transform: uppercase;
            letter-spacing: 0.10em;
        }
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .footer-links a {
            color: #475569;
            text-decoration: none;
            transition: color 0.2s;
            font-size: 13px;
        }
        .footer-links a:hover {
            color: #60A5FA;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.05);
            padding-top: 28px;
            text-align: center;
            font-size: 12px;
            color: #334155;
            max-width: 1140px;
            margin: 0 auto;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .top-nav { padding: 14px 20px; }
            .nav-links { display: none; }
            .hero-stats-grid { grid-template-columns: repeat(2, 1fr); gap: 0; }
            .hero-stat-card { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.07); padding: 18px 0; }
            .hero-stat-card:nth-child(odd) { border-right: 1px solid rgba(255,255,255,0.07); }
            .hero-stat-card:nth-last-child(-n+2) { border-bottom: none; }
            .steps-grid { grid-template-columns: repeat(2, 1fr); }
            .steps-grid::before { display: none; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            .hero-title { font-size: 30px; }
            .hero-stats-grid { grid-template-columns: 1fr 1fr; }
            .steps-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
            .cta-banner-title { font-size: 26px; }
            .section-heading { font-size: 28px; }
        }
    </style>
</head>
<body>

<!-- Top Navigation -->
<nav class="top-nav">
    <a href="/" class="brand-logo">
        @if(!empty($platformLogo))
            <img src="{{ $platformLogo }}" alt="Logo" class="rounded object-fit-contain" style="width:38px;height:38px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="brand-icon" style="display:none;">
                <iconify-icon icon="solar:buildings-2-bold-duotone" style="font-size:22px;"></iconify-icon>
            </div>
        @else
            <div class="brand-icon">
                <iconify-icon icon="solar:buildings-2-bold-duotone" style="font-size:22px;"></iconify-icon>
            </div>
        @endif
        <div>
            {{ $platformName }}
            <div style="font-size:10px; font-weight:600; color:#2563EB; letter-spacing:0.04em;">{{ $platformTagline }}</div>
        </div>
    </a>

    <ul class="nav-links">
        <li><a href="{{ route('saas.fitur') }}">Fitur Lengkap</a></li>
        <li><a href="{{ route('saas.cara_kerja') }}">Cara Kerja</a></li>
        <li><a href="{{ route('saas.keamanan') }}">Keamanan Data</a></li>
        <li><a href="{{ route('saas.testimoni') }}">Testimoni</a></li>
        <li><a href="{{ route('saas.faq') }}">FAQ</a></li>
    </ul>

    <!-- Header Actions -> Login Owner/Platform & Daftar Yayasan Baru -->
    <div class="d-flex align-items-center gap-2">
        @auth
            <a href="/dashboard" class="btn-header-login">
                <iconify-icon icon="solar:widget-6-bold-duotone" style="font-size:18px;"></iconify-icon>
                Kembali ke Dashboard
            </a>
        @else
            <a href="/login" class="btn-header-login">
                <iconify-icon icon="solar:login-2-bold-duotone" style="font-size:18px;"></iconify-icon>
                Login Platform
            </a>
        @endauth
        <a href="{{ route('yayasan.daftar') }}" class="btn-header-cta">
            <iconify-icon icon="solar:rocket-bold-duotone" style="font-size:18px;"></iconify-icon>
            {{ $headerCta }}
        </a>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <!-- floating decorative shapes -->
    <div class="hero-glow"></div>
    <div class="hero-shape hero-shape-1"></div>
    <div class="hero-shape hero-shape-2"></div>
    <div class="hero-shape hero-shape-3"></div>

    <div class="hero-badge">
        <iconify-icon icon="solar:shield-check-bold" style="font-size:16px; color:#60A5FA;"></iconify-icon>
        {{ $heroBadge }}
    </div>
    <h1 class="hero-title">
        {{ $heroTitle1 }}<br><span>{{ $heroTitleHighlight }}</span>
    </h1>
    <p class="hero-sub">
        {{ $heroDesc }}
    </p>

    <div class="hero-actions">
        <a href="{{ route('yayasan.daftar') }}" class="btn-hero-main">
            <iconify-icon icon="solar:pen-new-square-bold-duotone" style="font-size:19px;"></iconify-icon>
            {{ $heroBtnPrimary }}
        </a>
        <a href="#fitur" class="btn-hero-outline">
            {{ $heroBtnSecondary }}
        </a>
    </div>

    <div class="hero-stats-grid">
        <div class="hero-stat-card">
            <div class="num">{{ $stat1Num }}</div>
            <div class="lbl">{{ $stat1Lbl }}</div>
        </div>
        <div class="hero-stat-card">
            <div class="num">{{ $stat2Num }}</div>
            <div class="lbl">{{ $stat2Lbl }}</div>
        </div>
        <div class="hero-stat-card">
            <div class="num">{{ $stat3Num }}</div>
            <div class="lbl">{{ $stat3Lbl }}</div>
        </div>
        <div class="hero-stat-card">
            <div class="num">{{ $stat4Num }}</div>
            <div class="lbl">{{ $stat4Lbl }}</div>
        </div>
    </div>
</section>

<!-- Client / Partner Pilot Logos (Yayasan & Cabang TK Dinamis) -->
<section class="clients-section">
    <div class="clients-title">Telah Dipercaya oleh Yayasan Pendidikan & TK di Seluruh Indonesia</div>
    <p class="clients-subtitle">Arahkan kursor atau klik yayasan di bawah untuk melihat daftar cabang TK/PAUD yang terdaftar</p>
    <div class="clients-logos">
        @forelse($yayasans as $y)
            <div class="yayasan-wrapper">
                <button type="button" class="yayasan-badge" aria-expanded="false" title="Klik atau hover untuk melihat cabang TK">
                    <iconify-icon icon="solar:buildings-bold-duotone" class="yayasan-icon"></iconify-icon>
                    <span>{{ $y->nama_yayasan }}</span>
                    <span class="yayasan-school-count">
                        <iconify-icon icon="solar:diploma-bold" style="font-size: 12px;"></iconify-icon>
                        {{ $y->schools->count() }} Cabang
                    </span>
                    <iconify-icon icon="solar:alt-arrow-down-linear" class="yayasan-chevron"></iconify-icon>
                </button>

                <!-- Popover Daftar TK -->
                <div class="yayasan-schools-popover" role="tooltip">
                    <div class="popover-header">
                        <div class="popover-tag">
                            <iconify-icon icon="solar:verified-check-bold"></iconify-icon>
                            Mitra Resmi Yayasan
                        </div>
                        <div class="popover-title">{{ $y->nama_yayasan }}</div>
                        <div class="popover-subtitle">
                            {{ $y->schools->count() }} Cabang TK/PAUD Terdaftar di Database
                        </div>
                    </div>

                    <div class="popover-body">
                        @forelse($y->schools as $school)
                            <div class="school-item">
                                <div class="school-icon-wrap">
                                    <iconify-icon icon="solar:diploma-bold-duotone"></iconify-icon>
                                </div>
                                <div class="school-info">
                                    <div class="school-name">{{ $school->nama_sekolah }}</div>
                                    <div class="school-meta">
                                        {{ $school->alamat ? \Illuminate\Support\Str::limit($school->alamat, 38) : 'Unit TK Resmi Aktif' }}
                                    </div>
                                </div>
                                <span class="school-tag">Aktif</span>
                            </div>
                        @empty
                            <div class="school-empty">
                                <iconify-icon icon="solar:info-circle-linear" style="font-size: 16px;"></iconify-icon>
                                <span>Belum ada cabang sekolah yang aktif</span>
                            </div>
                        @endforelse
                    </div>

                    @if(!empty($y->kode_yayasan))
                    <div class="popover-footer">
                        <a href="{{ url('/y/' . $y->kode_yayasan) }}" class="popover-cta-btn">
                            <span>Kunjungi Landing Page Yayasan</span>
                            <iconify-icon icon="solar:arrow-right-linear" style="font-size: 15px;"></iconify-icon>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-muted" style="font-size: 13px;">Belum ada yayasan terdaftar di sistem.</div>
        @endforelse
    </div>
</section>

<!-- How It Works (Alur Cara Kerja) -->
<section class="workflow-section" id="cara-kerja">
    <div class="section-header">
        <span class="section-tag">Alur Cepat & Transparan</span>
        <h2 class="section-heading">Bagaimana Cara Kerjanya?</h2>
        <p class="section-desc">Hanya butuh 4 langkah mudah untuk mendigitalkan seluruh operasional yayasan dan cabang sekolah Anda.</p>
    </div>

    <div class="steps-grid">
        <div class="step-card">
            <div class="step-number">1</div>
            <h3 class="step-title">{{ $step1Title }}</h3>
            <p class="step-desc">{{ $step1Desc }}</p>
        </div>

        <div class="step-card">
            <div class="step-number">2</div>
            <h3 class="step-title">{{ $step2Title }}</h3>
            <p class="step-desc">{{ $step2Desc }}</p>
        </div>

        <div class="step-card">
            <div class="step-number">3</div>
            <h3 class="step-title">{{ $step3Title }}</h3>
            <p class="step-desc">{{ $step3Desc }}</p>
        </div>

        <div class="step-card">
            <div class="step-number">4</div>
            <h3 class="step-title">{{ $step4Title }}</h3>
            <p class="step-desc">{{ $step4Desc }}</p>
        </div>
    </div>
</section>

<!-- Complete Modules Breakdown -->
<section class="features-section" id="fitur">
    <div class="section-header">
        <span class="section-tag">Modul Lengkap & Terintegrasi</span>
        <h2 class="section-heading">Fitur Unggulan Siap Pakai</h2>
        <p class="section-desc">Dirancang spesifik untuk kebutuhan administrasi anak usia dini (TK & PAUD) sesuai regulasi Kurikulum Merdeka.</p>
    </div>

    <div class="features-grid">
        <!-- Feature 1 -->
        <div class="feature-card">
            <div class="feature-icon-wrapper" style="background:#EFF6FF; color:#2563EB;">
                <iconify-icon icon="solar:chart-bold-duotone" style="font-size:28px;"></iconify-icon>
            </div>
            <h3 class="feature-title">E-Rapor Kurikulum Merdeka</h3>
            <p class="feature-desc">Template rapor dinamis, narasi capaian pembelajaran, penilaian perkembangan, dan cetak PDF satu klik.</p>
            <ul class="feature-points">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Narasi Capaian Nilai Agama & Budi Pekerti</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Sisipkan Dokumentasi Foto Karya Anak</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Format Resmi Siap Cetak A4 / F4</li>
            </ul>
        </div>

        <!-- Feature 2 -->
        <div class="feature-card">
            <div class="feature-icon-wrapper" style="background:#ECFDF5; color:#10B981;">
                <iconify-icon icon="solar:calendar-check-bold-duotone" style="font-size:28px;"></iconify-icon>
            </div>
            <h3 class="feature-title">Presensi & Absensi Harian</h3>
            <p class="feature-desc">Pencatatan absensi realtime dengan status hadir, sakit, izin, atau tanpa keterangan beserta rekapitulasi bulanan.</p>
            <ul class="feature-points">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Rekap 5 Hari Sekolah Otomatis</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Filter Per Rombel Kelas & Semester</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Export Laporan Kehadiran Bulanan</li>
            </ul>
        </div>

        <!-- Feature 3 -->
        <div class="feature-card">
            <div class="feature-icon-wrapper" style="background:#FEF3C7; color:#D97706;">
                <iconify-icon icon="solar:notebook-bold-duotone" style="font-size:28px;"></iconify-icon>
            </div>
            <h3 class="feature-title">Jurnal Kegiatan & LMS Guru</h3>
            <p class="feature-desc">Guru mendokumentasikan kegiatan harian, tema mingguan, dan foto aktivitas belajar yang dapat dilihat langsung oleh orang tua.</p>
            <ul class="feature-points">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Upload Foto Kegiatan Resolusi Tinggi</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Kolom Komentar & Interaksi Orang Tua</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Download Bundel Foto ZIP per Semester</li>
            </ul>
        </div>

        <!-- Feature 4 -->
        <div class="feature-card">
            <div class="feature-icon-wrapper" style="background:#FEE2E2; color:#EF4444;">
                <iconify-icon icon="solar:health-bold-duotone" style="font-size:28px;"></iconify-icon>
            </div>
            <h3 class="feature-title">Perkembangan Fisik & KMS</h3>
            <p class="feature-desc">Pemantauan berat badan, tinggi badan, lingkar kepala, dan kesehatan fisik anak secara berkala standar Dapodik.</p>
            <ul class="feature-points">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Riwayat Pemeriksaan Medis Berkala</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Terintegrasi Otomatis ke Lembar Rapor</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Pantau Pertumbuhan Anak Sehat</li>
            </ul>
        </div>

        <!-- Feature 5 -->
        <div class="feature-card">
            <div class="feature-icon-wrapper" style="background:#F3E8FF; color:#9333EA;">
                <iconify-icon icon="solar:users-group-rounded-bold-duotone" style="font-size:28px;"></iconify-icon>
            </div>
            <h3 class="feature-title">Manajemen Guru & Multi-Cabang</h3>
            <p class="feature-desc">Kelola penugasan wali kelas, struktur cabang sekolah di bawah yayasan, kenaikan kelas, dan pembagian hak akses modular.</p>
            <ul class="feature-points">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Multi-Tenant Satu Dasbor Yayasan</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Hak Akses: Super Admin, Admin, Guru, Kepsek</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Proses Kenaikan & Kelulusan Kelas Mudah</li>
            </ul>
        </div>

        <!-- Feature 6 -->
        <div class="feature-card">
            <div class="feature-icon-wrapper" style="background:#E0F2FE; color:#0284C7;">
                <iconify-icon icon="solar:global-bold-duotone" style="font-size:28px;"></iconify-icon>
            </div>
            <h3 class="feature-title">Landing Page Mandiri & PPDB</h3>
            <p class="feature-desc">Setiap yayasan otomatis memiliki halaman website publik mandiri <code>/y/{kode_yayasan}</code> dengan formulir PPDB online.</p>
            <ul class="feature-points">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> URL Khusus Yayasan Anda Sendiri</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Kuota Inden Siswa Realtime per Tahun</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Upload Berkas KK, Akte, & Bukti Bayar</li>
            </ul>
        </div>
    </div>
</section>

<!-- Data Isolation & Security Section -->
<section class="security-section" id="keamanan">
    <div class="section-header">
        <span class="section-tag" style="background:rgba(255,255,255,0.1); color:#93C5FD; border-color:rgba(255,255,255,0.2);">Keamanan Tingkat Tinggi</span>
        <h2 class="section-heading" style="color:#FFFFFF;">Jaminan Isolasi Data Multi-Tenant</h2>
        <p class="section-desc" style="color:#94A3B8;">Setiap yayasan adalah "rumah mandiri". Data sensitif siswa, guru, dan rekap nilai 100% terisolasi dan aman.</p>
    </div>

    <div class="security-grid">
        <div class="security-card">
            <div class="security-icon">
                <iconify-icon icon="solar:shield-check-bold-duotone" style="font-size:26px; color:#60A5FA;"></iconify-icon>
            </div>
            <h3 class="security-title">Isolasi Tenant Ketat</h3>
            <p class="security-desc">Setiap kueri database difilter secara ketat berdasarkan <code>yayasan_id</code>. Yayasan lain tidak dapat mengintip atau mengakses data Anda.</p>
        </div>

        <div class="security-card">
            <div class="security-icon">
                <iconify-icon icon="solar:lock-bold-duotone" style="font-size:26px; color:#60A5FA;"></iconify-icon>
            </div>
            <h3 class="security-title">Enkripsi & Hashing</h3>
            <p class="security-desc">Password disimpan dengan algoritma enkripsi Bcrypt. Jalur komunikasi data terlindungi sertifikat SSL/TLS enkripsi end-to-end.</p>
        </div>

        <div class="security-card">
            <div class="security-icon">
                <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" style="font-size:26px; color:#60A5FA;"></iconify-icon>
            </div>
            <h3 class="security-title">Role-Based Access (RBAC)</h3>
            <p class="security-desc">Hak akses ketat berbasis peran: Owner, Super Admin Yayasan, Admin Sekolah, Guru Kelas, Kepala Sekolah, dan Wali Murid.</p>
        </div>

        <div class="security-card">
            <div class="security-icon">
                <iconify-icon icon="solar:server-bold-duotone" style="font-size:26px; color:#60A5FA;"></iconify-icon>
            </div>
            <h3 class="security-title">Auto Backup & Uptime 99.9%</h3>
            <p class="security-desc">Pencadangan data berkala di cloud server terpercaya memastikan data Anda aman dari risiko kerusakan perangkat lokal.</p>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section" id="testimoni">
    <div class="section-header">
        <span class="section-tag">Testimoni Klien</span>
        <h2 class="section-heading">Apa Kata Pimpinan Yayasan?</h2>
        <p class="section-desc">Pengalaman nyata dari ketua yayasan dan kepala sekolah yang telah bertransformasi ke platform kami.</p>
    </div>

    <div class="testimonials-grid">
        <div class="testimonial-card">
            <div class="testimonial-rating">
                <iconify-icon icon="solar:star-bold"></iconify-icon>
                <iconify-icon icon="solar:star-bold"></iconify-icon>
                <iconify-icon icon="solar:star-bold"></iconify-icon>
                <iconify-icon icon="solar:star-bold"></iconify-icon>
                <iconify-icon icon="solar:star-bold"></iconify-icon>
            </div>
            <p class="testimonial-quote">
                "Sistem ini sangat mempermudah koordinasi antar 3 cabang TK kami. Rekap nilai dan pencetakan rapor yang biasanya memakan waktu 2 minggu, kini beres dalam hitungan jam!"
            </p>
            <div class="testimonial-author">
                <div class="author-avatar">NH</div>
                <div>
                    <div class="author-name">Hj. Nurul Hidayati</div>
                    <div class="author-role">Ketua Yayasan Al-Hikmah 2</div>
                </div>
            </div>
        </div>

        <div class="testimonial-card">
            <div class="testimonial-rating">
                <iconify-icon icon="solar:star-bold"></iconify-icon>
                <iconify-icon icon="solar:star-bold"></iconify-icon>
                <iconify-icon icon="solar:star-bold"></iconify-icon>
                <iconify-icon icon="solar:star-bold"></iconify-icon>
                <iconify-icon icon="solar:star-bold"></iconify-icon>
            </div>
            <p class="testimonial-quote">
                "Orang tua murid sangat senang bisa melihat jurnal foto kegiatan anak tiap sore langsung lewat smartphone tanpa ribet install aplikasi. Sekolah kami terlihat jauh lebih profesional."
            </p>
            <div class="testimonial-author">
                <div class="author-avatar" style="background:linear-gradient(135deg,#10B981,#34D399);">RD</div>
                <div>
                    <div class="author-name">Bunda Ratna Dewi, S.Pd</div>
                    <div class="author-role">Kepala Sekolah TK Permata</div>
                </div>
            </div>
        </div>

        <div class="testimonial-card">
            <div class="testimonial-rating">
                <iconify-icon icon="solar:star-bold"></iconify-icon>
                <iconify-icon icon="solar:star-bold"></iconify-icon>
                <iconify-icon icon="solar:star-bold"></iconify-icon>
                <iconify-icon icon="solar:star-bold"></iconify-icon>
                <iconify-icon icon="solar:star-bold"></iconify-icon>
            </div>
            <p class="testimonial-quote">
                "Setup awalnya cepat sekali, hanya 1 hari akun Super Admin sudah aktif. Guru-guru yang awalnya gaptek pun langsung paham karena tampilannya sangat rapi dan mudah dipakai."
            </p>
            <div class="testimonial-author">
                <div class="author-avatar" style="background:linear-gradient(135deg,#8B5CF6,#A78BFA);">AF</div>
                <div>
                    <div class="author-name">Ahmad Fauzi, M.Pd</div>
                    <div class="author-role">Direktur Yayasan Bina Insani Ceria</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section" id="faq">
    <div class="section-header">
        <span class="section-tag">Pertanyaan Umum</span>
        <h2 class="section-heading">Frequently Asked Questions</h2>
        <p class="section-desc">Jawaban atas pertanyaan yang paling sering diajukan oleh calon yayasan mitra.</p>
    </div>

    <div class="faq-accordion">
        <div class="faq-item active">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>Apakah yayasan kami membutuhkan server atau instalasi khusus?</span>
                <iconify-icon icon="solar:alt-arrow-down-bold"></iconify-icon>
            </div>
            <div class="faq-answer">
                Tidak sama sekali. Kiddy School ERP adalah platform berbasis Cloud SaaS (Software as a Service). Anda dan guru-guru cukup membuka link melalui browser di komputer, tablet, maupun smartphone tanpa perlu membeli server atau menginstal software apapun.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>Berapa lama proses verifikasi pendaftaran yayasan baru?</span>
                <iconify-icon icon="solar:alt-arrow-down-bold"></iconify-icon>
            </div>
            <div class="faq-answer">
                Proses verifikasi oleh tim owner biasanya memakan waktu <strong>1–24 jam kerja</strong>. Begitu pendaftaran di-ACC, akun Super Admin yayasan Anda akan langsung dibuat dan data akses dikirimkan ke email kontak penanggung jawab yang didaftarkan.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>Apakah data yayasan kami terisolasi dari yayasan lain?</span>
                <iconify-icon icon="solar:alt-arrow-down-bold"></iconify-icon>
            </div>
            <div class="faq-answer">
                Ya, 100% terisolasi. Sistem kami menerapkan arsitektur Multi-Tenant Isolation ketat. Setiap yayasan ibarat memiliki gedung kantor sendiri yang terkunci rapat; data siswa, rapor, keuangan, dan guru dari yayasan Anda tidak akan pernah bisa dilihat oleh yayasan lain.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>Apakah bisa mengelola lebih dari 1 cabang sekolah TK / PAUD?</span>
                <iconify-icon icon="solar:alt-arrow-down-bold"></iconify-icon>
            </div>
            <div class="faq-answer">
                Tentu bisa. Yayasan dapat menambahkan data unit cabang TK, PAUD, atau Kelompok Bermain di bawah naungan yayasan Anda. Masing-masing cabang memiliki admin, guru, rombel kelas, dan daftar siswanya sendiri.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>Bagaimana cara orang tua / wali murid mengakses sistem?</span>
                <iconify-icon icon="solar:alt-arrow-down-bold"></iconify-icon>
            </div>
            <div class="faq-answer">
                Orang tua cukup membuka link website publik yayasan Anda (<code>/y/{kode_yayasan}</code>), lalu mendaftar akun wali murid. Akun orang tua otomatis terhubung ke yayasan Anda dan dapat langsung melihat pengumuman, absensi, jurnal foto, dan e-rapor anak.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>Apakah ada panduan penggunaan atau pelatihan untuk guru?</span>
                <iconify-icon icon="solar:alt-arrow-down-bold"></iconify-icon>
            </div>
            <div class="faq-answer">
                Ya, kami menyediakan video panduan lengkap, buku petunjuk praktis PDF, serta sesi onboarding via Zoom untuk membantu tim admin dan guru yayasan Anda mulai menggunakan sistem dengan lancar.
            </div>
        </div>
    </div>
</section>

<!-- Bottom CTA Banner Section -->
<section class="cta-banner-section">
    <div class="glow-top-right"></div>
    <div class="glow-bottom-left"></div>
    <div class="cta-banner-container">
        <h2 class="cta-banner-title">{{ $ctaTitle }}</h2>
        <p class="cta-banner-desc">{{ $ctaDesc }}</p>
        <a href="{{ route('yayasan.daftar') }}" class="btn-cta-large">
            <iconify-icon icon="solar:pen-new-square-bold-duotone" style="font-size:22px; color:#2563EB;"></iconify-icon>
            {{ $ctaBtn }}
        </a>
    </div>
</section>

<!-- Site Footer -->
<footer class="site-footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <div class="d-flex align-items-center gap-2 mb-3">
                @if(!empty($platformLogo))
                    <img src="{{ $platformLogo }}" alt="Logo" class="rounded object-fit-contain" style="width:32px;height:32px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="brand-icon" style="width:32px;height:32px;font-size:16px;display:none;">
                        <iconify-icon icon="solar:buildings-2-bold-duotone"></iconify-icon>
                    </div>
                @else
                    <div class="brand-icon" style="width:32px;height:32px;font-size:16px;">
                        <iconify-icon icon="solar:buildings-2-bold-duotone"></iconify-icon>
                    </div>
                @endif
                <h4 style="margin:0;">{{ $platformName }}</h4>
            </div>
            <p>{{ $footerAbout }}</p>
            <div class="footer-social">
                <a href="#" class="social-btn" title="Instagram">
                    <iconify-icon icon="mdi:instagram"></iconify-icon>
                </a>
                <a href="#" class="social-btn" title="WhatsApp">
                    <iconify-icon icon="mdi:whatsapp"></iconify-icon>
                </a>
                <a href="#" class="social-btn" title="YouTube">
                    <iconify-icon icon="mdi:youtube"></iconify-icon>
                </a>
                <a href="#" class="social-btn" title="Email">
                    <iconify-icon icon="mdi:email-outline"></iconify-icon>
                </a>
            </div>
        </div>

        <div class="footer-col">
            <h5>Navigasi</h5>
            <ul class="footer-links">
                <li><a href="{{ route('saas.fitur') }}">Fitur & Modul</a></li>
                <li><a href="{{ route('saas.cara_kerja') }}">Alur Cara Kerja</a></li>
                <li><a href="{{ route('saas.keamanan') }}">Keamanan Data</a></li>
                <li><a href="{{ route('saas.testimoni') }}">Testimoni Klien</a></li>
                <li><a href="{{ route('yayasan.daftar') }}">Daftar Yayasan</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h5>Bantuan & Legal</h5>
            <ul class="footer-links">
                <li><a href="{{ route('saas.faq') }}">FAQ Pertanyaan</a></li>
                <li><a href="{{ route('legal.privacy') }}">Kebijakan Privasi</a></li>
                <li><a href="{{ route('legal.terms') }}">Syarat & Ketentuan</a></li>
                <li><a href="/login">Portal Login Staf</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h5>Kontak & Layanan</h5>
            <p style="font-size:13px; line-height:1.7; color:#94A3B8;">
                Email Dukungan:<br>
                <strong class="text-white">{{ $contactEmail }}</strong><br>
                WhatsApp: <strong class="text-white">{{ $contactPhone }}</strong><br>
                Alamat: <span class="text-white text-opacity-75">{{ $contactAddress }}</span>
            </p>
        </div>
    </div>

    <div class="footer-bottom">
        © {{ date('Y') }} {{ $platformName }}. Seluruh Hak Cipta Dilindungi Undang-Undang.
    </div>
</footer>

<script>
// FAQ Accordion — smooth max-height toggle
function toggleFaq(el) {
    const item = el.parentElement;
    const wasActive = item.classList.contains('active');

    // close all
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));

    // open clicked if it was closed
    if (!wasActive) {
        item.classList.add('active');
    }
}

// Subtle scroll-reveal for cards
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.style.opacity = '1';
            e.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.08 });

document.querySelectorAll('.feature-card, .step-card, .security-card, .testimonial-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(22px)';
    el.style.transition = (el.style.transition || '') + ', opacity 0.55s cubic-bezier(0.16,1,0.3,1), transform 0.55s cubic-bezier(0.16,1,0.3,1)';
    observer.observe(el);
});

// Yayasan Badge Dropdown Toggle for Click & Touch
document.addEventListener('click', function(e) {
    const clickedWrapper = e.target.closest('.yayasan-wrapper');
    const allWrappers = document.querySelectorAll('.yayasan-wrapper');

    if (clickedWrapper && e.target.closest('.yayasan-badge')) {
        const isOpen = clickedWrapper.classList.contains('is-open');
        allWrappers.forEach(w => {
            w.classList.remove('is-open');
            const btn = w.querySelector('.yayasan-badge');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        });
        if (!isOpen) {
            clickedWrapper.classList.add('is-open');
            const btn = clickedWrapper.querySelector('.yayasan-badge');
            if (btn) btn.setAttribute('aria-expanded', 'true');
        }
    } else if (!clickedWrapper || !clickedWrapper.contains(e.target)) {
        allWrappers.forEach(w => {
            w.classList.remove('is-open');
            const btn = w.querySelector('.yayasan-badge');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        });
    }
});

// Close popovers on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.yayasan-wrapper.is-open').forEach(w => {
            w.classList.remove('is-open');
            const btn = w.querySelector('.yayasan-badge');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        });
    }
});
</script>

</body>
</html>
