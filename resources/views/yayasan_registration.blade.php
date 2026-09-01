<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Formulir Pendaftaran Yayasan Baru — Kiddy School ERP</title>
    <meta name="description" content="Daftarkan yayasan TK/PAUD Anda ke platform Kiddy School ERP. Kelola cabang, PPDB, dan SPP dalam satu pintu.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & Leaflet & Iconify -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F8FAFC;
            background-image: 
                radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(99, 102, 241, 0.06) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(224, 231, 255, 0.4) 0px, transparent 50%);
            background-attachment: fixed;
            color: #1E293B;
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        /* ── Top Bar ────────────────────────────────── */
        .registration-topbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
        }
        .btn-back-home {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 9px;
            padding: 6px 14px;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-back-home:hover {
            background: #EFF6FF;
            border-color: #BFDBFE;
            color: #1D4ED8;
            transform: translateY(-1px);
        }
        .brand-center {
            display: flex;
            align-items: center;
            gap: 9px;
            text-decoration: none;
            color: #0F172A;
            font-weight: 800;
            font-size: 15px;
            letter-spacing: -0.01em;
        }
        .brand-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
        }
        .step-pill {
            font-size: 12px;
            font-weight: 600;
            color: #2563EB;
            background: #EFF6FF;
            border: 1px solid #DBEAFE;
            border-radius: 100px;
            padding: 4px 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* ── Main Layout (Wide Two-Column) ─────────── */
        .form-wrapper {
            flex: 1;
            padding: 24px 20px 48px;
            max-width: 1160px;
            margin: 0 auto;
            width: 100%;
        }

        /* ── Left Sticky Overview Column ───────────── */
        .info-sidebar {
            position: sticky;
            top: 76px;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #EEF2FF;
            border: 1px solid #E0E7FF;
            color: #4F46E5;
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 11px;
            border-radius: 999px;
            margin-bottom: 12px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .hero-title {
            font-size: 24px;
            font-weight: 800;
            line-height: 1.25;
            color: #0F172A;
            letter-spacing: -0.02em;
            margin-bottom: 10px;
        }
        .hero-desc {
            font-size: 13.5px;
            color: #64748B;
            line-height: 1.55;
            margin-bottom: 20px;
        }

        .perks-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }
        .perk-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 12px 14px;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02);
            transition: all 0.2s ease;
        }
        .perk-item:hover {
            border-color: #CBD5E1;
            transform: translateX(3px);
        }
        .perk-icon-wrap {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #EFF6FF;
            color: #2563EB;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .perk-text h4 {
            font-size: 13px;
            font-weight: 700;
            color: #1E293B;
            margin: 0 0 2px;
        }
        .perk-text p {
            font-size: 11.5px;
            color: #64748B;
            margin: 0;
            line-height: 1.4;
        }

        .help-support-card {
            background: linear-gradient(145deg, #1E293B, #0F172A);
            border-radius: 14px;
            padding: 16px;
            color: #F8FAFC;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .help-support-card iconify-icon {
            font-size: 28px;
            color: #38BDF8;
            flex-shrink: 0;
        }
        .help-support-card h5 {
            font-size: 12.5px;
            font-weight: 700;
            margin: 0 0 2px;
            color: #FFFFFF;
        }
        .help-support-card p {
            font-size: 11.5px;
            color: #94A3B8;
            margin: 0;
        }

        /* ── Right Form Card ────────────────────────── */
        .form-card {
            background: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0 10px 30px -8px rgba(15, 23, 42, 0.07), 0 0 0 1px rgba(226, 232, 240, 0.8);
            overflow: hidden;
        }
        .form-card-header {
            background: linear-gradient(135deg, #1E40AF 0%, #2563EB 60%, #3B82F6 100%);
            padding: 20px 28px;
            color: #fff;
            position: relative;
        }
        .form-card-header::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; width: 40%;
            background: radial-gradient(circle at right center, rgba(255,255,255,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .form-card-header h1 {
            font-size: 18px;
            font-weight: 800;
            margin: 0 0 4px;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-card-header p {
            font-size: 12.5px;
            color: #DBEAFE;
            margin: 0;
            line-height: 1.5;
        }

        .form-card-body {
            padding: 24px 28px 28px;
        }

        /* ── Section Title ─────────────────────────── */
        .section-form-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #1E40AF;
            background: #F1F5F9;
            padding: 6px 12px;
            border-radius: 8px;
            margin-top: 6px;
            margin-bottom: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-left: 3px solid #2563EB;
        }
        .section-form-title iconify-icon {
            font-size: 15px;
        }

        /* ── Form Labels & Controls ─────────────────── */
        .form-label {
            font-size: 12.5px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .required-mark {
            color: #EF4444;
            font-weight: 700;
        }

        /* Input Group with Icon Prepends */
        .input-group-modern {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-prefix-icon {
            position: absolute;
            left: 12px;
            color: #94A3B8;
            font-size: 16px;
            z-index: 4;
            pointer-events: none;
            transition: color 0.2s;
        }
        .input-group-modern:focus-within .input-prefix-icon {
            color: #2563EB;
        }

        .form-control, .form-select {
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            padding: 8.5px 14px 8.5px 36px;
            font-size: 13.5px;
            font-family: inherit;
            color: #0F172A;
            font-weight: 500;
            background: #FFFFFF;
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
            width: 100%;
        }
        .form-control.no-icon {
            padding-left: 13px;
        }
        .form-control:focus, .form-select:focus {
            background: #FFFFFF;
            border-color: #2563EB;
            box-shadow: 0 0 0 3.5px rgba(37, 99, 235, 0.14);
            outline: none;
            color: #0F172A;
        }

        /* =============================================
           PLACEHOLDER TRANSPARAN & LEBIH SAMAR
           ============================================= */
        .form-control::placeholder,
        .form-select::placeholder,
        textarea::placeholder {
            color: #94A3B8 !important;
            opacity: 0.38 !important;
            font-weight: 400 !important;
        }
        .form-control::-webkit-input-placeholder {
            color: #94A3B8 !important;
            opacity: 0.38 !important;
            font-weight: 400 !important;
        }
        .form-control::-moz-placeholder {
            color: #94A3B8 !important;
            opacity: 0.38 !important;
            font-weight: 400 !important;
        }
        .form-control:-ms-input-placeholder {
            color: #94A3B8 !important;
            opacity: 0.38 !important;
            font-weight: 400 !important;
        }

        .form-hint {
            font-size: 11px;
            color: #64748B;
            margin-top: 3px;
            line-height: 1.35;
        }

        /* ── Map Container ──────────────────────────── */
        .map-wrapper {
            border: 1px solid #CBD5E1;
            border-radius: 12px;
            overflow: hidden;
            background: #F8FAFC;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            margin-top: 4px;
        }
        .map-header-hint {
            background: #F1F5F9;
            padding: 6px 12px;
            font-size: 11.5px;
            color: #475569;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }
        .map-container {
            height: 220px;
            width: 100%;
        }

        /* ── Koordinat Box ──────────────────────────── */
        .coord-input {
            background: #F8FAFC !important;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            color: #2563EB !important;
            border: 1px dashed #CBD5E1 !important;
            cursor: not-allowed;
        }

        /* ── Submit Button ──────────────────────────── */
        .btn-submit {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
            color: #ffffff;
            border: none;
            border-radius: 11px;
            padding: 12px 24px;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(37, 99, 235, 0.42);
            color: #ffffff;
        }
        .btn-submit:active {
            transform: translateY(0);
        }
        .btn-submit:disabled {
            opacity: 0.65;
            transform: none;
            cursor: not-allowed;
        }

        .guarantee-badges {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid #F1F5F9;
        }
        .guarantee-badge {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            color: #64748B;
            font-weight: 500;
        }
        .guarantee-badge iconify-icon {
            color: #10B981;
            font-size: 14px;
        }

        /* ── Footer ─────────────────────────────────── */
        .simple-footer {
            text-align: center;
            padding: 16px;
            font-size: 12px;
            color: #94A3B8;
            border-top: 1px solid #E2E8F0;
            background: #FFFFFF;
            margin-top: auto;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .info-sidebar {
                position: static;
                margin-bottom: 24px;
            }
            .form-card-body {
                padding: 20px;
            }
            .form-card-header {
                padding: 18px 20px;
            }
        }
        @media (max-width: 576px) {
            .registration-topbar {
                padding: 8px 14px;
            }
            .step-pill {
                display: none;
            }
            .form-wrapper {
                padding: 16px 12px 32px;
            }
            .form-card {
                border-radius: 16px;
            }
        }
    </style>
</head>
<body>

<!-- Top Navigation Bar -->
<header class="registration-topbar">
    <a href="/" class="btn-back-home">
        <iconify-icon icon="solar:arrow-left-bold-duotone" style="font-size:15px;"></iconify-icon>
        Kembali ke Beranda
    </a>

    <a href="/" class="brand-center">
        <div class="brand-icon">
            <iconify-icon icon="solar:buildings-2-bold" style="font-size:18px;"></iconify-icon>
        </div>
        <span>Kiddy School ERP</span>
    </a>

    <span class="step-pill">
        <iconify-icon icon="solar:shield-check-bold" style="font-size:14px; color:#2563EB;"></iconify-icon>
        Registrasi Multi-Tenant
    </span>
</header>

<!-- Main Container (2-Column Balanced Layout) -->
<main class="form-wrapper">
    <div class="row g-4 align-items-start">
        
        <!-- LEFT COLUMN: Value Proposition & Trust Signals -->
        <div class="col-lg-4 d-none d-lg-block">
            <div class="info-sidebar">
                <div class="hero-badge">
                    <iconify-icon icon="solar:stars-minimalistic-bold"></iconify-icon>
                    SaaS Multi-Tenant v2.5
                </div>
                <h2 class="hero-title">Ekosistem Digital Khusus Yayasan</h2>
                <p class="hero-desc">Satu portal terpusat untuk memantau administrasi, keuangan, kurikulum, dan PPDB di seluruh cabang TK & PAUD Anda.</p>

                <div class="perks-list">
                    <div class="perk-item">
                        <div class="perk-icon-wrap">
                            <iconify-icon icon="solar:bolt-circle-bold-duotone"></iconify-icon>
                        </div>
                        <div class="perk-text">
                            <h4>Aktivasi Super Admin Cepat</h4>
                            <p>Setelah formulir diverifikasi tim pusat, kredensial instan dikirim via email.</p>
                        </div>
                    </div>

                    <div class="perk-item">
                        <div class="perk-icon-wrap">
                            <iconify-icon icon="solar:shield-keyhole-bold-duotone"></iconify-icon>
                        </div>
                        <div class="perk-text">
                            <h4>Keamanan Data Terisolasi</h4>
                            <p>Pemisahan data tingkat tenant dengan enkripsi SSL 256-bit berstandar tinggi.</p>
                        </div>
                    </div>

                    <div class="perk-item">
                        <div class="perk-icon-wrap">
                            <iconify-icon icon="solar:chart-square-bold-duotone"></iconify-icon>
                        </div>
                        <div class="perk-text">
                            <h4>Rekap Finansial Konsolidasi</h4>
                            <p>Pantau pembayaran SPP dan kas masuk semua sekolah dalam satu ringkasan.</p>
                        </div>
                    </div>
                </div>

                <div class="help-support-card">
                    <iconify-icon icon="solar:chat-round-call-bold-duotone"></iconify-icon>
                    <div>
                        <h5>Butuh Pendampingan?</h5>
                        <p>Tim support kami siap membantu proses pengisian pendaftaran yayasan.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Registration Form Card -->
        <div class="col-lg-8 col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <h1>
                        <iconify-icon icon="solar:notes-bold-duotone"></iconify-icon>
                        Formulir Pendaftaran Yayasan Baru
                    </h1>
                    <p>Lengkapi formulir di bawah ini. Tim kami akan memverifikasi dan mengaktifkan akun Super Admin yayasan Anda dalam 1x24 jam.</p>
                </div>

                <div class="form-card-body">

                    {{-- Flash Message Success --}}
                    @if(session('success'))
                    <div class="alert alert-success border-success-subtle bg-success-subtle text-success rounded-3 p-3 mb-3 d-flex align-items-start gap-2">
                        <iconify-icon icon="solar:check-circle-bold" style="font-size:22px; flex-shrink:0; margin-top:2px;"></iconify-icon>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size:14px;">Pendaftaran Berhasil Terkirim!</h6>
                            <p class="mb-0 small leading-relaxed">{{ session('success') }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Flash Message Errors --}}
                    @if($errors->any())
                    <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger rounded-3 p-3 mb-3">
                        <div class="d-flex align-items-center gap-2 fw-bold mb-1" style="font-size:13px;">
                            <iconify-icon icon="solar:danger-triangle-bold" style="font-size:18px;"></iconify-icon>
                            Mohon lengkapi isian formulir berikut:
                        </div>
                        <ul class="mb-0 ps-3 small">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('yayasan.register.store') }}" method="POST" id="formYayasan">
                        @csrf

                        {{-- BAGIAN 1: Identitas & Kontak Yayasan --}}
                        <div class="section-form-title">
                            <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
                            1. Identitas & Kontak Yayasan
                        </div>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-12">
                                <label class="form-label">Nama Yayasan <span class="required-mark">*</span></label>
                                <div class="input-group-modern">
                                    <iconify-icon icon="solar:buildings-2-bold-duotone" class="input-prefix-icon"></iconify-icon>
                                    <input type="text" name="nama_yayasan" class="form-control" placeholder="Contoh: Yayasan Bina Insani Ceria" required value="{{ old('nama_yayasan') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Penanggung Jawab / Ketua <span class="required-mark">*</span></label>
                                <div class="input-group-modern">
                                    <iconify-icon icon="solar:user-bold-duotone" class="input-prefix-icon"></iconify-icon>
                                    <input type="text" name="penanggung_jawab" class="form-control" placeholder="Nama lengkap ketua yayasan" required value="{{ old('penanggung_jawab') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Yayasan / PIC <span class="required-mark">*</span></label>
                                <div class="input-group-modern">
                                    <iconify-icon icon="solar:letter-bold-duotone" class="input-prefix-icon"></iconify-icon>
                                    <input type="email" name="email_kontak" class="form-control" placeholder="email@yayasan.org" required value="{{ old('email_kontak') }}">
                                </div>
                                <div class="form-hint">Email penerima akun Super Admin yayasan.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon / WhatsApp <span class="required-mark">*</span></label>
                                <div class="input-group-modern">
                                    <iconify-icon icon="solar:phone-calling-rounded-bold-duotone" class="input-prefix-icon"></iconify-icon>
                                    <input type="tel" name="telepon" class="form-control" placeholder="Contoh: 0812-3456-7890" required value="{{ old('telepon') }}">
                                </div>
                                <div class="form-hint">Nomor aktif untuk konfirmasi & koordinasi teknis.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">NPWP Yayasan</label>
                                <div class="input-group-modern">
                                    <iconify-icon icon="solar:card-2-bold-duotone" class="input-prefix-icon"></iconify-icon>
                                    <input type="text" name="npwp_yayasan" class="form-control" placeholder="XX.XXX.XXX.X-XXX.XXX (opsional)" value="{{ old('npwp_yayasan') }}">
                                </div>
                            </div>
                        </div>

                        {{-- BAGIAN 2: Alamat & Titik Koordinat Peta --}}
                        <div class="section-form-title">
                            <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon>
                            2. Alamat & Titik Koordinat Peta
                        </div>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-12">
                                <label class="form-label">Alamat Lengkap Kantor Yayasan <span class="required-mark">*</span></label>
                                <div class="input-group-modern">
                                    <iconify-icon icon="solar:map-point-wave-bold-duotone" class="input-prefix-icon" style="top:12px;"></iconify-icon>
                                    <textarea name="alamat" class="form-control" rows="2" placeholder="Jl. Pendidikan No. 1, Kelurahan..., Kecamatan..., Kota/Kabupaten..." required style="padding-top:8px;">{{ old('alamat') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Tentukan Titik Lokasi Yayasan di Peta</label>
                                <div class="map-wrapper">
                                    <div class="map-header-hint">
                                        <iconify-icon icon="solar:cursor-bold-duotone" style="color:#2563EB;"></iconify-icon>
                                        Klik pada peta atau cari alamat di kolom pencarian untuk menetapkan koordinat.
                                    </div>
                                    <div class="map-container" id="map"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label" style="font-size:11.5px;">Lintang (Latitude)</label>
                                <div class="input-group-modern">
                                    <iconify-icon icon="solar:map-arrow-square-bold-duotone" class="input-prefix-icon" style="color:#2563EB;"></iconify-icon>
                                    <input type="text" name="lintang" id="lintang" class="form-control coord-input" placeholder="Otomatis dari peta" value="{{ old('lintang') }}" readonly>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label" style="font-size:11.5px;">Bujur (Longitude)</label>
                                <div class="input-group-modern">
                                    <iconify-icon icon="solar:map-arrow-square-bold-duotone" class="input-prefix-icon" style="color:#2563EB;"></iconify-icon>
                                    <input type="text" name="bujur" id="bujur" class="form-control coord-input" placeholder="Otomatis dari peta" value="{{ old('bujur') }}" readonly>
                                </div>
                            </div>
                        </div>

                        {{-- BAGIAN 3: Legalitas Yayasan --}}
                        <div class="section-form-title">
                            <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                            3. Legalitas Yayasan <span style="font-weight:400; font-size:11px; color:#64748B; text-transform:none;">(Opsional — dapat dilengkapi nanti)</span>
                        </div>
                        
                        <div class="row g-2 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nomor Akta Pendirian</label>
                                <div class="input-group-modern">
                                    <iconify-icon icon="solar:document-bold-duotone" class="input-prefix-icon"></iconify-icon>
                                    <input type="text" name="nomor_akta_pendirian" class="form-control" placeholder="Contoh: AHU-123456.AH.01.04" value="{{ old('nomor_akta_pendirian') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor SK Kemenkumham</label>
                                <div class="input-group-modern">
                                    <iconify-icon icon="solar:verified-check-bold-duotone" class="input-prefix-icon"></iconify-icon>
                                    <input type="text" name="nomor_sk_kemenkumham" class="form-control" placeholder="Contoh: AHU-0012345.AH.01.12.2023" value="{{ old('nomor_sk_kemenkumham') }}">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit" id="btnSubmit">
                            <iconify-icon icon="solar:plain-bold-duotone" style="font-size:18px;"></iconify-icon>
                            Kirim Pendaftaran Yayasan Sekarang
                        </button>

                        <div class="guarantee-badges">
                            <div class="guarantee-badge">
                                <iconify-icon icon="solar:shield-check-bold"></iconify-icon>
                                Enkripsi SSL 256-Bit
                            </div>
                            <div class="guarantee-badge">
                                <iconify-icon icon="solar:clock-circle-bold"></iconify-icon>
                                Verifikasi Cepat 1x24 Jam
                            </div>
                            <div class="guarantee-badge">
                                <iconify-icon icon="solar:wallet-money-bold"></iconify-icon>
                                Tanpa Biaya Pendaftaran
                            </div>
                        </div>

                        <p style="text-align:center; font-size:11.5px; color:#64748B; margin-top:12px; margin-bottom:0;">
                            Dengan mendaftar, Anda menyetujui 
                            <a href="{{ route('legal.privacy') }}" target="_blank" style="color:#2563EB; font-weight:600; text-decoration:none;">Kebijakan Privasi</a> dan 
                            <a href="{{ route('legal.terms') }}" target="_blank" style="color:#2563EB; font-weight:600; text-decoration:none;">Syarat & Ketentuan</a> Kiddy School ERP.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Simple Footer -->
<footer class="simple-footer">
    © {{ date('Y') }} Kiddy School ERP. Platform Manajemen Yayasan TK & PAUD Terpadu.
</footer>

<!-- Leaflet Map Scripts -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Init Peta Leaflet ──────────────────────────────────────────────
    const defaultLat = -6.2, defaultLng = 106.816;
    const map = L.map('map').setView([defaultLat, defaultLng], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Geocoder (search box)
    const geocoder = L.Control.geocoder({
        defaultMarkGeocode: false,
        placeholder: 'Cari alamat yayasan di peta...'
    })
    .on('markgeocode', function (e) {
        const { center } = e.geocode;
        placeMarker(center);
        map.setView(center, 16);
    })
    .addTo(map);

    let marker = null;

    function placeMarker(latlng) {
        if (marker) marker.remove();
        marker = L.marker(latlng, { draggable: true }).addTo(map);
        updateCoords(latlng.lat, latlng.lng);
        marker.on('dragend', function (e) {
            updateCoords(e.target.getLatLng().lat, e.target.getLatLng().lng);
        });
    }

    function updateCoords(lat, lng) {
        document.getElementById('lintang').value = lat.toFixed(7);
        document.getElementById('bujur').value   = lng.toFixed(7);
    }

    map.on('click', function (e) {
        placeMarker(e.latlng);
    });

    // Restore koordinat dari old() jika ada
    const oldLat = '{{ old('lintang') }}';
    const oldLng = '{{ old('bujur') }}';
    if (oldLat && oldLng) {
        const ll = L.latLng(parseFloat(oldLat), parseFloat(oldLng));
        placeMarker(ll);
        map.setView(ll, 15);
    }

    // Invalidate map size after DOM render
    setTimeout(function() {
        map.invalidateSize();
    }, 400);

    // ── Form Submit Loading ────────────────────────────────────────────
    document.getElementById('formYayasan').addEventListener('submit', function () {
        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sedang Mengirim Pendaftaran...';
    });
});
</script>

</body>
</html>
