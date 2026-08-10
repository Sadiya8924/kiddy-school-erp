<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kebijakan Privasi — {{ \App\Models\Setting::get('site_title', 'Sistem TK Ceria') }}</title>
  <meta name="description" content="Kebijakan Privasi Sistem Informasi Manajemen TK — cara kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda.">
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

{{-- Header --}}
<div class="hero-legal">
  <div class="container">
    <a href="/" class="text-white text-decoration-none small opacity-75">← Kembali ke Beranda</a>
    <div class="mt-3 d-flex align-items-center gap-3">
      <iconify-icon icon="solar:shield-check-bold-duotone" style="font-size:3rem; color:#93c5fd;"></iconify-icon>
      <div>
        <h1 class="h3 fw-bold mb-1">Kebijakan Privasi</h1>
        <span class="badge-version me-2">Versi 1.0</span>
        <span class="opacity-75 small">Berlaku sejak: 1 Agustus 2026</span>
      </div>
    </div>
  </div>
</div>

{{-- Konten --}}
<div class="container py-5" style="max-width: 800px;">

  <div class="alert alert-info border-0 rounded-3 mb-4 d-flex gap-2 align-items-start">
    <iconify-icon icon="solar:info-circle-bold" class="fs-5 text-info mt-1 flex-shrink-0"></iconify-icon>
    <div class="small">Kebijakan ini menjelaskan bagaimana <strong>{{ \App\Models\Setting::get('site_title', 'Sistem TK Ceria') }}</strong>
    mengumpulkan, menggunakan, dan melindungi data pribadi Anda sesuai <strong>UU PDP No. 27/2022</strong>.</div>
  </div>

  <h2>1. Data yang Kami Kumpulkan</h2>
  <p class="text-muted small mb-2">Sistem kami mengumpulkan data berikut demi keperluan administrasi pendidikan anak:</p>
  <ul class="small">
    <li><strong>Data identitas anak:</strong> nama, NIK, tanggal lahir, jenis kelamin, foto</li>
    <li><strong>Dokumen keluarga:</strong> scan Kartu Keluarga, scan akta kelahiran</li>
    <li><strong>Lokasi domisili:</strong> alamat, koordinat geografis (keperluan Dapodik)</li>
    <li><strong>Data orang tua/wali:</strong> nama, NIK, pekerjaan, nomor telepon</li>
    <li><strong>Data akademik:</strong> absensi, perkembangan belajar, rapor, jurnal guru</li>
    <li><strong>Data akun:</strong> alamat email, kata sandi terenkripsi</li>
  </ul>

  <h2>2. Dasar Hukum Pemrosesan</h2>
  <p class="small">Pemrosesan data dilakukan berdasarkan:</p>
  <ul class="small">
    <li><strong>Persetujuan tersurat</strong> orang tua/wali (UU PDP Pasal 20) yang diberikan saat pendaftaran</li>
    <li><strong>Pemenuhan kewajiban administrasi pendidikan</strong> (Permendikbud terkait Dapodik)</li>
  </ul>

  <h2>3. Cara Kami Menggunakan Data</h2>
  <ul class="small">
    <li>Mengelola proses pendaftaran dan administrasi siswa</li>
    <li>Menyajikan laporan perkembangan belajar kepada orang tua/wali</li>
    <li>Memenuhi kewajiban pelaporan ke Dinas Pendidikan (Dapodik)</li>
    <li>Mengirimkan notifikasi penting terkait kegiatan sekolah</li>
  </ul>

  <h2>4. Penyimpanan dan Keamanan Data</h2>
  <ul class="small">
    <li>Data disimpan di server dengan enkripsi dan proteksi akses berbasis peran</li>
    <li>Kata sandi selalu disimpan dalam bentuk terenkripsi (bcrypt)</li>
    <li>Akses data dibatasi hanya kepada staf yang berwenang</li>
    <li>File dokumen sensitif (rapor, KK, akte) hanya bisa diakses melalui akun terautentikasi</li>
  </ul>

  <h2>5. Berbagi Data dengan Pihak Ketiga</h2>
  <p class="small">Kami <strong>tidak menjual</strong> data pribadi Anda. Data hanya dibagikan kepada:</p>
  <ul class="small">
    <li>Dinas Pendidikan setempat (kewajiban Dapodik)</li>
    <li>Penyedia layanan infrastruktur server (hosting, email) yang terikat perjanjian kerahasiaan</li>
  </ul>

  <h2>6. Hak Subjek Data</h2>
  <p class="small">Sesuai UU PDP, Anda berhak untuk:</p>
  <ul class="small">
    <li><strong>Mengakses</strong> data pribadi yang kami simpan</li>
    <li><strong>Mengoreksi</strong> data yang tidak akurat atau tidak lengkap</li>
    <li><strong>Meminta penghapusan</strong> data (dalam batasan kewajiban hukum)</li>
    <li><strong>Mengajukan keberatan</strong> atas pemrosesan tertentu</li>
  </ul>
  <p class="small">Hubungi admin sekolah atau email <a href="mailto:privasi@tkceria.com">privasi@tkceria.com</a> untuk menggunakan hak-hak di atas.</p>

  <h2>7. Perubahan Kebijakan</h2>
  <p class="small">Kebijakan ini dapat diperbarui sewaktu-waktu. Perubahan material akan diberitahukan melalui email atau notifikasi dalam aplikasi.</p>

  <h2>8. Kontak</h2>
  <p class="small">Untuk pertanyaan terkait privasi, hubungi: <a href="mailto:privasi@tkceria.com">privasi@tkceria.com</a></p>

  <div class="alert alert-warning border-0 rounded-3 mt-4 small">
    <iconify-icon icon="solar:danger-triangle-bold" class="me-1"></iconify-icon>
    <strong>Catatan:</strong> Dokumen ini adalah template. Konten lengkap harus ditinjau oleh konsultan hukum sebelum go-live produksi.
  </div>

  <hr class="my-4">
  <div class="d-flex gap-3 justify-content-between flex-wrap small">
    <a href="/syarat-ketentuan" class="text-primary-custom">Syarat & Ketentuan →</a>
    <a href="/" class="text-muted">← Kembali ke Beranda</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
