<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pendaftaran PPDB & Registrasi Akun — {{ $yayasan->nama_yayasan }}</title>
    <meta name="description" content="Pendaftaran siswa baru (PPDB) dan pembuatan akun portal wali murid untuk {{ $yayasan->nama_yayasan }}.">

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
            background: #F1F5F9;
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
            background: #EFF6FF;
            border-color: #BFDBFE;
            color: #1D4ED8;
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
            width: 32px; height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #1E40AF, #3B82F6);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            font-size: 14px;
        }

        /* ── Main Layout ────────────────────────────── */
        .portal-wrapper {
            padding: 40px 20px 80px;
            max-width: 1140px;
            margin: 0 auto;
        }

        .header-box {
            background: linear-gradient(135deg, #1E3A8A 0%, #1E40AF 100%);
            border-radius: 24px;
            padding: 40px 36px;
            color: #fff;
            margin-bottom: 36px;
            box-shadow: 0 10px 30px rgba(30,64,175,0.15);
        }
        .header-box h1 {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }
        .header-box p {
            font-size: 14.5px;
            color: #DBEAFE;
            margin: 0;
            max-width: 680px;
            line-height: 1.6;
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
            background: #EFF6FF;
            border-color: #3B82F6;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30,64,175,0.08);
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
            border-color: #2563EB;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.12);
        }
        .btn-register-parent {
            background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-size: 14.5px;
            font-weight: 700;
            width: 100%;
            box-shadow: 0 6px 18px rgba(30,64,175,0.25);
            transition: all 0.2s;
        }
        .btn-register-parent:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(30,64,175,0.35);
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
            color: #1E40AF;
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

        @media (max-width: 600px) {
            .header-box { padding: 28px 20px; }
            .card-custom { padding: 24px 18px; }
            .pendaftaran-topbar { padding: 10px 16px; }
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

    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1.5 fw-bold" style="font-size:11.5px;">
        PPDB Online {{ date('Y') }}/{{ date('Y')+1 }}
    </span>
</div>

<!-- Main Portal Container -->
<main class="portal-wrapper">
    <!-- Header Banner -->
    <div class="header-box">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-warning text-dark font-bold px-3 py-1 rounded-pill" style="font-size:11px;">
                PORTAL PENDAFTARAN RESMI
            </span>
        </div>
        <h1>Pendaftaran Siswa Baru & Akun Orang Tua</h1>
        <p>
            Selamat datang di portal pendaftaran <strong>{{ $yayasan->nama_yayasan }}</strong>. Silakan pilih pendaftaran siswa baru (PPDB) atau buat akun wali murid untuk memantau aktivitas dan e-rapor anak.
        </p>
    </div>

    <!-- Dual Action Grid -->
    <div class="row g-4 mb-5">
        <!-- Kolom 1: Formulir Pendaftaran Siswa (PPDB Online) -->
        <div class="col-lg-6">
            <div class="card-custom">
                <span class="section-badge-tag bg-success-subtle text-success border border-success-subtle">
                    <iconify-icon icon="solar:diploma-bold"></iconify-icon>
                    Pendaftaran Siswa (PPDB)
                </span>

                <h3 class="fw-bold mb-2" style="font-size:20px; color:#0F172A;">Daftarkan Putra / Putri Anda</h3>
                <p class="text-muted small mb-4">
                    Pilih cabang TK atau PAUD tujuan di bawah <strong>{{ $yayasan->nama_yayasan }}</strong> untuk langsung mengisi formulir pendaftaran siswa baru secara online.
                </p>

                <div class="mb-4">
                    <div class="text-muted small fw-bold text-uppercase mb-2" style="font-size:11px; letter-spacing:0.05em;">
                        Pilih Cabang Sekolah Tujuan:
                    </div>

                    @forelse($schools as $school)
                    <a href="{{ route('pendaftaran', ['school_id' => $school->id]) }}" class="school-choice-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-3 bg-white text-primary border" style="width:42px;height:42px;font-size:20px;flex-shrink:0;">
                                🏫
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size:14.5px;">{{ $school->nama_sekolah }}</div>
                                <div class="text-muted" style="font-size:12px;">{{ Str::limit($school->alamat, 40) }}</div>
                            </div>
                        </div>
                        <div class="btn btn-sm btn-primary rounded-pill px-3 fw-bold d-flex align-items-center gap-1" style="font-size:12px;">
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
                    <div class="fw-bold text-dark small mb-2">📋 Dokumen yang Perlu Disiapkan:</div>
                    <div class="doc-item">
                        <span>📄</span>
                        <div class="small text-muted"><strong>Kartu Keluarga & Akta Kelahiran</strong> (Foto / Scan asli).</div>
                    </div>
                    <div class="doc-item">
                        <span>🖼️</span>
                        <div class="small text-muted"><strong>Pas Foto Anak</strong> (Ukuran 3x4 / setengah badan).</div>
                    </div>
                    <div class="doc-item">
                        <span>💳</span>
                        <div class="small text-muted"><strong>Bukti Pembayaran Pendaftaran</strong> (Struk / Transfer bank).</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom 2: Registrasi & Login Akun Wali Murid (In-Place Switcher) -->
        <div class="col-lg-6">
            <div class="card-custom">
                @auth
                <span class="section-badge-tag bg-primary-subtle text-primary border border-primary-subtle">
                    <iconify-icon icon="solar:user-bold"></iconify-icon>
                    Akun Portal Wali Murid
                </span>

                <div class="text-center py-5 bg-light rounded-4 mt-3">
                    <div style="font-size:42px; margin-bottom:10px;">👋</div>
                    <h5 class="fw-bold mb-1" style="color:#0F172A;">Halo, {{ Auth::user()->name }}!</h5>
                    <p class="text-muted small mb-3">Anda sudah masuk ke portal dengan akun terdaftar.</p>
                    <a href="/dashboard" class="btn btn-primary rounded-pill px-4 fw-bold">
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
                    <iconify-icon icon="solar:check-circle-bold" class="fs-5 flex-shrink-0 mt-0.5"></iconify-icon>
                    <div>{{ session('success') }}</div>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger rounded-3 p-3 mb-3 small d-flex gap-2 align-items-start">
                    <iconify-icon icon="solar:danger-triangle-bold" class="fs-5 flex-shrink-0 mt-0.5"></iconify-icon>
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
                            🚀 Buat Akun Wali Murid Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-3 small text-muted">
                        Sudah punya akun? <a href="javascript:void(0)" onclick="switchAuthTab('login')" class="text-primary fw-bold text-decoration-none">Masuk ke Portal Langsung</a>
                    </div>
                </div>

                <!-- PANEL 2: LOGIN FORM (IN-PLACE) -->
                <div id="panelLogin" class="auth-form-panel" style="display: none;">
                    <h3 class="fw-bold mb-2" style="font-size:20px; color:#0F172A;">Masuk ke Portal</h3>
                    <p class="text-muted small mb-4">
                        Masuk ke akun portal wali murid <strong>{{ $yayasan->nama_yayasan }}</strong> untuk memantau perkembangan ananda.
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
                            🔑 Masuk ke Portal Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-3 small text-muted">
                        Belum punya akun? <a href="javascript:void(0)" onclick="switchAuthTab('register')" class="text-primary fw-bold text-decoration-none">Daftar Akun Baru</a>
                    </div>
                </div>

                @endauth
            </div>
        </div>
    </div>

    <!-- Bantuan WhatsApp -->
    @if($yayasan->telepon)
    <div class="p-4 rounded-4 bg-white border border-success-subtle d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-circle bg-success text-white" style="width:46px;height:46px;font-size:24px;flex-shrink:0;">
                <iconify-icon icon="simple-icons:whatsapp"></iconify-icon>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark" style="font-size:16px;">Butuh Bantuan Pendaftaran PPDB?</h5>
                <p class="text-muted small mb-0">Hubungi tim administrasi <strong>{{ $yayasan->nama_yayasan }}</strong> via WhatsApp.</p>
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
    © {{ date('Y') }} {{ $yayasan->nama_yayasan }}. Didukung oleh Kiddy School ERP.
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
