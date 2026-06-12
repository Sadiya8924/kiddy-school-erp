<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - TK Ceria</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('template2/images/logos/seodashlogo.png') }}" />
  <link rel="stylesheet" href="{{ asset('template2/css/styles.min.css') }}" />
</head>

<body class="bg-primary-subtle">
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-5 col-xxl-4">
            <div class="card mb-0 shadow-lg rounded-4 border-0">
              <div class="card-body">
                <a href="/" class="text-nowrap logo-img text-center d-block py-3 w-100">
                   <h3 class="fw-bold text-success">TK CERIA</h3>
                </a>
                <p class="text-center text-muted mb-4 small">Silakan masuk ke akun Anda</p>
                
                <form method="POST" action="/login">
                  @csrf
                  <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Email</label>
                    <input type="email" name="email" class="form-control border-success" placeholder="nama@email.com" required>
                  </div>
                  <div class="mb-4">
                    <label class="form-label fw-bold">Kata Sandi</label>
                    <input type="password" name="password" class="form-control border-success" placeholder="********" required>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" value="" id="rememberCheck">
                      <label class="form-check-label text-dark" for="rememberCheck">
                        Ingat Saya
                      </label>
                    </div>
                    <a class="text-success fw-bold" href="#">Lupa Sandi?</a>
                  </div>
                  <button type="submit" class="btn btn-success w-100 py-8 fs-4 mb-4 rounded-pill fw-bold shadow-sm">Masuk Sekarang</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Belum punya akun?</p>
                    <a class="text-success fw-bold ms-2" href="/register">Daftar di sini</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('template2/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('template2/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>