<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lupa Sandi - {{ \App\Models\Setting::get('site_title', 'Yayasan Al-Hikmah') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { background-color: #fafafa; font-family: 'Segoe UI', sans-serif; }
    .text-primary-custom { color: #2952ff; }
    .btn-primary-custom { background-color: #2952ff; color: #fff; transition: 0.3s; border: none; }
    .btn-primary-custom:hover { background-color: #193199; color: #fff; }
    .form-control:focus { border-color: #2952ff; box-shadow: 0 0 0 0.25rem rgba(41, 82, 255, 0.25); }
    .bg-primary-custom { background-color: #2952ff !important; color: white !important; }
    .icon-circle {
      width: 64px; height: 64px; border-radius: 50%;
      background: linear-gradient(135deg, #dbeafe, #bfdbfe);
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto;
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-5 col-xxl-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
          <div class="text-center mb-4">
            <a href="/" class="text-decoration-none d-flex flex-column align-items-center gap-2 mb-3">
              @if(\App\Models\Setting::get('site_logo'))
                <img src="{{ \App\Models\Setting::get('site_logo') }}" alt="Logo" style="height: 40px; object-fit: contain;">
              @else
                <div class="bg-primary-custom rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                  <iconify-icon icon="solar:diploma-bold-duotone" class="fs-4 text-white"></iconify-icon>
                </div>
              @endif
            </a>
            <div class="icon-circle mb-3">
              <iconify-icon icon="solar:letter-opened-bold-duotone" style="font-size: 28px; color: #2952ff;"></iconify-icon>
            </div>
            <h5 class="fw-bold mb-1">Lupa Kata Sandi?</h5>
            <p class="text-muted small">Masukkan email Anda. Kami akan mengirim instruksi reset sandi ke email tersebut.</p>
          </div>

          @if(session('success'))
            <div class="alert alert-success border-0 rounded-3 d-flex align-items-start gap-2 mb-4" role="alert">
              <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-5 text-success mt-1"></iconify-icon>
              <div>
                <div class="fw-semibold small">Permintaan Terkirim</div>
                <div class="small">{{ session('success') }}</div>
              </div>
            </div>
          @endif

          @if($errors->any())
            <div class="alert alert-danger border-0 rounded-3 mb-4" role="alert">
              <ul class="mb-0 ps-3 small text-start">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          @if(!session('success'))
          <form method="POST" action="/forgot-password">
            @csrf
            <div class="mb-4">
              <label class="form-label fw-semibold small text-secondary">Alamat Email Terdaftar</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                  <iconify-icon icon="solar:letter-bold-duotone" class="text-muted"></iconify-icon>
                </span>
                <input type="email" name="email" id="email"
                       class="form-control border-start-0 ps-0 bg-light"
                       placeholder="nama@email.com"
                       value="{{ old('email') }}"
                       required autofocus>
              </div>
              <div class="form-text text-muted small mt-1">Pesan reset akan dikirim jika email ini terdaftar.</div>
            </div>
            <button type="submit" class="btn btn-primary-custom w-100 py-2 rounded-pill fw-bold shadow-sm mb-3">
              <iconify-icon icon="solar:letter-send-bold-duotone" class="me-1"></iconify-icon>
              Kirim Instruksi Reset
            </button>
          </form>
          @else
          <div class="text-center">
            <div class="text-muted small mb-3">Tidak menerima email? Periksa folder Spam/Junk atau tunggu beberapa menit.</div>
            <a href="/forgot-password" class="btn btn-outline-secondary btn-sm rounded-pill">Coba Lagi</a>
          </div>
          @endif

          <div class="text-center mt-3">
            <a href="/login" class="text-primary-custom text-decoration-none small fw-semibold">
              <iconify-icon icon="solar:arrow-left-bold" class="me-1"></iconify-icon>
              Kembali ke Halaman Login
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @if(session('success'))
    <script>
      Swal.fire({ icon: 'success', title: 'Email Terkirim!', text: "{{ session('success') }}", confirmButtonColor: '#2952ff', confirmButtonText: 'OK' });
    </script>
  @endif
</body>
</html>
