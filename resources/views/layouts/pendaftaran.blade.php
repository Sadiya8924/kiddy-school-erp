<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran – Kiddy School ERP</title>

    <!-- CSS Bawaan Template -->
    <link rel="stylesheet" href="{{ asset('template2/css/styles.min.css') }}" />
    <!-- Iconify -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')

    <style>
        body {
            background-color: #F8FAFC;
            background-image: 
                radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.04) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(99, 102, 241, 0.05) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(224, 231, 255, 0.3) 0px, transparent 50%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* ── Mini top bar ─────────────────────────── */
        .pendaftaran-topbar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
            padding: 9px 24px;
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
            color: #6b7280;
            font-weight: 600;
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
        @hasSection('topbar_back_btn')
            @yield('topbar_back_btn')
        @else
            <a href="javascript:history.back()" class="back-btn">
                <iconify-icon icon="solar:alt-arrow-left-bold-duotone"></iconify-icon>
                Kembali
            </a>
        @endif

        @yield('topbar_branding')

        <span class="step-label d-none d-sm-inline">📋 Formulir Pendaftaran Siswa Baru</span>
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
