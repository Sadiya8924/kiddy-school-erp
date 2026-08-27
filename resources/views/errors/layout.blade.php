@php
  $yayasanToken = null;
  $yayasanObj = null;

  // 1. Deteksi token dari segmen URL saat ini (/y/{kode_yayasan}/...)
  if (request()->segment(1) === 'y' && request()->segment(2)) {
      $yayasanToken = request()->segment(2);
  }

  // 2. Jika tidak ada di URL saat ini, cek dari URL referer (URL sebelumnya)
  if (!$yayasanToken && request()->header('referer')) {
      $refererPath = parse_url(request()->header('referer'), PHP_URL_PATH);
      if ($refererPath && preg_match('#^/y/([^/]+)#', $refererPath, $matches)) {
          $yayasanToken = $matches[1];
      }
  }

  // 3. Jika user sedang login dan belum ketemu token, cek dari data Yayasan user
  if (!$yayasanToken && auth()->check() && method_exists(auth()->user(), 'getUserYayasanId')) {
      $userYayasanId = auth()->user()->getUserYayasanId();
      if ($userYayasanId) {
          $yayasanObj = \App\Models\Yayasan::find($userYayasanId);
          if ($yayasanObj) {
              $yayasanToken = $yayasanObj->encrypted_token;
          }
      }
  }

  // 4. Cari objek Yayasan jika token ditemukan
  if ($yayasanToken && !$yayasanObj) {
      $decryptedCode = \App\Models\Yayasan::decryptToken($yayasanToken);
      $targetCode = $decryptedCode ? strtoupper(trim($decryptedCode)) : strtoupper(trim($yayasanToken));
      $yayasanObj = \App\Models\Yayasan::where(function($q) use ($targetCode) {
          $q->where('kode_yayasan', $targetCode)
            ->orWhere('kode_yayasan', strtolower($targetCode));
          if (in_array($targetCode, ['YYS-002', 'YYS-2', 'YAH2'])) {
              $q->orWhereIn('kode_yayasan', ['YYS-002', 'YAH2']);
          } elseif (in_array($targetCode, ['YYS-001', 'YYS-1', 'YBIC'])) {
              $q->orWhereIn('kode_yayasan', ['YYS-001', 'YBIC']);
          }
      })->where('status', 'aktif')->first();
  }

  // Tentukan URL Beranda & Login
  if ($yayasanToken) {
      $homeUrl = route('yayasan.landing', $yayasanToken);
      $loginUrl = route('yayasan.login', $yayasanToken);
  } else {
      $homeUrl = url('/');
      $loginUrl = url('/login');
  }

  // Default title & logo
  $siteTitle = \App\Models\Setting::get("site_title", "Sistem TK");
  $siteLogo = \App\Models\Setting::get("site_logo");

  if ($yayasanObj) {
      $siteTitle = $yayasanObj->nama_yayasan;
      if (!empty($yayasanObj->logo_yayasan)) {
          $siteLogo = asset('storage/' . $yayasanObj->logo_yayasan);
      }
  }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield("code") — @yield("title") | {{ $siteTitle }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <style>
    body { background: linear-gradient(135deg, #f0f4ff, #fafbff); font-family: "Segoe UI", sans-serif; min-height: 100vh; }
    .error-code { font-size: 8rem; font-weight: 900; background: linear-gradient(135deg, #2952ff, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; }
    .error-card { background: white; border-radius: 1.5rem; box-shadow: 0 20px 60px rgba(41,82,255,.1); padding: 3rem; max-width: 520px; width: 100%; }
    .logo-area img { max-height: 48px; }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center p-4">
  <div class="error-card text-center">

    {{-- Logo Tenant --}}
    <div class="logo-area mb-4">
      @if($siteLogo)
        <img src="{{ $siteLogo }}" alt="{{ $siteTitle }}">
      @else
        <div class="d-inline-flex align-items-center justify-content-center rounded" style="width:48px;height:48px;background:#2952ff;">
          <iconify-icon icon="solar:diploma-bold-duotone" style="color:white;font-size:1.5rem;"></iconify-icon>
        </div>
      @endif
    </div>

    {{-- Kode Error --}}
    <div class="error-code">@yield("code")</div>

    {{-- Judul & Pesan --}}
    <h2 class="fw-bold mt-2 mb-2" style="font-size:1.3rem;">@yield("title")</h2>
    <p class="text-muted mb-4 small">@yield("message")</p>

    {{-- Tombol Navigasi --}}
    @auth
      <a href="{{ $homeUrl }}" class="btn btn-primary rounded-pill px-4 me-2">
        <iconify-icon icon="solar:home-2-bold-duotone" class="me-1"></iconify-icon>
        Beranda
      </a>
      <a href="{{ route("dashboard") }}" class="btn btn-outline-secondary rounded-pill px-4">
        Ke Dashboard
      </a>
    @else
      <a href="{{ $homeUrl }}" class="btn btn-primary rounded-pill px-4 me-2">
        <iconify-icon icon="solar:home-2-bold-duotone" class="me-1"></iconify-icon>
        Beranda
      </a>
      <a href="{{ $loginUrl }}" class="btn btn-outline-secondary rounded-pill px-4">
        Login
      </a>
    @endauth

    <div class="mt-4 small text-muted">{{ $siteTitle }}</div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
