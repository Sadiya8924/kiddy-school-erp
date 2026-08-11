<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Super Admin') — TK Ceria</title>
  <link rel="stylesheet" href="/template2/css/styles.min.css" />
  <link rel="stylesheet" href="/template2/css/icons/tabler-icons/tabler-icons.css" />
  
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
            margin-bottom: 1.25rem;
        }

        /* Custom Fractional Spacing & Utility Fixes for Layout & Cards */
        .gap-3\.5 { gap: 1.25rem !important; }
        .gap-2\.5 { gap: 0.875rem !important; }
        .gap-4 { gap: 1.5rem !important; }
        .mb-3\.5 { margin-bottom: 1.25rem !important; }
        .mt-3\.5 { margin-top: 1.25rem !important; }
        .p-3\.5 { padding: 1.25rem !important; }
        .px-3\.5 { padding-left: 1.25rem !important; padding-right: 1.25rem !important; }
        .py-3\.5 { padding-top: 1.25rem !important; padding-bottom: 1.25rem !important; }
        .mb-0\.5 { margin-bottom: 0.25rem !important; }
        .mb-1\.5 { margin-bottom: 0.5rem !important; }
        .mb-2\.5 { margin-bottom: 0.875rem !important; }

        /* Ensure card elements and card wrappers always maintain vertical separation */
        .container-fluid > div > .card + .card,
        .container-fluid > div > .card + form,
        .container-fluid > div > form + .card,
        .container-fluid > div > .card + .row,
        .container-fluid > div > .row + .card,
        .container-fluid > div > .card + .table-responsive,
        .container-fluid > div > .card + div:not(.row) {
            margin-top: 0.5rem;
        }
        
        .row:has(> .col-12 > .card), .row:has(> [class*="col-"]) {
            margin-bottom: 0.5rem;
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

        /* Pagination SVG Overflow Fix */
        .pagination svg {
            width: 1rem !important;
            height: 1rem !important;
            max-width: 100%;
            display: inline-block;
        }
        .pagination {
            margin-bottom: 0 !important;
        }
    </style>
</head>
<body>

  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

      @include('layouts.partials.sidebar')

      <div class="body-wrapper">

        <header class="app-header">
          <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                  <i class="ti ti-menu-2"></i>
                </a>
              </li>
            </ul>

            <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
              <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

                <li class="nav-item d-none d-md-block me-4 border-end pe-4">
                    <form action="{{ route('superadmin.switch_school') }}" method="POST" id="formSwitchSchool" class="d-flex align-items-center gap-2 m-0">
                        @csrf
                        <iconify-icon icon="solar:buildings-bold-duotone" class="fs-6 text-primary"></iconify-icon>
                        <select name="active_school_id" class="form-select form-select-sm border-primary text-primary fw-bold shadow-sm" onchange="document.getElementById('formSwitchSchool').submit()" style="min-width: 200px;">
                            <option value="">-- Mode Pusat (Semua Data) --</option>
                            @foreach(\App\Models\School::where('status', 'aktif')->get() as $school)
                                <option value="{{ $school->id }}" {{ session('active_school_id') == $school->id ? 'selected' : '' }}>
                                    Pantau: {{ $school->nama_sekolah }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </li>

                <li class="nav-item dropdown">
                  <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center gap-2">
                      <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                           style="width:35px;height:35px;font-size:14px;">
                        {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                      </div>
                      <span class="d-none d-md-block fw-semibold text-dark small">
                        {{ auth()->user()->name ?? 'User' }}
                      </span>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up">
                    <div class="message-body">
                      <p class="mb-1 px-3 py-1 text-muted small">
                        <span class="badge bg-danger-subtle text-danger">Super Admin</span>
                      </p>
                      <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                          <i class="ti ti-logout fs-6"></i> Logout
                        </button>
                      </form>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </nav>
        </header>

        <div class="container-fluid">

          {{-- Flash messages will be handled by SweetAlert2 --}}

          @yield('content')
        </div>

      </div>
  </div> 
  
  <script src="/template2/libs/jquery/dist/jquery.min.js"></script>
  <script src="/template2/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/template2/js/sidebarmenu.js"></script>
  <script src="/template2/js/app.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const togglers = document.querySelectorAll(".sidebartoggler");
      togglers.forEach(function (toggler) {
        toggler.addEventListener("click", function (e) {
          e.preventDefault();
          document.body.classList.toggle("sidebar-hidden");
        });
      });
    });

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

  @stack('scripts')
</body>
</html>