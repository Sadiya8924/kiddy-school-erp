<!doctype html>
<!-- Tambahkan data-bs-theme="dark" di sini kalau mau langsung test Dark Mode bawaan Bootstrap 5 -->
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi TK Ceria</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('template2/images/logos/seodashlogo.png') }}" />
    
    <!-- CSS Simplebar (wajib untuk scrollbar custom bawaan template) -->
    <link rel="stylesheet" href="{{ asset('template2/libs/simplebar/dist/simplebar.min.css') }}">
    
    <!-- CSS Utama SeoDash -->
    <link rel="stylesheet" href="{{ asset('template2/css/styles.min.css') }}" />
    
    <!-- Global UI/UX Fixes (Sesuai Review Desain) -->
    <style>
        /* 1. Kontras & Aksesibilitas: Memperjelas border input form secara global */
        .form-control, .form-select {
            border: 1px solid #adb5bd !important; /* Abu-abu medium yang lebih tegas */
        }
        .form-control:focus, .form-select:focus {
            border-color: #2952ff !important; /* Fokus warna biru (Primary) */
            box-shadow: 0 0 0 0.25rem rgba(41, 82, 255, 0.25) !important;
        }
        
        /* 2. Warna Background & Kontras: Memastikan body off-white agar card putih menonjol */
        body, .page-wrapper {
            background-color: #f8f9fa !important;
        }

        /* 3. Standardisasi Identitas Warna: Memaksa elemen aktif sidebar menjadi hijau (menggantikan biru) */
        .sidebar-nav ul .sidebar-item.selected>.sidebar-link, 
        .sidebar-nav ul .sidebar-item.selected>.sidebar-link.active, 
        .sidebar-nav ul .sidebar-item>.sidebar-link.active {
            background-color: #2952ff !important;
            color: #fff !important;
        }
        
        /* Mengubah warna icon pada menu yang aktif agar selaras */
        .sidebar-nav ul .sidebar-item.selected>.sidebar-link iconify-icon, 
        .sidebar-nav ul .sidebar-item>.sidebar-link.active iconify-icon {
            color: #fff !important;
        }
    </style>

    <!-- Tempat untuk naruh CSS tambahan per-halaman -->
    @stack('styles')
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!-- Header / Navbar -->
            @include('layouts.partials.navbar')

            <!-- Container Content -->
            <div class="container-fluid">
                
                <!-- Konten Halaman Utama -->
                @yield('konten_halaman')

                <!-- Footer -->
                <div class="py-6 px-6 text-center mt-5">
                    <p class="mb-0 fs-4 text-muted">© {{ date('Y') }} TK Ceria - Web TK Al-Hidayah v1.0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts Dasar Template -->
    <script src="{{ asset('template2/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template2/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template2/libs/simplebar/dist/simplebar.js') }}"></script>
    <script src="{{ asset('template2/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('template2/js/app.min.js') }}"></script>
    
    <!-- Iconify untuk icon modern bawaan template -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    
    <!-- Library Chart (ApexCharts) untuk Grafik -->
    <script src="{{ asset('template2/libs/apexcharts/dist/apexcharts.min.js') }}"></script>

    <!-- Tempat untuk naruh Script JS tambahan per-halaman -->
    @stack('scripts')
</body>

</html>