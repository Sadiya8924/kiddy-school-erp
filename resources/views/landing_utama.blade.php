<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kiddy School ERP — Platform Manajemen Yayasan TK & PAUD Terpadu</title>
    <meta name="description" content="Platform Cloud ERP Manajemen Yayasan TK & PAUD terlengkap di Indonesia. Kelola seluruh cabang sekolah, e-rapor, presensi, jurnal, dan portal orang tua dengan jaminan isolasi data penuh.">

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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #E2E8F0;
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
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
            background: linear-gradient(135deg, #2563EB, #3B82F6);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 26px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-links a {
            text-decoration: none;
            color: #475569;
            font-size: 13.5px;
            font-weight: 600;
            transition: color 0.2s;
        }
        .nav-links a:hover {
            color: #2563EB;
        }
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
            box-shadow: 0 4px 14px rgba(37,99,235,0.35);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            border: none;
        }
        .btn-header-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(37,99,235,0.45);
            color: #fff;
        }

        /* ── Hero Section ───────────────────────────── */
        .hero-section {
            background: radial-gradient(circle at 80% 20%, #1E3A8A 0%, #0F172A 70%);
            padding: 95px 24px 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
            color: #fff;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.22);
            color: #93C5FD;
            border-radius: 100px;
            padding: 7px 20px;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 24px;
            backdrop-filter: blur(8px);
        }
        .hero-title {
            font-size: clamp(32px, 5vw, 56px);
            font-weight: 800;
            color: #FFFFFF;
            line-height: 1.15;
            letter-spacing: -0.035em;
            max-width: 900px;
            margin: 0 auto 20px;
        }
        .hero-title span {
            background: linear-gradient(135deg, #60A5FA 0%, #93C5FD 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-sub {
            font-size: 16.5px;
            color: #CBD5E1;
            max-width: 700px;
            margin: 0 auto 38px;
            line-height: 1.75;
        }
        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 52px;
        }
        .btn-hero-main {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
            color: #fff;
            font-weight: 700;
            font-size: 15.5px;
            padding: 14px 36px;
            border-radius: 100px;
            text-decoration: none;
            box-shadow: 0 8px 24px rgba(37,99,235,0.4);
            transition: all 0.2s;
            border: 1px solid rgba(255,255,255,0.2);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-hero-main:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(37,99,235,0.5);
        }
        .btn-hero-outline {
            background: rgba(255,255,255,0.08);
            border: 1.5px solid rgba(255,255,255,0.25);
            color: #fff;
            font-weight: 600;
            font-size: 15.5px;
            padding: 13px 32px;
            border-radius: 100px;
            text-decoration: none;
            transition: all 0.2s;
            backdrop-filter: blur(6px);
        }
        .btn-hero-outline:hover {
            background: rgba(255,255,255,0.18);
            color: #fff;
        }

        .hero-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            max-width: 900px;
            margin: 0 auto;
            border-top: 1px solid rgba(255,255,255,0.12);
            padding-top: 36px;
        }
        .hero-stat-card {
            text-align: center;
        }
        .hero-stat-card .num {
            font-size: 28px;
            font-weight: 800;
            color: #60A5FA;
            line-height: 1;
        }
        .hero-stat-card .lbl {
            font-size: 12.5px;
            color: #94A3B8;
            margin-top: 6px;
            font-weight: 500;
        }

        /* ── Client / Partner Logos ─────────────────── */
        .clients-section {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 38px 24px;
            text-align: center;
        }
        .clients-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748B;
            margin-bottom: 22px;
        }
        .clients-logos {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 32px;
            flex-wrap: wrap;
        }
        .client-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 100px;
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            transition: all 0.2s;
        }
        .client-badge:hover {
            background: #EFF6FF;
            border-color: #BFDBFE;
            color: #1D4ED8;
            transform: translateY(-2px);
        }

        /* ── How It Works (Alur Cara Kerja) ──────────── */
        .workflow-section {
            padding: 85px 24px;
            background: #F8FAFC;
        }
        .section-header {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 52px;
        }
        .section-tag {
            display: inline-block;
            background: #EFF6FF;
            color: #2563EB;
            border: 1px solid #DBEAFE;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 5px 14px;
            border-radius: 100px;
            margin-bottom: 12px;
        }
        .section-heading {
            font-size: 32px;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.025em;
            margin-bottom: 12px;
        }
        .section-desc {
            font-size: 15px;
            color: #64748B;
            line-height: 1.65;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            max-width: 1140px;
            margin: 0 auto;
        }
        .step-card {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 32px 22px;
            position: relative;
            transition: all 0.25s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .step-card:hover {
            border-color: #2563EB;
            box-shadow: 0 12px 28px rgba(37,99,235,0.08);
            transform: translateY(-4px);
        }
        .step-number {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
            color: #2563EB;
            font-size: 18px;
            font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px;
            border: 1px solid #BFDBFE;
        }
        .step-title {
            font-size: 16px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 8px;
        }
        .step-desc {
            font-size: 13px;
            color: #64748B;
            line-height: 1.6;
        }

        /* ── Modul Lengkap (Feature Breakdown) ──────── */
        .features-section {
            padding: 85px 24px;
            background: #FFFFFF;
            border-top: 1px solid #E2E8F0;
            border-bottom: 1px solid #E2E8F0;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
            max-width: 1140px;
            margin: 0 auto;
        }
        .feature-card {
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 28px;
            transition: all 0.2s;
        }
        .feature-card:hover {
            background: #FFFFFF;
            border-color: #3B82F6;
            box-shadow: 0 10px 30px rgba(37,99,235,0.08);
            transform: translateY(-3px);
        }
        .feature-icon-wrapper {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
            margin-bottom: 18px;
        }
        .feature-title {
            font-size: 17px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 8px;
        }
        .feature-desc {
            font-size: 13.5px;
            color: #64748B;
            line-height: 1.65;
            margin-bottom: 14px;
        }
        .feature-points {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .feature-points li {
            font-size: 12.5px;
            color: #334155;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .feature-points li iconify-icon {
            color: #10B981;
            font-size: 15px;
            flex-shrink: 0;
        }

        /* ── Data Isolation & Security Section ──────── */
        .security-section {
            padding: 85px 24px;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .security-section::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(37,99,235,0.25) 0%, transparent 70%);
            pointer-events: none;
        }
        .security-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            max-width: 1140px;
            margin: 0 auto;
        }
        .security-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 28px;
            backdrop-filter: blur(10px);
            transition: all 0.2s;
        }
        .security-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: #60A5FA;
            transform: translateY(-3px);
        }
        .security-icon {
            font-size: 32px;
            margin-bottom: 14px;
        }
        .security-title {
            font-size: 16px;
            font-weight: 700;
            color: #F8FAFC;
            margin-bottom: 8px;
        }
        .security-desc {
            font-size: 13px;
            color: #94A3B8;
            line-height: 1.65;
        }

        /* ── Testimonials ────────────────────────────── */
        .testimonials-section {
            padding: 85px 24px;
            background: #FFFFFF;
        }
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            max-width: 1140px;
            margin: 0 auto;
        }
        .testimonial-card {
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .testimonial-rating {
            color: #F59E0B;
            font-size: 16px;
            margin-bottom: 14px;
        }
        .testimonial-quote {
            font-size: 14px;
            color: #334155;
            line-height: 1.7;
            font-style: italic;
            margin-bottom: 20px;
        }
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
            border-top: 1px solid #E2E8F0;
            padding-top: 16px;
        }
        .author-avatar {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563EB, #60A5FA);
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .author-name {
            font-size: 13.5px;
            font-weight: 700;
            color: #0F172A;
        }
        .author-role {
            font-size: 12px;
            color: #64748B;
        }

        /* ── FAQ Section ────────────────────────────── */
        .faq-section {
            padding: 85px 24px;
            background: #F8FAFC;
            border-top: 1px solid #E2E8F0;
        }
        .faq-accordion {
            max-width: 800px;
            margin: 0 auto;
        }
        .faq-item {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 14px;
            margin-bottom: 12px;
            overflow: hidden;
            transition: all 0.2s;
        }
        .faq-item.active {
            border-color: #93C5FD;
            box-shadow: 0 4px 16px rgba(37,99,235,0.06);
        }
        .faq-question {
            padding: 18px 24px;
            font-size: 15px;
            font-weight: 700;
            color: #0F172A;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            user-select: none;
        }
        .faq-question iconify-icon {
            font-size: 20px;
            color: #64748B;
            transition: transform 0.2s;
        }
        .faq-item.active .faq-question iconify-icon {
            transform: rotate(180deg);
            color: #2563EB;
        }
        .faq-answer {
            padding: 0 24px 20px;
            font-size: 14px;
            color: #475569;
            line-height: 1.7;
            display: none;
        }
        .faq-item.active .faq-answer {
            display: block;
        }

        /* ── Bottom CTA Banner ──────────────────────── */
        .cta-banner-section {
            padding: 70px 24px;
            background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%);
            color: #fff;
            text-align: center;
        }
        .cta-banner-container {
            max-width: 760px;
            margin: 0 auto;
        }
        .cta-banner-title {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 14px;
            letter-spacing: -0.02em;
        }
        .cta-banner-desc {
            font-size: 16px;
            color: #DBEAFE;
            margin-bottom: 32px;
            line-height: 1.7;
        }
        .btn-cta-large {
            background: #FFFFFF;
            color: #1E3A8A !important;
            font-weight: 800;
            font-size: 16px;
            padding: 16px 42px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            transition: all 0.25s;
            border: none;
        }
        .btn-cta-large:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 36px rgba(0,0,0,0.35);
            background: #F8FAFC;
        }

        /* ── Site Footer ────────────────────────────── */
        .site-footer {
            background: #0F172A;
            color: #94A3B8;
            padding: 56px 24px 28px;
            border-top: 1px solid #1E293B;
            font-size: 13.5px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 40px;
            max-width: 1140px;
            margin: 0 auto 40px;
        }
        .footer-brand h4 {
            color: #fff;
            font-weight: 800;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .footer-brand p {
            line-height: 1.7;
            font-size: 13px;
            color: #94A3B8;
            max-width: 320px;
        }
        .footer-col h5 {
            color: #F8FAFC;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .footer-links a {
            color: #94A3B8;
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer-links a:hover {
            color: #60A5FA;
        }
        .footer-bottom {
            border-top: 1px solid #1E293B;
            padding-top: 24px;
            text-align: center;
            font-size: 12.5px;
            color: #64748B;
            max-width: 1140px;
            margin: 0 auto;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .nav-links { display: none; }
            .hero-stats-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
            .steps-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 600px) {
            .hero-stats-grid { grid-template-columns: 1fr; }
            .steps-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- Top Navigation -->
<nav class="top-nav">
    <a href="/" class="brand-logo">
        <div class="brand-icon">🏫</div>
        <div>
            Kiddy School ERP
            <div style="font-size:10px; font-weight:600; color:#2563EB; letter-spacing:0.04em;">MULTI-TENANT PLATFORM</div>
        </div>
    </a>

    <ul class="nav-links">
        <li><a href="#fitur">Fitur Lengkap</a></li>
        <li><a href="#cara-kerja">Cara Kerja</a></li>
        <li><a href="#keamanan">Keamanan Data</a></li>
        <li><a href="#testimoni">Testimoni</a></li>
        <li><a href="#faq">FAQ</a></li>
    </ul>

    <!-- Header CTA Button -> Navigates to dedicated registration page -->
    <a href="{{ route('yayasan.daftar') }}" class="btn-header-cta">
        <iconify-icon icon="solar:rocket-bold-duotone" style="font-size:18px;"></iconify-icon>
        Daftar Sekarang
    </a>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-badge">
        <iconify-icon icon="solar:shield-check-bold" style="font-size:16px; color:#60A5FA;"></iconify-icon>
        SaaS Multi-Tenant Terpercaya Khusus Yayasan & Sekolah Anak Usia Dini
    </div>
    <h1 class="hero-title">
        Solusi ERP Modern & Terpadu<br>untuk <span>Manajemen Yayasan TK & PAUD</span>
    </h1>
    <p class="hero-sub">
        Kelola seluruh cabang sekolah, ribuan siswa, e-rapor Kurikulum Merdeka, presensi harian, hingga portal mandiri orang tua dalam satu sistem cloud terintegrasi dengan jaminan isolasi data penuh.
    </p>

    <div class="hero-actions">
        <a href="{{ route('yayasan.daftar') }}" class="btn-hero-main">
            <iconify-icon icon="solar:pen-new-square-bold-duotone" style="font-size:19px;"></iconify-icon>
            Daftarkan Yayasan Anda
        </a>
        <a href="#fitur" class="btn-hero-outline">
            Pelajari Fitur & Modul
        </a>
    </div>

    <div class="hero-stats-grid">
        <div class="hero-stat-card">
            <div class="num">500+</div>
            <div class="lbl">Cabang Sekolah Terdaftar</div>
        </div>
        <div class="hero-stat-card">
            <div class="num">15.000+</div>
            <div class="lbl">Siswa Aktif & Alumni</div>
        </div>
        <div class="hero-stat-card">
            <div class="num">100%</div>
            <div class="lbl">Isolasi Data Antar-Tenant</div>
        </div>
        <div class="hero-stat-card">
            <div class="num">99.9%</div>
            <div class="lbl">Cloud Server Uptime</div>
        </div>
    </div>
</section>

<!-- Client / Partner Pilot Logos -->
<section class="clients-section">
    <div class="clients-title">Telah Dipercaya oleh Puluhan Yayasan Pendidikan & TK di Seluruh Indonesia</div>
    <div class="clients-logos">
        <div class="client-badge">
            <span>🏛️</span> Yayasan Al-Hikmah 2
        </div>
        <div class="client-badge">
            <span>🌟</span> Yayasan Bina Insani Ceria
        </div>
        <div class="client-badge">
            <span>💎</span> TK Permata Bangsa
        </div>
        <div class="client-badge">
            <span>☀️</span> TK Matahari Ceria
        </div>
        <div class="client-badge">
            <span>⭐</span> TK Bintang Harapan
        </div>
        <div class="client-badge">
            <span>🎓</span> TK Cendekia Smart
        </div>
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
            <h3 class="step-title">Isi Form Pendaftaran</h3>
            <p class="step-desc">Lengkapi identitas yayasan, kontak penanggung jawab, serta titik lokasi peta yayasan Anda di halaman pendaftaran.</p>
        </div>

        <div class="step-card">
            <div class="step-number">2</div>
            <h3 class="step-title">Verifikasi & ACC Owner</h3>
            <p class="step-desc">Tim pusat akan memverifikasi data dan langsung meng-ACC pendaftaran serta men-generate akun <strong>Super Admin Yayasan</strong>.</p>
        </div>

        <div class="step-card">
            <div class="step-number">3</div>
            <h3 class="step-title">Setup Cabang & Guru</h3>
            <p class="step-desc">Masuk ke dasbor yayasan, daftarkan cabang TK/PAUD Anda, buat rombel kelas, dan undang akun guru serta kepala sekolah.</p>
        </div>

        <div class="step-card">
            <div class="step-number">4</div>
            <h3 class="step-title">Sistem Siap Digunakan!</h3>
            <p class="step-desc">Gunakan link unik <code>/y/kode-yayasan</code> untuk pendaftaran siswa baru secara online, pencatatan absensi, dan penerbitan e-rapor.</p>
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
            <div class="feature-icon-wrapper" style="background:#EFF6FF; color:#2563EB;">📊</div>
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
            <div class="feature-icon-wrapper" style="background:#ECFDF5; color:#10B981;">📅</div>
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
            <div class="feature-icon-wrapper" style="background:#FEF3C7; color:#D97706;">📖</div>
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
            <div class="feature-icon-wrapper" style="background:#FEE2E2; color:#EF4444;">📈</div>
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
            <div class="feature-icon-wrapper" style="background:#F3E8FF; color:#9333EA;">👥</div>
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
            <div class="feature-icon-wrapper" style="background:#E0F2FE; color:#0284C7;">🌐</div>
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
            <div class="security-icon">🛡️</div>
            <h3 class="security-title">Isolasi Tenant Ketat</h3>
            <p class="security-desc">Setiap kueri database difilter secara ketat berdasarkan <code>yayasan_id</code>. Yayasan lain tidak dapat mengintip atau mengakses data Anda.</p>
        </div>

        <div class="security-card">
            <div class="security-icon">🔒</div>
            <h3 class="security-title">Enkripsi & Hashing</h3>
            <p class="security-desc">Password disimpan dengan algoritma enkripsi Bcrypt. Jalur komunikasi data terlindungi sertifikat SSL/TLS enkripsi end-to-end.</p>
        </div>

        <div class="security-card">
            <div class="security-icon">👥</div>
            <h3 class="security-title">Role-Based Access (RBAC)</h3>
            <p class="security-desc">Hak akses ketat berbasis peran: Owner, Super Admin Yayasan, Admin Sekolah, Guru Kelas, Kepala Sekolah, dan Wali Murid.</p>
        </div>

        <div class="security-card">
            <div class="security-icon">💾</div>
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
            <div class="testimonial-rating">★★★★★</div>
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
            <div class="testimonial-rating">★★★★★</div>
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
            <div class="testimonial-rating">★★★★★</div>
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
    <div class="cta-banner-container">
        <h2 class="cta-banner-title">Siap Mendigitalkan Manajemen Yayasan & TK Anda?</h2>
        <p class="cta-banner-desc">Bergabunglah bersama ratusan yayasan pendidikan anak usia dini lainnya di platform Kiddy School ERP hari ini.</p>
        <a href="{{ route('yayasan.daftar') }}" class="btn-cta-large">
            <iconify-icon icon="solar:pen-new-square-bold-duotone" style="font-size:22px; color:#2563EB;"></iconify-icon>
            Daftarkan Yayasan Sekarang
        </a>
    </div>
</section>

<!-- Site Footer -->
<footer class="site-footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="brand-icon" style="width:32px;height:32px;font-size:16px;">🏫</div>
                <h4 style="margin:0;">Kiddy School ERP</h4>
            </div>
            <p>Platform Cloud SaaS Manajemen Yayasan Pendidikan Anak Usia Dini (TK, PAUD, KB) terdepan dengan teknologi multi-tenant terisolasi.</p>
        </div>

        <div class="footer-col">
            <h5>Navigasi</h5>
            <ul class="footer-links">
                <li><a href="#fitur">Fitur & Modul</a></li>
                <li><a href="#cara-kerja">Alur Cara Kerja</a></li>
                <li><a href="#keamanan">Keamanan Data</a></li>
                <li><a href="#testimoni">Testimoni Klien</a></li>
                <li><a href="{{ route('yayasan.daftar') }}">Daftar Yayasan</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h5>Bantuan & Legal</h5>
            <ul class="footer-links">
                <li><a href="#faq">FAQ Pertanyaan</a></li>
                <li><a href="{{ route('legal.privacy') }}">Kebijakan Privasi</a></li>
                <li><a href="{{ route('legal.terms') }}">Syarat & Ketentuan</a></li>
                <li><a href="/login">Portal Login Staf</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h5>Kontak & Layanan</h5>
            <p style="font-size:13px; line-height:1.7; color:#94A3B8;">
                Konsultasi & demo sistem:<br>
                <strong class="text-white">halo@kiddyschool.id</strong><br>
                WhatsApp: <strong class="text-white">0812-3456-7890</strong><br>
                Senin – Jumat, 08.00 – 17.00 WIB
            </p>
        </div>
    </div>

    <div class="footer-bottom">
        © {{ date('Y') }} Kiddy School ERP. Seluruh Hak Cipta Dilindungi Undang-Undang.
    </div>
</footer>

<script>
// FAQ Accordion Toggle
function toggleFaq(el) {
    const item = el.parentElement;
    const wasActive = item.classList.contains('active');
    
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));
    
    if (!wasActive) {
        item.classList.add('active');
    }
}
</script>

</body>
</html>
