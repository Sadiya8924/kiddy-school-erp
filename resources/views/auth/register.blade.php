<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register - Yayasan Al-Hikmah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { background-color: #fafafa; font-family: 'Segoe UI', sans-serif; }
    .text-primary-custom { color: #2952ff; }
    .btn-primary-custom { background-color: #2952ff; color: #fff; transition: 0.3s; border: none; }
    .btn-primary-custom:hover { background-color: #193199; color: #fff; }
    .form-control:focus { border-color: #2952ff; box-shadow: 0 0 0 0.25rem rgba(41, 82, 255, 0.25); }
    .bg-primary-custom { background-color: #2952ff !important; color: white !important; }
  </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">
  <div class="container py-5">
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
            <p class="text-muted small mt-2">Silakan buat akun Anda</p>
          </div>

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
          
          <form method="POST" action="/register">
            @csrf
            
            <div class="mb-3">
              <label class="form-label fw-semibold small text-secondary">Nama Lengkap</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><iconify-icon icon="solar:user-bold-duotone" class="text-muted"></iconify-icon></span>
                <input type="text" name="name" class="form-control border-start-0 ps-0 bg-light" placeholder="Masukkan nama Anda" value="{{ old('name') }}" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold small text-secondary">Alamat Email</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><iconify-icon icon="solar:letter-bold-duotone" class="text-muted"></iconify-icon></span>
                <input type="email" name="email" class="form-control border-start-0 ps-0 bg-light" placeholder="nama@email.com" value="{{ old('email') }}" required>
              </div>
            </div>
            
            <div class="mb-3">
              <label class="form-label fw-semibold small text-secondary">Kata Sandi</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><iconify-icon icon="solar:lock-password-bold-duotone" class="text-muted"></iconify-icon></span>
                <input type="password" name="password" class="form-control border-start-0 ps-0 bg-light" placeholder="Minimal 8 karakter" required>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold small text-secondary">Konfirmasi Sandi</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><iconify-icon icon="solar:lock-password-bold-duotone" class="text-muted"></iconify-icon></span>
                <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0 bg-light" placeholder="Ketik ulang sandi" required>
              </div>
            </div>

            {{-- TM-33: JANGAN tambahkan field "role" di sini. Role dipaksa server-side (wali_murid). --}}

            <button type="submit" class="btn btn-primary-custom w-100 py-2 rounded-pill fw-bold shadow-sm mb-4 mt-2">
               Daftar Akun
            </button>
            
            <div class="text-center small">
              <span class="text-muted">Sudah punya akun?</span>
              <a class="text-primary-custom fw-bold text-decoration-none ms-1" href="/login">Masuk di sini</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  @if(session('error'))
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Registrasi Gagal',
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