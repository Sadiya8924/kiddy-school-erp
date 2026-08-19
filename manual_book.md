# 📘 Manual Book — Kiddy School ERP
### Panduan Lengkap Alur Sistem dari Semua Sudut Pandang (Role)

---

> [!IMPORTANT]
> Sistem ini adalah platform **SaaS Multi-Tenant** untuk manajemen Yayasan TK & PAUD.
> Satu platform dapat menampung banyak yayasan, dan setiap yayasan dapat memiliki banyak cabang sekolah.
> **Password default semua akun demo:** `password`

---

## 🗺️ Peta Role Sistem

| Role | Cakupan | Deskripsi |
|------|---------|-----------|
| 🔑 **Owner SaaS** | Platform Global | Pemilik platform, mengelola semua yayasan yang bergabung |
| 👑 **Super Admin** | Per Yayasan | Admin tertinggi yayasan, bisa akses semua cabang sekolah |
| 🛠️ **Admin TK** | Per Sekolah | Pengelola satu cabang sekolah |
| 🏫 **Kepala Sekolah** | Per Sekolah | Kepala sekolah satu cabang (approval materi, rapor) |
| 👨‍🏫 **Guru** | Per Kelas | Guru yang memegang satu kelas |
| 👪 **Wali Murid** | Per Anak | Orang tua siswa yang terdaftar |

---

## 🌐 Halaman Publik (Tanpa Login)

### Landing Page Utama SaaS — `http://127.0.0.1:8000/`
Halaman promosi platform untuk menarik calon yayasan baru.

```
[/] Halaman Utama
  ├── Tombol "Daftar Sekarang" (navbar atas)    → /daftar-yayasan
  ├── Tombol "Daftarkan Yayasan Anda" (hero)    → /daftar-yayasan
  ├── Tombol "Pelajari Fitur & Modul"           → Scroll ke #fitur
  └── Tombol "Daftarkan Yayasan Sekarang" (CTA) → /daftar-yayasan
```

### Form Pendaftaran Yayasan Baru — `/daftar-yayasan`
```
[/daftar-yayasan]
  ├── Isi nama yayasan, email, no HP, alamat
  ├── Klik "Daftarkan Yayasan Sekarang"
  └── → Menunggu approval Owner
```

### Landing Page Yayasan — `/y/{kode_yayasan}`
Halaman publik khusus masing-masing yayasan (contoh: `/y/YAH2` atau `/y/YBIC`).
```
[/y/{kode_yayasan}]
  ├── Tampilan Visual Dinamis 4 Tema (Natural Sage, Ceria Playful, Trustworthy Navy, Modern Ocean)
  ├── Sinkronisasi Nama Publik Yayasan (site_title) di Title, Header, Footer & Metadata
  ├── Navbar Header Responsif (dengan Offcanvas Drawer di Mobile/Tablet <= 992px)
  ├── Tombol "Daftar PPDB Online"   → /y/{kode_yayasan}/daftar
  └── Tombol "Dashboard / Login"   → /y/{kode_yayasan}/login (jika belum login) atau /dashboard (jika sudah login)
```

### Portal Login Resmi per Yayasan — `/y/{kode_yayasan}/login`
Halaman login terisolasi khusus untuk pengguna yayasan terkait (Super Admin, Admin TK, Kepala Sekolah, Guru, & Wali Murid).
```
[/y/{kode_yayasan}/login]
  ├── Layout Split-Screen Modern (Fitur Unggulan Kiri & Form Login Kanan)
  ├── Skema Warna Terikat Tema Yayasan (Natural, Ceria, Trustworthy, Ocean)
  ├── Tombol Toggle Intip Password & Checkbox "Ingat Saya" (remember_token)
  ├── Grid Demo Quick-Fill Account untuk Kemudahan Pengujian
  └── Sistem Validasi Cross-Tenant Blocker (Mencegah Login Pengguna Yayasan Lain)
```

### Halaman Pendaftaran PPDB per Yayasan — `/y/{kode_yayasan}/daftar`
Formulir registrasi calon wali murid baru per yayasan.
```
[/y/{kode_yayasan}/daftar]
  ├── Pilihan Cabang Sekolah TK / PAUD
  ├── Form Data Wali Murid & Calon Siswa
  └── Tombol Navigasi Topbar "Login Portal" → /y/{kode_yayasan}/login
```

### Portal Login Khusus Owner Platform SaaS — `/login`
Halaman login eksklusif untuk jajaran **Owner SaaS & Platform Admin**.
```
[/login]
  ├── Branding Resmi Platform SaaS: "Kiddy School ERP — Platform Management"
  ├── Restriksi Akses: HANYA DIBUKA UNTUK OWNER / PLATFORM ADMIN (owner@demo.com)
  └── Pengguna Yayasan yang mencoba login di URL ini akan DIBLOKIR dan diarahkan kembali 
      ke Portal Yayasan mereka melalui SweetAlert2 Warning dengan 1-klik Direct Link.
```

---
---

# 🔑 SUDUT PANDANG: OWNER SaaS

## Siapa Owner?
Owner adalah pemilik platform Kiddy School ERP. Bisa melihat dan mengelola **semua yayasan** yang terdaftar di sistem.

## Alur Masuk
```
/login → (email owner) → /superadmin/dashboard
```

## Menu Owner

### 📋 Antrian Pendaftaran Yayasan — `/owner/yayasan-registrations`
```
[Antrian Pendaftaran Yayasan]
  ├── Lihat daftar yayasan yang menunggu persetujuan
  ├── Per baris: nama yayasan, email, tanggal daftar, status
  ├── Tombol [Setujui]
  │   └── → Yayasan diaktifkan, Super Admin otomatis dibuat
  └── Tombol [Tolak]
      └── → Pendaftaran ditolak
```

### 🏛️ Data Semua Yayasan — `/owner/yayasans`
```
[Daftar Yayasan]
  ├── Lihat semua yayasan terdaftar
  ├── Tombol [+ Tambah Yayasan] → Form tambah yayasan manual
  ├── Tombol [Edit] per yayasan → Ubah profil yayasan
  └── Tombol [Ganti Konteks] → Masuk/pantau yayasan tertentu
```

### 🖥️ Edit Landing Page Platform — `/settings/landing-page`
Mengedit konten yang tampil di halaman `http://127.0.0.1:8000/`.
```
[Edit Landing Page Utama]
  ├── BAGIAN 1: Identitas & Branding
  │   ├── Nama Platform SaaS
  │   ├── Tagline Platform
  │   └── Upload Logo Utama
  │
  ├── BAGIAN 2: Hero Promosi
  │   ├── Judul Utama + Judul Highlight
  │   ├── Teks Deskripsi
  │   └── Teks Tombol CTA
  │
  ├── BAGIAN 3: Statistik (4 angka metrik)
  ├── BAGIAN 4: Alur Cara Kerja (4 Langkah)
  ├── BAGIAN 5: Banner Ajakan Bawah
  ├── BAGIAN 6: Kontak & Footer
  │
  └── Tombol [Simpan Perubahan Landing Page]
```

---
---

# 👑 SUDUT PANDANG: SUPER ADMIN (Yayasan)

## Siapa Super Admin?
Admin pusat yayasan. Bisa mengakses dan memantau **semua cabang sekolah** di bawah yayasannya.

## Alur Masuk
```
/login → (email super admin) → /superadmin/dashboard
```

## Dashboard Super Admin — `/superadmin/dashboard`
```
[Dashboard Super Admin]
  ├── Statistik: Total Sekolah, Sekolah Aktif, Total Pengguna, Total Siswa
  ├── Dropdown "Switch Sekolah" → Pilih cabang yang ingin dipantau
  └── Daftar Pengumuman terbaru dari semua cabang
```

## Menu Super Admin

### 🏫 Manajemen Sekolah — `/superadmin/schools`
```
[Daftar Sekolah]
  ├── Lihat semua cabang sekolah di bawah yayasan
  ├── Tombol [+ Tambah Sekolah]
  │   └── Form: nama sekolah, NPSN, kepala sekolah, foto, dll.
  ├── Tombol [Edit] per sekolah → Ubah profil sekolah
  └── Tombol [Modul] per sekolah → /superadmin/schools/{id}/modules
      └── Toggle ON/OFF fitur per sekolah
          (Jurnal Harian, Absensi, Rapor, Materi, dll.)
```

### 👥 Manajemen Pengguna — `/superadmin/users`
```
[Daftar Pengguna]
  ├── Lihat semua pengguna lintas sekolah
  ├── Tombol [+ Tambah User]
  │   └── Form: nama, email, password, role, pilih sekolah
  ├── Tombol [Edit] per user
  └── Tombol [Hapus] per user
```

### ✏️ Edit Landing Page Yayasan — `/superadmin/landing-page`
Edit konten halaman publik yayasan di `/y/{kode_yayasan}`.
```
[Edit Landing Page Yayasan]
  ├── Pilih Template Desain
  ├── Hero & Branding Yayasan
  ├── Visi Misi Yayasan
  ├── Unit Pendidikan (TK/PAUD)
  └── Kontak & Sosial Media
```

> [!NOTE]
> Super Admin juga bisa mengakses **semua fitur Admin TK** setelah memilih sekolah via dropdown "Switch Sekolah" di dashboard.

---
---

# 🛠️ SUDUT PANDANG: ADMIN TK

## Siapa Admin TK?
Pengelola operasional satu cabang sekolah. Mengurus data siswa, kelas, rapor, dan pengumuman.

## Alur Masuk
```
/login → (email admin) → /dashboard (Dashboard sekolah sendiri)
```

## Dashboard Admin — `/dashboard`
```
[Dashboard Sekolah]
  ├── Statistik: Total Siswa Aktif, Total Kelas, Semester Aktif
  ├── Daftar Pengumuman terbaru
  └── Kartu per kelas: nama kelas, jumlah siswa
```

## Menu Admin

### 👧 Data Siswa — `/data-siswa`
```
[Data Siswa]
  ├── Tabel daftar semua siswa aktif
  ├── Filter kelas & pencarian nama/NIK
  ├── Tombol [+ Tambah Siswa Manual]
  ├── Tombol [Lihat Detail] per siswa → /siswa/{id}
  │   └── Profil, data orang tua, foto dokumen
  ├── Tombol [Set Kelas]  → Masukkan siswa ke kelas tertentu
  ├── Tombol [Import NISN] → Upload file Excel NISN
  └── Tombol [Terima] untuk siswa PPDB online (status: inden)
      └── Status berubah: "inden" → "aktif"
```

### 🏫 Data Kelas — `/kelas`
```
[Data Kelas]
  ├── Kartu per kelas: nama kelas, jumlah siswa, wali guru
  ├── Tombol [+ Buat Kelas Baru]
  ├── Tombol [Lihat Detail] → /kelas/{id}
  │   └── Daftar siswa di kelas
  ├── Tombol [Pindah Siswa] → Pindahkan siswa antar kelas
  └── Tombol [Hapus Kelas]
```

### 📅 Absensi Harian — `/absensi-harian`
```
[Absensi Harian]
  ├── Filter kelas & tanggal
  ├── Daftar siswa + status absensi hari ini
  ├── Pilihan status per siswa: [Hadir] [Sakit] [Izin] [Alfa]
  └── Tombol [Simpan Absensi]
```

### 📊 Rekap Absensi — `/rekap-absensi`
```
[Rekap Absensi]
  ├── Pilih rentang tanggal (dari - sampai)
  ├── Pilih kelas / semua kelas
  ├── Tabel rekap H/I/S/A per hari per siswa
  └── Total kehadiran & ketidakhadiran
```

### 📋 Isi Rapor — `/isi-rapor`
```
[Isi Rapor]
  ├── Filter kelas
  ├── Daftar siswa + status rapor (Belum / Draft / Permanen)
  └── Tombol [Isi/Edit Rapor] → /rapor/{id}
      ├── Form nilai per bidang perkembangan
      ├── Narasi capaian pembelajaran
      ├── Data absensi (otomatis dari sistem)
      ├── Sisipkan foto jurnal kegiatan
      └── Tombol [Simpan Draft] / [Kunci Permanen]
```

### 🖨️ Cetak Rapor — `/cetak-rapor`
```
[Cetak Rapor]
  ├── Filter kelas
  ├── Daftar siswa + status rapor
  ├── Tombol [Cetak PDF] per siswa
  └── Tombol [Toggle Akses Wali Murid]
      ├── ON  → Wali murid bisa lihat rapor online
      └── OFF → Rapor tersembunyi dari portal wali murid
```

### 📦 Arsip Rapor — `/arsip-rapor`
```
[Arsip Rapor]
  ├── Daftar arsip rapor per semester/tahun ajaran lama
  └── Tombol [Upload Arsip] / [Download Arsip]
```

### 📢 Pengumuman — `/pengumuman`
```
[Pengumuman]
  ├── Daftar pengumuman aktif
  ├── Tombol [+ Buat Pengumuman]
  │   └── Form: judul, isi, tanggal, target (Semua/Guru/Wali Murid)
  └── Tombol [Hapus] per pengumuman
```

### ⚙️ Pengaturan Sekolah — `/pengaturan`
```
[Pengaturan]
  ├── Tab: Semester
  │   ├── Tombol [+ Buat Semester Baru]
  │   ├── Tombol [Aktifkan] per semester
  │   └── Dropdown [Ganti Konteks Semester]
  │
  ├── Tab: Kuota PPDB
  │   ├── Kuota TK Pagi, TK Siang, PAUD
  │   └── Batas maksimal tahun inden
  │
  ├── Tab: Penugasan Guru
  │   └── Assign guru ke kelas tertentu (1 guru = 1 kelas)
  │
  └── Tab: Kenaikan Kelas → /kenaikan-kelas
```

### 🎓 Kenaikan Kelas — `/kenaikan-kelas`
```
[Kenaikan Kelas]
  ├── Pilih kelas asal & kelas tujuan
  ├── Preview daftar siswa yang akan dinaikkan
  └── Tombol [Proses Kenaikan Kelas]
      └── Siswa berpindah ke kelas baru semester berikutnya
```

### 📐 Template Rapor — `/admin/template`
```
[Template Rapor]
  ├── Daftar template yang tersedia
  ├── Tombol [+ Buat Template Baru]
  ├── Tombol [Lihat/Edit] → /admin/template/{id}
  │   └── Tambah item penilaian (KD/Kompetensi Dasar)
  └── Tombol [Aktifkan] → Template ini jadi acuan isi rapor
```

---
---

# 🏫 SUDUT PANDANG: KEPALA SEKOLAH (Kepsek)

## Siapa Kepsek?
Akses hampir sama dengan Admin, dengan tambahan wewenang **approval materi** dari guru.

## Alur Masuk
```
/login → (email kepsek) → /dashboard
```

## Menu Eksklusif Kepsek

### ✅ Persetujuan Materi — `/materi/persetujuan`
```
[Persetujuan Materi Guru]
  ├── Daftar materi yang diajukan guru (menunggu persetujuan)
  ├── Per item: judul, nama guru, tanggal, file lampiran
  ├── Tombol [Setujui] → Materi tampil ke wali murid
  └── Tombol [Tolak]   → Materi dikembalikan ke guru + keterangan
```

> [!NOTE]
> Kepsek juga dapat mengakses semua fitur Admin TK kecuali manajemen pengguna dan pembuatan semester.

---
---

# 👨‍🏫 SUDUT PANDANG: GURU

## Siapa Guru?
Memegang satu kelas. Bertanggung jawab atas absensi, jurnal, perkembangan fisik, dan rapor kelasnya.

## Alur Masuk
```
/login → (email guru) → /dashboard (menampilkan kelas yang diampu)
```

## Alur Kegiatan Harian Guru

### Langkah 1 — Absensi Harian `/absensi-harian`
```
[Absensi Harian]
  ├── Otomatis tampil siswa di kelas yang diampu
  ├── Pilih status: [Hadir] [Sakit] [Izin] [Alfa]
  └── Tombol [Simpan Absensi Hari Ini]
```

### Langkah 2 — Buat Materi `/materi/guru/create`
```
[Form Buat Materi Baru]
  ├── Pilih kelas
  ├── Judul materi & tema kegiatan
  ├── Deskripsi / tujuan pembelajaran
  ├── Upload file lampiran (PDF, gambar, video)
  ├── Tombol [Kirim untuk Disetujui Kepsek]
  └── Status: "Menunggu Persetujuan"
      (Setelah disetujui → tampil ke Wali Murid)
```

### Langkah 3 — Jurnal Harian `/jurnal-harian`
```
[Jurnal Harian]
  ├── Daftar materi yang sudah dibuat
  ├── Klik materi → /jurnal-harian/materi/{id}/kelas/{kelas_id}
  │   └── Klik per siswa → Form input jurnal
  │       ├── Upload foto kegiatan siswa
  │       ├── Catatan perkembangan
  │       └── Tombol [Simpan Jurnal]
  └── Tombol [Download ZIP Semua Foto]
```

### Langkah 4 — Perkembangan Fisik `/perkembangan-physic`
```
[Perkembangan Fisik]
  ├── Daftar siswa + data TB/BB terakhir
  ├── Tombol [Tambah Data] per siswa
  │   └── Form: tanggal, tinggi badan, berat badan, lingkar kepala
  └── Tombol [Lihat Riwayat] → /perkembangan-fisik/{id}
      └── Grafik perkembangan dari waktu ke waktu
```

### Langkah 5 — Isi Rapor `/isi-rapor`
```
[Isi Rapor Kelas]
  ├── Filter otomatis ke kelas yang diampu
  ├── Klik per siswa → /rapor/{id}
  │   ├── Form nilai per komponen (sesuai template aktif)
  │   ├── Narasi perkembangan (teks bebas)
  │   ├── Data absensi otomatis terhubung
  │   ├── Pilih foto jurnal untuk disisipkan
  │   └── Tombol [Simpan Draft] atau [Kunci Permanen]
  └── Status: Draft (bisa direvisi) / Permanen (final, tidak bisa diubah)
```

---
---

# 👪 SUDUT PANDANG: WALI MURID

## Siapa Wali Murid?
Orang tua atau wali yang memantau perkembangan anaknya secara online melalui portal yayasan.

## Cara Mendapatkan Akun

### Jalur A: Daftar via Portal Yayasan
```
/y/{kode_yayasan}/daftar
  ├── Tab "Buat Akun Baru"
  ├── Isi nama, email, kata sandi
  └── Klik "Buat Akun Wali Murid Sekarang" → Login otomatis
```

### Jalur B: Daftar via Formulir PPDB
```
/pendaftaran-siswa?school_id=...
  ├── Isi formulir pendaftaran anak (nama, NIK, alamat, dll.)
  ├── Isi email orang tua
  │   ├── Email BARU → Buat kata sandi baru → Akun otomatis dibuat
  │   └── Email LAMA → Isi kata sandi lama → Anak dihubungkan ke akun lama
  ├── Upload dokumen (foto anak, KK, akta lahir, bukti bayar)
  └── Klik "Kirim Formulir Pendaftaran"
      └── Siswa masuk antrian "Inden" di Admin TK
```

## Alur Masuk Wali Murid
```
/login → (email wali murid) → /dashboard
```

## Dashboard Wali Murid — `/dashboard`
```
[Dashboard Wali Murid]
  ├── Sambutan: "Halo, [Nama Orang Tua]!"
  ├── Kartu per anak: nama, kelas, sekolah
  ├── Tombol [Ganti Anak Aktif] (jika punya lebih dari 1 anak)
  └── Pengumuman terbaru dari sekolah anak
```

> [!NOTE]
> Jika anak masih berstatus **"Inden"** (belum disetujui Admin), anak belum muncul di dashboard. Anak akan muncul setelah Admin menekan tombol **Terima**.

## Menu Wali Murid

### 📝 Rapor Anak — `/rapor-anak`
```
[Rapor Anak]
  ├── Nilai per bidang perkembangan
  ├── Narasi dari guru
  ├── Rekap absensi (H/S/I/A)
  ├── Foto kegiatan belajar yang disisipkan guru
  └── Hanya tampil jika Admin/Guru mengaktifkan akses rapor
```

### 📈 Perkembangan Fisik Anak — `/perkembangan-anak`
```
[Perkembangan Fisik]
  ├── Tabel riwayat: tinggi badan, berat badan, lingkar kepala
  └── Grafik pertumbuhan dari waktu ke waktu
```

### 📓 Jurnal Kegiatan Anak — `/jurnal-anak`
```
[Jurnal Kegiatan]
  ├── Timeline jurnal harian anak di sekolah
  ├── Foto kegiatan belajar & bermain
  ├── Catatan tema & kegiatan dari guru
  └── Kolom komentar (wali murid bisa berkomentar)
```

### 📚 Materi Pelajaran — `/materi`
```
[Materi Pelajaran]
  ├── Daftar materi yang sudah disetujui Kepsek
  ├── File lampiran (PDF, gambar, video)
  ├── Tombol [Tandai Sudah Dibaca]
  └── Kolom komentar/pertanyaan ke guru
```

---
---

# 📋 ALUR PPDB — End-to-End

```
1.  [Publik]  Buka /y/{kode_yayasan}/daftar
2.  [Publik]  Klik "Isi Form PPDB" di samping nama sekolah
3.  [Publik]  Formulir PPDB terbuka → /pendaftaran-siswa?school_id=...
4.  [Publik]  Pilih tahun ajaran → Program muncul (TK Pagi/Siang/PAUD)
5.  [Publik]  Isi data lengkap anak + orang tua + upload dokumen
6.  [Publik]  Sistem cek email orang tua:
              ├── Baru → Buat akun otomatis (isi kata sandi)
              └── Lama → Konfirmasi kata sandi untuk link ke akun lama
7.  [Publik]  Klik "Kirim Formulir Pendaftaran"
8.  [Sistem]  Siswa masuk database dengan status "inden"
9.  [Admin]   Buka /data-siswa → Lihat siswa status "inden"
10. [Admin]   Verifikasi dokumen → Klik [Terima]
11. [Sistem]  Status siswa berubah "aktif"
12. [Wali]    Buka /dashboard → Anak muncul → Akses semua fitur
```

---

# 🔄 ALUR RAPOR SEMESTER — End-to-End

```
1.  [Admin]  Buka /pengaturan → Tab Semester → Buat Semester Baru
2.  [Admin]  Klik [Aktifkan] pada semester yang baru dibuat
3.  [Admin]  Buka /admin/template → Buat/pilih template rapor
4.  [Admin]  Klik [Aktifkan] pada template
5.  [Guru]   Sepanjang semester: input absensi di /absensi-harian
6.  [Guru]   Sepanjang semester: input jurnal + foto di /jurnal-harian
7.  [Guru]   Sepanjang semester: input TB/BB di /perkembangan-physic
8.  [Guru]   Akhir semester: buka /isi-rapor → klik per siswa
9.  [Guru]   Isi nilai per komponen, narasi, pilih foto jurnal
10. [Guru]   Klik [Simpan Draft] (bisa revisi) atau [Kunci Permanen]
11. [Admin]  Buka /cetak-rapor → Toggle [Akses Wali Murid] → ON
12. [Wali]   Buka /rapor-anak → Lihat rapor online
13. [Admin]  Opsional: /cetak-rapor → [Cetak PDF] untuk rapor fisik
```

---
---

# 🔗 Tabel URL Cepat per Role

| URL | Role yang Bisa Akses |
|-----|----------------------|
| `/` | Publik |
| `/daftar-yayasan` | Publik |
| `/y/{kode}` | Publik |
| `/y/{kode}/daftar` | Publik |
| `/pendaftaran-siswa` | Publik |
| `/login` | Publik |
| `/dashboard` | Semua (setelah login) |
| `/owner/yayasans` | 🔑 Owner |
| `/owner/yayasan-registrations` | 🔑 Owner |
| `/settings/landing-page` | 🔑 Owner |
| `/superadmin/dashboard` | 🔑 Owner, 👑 Super Admin |
| `/superadmin/schools` | 🔑 Owner, 👑 Super Admin |
| `/superadmin/users` | 🔑 Owner, 👑 Super Admin |
| `/superadmin/landing-page` | 👑 Super Admin |
| `/data-siswa` | 👑 Super Admin, 🛠️ Admin, 🏫 Kepsek, 👨‍🏫 Guru |
| `/kelas` | 👑 Super Admin, 🛠️ Admin, 🏫 Kepsek, 👨‍🏫 Guru |
| `/absensi-harian` | 👑 Super Admin, 🛠️ Admin, 🏫 Kepsek, 👨‍🏫 Guru |
| `/rekap-absensi` | 👑 Super Admin, 🛠️ Admin, 🏫 Kepsek, 👨‍🏫 Guru |
| `/jurnal-harian` | 👑 Super Admin, 🛠️ Admin, 🏫 Kepsek, 👨‍🏫 Guru |
| `/perkembangan-physic` | 👑 Super Admin, 🛠️ Admin, 🏫 Kepsek, 👨‍🏫 Guru |
| `/isi-rapor` | 👑 Super Admin, 🛠️ Admin, 🏫 Kepsek, 👨‍🏫 Guru |
| `/cetak-rapor` | 👑 Super Admin, 🛠️ Admin, 🏫 Kepsek, 👨‍🏫 Guru |
| `/arsip-rapor` | 👑 Super Admin, 🛠️ Admin, 🏫 Kepsek, 👨‍🏫 Guru |
| `/pengumuman` | 🛠️ Admin, 🏫 Kepsek (buat/hapus) |
| `/materi/guru/create` | 👨‍🏫 Guru |
| `/materi/persetujuan` | 🛠️ Admin, 🏫 Kepsek |
| `/admin/template` | 🛠️ Admin, 🏫 Kepsek |
| `/pengaturan` | 🛠️ Admin, 👑 Super Admin |
| `/kenaikan-kelas` | 🛠️ Admin, 👑 Super Admin |
| `/rapor-anak` | 👪 Wali Murid |
| `/perkembangan-anak` | 👪 Wali Murid |
| `/jurnal-anak` | 👪 Wali Murid |
| `/materi` | 👪 Wali Murid |

---

> [!TIP]
> **Akun Demo Default — password semua akun: `password`**
> - Owner: `owner@saas.com`
> - Super Admin: (akun yang dibuat saat yayasan disetujui)
> - Admin TK: (dibuat via /superadmin/users)
> - Guru: (dibuat via /superadmin/users)
> - Wali Murid: (dibuat via /y/{kode}/daftar)

---

*Kiddy School ERP — Panduan Penggunaan Sistem v1.0*
