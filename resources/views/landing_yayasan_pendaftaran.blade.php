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
                'primary_dark' => '#1E3A8A',
                'accent'       => '#F59E0B',
                'surface'      => '#F8FAFC',
                'hero_bg'      => 'linear-gradient(135deg, #0F172A 0%, #1E3A8A 60%, #1E40AF 100%)',
                'avatar_bg'    => 'linear-gradient(135deg, #1E40AF, #3B82F6)',
                'btn_cta'      => 'linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%)',
                'btn_cta_text' => '#FFFFFF',
                'pill_bg'      => '#EFF6FF',
                'pill_border'  => '#DBEAFE',
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
                'pill_bg'      => '#FEF3E2',
                'pill_border'  => '#FCD7A1',
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
                'pill_bg'      => '#EBF3FC',
                'pill_border'  => '#BDD8F5',
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
                'pill_bg'      => '#F0F7E6',
                'pill_border'  => '#CEE5AE',
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
                'pill_bg'      => '#FFF1F2',
                'pill_border'  => '#FFE4E6',
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
                'pill_bg'      => '#ECFDF5',
                'pill_border'  => '#D1FAE5',
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
                'pill_bg'      => '#F3E8FF',
                'pill_border'  => '#E9D5FF',
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
                'pill_bg'      => '#F1F5F9',
                'pill_border'  => '#E2E8F0',
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
                'pill_bg'      => '#FEF3C7',
                'pill_border'  => '#FDE68A',
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
                'pill_bg'      => '#CCFBF1',
                'pill_border'  => '#99F6E4',
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
                'pill_bg'      => '#FFE4E6',
                'pill_border'  => '#FECDD3',
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
                'pill_bg'      => '#1E293B',
                'pill_border'  => '#334155',
            ],
        ];

        $theme = $themeMap[$template] ?? $themeMap['ocean'];
    @endphp

    <title>Pendaftaran PPDB & Registrasi Akun — {{ $siteTitle }}</title>
    <meta name="description" content="Pendaftaran siswa baru (PPDB) dan pembuatan akun portal wali murid untuk {{ $siteTitle }}.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & Iconify -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>

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
            --pill-bg:       {{ $theme['pill_bg'] }};
            --pill-border:   {{ $theme['pill_border'] }};
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F8FAFC;
            color: #1E293B;
            min-height: 100vh;
            margin: 0;
        }

        /* ── Top Bar ────────────────────────────────── */
        .pendaftaran-topbar {
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
        .btn-back-yayasan {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            padding: 7px 16px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-back-yayasan:hover {
            background: var(--pill-bg);
            border-color: var(--pill-border);
            color: var(--primary);
        }
        .brand-yayasan {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #0F172A;
            font-weight: 800;
            font-size: 15.5px;
        }
        .brand-avatar-mini {
            width: 34px; height: 34px;
            border-radius: 10px;
            background: var(--avatar-bg);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            font-size: 14px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .brand-avatar-mini img {
            width: 100%; height: 100%; object-fit: cover;
        }

        /* ── Main Layout ────────────────────────────── */
        .portal-wrapper {
            padding: 40px 20px 80px;
            max-width: 1140px;
            margin: 0 auto;
        }

        .header-box {
            background: var(--hero-bg);
            border-radius: 24px;
            padding: 40px 36px;
            color: #fff;
            margin-bottom: 36px;
            box-shadow: 0 12px 36px rgba(0,0,0,0.12);
            position: relative;
            overflow: hidden;
        }
        .header-box::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
        }
        .header-box h1 {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 2;
        }
        .header-box p {
            font-size: 14.5px;
            color: rgba(255,255,255,0.85);
            margin: 0;
            max-width: 680px;
            line-height: 1.6;
            position: relative;
            z-index: 2;
        }

        .card-custom {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 22px;
            padding: 32px 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .section-badge-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 5px 14px;
            border-radius: 100px;
            margin-bottom: 16px;
        }

        .school-choice-item {
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 16px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .school-choice-item:hover {
            background: var(--pill-bg);
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        }

        .doc-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            margin-bottom: 8px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
        }
        .form-control {
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 14px;
            background: #F8FAFC;
            transition: all 0.2s;
        }
        .form-control:focus {
            background: #FFFFFF;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0,0,0,0.06);
        }
        .btn-register-parent {
            background: var(--btn-cta);
            color: var(--btn-cta-text) !important;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-size: 14.5px;
            font-weight: 700;
            width: 100%;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
            transition: all 0.2s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-register-parent:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(0,0,0,0.25);
            color: #fff;
        }

        .auth-toggle-pill {
            background: #F1F5F9;
            border: 1.5px solid #E2E8F0;
            padding: 4px;
            border-radius: 14px;
            display: flex;
            gap: 4px;
        }
        .btn-toggle-tab {
            flex: 1;
            border: none;
            background: transparent;
            padding: 9px 14px;
            font-size: 13.5px;
            font-weight: 700;
            color: #64748B;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
        }
        .btn-toggle-tab.active {
            background: #FFFFFF;
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .btn-toggle-tab:hover:not(.active) {
            color: #1E293B;
        }
        .auth-form-panel {
            animation: fadeIn 0.25s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-form-action {
            background: var(--btn-cta);
            color: var(--btn-cta-text) !important;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 16px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border: none;
            transition: all 0.2s;
        }
        .btn-form-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        @media (max-width: 768px) {
            .portal-wrapper { padding: 20px 12px 60px; }
            .header-box { padding: 24px 18px; border-radius: 18px; text-align: center; }
            .header-box p { font-size: 13.5px; }
            .card-custom { padding: 20px 16px; border-radius: 16px; }
            .pendaftaran-topbar { padding: 10px 14px; flex-wrap: wrap; gap: 8px; justify-content: center; }
            .btn-back-yayasan { font-size: 11.5px; padding: 6px 12px; }
            .brand-yayasan { font-size: 13.5px; }
        }
    </style>
</head>
<body>

<!-- Topbar Navigation -->
<div class="pendaftaran-topbar">
    <a href="{{ route('yayasan.landing', $yayasan->kode_yayasan) }}" class="btn-back-yayasan">
        <iconify-icon icon="solar:arrow-left-bold-duotone" style="font-size:16px;"></iconify-icon>
        Kembali ke Profil Yayasan
    </a>

    <a href="{{ route('yayasan.landing', $yayasan->kode_yayasan) }}" class="brand-yayasan">
        <div class="brand-avatar-mini">
            {{ strtoupper(substr($yayasan->nama_yayasan, 0, 2)) }}
        </div>
        <span class="d-none d-sm-inline">{{ $yayasan->nama_yayasan }}</span>
    </a>

    <div class="d-flex align-items-center gap-2">
        @auth
            <a href="/dashboard" class="btn btn-sm rounded-pill px-3 fw-bold d-inline-flex align-items-center gap-1" style="font-size:12.5px; border: 1.5px solid var(--primary); color: var(--primary);">
                <iconify-icon icon="solar:widget-6-bold-duotone"></iconify-icon> Kembali ke Dashboard
            </a>
        @else
            <a href="{{ route('yayasan.login', $yayasan->kode_yayasan) }}" class="btn btn-sm rounded-pill px-3 fw-bold d-inline-flex align-items-center gap-1" style="font-size:12.5px; border: 1.5px solid var(--primary); color: var(--primary);">
                <iconify-icon icon="solar:login-2-bold-duotone"></iconify-icon> Login Portal
            </a>
        @endauth
        <span class="badge rounded-pill px-3 py-1.5 fw-bold d-none d-md-inline-block" style="font-size:11.5px; background: var(--pill-bg); color: var(--primary); border: 1px solid var(--pill-border);">
            PPDB Online {{ date('Y') }}/{{ date('Y')+1 }}
        </span>
    </div>
</div>

<!-- Main Portal Container -->
<main class="portal-wrapper">
    <!-- Header Banner -->
    <div class="header-box">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge font-bold px-3 py-1 rounded-pill" style="font-size:11px; background: var(--accent); color: #0F172A;">
                PORTAL PENDAFTARAN RESMI
            </span>
        </div>
        <h1>Pendaftaran Siswa Baru & Akun Orang Tua</h1>
        <p>
            Selamat datang di portal pendaftaran <strong>{{ $siteTitle }}</strong>. Silakan pilih pendaftaran siswa baru (PPDB) atau buat akun wali murid untuk memantau aktivitas dan e-rapor anak.
        </p>
    </div>

    <!-- Dual Action Grid -->
    <div class="row g-4 mb-5">
        <!-- Kolom 1: Formulir Pendaftaran Siswa (PPDB Online) -->
        <div class="col-lg-6">
            <div class="card-custom">
                <span class="section-badge-tag" style="background: var(--pill-bg); color: var(--primary); border: 1px solid var(--pill-border);">
                    <iconify-icon icon="solar:diploma-bold-duotone"></iconify-icon>
                    Pendaftaran Siswa (PPDB)
                </span>

                <h3 class="fw-bold mb-2" style="font-size:20px; color:#0F172A;">Daftarkan Putra / Putri Anda</h3>
                <p class="text-muted small mb-4">
                    Pilih cabang TK atau PAUD tujuan di bawah <strong>{{ $siteTitle }}</strong> untuk langsung mengisi formulir pendaftaran siswa baru secara online.
                </p>

                <div class="mb-4">
                    <div class="text-muted small fw-bold text-uppercase mb-2" style="font-size:11px; letter-spacing:0.05em;">
                        Pilih Cabang Sekolah Tujuan:
                    </div>

                    @forelse($schools as $school)
                    <a href="{{ route('yayasan.pendaftaran.form', ['kode_yayasan' => $yayasan->encrypted_token, 'school_id' => $school->id]) }}#form" class="school-choice-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-3 bg-white border" style="width:42px;height:42px;color:var(--primary);flex-shrink:0;">
                                <iconify-icon icon="solar:buildings-3-bold-duotone" style="font-size:22px;"></iconify-icon>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size:14.5px;">{{ $school->nama_sekolah }}</div>
                                <div class="text-muted" style="font-size:12px;">{{ Str::limit($school->alamat, 40) }}</div>
                            </div>
                        </div>
                        <div class="btn-form-action">
                            Isi Form <iconify-icon icon="solar:arrow-right-bold"></iconify-icon>
                        </div>
                    </a>
                    @empty
                    <div class="p-3 text-center text-muted small bg-light rounded-3">
                        Belum ada cabang sekolah aktif.
                    </div>
                    @endforelse
                </div>

                <div class="mt-auto pt-3 border-top">
                    <div class="fw-bold text-dark small mb-2 d-flex align-items-center gap-1.5">
                        <iconify-icon icon="solar:document-text-bold-duotone" style="color:var(--primary);font-size:18px;"></iconify-icon>
                        Dokumen yang Perlu Disiapkan:
                    </div>
                    <div class="doc-item">
                        <iconify-icon icon="solar:file-check-bold-duotone" style="color:var(--primary);font-size:20px;flex-shrink:0;margin-top:2px;"></iconify-icon>
                        <div class="small text-muted"><strong>Kartu Keluarga &amp; Akta Kelahiran</strong> (Foto / Scan asli).</div>
                    </div>
                    <div class="doc-item">
                        <iconify-icon icon="solar:user-circle-bold-duotone" style="color:var(--primary);font-size:20px;flex-shrink:0;margin-top:2px;"></iconify-icon>
                        <div class="small text-muted"><strong>Pas Foto Anak</strong> (Ukuran 3x4 / setengah badan).</div>
                    </div>
                    <div class="doc-item">
                        <iconify-icon icon="solar:card-2-bold-duotone" style="color:var(--primary);font-size:20px;flex-shrink:0;margin-top:2px;"></iconify-icon>
                        <div class="small text-muted"><strong>Bukti Pembayaran Pendaftaran</strong> (Struk / Transfer bank).</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom 2: Registrasi & Login Akun Wali Murid (In-Place Switcher) -->
        <div class="col-lg-6">
            <div class="card-custom">
                @auth
                <span class="section-badge-tag" style="background: var(--pill-bg); color: var(--primary); border: 1px solid var(--pill-border);">
                    <iconify-icon icon="solar:user-bold-duotone"></iconify-icon>
                    Akun Portal Wali Murid
                </span>

                <div class="text-center py-5 bg-light rounded-4 mt-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:60px;height:60px;background:var(--pill-bg);color:var(--primary);font-size:28px;">
                        <iconify-icon icon="solar:user-check-bold-duotone"></iconify-icon>
                    </div>
                    <h5 class="fw-bold mb-1" style="color:#0F172A;">Halo, {{ Auth::user()->name }}!</h5>
                    <p class="text-muted small mb-3">Anda sudah masuk ke portal dengan akun terdaftar.</p>
                    <a href="/dashboard" class="btn-register-parent d-inline-flex align-items-center gap-2" style="width:auto;padding:10px 24px;">
                        Buka Dashboard Portal →
                    </a>
                </div>
                @else

                <!-- Interactive Switcher Tabs -->
                <div class="auth-toggle-pill mb-4">
                    <button type="button" class="btn-toggle-tab active" id="tabBtnRegister" onclick="switchAuthTab('register')">
                        <iconify-icon icon="solar:user-plus-bold"></iconify-icon>
                        Buat Akun Baru
                    </button>
                    <button type="button" class="btn-toggle-tab" id="tabBtnLogin" onclick="switchAuthTab('login')">
                        <iconify-icon icon="solar:login-2-bold"></iconify-icon>
                        Masuk Portal
                    </button>
                </div>

                @if(session('success'))
                <div class="alert alert-success border-success-subtle bg-success-subtle text-success rounded-3 p-3 mb-3 small d-flex gap-2 align-items-start">
                    <iconify-icon icon="solar:check-circle-bold" class="fs-5 shrink-0 mt-0.5"></iconify-icon>
                    <div>{{ session('success') }}</div>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger rounded-3 p-3 mb-3 small d-flex gap-2 align-items-start">
                    <iconify-icon icon="solar:danger-triangle-bold" class="fs-5 shrink-0 mt-0.5"></iconify-icon>
                    <div>{{ session('error') }}</div>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger rounded-3 p-3 mb-3 small">
                    <strong>⚠️ Perhatian:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <!-- PANEL 1: REGISTER FORM -->
                <div id="panelRegister" class="auth-form-panel">
                    <h3 class="fw-bold mb-2" style="font-size:20px; color:#0F172A;">Buat Akun Wali Murid</h3>
                    <p class="text-muted small mb-4">
                        Buat akun portal orang tua untuk memantau nilai e-rapor, absensi harian, dan dokumentasi kegiatan belajar anak di <strong>{{ $yayasan->nama_yayasan }}</strong>.
                    </p>

                    <form action="{{ route('yayasan.register', $yayasan->kode_yayasan) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap Orang Tua / Wali <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Ayah Budi / Bunda Siti" required value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Email Aktif <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="email@anda.com" required value="{{ old('email') }}">
                            <div class="form-text" style="font-size:11.5px;">Gunakan email yang sama saat mendaftarkan putra/putri Anda.</div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="form-label">Kata Sandi <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required minlength="8">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Konfirmasi Sandi <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi sandi" required>
                            </div>
                        </div>

                        <button type="submit" class="btn-register-parent">
                            <iconify-icon icon="solar:user-plus-bold" style="font-size:18px;"></iconify-icon>
                            Buat Akun Wali Murid Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-3 small text-muted">
                        Sudah punya akun? <a href="javascript:void(0)" onclick="switchAuthTab('login')" class="fw-bold text-decoration-none" style="color:var(--primary);">Masuk ke Portal Langsung</a>
                    </div>
                </div>

                <!-- PANEL 2: LOGIN FORM (IN-PLACE) -->
                <div id="panelLogin" class="auth-form-panel" style="display: none;">
                    <h3 class="fw-bold mb-2" style="font-size:20px; color:#0F172A;">Masuk ke Portal</h3>
                    <p class="text-muted small mb-4">
                        Masuk ke akun portal wali murid <strong>{{ $siteTitle }}</strong> untuk memantau perkembangan ananda.
                    </p>

                    <form action="{{ url('/login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Alamat Email Terdaftar <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="email@anda.com" required value="{{ old('email') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kata Sandi <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan kata sandi Anda" required>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                <label class="form-check-label small text-muted" for="rememberMe">
                                    Ingat Saya
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn-register-parent">
                            <iconify-icon icon="solar:login-2-bold" style="font-size:18px;"></iconify-icon>
                            Masuk ke Portal Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-3 small text-muted">
                        Belum punya akun? <a href="javascript:void(0)" onclick="switchAuthTab('register')" class="fw-bold text-decoration-none" style="color:var(--primary);">Daftar Akun Baru</a>
                    </div>
                </div>

                @endauth
            </div>
        </div>
    </div>

    <!-- Bantuan WhatsApp -->
    @if($yayasan->telepon)
    <div class="p-4 rounded-4 bg-white border d-flex align-items-center justify-content-between flex-wrap gap-3" style="border-color: #E2E8F0 !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-circle text-white" style="width:46px;height:46px;font-size:24px;flex-shrink:0;background:#22C55E;">
                <iconify-icon icon="simple-icons:whatsapp"></iconify-icon>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark" style="font-size:16px;">Butuh Bantuan Pendaftaran PPDB?</h5>
                <p class="text-muted small mb-0">Hubungi tim administrasi <strong>{{ $siteTitle }}</strong> via WhatsApp.</p>
            </div>
        </div>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $yayasan->telepon) }}" target="_blank" class="btn btn-success rounded-pill px-4 fw-bold d-inline-flex align-items-center gap-2">
            <iconify-icon icon="simple-icons:whatsapp"></iconify-icon>
            Chat WhatsApp
        </a>
    </div>
    @endif
</main>

<footer class="text-center py-4 border-top bg-white text-muted small">
    © {{ date('Y') }} {{ $siteTitle }}. Didukung oleh Kiddy School ERP.
</footer>

<script>
    function switchAuthTab(tab) {
        const tabBtnRegister = document.getElementById('tabBtnRegister');
        const tabBtnLogin = document.getElementById('tabBtnLogin');
        const panelRegister = document.getElementById('panelRegister');
        const panelLogin = document.getElementById('panelLogin');

        if (!tabBtnRegister || !tabBtnLogin || !panelRegister || !panelLogin) return;

        if (tab === 'login') {
            tabBtnRegister.classList.remove('active');
            tabBtnLogin.classList.add('active');
            panelRegister.style.display = 'none';
            panelLogin.style.display = 'block';
            window.location.hash = 'login';
        } else {
            tabBtnLogin.classList.remove('active');
            tabBtnRegister.classList.add('active');
            panelLogin.style.display = 'none';
            panelRegister.style.display = 'block';
            window.location.hash = 'register';
        }
    }

    // Cek jika URL memiliki hash #login atau jika ada error saat login
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash === '#login' || @json(session()->has('error') && !errors()->any())) {
            switchAuthTab('login');
        }
    });
</script>
</body>
</html>
