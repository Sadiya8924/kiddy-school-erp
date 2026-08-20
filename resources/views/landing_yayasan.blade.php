<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $landingContent = $yayasan->landing_content ?? [];
        $template       = $landingContent['template'] ?? 'ocean';
        $siteTitle      = !empty($landingContent['site_title']) ? $landingContent['site_title'] : $yayasan->nama_yayasan;

        // Palette Theme Mapping for Template Selection
        $themeMap = [
            'ceria' => [
                'primary'      => '#EF9F27',
                'primary_dark' => '#7A5310',
                'accent'       => '#F0997B',
                'dark'         => '#3D2804',
                'surface'      => '#FDFBF7',
                'hero_bg'      => 'linear-gradient(135deg, #7A5310 0%, #D4537E 60%, #EF9F27 100%)',
                'badge_bg'     => 'rgba(255,255,255,0.22)',
                'badge_text'   => '#FFFFFF',
                'avatar_bg'    => 'linear-gradient(135deg, #EF9F27, #F0997B)',
                'btn_cta'      => 'linear-gradient(135deg, #F0997B 0%, #D4537E 100%)',
                'btn_cta_text' => '#FFFFFF',
                'pill_bg'      => '#FFF7ED',
                'pill_border'  => '#FFEDD5',
                'footer_bg'    => '#3D2804',
            ],
            'trustworthy' => [
                'primary'      => '#185FA5',
                'primary_dark' => '#0B2545',
                'accent'       => '#60A5FA',
                'dark'         => '#0B2545',
                'surface'      => '#F4F6F9',
                'hero_bg'      => 'linear-gradient(135deg, #0B2545 0%, #134074 60%, #185FA5 100%)',
                'badge_bg'     => 'rgba(255,255,255,0.14)',
                'badge_text'   => '#93C5FD',
                'avatar_bg'    => 'linear-gradient(135deg, #0B2545, #185FA5)',
                'btn_cta'      => 'linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%)',
                'btn_cta_text' => '#FFFFFF',
                'pill_bg'      => '#F0F9FF',
                'pill_border'  => '#E0F2FE',
                'footer_bg'    => '#0B2545',
            ],
            'natural' => [
                'primary'      => '#639922',
                'primary_dark' => '#1C3309',
                'accent'       => '#FAC775',
                'dark'         => '#1C3309',
                'surface'      => '#F6F3EC',
                'hero_bg'      => 'linear-gradient(135deg, #1C3309 0%, #355312 60%, #4D7C0F 100%)',
                'badge_bg'     => 'rgba(255,255,255,0.18)',
                'badge_text'   => '#FEF08A',
                'avatar_bg'    => 'linear-gradient(135deg, #355312, #639922)',
                'btn_cta'      => 'linear-gradient(135deg, #D97706 0%, #B45309 100%)',
                'btn_cta_text' => '#FFFFFF',
                'pill_bg'      => '#F7FEE7',
                'pill_border'  => '#ECFCCB',
                'footer_bg'    => '#142606',
            ],
            'ocean' => [
                'primary'      => '#1E40AF',
                'primary_dark' => '#1E3A8A',
                'accent'       => '#F59E0B',
                'dark'         => '#0F172A',
                'surface'      => '#F8FAFC',
                'hero_bg'      => 'linear-gradient(135deg, #0F172A 0%, #1E3A8A 60%, #1E40AF 100%)',
                'badge_bg'     => 'rgba(255,255,255,0.14)',
                'badge_text'   => '#FDE68A',
                'avatar_bg'    => 'linear-gradient(135deg, #1E40AF, #3B82F6)',
                'btn_cta'      => 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)',
                'btn_cta_text' => '#0F172A',
                'pill_bg'      => '#EFF6FF',
                'pill_border'  => '#DBEAFE',
                'footer_bg'    => '#0F172A',
            ],
        ];

        $theme = $themeMap[$template] ?? $themeMap['ocean'];

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

    <title>{{ $siteTitle }} — Membangun Generasi Cerdas & Berakhlak Mulia</title>
    <meta name="description" content="Selamat datang di website resmi {{ $siteTitle }}. Temukan informasi cabang TK/PAUD, kurikulum unggulan, galeri kegiatan, dan pendaftaran PPDB online.">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap & Iconify --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; scroll-padding-top: 80px; }

        :root {
            --primary:       {{ $theme['primary'] }};
            --primary-dark:  {{ $theme['primary_dark'] }};
            --accent:        {{ $theme['accent'] }};
            --dark:          {{ $theme['dark'] }};
            --surface:       {{ $theme['surface'] }};
            --hero-bg:       {{ $theme['hero_bg'] }};
            --badge-bg:      {{ $theme['badge_bg'] }};
            --badge-text:    {{ $theme['badge_text'] }};
            --avatar-bg:     {{ $theme['avatar_bg'] }};
            --btn-cta:       {{ $theme['btn_cta'] }};
            --btn-cta-text:  {{ $theme['btn_cta_text'] }};
            --pill-bg:       {{ $theme['pill_bg'] }};
            --pill-border:   {{ $theme['pill_border'] }};
            --footer-bg:     {{ $theme['footer_bg'] }};
            --card-radius:   20px;
            --card-bg:       #FFFFFF;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--surface);
            color: #1E293B;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ═══════════════════════════════════════════════════════
           TOP NAVIGATION BAR
        ═══════════════════════════════════════════════════════ */
        .top-nav {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 0 28px;
            height: 70px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 20px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
        }
        .top-nav > .container {
            height: 100%;
            display: flex;
            align-items: center;
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #0F172A;
            font-weight: 800;
            font-size: 16px;
            letter-spacing: -0.025em;
            flex-shrink: 0;
        }
        .brand-logo:hover { color: var(--primary); }
        .brand-logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }
        .brand-logo-text .brand-name { font-size: 15.5px; font-weight: 800; color: #0F172A; }
        .brand-logo-text .brand-tag {
            font-size: 10px;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .brand-avatar {
            width: 42px; height: 42px;
            border-radius: 12px;
            background: var(--avatar-bg);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            font-size: 15px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.18);
            overflow: hidden;
            flex-shrink: 0;
        }
        .brand-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-menu li a {
            text-decoration: none;
            color: #475569;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            transition: color 0.2s, background 0.2s;
            white-space: nowrap;
            display: block;
        }
        .nav-menu li a:hover {
            color: var(--primary);
            background: var(--pill-bg);
        }

        .btn-nav-daftar {
            background: var(--btn-cta);
            color: var(--btn-cta-text) !important;
            font-weight: 800;
            font-size: 13px;
            padding: 9px 20px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.18);
            transition: all 0.25s;
            border: none;
            white-space: nowrap;
            flex-shrink: 0;
            letter-spacing: -0.01em;
        }
        .btn-nav-daftar:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.28);
        }
        .btn-nav-dashboard {
            background: transparent;
            border: 1.5px solid rgba(0,0,0,0.12);
            color: #374151 !important;
            font-weight: 700;
            font-size: 13px;
            padding: 8px 16px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .btn-nav-dashboard:hover {
            background: var(--primary);
            color: #FFFFFF !important;
            border-color: var(--primary);
            transform: translateY(-1px);
        }

        /* ═══════════════════════════════════════════════════════
           HERO SECTION
        ═══════════════════════════════════════════════════════ */
        .hero-section {
            background: var(--hero-bg);
            padding: 80px 24px 88px;
            position: relative;
            overflow: hidden;
            color: #fff;
        }
        /* Decorative dot grid pattern */
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }
        /* Decorative glow blobs */
        .hero-section::after {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 500px; height: 500px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            pointer-events: none;
        }
        .hero-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--badge-bg);
            border: 1px solid rgba(255,255,255,0.24);
            color: var(--badge-text);
            border-radius: 100px;
            padding: 8px 20px;
            font-size: 12.5px;
            font-weight: 700;
            margin-bottom: 22px;
            backdrop-filter: blur(10px);
            letter-spacing: 0.01em;
        }
        .hero-title {
            font-size: clamp(30px, 4.5vw, 52px);
            font-weight: 800;
            color: #FFFFFF;
            line-height: 1.16;
            letter-spacing: -0.035em;
            margin-bottom: 20px;
            text-shadow: 0 2px 20px rgba(0,0,0,0.12);
        }
        .hero-subtitle {
            font-size: 16px;
            color: rgba(255,255,255,0.82);
            line-height: 1.72;
            margin-bottom: 36px;
            max-width: 540px;
            font-weight: 400;
        }
        .hero-buttons {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 0;
        }
        .btn-cta-orange {
            background: var(--btn-cta);
            color: var(--btn-cta-text) !important;
            font-weight: 800;
            font-size: 15px;
            padding: 15px 34px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 28px rgba(0,0,0,0.2);
            transition: all 0.28s cubic-bezier(.4,0,.2,1);
            letter-spacing: -0.01em;
        }
        .btn-cta-orange:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 16px 40px rgba(0,0,0,0.3);
        }
        .btn-cta-outline {
            background: rgba(255,255,255,0.1);
            border: 1.5px solid rgba(255,255,255,0.35);
            color: rgba(255,255,255,0.95) !important;
            font-weight: 600;
            font-size: 15px;
            padding: 14px 28px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.22s;
            backdrop-filter: blur(8px);
        }
        .btn-cta-outline:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.6);
            transform: translateY(-1px);
        }

        /* Hero visual card */
        .hero-visual-card {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            border: 2px solid rgba(255,255,255,0.22);
            box-shadow: 0 24px 64px rgba(0,0,0,0.32);
            max-height: 440px;
        }
        .hero-visual-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            min-height: 380px;
            transition: transform 0.6s ease;
        }
        .hero-visual-card:hover img { transform: scale(1.03); }
        .hero-visual-badge {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(10, 18, 36, 0.88);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,0.16);
            border-radius: 16px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* ═══════════════════════════════════════════════════════
           STATS BAR
        ═══════════════════════════════════════════════════════ */
        .stats-bar {
            background: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.07);
            border: 1px solid #E9EFF6;
            padding: 28px 40px;
            margin-top: -40px;
            position: relative;
            z-index: 10;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            text-align: center;
        }
        .stat-item {
            padding: 4px 16px;
        }
        .stat-item + .stat-item {
            border-left: 1px solid #E9EFF6;
        }
        .stat-item .stat-num {
            font-size: 30px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
            letter-spacing: -0.03em;
        }
        .stat-item .stat-label {
            font-size: 12.5px;
            color: #64748B;
            margin-top: 6px;
            font-weight: 600;
        }

        /* ═══════════════════════════════════════════════════════
           GENERAL SECTION UTILITIES
        ═══════════════════════════════════════════════════════ */
        .section-padding {
            padding: 90px 24px;
        }
        .section-header-center {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 56px;
        }
        .section-pill-tag {
            display: inline-block;
            background: var(--pill-bg);
            color: var(--primary);
            border: 1px solid var(--pill-border);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 5px 16px;
            border-radius: 100px;
            margin-bottom: 14px;
        }
        .section-main-title {
            font-size: clamp(24px, 3vw, 34px);
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.03em;
            margin-bottom: 14px;
            line-height: 1.2;
        }
        .section-subtitle-text {
            font-size: 15.5px;
            color: #64748B;
            line-height: 1.68;
        }

        /* ═══════════════════════════════════════════════════════
           TENTANG & VISI MISI CARDS
        ═══════════════════════════════════════════════════════ */
        .vision-card {
            background: #FFFFFF;
            border: 1px solid #E9EFF6;
            border-radius: 20px;
            padding: 32px 30px;
            height: 100%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            transition: transform 0.25s, border-color 0.25s, box-shadow 0.25s;
        }
        .vision-card:hover {
            transform: translateY(-4px);
            border-color: var(--primary);
            box-shadow: 0 12px 32px rgba(0,0,0,0.07);
        }

        /* ═══════════════════════════════════════════════════════
           SCHOOL BRANCH CARDS
        ═══════════════════════════════════════════════════════ */
        .school-card-item {
            background: #FFFFFF;
            border: 1px solid #E9EFF6;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            transition: all 0.28s cubic-bezier(.4,0,.2,1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .school-card-item:hover {
            transform: translateY(-6px);
            border-color: var(--primary);
            box-shadow: 0 20px 48px rgba(0,0,0,0.1);
        }
        .school-card-banner {
            height: 150px;
            background: var(--avatar-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.4);
            font-size: 52px;
            position: relative;
            overflow: hidden;
        }
        .school-card-banner::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 40%, rgba(0,0,0,0.15) 100%);
        }
        .school-card-content {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .btn-daftar-school {
            background: var(--avatar-bg);
            color: #fff !important;
            border: none;
            border-radius: 14px;
            font-size: 13.5px;
            font-weight: 700;
            padding: 13px;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: auto;
            transition: all 0.22s;
            box-shadow: 0 6px 18px rgba(0,0,0,0.14);
        }
        .btn-daftar-school:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 26px rgba(0,0,0,0.24);
        }

        /* ═══════════════════════════════════════════════════════
           GALLERY GRID
        ═══════════════════════════════════════════════════════ */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }
        .gallery-card {
            background: #FFFFFF;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #E9EFF6;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
            transition: all 0.28s cubic-bezier(.4,0,.2,1);
        }
        .gallery-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 40px rgba(0,0,0,0.1);
            border-color: var(--primary);
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
            transition: transform 0.5s ease;
        }
        .gallery-card:hover .gallery-img-wrapper img {
            transform: scale(1.06);
        }
        .gallery-tag-pill {
            position: absolute;
            top: 14px;
            left: 14px;
            background: rgba(10, 18, 36, 0.78);
            backdrop-filter: blur(8px);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 5px 13px;
            border-radius: 100px;
            letter-spacing: 0.01em;
        }
        .gallery-body {
            padding: 22px 24px;
        }
        .gallery-title {
            font-size: 15.5px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 7px;
            letter-spacing: -0.01em;
        }
        .gallery-desc {
            font-size: 13px;
            color: #64748B;
            line-height: 1.62;
            margin: 0;
        }

        /* ═══════════════════════════════════════════════════════
           KEUNGGULAN GRID
        ═══════════════════════════════════════════════════════ */
        .features-grid-custom {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 22px;
        }
        .feature-item-card {
            background: #FFFFFF;
            border: 1px solid #E9EFF6;
            border-radius: 20px;
            padding: 28px;
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
        }
        .feature-item-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--avatar-bg);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
            border-radius: 0;
        }
        .feature-item-card:hover::before { transform: scaleX(1); }
        .feature-item-card:hover {
            border-color: var(--primary);
            transform: translateY(-4px);
            box-shadow: 0 14px 32px rgba(0,0,0,0.07);
        }

        /* ═══════════════════════════════════════════════════════
           TESTIMONIALS
        ═══════════════════════════════════════════════════════ */
        .testimonial-card-item {
            background: #FFFFFF;
            border: 1px solid #E9EFF6;
            border-radius: 20px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            box-shadow: 0 4px 16px rgba(0,0,0,0.03);
            transition: all 0.25s;
        }
        .testimonial-card-item:hover {
            box-shadow: 0 12px 32px rgba(0,0,0,0.07);
            transform: translateY(-3px);
            border-color: var(--primary);
        }

        /* ═══════════════════════════════════════════════════════
           CTA BOTTOM BANNER
        ═══════════════════════════════════════════════════════ */
        .cta-bottom-banner {
            background: var(--hero-bg);
            border-radius: 28px;
            padding: 64px 48px;
            color: #fff;
            text-align: center;
            box-shadow: 0 24px 60px rgba(0,0,0,0.18);
            position: relative;
            overflow: hidden;
        }
        .cta-bottom-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        /* ═══════════════════════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════════════════════ */
        .site-footer-custom {
            background: var(--footer-bg);
            color: rgba(255,255,255,0.75);
            padding: 56px 24px 32px;
            font-size: 13.5px;
        }
        .site-footer-custom a { transition: color 0.2s; }
        .site-footer-custom a:hover { color: var(--accent) !important; }

        /* ═══════════════════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════════════════ */
        @media (max-width: 1200px) {
            .nav-menu { gap: 2px; }
            .nav-menu li a { font-size: 12.5px; padding: 6px 10px; }
        }
        @media (max-width: 992px) {
            .nav-menu { display: none; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
            .stat-item + .stat-item { border-left: none; }
            .hero-section { text-align: center; padding: 56px 18px 64px; }
            .hero-subtitle { margin-left: auto; margin-right: auto; }
            .hero-buttons { justify-content: center; }
            .brand-logo-text { display: none; }
            .stats-bar { padding: 22px 24px; margin-top: -28px; }
        }
        @media (max-width: 576px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .stat-item .stat-num { font-size: 24px; }
            .stat-item .stat-label { font-size: 11.5px; }
            .top-nav { padding: 0 14px; height: 60px; }
            .cta-bottom-banner { padding: 36px 20px; }
            .btn-nav-daftar, .btn-nav-dashboard { font-size: 12px; padding: 7px 13px; }
            .brand-avatar { width: 36px; height: 36px; font-size: 13px; }
            .section-padding { padding: 52px 16px; }
            .gallery-grid { grid-template-columns: 1fr; }
            .features-grid-custom { grid-template-columns: 1fr; }
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
                    {{ strtoupper(substr($siteTitle, 0, 2)) }}
                @endif
            </div>
            <div class="brand-logo-text">
                <span class="brand-name">{{ $siteTitle }}</span>
                <span class="brand-tag">Lembaga Pendidikan Anak Usia Dini</span>
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

        <div class="d-flex align-items-center gap-2 flex-shrink-0" style="white-space: nowrap !important;">
            <a href="{{ route('yayasan.pendaftaran', $yayasan->kode_yayasan) }}" class="btn-nav-daftar">
                <iconify-icon icon="solar:pen-new-square-bold" style="font-size:16px;"></iconify-icon>
                Daftar PPDB Online
            </a>
            @auth
                <a href="/dashboard" class="btn-nav-dashboard">
                    <iconify-icon icon="solar:widget-6-bold-duotone" style="font-size:16px;"></iconify-icon> Dashboard
                </a>
            @else
                <a href="{{ route('yayasan.login', $yayasan->kode_yayasan) }}" class="btn-nav-dashboard">
                    <iconify-icon icon="solar:login-2-bold-duotone" style="font-size:16px;"></iconify-icon> Login
                </a>
            @endauth
            <!-- Mobile Offcanvas Menu Toggler -->
            <button class="btn border-0 text-dark d-lg-none p-1 ms-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavMenu" aria-label="Toggle Navigation">
                <iconify-icon icon="solar:hamburger-menu-bold" style="font-size: 26px; color: var(--primary);"></iconify-icon>
            </button>
        </div>
    </div>
</nav>

<!-- ── Mobile Offcanvas Navigation Drawer ──────────── -->
<div class="offcanvas offcanvas-end rounded-start-4" tabindex="-1" id="mobileNavMenu" aria-labelledby="mobileNavMenuLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold text-dark d-flex align-items-center gap-2" id="mobileNavMenuLabel" style="font-size: 16px;">
            <div class="brand-avatar" style="width:32px; height:32px; font-size:13px;">
                {{ strtoupper(substr($siteTitle, 0, 2)) }}
            </div>
            <span>Menu Navigasi</span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-4">
        <ul class="list-unstyled d-flex flex-column gap-3 mb-4 font-semibold" style="font-size: 14.5px;">
            <li><a href="#hero" data-bs-dismiss="offcanvas" class="text-dark text-decoration-none d-flex align-items-center gap-2.5"><iconify-icon icon="solar:home-2-bold-duotone" style="color:var(--primary);" class="fs-5"></iconify-icon> Beranda</a></li>
            <li><a href="#tentang" data-bs-dismiss="offcanvas" class="text-dark text-decoration-none d-flex align-items-center gap-2.5"><iconify-icon icon="solar:info-circle-bold-duotone" style="color:var(--primary);" class="fs-5"></iconify-icon> Tentang Kami</a></li>
            <li><a href="#cabang" data-bs-dismiss="offcanvas" class="text-dark text-decoration-none d-flex align-items-center gap-2.5"><iconify-icon icon="solar:buildings-bold-duotone" style="color:var(--primary);" class="fs-5"></iconify-icon> Cabang TK / PAUD</a></li>
            <li><a href="#galeri" data-bs-dismiss="offcanvas" class="text-dark text-decoration-none d-flex align-items-center gap-2.5"><iconify-icon icon="solar:gallery-bold-duotone" style="color:var(--primary);" class="fs-5"></iconify-icon> Galeri Kegiatan</a></li>
            <li><a href="#keunggulan" data-bs-dismiss="offcanvas" class="text-dark text-decoration-none d-flex align-items-center gap-2.5"><iconify-icon icon="solar:star-bold-duotone" style="color:var(--primary);" class="fs-5"></iconify-icon> Keunggulan</a></li>
            <li><a href="#testimoni" data-bs-dismiss="offcanvas" class="text-dark text-decoration-none d-flex align-items-center gap-2.5"><iconify-icon icon="solar:chat-round-like-bold-duotone" style="color:var(--primary);" class="fs-5"></iconify-icon> Testimoni</a></li>
        </ul>
        <hr class="my-3 opacity-25">
        <div class="d-grid gap-2">
            <a href="{{ route('yayasan.pendaftaran', $yayasan->kode_yayasan) }}" class="btn-nav-daftar justify-content-center py-2.5">
                <iconify-icon icon="solar:pen-new-square-bold" style="font-size:16px;"></iconify-icon>
                Daftar PPDB Online
            </a>
            @auth
                <a href="/dashboard" class="btn-nav-dashboard justify-content-center py-2.5">
                    <iconify-icon icon="solar:widget-6-bold-duotone" style="font-size:16px;"></iconify-icon> Dashboard Saya
                </a>
            @else
                <a href="{{ route('yayasan.login', $yayasan->kode_yayasan) }}" class="btn-nav-dashboard justify-content-center py-2.5">
                    <iconify-icon icon="solar:login-2-bold-duotone" style="font-size:16px;"></iconify-icon> Login Portal
                </a>
            @endauth
        </div>
    </div>
</div>

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
                    @if(!empty($content['hero_image']))
                        <img src="{{ asset($content['hero_image']) }}" alt="Belajar Ceria di {{ $yayasan->nama_yayasan }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=900&auto=format&fit=crop&q=80" alt="Belajar Ceria di {{ $yayasan->nama_yayasan }}">
                    @endif

                    <div class="hero-visual-badge">
                        <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width:42px;height:42px;font-size:18px;background:var(--btn-cta);">
                            <iconify-icon icon="solar:medal-star-bold-duotone" style="color:var(--btn-cta-text);"></iconify-icon>
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
                <div class="stat-num">{{ $total_siswa > 0 ? $total_siswa : '300+' }}</div>
                <div class="stat-label">Siswa Aktif</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">{{ $jumlahPengajar }}+</div>
                <div class="stat-label">Tenaga Pendidik Profesional</div>
            </div>
            <div class="stat-item">
                <div class="stat-num" style="color:#10B981;">Akreditasi {{ $akreditasi }}</div>
                <div class="stat-label">BAN PAUD &amp; PNF</div>
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
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width:48px;height:48px;background:var(--pill-bg);border:1.5px solid var(--pill-border);">
                            <iconify-icon icon="solar:target-bold-duotone" style="font-size:22px;color:var(--primary);"></iconify-icon>
                        </div>
                        <div>
                            <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--primary);margin-bottom:3px;">Visi Kami</div>
                            <h4 class="fw-bold mb-0" style="font-size:17px; color:#0F172A;">Visi Lembaga</h4>
                        </div>
                    </div>
                    <p class="text-muted" style="font-size:14.5px;line-height:1.75;">
                        "Menjadi lembaga pendidikan anak usia dini terkemuka yang unggul dalam membentuk generasi beriman, berakhlak mulia, cerdas, kreatif, dan mandiri berwawasan global."
                    </p>
                </div>
            </div>

            <!-- Misi Card -->
            <div class="col-md-6">
                <div class="vision-card">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width:48px;height:48px;background:#f0fdf4;border:1.5px solid #dcfce7;">
                            <iconify-icon icon="solar:rocket-bold-duotone" style="font-size:22px;color:#16a34a;"></iconify-icon>
                        </div>
                        <div>
                            <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#16a34a;margin-bottom:3px;">Misi Utama</div>
                            <h4 class="fw-bold mb-0" style="font-size:17px; color:#0F172A;">Misi Lembaga</h4>
                        </div>
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
                        <iconify-icon icon="solar:buildings-3-bold-duotone" style="font-size:52px;opacity:.35;"></iconify-icon>
                        <span class="badge position-absolute top-0 end-0 m-3 px-3 py-1.5 rounded-pill fw-bold" style="font-size:11px;background:var(--btn-cta);color:var(--btn-cta-text);z-index:2;">
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
            <div class="col-12 text-center py-5">
                <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-4" style="width:80px;height:80px;background:var(--pill-bg);border:2px solid var(--pill-border);">
                    <iconify-icon icon="solar:buildings-3-bold-duotone" style="font-size:36px;color:var(--primary);"></iconify-icon>
                </div>
                <h5 class="fw-bold text-dark" style="font-size:18px;">Belum ada data cabang sekolah aktif.</h5>
                <p class="text-muted small mt-2">Cabang sekolah akan tampil di sini setelah ditambahkan oleh administrator.</p>
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
                    <span class="gallery-tag-pill">Kreativitas &amp; Motorik</span>
                </div>
                <div class="gallery-body">
                    <h3 class="gallery-title">Eksplorasi Belajar &amp; Sensorik</h3>
                    <p class="gallery-desc">Mewarnai, meronce, menyusun balok, dan melatih motorik halus dengan metode bermain bermakna.</p>
                </div>
            </div>

            <!-- Galeri 2 -->
            <div class="gallery-card">
                <div class="gallery-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?w=700&auto=format&fit=crop&q=80" alt="Tahfidz & Doa Harian">
                    <span class="gallery-tag-pill">Karakter &amp; Keagamaan</span>
                </div>
                <div class="gallery-body">
                    <h3 class="gallery-title">Tahfidz Cilik &amp; Pembiasaan Doa</h3>
                    <p class="gallery-desc">Hafalan surat-surat pendek juz 30, doa harian, dan pengenalan adab sopan santun sejak dini.</p>
                </div>
            </div>

            <!-- Galeri 3 -->
            <div class="gallery-card">
                <div class="gallery-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?w=700&auto=format&fit=crop&q=80" alt="Ekstrakurikuler Drumband & Seni">
                    <span class="gallery-tag-pill">Ekstrakurikuler</span>
                </div>
                <div class="gallery-body">
                    <h3 class="gallery-title">Minat Bakat &amp; Drumband Cilik</h3>
                    <p class="gallery-desc">Melatih kekompakan, rasa percaya diri, dan kepekaan musikalitas anak melalui ekskul pilihan.</p>
                </div>
            </div>

            <!-- Galeri 4 -->
            <div class="gallery-card">
                <div class="gallery-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1596464716127-f2a829822301?w=700&auto=format&fit=crop&q=80" alt="Playground & Outbound">
                    <span class="gallery-tag-pill">Area Bermain Outbound</span>
                </div>
                <div class="gallery-body">
                    <h3 class="gallery-title">Taman Bermain Aman &amp; Asri</h3>
                    <p class="gallery-desc">Fasilitas playground indoor &amp; outdoor yang aman untuk melatih ketangkasan serta motorik kasar.</p>
                </div>
            </div>

            <!-- Galeri 5 -->
            <div class="gallery-card">
                <div class="gallery-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=700&auto=format&fit=crop&q=80" alt="Field Trip & Pentas Seni">
                    <span class="gallery-tag-pill">Pentas Seni &amp; Field Trip</span>
                </div>
                <div class="gallery-body">
                    <h3 class="gallery-title">Panggung Apresiasi &amp; Wisata Edukasi</h3>
                    <p class="gallery-desc">Kunjungan profesi edukatif serta panggung tahunan untuk melatih keberanian anak tampil di depan publik.</p>
                </div>
            </div>

            <!-- Galeri 6 -->
            <div class="gallery-card">
                <div class="gallery-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=700&auto=format&fit=crop&q=80" alt="Eksperimen Sains Sederhana">
                    <span class="gallery-tag-pill">Sains &amp; Eksplorasi</span>
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
                <div class="d-flex align-items-center justify-content-center rounded-3 mb-4" style="width:48px;height:48px;background:linear-gradient(135deg,#2563EB,#3B82F6);">
                    <iconify-icon icon="solar:user-check-bold-duotone" style="font-size:22px;color:#fff;"></iconify-icon>
                </div>
                <h4 class="fw-bold mb-2" style="font-size:16px; color:#0F172A;">Guru Profesional &amp; Penuh Kasih</h4>
                <p class="text-muted mb-0" style="font-size:13.5px;line-height:1.65;">Pendidik berkualifikasi S1 PAUD/PGTK yang sabar, telaten, dan memahami tahapan psikologi perkembangan anak.</p>
            </div>

            <div class="feature-item-card">
                <div class="d-flex align-items-center justify-content-center rounded-3 mb-4" style="width:48px;height:48px;background:linear-gradient(135deg,#10B981,#34D399);">
                    <iconify-icon icon="solar:book-bold-duotone" style="font-size:22px;color:#fff;"></iconify-icon>
                </div>
                <h4 class="fw-bold mb-2" style="font-size:16px; color:#0F172A;">Kurikulum Merdeka &amp; Adab</h4>
                <p class="text-muted mb-0" style="font-size:13.5px;line-height:1.65;">Memadukan standar nasional Kurikulum Merdeka PAUD dengan pembiasaan adab islami dan kemandirian sehari-hari.</p>
            </div>

            <div class="feature-item-card">
                <div class="d-flex align-items-center justify-content-center rounded-3 mb-4" style="width:48px;height:48px;background:linear-gradient(135deg,#F59E0B,#FBBF24);">
                    <iconify-icon icon="solar:smartphone-bold-duotone" style="font-size:22px;color:#fff;"></iconify-icon>
                </div>
                <h4 class="fw-bold mb-2" style="font-size:16px; color:#0F172A;">Portal Digital Orang Tua</h4>
                <p class="text-muted mb-0" style="font-size:13.5px;line-height:1.65;">Pantau absensi harian, foto dokumentasi jurnal kelas, dan lembar e-rapor anak secara mudah lewat smartphone.</p>
            </div>

            <div class="feature-item-card">
                <div class="d-flex align-items-center justify-content-center rounded-3 mb-4" style="width:48px;height:48px;background:linear-gradient(135deg,#EF4444,#F87171);">
                    <iconify-icon icon="solar:chart-square-bold-duotone" style="font-size:22px;color:#fff;"></iconify-icon>
                </div>
                <h4 class="fw-bold mb-2" style="font-size:16px; color:#0F172A;">Monitoring Fisik &amp; Medis Berkala</h4>
                <p class="text-muted mb-0" style="font-size:13.5px;line-height:1.65;">Pemeriksaan tinggi badan, berat badan, lingkar kepala, dan kesehatan gigi standar KMS Dapodik berkala.</p>
            </div>

            <div class="feature-item-card">
                <div class="d-flex align-items-center justify-content-center rounded-3 mb-4" style="width:48px;height:48px;background:linear-gradient(135deg,#8B5CF6,#A78BFA);">
                    <iconify-icon icon="solar:shield-check-bold-duotone" style="font-size:22px;color:#fff;"></iconify-icon>
                </div>
                <h4 class="fw-bold mb-2" style="font-size:16px; color:#0F172A;">Lingkungan Asri &amp; Ramah Anak</h4>
                <p class="text-muted mb-0" style="font-size:13.5px;line-height:1.65;">Ruang kelas ber-AC, pencahayaan alami, alat peraga edukasi non-toxic, serta keamanan sekolah terpantau ketat.</p>
            </div>

            <div class="feature-item-card">
                <div class="d-flex align-items-center justify-content-center rounded-3 mb-4" style="width:48px;height:48px;background:linear-gradient(135deg,#06B6D4,#22D3EE);">
                    <iconify-icon icon="solar:hand-heart-bold-duotone" style="font-size:22px;color:#fff;"></iconify-icon>
                </div>
                <h4 class="fw-bold mb-2" style="font-size:16px; color:#0F172A;">Kemitraan Erat dengan Orang Tua</h4>
                <p class="text-muted mb-0" style="font-size:13.5px;line-height:1.65;">Laporan harian, sesi konsultasi berkala, dan pelibatan orang tua dalam kegiatan parenting serta pentas kreasi.</p>
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
                        <div class="d-flex gap-0.5 mb-3">
                            @for($i = 0; $i < 5; $i++)
                                <iconify-icon icon="solar:star-bold" style="font-size:16px;color:#F59E0B;"></iconify-icon>
                            @endfor
                        </div>
                        <p class="text-muted fst-italic mb-4" style="font-size:13.5px;line-height:1.75;">
                            "Anak saya dulu pemalu sekali, tapi setelah 3 bulan di sini jadi sangat percaya diri, ceria, dan sudah hafal banyak doa pendek. Gurunya sangat telaten dan hangat."
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:42px;height:42px;font-size:14px;background:var(--pill-bg);border:1.5px solid var(--pill-border);color:var(--primary);">
                            DR
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size:13.5px;">Bunda Dwi Rahayu</div>
                            <div class="text-muted" style="font-size:11.5px;">Wali Murid TK A</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="testimonial-card-item">
                    <div>
                        <div class="d-flex gap-0.5 mb-3">
                            @for($i = 0; $i < 5; $i++)
                                <iconify-icon icon="solar:star-bold" style="font-size:16px;color:#F59E0B;"></iconify-icon>
                            @endfor
                        </div>
                        <p class="text-muted fst-italic mb-4" style="font-size:13.5px;line-height:1.75;">
                            "Fasilitas playground-nya bersih dan aman. Program tahfidz dan pembiasaan adab makannya sangat terasa di rumah. Sangat bersyukur memilih yayasan ini."
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:42px;height:42px;font-size:14px;background:#f0fdf4;border:1.5px solid #dcfce7;color:#16a34a;">
                            AP
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size:13.5px;">Bapak Andi Prasetyo</div>
                            <div class="text-muted" style="font-size:11.5px;">Wali Murid TK B</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="testimonial-card-item">
                    <div>
                        <div class="d-flex gap-0.5 mb-3">
                            @for($i = 0; $i < 5; $i++)
                                <iconify-icon icon="solar:star-bold" style="font-size:16px;color:#F59E0B;"></iconify-icon>
                            @endfor
                        </div>
                        <p class="text-muted fst-italic mb-4" style="font-size:13.5px;line-height:1.75;">
                            "Portal orang tuanya canggih! Tiap sore bisa lihat foto kegiatan harian anak di kelas, dan e-rapornya sangat detail menggambarkan perkembangan motorik anak."
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:42px;height:42px;font-size:14px;background:#fffbeb;border:1.5px solid #fef3c7;color:#d97706;">
                            SA
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size:13.5px;">Bunda Siti Aminah</div>
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
        <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-4" style="width:60px;height:60px;background:rgba(255,255,255,0.15);">
            <iconify-icon icon="solar:pen-new-square-bold-duotone" style="font-size:28px;color:#fff;"></iconify-icon>
        </div>
        <h3 class="fw-bold mb-3" style="font-size:clamp(22px,3.5vw,32px);letter-spacing:-0.025em;">Daftarkan Putra-Putri Anda Sekarang!</h3>
        <p class="mb-5 mx-auto" style="max-width:560px; font-size:15px;color:rgba(255,255,255,.75);line-height:1.68;">
            Amankan kuota tempat duduk di cabang sekolah kami. Proses pendaftaran mudah dan cepat secara online.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('yayasan.pendaftaran', $yayasan->kode_yayasan) }}" class="btn-cta-orange">
                <iconify-icon icon="solar:pen-new-square-bold" style="font-size:18px;"></iconify-icon>
                Buka Formulir PPDB Online
            </a>
            @if($whatsappCta)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappCta) }}" target="_blank" class="btn-cta-outline">
                <iconify-icon icon="solar:chat-round-dots-bold" style="font-size:18px;"></iconify-icon>
                Konsultasi via WhatsApp
            </a>
            @endif
        </div>
    </div>
</div>

<!-- ── Footer ─────────────────────────────────────── -->
<footer class="site-footer-custom" id="footer">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="brand-avatar" style="background:rgba(255,255,255,.12);font-size:14px;font-weight:800;color:#fff;">
                        @if($yayasan->logo_yayasan)
                            <img src="{{ asset('storage/' . $yayasan->logo_yayasan) }}" alt="Logo">
                        @else
                            {{ strtoupper(substr($siteTitle, 0, 2)) }}
                        @endif
                    </div>
                    <div>
                        <div class="fw-bold text-white" style="font-size:15px;">{{ $siteTitle }}</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.5);font-weight:600;text-transform:uppercase;letter-spacing:.06em;">Lembaga Pendidikan Anak Usia Dini</div>
                    </div>
                </div>
                <p style="font-size:13.5px;color:rgba(255,255,255,.55);line-height:1.72;max-width:420px;margin-bottom:20px;">Lembaga Pendidikan Anak Usia Dini (TK, PAUD, Kelompok Bermain) yang berdedikasi membangun masa depan cerah generasi bangsa.</p>
                @if($alamatTampil)
                <div class="d-flex align-items-start gap-2 mb-2" style="font-size:13px;color:rgba(255,255,255,.55);">
                    <iconify-icon icon="solar:map-point-bold" style="color:var(--accent);font-size:15px;margin-top:2px;flex-shrink:0;"></iconify-icon>
                    <span>{{ $alamatTampil }}</span>
                </div>
                @endif
                @if($yayasan->telepon)
                <div class="d-flex align-items-center gap-2" style="font-size:13px;color:rgba(255,255,255,.55);">
                    <iconify-icon icon="solar:phone-bold" style="color:var(--accent);font-size:15px;"></iconify-icon>
                    <span>{{ $yayasan->telepon }}</span>
                </div>
                @endif
            </div>

            <div class="col-lg-3 offset-lg-3">
                <h6 class="fw-bold mb-4" style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);">Tautan Cepat</h6>
                <ul class="list-unstyled d-flex flex-column gap-3">
                    <li><a href="#tentang" class="text-decoration-none" style="font-size:13.5px;color:rgba(255,255,255,.6);font-weight:500;">Profil Yayasan</a></li>
                    <li><a href="#cabang" class="text-decoration-none" style="font-size:13.5px;color:rgba(255,255,255,.6);font-weight:500;">Cabang Sekolah</a></li>
                    <li><a href="#galeri" class="text-decoration-none" style="font-size:13.5px;color:rgba(255,255,255,.6);font-weight:500;">Galeri Kegiatan</a></li>
                    <li><a href="{{ route('yayasan.pendaftaran', $yayasan->kode_yayasan) }}" class="text-decoration-none fw-bold" style="font-size:13.5px;color:var(--accent);">Daftar PPDB Online</a></li>
                    @auth
                        <li><a href="/dashboard" class="text-decoration-none fw-bold d-flex align-items-center gap-1.5" style="font-size:13.5px;color:var(--accent);"><iconify-icon icon="solar:widget-6-bold-duotone" style="font-size:15px;"></iconify-icon>Kembali ke Dashboard</a></li>
                    @else
                        <li><a href="{{ route('yayasan.login', $yayasan->kode_yayasan) }}" class="text-decoration-none" style="font-size:13.5px;color:rgba(255,255,255,.6);font-weight:500;">Login Portal Guru &amp; Wali</a></li>
                    @endauth
                </ul>
            </div>
        </div>

        <div class="pt-5 text-center" style="border-top:1px solid rgba(255,255,255,0.1);">
            <p class="mb-0" style="font-size:12.5px;color:rgba(255,255,255,.4);">© {{ date('Y') }} {{ $siteTitle }}. Didukung oleh <a href="/" class="text-decoration-none fw-bold" style="color:var(--accent);">Kiddy School ERP</a>.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
