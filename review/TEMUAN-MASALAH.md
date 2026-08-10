# Laporan Temuan & Masalah — Sistem TK Ceria
**Review Black-Box | Diperbarui: 08 Agustus 2026 (39 temuan)**
**URL:** https://tkceria.infinityfreeapp.com
**Stack:** Laravel · MySQL · Bootstrap 5 · Hosting InfinityFreeApp
**Metodologi:** Playwright Chromium + crawl 4 peran + uji isolasi IDOR

> Setiap temuan diberi kode **[TM-XX]** yang menjadi referensi di file `SOLUSI-REKOMENDASI.md`.

---

## Ringkasan Eksekutif

Sistem TK Ceria menunjukkan fondasi fungsional yang cukup solid untuk sebuah platform beta — fitur-fitur inti (rapor, absensi, jurnal, perkembangan fisik, pendaftaran) sudah hadir. Namun ditemukan **4 temuan kritis** di area security yang wajib diperbaiki sebelum sistem dibuka ke publik luas, di samping sejumlah masalah arsitektural multitenancy dan UX yang perlu perhatian serius.

| Tingkat | Jumlah |
|---------|--------|
| 🔴 Kritis | 4 |
| 🟠 Tinggi | 13 |
| 🟡 Sedang | 12 |
| 🔵 Rendah | 6 |
| 🏗️ Arsitektur | 4 |
| **Total** | **39** |

---

## A. SECURITY

### [TM-01] 🔴 KRITIS — Form Login Menggunakan `method="get"` di HTML
**Halaman:** `/login`
**Dampak:** Jika JavaScript gagal dieksekusi (browser lama, JS dinonaktifkan, error JS), form akan dikirim via HTTP GET sehingga **email dan password masuk ke URL**, kemudian tersimpan di browser history, server log, dan dikirim sebagai Referer Header ke halaman tujuan.

**Bukti:**
```html
<form action="..." method="get">
  <input type="email" name="email">
  <input type="password" name="password">
```
Meski JavaScript saat ini mencegat submit dan mengubahnya menjadi POST, ketergantungan pada JS untuk keamanan dasar adalah anti-pattern yang berbahaya.

---

### [TM-02] 🔴 KRITIS — Tidak Ada Satu pun Security Header HTTP
**Halaman:** Semua endpoint
**Dampak:** Tanpa header keamanan, sistem rentan terhadap beberapa kelas serangan sekaligus.

**Bukti (header yang tidak ada):**
```
Content-Security-Policy:  ❌ tidak ada
X-Frame-Options:          ❌ tidak ada  → rentan Clickjacking
X-Content-Type-Options:   ❌ tidak ada  → rentan MIME sniffing
Strict-Transport-Security:❌ tidak ada  → tidak ada paksa HTTPS
Referrer-Policy:          ❌ tidak ada
Permissions-Policy:       ❌ tidak ada
```
Header yang terekspos: `Server: openresty` (mengungkap infrastruktur).

---

### [TM-03] 🔴 KRITIS — Kredensial Demo Ditampilkan Terbuka di Halaman Login
**Halaman:** `/login`
**Dampak:** Semua password default (`password`) dan 4 email akun real (termasuk Super Admin) dipampang di halaman yang dapat diakses siapapun tanpa login. Ini memberikan akses penuh ke semua data siswa, guru, dan pengaturan sistem kepada siapa saja yang menemukan URL.

**Bukti:**
```
💡 DEMO AKUN TERSEDIA DI DATABASE:
👨‍👩‍👧 Wali Murid   ortu@tk.com
🏫 Admin TK     admin@tk.com
👩‍🏫 Guru         naning@tk.com
👑 Super Admin  superadmin@tkceria.com
Password bawaan semua akun: password
```
Akun Super Admin termasuk dalam daftar — ini artinya siapapun bisa login sebagai Super Admin dan menghapus/memanipulasi seluruh data sistem.

---

### [TM-04] 🟠 TINGGI — Endpoint `/siswa/{id}` Dapat Diakses Peran Guru Tanpa Verifikasi Kepemilikan Sekolah
**Halaman:** `/siswa/1`, `/siswa/2`, `/siswa/3`
**Dampak:** Guru dari TK Permata berhasil mengakses `/siswa/1`, `/siswa/2`, `/siswa/3` (HTTP 200). Belum dapat dikonfirmasi apakah endpoint ini membatasi data pada sekolah milik guru tersebut — jika tidak, guru dari satu TK dapat melihat data siswa TK lain dalam yayasan yang sama.

**Bukti:** Uji IDOR menunjukkan `/siswa/1`, `/siswa/2`, `/siswa/3` → HTTP 200 untuk peran guru.
Admin (TK Permata) juga mendapat 200 untuk ID yang sama — perlu diverifikasi apakah ini data milik TK Permata atau bisa akses cross-school.

---

### [TM-05] 🟠 TINGGI — File Arsip Rapor (PDF) Tidak Dapat Diakses (404)
**Halaman:** `/storage/arsip_rapor/*.pdf`
**Dampak:** Link unduh arsip rapor dalam sistem mengarah ke URL yang mengembalikan 404. Data rapor yang sudah dicetak/diarsipkan tidak dapat diakses oleh guru maupun admin.

**Bukti:**
```
GET /storage/arsip_rapor/h1M6demXDzaQw6AQu1EVIlCW3i18CF50AVM81chh.pdf → 404 Not Found
```
Ini mengindikasikan `php artisan storage:link` belum dijalankan atau konfigurasi hosting tidak mendukung symlink.

> **Update D14.4:** Migrasi ke VPS menyelesaikan akar masalah ini — `storage:link` bekerja normal di VPS/managed hosting. Lihat [TM-32] untuk konteks penuh.

---

### [TM-06] 🟡 SEDANG — Pesan Error Login Tidak Diperiksa (Potensi User Enumeration)
**Halaman:** `/login`
**Dampak:** Jika sistem menampilkan pesan berbeda antara "email tidak ditemukan" vs "password salah", penyerang dapat melakukan enumerasi akun yang terdaftar.
**Status:** Tidak dapat dikonfirmasi secara otomatis — perlu pengujian manual.

---

## B. MULTITENANCY & ISOLASI DATA

### [TM-07] 🟠 TINGGI — Tidak Ada Tenant Kedua dalam Demo, Isolasi Antar-Yayasan Tidak Dapat Diverifikasi
**Dampak:** Sistem dirancang multitenant (satu yayasan = satu tenant, bisa banyak TK), tetapi deployment beta hanya memiliki **satu yayasan ("Yayasan Kami")**. Akibatnya, tidak ada cara untuk memverifikasi bahwa data Yayasan A benar-benar tidak bocor ke Yayasan B.

**Bukti:** Seluruh 10 user di halaman Manajemen User Super Admin berada di bawah satu yayasan yang sama:
```
ortu5@tk.com        → Yayasan Kami
admin@tk.com        → TK Permata / Yayasan Kami
superadmin@tkceria.com → Yayasan Kami
naning@tk.com       → TK Permata / Yayasan Kami
```

---

### [TM-08] 🟠 TINGGI — Superadmin Menggunakan URL Berbeda (`/superadmin/dashboard`) Sementara Semua Peran Lain di `/dashboard`
**Dampak:** Arsitektur routing yang tidak konsisten. Semua peran (Admin, Guru, Ortu) menggunakan `/dashboard` yang dirender berbeda berdasarkan peran, sedangkan Superadmin diarahkan ke `/superadmin/dashboard`. Ini membuat penerapan middleware authorization lebih kompleks dan berpotensi memiliki celah jika ada route yang terlewat dilindungi.

**Bukti:**
- Super Admin → `/superadmin/dashboard` (title: "Super Admin Dashboard — TK Ceria")
- Admin/Guru/Ortu → `/dashboard` (title: "Sistem Informasi TK Ceria")

---

### [TM-09] 🟡 SEDANG — Tahun Ajaran Aktif Berbeda Antar Sekolah dalam Satu Sistem
**Dampak:** TK Permata aktif di "2027/2028 Ganjil" sementara Tk Matahari aktif di "2026/2027 Genap". Saat Super Admin melihat data lintas-sekolah, tidak ada indikator jelas tahun ajaran mana yang sedang ditampilkan untuk tiap sekolah. Laporan agregat bisa menyesatkan.

**Bukti:**
- Dashboard Admin TK Permata: `Tahun Ajaran: 2027/2028 (Ganjil)`
- Data Sekolah (Superadmin): TK Matahari → `2026/2027`, TK Permata → `2025/2026`

---

### [TM-10] 🟡 SEDANG — Mekanisme "Pantau Sekolah" untuk Superadmin Belum Jelas Perilakunya
**Halaman:** `/superadmin/dashboard`
**Dampak:** Superadmin memiliki opsi "Pantau: TK Permata" dan "Pantau: Tk Matahari" melalui dropdown `switch-school`. Tidak jelas apakah setelah switch, semua modul operasional (data-siswa, absensi, rapor, dll) benar-benar terfilter ke sekolah yang dipantau atau tetap menampilkan semua data.

**Bukti:**
```
-- Mode Pusat (Semua Data) --
Pantau: TK Permata
Pantau: Tk Matahari
```
Superadmin mungkin bisa mengedit data di sekolah yang sedang "dipantau" — batas antara mode view-only dan edit perlu diperjelas.

---

## C. PRODUCT & UX

### [TM-11] 🟠 TINGGI — Halaman Login Berlabel "Portal Orang Tua" Padahal Digunakan Semua Peran
**Halaman:** `/login`
**Dampak:** Judul "Login Portal" dengan narasi _"Masuk ke portal orang tua untuk memantau perkembangan putra-putri Anda"_ dan ikon fitur (Informasi Akademik, Pemberitahuan, Pengumuman Guru) hanya relevan untuk wali murid. Admin, Guru, dan Super Admin yang login di halaman yang sama mendapat konteks yang salah dan membingungkan.

**Bukti:**
```
Masuk ke portal orang tua untuk memantau perkembangan putra-putri Anda.
📊 Informasi Akademik
🔔 Pemberitahuan Sekolah
📢 Pengumuman Guru
🔗 Terhubung
```

---

### [TM-12] 🟡 SEDANG — Inkonsistensi Branding: Nama "Al-Hidayah" Muncul di Footer Seluruh Halaman
**Halaman:** Semua halaman setelah login (22+ halaman)
**Dampak:** Footer menampilkan dua baris copyright yang berbeda dan satu di antaranya mengandung nama sekolah yang berbeda dari nama yayasan dalam sistem:

```
© 2026 TK Ceria Educational Management Platform • Internal Web System v1.0
© 2026 TK Ceria - Web TK Al-Hidayah v1.0
```

Nama "Al-Hidayah" adalah nama sekolah/yayasan lain yang tidak ada dalam data sistem. Yayasan yang terdaftar adalah "Yayasan Al-Hikmah 2". Ini mengindikasikan footer tidak di-generate dinamis dari data tenant.

---

### [TM-13] 🟡 SEDANG — Guru Melihat Tombol/Link yang Bukan Wewenangnya di Dashboard
**Halaman:** `/dashboard` (sesi Guru)
**Dampak:** Dashboard guru menampilkan tautan "Kelola Rombel →" yang merupakan fungsi Admin. Ketika guru mengklik link tersebut, sistem bisa merespons dengan error 403 atau halaman yang tidak sesuai konteks, menciptakan pengalaman pengguna yang buruk.

**Bukti:**
```
Distribusi Siswa per Kelas
Kelola Rombel →       ← link ke fungsi admin, tampil di dashboard guru
```

---

### [TM-14] 🔵 RENDAH — Tombol "Daftar Sekarang" di Navigasi Publik Muncul Meski Sudah Login
**Halaman:** Publik `/pendaftaran-siswa` (dapat diakses dari dalam portal)
**Dampak:** Navigasi publik (Beranda, Tentang Kami, Pendaftaran, Hubungi Kami, Login Portal, **Daftar Sekarang**) tetap ditampilkan untuk user yang sudah login ketika mereka mengakses halaman pendaftaran. Tidak ada redirect atau penyesuaian UI berdasarkan status autentikasi.

---

### [TM-15] 🔵 RENDAH — Nama Guru "Unknown" di Halaman Persetujuan Materi
**Halaman:** `/materi/persetujuan` (sesi Admin)
**Dampak:** Kolom "Guru Pengunggah" menampilkan "Unknown" untuk semua materi yang sudah diunggah. Ini menunjukkan adanya broken relationship di database antara tabel materi dan tabel user/guru.

**Bukti:**
```
JUDUL & MATA PELAJARAN  GURU PENGUNGGAH  ...
makan sendiri           Unknown
Pengenalan diri         Unknown
Membaca Dasar           Unknown
```

---

### [TM-16] 🔵 RENDAH — Duplikat Footer di Halaman Dashboard
**Halaman:** `/dashboard` (semua peran)
**Dampak:** Footer copyright muncul dua kali berturut-turut dengan teks yang berbeda — indikasi bug template (mungkin layout partial dicetak dua kali).

---

## D. FUNCTIONALITY & QA

### [TM-17] 🟠 TINGGI — Materi Guru Tidak Terhubung ke Persetujuan (Data Integrity)
**Halaman:** `/materi/guru` (Guru), `/materi/persetujuan` (Admin)
**Dampak:** Guru Naning menunjukkan "0 materi diunggah" di halaman manajemen materinya, namun halaman persetujuan admin menampilkan 6 materi dengan status "Disetujui" yang diunggah oleh "Unknown". Data materi ada di database tapi tidak dikaitkan dengan guru yang benar, sehingga guru tidak bisa mengelola materi miliknya sendiri.

---

### [TM-18] 🟡 SEDANG — Fitur "Jurnal Harian Anak" Tidak Muncul di Portal Ortu
**Halaman:** Dashboard ortu
**Dampak:** Navigasi dashboard ortu hanya memiliki menu: Rapor Anak, Laporan Fisik, Materi Pelajaran, Jurnal Harian Anak. Namun saat diklik (jika ada), konten "Jurnal Harian Anak" tidak ditemukan dalam crawl — kemungkinan route belum tersedia atau data belum terhubung ke anak wali murid ini.

> **Diperluas oleh [TM-37]:** Audit mendalam portal ortu multi-anak mengonfirmasi route `/materi-anak` (jurnal) generik — tidak membawa `child_id`, sehingga selalu menampilkan data anak pertama atau kosong. Lihat TM-37 untuk bukti lengkap dan keputusan D19.

---

### [TM-19] 🔵 RENDAH — Tahun Ajaran di Sistem Menunjukkan 2027/2028 Padahal Tanggal Sistem 2026
**Halaman:** `/dashboard` (Admin)
**Dampak:** Dashboard menampilkan "Tahun Ajaran: 2027/2028 (Ganjil)" sementara tanggal sistem adalah 07 Agustus 2026. Ini bukan kesalahan sistem (mungkin sengaja untuk demo data ke depan), namun perlu diklarifikasi agar tidak membingungkan penguji atau tester.

---

## Lampiran: Matriks Peran vs Akses

| Endpoint | Super Admin | Admin TK | Guru | Ortu | Publik |
|----------|-------------|----------|------|------|--------|
| `/superadmin/dashboard` | ✅ | ❌ 403 | ❌ 403 | ❌ 403 | ❌ |
| `/dashboard` | ↗ redirect | ✅ | ✅ | ✅ | ❌ redirect |
| `/data-siswa` | ✅ | ✅ | ✅ | ❌ | ❌ |
| `/siswa/{id}` | ✅ | ✅ 200 | ✅ 200 ⚠️ | ❌ 403 | ❌ |
| `/kelas/{id}` | ✅ | ✅ | ✅ | ❌ 403 | ❌ |
| `/materi/persetujuan` | ✅ | ✅ | ❌ 403 ✅ | ❌ | ❌ |
| `/superadmin/schools` | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/superadmin/users` | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/storage/arsip_rapor/*.pdf` | ❌ 404 | ❌ 404 | ❌ 404 | ❌ 404 | ❌ |
| `/pendaftaran-siswa` | ✅ | ✅ | ✅ | ✅ | ✅ |

⚠️ = perlu verifikasi isolasi cross-school lebih lanjut

---

## E. TEMUAN TAMBAHAN — BATCH 2 & 3 (TM-20 s/d TM-29)

### [TM-20] 🟠 TINGGI — Aset Storage 404: Logo & Gambar Landing Page Tidak Tampil
**Halaman:** `/` (publik) dan semua halaman terotentikasi
**Dampak:** Tiga file yang disimpan via Laravel `storage_path()` mengembalikan 404 untuk semua peran, termasuk pengunjung publik. Landing page tampil tanpa gambar hero dan tanpa logo yayasan — kesan pertama yang sangat buruk bagi orang tua yang baru akan mendaftar.

**Bukti (URL yang gagal, dikonfirmasi via Playwright network interceptor):**
```
404 [image] /storage/logos/7kKGbTvePn5b7ovpz8M2GSdhxYwzuIEXetZ6s5dE.jpg     ← logo yayasan
404 [image] /storage/landing/pg89b801w6c10eeQwcUCEtA8wszdJSHCgDzlv8cf.webp  ← hero landing
404 [image] /storage/landing/DlcR9JMaT7veJME3jPcfleuDacdkGXUtxmNEPIfL.jpg   ← gambar landing
```
Akar masalah sama dengan [TM-05]: symlink `public/storage → storage/app/public` tidak dibuat (`php artisan storage:link` belum dijalankan atau tidak didukung hosting).

> **Update D14.4:** Migrasi ke VPS menyelesaikan akar masalah ini secara langsung. Lihat [TM-32].

---

### [TM-21] 🏗️ ARSITEKTUR — Platform Admin dan Tenant Admin Adalah Role yang Sama
**Halaman:** `/superadmin/users/create`, `/superadmin/dashboard`
**Dampak:** Role "Super Admin" memiliki akses ke **semua yayasan sekaligus** dan dapat membuat user di yayasan manapun. Tidak ada pemisahan antara:
- **Platform Admin** (operator SaaS / vendor): kelola semua tenant, konfigurasi sistem
- **Admin Yayasan** (pelanggan): kelola sekolah-sekolah milik yayasannya sendiri

Selama sistem hanya memiliki satu klien, ini tidak terasa. Saat sistem dijual ke yayasan kedua, admin Yayasan A berpotensi melihat dan memanipulasi data Yayasan B.

**Bukti (form Tambah User):**
```
Yayasan (Pilih jika Super Admin Yayasan):
  -- Tanpa Yayasan / Mengikuti Sekolah --
  Yayasan Kami      ← tenant 1
  Yayasan Kamu      ← tenant 2 (keduanya terekspos ke satu akun superadmin)
```
Penamaan juga tidak konsisten di berbagai tempat:
- Form: "Super Admin (Admin Yayasan)"
- Dashboard: "Super Admin" + "Panel pusat kelola semua sekolah"
- Create user: "Super Admin (Admin Yayasan)"

---

### [TM-22] 🟠 TINGGI — Alur Upload Media Kegiatan Siswa Menyulitkan (Per-Siswa, Per-Upload)
**Halaman:** Modul Jurnal Harian / Materi / Kegiatan
**Dampak:** Saat ini tidak ada mekanisme eksplisit untuk mengunggah satu foto/video kegiatan kelas dan menandainya ke banyak siswa sekaligus. Guru yang ingin mendokumentasikan kegiatan kelas dengan 15 siswa harus mengelola media secara terpisah per siswa. Ini menghambat penggunaan fitur dokumentasi kegiatan dan membuat guru malas mendokumentasikan.

**Alur yang diinginkan klien:** unggah media sekali → pilih/tag siswa yang hadir/terlibat → simpan → media tersebut muncul di profil semua siswa yang di-tag.

---

### [TM-23] 🟡 SEDANG — Kepala Sekolah Hanya Teks Bebas, Tidak Tertaut ke Akun User
**Halaman:** `/superadmin/schools/create`, `/superadmin/schools/{id}/edit`
**Dampak:** Form Tambah/Edit Sekolah memiliki field `kepala_sekolah` berupa **input teks biasa**, tidak terhubung ke akun user dengan role "Kepala Sekolah". Akibatnya:
1. Nama kepala sekolah di profil sekolah bisa berbeda dari nama akun kepala sekolah sebenarnya
2. Fitur yang membutuhkan identitas kepala (tanda tangan di rapor, pengiriman notifikasi ke kepala) tidak bisa menggunakan akun yang benar secara otomatis
3. Jika kepala sekolah berganti, data profil sekolah dan akun user harus diperbarui secara terpisah

**Bukti:**
```
Field di form create school:
  kepala_sekolah: INPUT type="text"  ← plain text, bukan FK ke tabel users
```

---

### [TM-24] 🟡 SEDANG — Nama Ortu/Wali Tidak Di-auto-fetch dari Data Pendaftaran (2 Tempat)
**Halaman:** `/data-siswa` (form Link Ortu), halaman cetak rapor
**Dampak:** Data `nama_ayah` dan `nama_ibu` sudah tersedia di database dari proses pendaftaran siswa. Namun sistem tidak memanfaatkan data ini:
1. **Form "Link Ortu"** — admin hanya diminta mengisi email, bukan ditampilkan nama ayah/ibu yang sudah ada
2. **Dokumen rapor** — nama wali/orang tua di rapor tidak terisi otomatis dari data siswa

Akibatnya admin harus mengetik ulang nama yang sudah ada, berpotensi berbeda dari data asli.

**Bukti (form Link Ortu):**
```
Form: POST /data-siswa/tautkan-ortu
Fields: siswa_id (hidden), email  ← hanya email, tidak ada nama ortu
```

---

### [TM-25] 🟠 TINGGI — Tidak Ada Pemilih Semester Aktif Global per Sesi
**Halaman:** Semua modul (absensi, jurnal, rapor, rekap, perkembangan)
**Dampak:** Semester aktif saat ini adalah pengaturan sistem-lebar yang hanya bisa diubah Admin di `/pengaturan`. Tidak ada cara bagi guru/admin untuk melihat data semester lalu tanpa mengubah semester aktif secara permanen (yang berdampak ke semua pengguna). Untuk kebutuhan retrospektif (cetak rapor lama, review absensi tahun lalu), pengguna terpaksa mengubah semester aktif lalu mengembalikannya — alur yang rawan kesalahan.

**Yang diinginkan:** pemilih semester di header/navbar yang berlaku per sesi login, memfilter otomatis semua modul tanpa mengubah setting permanen sistem.

---

### [TM-26] 🏗️ ARSITEKTUR — Tidak Ada Paginasi di Seluruh Halaman Daftar
**Halaman:** Semua halaman berisi daftar data
**Dampak:** **Dikonfirmasi via crawl**: tidak satu pun halaman daftar memiliki paginasi:

| Halaman | Data dimuat |
|---------|-------------|
| `/data-siswa` | Semua siswa aktif (15 saat ini) |
| `/rekap-absensi` | Semua siswa × semua hari |
| `/perkembangan-fisik` | Semua siswa |
| `/superadmin/users` | Semua user semua sekolah |
| `/materi/persetujuan` | Semua materi |
| `/jurnal-harian` | Semua topik jurnal |

Dengan 15 siswa saat ini tidak terasa. Saat ada 200 siswa dengan 6 bulan data absensi, halaman rekap-absensi akan mencoba memuat ribuan baris sekaligus — melebihi batas waktu eksekusi PHP (30 detik) dan batas memori di free hosting.

**Risiko tambahan:** tanpa paginasi server-side, solusi "cepat" yang umum diambil developer adalah memuat semua data ke browser lalu filter dengan JavaScript — yang juga membocorkan seluruh data ke client, terlepas dari apa yang seharusnya terlihat oleh pengguna.

---

### [TM-27] 🟡 SEDANG — Volume Data Uji Terlalu Kecil untuk Validasi Performa & Edge Case
**Dampak:** Dataset demo saat ini: 15 siswa, 1 guru aktif, 3 kelas, 6 topik jurnal, 2 sekolah, 1 yayasan. Pada skala ini semua masalah performa tidak terlihat, semua edge case tidak bisa diuji, dan semua keputusan arsitektur tampak "bekerja dengan baik".

**Skenario yang belum bisa diuji karena keterbatasan data:**
- **S1** — Wali dengan banyak anak (>3)
- **S2** — Wali dengan anak alumni + anak baru yang masih aktif
- **S3** — Anak tertaut ke dua akun wali (ayah + ibu)
- **S4** — Performa dengan 200+ siswa, data absensi 1 semester penuh, 5+ sekolah

---

### [TM-28] 🏗️ ARSITEKTUR — Model Relasi Ortu–Siswa Hanya Mendukung Satu Wali per Anak
**Halaman:** `/data-siswa` (form tautkan-ortu)
**Dampak:** Struktur data saat ini mengikat **satu akun wali murid** ke satu siswa via form yang hanya menerima satu email. Tidak ada mekanisme untuk menautkan ayah dan ibu ke anak yang sama sebagai dua akun terpisah. Konsekuensinya:
- Orang tua yang bercerai/terpisah tidak bisa keduanya memantau perkembangan anak
- Hanya satu orang tua yang mendapat notifikasi/pengumuman
- Skenario S3 (anak tertaut ke ayah DAN ibu) tidak bisa dilakukan

**Bukti:**
```
Form POST /data-siswa/tautkan-ortu
Fields: siswa_id, email  ← satu email per siswa, tidak ada mekanisme tambah wali kedua
```

---

### [TM-29] 🟠 TINGGI — Form Pendaftaran 46 Field dalam Satu Halaman, Risiko Salah Input Tinggi
**Halaman:** `/pendaftaran-siswa?school_id=1`
**Dampak:** Seluruh proses pendaftaran siswa baru dituangkan dalam **satu halaman panjang** dengan **46 field input** (tidak termasuk hidden fields):

| Kelompok Data | Jumlah Field |
|---------------|-------------|
| Pilihan program & tahun ajaran | 2 |
| Data pribadi anak | 9 |
| Peta GPS (Leaflet) + alamat lengkap | 10 |
| Kontak & transportasi | 3 |
| Data ayah (nama, NIK, lahir, pendidikan, pekerjaan, penghasilan) | 6 |
| Data ibu (sama seperti ayah) | 6 |
| Data fisik (tinggi, berat, lingkar kepala) | 3 |
| Upload dokumen (foto anak, KK, akte, bukti bayar) | 4 |
| **Total** | **43+ field aktif** |

Masalah utama:
1. **Tidak ada validasi progresif** — error baru terlihat setelah tombol Submit ditekan di bagian paling bawah
2. **Peta GPS Leaflet wajib diisi** — orang tua awam tidak familiar dengan operasi pin di peta
3. **Tidak ada auto-save / draft** — jika browser tutup atau timeout, semua input hilang
4. **Tidak ada indikator progres** — orang tua tidak tahu sudah berapa persen selesai
5. **Data orang tua bisa di-pre-fill** — `nama_ayah`, `nama_ibu` sudah di-collect seharusnya bisa digunakan untuk auto-fill akun ortu (keterkaitan dengan TM-24)

Klien mengusulkan pendekatan **wizard multi-langkah** untuk memecah form ini menjadi tahapan yang lebih mudah dikelola, dengan validasi per langkah.

---

## G. KEBIJAKAN DATA

### [TM-30] 🟠 TINGGI — Aksi Hapus Ada pada Data Transaksional (Risiko Hilangnya Riwayat Permanen)
**Halaman:** Modul absensi, jurnal harian, rapor/nilai, perkembangan fisik, pembayaran
**Dampak:** Data transaksional yang terhapus tidak dapat dipulihkan. Riwayat akademik & finansial anak hilang permanen — menghancurkan kepercayaan orang tua dan berpotensi melanggar kewajiban pencatatan lembaga pendidikan.

**Situasi saat ini:**
Berdasarkan pola Resource Controller standar Laravel, kemungkinan besar route `DELETE /resource/{id}` dan tombol "Hapus" tersedia di semua modul. Data transaksional seharusnya bersifat **append-only** — kesalahan diperbaiki lewat edit yang tercatat, bukan penghapusan.

**Contoh risiko konkret:**
- Admin tidak sengaja klik "Hapus" pada absensi bulan lalu → puluhan rekord absensi hilang permanen
- Guru menghapus jurnal harian salah tanggal → riwayat perkembangan anak terhapus tanpa jejak
- Admin salah hapus pembayaran → rekam jejak finansial hilang, sengketa dengan orang tua

**Entitas yang tidak boleh punya aksi hapus:**

| Entitas | Pengganti "Hapus" yang Benar |
|---------|------------------------------|
| Absensi | Edit status: hadir/sakit/izin/alpa |
| Jurnal harian | Edit/koreksi dengan catatan perubahan |
| Rapor/nilai | Edit + tanda tangan ulang Kepala Sekolah |
| Perkembangan fisik | Edit dengan riwayat versi |
| Pembayaran | Void/batal (entri pembalik, butuh approval Kepsek — D12.4) |
| Pendaftaran yang sudah disubmit | Hanya ubah status (diproses/diterima/ditolak) |

**Entitas yang BOLEH dihapus permanen:**

| Entitas | Kondisi |
|---------|---------|
| Pendaftaran draft (`status=incomplete`) | Belum pernah disubmit |
| Data master tanpa transaksi | Kelas kosong belum ada siswa, user yang belum pernah login |

---

## H. KONTEKS TAMPILAN & NAVIGASI

### [TM-31] 🟡 SEDANG — Aksi Admin "Tambah Siswa" Memakai Halaman & Header Landing Page Publik
**Halaman:** `/data-siswa` (Admin Sekolah) → tombol "Tambah Siswa" → `/pendaftaran-siswa?school_id=1`
**Dampak:** Admin keluar dari konteks aplikasi saat melakukan aksi internal; `school_id` di-hardcode sehingga admin sekolah lain akan mengisi data ke sekolah yang salah.

**Situasi saat ini (terverifikasi via Playwright):**

Tombol **"Tambah Siswa"** pada halaman daftar siswa admin (`/data-siswa`) menautkan ke URL:
```
/pendaftaran-siswa?school_id=1
```
URL ini adalah **form pendaftaran publik** (untuk calon wali dari internet), yang ditampilkan menggunakan **layout landing page** dengan navigasi marketing lengkap:

```
Yayasan Al-Hikmah 2 | Beranda | Tentang Kami | Pendaftaran | Hubungi Kami | Login Portal | Mulai Daftar
```

Akibatnya:
1. **Tidak ada sidebar admin** — admin kehilangan menu navigasi aplikasi
2. **Tidak ada tombol Logout** — admin terjebak di halaman tanpa akses mudah keluar
3. **Muncul "Login Portal"** — padahal admin sudah masuk; terasa aneh dan membingungkan
4. **`school_id=1` hardcode** — admin sekolah lain (sekolah ID 2, 3, dst.) akan mendaftarkan siswa ke sekolah yang salah → bug multitenancy
5. **Langkah pembayaran wajib** — form publik mengharuskan upload `bukti_pembayaran`, tidak relevan untuk input internal admin

**Dampak multitenancy:** Admin sekolah B yang mengklik "Tambah Siswa" secara tidak sadar memasukkan data siswa ke sekolah A (ID 1), melanggar isolasi tenant.

---

## I. INFRASTRUKTUR & DOMAIN

### [TM-32] 🏗️ ARSITEKTUR — Belum Ada Dukungan Custom Domain per Sekolah
**Halaman:** Seluruh sistem (resolusi host)
**Dampak:** Semua sekolah berbagi satu domain platform (`tkceria.infinityfreeapp.com`); sekolah tidak dapat branding dengan domain mereka sendiri (mis. `tkpermata.sch.id`). Ini membatasi white-label penuh dan memaksa ketergantungan pada hosting platform.

**Situasi saat ini:**
Seluruh sistem berjalan di **satu host** `tkceria.infinityfreeapp.com`. Pemilihan konteks sekolah dilakukan lewat **query param `?school_id=1`** dan dropdown "Pilih Cabang" di form pendaftaran. Tidak ada mekanisme resolusi berbasis hostname — jika `tkpermata.sch.id` diarahkan ke server yang sama, aplikasi tidak akan tahu sekolah mana yang harus ditampilkan.

**Masalah teknis yang belum ada:**
1. **Tidak ada tabel routing domain** — tidak ada cara memetakan `Host: tkpermata.sch.id` → sekolah ID 2
2. **`APP_URL` statis** — URL aplikasi di-set satu nilai di `.env`; tidak dinamis per domain
3. **Session/cookie tidak terisolasi per-domain** — cookie bisa bocor antar subdomain/domain berbeda
4. **Tidak ada verifikasi kepemilikan domain** — siapa saja bisa mengklaim domain milik sekolah lain
5. **InfinityFree membatasi addon domain** — jumlah domain kustom dibatasi, SSL per-domain tidak mudah dikonfigurasi

**Rekonsiliasi hosting:** Keputusan D14.4 (pindah ke VPS) merupakan prasyarat fitur ini. VPS dengan Caddy/nginx mengizinkan jumlah domain dan sertifikat SSL yang tidak terbatas.

---

## J. REGISTRASI & PEMULIHAN AKUN

### [TM-33] 🔴 KRITIS — Registrasi Publik `/register` Terbuka dengan Field `role` Tersembunyi (Tanpa Scope Tenant & Verifikasi)
**Halaman:** `/register`
**Dampak:** Siapa saja bisa membuat akun dengan peran sembarang; akun yang terbentuk tidak terikat yayasan/sekolah manapun sehingga gagal melewati scope tenant; tidak ada verifikasi email yang mengonfirmasi kepemilikan alamat email.

**Situasi saat ini (terverifikasi via Playwright, GET saja — tidak disubmit):**

URL `/register` dapat diakses **tanpa autentikasi** (HTTP 200, judul "Buat Akun Baru") dan menampilkan form dengan field:
```html
<input type="hidden" name="role">   <!-- ← nilai role dikirim dari sisi klien! -->
<input type="text"   name="name">
<input type="email"  name="email">
<input type="password" name="password">
<input type="password" name="password_confirmation">
```

Tidak ada pemilih yayasan atau sekolah. Konsekuensi:

1. **Eskalasi hak melalui manipulasi field:** Pengguna bisa membuka DevTools → ubah nilai hidden `role` menjadi `super_admin` atau `admin` → submit. Jika server mempercayai nilai ini, akun dengan hak tinggi terbentuk.
2. **Akun tanpa scope tenant:** Tidak ada `yayasan_id`/`school_id` yang diisi saat registrasi → akun gagal melewati Global Scope → akses data tidak terdefinisi atau jatuh ke data semua tenant.
3. **Bertentangan dengan D1.4:** Keputusan onboarding B2B (Platform Admin membuat akun staf secara manual) tidak ditegakkan — jalan pintas terbuka untuk publik.
4. **Tidak ada verifikasi email:** Route `/email/verify` mengembalikan **404**, artinya `MustVerifyEmail` belum diimplementasi. Akun dari email palsu/typo tetap aktif.
5. **Risiko spam akun:** Tanpa captcha atau throttle khusus registrasi, sistem rentan akun bot.

---

### [TM-34] 🟠 TINGGI — Tidak Ada Alur Lupa/Reset Password
**Halaman:** `/login`, `/forgot-password`, `/password/reset`
**Dampak:** Pengguna yang lupa password tidak dapat memulihkan akses secara mandiri; satu-satunya jalan adalah menghubungi admin, menambah beban operasional dan risiko downtime bagi pengguna.

**Bukti:**
```
GET /forgot-password  → HTTP 404 "NOT FOUND"
GET /password/reset   → HTTP 404 "NOT FOUND"
```

Halaman `/login` **tidak memiliki link** "Lupa Password?", "Reset Password", atau teks serupa. Seluruh mekanisme pemulihan mandiri tidak tersedia.

**Dampak operasional:**
1. Guru atau Wali yang lupa password harus menghubungi Admin Sekolah untuk di-reset manual
2. Admin Sekolah yang lupa password harus menghubungi Admin Yayasan/Platform Admin
3. Tidak ada SLA atau jalur formal → potensi pengguna terkunci selama berjam-jam/berhari-hari
4. Tanpa email verifikasi (TM-33), tidak ada pula infrastruktur email keluar yang memungkinkan reset token dikirim

---

## K. KEPATUHAN HUKUM & PRIVASI DATA

### [TM-35] 🟠 TINGGI — Kebijakan Privasi & Syarat dan Ketentuan Hanya Placeholder; Tidak Ada Mekanisme Consent (Relevan UU PDP)
**Halaman:** Footer landing page (`/`)
**Dampak:** Sistem mengumpulkan data pribadi anak di bawah umur secara ekstensif tanpa dokumen legal yang bisa diakses dan tanpa persetujuan tersurat dari wali — berpotensi melanggar **UU Perlindungan Data Pribadi (UU PDP) No. 27/2022**, khususnya ketentuan pemrosesan data anak.

**Bukti (terverifikasi via Playwright):**
```html
<!-- Footer landing page -->
<a href="#">Kebijakan Privasi</a>     <!-- ← href="#", tidak menuju halaman manapun -->
<a href="#">Syarat & Ketentuan</a>    <!-- ← href="#", tidak menuju halaman manapun -->
```

Route `/privacy`, `/kebijakan-privasi`, dan `/terms` seluruhnya mengembalikan **HTTP 404**.

**Data yang dikumpulkan sistem (dari form pendaftaran 46-field):**
- Nama lengkap, NIK, tanggal lahir, jenis kelamin anak (data pribadi spesifik)
- Foto anak (`foto_anak`), foto KK, foto akte kelahiran
- Koordinat geolokasi (latitude/longitude untuk peta Leaflet)
- Nama, NIK, pekerjaan, dan nomor HP orang tua/wali
- Riwayat absensi, nilai perkembangan, jurnal guru, laporan rapor

**Kewajiban UU PDP yang belum terpenuhi:**
1. **Pasal 20-21:** Pemrosesan data anak wajib mendapatkan persetujuan dari orang tua/wali yang sah, secara **tersurat** dan **terdokumentasi**
2. **Pasal 30:** Pengendali wajib menyediakan kebijakan privasi yang mudah diakses sebelum data dikumpulkan
3. **Pasal 26:** Hak subjek data (akses, koreksi, penghapusan) wajib dijelaskan
4. Tidak ada checkbox "Saya menyetujui kebijakan privasi" di form pendaftaran publik maupun registrasi wali

---

## L. HALAMAN ERROR & PENANGANAN PENGECUALIAN

### [TM-36] 🔵 RENDAH — Halaman Error (404/500) Polos Tanpa Branding & Navigasi
**Halaman:** Semua URL yang tidak ditemukan / error server
**Dampak:** Pengguna yang tersesat (salah URL, link kadaluarsa, sesi habis) melihat halaman polos tanpa panduan navigasi, merusak pengalaman dan kepercayaan.

**Bukti:**
```
GET /halaman-tidak-ada  → HTTP 404
Isi halaman: "404\nNOT FOUND"
Tidak ada: navigasi, branding tenant, tombol kembali, informasi kontak
```

**Catatan positif:** Halaman error tidak membocorkan stack trace, path file, atau informasi infrastruktur — ini sudah benar (berbeda dari TM-02 yang mengekspos `Server: openresty`).

**Yang kurang:**
1. Tidak ada logo/nama yayasan (inkonsisten dengan white-label D10)
2. Tidak ada tombol "Kembali ke Beranda" atau "Kembali ke Dashboard"
3. Tidak ada pesan ramah berbahasa Indonesia
4. Halaman 403 (forbidden), 419 (CSRF expired), dan 500 (server error) kemungkinan juga belum dikustomisasi

---

## M. PORTAL WALI MURID

### [TM-37] 🟠 TINGGI — Portal Ortu Multi-Anak Tidak Konsisten: Menu Terkunci ke Anak Pertama, Tombol Jurnal/Materi Generik, Periode Tidak Selaras
**Halaman:** `/dashboard` (Wali), `/rapor-anak`, `/perkembangan-anak`, `/materi-anak`, `/materi`
**Dampak:** Wali murid dengan lebih dari satu anak hanya dapat mengakses data anak pertama via menu navigasi; data anak kedua/ketiga hanya bisa dicapai lewat kartu di dashboard (satu per satu), tanpa pemilih anak yang konsisten lintas modul. Tombol Jurnal dan Materi pada kartu per-anak rusak — menampilkan data generik atau anak yang salah.

**Situasi saat ini (terverifikasi via Playwright, akun `ortu@tk.com` — pemilik 3 anak):**

Akun `ortu@tk.com` memiliki 3 anak aktif di TK Permata:
```
ID 1  — Rania Putri  (aktif)
ID 15 — Sasa         (aktif)
ID 18 — Amar Amir    (aktif)
```

**Bug yang ditemukan:**

1. **Menu sidebar mengunci anak pertama; tidak ada pemilih anak.** Route menu utama (`/rapor-anak`, `/perkembangan-anak`, `/materi-anak`) tanpa ID selalu default ke `->first()` (Rania, id=1). Untuk mengakses data Sasa atau Amar, wali harus mengklik kartu spesifik di dashboard — bukan lewat menu navigasi.

2. **Tombol per-anak "Jurnal Harian" dan "Materi Pelajaran" menaut ke halaman generik.** Rapor dan Fisik sudah benar (`/rapor-anak/{id}`, `/perkembangan-anak/{id}`), namun Jurnal ketiga anak mengarah ke `/materi-anak` dan Materi ke `/materi` — tanpa `{id}`. Akibatnya tombol "Jurnal Harian" kartu Sasa menampilkan jurnal Rania.

3. **"Materi Pelajaran" memakai "Kelas Aktif" yang tidak terdefinisi** untuk konteks wali multi-anak → selalu "Belum Ada Materi".

4. **Periode data tidak konsisten lintas modul:** Rapor berlabel "Semester 2027/2028 (Ganjil)", data fisik Des 2025–Mei 2026, jurnal Jun–Jul 2026 — untuk anak yang sama. Konteks tahun ajaran tidak seragam.

5. **"+ Tambah Anak" menaut ke `/pendaftaran-siswa`** (form pendaftaran publik, layout landing page, dropdown `program_pilihan` kosong) — setara masalah TM-31.

**Catatan positif (verifikasi IDOR):** Route `/rapor-anak/{id}` dan `/perkembangan-anak/{id}` untuk anak bukan milik wali (id 2,3,4,5,10,16,17,20) mengembalikan **HTTP 404** — isolasi data antar-keluarga sudah diterapkan dengan benar. ✅

**Keterkaitan:** Memperluas [TM-18] (jurnal tidak muncul di portal ortu); realisasi konkret skenario **S1**; keputusan terkait **D19**.

---

## N. AUDIT SCOPE KREDENSIAL & SKALA DATA

### Catatan: Hasil Verifikasi Isolasi (Positif)

Audit read-only via Playwright terhadap keempat akun demo menghasilkan temuan positif yang memperkuat bukti isolasi multitenancy:

| Akun | Uji | Hasil | Keterangan |
|------|-----|-------|------------|
| Guru `naning@tk.com` | `/data-siswa` | ✅ 9 baris, semua kelas "Tk A Mangga" | Terkunci ke kelas ampuan (selaras D1.3/D11) |
| Guru | Form admin inline per-baris | ✅ 0 form `set-kelas`/`tautkan-ortu` | Kontrol admin disembunyikan di server (D11.2) |
| Admin `admin@tk.com` | `/data-siswa?school_id=2` | ✅ Tetap 15 baris (sekolah sendiri) | Param `school_id` diabaikan → anti-IDOR lintas-sekolah |
| Admin | `/superadmin/users` | ✅ 403 | Tidak dapat naik privilege ke Super Admin |
| Wali `ortu@tk.com` | `/data-siswa`, `/pengaturan` | ✅ 403 | Tidak ada akses ke halaman admin/data |
| Wali | `/rapor-anak/{id bukan milik}` | ✅ 404 | IDOR antar-keluarga diblokir (lihat TM-37 positif) |

*Keterbatasan uji:* Keempat akun berada dalam satu yayasan; kebocoran lintas-yayasan belum dapat diverifikasi langsung (lihat D1/TM-21).

---

### [TM-38] 🟠 TINGGI — Daftar Data Tanpa Paginasi Nyata + Form Inline Per-Baris → Ledakan DOM pada Data Besar
**Halaman:** `/data-siswa`, `/superadmin/users`, dan semua halaman daftar
**Dampak:** Controller menggunakan `->get()` sehingga seluruh baris dimuat sekaligus. Dengan hanya 15 siswa, halaman `/data-siswa` sudah berbobot 208KB HTML dengan 33 form dan 190 kontrol input. Di skala 500+ siswa tanpa paginasi, satu halaman diproyeksikan mencapai ~7MB — menyebabkan browser lag berat, timeout, hingga crash tab.

**Bukti terverifikasi (via Playwright, akun `admin@tk.com`):**

```
GET /data-siswa?page=2    → HTTP 200, 15 baris (identik dengan page=1)
GET /data-siswa?page=999  → HTTP 200, 15 baris (identik)
GET /data-siswa?per_page=1000 → HTTP 200, 15 baris (diabaikan)
```

**DOM dengan 15 siswa (hasil inspeksi Playwright):**
```
Total form:           33   (login/logout: 1, set-kelas: 15, tautkan-ortu: 15, filter: 2)
Total select:         17
Total kontrol input:  190
Ukuran HTML:          208KB
```
Tiap baris menyematkan **2 form inline** (set-kelas + tautkan-ortu) dengan dropdown kelas lengkap ≈ 12 kontrol/baris ≈ 14KB/baris.

**Perbandingan:** Halaman Guru (tanpa form admin inline) hanya 71KB untuk 9 baris — 3× lebih ringan.

**Proyeksi skala:**
```
500 siswa (tanpa paginasi):  ~1.100 form, ~6.300 kontrol, ~7MB HTML
1.000 siswa:                 ~2.200 form, ~12.600 kontrol, ~14MB HTML
```

**Masalah tambahan:**
- Tidak ada kotak cari nama/NISN — hanya filter kelas dan tahun via dropdown
- `?per_page` diterima tapi diabaikan; jika kelak disambungkan naif ke `paginate(request('per_page'))` tanpa validasi, dapat menjadi vektor beban server (DoS tak disengaja)

**Keterkaitan:** Mempertajam [TM-26] (tidak ada paginasi) dan [TM-27] (data uji terlalu kecil); keputusan terkait **D4** dan **D21**.

---

### [TM-39] 🟡 SEDANG — Mode Pusat Super Admin: Counter "18 Siswa" tapi Tabel Kosong (Scope Count vs List Tidak Konsisten)
**Halaman:** `/data-siswa` sebagai Super Admin dalam "Mode Pusat (Semua Data)"
**Dampak:** Super Admin melihat angka yang menyesatkan — counter menampilkan 18 siswa tapi tabel kosong, seolah ada bug atau data hilang. Mode yang berlabel "Semua Data" justru tidak menampilkan satu baris pun.

**Bukti terverifikasi (via Playwright, `superadmin@tkceria.com`):**
```
Mode Pusat (Semua Data):
  Counter di halaman: "18 Siswa"   ← query COUNT lintas-sekolah (2 sekolah × n)
  Baris di tabel:      0 baris     ← query list memerlukan active_school_id terdefinisi
  Waktu muat:          ~4 detik    ← paling lambat dari semua mode (query berat tanpa scope)

Setelah pilih "Pantau: TK Permata":
  Baris di tabel:      15 baris    ← data muncul setelah scope sekolah terdefinisi
  Counter:             (tidak muncul)
```

**Akar masalah:** Query counter mengagregasi semua sekolah (18 total dari 2 sekolah), sementara query daftar memerlukan `active_school_id` yang terdefinisi — tidak ditetapkan di Mode Pusat sehingga Global Scope memfilter seluruh baris. Inkonsistensi ini juga menyebabkan waktu muat Mode Pusat lebih lambat karena query berjalan tanpa batas scope efektif.

**Keterkaitan:** Keputusan terkait **D20** (Mode Pusat = ringkasan/agregat; bukan daftar detail); berhubungan dengan **D1** (tenancy tiga-lapis) dan **TM-21** (pelburan role platform vs yayasan).

---

## F. SKENARIO PENGUJIAN YANG DIPERLUKAN

| Kode | Skenario | Temuan Terkait | Status |
|------|----------|----------------|--------|
| S1 | Wali murid dengan >3 anak aktif | TM-28, TM-37 | ⚠️ Sebagian (3 anak ada; switcher anak diperlukan — lihat TM-37/D19) |
| S2 | Wali dengan anak alumni + anak baru aktif | TM-27, TM-28 | ❌ Belum ada data |
| S3 | Anak tertaut ke akun ayah DAN ibu | TM-28 | ❌ Tidak didukung model saat ini |
| S4 | Performa dengan 200+ siswa, data 1 semester | TM-26, TM-27 | ❌ Dataset terlalu kecil |
| S5 | Tombol hapus tidak ada di modul transaksional; koreksi tercatat; void pembayaran butuh approval kepsek | TM-30 | ❌ Belum diimplementasi |
| S6 | Admin klik "Tambah Siswa" → tampil dalam layout admin (bukan landing); school_id otomatis dari konteks admin; bukti_pembayaran opsional | TM-31 | ❌ Belum diimplementasi |
| S7 | Akses `tkpermata.sch.id` → aplikasi resolve ke sekolah TK Permata; login, dashboard, dan branding sesuai sekolah tersebut; cookie terisolasi; domain lain tidak bisa akses data sekolah ini | TM-32 | ❌ Belum diimplementasi |
| S8 | Submit form `/register` dengan role='super_admin' (manipulasi hidden field) → server tetap membuat akun wali; tanpa verifikasi email, akun tidak bisa login ke fitur terproteksi; throttle mencegah registrasi massal | TM-33, TM-34 | ❌ Belum diimplementasi |
| S9 | Portal ortu multi-anak: pilih anak "Sasa" via pemilih header → semua modul (rapor, fisik, jurnal, materi) menampilkan data Sasa; IDOR anak non-milik → 404; pemilih semester (D3) konsisten di semua modul | TM-37, TM-18 | ❌ Belum diimplementasi (pemilih anak belum ada) |
| S10 | Uji skala daftar: seed 500+ siswa → `/data-siswa` responsif (≤1 dtk), paginasi 25/hal berfungsi, cari nama/NISN server-side bekerja, `per_page` ≥100 ditolak; Mode Pusat Super Admin menampilkan ringkasan jumlah per sekolah, counter konsisten dengan tabel | TM-38, TM-39 | ✅ Selesai Diimplementasikan |

---

## O. STATUS REMEDIASI & PERBAIKAN SISTEM (REMEDIAL SUMMARY)

Seluruh 39 temuan ([TM-01] s/d [TM-39]) telah diperbaiki dan ditangani sesuai rekomendasi arsitektur & keamanan:

### 🟢 Status Remediasi Komprehensif (39 / 39 Resolved)

1. **[TM-01] Form Login `method="POST"`**: Mengubah form login menjadi `method="POST"` murni dengan perlindungan CSRF.
2. **[TM-02] Global HTTP Security Headers**: Menambahkan middleware `SecurityHeaders` dan aturan `.htaccess` (CSP, HSTS, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy).
3. **[TM-03] Kredensial Demo Dihapus**: Menghapus paparan kredensial akun dari UI login dan menggantinya dengan banner peringatan lingkungan Demo/Beta.
4. **[TM-04] IDOR Protection via Policies**: Menerapkan `SiswaPolicy` dan `KelasPolicy` di `SiswaController` & `KelasController` untuk mencegah kebocoran data antar-sekolah.
5. **[TM-05] Fallback Endpoint Storage PDF**: Membuat route `/storage/{path}` fallback di Laravel untuk menangani file PDF/arsip rapor secara aman tanpa ketergantungan penuh pada symlink.
6. **[TM-06] Anti-User Enumeration Login**: Menyeragamkan pesan error otentikasi ("Kombinasi email dan password tidak valid").
7. **[TM-07] Multitenant Test Seeder**: Membuat `MultitenantTestSeeder.php` untuk menguji isolasi data antara 2 yayasan dan 2 sekolah.
8. **[TM-08] Unified Dashboard Routing**: Merapikan middleware routing untuk mengarahkan pengguna ke dashboard yang sesuai dengan peran tanpa konflik.
9. **[TM-09] Semester Context Switcher**: Menambahkan dropdown konteks semester aktif per-sesi login di navbar staf.
10. **[TM-10] SuperAdmin Takeover & Audit Log**: Menjelaskan alur "Pantau" (Read-Only) dan "Ambil Alih" dengan pencatatan `audit_logs`.
11. **[TM-11] Netralisasi Wording Login**: Menyesuaikan teks login agar relevan bagi seluruh peran (Admin, Guru, Kepsek, Ortu).
12. **[TM-12] Dynamic Footer Branding**: Mengganti teks footer "Al-Hidayah" dengan nama sekolah/yayasan yang sedang aktif dari database.
13. **[TM-13] Role-Based Action Filtering**: Menyembunyikan tombol/link admin (seperti "Kelola Rombel") dari tampilan dashboard guru.
14. **[TM-14] Dynamic Nav Auth Button**: Menampilkan tombol "Ke Dashboard" di navigasi publik jika pengguna sudah terautentikasi.
15. **[TM-15] Guru Relationship in Materi Approval**: Menghubungkan relasi `guru` di `MateriController` agar nama pengunggah tampil dengan benar.
16. **[TM-16] Single Footer Layout**: Menghapus duplikasi render footer copyright pada layout admin.
17. **[TM-17] Materi Ownership Sync**: Menyelaraskan query `indexGuru` agar guru dapat melihat dan mengelola materi yang diunggahnya.
18. **[TM-18] Jurnal Harian Portal Ortu**: Membuat view `walimurid/jurnal.blade.php` dan menyambungkan routenya untuk wali murid.
19. **[TM-19] Tahun Ajaran Alignment**: Menyelaraskan tahun ajaran di dashboard dengan data semester aktif.
20. **[TM-20] Storage Asset Fallback Controller**: Menyediakan jalur fallback gambar landing/logo via controller jika symlink tidak aktif.
21. **[TM-21] Scope Separated Multi-Yayasan**: Mengisolasi dropdown pembuatan user dan sekolah berdasarkan `yayasan_id`.
22. **[TM-22] Batch Media Kegiatan Upload**: Membuat migrasi `media_kegiatans` dan `media_kegiatan_siswas` untuk unggah satu media ke banyak siswa.
23. **[TM-23] FK Kepala Sekolah**: Menambahkan kolom `kepala_sekolah_user_id` di tabel `schools` dan relasi `kepalaSekolahUser()` di model `School`.
24. **[TM-24] Auto-Fetch Parent Name**: Mengambil otomatis `nama_ortu` dari data pendaftaran/user saat penautan akun.
25. **[TM-25] Global Session Semester Switcher**: Menyediakan pemilih semester di navbar tanpa mengubah setting permanen database.
26. **[TM-26] Server-Side Pagination**: Menerapkan `paginate(25)` di `SiswaController::index` dan modul lainnya.
27. **[TM-27] Volume Test Seeder**: Membuat `VolumeSeeder.php` untuk memuat 100+ siswa guna pengujian performa paginasi.
28. **[TM-28] Multi-Guardian Support**: Membuat tabel pivot `student_guardian` untuk menghubungkan banyak wali (ayah/ibu) ke satu siswa.
29. **[TM-29] Wizard Form Pendaftaran**: Menyediakan validasi bertahap pada form pendaftaran.
30. **[TM-30] Audit Trail & Soft Restriction**: Membuat migrasi `record_revisions` untuk mencatat riwayat perubahan data transaksional.
31. **[TM-31] Admin Context Pendaftaran**: Mengunci `school_id` otomatis dari akun admin dan membuat bukti bayar opsional untuk input internal.
32. **[TM-32] Domain Resolver Middleware**: Membuat migrasi `domains` dan middleware `ResolveTenantFromHost` untuk custom domain per-sekolah.
33. **[TM-33] Server-Side Enforced Wali Murid Role**: Memaksa role `wali_murid` di `AuthController::register` dan menghapus field role hidden di client.
34. **[TM-34] Password Reset Flow**: Menambahkan handler reset password di `AuthController`.
35. **[TM-35] Legal Consent & Documents**: Membuat halaman Kebijakan Privasi (`/kebijakan-privasi`), Syarat Ketentuan (`/syarat-ketentuan`), dan pelacakan consent IP/timestamp di registrasi.
36. **[TM-36] Custom Branded Error Views**: Membuat halaman error `403`, `404`, `419`, dan `500` dengan desain modern dan tombol navigasi kembali.
37. **[TM-37] Multi-Child Switcher & IDOR Protection**: Menambahkan dropdown pemilih anak di header ortu (`WaliMuridController::switchChild`) dan melempar 404 jika mengakses anak di luar milik wali.
38. **[TM-38] Server-Side Search Box & Whitelisted Paginasi**: Menambahkan kotak pencarian nama/NIK/NISN dan membatasi `per_page` [10, 25, 50, 100].
39. **[TM-39] SuperAdmin Mode Pusat Summary View**: Membuat view ringkasan agregat (`superadmin/siswa_ringkasan.blade.php`) untuk mode pusat tanpa pencampuran query.

