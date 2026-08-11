<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $yayasan->nama_yayasan }} – Selamat Datang</title>
    <meta name="description" content="Halaman resmi {{ $yayasan->nama_yayasan }}. Temukan informasi sekolah, pendaftaran siswa, dan program unggulan kami.">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap & Iconify --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>

    <style>
        :root {
            --primary:   #2563eb;
            --primary-dark: #1d4ed8;
            --accent:    #f59e0b;
            --dark:      #0f172a;
            --surface:   #f8faff;
            --card-radius: 20px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--surface);
            color: var(--dark);
            overflow-x: hidden;
        }

        /* ── HERO ─────────────────────────────────────────── */
        .hero {
            position: relative;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 55%, #2563eb 100%);
            min-height: 80vh;
            display: flex;
            align-items: center;
            overflow: hidden;
            padding: 80px 0 100px;
        }

        /* Decorative blobs */
        .hero::before,
        .hero::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .25;
        }
        .hero::before {
            width: 500px; height: 500px;
            background: #60a5fa;
            top: -120px; right: -100px;
        }
        .hero::after {
            width: 380px; height: 380px;
            background: #f59e0b;
            bottom: -100px; left: -80px;
        }

        .hero-logo {
            width: 88px; height: 88px;
            object-fit: contain;
            border-radius: 20px;
            background: #fff;
            padding: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,.25);
        }
        .hero-logo-placeholder {
            width: 88px; height: 88px;
            border-radius: 20px;
            background: rgba(255,255,255,.15);
            border: 2px solid rgba(255,255,255,.3);
            display: flex; align-items: center; justify-content: center;
            font-size: 36px; color: #fff;
            box-shadow: 0 8px 32px rgba(0,0,0,.25);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.25);
            backdrop-filter: blur(10px);
            color: #bfdbfe;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 100px;
            margin-bottom: 16px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .hero h1 {
            font-size: clamp(28px, 5vw, 48px);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -.02em;
        }
        .hero p.lead {
            color: rgba(255,255,255,.75);
            font-size: 16px;
            max-width: 520px;
        }

        .btn-hero-primary {
            background: var(--accent);
            color: #0f172a;
            border: none;
            font-weight: 700;
            font-size: 14px;
            padding: 14px 28px;
            border-radius: 14px;
            transition: all .2s;
            box-shadow: 0 4px 20px rgba(245,158,11,.4);
        }
        .btn-hero-primary:hover {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(245,158,11,.45);
        }

        .btn-hero-outline {
            background: rgba(255,255,255,.1);
            color: #fff;
            border: 1.5px solid rgba(255,255,255,.35);
            font-weight: 600;
            font-size: 14px;
            padding: 13px 24px;
            border-radius: 14px;
            transition: all .2s;
            backdrop-filter: blur(10px);
        }
        .btn-hero-outline:hover {
            background: rgba(255,255,255,.2);
            color: #fff;
            transform: translateY(-2px);
        }

        /* Stats strip */
        .stats-strip {
            background: #fff;
            border-radius: var(--card-radius);
            box-shadow: 0 4px 30px rgba(15,23,42,.08);
            padding: 28px 36px;
            margin-top: -48px;
            position: relative;
            z-index: 10;
        }
        .stat-item {
            text-align: center;
        }
        .stat-item .stat-num {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
        }
        .stat-item .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        /* ── SECTION TITLES ───────────────────────────────── */
        .section-tag {
            display: inline-block;
            background: #eff6ff;
            color: var(--primary);
            font-size: 11.5px;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 100px;
            text-transform: uppercase;
            letter-spacing: .07em;
            margin-bottom: 10px;
        }
        .section-title {
            font-size: clamp(22px, 3.5vw, 32px);
            font-weight: 800;
            color: var(--dark);
            line-height: 1.25;
            letter-spacing: -.02em;
        }

        /* ── SCHOOL CARDS ─────────────────────────────────── */
        .school-card {
            border-radius: var(--card-radius);
            border: 1.5px solid #e2e8f0;
            background: #fff;
            overflow: hidden;
            transition: transform .25s, box-shadow .25s;
            height: 100%;
        }
        .school-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(15,23,42,.1);
        }
        .school-card-accent {
            height: 5px;
            background: linear-gradient(90deg, var(--primary), #60a5fa);
        }
        .school-card-body {
            padding: 24px;
        }

        .school-logo-wrap {
            width: 56px; height: 56px;
            border-radius: 14px;
            overflow: hidden;
            background: #eff6ff;
            border: 1.5px solid #dbeafe;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .school-logo-wrap img { width: 100%; height: 100%; object-fit: contain; }
        .school-logo-placeholder { color: #2563eb; font-size: 26px; }

        .school-name {
            font-size: 16px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 4px;
        }
        .school-meta {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }

        .info-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 12px;
            color: #475569;
            font-weight: 500;
        }
        .info-chip iconify-icon { color: var(--primary); font-size: 14px; }

        .btn-daftar {
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all .2s;
            box-shadow: 0 4px 14px rgba(37,99,235,.3);
        }
        .btn-daftar:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(37,99,235,.4);
        }

        /* ── WHY US ───────────────────────────────────────── */
        .feature-card {
            background: #fff;
            border-radius: 18px;
            border: 1.5px solid #e2e8f0;
            padding: 28px 24px;
            text-align: center;
            transition: transform .2s, box-shadow .2s;
        }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(15,23,42,.08); }
        .feature-icon {
            width: 56px; height: 56px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            font-size: 26px;
        }

        /* ── CONTACT / CTA ────────────────────────────────── */
        .cta-section {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            border-radius: 28px;
            padding: 60px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(96,165,250,.15);
            top: -80px; right: -60px;
            filter: blur(50px);
        }

        /* ── NAVBAR ───────────────────────────────────────── */
        .top-nav {
            position: absolute;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 18px 0;
        }

        /* ── FOOTER ───────────────────────────────────────── */
        .site-footer {
            background: var(--dark);
            color: rgba(255,255,255,.6);
            font-size: 13px;
            padding: 32px 0;
            text-align: center;
        }
        .site-footer a { color: #93c5fd; text-decoration: none; }

        /* animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp .6s ease both; }
        .fade-up-d1 { animation-delay: .1s; }
        .fade-up-d2 { animation-delay: .2s; }
        .fade-up-d3 { animation-delay: .3s; }
        .fade-up-d4 { animation-delay: .4s; }

        .divider {
            width: 48px; height: 4px;
            border-radius: 100px;
            background: linear-gradient(90deg, var(--primary), #60a5fa);
            margin: 12px 0 20px;
        }
    </style>
</head>
<body>

    {{-- ── TOP NAV ──────────────────────────────────────────────── --}}
    <nav class="top-nav">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    @if($yayasan->logo_yayasan)
                        <img src="{{ asset('storage/' . $yayasan->logo_yayasan) }}" alt="Logo" style="width:36px;height:36px;object-fit:contain;border-radius:8px;background:#fff;padding:3px;">
                    @endif
                    <span class="text-white fw-bold" style="font-size:15px;">{{ $yayasan->nama_yayasan }}</span>
                </div>
                <a href="{{ route('login') }}" class="btn-hero-outline text-decoration-none" style="padding:9px 18px;font-size:13px;">
                    <iconify-icon icon="solar:login-bold-duotone" class="align-middle me-1"></iconify-icon> Login
                </a>
            </div>
        </div>
    </nav>

    {{-- ── HERO ─────────────────────────────────────────────────── --}}
    <section class="hero">
        <div class="container position-relative" style="z-index:2;">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">

                    {{-- Logo & badge --}}
                    <div class="d-flex align-items-center gap-3 mb-4 fade-up">
                        @if($yayasan->logo_yayasan)
                            <img src="{{ asset('storage/' . $yayasan->logo_yayasan) }}" alt="Logo {{ $yayasan->nama_yayasan }}" class="hero-logo">
                        @else
                            <div class="hero-logo-placeholder">
                                <iconify-icon icon="solar:book-bold-duotone"></iconify-icon>
                            </div>
                        @endif
                        <div>
                            <div class="hero-badge">
                                <iconify-icon icon="solar:verified-check-bold-duotone"></iconify-icon>
                                Yayasan Resmi Terdaftar
                            </div>
                            <div class="text-white-50" style="font-size:13px;font-weight:500;">{{ $yayasan->kode_yayasan }}</div>
                        </div>
                    </div>

                    <h1 class="fade-up fade-up-d1">
                        Selamat Datang di<br>
                        <span style="color:#fbbf24;">{{ $yayasan->nama_yayasan }}</span>
                    </h1>

                    <p class="lead mt-3 mb-4 fade-up fade-up-d2">
                        Memberikan pendidikan anak usia dini yang berkualitas, menyenangkan, dan berlandaskan nilai-nilai akhlak mulia.
                    </p>

                    <div class="d-flex flex-wrap gap-3 fade-up fade-up-d3">
                        @if($schools->isNotEmpty())
                            <a href="#sekolah" class="btn-hero-primary text-decoration-none">
                                <iconify-icon icon="solar:buildings-bold-duotone" class="align-middle me-1"></iconify-icon>
                                Lihat Sekolah Kami
                            </a>
                        @endif
                        <a href="{{ route('pendaftaran') }}" class="btn-hero-outline text-decoration-none">
                            <iconify-icon icon="solar:pen-bold-duotone" class="align-middle me-1"></iconify-icon>
                            Daftar Sekarang
                        </a>
                    </div>

                    {{-- Info chips --}}
                    @if($yayasan->penanggung_jawab || $yayasan->telepon || $yayasan->alamat)
                    <div class="d-flex flex-wrap gap-2 mt-4 fade-up fade-up-d4">
                        @if($yayasan->penanggung_jawab)
                            <div class="info-chip" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.8);">
                                <iconify-icon icon="solar:user-bold-duotone" style="color:#93c5fd;"></iconify-icon>
                                {{ $yayasan->penanggung_jawab }}
                            </div>
                        @endif
                        @if($yayasan->telepon)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $yayasan->telepon) }}" target="_blank" class="info-chip text-decoration-none" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.8);">
                                <iconify-icon icon="solar:phone-bold-duotone" style="color:#93c5fd;"></iconify-icon>
                                {{ $yayasan->telepon }}
                            </a>
                        @endif
                        @if($yayasan->alamat)
                            <div class="info-chip" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.8);">
                                <iconify-icon icon="solar:map-point-bold-duotone" style="color:#93c5fd;"></iconify-icon>
                                {{ \Str::limit($yayasan->alamat, 50) }}
                            </div>
                        @endif
                    </div>
                    @endif

                </div>

                {{-- Floating illustration / stats card --}}
                <div class="col-lg-5 d-none d-lg-flex justify-content-end fade-up fade-up-d2">
                    <div style="background:rgba(255,255,255,.08);border:1.5px solid rgba(255,255,255,.18);backdrop-filter:blur(16px);border-radius:24px;padding:28px;width:320px;">
                        <p class="text-white-50 mb-3" style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;">Ringkasan Yayasan</p>
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:44px;height:44px;border-radius:12px;background:rgba(96,165,250,.2);display:flex;align-items:center;justify-content:center;font-size:22px;">
                                    <iconify-icon icon="solar:buildings-bold-duotone" style="color:#93c5fd;"></iconify-icon>
                                </div>
                                <div>
                                    <div class="text-white fw-bold" style="font-size:22px;line-height:1;">{{ $total_sekolah }}</div>
                                    <div class="text-white-50" style="font-size:12px;">Sekolah Aktif</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:44px;height:44px;border-radius:12px;background:rgba(251,191,36,.2);display:flex;align-items:center;justify-content:center;font-size:22px;">
                                    <iconify-icon icon="solar:users-group-rounded-bold-duotone" style="color:#fbbf24;"></iconify-icon>
                                </div>
                                <div>
                                    <div class="text-white fw-bold" style="font-size:22px;line-height:1;">{{ $total_siswa }}</div>
                                    <div class="text-white-50" style="font-size:12px;">Total Siswa Aktif</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:44px;height:44px;border-radius:12px;background:rgba(52,211,153,.2);display:flex;align-items:center;justify-content:center;font-size:22px;">
                                    <iconify-icon icon="solar:diploma-bold-duotone" style="color:#6ee7b7;"></iconify-icon>
                                </div>
                                <div>
                                    <div class="text-white fw-bold" style="font-size:16px;line-height:1;">TK & PAUD</div>
                                    <div class="text-white-50" style="font-size:12px;">Jenjang Tersedia</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── MAIN CONTENT ─────────────────────────────────────────── --}}
    <main>
        <div class="container">

            {{-- ── STATS STRIP ──────────────────────────────────── --}}
            <div class="stats-strip fade-up" style="display:flex;gap:0;justify-content:space-around;flex-wrap:wrap;">
                <div class="stat-item px-3 py-2">
                    <div class="stat-num">{{ $total_sekolah }}</div>
                    <div class="stat-label">Sekolah</div>
                </div>
                <div class="stat-item px-3 py-2" style="border-left:1.5px solid #e2e8f0;">
                    <div class="stat-num">{{ $total_siswa }}</div>
                    <div class="stat-label">Siswa Aktif</div>
                </div>
                <div class="stat-item px-3 py-2" style="border-left:1.5px solid #e2e8f0;">
                    <div class="stat-num">3</div>
                    <div class="stat-label">Jenjang</div>
                </div>
                <div class="stat-item px-3 py-2" style="border-left:1.5px solid #e2e8f0;">
                    <div class="stat-num">{{ date('Y') - 2010 }}+</div>
                    <div class="stat-label">Tahun Berdiri</div>
                </div>
            </div>

            {{-- ── SEKOLAH ───────────────────────────────────────── --}}
            @if($schools->isNotEmpty())
            <section id="sekolah" class="py-5 mt-4">
                <div class="text-center mb-5">
                    <div class="section-tag">
                        <iconify-icon icon="solar:buildings-bold-duotone" class="align-middle me-1"></iconify-icon>
                        Sekolah Kami
                    </div>
                    <h2 class="section-title">Unit Sekolah di Bawah<br>{{ $yayasan->nama_yayasan }}</h2>
                    <div class="divider mx-auto"></div>
                    <p class="text-muted" style="font-size:15px;max-width:500px;margin:0 auto;">
                        Pilih sekolah yang sesuai untuk mendaftarkan putra-putri Anda.
                    </p>
                </div>

                <div class="row g-4">
                    @foreach($schools as $school)
                        <div class="col-md-6 col-lg-4">
                            <div class="school-card">
                                <div class="school-card-accent"></div>
                                <div class="school-card-body">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="school-logo-wrap">
                                            @if($school->logo_sekolah)
                                                <img src="{{ asset('storage/' . $school->logo_sekolah) }}" alt="Logo {{ $school->nama_sekolah }}">
                                            @else
                                                <iconify-icon icon="solar:buildings-bold-duotone" class="school-logo-placeholder"></iconify-icon>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="school-name">{{ $school->nama_sekolah }}</div>
                                            @if($school->npsn)
                                                <div class="school-meta">NPSN: {{ $school->npsn }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <span class="info-chip">
                                            <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                                            {{ $school->jumlah_siswa }} Siswa
                                        </span>
                                        @if($school->kepala_sekolah)
                                            <span class="info-chip">
                                                <iconify-icon icon="solar:user-bold-duotone"></iconify-icon>
                                                {{ \Str::limit($school->kepala_sekolah, 20) }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($school->alamat)
                                        <div class="d-flex align-items-start gap-2 mb-4 text-muted" style="font-size:12.5px;">
                                            <iconify-icon icon="solar:map-point-bold-duotone" class="text-primary mt-1 flex-shrink-0" style="font-size:15px;"></iconify-icon>
                                            <span>{{ \Str::limit($school->alamat, 80) }}</span>
                                        </div>
                                    @else
                                        <div class="mb-4"></div>
                                    @endif

                                    <a href="{{ route('pendaftaran') }}" class="btn-daftar w-100 justify-content-center">
                                        <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon>
                                        Daftar ke Sekolah Ini
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- ── KEUNGGULAN ────────────────────────────────────── --}}
            <section class="py-5">
                <div class="text-center mb-5">
                    <div class="section-tag">Keunggulan</div>
                    <h2 class="section-title">Mengapa Memilih Kami?</h2>
                    <div class="divider mx-auto"></div>
                </div>
                <div class="row g-4">
                    @php
                        $features = [
                            ['icon' => 'solar:diploma-bold-duotone',       'bg' => '#eff6ff', 'color' => '#2563eb', 'title' => 'Kurikulum Terstruktur',   'desc' => 'Mengikuti standar kurikulum Merdeka Belajar yang dirancang sesuai tahap perkembangan anak.'],
                            ['icon' => 'solar:shield-user-bold-duotone',   'bg' => '#fef9ec', 'color' => '#d97706', 'title' => 'Lingkungan Aman & Nyaman', 'desc' => 'Lingkungan belajar yang aman, bersih, dan kondusif untuk tumbuh kembang optimal.'],
                            ['icon' => 'solar:chart-bold-duotone',         'bg' => '#f0fdf4', 'color' => '#16a34a', 'title' => 'Laporan Perkembangan',     'desc' => 'Orang tua dapat memantau perkembangan anak secara real-time via portal digital.'],
                            ['icon' => 'solar:users-group-rounded-bold-duotone', 'bg' => '#fdf4ff', 'color' => '#9333ea', 'title' => 'Guru Profesional', 'desc' => 'Tenaga pengajar berpengalaman dan bersertifikat di bidang pendidikan anak usia dini.'],
                        ];
                    @endphp
                    @foreach($features as $f)
                        <div class="col-md-6 col-lg-3">
                            <div class="feature-card">
                                <div class="feature-icon" style="background:{{ $f['bg'] }};">
                                    <iconify-icon icon="{{ $f['icon'] }}" style="color:{{ $f['color'] }};"></iconify-icon>
                                </div>
                                <h6 style="font-weight:700;font-size:15px;color:#0f172a;margin-bottom:8px;">{{ $f['title'] }}</h6>
                                <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0;">{{ $f['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- ── CTA ───────────────────────────────────────────── --}}
            <section class="mb-5">
                <div class="cta-section">
                    <div class="position-relative" style="z-index:2;">
                        <div class="section-tag" style="background:rgba(255,255,255,.15);color:#bfdbfe;border:1px solid rgba(255,255,255,.2);">Bergabung Sekarang</div>
                        <h2 class="text-white mt-3 mb-3" style="font-size:clamp(22px,3.5vw,36px);font-weight:800;letter-spacing:-.02em;">
                            Daftarkan Putra-Putri Anda<br>Hari Ini!
                        </h2>
                        <p class="mb-4" style="color:rgba(255,255,255,.7);font-size:15px;max-width:480px;margin-left:auto;margin-right:auto;">
                            Proses pendaftaran mudah, cepat, dan dapat dilakukan secara online. Kami siap menyambut anak Anda!
                        </p>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="{{ route('pendaftaran') }}" class="btn-hero-primary text-decoration-none">
                                <iconify-icon icon="solar:pen-bold-duotone" class="align-middle me-1"></iconify-icon>
                                Formulir Pendaftaran
                            </a>
                            @if($yayasan->telepon)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $yayasan->telepon) }}?text=Assalamualaikum, saya ingin bertanya tentang pendaftaran di {{ urlencode($yayasan->nama_yayasan) }}" target="_blank" class="btn-hero-outline text-decoration-none">
                                    <iconify-icon icon="solar:chat-round-bold-duotone" class="align-middle me-1"></iconify-icon>
                                    Hubungi via WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    {{-- ── FOOTER ───────────────────────────────────────────────── --}}
    <footer class="site-footer">
        <div class="container">
            <div class="mb-2">
                <strong style="color:rgba(255,255,255,.85);">{{ $yayasan->nama_yayasan }}</strong>
            </div>
            @if($yayasan->alamat)
                <div class="mb-2">{{ $yayasan->alamat }}</div>
            @endif
            <div>
                © {{ date('Y') }} {{ $yayasan->nama_yayasan }}. Didukung oleh
                <a href="/">Kiddy School ERP</a>.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Smooth scroll --}}
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
            });
        });
    </script>
</body>
</html>
