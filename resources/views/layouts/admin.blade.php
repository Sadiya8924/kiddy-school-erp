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
    
    <!-- ========================================================= -->
    <!-- TEMA "SOLID & CRISP" (Sesuai Referensi Terbaru)           -->
    <!-- ========================================================= -->
    <!-- Import Font 'Inter' biar tulisan lebih "bakuh" -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #2563eb;       /* Biru solid/pekat */
            --primary-blue-soft: #eff6ff;  /* Background biru super muda */
            --primary-blue-border: #bfdbfe;/* Border biru muda */
            --primary-blue-dark: #1e40af;  /* Biru gelap pekat */
            --text-dark: #0f172a;          /* Teks hitam pekat (Slate 900) */
            --text-muted: #64748b;         /* Abu-abu solid (Slate 500) */
            --bg-body: #f8fafc;            
            --border-color: #e2e8f0;       
        }

        body, .page-wrapper {
            background-color: var(--bg-body) !important;
            color: var(--text-dark);
            font-family: 'Inter', sans-serif !important;
            -webkit-font-smoothing: antialiased; /* Kunci biar tulisan tajam! */
        }

        h1, h2, h3, h4, h5, h6 { font-weight: 600 !important; color: var(--text-dark); }
        .text-muted { color: var(--text-muted) !important; }

        .card {
            border: 1px solid var(--border-color) !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            border-radius: 12px !important;
            background-color: #ffffff;
        }

        /* Sidebar Styling (Lebih Rapi & Tegas) */
        .sidebar-nav ul .sidebar-item > .sidebar-link {
            font-weight: 500;
            color: #475569;
        }
        .sidebar-nav ul .sidebar-item.selected > .sidebar-link,
        .sidebar-nav ul .sidebar-item > .sidebar-link.active {
            background-color: var(--primary-blue-soft) !important;
            color: var(--primary-blue-dark) !important;
            font-weight: 600;
            border-radius: 8px;
        }
        .sidebar-nav ul .sidebar-item.selected > .sidebar-link iconify-icon,
        .sidebar-nav ul .sidebar-item > .sidebar-link.active iconify-icon {
            color: var(--primary-blue-dark) !important;
        }
        .nav-small-cap {
            color: #94a3b8 !important;
            font-weight: 600 !important;
            font-size: 11px !important;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Tombol Utama */
        .btn-primary { 
            background-color: var(--primary-blue) !important; 
            border-color: var(--primary-blue) !important; 
            font-weight: 500 !important;
        }
        .btn-primary:hover { 
            background-color: var(--primary-blue-dark) !important; 
        }

        /* Header Tabel */
        .table thead th {
            font-size: 11px;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 600;
            letter-spacing: 0.5px;
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Sweep form onsubmit confirmations
            $('form[onsubmit*="confirm"]').each(function() {
                let form = $(this);
                let onsubmitAttr = form.attr('onsubmit');
                let match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
                let message = match ? match[1] : "Apakah Anda yakin ingin menghapus data ini?";
                
                form.removeAttr('onsubmit');
                form.on('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.off('submit').submit();
                        }
                    });
                });
            });

            // Sweep button/link onclick confirmations
            $('[onclick*="confirm"]').each(function() {
                let element = $(this);
                let onclickAttr = element.attr('onclick');
                let match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
                let message = match ? match[1] : "Apakah Anda yakin?";
                
                element.removeAttr('onclick');
                element.on('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, lanjutkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (element.is('button') || element.is('input')) {
                                element.closest('form').submit();
                            } else if (element.is('a')) {
                                window.location.href = element.attr('href');
                            }
                        }
                    });
                });
            });

            // Global Flash Messages (Session Success/Error)
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#2563eb'
                });
            @endif
        });
    </script>

    <!-- Tempat untuk naruh Script JS tambahan per-halaman -->
    @stack('scripts')
</body>

</html>