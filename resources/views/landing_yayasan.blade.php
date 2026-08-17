<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $yayasan->nama_yayasan }} — Membangun Generasi Cerdas & Berakhlak Mulia</title>
    <meta name="description" content="Selamat datang di website resmi {{ $yayasan->nama_yayasan }}. Temukan informasi cabang TK/PAUD, kurikulum unggulan, galeri kegiatan, dan pendaftaran PPDB online.">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap & Iconify --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>

    @php
        $landingContent = $yayasan->landing_content ?? [];
        $template       = $landingContent['template'] ?? 'ocean';

        // Content bindings with fallbacks
        $heroTitle      = !empty($landingContent['hero_title']) ? $landingContent['hero_title'] : 'Membangun Generasi Cerdas, Mandiri & Berakhlak Mulia';
        $heroSubtitle   = !empty($landingContent['hero_subtitle']) ? $landingContent['hero_subtitle'] : 'Mendidik anak usia dini dengan penuh kasih sayang, kurikulum holistik terintegrasi, dan fasilitas pembelajaran yang aman serta menyenangkan.';
        $whatsappCta    = $landingContent['whatsapp_cta'] ?? $yayasan->telepon;
        $alamatTampil   = $landingContent['alamat_tampil'] ?? $yayasan->alamat;
        $tahunBerdiri   = $landingContent['tahun_berdiri'] ?? 2015;
        $badgeText      = $landingContent['badge_text'] ?? 'Pendaftaran & Inden Dibuka — TA ' . (date('Y')) . '/' . (date('Y')+1);
        $akreditasi     = $landingContent['akreditasi'] ?? 'A';
        $jumlahPengajar = $landingContent['jumlah_pengajar'] ?? '18';
        $pengabdianTahun= date('Y') - (int)$tahunBerdiri;
        if ($pengabdianTahun <= 0) $pengabdianTahun = 10;
    @endphp

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        :root {
            --primary:       #1E40AF;
            --primary-dark:  #1E3A8A;
            --accent:        #F59E0B;
            --dark:          #0F172A;
            --surface:       #F8FAFC;
            --card-radius:   20px;
            --card-bg:       #FFFFFF;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: #1E293B;
            overflow-x: hidden;
        }

        /* ── Top Navigation Bar ──────────────────────── */
        .top-nav {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #E2E8F0;
            padding: 14px 28px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 14px rgba(0,0,0,0.03);
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
        .brand-avatar {
            width: 40px; height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1E40AF, #3B82F6);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(30,64,175,0.25);
            overflow: hidden;
            flex-shrink: 0;
        }
        .brand-avatar img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 24px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-menu a {
            text-decoration: none;
            color: #475569;
            font-size: 13.5px;
            font-weight: 600;
            transition: color 0.2s;
        }
        .nav-menu a:hover {
            color: #1E40AF;
        }
        .btn-nav-daftar {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: #0F172A !important;
            font-weight: 800;
            font-size: 13.5px;
            padding: 10px 22px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(245,158,11,0.35);
            transition: all 0.25s;
            border: none;
        }
        .btn-nav-daftar:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(245,158,11,0.45);
        }

        /* ── Hero Section (Focal Point Utama) ────────── */
        .hero-section {
            background: linear-gradient(135deg, #0F172A 0%, #1E3A8A 60%, #1E40AF 100%);
            padding: 75px 24px 70px;
            position: relative;
            overflow: hidden;
            color: #fff;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }
        .hero-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.22);
            color: #FDE68A;
            border-radius: 100px;
            padding: 7px 18px;
            font-size: 12.5px;
            font-weight: 700;
            margin-bottom: 20px;
            backdrop-filter: blur(8px);
        }
        .hero-title {
            font-size: clamp(28px, 4.2vw, 48px);
            font-weight: 800;
            color: #FFFFFF;
            line-height: 1.18;
            letter-spacing: -0.03em;
            margin-bottom: 18px;
        }
        .hero-subtitle {
            font-size: 16px;
            color: #E2E8F0;
            line-height: 1.7;
            margin-bottom: 32px;
            max-width: 560px;
        }
        .hero-buttons {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }
        .btn-cta-orange {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: #0F172A !important;
            font-weight: 800;
            font-size: 15px;
            padding: 14px 32px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 24px rgba(245,158,11,0.4);
            transition: all 0.25s;
        }
        .btn-cta-orange:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(245,158,11,0.5);
        }
        .btn-cta-outline {
            background: rgba(255,255,255,0.08);
            border: 1.5px solid rgba(255,255,255,0.3);
            color: #fff !important;
            font-weight: 600;
            font-size: 15px;
            padding: 13px 28px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            backdrop-filter: blur(6px);
        }
        .btn-cta-outline:hover {
            background: rgba(255,255,255,0.18);
        }

        /* Hero Image Focal Point Card */
        .hero-visual-card {
            position: relative;
            border-radius: 28px;
            overflow: hidden;
            border: 2px solid rgba(255,255,255,0.2);
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            max-height: 440px;
        }
        .hero-visual-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            min-height: 380px;
        }
        .hero-visual-badge {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 16px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* Stats Bar */
        .stats-bar {
            background: #FFFFFF;
            border-radius: 24px;
            box-shadow: 0 10px 36px rgba(0,0,0,0.06);
            border: 1px solid #E2E8F0;
            padding: 24px 32px;
            margin-top: -36px;
            position: relative;
            z-index: 10;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            text-align: center;
        }
        .stat-item .stat-num {
            font-size: 28px;
            font-weight: 800;
            color: #1E40AF;
            line-height: 1;
        }
        .stat-item .stat-label {
            font-size: 13px;
            color: #64748B;
            margin-top: 6px;
            font-weight: 600;
        }

        /* ── General Section Utilities ──────────────── */
        .section-padding {
            padding: 85px 24px;
        }
        .section-header-center {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 52px;
        }
        .section-pill-tag {
            display: inline-block;
            background: #EFF6FF;
            color: #1E40AF;
            border: 1px solid #DBEAFE;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 5px 16px;
            border-radius: 100px;
            margin-bottom: 12px;
        }
        .section-main-title {
            font-size: 32px;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.025em;
            margin-bottom: 12px;
        }
        .section-subtitle-text {
            font-size: 15px;
            color: #64748B;
            line-height: 1.65;
        }

        /* ── Tentang & Visi Misi Cards ──────────────── */
        .vision-card {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 32px 28px;
            height: 100%;
            box-shadow: 0 4px 16px rgba(0,0,0,0.02);
            transition: transform .2s;
        }
        .vision-card:hover {
            transform: translateY(-3px);
            border-color: #93C5FD;
        }

        /* ── School Branch Cards ────────────────────── */
        .school-card-item {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.03);
            transition: all 0.25s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .school-card-item:hover {
            transform: translateY(-5px);
            border-color: #3B82F6;
            box-shadow: 0 16px 36px rgba(30,64,175,0.08);
        }
        .school-card-banner {
            height: 140px;
            background: linear-gradient(135deg, #1E3A8A, #3B82F6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 48px;
            position: relative;
        }
        .school-card-content {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .btn-daftar-school {
            background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);
            color: #fff !important;
            border: none;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 700;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: auto;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(30,64,175,0.25);
        }
        .btn-daftar-school:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(30,64,175,0.35);
        }

        /* ── Gallery & Activities Grid (Wajib Ada) ───── */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }
        .gallery-card {
            background: #FFFFFF;
            border-radius: 20px;
            overflow: hidden;
            border: 1.5px solid #E2E8F0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.03);
            transition: all 0.25s;
        }
        .gallery-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 32px rgba(0,0,0,0.08);
            border-color: #93C5FD;
        }
        .gallery-img-wrapper {
            height: 220px;
            overflow: hidden;
            position: relative;
        }
        .gallery-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }
        .gallery-card:hover .gallery-img-wrapper img {
            transform: scale(1.05);
        }
        .gallery-tag-pill {
            position: absolute;
            top: 14px;
            left: 14px;
            background: rgba(15, 23, 42, 0.82);
            backdrop-filter: blur(6px);
            color: #fff;
            font-size: 11.5px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 100px;
        }
        .gallery-body {
            padding: 20px 22px;
        }
        .gallery-title {
            font-size: 16px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 6px;
        }
        .gallery-desc {
            font-size: 13px;
            color: #64748B;
            line-height: 1.6;
            margin: 0;
        }

        /* ── Keunggulan Grid ────────────────────────── */
        .features-grid-custom {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 22px;
        }
        .feature-item-card {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 18px;
            padding: 26px;
            transition: all 0.2s;
        }
        .feature-item-card:hover {
            border-color: #3B82F6;
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(30,64,175,0.06);
        }

        /* ── Testimonials ────────────────────────────── */
        .testimonial-card-item {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        /* ── CTA Banner ─────────────────────────────── */
        .cta-bottom-banner {
            background: linear-gradient(135deg, #1E3A8A 0%, #1E40AF 100%);
            border-radius: 28px;
            padding: 56px 40px;
            color: #fff;
            text-align: center;
            box-shadow: 0 16px 40px rgba(30,64,175,0.2);
            position: relative;
            overflow: hidden;
        }

        /* ── Footer ─────────────────────────────────── */
        .site-footer-custom {
            background: #0F172A;
            color: #94A3B8;
            padding: 50px 24px 28px;
            border-top: 1px solid #1E293B;
            font-size: 13.5px;
        }

        @media (max-width: 992px) {
            .nav-menu { display: none; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .hero-section { text-align: center; }
            .hero-subtitle { margin-left: auto; margin-right: auto; }
            .hero-buttons { justify-content: center; }
        }
        @media (max-width: 600px) {
            .stats-grid { grid-template-columns: 1fr; }
            .top-nav { padding: 12px 18px; }
            .cta-bottom-banner { padding: 36px 20px; }
        }
    </style>
</head>
<body>

<!-- ── Top Navigation Bar ────────────────────────── -->
<nav class="top-nav">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ route('yayasan.landing', $yayasan->kode_yayasan) }}" class="brand-logo">
            <div class="brand-avatar">
                @if($yayasan->logo_yayasan)
                    <img src="{{ asset('storage/' . $yayasan->logo_yayasan) }}" alt="Logo">
                @else
                    {{ strtoupper(substr($yayasan->nama_yayasan, 0, 2)) }}
                @endif
            </div>
            <div>
                {{ $yayasan->nama_yayasan }}
                <div style="font-size:11px; font-weight:600; color:#1E40AF; letter-spacing:0.02em;">LEMBAGA PENDIDIKAN ANAK USIA DINI</div>
            </div>
        </a>

        <ul class="nav-menu">
            <li><a href="#hero">Beranda</a></li>
            <li><a href="#tentang">Tentang Kami</a></li>
            <li><a href="#cabang">Cabang TK / PAUD</a></li>
            <li><a href="#galeri">Galeri Kegiatan</a></li>
            <li><a href="#keunggulan">Keunggulan</a></li>
            <li><a href="#testimoni">Testimoni</a></li>
        </ul>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('yayasan.pendaftaran', $yayasan->kode_yayasan) }}" class="btn-nav-daftar">
                <iconify-icon icon="solar:pen-new-square-bold" style="font-size:16px;"></iconify-icon>
                Daftar PPDB Online
            </a>
            <a href="/login" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold d-none d-sm-inline-flex align-items-center gap-1">
                <iconify-icon icon="solar:login-2-bold-duotone"></iconify-icon> Login
            </a>
        </div>
    </div>
</nav>

<!-- ── 1. Hero Section (Focal Point Utama) ────────── -->
<section class="hero-section" id="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Kolom Teks Hero -->
            <div class="col-lg-6">
                <div class="hero-badge-pill">
                    <iconify-icon icon="solar:bell-bold" style="font-size:14px;"></iconify-icon>
                    {{ $badgeText }}
                </div>
                <h1 class="hero-title">{{ $heroTitle }}</h1>
                <p class="hero-subtitle">{{ $heroSubtitle }}</p>

                <div class="hero-buttons">
                    <a href="{{ route('yayasan.pendaftaran', $yayasan->kode_yayasan) }}" class="btn-cta-orange">
                        <iconify-icon icon="solar:rocket-bold-duotone" style="font-size:20px;"></iconify-icon>
                        Daftar PPDB Sekarang
                    </a>
                    <a href="#tentang" class="btn-cta-outline">
                        <iconify-icon icon="solar:info-circle-bold-duotone" style="font-size:18px;"></iconify-icon>
                        Kenali Program Kami
                    </a>
                </div>
            </div>

            <!-- Kolom Visual Kuat (Foto Interaksi Guru & Murid Nyata) -->
            <div class="col-lg-6">
                <div class="hero-visual-card">
                    <!-- Foto Berkualitas Tinggi: Interaksi Guru & Anak Preschool -->
                    <img src="https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=900&auto=format&fit=crop&q=80" alt="Belajar Ceria di {{ $yayasan->nama_yayasan }}">
                    
                    <div class="hero-visual-badge">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-warning text-dark fw-bold" style="width:42px;height:42px;font-size:20px;flex-shrink:0;">
                            ✨
                        </div>
                        <div>
                            <div class="fw-bold text-white small">Pendidikan Holistik Ramah Anak</div>
                            <div class="text-white-50" style="font-size:11.5px;">Membimbing motorik, logika, & adab islami sejak usia dini.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── Stats Bar ──────────────────────────────────── -->
<div class="container">
    <div class="stats-bar">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-num">{{ $pengabdianTahun }}+</div>
                <div class="stat-label">Tahun Pengabdian</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">{{ $total_siswa > 0 ? $total_siswa : '300+' }}+</div>
                <div class="stat-label">Siswa Aktif</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">{{ $jumlahPengajar }}+</div>
                <div class="stat-label">Tenaga Pendidik Profesional</div>
            </div>
            <div class="stat-item">
                <div class="stat-num" style="color:#10B981;">Akreditasi {{ $akreditasi }}</div>
                <div class="stat-label">BAN PAUD & PNF</div>
            </div>
        </div>
    </div>
</div>

<!-- ── 2. Tentang Yayasan & Visi Misi ─────────────── -->
<section class="section-padding" id="tentang">
    <div class="container">
        <div class="section-header-center">
            <span class="section-pill-tag">Profil Lembaga</span>
            <h2 class="section-main-title">Tentang {{ $yayasan->nama_yayasan }}</h2>
            <p class="section-subtitle-text">
                Dedikasi berkelanjutan dalam menghadirkan lingkungan belajar yang aman, hangat, dan berstandar mutu tinggi bagi tumbuh kembang optimal putra-putri Anda.
            </p>
        </div>

        <div class="row g-4 mb-4">
            <!-- Visi Card -->
            <div class="col-md-6">
                <div class="vision-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3 text-white" style="width:44px;height:44px;background:linear-gradient(135deg,#1E40AF,#3B82F6);font-size:22px;">🎯</div>
                        <h4 class="fw-bold mb-0" style="font-size:18px; color:#0F172A;">Visi Kami</h4>
                    </div>
                    <p class="text-muted leading-relaxed" style="font-size:14.5px;">
                        "Menjadi lembaga pendidikan anak usia dini terkemuka yang unggul dalam membentuk generasi beriman, berakhlak mulia, cerdas, kreatif, dan mandiri berwawasan global."
                    </p>
                </div>
            </div>

            <!-- Misi Card -->
            <div class="col-md-6">
                <div class="vision-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3 text-white" style="width:44px;height:44px;background:linear-gradient(135deg,#10B981,#34D399);font-size:22px;">🚀</div>
                        <h4 class="fw-bold mb-0" style="font-size:18px; color:#0F172A;">Misi Utama</h4>
                    </div>
                    <ul class="list-unstyled text-muted small d-flex flex-direction-column gap-2 mb-0" style="font-size:13.5px; line-height:1.6;">
                        <li class="d-flex align-items-start gap-2">
                            <iconify-icon icon="solar:check-circle-bold" class="text-success fs-5 mt-0.5 flex-shrink-0"></iconify-icon>
                            <span>Menanamkan nilai-nilai keagamaan dan pembiasaan adab mulia sejak dini.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <iconify-icon icon="solar:check-circle-bold" class="text-success fs-5 mt-0.5 flex-shrink-0"></iconify-icon>
                            <span>Mengembangkan seluruh potensi kognitif, motorik, bahasa, dan sosio-emosional anak.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <iconify-icon icon="solar:check-circle-bold" class="text-success fs-5 mt-0.5 flex-shrink-0"></iconify-icon>
                            <span>Menciptakan lingkungan bermain seraya belajar yang aman, asri, dan menyenangkan.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <iconify-icon icon="solar:check-circle-bold" class="text-success fs-5 mt-0.5 flex-shrink-0"></iconify-icon>
                            <span>Membangun kemitraan erat dengan orang tua dalam memantau perkembangan anak.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── 3. Unit Pendidikan (Cabang TK / PAUD) ───────── -->
<section class="section-padding bg-white border-top border-bottom" id="cabang">
    <div class="container">
        <div class="section-header-center">
            <span class="section-pill-tag">Unit Pendidikan</span>
            <h2 class="section-main-title">Cabang Sekolah di Bawah Yayasan</h2>
            <p class="section-subtitle-text">
                Pilih cabang TK / PAUD terdekat dengan domisili Anda untuk melihat informasi kuota dan mendaftarkan anak Anda.
            </p>
        </div>

        <div class="row g-4">
            @forelse($schools as $school)
            <div class="col-md-6 col-lg-4">
                <div class="school-card-item">
                    <div class="school-card-banner">
                        <span>🏫</span>
                        <span class="badge bg-warning text-dark font-bold position-absolute top-0 end-0 m-3 px-3 py-1.5 rounded-pill" style="font-size:11px;">
                            PPDB DIBUKA
                        </span>
                    </div>
                    <div class="school-card-content">
                        <h4 class="fw-bold mb-1" style="font-size:18px; color:#0F172A;">{{ $school->nama_sekolah }}</h4>
                        <div class="d-flex align-items-center gap-3 text-muted small mb-3">
                            <span><iconify-icon icon="solar:users-group-rounded-bold" class="text-primary me-1"></iconify-icon>{{ $school->jumlah_siswa }} Siswa Aktif</span>
                            <span><iconify-icon icon="solar:verified-check-bold" class="text-success me-1"></iconify-icon>Akreditasi A</span>
                        </div>
                        @if($school->alamat)
                        <p class="text-muted small mb-4" style="line-height:1.5;">
                            <iconify-icon icon="solar:map-point-bold" class="text-primary me-1"></iconify-icon>
                            {{ Str::limit($school->alamat, 75) }}
                        </p>
                        @endif

                        <a href="{{ route('yayasan.pendaftaran', ['kode_yayasan' => $yayasan->kode_yayasan, 'school_id' => $school->id]) }}" class="btn-daftar-school">
                            Daftar ke Sekolah Ini →
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <div style="font-size:42px; margin-bottom:10px;">🏫</div>
                <h5>Belum ada data cabang sekolah aktif.</h5>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ── 4. Galeri & Kegiatan Unggulan (Wajib Ada!) ─── -->
<section class="section-padding" id="galeri">
    <div class="container">
        <div class="section-header-center">
            <span class="section-pill-tag">Aktivitas & Fasilitas</span>
            <h2 class="section-main-title">Galeri Kegiatan & Fasilitas Unggulan</h2>
            <p class="section-subtitle-text">
                Dokumentasi nyata keseruan belajar, bermain, pembiasaan ibadah, dan eksplorasi bakat anak-anak di sekolah kami.
            </p>
        </div>

        <div class="gallery-grid">
            <!-- Galeri 1 -->
            <div class="gallery-card">
                <div class="gallery-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=700&auto=format&fit=crop&q=80" alt="Belajar & Motorik Kreatif">
                    <span class="gallery-tag-pill">🎨 Kreativitas & Motorik</span>
                </div>
                <div class="gallery-body">
                    <h3 class="gallery-title">Eksplorasi Belajar & Sensorik</h3>
                    <p class="gallery-desc">Mewarnai, meronce, menyusun balok, dan melatih motorik halus dengan metode bermain bermakna.</p>
                </div>
            </div>

            <!-- Galeri 2 -->
            <div class="gallery-card">
                <div class="gallery-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?w=700&auto=format&fit=crop&q=80" alt="Tahfidz & Doa Harian">
                    <span class="gallery-tag-pill">🕌 Karakter & Keagamaan</span>
                </div>
                <div class="gallery-body">
                    <h3 class="gallery-title">Tahfidz Cilik & Pembiasaan Doa</h3>
                    <p class="gallery-desc">Hafalan surat-surat pendek juz 30, doa harian, dan pengenalan adab sopan santun sejak dini.</p>
                </div>
            </div>

            <!-- Galeri 3 -->
            <div class="gallery-card">
                <div class="gallery-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?w=700&auto=format&fit=crop&q=80" alt="Ekstrakurikuler Drumband & Seni">
                    <span class="gallery-tag-pill">🥁 Ekstrakurikuler</span>
                </div>
                <div class="gallery-body">
                    <h3 class="gallery-title">Minat Bakat & Drumband Cilik</h3>
                    <p class="gallery-desc">Melatih kekompakan, rasa percaya diri, dan kepekaan musikalitas anak melalui ekskul pilihan.</p>
                </div>
            </div>

            <!-- Galeri 4 -->
            <div class="gallery-card">
                <div class="gallery-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1596464716127-f2a829822301?w=700&auto=format&fit=crop&q=80" alt="Playground & Outbound">
                    <span class="gallery-tag-pill">🌳 Area Bermain Outbound</span>
                </div>
                <div class="gallery-body">
                    <h3 class="gallery-title">Taman Bermain Aman & Asri</h3>
                    <p class="gallery-desc">Fasilitas playground indoor & outdoor yang aman untuk melatih ketangkasan serta motorik kasar.</p>
                </div>
            </div>

            <!-- Galeri 5 -->
            <div class="gallery-card">
                <div class="gallery-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=700&auto=format&fit=crop&q=80" alt="Field Trip & Pentas Seni">
                    <span class="gallery-tag-pill">🎭 Pentas Seni & Field Trip</span>
                </div>
                <div class="gallery-body">
                    <h3 class="gallery-title">Panggung Apresiasi & Wisata Edukasi</h3>
                    <p class="gallery-desc">Kunjungan profesi edukatif serta panggung tahunan untuk melatih keberanian anak tampil di depan publik.</p>
                </div>
            </div>

            <!-- Galeri 6 -->
            <div class="gallery-card">
                <div class="gallery-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=700&auto=format&fit=crop&q=80" alt="Eksperimen Sains Sederhana">
                    <span class="gallery-tag-pill">🧪 Sains & Eksplorasi</span>
                </div>
                <div class="gallery-body">
                    <h3 class="gallery-title">Eksperimen Sains Anak Usia Dini</h3>
                    <p class="gallery-desc">Mengenalkan logika fenomena alam dan rasa ingin tahu ilmiah melalui percobaan sederhana yang aman.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── 5. Keunggulan (Mengapa Memilih Kami?) ───────── -->
<section class="section-padding bg-white border-top border-bottom" id="keunggulan">
    <div class="container">
        <div class="section-header-center">
            <span class="section-pill-tag">Keunggulan Lembaga</span>
            <h2 class="section-main-title">Mengapa Memilih {{ $yayasan->nama_yayasan }}?</h2>
            <p class="section-subtitle-text">
                Fasilitas lengkap, tenaga pendidik berkompeten, dan kurikulum yang didesain agar anak mencintai proses belajar.
            </p>
        </div>

        <div class="features-grid-custom">
            <div class="feature-item-card">
                <div class="d-flex align-items-center justify-content-center rounded-3 text-white mb-3" style="width:44px;height:44px;background:linear-gradient(135deg,#2563EB,#3B82F6);font-size:22px;">👩‍🏫</div>
                <h4 class="fw-bold mb-2" style="font-size:16px; color:#0F172A;">Guru Profesional & Penuh Kasih</h4>
                <p class="text-muted small mb-0" style="line-height:1.6;">Pendidik berkualifikasi S1 PAUD/PGTK yang sabar, telaten, dan memahami tahapan psikologi perkembangan anak.</p>
            </div>

            <div class="feature-item-card">
                <div class="d-flex align-items-center justify-content-center rounded-3 text-white mb-3" style="width:44px;height:44px;background:linear-gradient(135deg,#10B981,#34D399);font-size:22px;">📚</div>
                <h4 class="fw-bold mb-2" style="font-size:16px; color:#0F172A;">Kurikulum Merdeka & Adab</h4>
                <p class="text-muted small mb-0" style="line-height:1.6;">Memadukan standar nasional Kurikulum Merdeka PAUD dengan pembiasaan adab islami dan kemandirian sehari-hari.</p>
            </div>

            <div class="feature-item-card">
                <div class="d-flex align-items-center justify-content-center rounded-3 text-white mb-3" style="width:44px;height:44px;background:linear-gradient(135deg,#F59E0B,#FBBF24);font-size:22px;">📱</div>
                <h4 class="fw-bold mb-2" style="font-size:16px; color:#0F172A;">Portal Digital Orang Tua</h4>
                <p class="text-muted small mb-0" style="line-height:1.6;">Pantau absensi harian, foto dokumentasi jurnal kelas, dan lembar e-rapor anak secara mudah lewat smartphone.</p>
            </div>

            <div class="feature-item-card">
                <div class="d-flex align-items-center justify-content-center rounded-3 text-white mb-3" style="width:44px;height:44px;background:linear-gradient(135deg,#EF4444,#F87171);font-size:22px;">📈</div>
                <h4 class="fw-bold mb-2" style="font-size:16px; color:#0F172A;">Monitoring Fisik & Medis Berkala</h4>
                <p class="text-muted small mb-0" style="line-height:1.6;">Pemeriksaan tinggi badan, berat badan, lingkar kepala, dan kesehatan gigi standar KMS Dapodik berkala.</p>
            </div>

            <div class="feature-item-card">
                <div class="d-flex align-items-center justify-content-center rounded-3 text-white mb-3" style="width:44px;height:44px;background:linear-gradient(135deg,#8B5CF6,#A78BFA);font-size:22px;">🛡️</div>
                <h4 class="fw-bold mb-2" style="font-size:16px; color:#0F172A;">Lingkungan Asri & Ramah Anak</h4>
                <p class="text-muted small mb-0" style="line-height:1.6;">Ruang kelas ber-AC, pencahayaan alami, alat peraga edukasi non-toxic, serta keamanan sekolah terpantau ketat.</p>
            </div>

            <div class="feature-item-card">
                <div class="d-flex align-items-center justify-content-center rounded-3 text-white mb-3" style="width:44px;height:44px;background:linear-gradient(135deg,#06B6D4,#22D3EE);font-size:22px;">🤝</div>
                <h4 class="fw-bold mb-2" style="font-size:16px; color:#0F172A;">Kemitraan Erat dengan Orang Tua</h4>
                <p class="text-muted small mb-0" style="line-height:1.6;">Laporan harian, sesi konsultasi berkala, dan pelibatan orang tua dalam kegiatan parenting serta pentas kreasi.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── 6. Testimoni Wali Murid ────────────────────── -->
<section class="section-padding" id="testimoni">
    <div class="container">
        <div class="section-header-center">
            <span class="section-pill-tag">Ulasan Nyata</span>
            <h2 class="section-main-title">Apa Kata Orang Tua Murid?</h2>
            <p class="section-subtitle-text">
                Kepercayaan dan kepuasan wali murid adalah amanah berharga yang senantiasa kami jaga.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card-item">
                    <div>
                        <div class="text-warning mb-3" style="font-size:17px;">★★★★★</div>
                        <p class="text-muted small fst-italic mb-4" style="line-height:1.7;">
                            "Anak saya dulu pemalu sekali, tapi setelah 3 bulan di sini jadi sangat percaya diri, ceria, dan sudah hafal banyak doa pendek. Gurunya sangat telaten dan hangat."
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <div class="rounded-circle bg-primary-subtle text-primary font-bold d-flex align-items-center justify-content-center fw-bold" style="width:42px;height:42px;font-size:14px;">
                            DR
                        </div>
                        <div>
                            <div class="fw-bold text-dark small">Bunda Dwi Rahayu</div>
                            <div class="text-muted" style="font-size:11.5px;">Wali Murid TK A</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="testimonial-card-item">
                    <div>
                        <div class="text-warning mb-3" style="font-size:17px;">★★★★★</div>
                        <p class="text-muted small fst-italic mb-4" style="line-height:1.7;">
                            "Fasilitas playground-nya bersih dan aman. Program tahfidz dan pembiasaan adab makannya sangat terasa di rumah. Sangat bersyukur memilih yayasan ini."
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <div class="rounded-circle bg-success-subtle text-success font-bold d-flex align-items-center justify-content-center fw-bold" style="width:42px;height:42px;font-size:14px;">
                            AP
                        </div>
                        <div>
                            <div class="fw-bold text-dark small">Bapak Andi Prasetyo</div>
                            <div class="text-muted" style="font-size:11.5px;">Wali Murid TK B</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="testimonial-card-item">
                    <div>
                        <div class="text-warning mb-3" style="font-size:17px;">★★★★★</div>
                        <p class="text-muted small fst-italic mb-4" style="line-height:1.7;">
                            "Portal orang tuanya canggih! Tiap sore bisa lihat foto kegiatan harian anak di kelas, dan e-rapornya sangat detail menggambarkan perkembangan motorik anak."
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <div class="rounded-circle bg-warning-subtle text-warning font-bold d-flex align-items-center justify-content-center fw-bold" style="width:42px;height:42px;font-size:14px;">
                            SA
                        </div>
                        <div>
                            <div class="fw-bold text-dark small">Bunda Siti Aminah</div>
                            <div class="text-muted" style="font-size:11.5px;">Wali Murid PAUD</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── CTA Bottom Banner ──────────────────────────── -->
<div class="container my-5">
    <div class="cta-bottom-banner">
        <h3 class="fw-bold mb-2" style="font-size:28px;">Daftarkan Putra-Putri Anda Sekarang!</h3>
        <p class="text-white-50 small mb-4 mx-auto" style="max-width:580px; font-size:14.5px;">
            Amankan kuota tempat duduk di cabang sekolah kami. Proses pendaftaran mudah dan cepat secara online.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('yayasan.pendaftaran', $yayasan->kode_yayasan) }}" class="btn-cta-orange">
                <iconify-icon icon="solar:pen-new-square-bold" style="font-size:18px;"></iconify-icon>
                Buka Formulir PPDB Online
            </a>
            @if($whatsappCta)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappCta) }}" target="_blank" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
                <iconify-icon icon="solar:chat-round-dots-bold" style="font-size:18px;"></iconify-icon>
                Konsultasi via WhatsApp
            </a>
            @endif
        </div>
    </div>
</div>

<!-- ── Footer ─────────────────────────────────────── -->
<footer class="site-footer-custom">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <h5 class="fw-bold text-white mb-2">{{ $yayasan->nama_yayasan }}</h5>
                <p class="text-white-50 small mb-3">Lembaga Pendidikan Anak Usia Dini (TK, PAUD, Kelompok Bermain) yang berdedikasi membangun masa depan cerah generasi bangsa.</p>
                @if($alamatTampil)
                <div class="small text-white-50 mb-2"><iconify-icon icon="solar:map-point-bold" class="text-primary me-1"></iconify-icon>{{ $alamatTampil }}</div>
                @endif
                @if($yayasan->telepon)
                <div class="small text-white-50"><iconify-icon icon="solar:phone-bold" class="text-primary me-1"></iconify-icon>{{ $yayasan->telepon }}</div>
                @endif
            </div>

            <div class="col-lg-3 offset-lg-3">
                <h6 class="text-uppercase text-white fw-bold mb-3 small" style="letter-spacing:1px;">Tautan Cepat</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2 text-white-50">
                    <li><a href="#tentang" class="text-white-50 text-decoration-none">Profil Yayasan</a></li>
                    <li><a href="#cabang" class="text-white-50 text-decoration-none">Cabang Sekolah</a></li>
                    <li><a href="#galeri" class="text-white-50 text-decoration-none">Galeri Kegiatan</a></li>
                    <li><a href="{{ route('yayasan.pendaftaran', $yayasan->kode_yayasan) }}" class="text-warning text-decoration-none fw-bold">Daftar PPDB Online</a></li>
                    <li><a href="/login" class="text-white-50 text-decoration-none">Login Portal Guru & Wali</a></li>
                </ul>
            </div>
        </div>

        <div class="border-top pt-4 text-center text-white-50 small" style="border-color:rgba(255,255,255,0.1) !important;">
            © {{ date('Y') }} {{ $yayasan->nama_yayasan }}. Didukung oleh <a href="/" class="text-primary text-decoration-none fw-bold">Kiddy School ERP</a>.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
