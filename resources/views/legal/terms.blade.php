<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Syarat & Ketentuan — {{ \App\Models\Setting::get('site_title', 'Sistem TK Ceria') }}</title>
  <meta name="description" content="Syarat dan Ketentuan Penggunaan Sistem Informasi Manajemen TK Ceria.">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; }
    .hero-legal { background: linear-gradient(135deg, #1e3a8a, #2952ff); color: #fff; padding: 3rem 0 2rem; }
    h2 { font-size: 1.1rem; font-weight: 700; color: #1e3a8a; border-left: 4px solid #2952ff; padding-left: .75rem; margin: 2rem 0 .5rem; }
    .badge-version { background: #dbeafe; color: #1e3a8a; border-radius: 20px; padding: .2rem .75rem; font-size: .75rem; }
    a { color: #2952ff; }
  </style>
</head>
<body>

<div class="hero-legal">
  <div class="container">
    <a href="/" class="text-white text-decoration-none small opacity-75">← Kembali ke Beranda</a>
    <div class="mt-3 d-flex align-items-center gap-3">
      <iconify-icon icon="solar:document-text-bold-duotone" style="font-size:3rem; color:#93c5fd;"></iconify-icon>
      <div>
        <h1 class="h3 fw-bold mb-1">Syarat & Ketentuan</h1>
        <span class="badge-version me-2">Versi 1.0</span>
        <span class="opacity-75 small">Berlaku sejak: 1 Agustus 2026</span>
      </div>
    </div>
  </div>
</div>

<div class="container py-5" style="max-width: 800px;">

  <div class="alert alert-info border-0 rounded-3 mb-4 d-flex gap-2 align-items-start">
    <iconify-icon icon="solar:info-circle-bold" class="fs-5 text-info mt-1 flex-shrink-0"></iconify-icon>
    <div class="small">Dengan menggunakan <strong>{{ \App\Models\Setting::get('site_title', 'Sistem TK Ceria') }}</strong>, Anda setuju dengan syarat dan ketentuan berikut.</div>
  </div>

  <h2>1. Penerimaan Syarat</h2>
  <p class="small">Dengan mendaftar atau menggunakan sistem ini, Anda menyatakan telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku.</p>

  <h2>2. Penggunaan yang Diizinkan</h2>
  <ul class="small">
    <li>Sistem ini digunakan <strong>hanya</strong> untuk keperluan administrasi pendidikan di sekolah yang bersangkutan</li>
    <li>Pengguna hanya boleh mengakses data yang sesuai dengan peran dan wewenangnya</li>
    <li>Dilarang berbagi akun atau memberikan akses kepada pihak yang tidak berwenang</li>
  </ul>

  <h2>3. Kewajiban Pengguna</h2>
  <ul class="small">
    <li>Memberikan informasi yang benar dan akurat saat mendaftar</li>
    <li>Menjaga kerahasiaan kata sandi akun</li>
    <li>Segera melaporkan kepada admin jika mengetahui adanya akses tidak sah</li>
    <li>Tidak mencoba mengakses data siswa atau pengguna lain yang bukan wewenangnya</li>
  </ul>

  <h2>4. Batasan Tanggung Jawab</h2>
  <p class="small">Sistem disediakan "sebagaimana adanya". Pengelola sistem tidak bertanggung jawab atas kerugian yang timbul akibat:</p>
  <ul class="small">
    <li>Kegagalan teknis di luar kendali pengelola (force majeure, gangguan server hosting)</li>
    <li>Penggunaan sistem yang tidak sesuai dengan ketentuan ini</li>
    <li>Data yang dimasukkan secara tidak benar oleh pengguna</li>
  </ul>

  <h2>5. Kepemilikan Intelektual</h2>
  <p class="small">Seluruh konten, desain, dan kode dalam sistem ini adalah milik pengembang. Data yang dimasukkan pengguna tetap menjadi milik lembaga yang bersangkutan.</p>

  <h2>6. Penghentian Akses</h2>
  <p class="small">Pengelola berhak menangguhkan atau mengakhiri akses pengguna yang melanggar ketentuan ini tanpa pemberitahuan sebelumnya.</p>

  <h2>7. Perubahan Ketentuan</h2>
  <p class="small">Syarat dan ketentuan ini dapat berubah. Perubahan signifikan akan diberitahukan melalui notifikasi dalam sistem atau email.</p>

  <h2>8. Hukum yang Berlaku</h2>
  <p class="small">Syarat ini tunduk pada hukum Negara Kesatuan Republik Indonesia, termasuk UU PDP No. 27/2022 dan peraturan pendidikan yang berlaku.</p>

  <div class="alert alert-warning border-0 rounded-3 mt-4 small">
    <iconify-icon icon="solar:danger-triangle-bold" class="me-1"></iconify-icon>
    <strong>Catatan:</strong> Dokumen ini adalah template. Konten lengkap harus ditinjau oleh konsultan hukum sebelum go-live produksi.
  </div>

  <hr class="my-4">
  <div class="d-flex gap-3 justify-content-between flex-wrap small">
    <a href="/kebijakan-privasi" class="text-primary-custom">← Kebijakan Privasi</a>
    <a href="/" class="text-muted">Kembali ke Beranda →</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
