@php
    $platformName = \App\Models\Setting::get('saas_platform_name', 'Kiddy School ERP');
    $platformTagline = \App\Models\Setting::get('saas_platform_tagline', 'MULTI-TENANT PLATFORM');
    $platformLogo = \App\Models\Setting::get('site_logo');
    $headerCta = \App\Models\Setting::get('saas_header_cta_text', 'Daftar Sekarang');

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
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Alur & Cara Kerja Implementasi — {{ $platformName }}</title>
    <meta name="description" content="Pelajari 4 langkah mudah implementasi Kiddy School ERP: Pendaftaran, Verifikasi, Pengaturan Cabang & Guru, hingga Sistem Siap Digunakan.">

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
            border-radius: 10px;
            background: #2563EB;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            box-shadow: 0 4px 12px rgba(37,99,235,0.30);
            flex-shrink: 0;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
            margin: 0; padding: 0;
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
            display: inline-flex; align-items: center; gap: 7px;
            transition: all 0.2s;
        }
        .btn-header-login:hover { background: #E2E8F0; color: #0F172A; }
        .btn-header-cta {
            background: #2563EB;
            color: #fff;
            font-weight: 700;
            font-size: 13.5px;
            padding: 9px 20px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 7px;
            box-shadow: 0 4px 14px rgba(37,99,235,0.35);
            transition: all 0.2s;
        }
        .btn-header-cta:hover {
            background: #1D4ED8; color: #fff;
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
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 16px;
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 100px;
            font-size: 12.5px; font-weight: 700; color: #93C5FD;
            backdrop-filter: blur(8px);
            margin-bottom: 20px;
        }
        .page-title {
            font-size: 42px; font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 16px; line-height: 1.2;
        }
        .page-subtitle {
            font-size: 16px; color: #94A3B8;
            max-width: 680px; margin: 0 auto;
            line-height: 1.7;
        }

        /* ── Workflow Steps Section ─────────────────── */
        .workflow-detail-section {
            padding: 80px 24px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .step-timeline-item {
            display: flex;
            gap: 36px;
            position: relative;
            margin-bottom: 56px;
        }
        .step-timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            top: 56px;
            left: 28px;
            bottom: -36px;
            width: 2px;
            background: linear-gradient(180deg, #3B82F6 0%, #BFDBFE 100%);
        }
        .step-number-circle {
            width: 56px; height: 56px;
            border-radius: 18px;
            background: linear-gradient(135deg, #2563EB, #60A5FA);
            color: #FFFFFF;
            font-size: 22px; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(37,99,235,0.30);
            z-index: 1;
        }
        .step-card-content {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 22px;
            padding: 36px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.03);
            flex: 1;
            transition: all 0.3s;
        }
        .step-card-content:hover {
            border-color: #93C5FD;
            box-shadow: 0 12px 30px rgba(37,99,235,0.08);
            transform: translateY(-2px);
        }
        .step-card-title {
            font-size: 22px;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }
        .step-card-desc {
            font-size: 15px;
            color: #64748B;
            line-height: 1.75;
            margin-bottom: 20px;
        }
        .step-features-list {
            list-style: none;
            padding: 0; margin: 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .step-features-list li {
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            display: flex; align-items: center; gap: 8px;
        }
        .step-features-list iconify-icon {
            color: #2563EB; font-size: 16px; flex-shrink: 0;
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
            display: inline-flex; align-items: center; gap: 10px;
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
            color: #F1F5F9; font-weight: 800; font-size: 17px;
            margin-bottom: 10px; letter-spacing: -0.02em;
        }
        .footer-brand p {
            line-height: 1.78; font-size: 13px; color: #475569;
            max-width: 290px;
        }
        .footer-social { display: flex; gap: 8px; margin-top: 20px; }
        .social-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            display: flex; align-items: center; justify-content: center;
            color: #475569; text-decoration: none; font-size: 17px;
            transition: all 0.22s;
        }
        .social-btn:hover {
            background: rgba(37,99,235,0.18);
            border-color: rgba(96,165,250,0.28);
            color: #60A5FA;
            transform: translateY(-2px);
        }
        .footer-col h5 {
            color: #CBD5E1; font-size: 11px; font-weight: 800;
            margin-bottom: 18px; text-transform: uppercase; letter-spacing: 0.10em;
        }
        .footer-links {
            list-style: none; padding: 0; margin: 0;
            display: flex; flex-direction: column; gap: 12px;
        }
        .footer-links a {
            color: #475569; text-decoration: none; transition: color 0.2s; font-size: 13px;
        }
        .footer-links a:hover { color: #60A5FA; }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.05);
            padding-top: 28px; text-align: center; font-size: 12px; color: #334155;
            max-width: 1140px; margin: 0 auto;
        }

        @media (max-width: 992px) {
            .top-nav { padding: 14px 20px; }
            .nav-links { display: none; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .step-features-list { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .page-title { font-size: 28px; }
            .step-timeline-item { gap: 18px; }
            .step-number-circle { width: 44px; height: 44px; font-size: 18px; }
            .step-timeline-item:not(:last-child)::before { left: 22px; }
            .footer-grid { grid-template-columns: 1fr; }
            .step-card-content { padding: 22px; }
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
        <li><a href="{{ route('saas.cara_kerja') }}" class="active">Cara Kerja</a></li>
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
        <iconify-icon icon="solar:round-transfer-vertical-bold-duotone"></iconify-icon>
        Alur Implementasi Cepat & Praktis
    </div>
    <h1 class="page-title">4 Langkah Mudah Digitalisasi Yayasan</h1>
    <p class="page-subtitle">Proses onboarding yang simpel, cepat, dan didampingi tim teknis hingga sistem siap dipakai di seluruh cabang TK/PAUD Anda.</p>
</header>

<!-- Step Timeline Section -->
<main class="workflow-detail-section">

    <!-- Step 1 -->
    <div class="step-timeline-item">
        <div class="step-number-circle">1</div>
        <div class="step-card-content">
            <h2 class="step-card-title">{{ $step1Title }}</h2>
            <p class="step-card-desc">{{ $step1Desc }} Cukup sediakan data legalitas yayasan (SK Kemenkumham, Akta Notaris, NPWP) serta titik koordinat peta lokasi yayasan Anda.</p>
            <ul class="step-features-list">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Isi form online tanpa biaya pendaftaran</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Pilih kode unik subdomain yayasan</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Lampirkan identitas penanggung jawab</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Estimasi pengisian formulir hanya 3 menit</li>
            </ul>
        </div>
    </div>

    <!-- Step 2 -->
    <div class="step-timeline-item">
        <div class="step-number-circle">2</div>
        <div class="step-card-content">
            <h2 class="step-card-title">{{ $step2Title }}</h2>
            <p class="step-card-desc">{{ $step2Desc }} Tim kami akan memvalidasi keabsahan lembaga Anda, mengaktifkan database tenant terisolasi, dan mengirimkan kredensial akun Super Admin melalui email/WhatsApp.</p>
            <ul class="step-features-list">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Verifikasi instan dalam kurun waktu 1x24 jam</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Database & ruang penyimpanan tenant aktif otomatis</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Notifikasi kredensial login akun Super Admin</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Website publik <code>/y/{kode_yayasan}</code> langsung online</li>
            </ul>
        </div>
    </div>

    <!-- Step 3 -->
    <div class="step-timeline-item">
        <div class="step-number-circle">3</div>
        <div class="step-card-content">
            <h2 class="step-card-title">{{ $step3Title }}</h2>
            <p class="step-card-desc">{{ $step3Desc }} Masukkan data unit TK/PAUD, daftarkan akun guru kelas, atur jadwal rombel belajar, dan sesuaikan template rapor sesuai kebijakan yayasan Anda.</p>
            <ul class="step-features-list">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Import massal data siswa & guru via Excel</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Atur hak akses Admin Sekolah & Kepala Sekolah</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Kustomisasi warna & foto banner profil yayasan</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Panduan onboarding interaktif untuk guru</li>
            </ul>
        </div>
    </div>

    <!-- Step 4 -->
    <div class="step-timeline-item">
        <div class="step-number-circle">4</div>
        <div class="step-card-content">
            <h2 class="step-card-title">{{ $step4Title }}</h2>
            <p class="step-card-desc">{{ $step4Desc }} Seluruh civitas sekolah mulai dari guru, staf TU, kepala sekolah, hingga orang tua murid siap menikmati kemudahan sistem administrasi modern dan terpadu.</p>
            <ul class="step-features-list">
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Guru input absensi & jurnal kegiatan tiap hari</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Orang tua akses portal pantau foto & rapor anak</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Pimpinan yayasan pantau laporan analitik realtime</li>
                <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Dukungan support teknis harian via WhatsApp</li>
            </ul>
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
