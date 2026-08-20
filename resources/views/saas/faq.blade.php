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
    <title>Pusat Bantuan & FAQ — {{ $platformName }}</title>
    <meta name="description" content="Temukan jawaban lengkap seputar cara pendaftaran, implementasi e-rapor Kurikulum Merdeka, portal orang tua, dan manajemen multi-cabang di Kiddy School ERP.">

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
            display: flex; align-items: center; gap: 6px;
            list-style: none; margin: 0; padding: 0;
        }
        .nav-links a {
            color: #475569; text-decoration: none; font-size: 14px;
            font-weight: 600; padding: 8px 16px; border-radius: 8px;
            transition: all 0.2s ease;
        }
        .nav-links a:hover { color: #2563EB; background: #EFF6FF; }
        .nav-links a.active { color: #2563EB; background: #EFF6FF; font-weight: 700; }

        .btn-header-login {
            background: #F1F5F9; color: #334155; font-weight: 700;
            font-size: 13.5px; padding: 9px 18px; border-radius: 10px;
            text-decoration: none; border: 1px solid #E2E8F0;
            display: inline-flex; align-items: center; gap: 7px; transition: all 0.2s;
        }
        .btn-header-login:hover { background: #E2E8F0; color: #0F172A; }
        .btn-header-cta {
            background: #2563EB; color: #fff; font-weight: 700;
            font-size: 13.5px; padding: 9px 20px; border-radius: 10px;
            text-decoration: none; display: inline-flex; align-items: center; gap: 7px;
            box-shadow: 0 4px 14px rgba(37,99,235,0.35); transition: all 0.2s;
        }
        .btn-header-cta:hover {
            background: #1D4ED8; color: #fff; transform: translateY(-1px);
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
            letter-spacing: -0.03em; margin-bottom: 16px; line-height: 1.2;
        }
        .page-subtitle {
            font-size: 16px; color: #94A3B8;
            max-width: 680px; margin: 0 auto; line-height: 1.7;
        }

        /* ── FAQ Section ────────────────────────────── */
        .faq-page-content {
            padding: 80px 24px;
            max-width: 860px;
            margin: 0 auto;
        }
        .faq-accordion {
            display: flex; flex-direction: column; gap: 14px;
        }
        .faq-item {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 18px;
            overflow: hidden;
            transition: all 0.25s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        .faq-item.active {
            border-color: #93C5FD;
            box-shadow: 0 8px 24px rgba(37,99,235,0.08);
        }
        .faq-question {
            padding: 22px 28px;
            font-size: 16px;
            font-weight: 700;
            color: #1E293B;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            user-select: none;
            transition: color 0.2s;
        }
        .faq-item.active .faq-question {
            color: #1D4ED8;
        }
        .faq-question iconify-icon {
            font-size: 22px;
            color: #94A3B8;
            transition: transform 0.3s cubic-bezier(0.16,1,0.3,1), color 0.2s;
            flex-shrink: 0;
        }
        .faq-item.active .faq-question iconify-icon {
            transform: rotate(180deg);
            color: #2563EB;
        }
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s cubic-bezier(0.16,1,0.3,1), padding 0.35s;
            padding: 0 28px;
            color: #475569;
            font-size: 14.5px;
            line-height: 1.75;
        }
        .faq-item.active .faq-answer {
            max-height: 400px;
            padding: 0 28px 24px;
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
            max-width: 720px; margin: 0 auto; position: relative; z-index: 2;
        }
        .cta-banner-title {
            font-size: 38px; font-weight: 800; letter-spacing: -0.03em;
            margin-bottom: 16px; line-height: 1.25;
        }
        .cta-banner-desc {
            font-size: 16.5px; color: #BFDBFE; margin-bottom: 36px; line-height: 1.7;
        }
        .btn-cta-large {
            display: inline-flex; align-items: center; gap: 10px;
            background: #FFFFFF; color: #1D4ED8; font-weight: 800;
            font-size: 16px; padding: 16px 36px; border-radius: 14px;
            text-decoration: none; box-shadow: 0 12px 32px rgba(0,0,0,0.25);
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
            line-height: 1.78; font-size: 13px; color: #475569; max-width: 290px;
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
        }
        @media (max-width: 640px) {
            .page-title { font-size: 28px; }
            .footer-grid { grid-template-columns: 1fr; }
            .faq-question { padding: 18px 20px; font-size: 15px; }
            .faq-item.active .faq-answer { padding: 0 20px 20px; }
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
        <li><a href="{{ route('saas.faq') }}" class="active">FAQ</a></li>
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
                Login Owner / Platform
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
        <iconify-icon icon="solar:question-circle-bold"></iconify-icon>
        Pusat Bantuan & Tanya Jawab
    </div>
    <h1 class="page-title">Pertanyaan yang Sering Diajukan</h1>
    <p class="page-subtitle">Segala hal yang perlu Anda ketahui tentang implementasi, fitur, keamanan, dan pengelolaan sistem Kiddy School ERP.</p>
</header>

<!-- FAQ Accordion Section -->
<main class="faq-page-content">

    <div class="faq-accordion">
        <div class="faq-item active">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>Apa itu sistem Multi-Tenant di Kiddy School ERP?</span>
                <iconify-icon icon="solar:alt-arrow-down-bold"></iconify-icon>
            </div>
            <div class="faq-answer">
                Multi-tenant adalah arsitektur di mana satu platform cloud melayani banyak yayasan secara mandiri. Setiap yayasan memiliki ruang data, pengaturan, dan website profil sendiri yang terisolasi 100% dari yayasan lain.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>Apakah format e-rapor sudah sesuai Kurikulum Merdeka?</span>
                <iconify-icon icon="solar:alt-arrow-down-bold"></iconify-icon>
            </div>
            <div class="faq-answer">
                Ya, format e-rapor kami telah disesuaikan dengan standar penilaian Fase Fondasi PAUD/TK Kurikulum Merdeka Kemendikbudristek, lengkap dengan narasi elemen capaian pembelajaran, lampiran dokumentasi foto karya anak, dan rekam pertumbuhan fisik.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>Apakah bisa mengelola lebih dari 1 cabang sekolah TK / PAUD?</span>
                <iconify-icon icon="solar:alt-arrow-down-bold"></iconify-icon>
            </div>
            <div class="faq-answer">
                Tentu bisa. Yayasan dapat menambahkan unit cabang TK, PAUD, atau Kelompok Bermain di bawah naungan yayasan Anda. Masing-masing cabang memiliki admin, guru, rombel kelas, dan daftar siswanya sendiri dalam satu sentralisasi dasbor yayasan.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>Bagaimana cara orang tua / wali murid mengakses sistem?</span>
                <iconify-icon icon="solar:alt-arrow-down-bold"></iconify-icon>
            </div>
            <div class="faq-answer">
                Orang tua cukup membuka tautan website publik yayasan Anda (<code>/y/{kode_yayasan}</code>), lalu mendaftar akun wali murid. Akun orang tua otomatis terhubung ke yayasan Anda dan dapat langsung melihat pengumuman, absensi, jurnal foto harian, dan e-rapor anak melalui smartphone.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>Apakah ada panduan penggunaan atau pelatihan untuk guru?</span>
                <iconify-icon icon="solar:alt-arrow-down-bold"></iconify-icon>
            </div>
            <div class="faq-answer">
                Ya, kami menyediakan video tutorial lengkap, buku petunjuk praktis format PDF, serta sesi onboarding daring untuk mendampingi tim admin dan guru yayasan Anda agar dapat mengoperasikan sistem dengan lancar sejak hari pertama.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>Bagaimana jika yayasan kami ingin memindahkan data lama?</span>
                <iconify-icon icon="solar:alt-arrow-down-bold"></iconify-icon>
            </div>
            <div class="faq-answer">
                Kami menyediakan fitur import massal menggunakan template Excel standar untuk data siswa, data guru, dan rombel kelas. Tim support kami juga siap membantu proses migrasi data yayasan Anda secara aman.
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

<script>
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
