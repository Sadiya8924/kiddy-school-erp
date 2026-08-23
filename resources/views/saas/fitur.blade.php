@php
    $platformName = \App\Models\Setting::get('saas_platform_name', 'Kiddy School ERP');
    $platformTagline = \App\Models\Setting::get('saas_platform_tagline', 'MULTI-TENANT PLATFORM');
    $platformLogo = \App\Models\Setting::get('site_logo');
    $headerCta = \App\Models\Setting::get('saas_header_cta_text', 'Daftar Sekarang');

    $ctaTitle = \App\Models\Setting::get('saas_cta_title', 'Siap Mentransformasi Digital Yayasan & Sekolah Anda?');
    $ctaDesc = \App\Models\Setting::get('saas_cta_desc', 'Bergabunglah bersama puluhan yayasan pendidikan lainnya yang telah menggunakan platform Kiddy School ERP.');
    $ctaBtn = \App\Models\Setting::get('saas_cta_btn_text', 'Daftarkan Yayasan Anda Sekarang');

    $footerAbout = \App\Models\Setting::get('saas_footer_about', 'Platform SaaS ERP Manajemen Yayasan TK & PAUD terintegrasi dengan arsitektur multi-tenant modern.');
    $contactEmail = \App\Models\Setting::get('saas_contact_email', 'support@kiddyschool.id');
    $contactPhone = \App\Models\Setting::get('saas_contact_phone', '+62 812-3456-7890');
    $contactAddress = \App\Models\Setting::get('saas_contact_address', 'Gedung Cyber Tech Lt. 5, Jakarta, Indonesia');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fitur Lengkap & Modul SaaS — {{ $platformName }}</title>
    <meta name="description" content="Jelajahi seluruh fitur dan modul lengkap Kiddy School ERP: E-Rapor Kurikulum Merdeka, Presensi Realtime, Jurnal Pembelajaran Guru, KMS Fisik, dan PPDB Online.">

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
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #2563EB;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            box-shadow: 0 4px 12px rgba(37,99,235,0.30);
            flex-shrink: 0;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-links a {
            color: #475569;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .nav-links a:hover {
            color: #2563EB;
            background: #EFF6FF;
        }
        .nav-links a.active {
            color: #2563EB;
            background: #EFF6FF;
            font-weight: 700;
        }

        .btn-header-login {
            background: #F1F5F9;
            color: #334155;
            font-weight: 700;
            font-size: 13.5px;
            padding: 9px 18px;
            border-radius: 10px;
            text-decoration: none;
            border: 1px solid #E2E8F0;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s;
        }
        .btn-header-login:hover {
            background: #E2E8F0;
            color: #0F172A;
        }
        .btn-header-cta {
            background: #2563EB;
            color: #fff;
            font-weight: 700;
            font-size: 13.5px;
            padding: 9px 20px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 4px 14px rgba(37,99,235,0.35);
            transition: all 0.2s;
        }
        .btn-header-cta:hover {
            background: #1D4ED8;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.45);
        }

        /* ── Page Header Banner ─────────────────────── */
        .page-header-section {
            background: radial-gradient(ellipse 80% 60% at 50% -10%, #1D4ED8 0%, #0B1736 55%, #050B1A 100%);
            padding: 80px 24px 70px;
            text-align: center;
            position: relative;
            overflow: hidden;
            color: #FFFFFF;
        }
        .page-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 100px;
            font-size: 12.5px;
            font-weight: 700;
            color: #93C5FD;
            backdrop-filter: blur(8px);
            margin-bottom: 20px;
        }
        .page-title {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 16px;
            line-height: 1.2;
        }
        .page-subtitle {
            font-size: 16px;
            color: #94A3B8;
            max-width: 680px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* ── Feature Detail Cards ───────────────────── */
        .detail-section {
            padding: 80px 24px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .feature-block {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 24px;
            padding: 48px;
            margin-bottom: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .feature-block:hover {
            box-shadow: 0 16px 36px rgba(37,99,235,0.07);
            border-color: #BFDBFE;
        }
        .feature-block.reverse {
            grid-template-columns: 1fr 1fr;
            direction: rtl;
        }
        .feature-block.reverse > * {
            direction: ltr;
        }
        .feature-icon-badge {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
        }
        .feature-block-title {
            font-size: 26px;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 14px;
            letter-spacing: -0.02em;
        }
        .feature-block-desc {
            font-size: 15px;
            color: #64748B;
            line-height: 1.75;
            margin-bottom: 24px;
        }
        .feature-checklist {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .feature-checklist li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }
        .feature-checklist iconify-icon {
            color: #10B981;
            font-size: 18px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .feature-preview-box {
            background: linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%);
            border: 1.5px dashed #CBD5E1;
            border-radius: 20px;
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .preview-card-mini {
            background: #FFFFFF;
            border-radius: 14px;
            padding: 16px 20px;
            border: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        /* ── Bottom CTA ─────────────────────────────── */
        .cta-banner-section {
            background: radial-gradient(ellipse 90% 80% at 50% 120%, #1D4ED8 0%, #1E40AF 40%, #0B1736 100%);
            padding: 88px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
            color: #fff;
        }
        .cta-banner-container {
            max-width: 720px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }
        .cta-banner-title {
            font-size: 38px;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 16px;
            line-height: 1.25;
        }
        .cta-banner-desc {
            font-size: 16.5px;
            color: #BFDBFE;
            margin-bottom: 36px;
            line-height: 1.7;
        }
        .btn-cta-large {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #FFFFFF;
            color: #1D4ED8;
            font-weight: 800;
            font-size: 16px;
            padding: 16px 36px;
            border-radius: 14px;
            text-decoration: none;
            box-shadow: 0 12px 32px rgba(0,0,0,0.25);
            transition: all 0.25s;
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

        @media (max-width: 992px) {
            .top-nav { padding: 14px 20px; }
            .nav-links { display: none; }
            .feature-block, .feature-block.reverse { grid-template-columns: 1fr; gap: 32px; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            .page-title { font-size: 28px; }
            .footer-grid { grid-template-columns: 1fr; }
            .feature-block { padding: 24px; }
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
        <li><a href="{{ route('saas.fitur') }}" class="active">Fitur Lengkap</a></li>
        <li><a href="{{ route('saas.cara_kerja') }}">Cara Kerja</a></li>
        <li><a href="{{ route('saas.keamanan') }}">Keamanan Data</a></li>
        <li><a href="{{ route('saas.testimoni') }}">Testimoni</a></li>
        <li><a href="{{ route('saas.faq') }}">FAQ</a></li>
    </ul>

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

<!-- Page Header Banner -->
<header class="page-header-section">
    <div class="page-badge">
        <iconify-icon icon="solar:widget-add-bold-duotone"></iconify-icon>
        Modul Lengkap & Terintegrasi
    </div>
    <h1 class="page-title">Fitur Unggulan Siap Pakai</h1>
    <p class="page-subtitle">Dirancang spesifik untuk kebutuhan administrasi anak usia dini (TK & PAUD) sesuai regulasi Kurikulum Merdeka.</p>
</header>

<!-- Feature Details Section -->
<main class="detail-section">

    <!-- Feature 1: E-Rapor -->
    <div class="feature-block">
        <div>
            <div class="feature-icon-badge" style="background:#EFF6FF; color:#2563EB;">
                <iconify-icon icon="solar:chart-bold-duotone"></iconify-icon>
            </div>
            <h2 class="feature-block-title">E-Rapor Kurikulum Merdeka</h2>
            <p class="feature-block-desc">
                Template rapor dinamis, narasi capaian pembelajaran, penilaian perkembangan, dan cetak PDF satu klik.
            </p>
            <ul class="feature-checklist">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Narasi Capaian Nilai Agama & Budi Pekerti</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Sisipkan Dokumentasi Foto Karya Anak</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Format Resmi Siap Cetak A4/F4</li>
            </ul>
        </div>
        <div class="feature-preview-box">
            <div class="preview-card-mini">
                <iconify-icon icon="solar:document-text-bold-duotone" style="font-size:26px; color:#2563EB;"></iconify-icon>
                <div>
                    <div style="font-weight:700; font-size:13.5px;">Format Resmi Kemendikbud</div>
                    <div style="font-size:12px; color:#64748B;">Capaian Fase Fondasi PAUD</div>
                </div>
            </div>
            <div class="preview-card-mini">
                <iconify-icon icon="solar:camera-bold-duotone" style="font-size:26px; color:#10B981;"></iconify-icon>
                <div>
                    <div style="font-weight:700; font-size:13.5px;">Portofolio Foto Karya</div>
                    <div style="font-size:12px; color:#64748B;">Otomatis tertata rapi dalam lembar cetak</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature 2: Presensi -->
    <div class="feature-block reverse">
        <div>
            <div class="feature-icon-badge" style="background:#ECFDF5; color:#10B981;">
                <iconify-icon icon="solar:calendar-check-bold-duotone"></iconify-icon>
            </div>
            <h2 class="feature-block-title">Presensi & Absensi Harian</h2>
            <p class="feature-block-desc">
                Pencatatan absensi realtime dengan status hadir, sakit, izin, atau tanpa keterangan beserta rekapitulasi bulanan.
            </p>
            <ul class="feature-checklist">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Rekap bulanan otomatis tanpa hitung manual</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Notifikasi realtime ke portal orang tua murid</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Unduh laporan kehadiran format Excel & PDF</li>
            </ul>
        </div>
        <div class="feature-preview-box">
            <div class="preview-card-mini">
                <iconify-icon icon="solar:clock-circle-bold-duotone" style="font-size:26px; color:#10B981;"></iconify-icon>
                <div>
                    <div style="font-weight:700; font-size:13.5px;">Presensi Cepat 1-Klik</div>
                    <div style="font-size:12px; color:#64748B;">Hemat waktu guru tiap pagi</div>
                </div>
            </div>
            <div class="preview-card-mini">
                <iconify-icon icon="solar:graph-up-bold-duotone" style="font-size:26px; color:#3B82F6;"></iconify-icon>
                <div>
                    <div style="font-weight:700; font-size:13.5px;">Grafik Kehadiran Bulanan</div>
                    <div style="font-size:12px; color:#64748B;">Statistik akurat untuk Kepala Sekolah</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature 3: Jurnal & LMS Guru -->
    <div class="feature-block">
        <div>
            <div class="feature-icon-badge" style="background:#FEF3C7; color:#D97706;">
                <iconify-icon icon="solar:notebook-bold-duotone"></iconify-icon>
            </div>
            <h2 class="feature-block-title">Jurnal Kegiatan & LMS Guru</h2>
            <p class="feature-block-desc">
                Dokumentasi aktivitas pembelajaran harian guru lengkap dengan tema mingguan, catatan perkembangan, foto kegiatan bermain sambil belajar, serta interaksi komentar bersama orang tua.
            </p>
            <ul class="feature-checklist">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Modul perencanaan tema & subtema mingguan (RPPH)</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Galeri foto resolusi tinggi aman terlindungi</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Ruang apresiasi & komentar antara guru dan orang tua</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Download bundel dokumentasi foto per semester</li>
            </ul>
        </div>
        <div class="feature-preview-box">
            <div class="preview-card-mini">
                <iconify-icon icon="solar:gallery-wide-bold-duotone" style="font-size:26px; color:#D97706;"></iconify-icon>
                <div>
                    <div style="font-weight:700; font-size:13.5px;">Feed Aktivitas Anak</div>
                    <div style="font-size:12px; color:#64748B;">Menumbuhkan kepercayaan orang tua</div>
                </div>
            </div>
            <div class="preview-card-mini">
                <iconify-icon icon="solar:chat-round-dots-bold-duotone" style="font-size:26px; color:#8B5CF6;"></iconify-icon>
                <div>
                    <div style="font-weight:700; font-size:13.5px;">Komunikasi Interaktif</div>
                    <div style="font-size:12px; color:#64748B;">Kolaborasi efektif guru & wali murid</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature 4: KMS & Fisik -->
    <div class="feature-block reverse">
        <div>
            <div class="feature-icon-badge" style="background:#FEE2E2; color:#EF4444;">
                <iconify-icon icon="solar:health-bold-duotone"></iconify-icon>
            </div>
            <h2 class="feature-block-title">Perkembangan Fisik & KMS</h2>
            <p class="feature-block-desc">
                Pemantauan tumbuh kembang fisik anak secara berkala mencakup berat badan, tinggi badan, lingkar kepala, dan rekam kesehatan fisik sesuai standar KMS Posyandu & Dapodik PAUD.
            </p>
            <ul class="feature-checklist">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Riwayat pemeriksaan medis berkala (TB, BB, LK)</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Otomatis terintegrasi ke lembar lampiran rapor anak</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Deteksi dini grafik pertumbuhan anak sehat</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Cetak data fisik untuk sinkronisasi Dapodik PAUD</li>
            </ul>
        </div>
        <div class="feature-preview-box">
            <div class="preview-card-mini">
                <iconify-icon icon="solar:heart-pulse-bold-duotone" style="font-size:26px; color:#EF4444;"></iconify-icon>
                <div>
                    <div style="font-weight:700; font-size:13.5px;">Kartu Menuju Sehat Digital</div>
                    <div style="font-size:12px; color:#64748B;">Pantau gizi & pertumbuhan anak</div>
                </div>
            </div>
            <div class="preview-card-mini">
                <iconify-icon icon="solar:clipboard-check-bold-duotone" style="font-size:26px; color:#10B981;"></iconify-icon>
                <div>
                    <div style="font-weight:700; font-size:13.5px;">Siap Sync Dapodik</div>
                    <div style="font-size:12px; color:#64748B;">Format data baku memudahkan operator</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature 5: Multi Cabang & RBAC -->
    <div class="feature-block">
        <div>
            <div class="feature-icon-badge" style="background:#F3E8FF; color:#9333EA;">
                <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
            </div>
            <h2 class="feature-block-title">Manajemen Guru & Multi-Cabang</h2>
            <p class="feature-block-desc">
                Satu dasbor pusat yayasan untuk memonitor semua unit cabang TK/PAUD. Atur pembagian rombel kelas, mutasi siswa, penugasan wali kelas, serta pembagian hak akses bertingkat.
            </p>
            <ul class="feature-checklist">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> 1 Dasbor Yayasan untuk banyak unit sekolah/cabang</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Role bertingkat: Super Admin, Admin Cabang, Guru, Kepsek, Ortu</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Proses kenaikan kelas dan kelulusan siswa sekali klik</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Log aktivitas dan audit keamanan data yayasan</li>
            </ul>
        </div>
        <div class="feature-preview-box">
            <div class="preview-card-mini">
                <iconify-icon icon="solar:buildings-bold-duotone" style="font-size:26px; color:#9333EA;"></iconify-icon>
                <div>
                    <div style="font-weight:700; font-size:13.5px;">Sentralisasi Multi-Unit</div>
                    <div style="font-size:12px; color:#64748B;">Laporan terkonsolidasi untuk pengurus yayasan</div>
                </div>
            </div>
            <div class="preview-card-mini">
                <iconify-icon icon="solar:shield-keyhole-bold-duotone" style="font-size:26px; color:#2563EB;"></iconify-icon>
                <div>
                    <div style="font-weight:700; font-size:13.5px;">Role-Based Access Control</div>
                    <div style="font-size:12px; color:#64748B;">Hak akses guru terisolasi per kelas masing-masing</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature 6: Landing Page & PPDB -->
    <div class="feature-block reverse">
        <div>
            <div class="feature-icon-badge" style="background:#E0F2FE; color:#0284C7;">
                <iconify-icon icon="solar:global-bold-duotone"></iconify-icon>
            </div>
            <h2 class="feature-block-title">Website Publik Mandiri & PPDB Online</h2>
            <p class="feature-block-desc">
                Setiap yayasan otomatis memiliki halaman profil website mandiri di URL <code>/y/{kode_yayasan}</code> yang bisa dikustomisasi tema warnanya, lengkap dengan formulir PPDB online untuk calon siswa baru.
            </p>
            <ul class="feature-checklist">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Website profil publik dengan 4 pilihan tema palet warna</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Formulir pendaftaran online dengan upload dokumen (Akta/KK)</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Verifikasi calon siswa dan aktivasi akun wali murid instan</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Kuota inden siswa terpantau realtime tiap tahun ajaran</li>
            </ul>
        </div>
        <div class="feature-preview-box">
            <div class="preview-card-mini">
                <iconify-icon icon="solar:laptop-minimalistic-bold-duotone" style="font-size:26px; color:#0284C7;"></iconify-icon>
                <div>
                    <div style="font-weight:700; font-size:13.5px;">Website Yayasan Siap Pakai</div>
                    <div style="font-size:12px; color:#64748B;">Meningkatkan citra profesional lembaga</div>
                </div>
            </div>
            <div class="preview-card-mini">
                <iconify-icon icon="solar:user-plus-bold-duotone" style="font-size:26px; color:#10B981;"></iconify-icon>
                <div>
                    <div style="font-weight:700; font-size:13.5px;">PPDB 24 Jam Online</div>
                    <div style="font-size:12px; color:#64748B;">Orang tua daftar kapan saja & di mana saja</div>
                </div>
            </div>
        </div>
    </div>

</main>

<!-- Bottom CTA Banner Section -->
<section class="cta-banner-section">
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
                <a href="#" class="social-btn" title="Instagram"><iconify-icon icon="mdi:instagram"></iconify-icon></a>
                <a href="#" class="social-btn" title="WhatsApp"><iconify-icon icon="mdi:whatsapp"></iconify-icon></a>
                <a href="#" class="social-btn" title="YouTube"><iconify-icon icon="mdi:youtube"></iconify-icon></a>
                <a href="#" class="social-btn" title="Email"><iconify-icon icon="mdi:email-outline"></iconify-icon></a>
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
            <ul class="footer-links">
                <li>Email: <strong style="color:#CBD5E1;">{{ $contactEmail }}</strong></li>
                <li>WhatsApp: <strong style="color:#CBD5E1;">{{ $contactPhone }}</strong></li>
                <li style="font-size:12px; color:#475569; margin-top:4px;">{{ $contactAddress }}</li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        &copy; {{ date('Y') }} {{ $platformName }}. Hak Cipta Dilindungi Undang-Undang.
    </div>
</footer>

</body>
</html>
