<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran – {{ \App\Models\Setting::get('site_title', 'Yayasan Al-Hikmah') }}</title>

    <!-- CSS Bawaan Template -->
    <link rel="stylesheet" href="{{ asset('template2/css/styles.min.css') }}" />
    <!-- Iconify -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

    @stack('styles')

    <style>
        body {
            background-color: #F7F9FC;
            min-height: 100vh;
        }

        /* ── Mini top bar ─────────────────────────── */
        .pendaftaran-topbar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 1px 6px rgba(0,0,0,0.06);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .pendaftaran-topbar .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 7px 14px;
            text-decoration: none;
            transition: background 0.18s, color 0.18s;
            cursor: pointer;
        }
        .pendaftaran-topbar .back-btn:hover {
            background: #e0e7ef;
            color: #1d4ed8;
        }

        .pendaftaran-topbar .brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            font-size: 15px;
            color: #111827;
            text-decoration: none;
        }
        .pendaftaran-topbar .brand img {
            height: 28px;
            object-fit: contain;
        }

        .pendaftaran-topbar .step-label {
            margin-left: auto;
            font-size: 11.5px;
            color: #9ca3af;
            font-weight: 500;
            letter-spacing: 0.02em;
        }

        .pendaftaran-main {
            padding-top: 8px;
            padding-bottom: 40px;
        }
    </style>
</head>
<body>

    {{-- ── Mini Top Bar: hanya back + logo, tanpa menu ── --}}
    <div class="pendaftaran-topbar">
        <a href="javascript:history.back()" class="back-btn">
            <iconify-icon icon="solar:alt-arrow-left-bold-duotone"></iconify-icon>
            Kembali
        </a>

        <a href="/" class="brand">
            @if(\App\Models\Setting::get('site_logo'))
                <img src="{{ \App\Models\Setting::get('site_logo') }}" alt="Logo">
            @else
                <iconify-icon icon="solar:diploma-bold-duotone" class="fs-5 text-primary"></iconify-icon>
            @endif
            {{ \App\Models\Setting::get('site_title', 'Yayasan Al-Hikmah') }}
        </a>

        <span class="step-label">📋 Formulir Pendaftaran Siswa Baru</span>
    </div>

    {{-- ── Konten Pendaftaran ── --}}
    <main class="pendaftaran-main">
        @yield('konten_halaman')
    </main>

    <!-- Scripts -->
    <script src="{{ asset('template2/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template2/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
