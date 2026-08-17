<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- TM-11: Title dinamis dari Setting, bukan hardcode nama sekolah --}}
  <title>Masuk — {{ \App\Models\Setting::get('site_title', 'Sistem Sekolah') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @php
    $activeYayasan = \App\Models\Yayasan::where('status', 'aktif')->first();
    $template = ($activeYayasan && isset($activeYayasan->landing_content['template'])) 
        ? $activeYayasan->landing_content['template'] 
        : \App\Models\Setting::get('landing_template', 'ocean');
    
    $themeColor = '#2563eb';
    $themeBg    = '#F7F9FC';
    
    if ($template === 'ceria') {
        $themeColor = '#EF9F27';
        $themeBg    = '#FDFBF7';
    } elseif ($template === 'trustworthy') {
        $themeColor = '#185FA5';
        $themeBg    = '#f8fafc';
    } elseif ($template === 'natural') {
        $themeColor = '#639922';
        $themeBg    = '#F6F3EC';
    }
  @endphp
  <style>
    body { background-color: {{ $themeBg }}; font-family: 'Segoe UI', sans-serif; }
    .text-primary-custom { color: {{ $themeColor }} !important; }
    .btn-primary-custom { background-color: {{ $themeColor }} !important; color: #fff !important; transition: 0.3s; border: none; }
    .btn-primary-custom:hover { opacity: 0.9; color: #fff !important; }
    .form-control:focus { border-color: {{ $themeColor }}; box-shadow: 0 0 0 0.25rem {{ $themeColor }}33; }
    .bg-primary-custom { background-color: {{ $themeColor }} !important; color: white !important; }
  </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-5 col-xxl-4">
        
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
          <div class="text-center mb-4">
            <a href="/" class="text-decoration-none d-flex flex-column align-items-center gap-2">
                @if(\App\Models\Setting::get('site_logo'))
                    <img src="{{ \App\Models\Setting::get('site_logo') }}" alt="Logo" style="height: 48px; object-fit: contain;">
                @else
                    <div class="bg-primary-custom rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <iconify-icon icon="solar:diploma-bold-duotone" class="fs-2 text-white"></iconify-icon>
                    </div>
                @endif
                <h4 class="fw-bold text-primary-custom mb-0 mt-2">{{ \App\Models\Setting::get('site_title', 'Yayasan Al-Hikmah') }}</h4>
            </a>
            {{-- TM-11: Kalimat sambutan netral — tidak mengasumsikan peran 'Orang Tua' saja --}}
            <p class="text-muted small mt-2">Masuk untuk mengakses sistem sesuai peran Anda —<br><span class="text-primary-custom fw-semibold">Admin, Guru, Kepala Sekolah, atau Wali Murid.</span></p>
          </div>

          {{-- TM-03: Banner Sistem Beta --}}
          @if(app()->environment('local', 'staging', 'demo', 'production'))
          <div class="alert alert-warning border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2 small">
            <iconify-icon icon="solar:danger-triangle-bold" class="fs-6 text-warning"></iconify-icon>
            <span><strong>Sistem Beta</strong> — Akun di bawah untuk demonstrasi. Jangan masukkan data pribadi nyata.</span>
          </div>
          @endif

          {{-- Alert Banners --}}
          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
              <div class="d-flex align-items-center gap-2">
                <iconify-icon icon="solar:danger-bold" class="fs-5"></iconify-icon>
                <span class="small fw-semibold">{{ session('error') }}</span>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
              <div class="d-flex align-items-center gap-2 mb-1">
                <iconify-icon icon="solar:danger-bold" class="fs-5"></iconify-icon>
                <span class="small fw-bold">Ada beberapa kesalahan:</span>
              </div>
              <ul class="mb-0 ps-3 small text-start">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          
          <form method="POST" action="/login">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-semibold small text-secondary">Alamat Email</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><iconify-icon icon="solar:letter-bold-duotone" class="text-muted"></iconify-icon></span>
                <input type="email" name="email" class="form-control border-start-0 ps-0 bg-light" placeholder="nama@email.com" value="{{ old('email') }}" required>
              </div>
            </div>
            
            <div class="mb-4">
              <label class="form-label fw-semibold small text-secondary">Kata Sandi</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><iconify-icon icon="solar:lock-password-bold-duotone" class="text-muted"></iconify-icon></span>
                <input type="password" name="password" class="form-control border-start-0 ps-0 bg-light" placeholder="••••••••" required>
              </div>
            </div>
            
            <div class="d-flex align-items-center justify-content-between mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="rememberCheck">
                <label class="form-check-label text-muted small" for="rememberCheck">Ingat Saya</label>
              </div>
              {{-- TM-34: Link ke halaman forgot password yang nyata --}}
              <a class="text-primary-custom fw-semibold small text-decoration-none" href="/forgot-password">Lupa Sandi?</a>
            </div>
            
            <button type="submit" class="btn btn-primary-custom w-100 py-2 rounded-pill fw-bold shadow-sm mb-4">
               Masuk Sekarang
            </button>
            
            <div class="text-center small mb-3">
              <span class="text-muted">Belum punya akun?</span>
              <a class="text-primary-custom fw-bold text-decoration-none ms-1" href="/register">Daftar di sini</a>
            </div>

            {{-- Quick Demo Login Accounts --}}
            <div class="mt-4 pt-3 border-top">
              <p class="text-uppercase text-muted fw-bold mb-2" style="font-size: 11px;">
                💡 Demo Akun Tersedia di Database:
              </p>
              <div class="row g-2">
                <div class="col-6">
                  <button type="button" onclick="fillDemo('ortu@tkceria.com')" class="btn btn-sm btn-light border text-start w-100 p-2">
                    <div class="fw-bold text-dark" style="font-size: 11px;">👨‍👩‍👧 Wali Murid</div>
                    <div class="text-muted text-truncate" style="font-size: 10px;">ortu@tkceria.com</div>
                  </button>
                </div>
                <div class="col-6">
                  <button type="button" onclick="fillDemo('naning@tk.com')" class="btn btn-sm btn-light border text-start w-100 p-2">
                    <div class="fw-bold text-dark" style="font-size: 11px;">👩‍🏫 Guru TK</div>
                    <div class="text-muted text-truncate" style="font-size: 10px;">naning@tk.com</div>
                  </button>
                </div>
                <div class="col-6">
                  <button type="button" onclick="fillDemo('admin@tk.com')" class="btn btn-sm btn-light border text-start w-100 p-2">
                    <div class="fw-bold text-dark" style="font-size: 11px;">🏫 Admin TK</div>
                    <div class="text-muted text-truncate" style="font-size: 10px;">admin@tk.com</div>
                  </button>
                </div>
                <div class="col-6">
                  <button type="button" onclick="fillDemo('kepsek@tk.com')" class="btn btn-sm btn-light border text-start w-100 p-2">
                    <div class="fw-bold text-dark" style="font-size: 11px;">🎓 Kepsek</div>
                    <div class="text-muted text-truncate" style="font-size: 10px;">kepsek@tk.com</div>
                  </button>
                </div>
                <div class="col-6">
                  <button type="button" onclick="fillDemo('superadmin@tkceria.com')" class="btn btn-sm btn-light border text-start w-100 p-2">
                    <div class="fw-bold text-dark" style="font-size: 11px;">🏛️ Super Admin</div>
                    <div class="text-muted text-truncate" style="font-size: 10px;">superadmin@tkceria.com</div>
                  </button>
                </div>
                <div class="col-6">
                  <button type="button" onclick="fillDemo('owner@yayasan.com')" class="btn btn-sm btn-light border text-start w-100 p-2">
                    <div class="fw-bold text-dark" style="font-size: 11px;">👑 Owner SaaS</div>
                    <div class="text-muted text-truncate" style="font-size: 10px;">owner@yayasan.com</div>
                  </button>
                </div>
              </div>
              <p class="text-muted text-center fst-italic mt-2 mb-0" style="font-size: 10px;">
                Password bawaan semua akun: <strong class="text-dark">password</strong>
              </p>
            </div>
          </form>

          <script>
            function fillDemo(email) {
              document.querySelector('input[name="email"]').value = email;
              document.querySelector('input[name="password"]').value = 'password';
            }
          </script>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  @if(session('error'))
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: "{{ session('error') }}",
        confirmButtonColor: '#2952ff',
        confirmButtonText: 'Coba Lagi'
      });
    </script>
  @endif

  @if($errors->any())
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Kesalahan Validasi',
        html: '<ul class="text-start" style="font-size: 14px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
        confirmButtonColor: '#2952ff',
        confirmButtonText: 'Perbaiki'
      });
    </script>
  @endif
</body>
</html>