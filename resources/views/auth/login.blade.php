<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Yayasan Al-Hikmah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <style>
    body { background-color: #fafafa; font-family: 'Segoe UI', sans-serif; }
    .text-dark-green { color: #0d5c46; }
    .btn-dark-green { background-color: #0d5c46; color: #fff; transition: 0.3s; }
    .btn-dark-green:hover { background-color: #0a4635; color: #fff; }
    .form-control:focus { border-color: #0d5c46; box-shadow: 0 0 0 0.25rem rgba(13, 92, 70, 0.25); }
  </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-5 col-xxl-4">
        
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
          <div class="text-center mb-4">
            <a href="/" class="text-decoration-none d-flex flex-column align-items-center gap-2">
                <div class="bg-success text-white rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <iconify-icon icon="solar:diploma-bold-duotone" class="fs-2"></iconify-icon>
                </div>
                <h4 class="fw-bold text-dark-green mb-0 mt-2">Yayasan Al-Hikmah</h4>
            </a>
            <p class="text-muted small mt-2">Silakan masuk ke akun Anda</p>
          </div>
          
          <form method="POST" action="/login">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-semibold small text-secondary">Alamat Email</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><iconify-icon icon="solar:letter-bold-duotone" class="text-muted"></iconify-icon></span>
                <input type="email" name="email" class="form-control border-start-0 ps-0 bg-light" placeholder="nama@email.com" required>
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
              <a class="text-dark-green fw-semibold small text-decoration-none" href="#">Lupa Sandi?</a>
            </div>
            
            <button type="submit" class="btn btn-dark-green w-100 py-2 rounded-pill fw-bold shadow-sm mb-4">
               Masuk Sekarang
            </button>
            
            <div class="text-center small">
              <span class="text-muted">Belum punya akun?</span>
              <a class="text-dark-green fw-bold text-decoration-none ms-1" href="/register">Daftar di sini</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</body>
</html>