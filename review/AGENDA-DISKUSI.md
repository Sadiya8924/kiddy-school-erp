# Agenda Diskusi Produk — Sistem TK Ceria
**Berdasarkan Review Black-Box | 08 Agustus 2026**
**Total temuan:** 39 | **Topik diskusi:** 21

> File ini adalah **peta keputusan**, bukan solusi teknis. Setiap topik berisi:
> konteks masalah, pertanyaan-pertanyaan yang perlu dijawab bersama, dan temuan yang
> mendasarinya. Solusi teknis akan ditulis di `SOLUSI-REKOMENDASI.md` **setelah**
> keputusan di sini disepakati.
>
> Urutan = prioritas. **Tier 1 harus diputuskan dulu** karena keputusannya mempengaruhi
> desain semua fitur di Tier 2 dan Tier 3.

---

## TIER 1 — Fondasi Arsitektur
> Keputusan di tier ini berdampak ke struktur database, model permission, dan hampir
> semua fitur. Jangan mulai membangun fitur baru sebelum topik-topik ini disepakati.

---

### D1. Hierarki Peran: Berapa Lapis yang Diperlukan?
**Temuan terkait:** TM-21, TM-08, TM-10, TM-07

**Situasi saat ini:**
Sistem memiliki satu role "Super Admin" yang sekaligus bertugas sebagai:
- Operator platform SaaS (akses ke semua yayasan)
- Admin yayasan (kelola banyak sekolah dalam 1 yayasan)

Akibatnya, jika sistem dijual ke yayasan kedua, admin yayasan pertama berpotensi melihat
data yayasan kedua karena keduanya memakai role yang sama. Routing juga terbagi dua:
Super Admin ke `/superadmin/dashboard`, sementara semua peran lain ke `/dashboard`.

**Pertanyaan untuk didiskusikan:**

1. **Apakah sistem ini akan dijual sebagai SaaS ke banyak yayasan berbeda?**
   Jika ya → perlu pemisahan *Platform Admin* (vendor) vs *Admin Yayasan* (tiap klien).
   Jika tidak, hanya satu yayasan yang akan menggunakan satu instalasi → role saat ini
   bisa dipertahankan dengan penyesuaian minor.

2. **Apa yang boleh dilakukan Super Admin ketika "Pantau" sebuah sekolah?**
   - Hanya lihat (read-only surveillance)?
   - Bisa edit data sekolah tersebut?
   - Bisa bertindak atas nama admin sekolah?
   Saat ini tidak ada pembeda di sistem.

3. **Apakah role "Kepala Sekolah" perlu dibedakan hak aksesnya dari "Admin Sekolah"?**
   Saat ini keduanya tampaknya memakai route yang sama — Kepala Sekolah bisa menyetujui
   materi (Persetujuan Materi), sementara Admin Sekolah mengelola data siswa/kelas.
   Apakah ini sudah benar, atau perlu ada perbedaan akses yang lebih tegas?

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D1.1** Arsitektur **3-lapis, database bersama:** Platform Admin (vendor/operator) → Admin Yayasan (satu yayasan, banyak sekolah) → Admin Sekolah. Isolasi via Global Scope + FK `yayasan_id`.
> - **D1.2** Mode Pantau = **read-only + "Ambil Alih" eksplisit**; setiap aksi Ambil Alih tercatat di tabel `audit_logs` (user, waktu, sekolah mana).
> - **D1.3** Kepala Sekolah = **pengawas/approver** (setujui materi, kontrol akses rapor, TTD rapor); Admin Sekolah = **operasional** (CRUD siswa/kelas/absensi harian).
> - **D1.4** Onboarding tenant baru dilakukan **Platform Admin secara manual** (model B2B saat closing deal).

---

### D2. Model Relasi Orang Tua–Anak: Seberapa Kompleks?
**Temuan terkait:** TM-28, TM-24, S1, S2, S3

**Situasi saat ini:**
Satu siswa hanya bisa ditautkan ke **satu akun wali murid** (melalui satu email). Data
`nama_ayah` dan `nama_ibu` ada di tabel siswa tapi tidak terhubung ke akun pengguna.

**Pertanyaan untuk didiskusikan:**

1. **Haruskah satu anak bisa punya dua akun wali terpisah (ayah DAN ibu)?**
   Implikasi: jika ya, perlu tabel pivot `siswa_wali` (many-to-many) menggantikan
   foreign key tunggal saat ini. Ini perubahan database yang signifikan.

2. **Apa yang terjadi ketika wali murid punya anak alumni + anak baru?**
   - Apakah anak alumni tetap muncul di dashboard wali?
   - Atau disembunyikan tapi datanya masih bisa diakses via menu "Arsip"?
   - Apakah status wali berubah (dari "wali aktif" ke "wali alumni") atau tidak?

3. **Siapa yang membuat akun wali murid — sistem secara otomatis atau admin manual?**
   - Jika admin manual (kondisi saat ini): admin input email wali → akun dibuat
   - Jika otomatis dari pendaftaran: email yang diisi di form pendaftaran langsung
     menjadi kredensial login wali, dengan password sementara dikirim via email
   Pilihan ini mempengaruhi alur pendaftaran dan alur D5 (wizard).

4. **Apakah nama ayah/ibu dari data pendaftaran harus otomatis menjadi nama akun wali?**
   Dan apakah nama tersebut juga otomatis muncul di rapor tanpa perlu diketik ulang?

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D2.1** Relasi **many-to-many** (pivot `student_guardian`): satu anak boleh banyak akun wali; tiap tautan menyimpan peran (`ayah`/`ibu`/`wali`) + flag `is_wali_utama`.
> - **D2.2** Nama ortu **auto-fetch read-only** dari data pendaftaran (`nama_ayah`/`nama_ibu`) di form Link Ortu DAN di rapor; ubah hanya lewat data pendaftaran — satu sumber kebenaran.
> - **D2.3** Satu akun wali menaungi **banyak anak lintas-status** (aktif & alumni). Alumni disembunyikan via filter status default; riwayat utuh dan bisa diakses via tab/filter "Alumni".
> - **D2.4** Akun wali **dibuat/ditautkan oleh Admin Sekolah** dari data pendaftaran yang sudah masuk.

---

### D3. Konteks Semester: Per-Sistem atau Per-Sesi Pengguna?
**Temuan terkait:** TM-25, TM-09

**Situasi saat ini:**
"Semester aktif" adalah satu nilai per sekolah yang diatur Admin di pengaturan dan
berlaku untuk semua pengguna. Tidak ada cara melihat data semester lain tanpa mengubah
setting sistem (yang berdampak ke semua pengguna serentak).

Tambahan: dua sekolah dalam satu yayasan bisa punya tahun ajaran aktif yang berbeda
(TK Permata 2027/2028, Tk Matahari 2026/2027), membuat laporan lintas-sekolah ambigu.

**Pertanyaan untuk didiskusikan:**

1. **Apakah setiap pengguna perlu bisa memilih semester yang ingin dilihat secara mandiri,
   tanpa mempengaruhi pengguna lain?**
   Contoh: Guru ingin cetak rapor semester lalu sambil guru lain sedang input absensi
   semester ini.

2. **Jika ya, di mana pemilih semester ini ditempatkan?**
   - Di header/navbar (selalu terlihat, memfilter semua modul)?
   - Di awal login (modal "pilih semester untuk sesi ini")?
   - Per-halaman (tiap modul punya filter sendiri)?

3. **Untuk laporan Super Admin yang mencakup banyak sekolah: bagaimana menangani
   sekolah yang sedang dalam tahun ajaran berbeda?**
   Apakah laporan agregat ditolak jika tahun ajaran tidak sama, atau ditampilkan dengan
   label peringatan?

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D3.1** Pemilih semester **per-sesi, global semua modul**: disimpan di sesi user, switcher di header, mem-filter otomatis semua modul (absensi/jurnal/rapor/perkembangan/rekap). Bisa ganti kapan saja.
> - **D3.2** Semester **dimiliki per-sekolah** (`school_id`); switcher hanya tampilkan semester milik sekolah yang sedang dibuka. Cocok untuk multi-sekolah dengan kalender berbeda.
> - **D3.3** Semua data ter-scope `semester_id`, **histori utuh**: ganti semester = ganti lensa tampilan; tidak ada data yang hilang atau dikunci.

---

### D4. Paginasi & Filter: Strategi untuk Semua Daftar
**Temuan terkait:** TM-26, TM-27, S4

**Situasi saat ini:**
Tidak ada paginasi di satu pun halaman daftar. Semua data dimuat sekaligus dalam satu
permintaan HTTP. Dengan 15 siswa saat ini tidak terasa, namun akan menjadi masalah
serius saat data bertumbuh.

**Pertanyaan untuk didiskusikan:**

1. **Berapa target jumlah siswa per sekolah yang harus ditangani sistem?**
   - < 100 siswa per sekolah: paginasi bisa lebih sederhana (25–50 per halaman)
   - 100–500 siswa: perlu paginasi + indexing database yang baik
   - > 500 siswa: perlu query optimization serius + kemungkinan lazy loading

2. **Apakah ada modul tertentu yang MEMANG harus menampilkan semua data sekaligus?**
   Contoh: presensi harian (form input untuk semua siswa sekelas = wajar dimuat semua).
   Rekap absensi bulanan (tampilkan 200 siswa × 30 hari = tidak boleh semua).

3. **Haruskah ada fitur ekspor (Excel/PDF) untuk data lengkap?**
   Jika ya, ekspor bisa menjadi alternatif untuk kasus "saya butuh semua data" — UI
   tetap terpaginasi, ekspor melayani kebutuhan data lengkap.

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D4.1** Standar baru: **server-side `paginate()` + filter `WHERE`** pada semua daftar; hapus semua filter JavaScript/DataTables client-side.
> - **D4.2** Buat **seeder volume realistis** (ratusan siswa, ribuan absensi/jurnal) + uji beban sebelum go-live produksi.
> - **D4.3** Pasang **indeks DB** pada kolom scope/filter (`yayasan_id`, `school_id`, `semester_id`, `kelas_id`, `status`) + **eager loading `with()`** untuk mencegah N+1 query.

---

## TIER 2 — Perbaikan Alur & Fitur Utama
> Keputusan di tier ini mempengaruhi UX dan alur kerja harian pengguna.

---

### D5. Wizard Pendaftaran: Berapa Langkah dan Bagaimana Draftnya?
**Temuan terkait:** TM-29, TM-24

**Situasi saat ini:**
Form pendaftaran adalah **satu halaman dengan 46 field** termasuk peta GPS interaktif
dan 4 upload file. Tidak ada validasi progresif, tidak ada auto-save, tidak ada indikator
progres. Jika browser tutup, semua input hilang.

**Pertanyaan untuk didiskusikan:**

1. **Bagaimana pemecahan langkah yang paling masuk akal?**
   Usulan awal (bisa disesuaikan):
   ```
   Langkah 1: Pilih Sekolah & Program        (2 field)
   Langkah 2: Data Pribadi Anak              (9 field)
   Langkah 3: Alamat & Lokasi Rumah (Peta)   (10 field termasuk GPS)
   Langkah 4: Data Orang Tua (Ayah & Ibu)   (12 field)
   Langkah 5: Data Fisik & Dokumen           (7 field + 4 upload)
   Langkah 6: Ringkasan & Konfirmasi         (review semua, submit)
   ```

2. **Apakah perlu fitur "lanjutkan nanti" (draft/resume)?**
   Orang tua mungkin tidak punya semua dokumen siap di satu waktu (misal: foto KK
   harus difoto dulu). Jika ya, perlu penyimpanan draft di server (tidak cukup di
   localStorage karena bisa berganti device).

3. **Apakah orang tua mendaftar sendiri (self-service) atau melalui operator/admin?**
   - Self-service publik → wizard harus sangat ramah pengguna, dengan bantuan/tooltip
   - Via admin → bisa lebih teknis, lebih banyak field wajib diisi sekaligus

4. **Bagaimana peta GPS ditangani untuk orang tua yang tidak melek teknologi?**
   Opsi: cukup isi alamat teks → koordinat di-geocode otomatis di backend, tanpa perlu
   orang tua menggeser pin secara manual.

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D5.1** **7 langkah:** Data Anak → Alamat/Peta → Data Ayah → Data Ibu → Data Fisik → Dokumen → Review & Kirim.
> - **D5.2** Validasi per-langkah **tapi resumable**: data disimpan dengan status `incomplete`; pengguna boleh berhenti & lanjut; kelengkapan wajib divalidasi penuh hanya saat submit final (step 7).
> - **D5.3** **Auto-save draft** setiap langkah; resume via link/token sesi (tersimpan di server, bukan localStorage).
> - **D5.4** Upload dokumen: **preview + validasi tipe/ukuran + kompres sisi klien** (`foto_anak`/`foto_kk`/`foto_akte`/`bukti_pembayaran`) sebelum dikirim ke server.

---

### D6. Media Kegiatan: Album Kelas atau Galeri Individual?
**Temuan terkait:** TM-22

**Situasi saat ini:**
Tidak ada fitur media kegiatan yang jelas. Modul yang ada (materi, jurnal harian) adalah
untuk konten akademik, bukan dokumentasi kegiatan.

**Pertanyaan untuk didiskusikan:**

1. **Apa unit utama dari media kegiatan — per-kegiatan/event atau per-siswa?**
   - **Per-kegiatan (album):** guru buat "Kegiatan: Mewarnai 5 Agustus 2026" → upload
     banyak foto → pilih siswa yang tampil di foto tersebut (tag). Setiap siswa yang di-tag
     melihat foto ini di portalnya.
   - **Per-siswa:** setiap siswa punya "galeri" sendiri; guru mengunggah foto langsung ke
     profil siswa tertentu.
   - **Hybrid:** per-kegiatan untuk foto kelas, per-siswa untuk momen individual.

2. **Siapa yang bisa melihat foto — hanya wali murid anak yang di-tag, atau semua wali
   murid di kelas tersebut?**
   Implikasi privasi: foto yang memperlihatkan wajah anak lain perlu kebijakan yang jelas.

3. **Apakah video juga perlu didukung?**
   Video jauh lebih besar dari gambar — perlu pertimbangan storage (InfinityFree punya
   batas disk) dan embedding (YouTube link vs upload langsung).

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D6.1** Alur: **unggah sekali → tag banyak siswa** (many-to-many `media_kegiatan_siswa`). Satu media bisa tampil di galeri banyak siswa tanpa upload ulang.
> - **D6.2** Guru hanya bisa tag siswa **di kelas/sekolah yang diampunya** (ter-scope); Admin Sekolah bisa tag lintas kelas dalam sekolahnya.
> - **D6.3** Wali hanya melihat media **yang men-tag anaknya** + flag **izin publikasi foto** per-siswa (dari data pendaftaran); siswa tanpa izin tidak muncul di galeri bersama.

---

### D7. Kepala Sekolah sebagai Jabatan Resmi dalam Sistem
**Temuan terkait:** TM-23

**Situasi saat ini:**
`kepala_sekolah` di profil sekolah adalah teks bebas, tidak terhubung ke akun user.
Role "Kepala Sekolah" ada di sistem tapi tidak di-assign ke sekolah manapun secara formal.

**Pertanyaan untuk didiskusikan:**

1. **Apa saja fungsi yang membutuhkan identitas kepala sekolah secara teknis?**
   - Tanda tangan digital / nama di rapor?
   - Penerima notifikasi tertentu?
   - Approver untuk persetujuan materi (saat ini dilakukan "Admin Sekolah")?
   - Laporan ke dinas/Dapodik?

2. **Bisakah satu sekolah memiliki lebih dari satu kepala sekolah (pagi/siang)?**

3. **Apakah kepala sekolah selalu punya akun di sistem, atau kadang tidak (digantikan
   teks nama saja untuk kebutuhan rapor)?**

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D7.1** Kepala Sekolah diassign dengan **memilih dari akun ber-role `kepala_sekolah`** (dropdown/FK `kepala_sekolah_user_id`), bukan teks bebas. Mengatasi TM-23.
> - **D7.2** Relasi **one-to-one**: satu kepsek per sekolah, satu sekolah per orang; validasi unique mencegah double-assign.
> - **D7.3** Kepsek yang ditetapkan **otomatis mengisi nama & TTD rapor** + menjadi **approver materi** (selaras D1.3).

---

### D8. Storage & File: Strategi Jangka Panjang di Lingkungan Hosting Saat Ini
**Temuan terkait:** TM-05, TM-20

**Situasi saat ini:**
Semua file yang diupload (logo, gambar landing, arsip rapor PDF) dikembalikan 404 karena
symlink storage Laravel tidak berfungsi di InfinityFreeApp. Ini berdampak ke logo yayasan,
landing page, arsip rapor, dan akan berdampak ke semua media kegiatan yang diupload nanti.

**Pertanyaan untuk didiskusikan:**

1. **Apakah InfinityFreeApp akan tetap digunakan sebagai hosting produksi, atau ini
   hanya untuk demo beta?**
   - Jika tetap InfinityFree: perlu workaround khusus (controller yang serve file,
     atau simpan di `public/` langsung)
   - Jika akan pindah ke VPS/shared hosting lain: solusi standard `storage:link` akan
     langsung berfungsi

2. **Apakah file yang diupload perlu tersedia secara publik (tanpa login) atau hanya
   untuk pengguna yang terotentikasi?**
   - Logo & gambar landing → publik
   - Foto siswa, KK, akte, rapor PDF → wajib terlindungi (hanya untuk pengguna yang berhak)

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D8.1** ~~Tetap di InfinityFree — perbaiki symlink/public path.~~ ⚠️ **DIPERBARUI oleh D14.4** (lihat topik D14): keputusan berubah menjadi **pindah ke VPS**, yang sekaligus menyelesaikan masalah symlink TM-05 & TM-20 secara otomatis. Workaround InfinityFree tidak diperlukan lagi.
> - **D8.2** File sensitif (dokumen pendaftaran, rapor, media siswa) dilayani lewat **route controller terotorisasi** (`Storage::response` + cek Policy/tenant); bukan symlink publik. **Tetap berlaku** — tidak berubah meski pindah VPS.

---

## TIER 3 — Keamanan, Branding & Integritas Data
> Tier ini berisi perbaikan yang lebih taktis dan dapat dikerjakan secara paralel
> dengan Tier 1–2, namun tetap harus selesai sebelum sistem dibuka ke publik luas.

---

### D9. Keamanan Pra-Launch: Checklist Apa yang Wajib Selesai?
**Temuan terkait:** TM-01, TM-02, TM-03, TM-04, TM-06

Ini bukan pertanyaan terbuka — kelima temuan ini **wajib diperbaiki** sebelum sistem
menerima data nyata dari orang tua/siswa. Yang perlu didiskusikan:

1. **Kapan target go-live ke publik (dengan data nyata)?**
   Ini menentukan urgensi checklist keamanan.

2. **Apakah ada mekanisme untuk menguji isolasi data antar-tenant sebelum launch?**
   Perlu minimal dua akun dari yayasan berbeda untuk membuktikan data tidak bocor.

3. **Bagaimana prosedur reset password untuk akun demo sebelum digunakan pengguna nyata?**

**Checklist minimum (tidak ada opsi lain):**
- [ ] Form login `method` diubah ke POST di HTML
- [ ] Security headers ditambahkan (X-Frame-Options, CSP, HSTS, dll)
- [ ] Kredensial demo dihapus dari halaman login publik
- [ ] Semua password default diganti
- [ ] IDOR endpoint `/siswa/{id}` diverifikasi scope-nya ke sekolah yang benar

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D9.1** Kredensial demo **DIPERTAHANKAN** untuk keperluan showcase beta; wajib dievaluasi ulang dan dihapus/diganti sebelum go-live produksi nyata dengan data pengguna sesungguhnya.
> - **D9.2** Header keamanan diatur di **level web server** (Apache `.htaccess`), bukan middleware Laravel — lebih ringan dan tidak bergantung pada siklus request PHP.
> - **D9.3** **Policy + Global Scope** tenant/sekolah/kelas diterapkan pada semua model & route ber-ID; dilengkapi uji IDOR otomatis (Pest/PHPUnit).
> - **D9.4** **Throttle login + pesan error seragam** (cegah enumerasi user) + **kebijakan password**: wajib ganti default `password`, syarat kekuatan diterapkan.

---

### D10. Branding: Sistem White-Label atau Satu Merek?
**Temuan terkait:** TM-11, TM-12, TM-14, TM-16

**Situasi saat ini:**
Footer hardcoded dengan nama "Al-Hidayah" (berbeda dari yayasan yang terdaftar), halaman
login berlabel "portal orang tua" untuk semua peran, ada dua baris footer duplikat, dan
tombol "Daftar Sekarang" tetap tampil untuk pengguna yang sudah login.

**Pertanyaan untuk didiskusikan:**

1. **Apakah sistem ini dirancang sebagai white-label (tiap yayasan punya nama/logo
   sendiri di sistem) atau satu merek "TK Ceria" untuk semua?**
   - White-label: nama aplikasi, logo, warna, footer — semuanya diambil dari data yayasan
   - Satu merek: semua yayasan memakai tampilan "TK Ceria" yang sama

2. **Apakah ada dua pintu masuk terpisah — satu untuk staf (admin/guru) dan satu untuk
   orang tua — atau tetap satu URL login untuk semua peran?**

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D10.1** **Semua branding dari data tenant** (nama tampil, footer, logo, warna tema, info kontak) — nol teks hardcoded. Mengatasi TM-11/TM-12/TM-14/TM-16.
> - **D10.2** Cakupan white-label: **logo + nama + warna tema + info kontak/footer** per yayasan/sekolah via menu Pengaturan. Custom domain di luar cakupan tahap ini.
> - **D10.3** **UI adaptif** per status login & peran: pengunjung lihat landing publik; user yang sudah login lihat dashboard sesuai perannya (tidak ada tombol "Daftar Sekarang" untuk yang sudah login).

---

### D11. Integritas Data Materi–Guru & Kontrol UI per Peran
**Temuan terkait:** TM-15, TM-17, TM-13

**Situasi saat ini:**
- Materi yang sudah disetujui admin tampil dengan guru "Unknown" (foreign key rusak)
- Guru Naning melihat 0 materi di halaman manajemen materinya padahal ada 6 materi disetujui
- Guru melihat tombol "Kelola Rombel" di dashboard padahal itu fitur admin

**Pertanyaan untuk didiskusikan:**

1. **Apa akar masalah data materi "Unknown"?** Apakah seeder menggunakan user_id yang salah,
   atau ada perubahan skema yang membuat FK lama tidak valid? Perlu investigasi langsung di
   database.

2. **Apakah ada fungsi lain yang "bocor" ke peran yang tidak seharusnya melihatnya?**
   Perlu audit menyeluruh semua elemen UI yang memiliki kondisi tampil/sembunyikan.

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D11.1** Perbaiki relasi materi→guru dengan **FK tegas + constraint DB (`on delete restrict/set null`) + backfill** data lama; guru wajib se-sekolah dengan materi. Mengatasi TM-15/TM-17.
> - **D11.2** Sembunyikan kontrol admin dari UI Guru **DAN tolak aksi di server** (Policy); bukan sekadar hide CSS — aksi tetap bisa dipicu via URL tanpa penolakan server. Mengatasi TM-13.
> - **D11.3** Navigasi & aksi dibangkitkan dari **permission terpusat** (permission-driven menu, mis. `spatie/laravel-permission`), selalu selaras dengan otorisasi server. Selaras D1.3/D9.3.

---

## TIER 3 (Lanjutan) — Kebijakan Data

---

### D12. Kebijakan Penghapusan: Data Transaksional Tidak Boleh Dihapus
**Temuan terkait:** TM-30

**Situasi saat ini:**
Dengan pola Resource Controller standar Laravel, kemungkinan besar route `DELETE /resource/{id}`
dan tombol "Hapus" tersedia di semua modul termasuk data transaksional (absensi, jurnal,
rapor, perkembangan fisik, pembayaran). Jika pengguna menghapus data transaksional secara
tidak sengaja, riwayat akademik/finansial anak hilang permanen tanpa kemungkinan pemulihan.

**Pertanyaan untuk didiskusikan:**

1. **Apakah data transaksional harus bersifat append-only (tidak ada hapus sama sekali)?**
   Atau cukup soft-delete dengan kemampuan restore oleh admin?

2. **Mekanisme apa yang menggantikan "hapus" ketika ada kesalahan input?**
   - Edit langsung (tanpa riwayat)?
   - Edit dengan audit trail (riwayat perubahan per-field)?
   - Status "dibatalkan/void" tanpa hapus fisik?

3. **Siapa yang boleh membatalkan transaksi finansial (pembayaran) dan perlu approval?**
   Perlu persetujuan Kepala Sekolah, atau Admin Sekolah bisa langsung?

4. **Data apa yang MASIH boleh dihapus secara permanen?**
   Misal: draft pendaftaran yang belum disubmit, data master yang belum punya transaksi.

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D12.1** Data transaksional **append-only, TANPA tombol hapus**: absensi, jurnal, rapor, nilai/perkembangan, pembayaran. Kesalahan diperbaiki via edit tercatat atau entri pembalik.
> - **D12.2** Hapus permanen **hanya untuk draft/incomplete** yang belum punya transaksi terkait. Begitu ada transaksi → tidak bisa dihapus.
> - **D12.3** Koreksi via **Edit dengan riwayat perubahan** (audit per-field: user, waktu, nilai lama→baru); entitas tak relevan ditandai **nonaktif/arsip** (bukan hapus); transaksi finansial pakai **entri pembalik (reversing/void)**.
> - **D12.4** Admin Sekolah boleh void/arsip operasional (tercatat `audit_logs`); **pembatalan transaksi finansial butuh persetujuan Kepala Sekolah** (selaras D1.3).

---

## TIER 3 (Lanjutan) — Konteks Tampilan & Navigasi

---

### D13. Layout "Tambah Siswa": Admin Diarahkan ke Halaman Publik
**Temuan terkait:** TM-31

**Situasi saat ini (terverifikasi via Playwright):**
Tombol "Tambah Siswa" di halaman admin `/data-siswa` menautkan ke `/pendaftaran-siswa?school_id=1`,
yaitu **form pendaftaran publik** yang menggunakan **layout landing page** (nav: Beranda, Tentang Kami,
Pendaftaran, Hubungi Kami, Login Portal, Mulai Daftar). Akibatnya:
- Admin keluar dari konteks aplikasi (tidak ada sidebar, tidak ada tombol Logout)
- Link "Login Portal" muncul padahal admin sudah masuk
- `school_id=1` hardcode → admin sekolah lain mendaftarkan siswa ke sekolah yang salah
- Langkah upload `bukti_pembayaran` wajib meski konteks ini adalah input internal admin

**Pertanyaan untuk didiskusikan:**

1. **Bagaimana halaman pendaftaran seharusnya ditampilkan saat diakses admin yang sudah login?**
   - Bungkus dalam layout admin (sidebar + header aplikasi)?
   - Buat route admin terpisah (`/data-siswa/create`) dengan form yang sama tapi layout berbeda?
   - Atau cukup sembunyikan nav marketing (solusi minimal)?

2. **Apakah form pendaftaran untuk admin harus sama persis dengan form publik?**
   - Sama (wizard 7-langkah), tapi cabang otomatis terisi & terkunci ke sekolah admin?
   - Atau buat form internal yang lebih ringkas (hanya field inti, tanpa alur bayar)?

3. **Bagaimana menentukan `school_id` saat admin menambah siswa?**
   - Otomatis dari sekolah aktif admin (tidak bisa diubah)?
   - Admin pilih dari dropdown yang dibatasi yayasannya?
   - Pertahankan hardcode (tidak direkomendasikan untuk multi-sekolah)?

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D13.1** **Bungkus dalam layout admin**: deteksi `auth()->check()` → render form dalam layout aplikasi (sidebar, header, breadcrumb, logout); hilangkan nav marketing untuk sesi admin. Satu form, dua konteks layout.
> - **D13.2** **Form sama (wizard D5), cabang & pembayaran disesuaikan**: cabang/sekolah otomatis terisi & dikunci ke sekolah admin; langkah `bukti_pembayaran` **opsional** untuk konteks admin (input internal, bukan wali eksternal).
> - **D13.3** **`school_id` dari konteks sekolah admin** (scope tenant), bukan hardcode; admin multi-sekolah memakai sekolah aktif yang sedang terpilih (selaras D1/D3); validasi `school_id` ∈ yayasan admin (anti-IDOR lintas-sekolah).

---

## TIER 4 — Infrastruktur & Skala

---

### D14. Custom Domain per Sekolah & per Yayasan
**Temuan terkait:** TM-32

**Situasi saat ini:**
Seluruh sistem berjalan di satu host `tkceria.infinityfreeapp.com`. Pemilihan konteks sekolah
dilakukan via `?school_id=` query param dan dropdown "Pilih Cabang". Tidak ada resolusi
berbasis hostname — sekolah tidak dapat memiliki domain branding sendiri.

**Pertanyaan untuk didiskusikan:**

1. **Custom domain diberikan pada level mana?**
   - Per sekolah saja (`tkpermata.sch.id`)
   - Per yayasan saja (`alhikmah.sch.id` sebagai portal induk)
   - Per sekolah DAN per yayasan (white-label penuh)

2. **Bagaimana aplikasi memetakan domain masuk → konteks sekolah?**
   - Tabel `domains` + middleware baca `Host` header (mendukung banyak alias/redirect)
   - Kolom `custom_domain` tunggal di tabel `schools` (lebih sederhana, tapi 1 domain per entitas)
   - Pemetaan statis di config/routes (tidak direkomendasikan — butuh deploy tiap perubahan)

3. **Bagaimana penyediaan domain & SSL dilakukan?**
   - Manual saat onboarding (Platform Admin tambah domain, klien arahkan DNS, verifikasi TXT)
   - Otomatis self-service (klien tambah domain di panel → ACME/Let's Encrypt otomatis)
   - Tanpa SSL kustom dulu (hanya HTTP atau sertifikat bersama — tidak disarankan untuk data anak)

4. **InfinityFree membatasi addon domain & SSL. Apakah hosting perlu diganti?**
   - Uji batas InfinityFree dulu, rencana pindah VPS untuk produksi
   - Tetap InfinityFree (terima batasan: sedikit addon domain, SSL manual per domain)
   - Langsung pindah ke VPS/managed hosting sekarang (nginx/Caddy + wildcard SSL)

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D14.1** Custom domain **per sekolah DAN per yayasan** — white-label penuh selaras D10.1.
> - **D14.2** **Tabel `domains`** (`yayasan_id`/`school_id`, `domain`, `is_primary`, `is_verified`) + **middleware `ResolveTenantFromHost`** membaca `Host` header → set konteks tenant/sekolah aktif; mendukung alias/redirect/multi-domain.
> - **D14.3** **Provisioning manual + verifikasi DNS TXT** (selaras D1.4 B2B): Platform Admin tambah domain; klien arahkan DNS (A/CNAME); verifikasi kepemilikan via TXT record.
> - **D14.4** **Pindah ke VPS/managed hosting SEKARANG** (Caddy/nginx + on-demand TLS). ⚠️ **Menggantikan D8.1** — pindah VPS sekaligus menyelesaikan TM-05 & TM-20 (storage symlink), TM-02 (security headers lebih andal), dan memungkinkan uji beban nyata (S4).

---

## TIER 5 — Registrasi, Pemulihan Akun & Kepatuhan (Temuan Baru Batch #6)

### D15 — Registrasi Publik: Pembatasan Peran & Keamanan
**Temuan terkait:** TM-33
**Urgensi:** 🔴 Kritis — perlu diperbaiki sebelum launch

**Konteks:** Route `/register` dapat diakses tanpa autentikasi, memuat form dengan `<input type="hidden" name="role">` yang nilainya dikontrol klien. Tidak ada scope yayasan/sekolah. Bertentangan dengan D1.4 (onboarding manual B2B).

**Pertanyaan untuk didiskusikan:**

1. **Apakah registrasi publik tetap dipertahankan atau ditutup?**
   - Tutup total: `/register` redirect ke `/login`; akun hanya dibuat oleh Admin
   - Batasi ke Wali Murid: registrasi tetap tersedia TAPI peran dipaksa `wali_murid` di server, school/yayasan dipilih eksplisit, wajib verifikasi email
   - Pertahankan apa adanya (tidak disarankan)

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D15.1** **Registrasi publik dibatasi hanya untuk Wali Murid.** Peran dipaksa `wali_murid` di server — field `role` dari klien diabaikan sepenuhnya; hapus dari `$fillable` dan dari form. Tenant/sekolah dipilih eksplisit (via konteks host D14 atau dropdown tervalidasi). Akun staf tetap dibuat oleh Admin/Platform Admin (selaras D1.4).
> - **D15.2** **Wajib verifikasi email** (`MustVerifyEmail`; `verified` middleware melindungi semua route terautentikasi). Route `/email/verify` diaktifkan.
> - **D15.3** **Throttle registrasi** (maks 5 percobaan/menit/IP); pertimbangkan CAPTCHA jika spam menjadi masalah di produksi.

---

### D16 — Alur Reset/Lupa Password
**Temuan terkait:** TM-34
**Urgensi:** 🟠 Tinggi

**Konteks:** `/forgot-password` dan `/password/reset` mengembalikan 404. Halaman login tidak memiliki link "Lupa Password?". Pengguna yang lupa password harus meminta bantuan admin.

**Pertanyaan untuk didiskusikan:**

1. **Mekanisme pemulihan password yang digunakan?**
   - Reset mandiri via email token (standar Laravel — butuh SMTP aktif)
   - Reset oleh Admin saja (tanpa self-service; admin set + force-change saat login berikutnya)
   - Hybrid: wali mandiri via email; staf direset admin sekolah/yayasan

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D16.1** **Reset mandiri via email token** (alur standar Laravel: `Password::sendResetLink`, throttle, token berlaku 60 menit). Tambah link "Lupa Password?" di halaman login.
> - **D16.2** **SMTP wajib diaktifkan** di Sprint 0 — fondasi bersama TM-33 (verifikasi email) dan notifikasi masa depan. Migrasi VPS (D14.4) mempermudah setup SMTP.
> - **D16.3** Pesan respons reset **seragam** (tidak membedakan "email terdaftar" vs "tidak terdaftar") — anti-enumerasi, konsisten dengan D9.4.

---

### D17 — Kepatuhan Hukum & Privasi Data (UU PDP)
**Temuan terkait:** TM-35
**Urgensi:** 🟠 Tinggi — relevan UU PDP No. 27/2022

**Konteks:** Footer menautkan "Kebijakan Privasi" dan "Syarat & Ketentuan" ke `href="#"` (placeholder mati). Sistem mengumpulkan PII anak (NIK, KK, akte, foto, geolokasi). Tidak ada checkbox consent.

**Pertanyaan untuk didiskusikan:**

1. **Seberapa cepat halaman legal dan consent perlu diaktifkan?**
   - Langsung: halaman legal nyata + checkbox consent di form sekarang
   - Halaman legal dulu, mekanisme consent menyusul sprint berikutnya
   - Tunda hingga menjelang go-live produksi

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D17.1** **Buat halaman Kebijakan Privasi dan Syarat & Ketentuan nyata** (route + Blade view; ganti semua `href="#"` di footer). Konten ditinjau konsultan hukum sebelum go-live.
> - **D17.2** **Checkbox persetujuan wali tersurat** di wizard pendaftaran siswa (D5, langkah terakhir) dan di form registrasi wali (D15): "Saya menyetujui Kebijakan Privasi dan S&K terkait pemrosesan data anak sesuai UU PDP."
> - **D17.3** **Simpan timestamp & versi consent** (`consent_at`, `consent_version`, `consent_ip`) di tabel `pendaftaran` dan `users` — bukti kepatuhan yang dapat diaudit.

---

### D18 — Halaman Error & Pengecualian
**Temuan terkait:** TM-36
**Urgensi:** 🔵 Rendah — polish & konsistensi branding

**Konteks:** Halaman 404 saat ini menampilkan "NOT FOUND" polos tanpa branding, navigasi, atau tombol kembali (positif: tidak membocorkan stack trace).

**Pertanyaan untuk didiskusikan:**

1. **Seberapa lengkap kustomisasi halaman error yang diperlukan?**
   - Semua kode (404/403/419/500) ber-branding tenant + navigasi kontekstual
   - Hanya 404 dan 500 (paling sering ditemui)
   - Biarkan default (tidak prioritas beta)

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D18.1** **Halaman error ber-branding untuk 404, 403, 419, dan 500** menggunakan branding tenant aktif (logo, nama yayasan/sekolah) — konsisten dengan white-label D10.
> - **D18.2** **Tombol navigasi kontekstual:** terautentikasi → "Kembali ke Dashboard"; tidak terautentikasi → "Kembali ke Beranda" + "Login".
> - **D18.3** **Tidak membocorkan informasi teknis** — tidak ada stack trace, path file, atau versi framework di produksi (sudah terpenuhi; pertahankan).

---

## TIER 6 — Portal Wali Murid & Skala Data

---

### D19 — Konsistensi Portal Ortu Multi-Anak
**Temuan terkait:** TM-37, TM-18
**Urgensi:** 🟠 Tinggi — Product/UX + Functionality

**Konteks:** Akun `ortu@tk.com` (3 anak: Rania id=1, Sasa id=15, Amar id=18) menunjukkan bahwa menu sidebar selalu menampilkan Rania; tombol Jurnal dan Materi di kartu dashboard menaut ke halaman generik (tanpa `{id}`). Rapor dan Fisik per-anak sudah benar. Tidak ada pemilih anak. Periode data tidak konsisten lintas modul (rapor: 2027/2028; fisik: 2025/2026). "Tambah Anak" membuka form pendaftaran publik. Positif: IDOR anak non-milik → 404 (isolasi antar-keluarga sudah benar).

**Pertanyaan yang didiskusikan:**

1. Bagaimana wali dengan banyak anak berpindah konteks (pilih anak aktif)?
2. Bagaimana Jurnal dan Materi mengetahui anak mana yang ditampilkan?
3. Apakah filter periode mengikuti semester global (D3)?
4. Bagaimana wali menautkan anak baru yang sudah terdaftar?

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D19.1** **Pemilih anak global di header** (mirip pola switcher semester D3): satu selector "Anak Aktif" persisten di header; memilih anak → semua modul (rapor, fisik, jurnal, materi) otomatis mengikuti. Simpan di sesi (`session('active_child_id')`).
> - **D19.2** **Jurnal & Materi di-scope per anak:** perbaiki routing tombol kartu agar membawa `anak_id` yang benar; Materi query berdasarkan kelas anak aktif — bukan "kelas aktif" ambigu. Tidak ada lagi halaman generik yang mengabaikan anak.
> - **D19.3** **Ikuti pemilih semester global (D3):** semua modul ortu menampilkan data semester aktif; label tahun ajaran seragam di rapor/fisik/jurnal/materi.
> - **D19.4** **"Tambah Anak" = tautkan anak yang sudah terdaftar:** ortu memasukkan NISN anak yang sudah didaftarkan admin (bukan form 46-field publik ulang); tautkan ke akun wali via pivot `student_guardian`. Selaras D2.4 & D13.

---

### D20 — Perilaku Mode Pusat Super Admin
**Temuan terkait:** TM-39
**Urgensi:** 🟡 Sedang — Multitenancy/Data-Consistency

**Konteks:** Mode Pusat Super Admin menampilkan counter "18 Siswa" tapi tabel 0 baris — query COUNT lintas-sekolah tidak selaras dengan query LIST yang butuh `active_school_id`. Label "Semua Data" justru tidak menampilkan data apapun.

**Pertanyaan yang didiskusikan:**

1. Bagaimana seharusnya Mode Pusat berperilaku untuk halaman daftar detail (mis. Data Siswa)?

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D20.1** **Mode Pusat = ringkasan/agregat saja:** tampilkan jumlah siswa per sekolah (agregat COUNT), bukan daftar detail per baris. Daftar detail baru muncul setelah sekolah dipilih (Mode Pantau). Counter diselaraskan dengan query yang sama dengan tabel (tidak ada lagi angka yang menyesatkan).
> - **D20.2** **UI ringkasan Mode Pusat:** tabel sekolah + jumlah + tombol "Pantau Sekolah Ini" yang langsung switch ke mode pantau sekolah terkait.

---

### D21 — Rendering Daftar pada Data Besar
**Temuan terkait:** TM-38, TM-26, TM-27
**Urgensi:** 🟠 Tinggi — Functionality/Performance + Multitenancy

**Konteks:** Controller menggunakan `->get()` (muat semua). Dengan 15 siswa, `/data-siswa` admin sudah 208KB HTML / 33 form / 190 kontrol — tiap baris menyematkan 2 form inline (~14KB/baris). Proyeksi 500 siswa: ~7MB satu halaman. Parameter paginasi (`?page`, `?per_page`) semuanya diabaikan. Tidak ada kotak cari nama/NISN.

**Pertanyaan yang didiskusikan:**

1. Bagaimana merender daftar besar agar tetap responsif dan tidak memberatkan browser?

> **KEPUTUSAN FINAL ✅** *(disepakati 08 Agustus 2026)*
> - **D21.1** **Paginasi server-side `->paginate(25)`** di semua controller daftar. Pengguna memilih 10/25/50/100 per halaman (whitelist); `per_page` tak terbatas ditolak.
> - **D21.2** **Hapus form inline per-baris:** pindahkan aksi (set kelas, tautkan ortu, dll.) ke **modal on-demand** yang dimuat via AJAX saat tombol diklik — bukan form HTML tercetak di setiap baris. Ini memotong bobot DOM dari ~12 kontrol/baris menjadi ~2 kontrol/baris.
> - **D21.3** **Tambah kotak cari nama/NISN server-side** (`WHERE nama_lengkap LIKE %q%` atau `nisn LIKE %q%`) + filter kelas/tahun yang sudah ada tetap server-side.
> - **D21.4** **Tambah indeks DB** pada kolom filter (`school_id + kelas_id`, `school_id + status`, `nisn`) untuk query cepat di skala besar. Selaras D4.3.

---

## Ringkasan: Status Keputusan

```
SEMUA KEPUTUSAN SUDAH DISEPAKATI (08 Agustus 2026)

TIER 1 — Fondasi Arsitektur (✅ semua diputuskan):
  D1 ✅ Hierarki peran 3-lapis, DB bersama + Ambil Alih + audit log
  D2 ✅ Many-to-many wali (student_guardian) + nama auto dari pendaftaran
  D3 ✅ Semester per-sesi, global semua modul, per-sekolah, histori utuh
  D4 ✅ Server-side paginate + filter, seeder volume, indeks + eager loading
  D20 ✅ Mode Pusat = ringkasan/agregat; daftar detail wajib pilih sekolah; counter konsisten
  D21 ✅ Paginasi 25/hal + hapus form inline per-baris + cari nama/NISN server-side + whitelist per_page

TIER 2 — Perbaikan Alur & Fitur Utama (✅ semua diputuskan):
  D5 ✅ Wizard 7-langkah, resumable (incomplete), auto-save, upload+kompres
  D6 ✅ Upload sekali → tag banyak siswa, scope kelas guru, izin publikasi
  D7 ✅ Kepsek FK ke users (one-to-one), TTD rapor otomatis + approver
  D8 ✅ [DIPERBARUI oleh D14.4] file privat via route controller (D8.2 tetap); hosting → VPS
  D19 ✅ Pemilih anak global header + scope per-anak + semester global (D3) + tautkan via NISN

TIER 3 — Keamanan, Branding & Integritas (✅ semua diputuskan):
  D9  ✅ Header via nginx/Caddy (VPS), Policy+GlobalScope, throttle, kredensial demo dipertahankan (beta)
  D10 ✅ Semua branding dari data tenant (white-label), UI adaptif per peran/status
  D11 ✅ FK materi→guru + constraint + backfill; sembunyikan+tolak server; permission-driven menu
  D12 ✅ Append-only transaksional; hapus hanya draft; edit+riwayat; void finansial→approval kepsek
  D13 ✅ Layout admin untuk "Tambah Siswa"; school_id dari konteks tenant; bukti_pembayaran opsional

TIER 4 — Infrastruktur & Skala (✅ diputuskan):
  D14 ✅ Custom domain per sekolah+yayasan; tabel domains + middleware host; DNS TXT; pindah VPS (→ D8.1)

TIER 5 — Registrasi, Pemulihan Akun & Kepatuhan (✅ semua diputuskan):
  D15 ✅ Registrasi publik dibatasi ke Wali Murid; role dipaksa server; wajib verifikasi email; throttle
  D16 ✅ Reset password mandiri via email token (Laravel bawaan); SMTP aktif Sprint 0; link di login
  D17 ✅ Halaman legal nyata + checkbox consent wali + simpan consent_at/version (UU PDP)
  D18 ✅ Halaman error ber-branding tenant (404/403/419/500) + navigasi kontekstual; tidak bocorkan teknis
```

### Catatan: Verifikasi Isolasi Positif (dari audit D19-D21)
Audit scope terhadap keempat akun demo (batch #8) mengonfirmasi isolasi multitenancy:
- **Guru** terkunci ke kelas ampuannya; kontrol admin disembunyikan di server (D11.2 terpenuhi).
- **Admin TK** terkunci ke sekolahnya; `?school_id` param diabaikan (anti-IDOR lintas-sekolah).
- **Wali** mendapat 403 di semua route admin; IDOR anak non-milik → 404 (D9.3 terpenuhi).
- **Bug ditemukan:** Mode Pusat Super Admin (counter vs tabel tidak konsisten → D20/TM-39).

---

## Peta Temuan ke Topik Diskusi

| Temuan | Topik | Ringkasan |
|--------|-------|-----------|
| TM-01 | D9 | Form login method GET |
| TM-02 | D9 | Tidak ada security header |
| TM-03 | D9 | Kredensial demo publik |
| TM-04 | D9 | IDOR siswa/{id} |
| TM-05 | D8 / D14 | Storage PDF 404 — akar masalah diselesaikan oleh migrasi VPS (D14.4) |
| TM-06 | D9 | User enumeration via error message |
| TM-07 | D1 | Isolasi antar-tenant belum terverifikasi |
| TM-08 | D1 | Split routing superadmin vs dashboard |
| TM-09 | D3 | Tahun ajaran berbeda antar sekolah |
| TM-10 | D1 | Mode pantau ambiguitas view vs edit |
| TM-11 | D10 | Login berlabel "portal orang tua" |
| TM-12 | D10 | Footer hardcoded "Al-Hidayah" |
| TM-13 | D11 | Guru lihat tombol admin |
| TM-14 | D10 | Nav publik tampil saat sudah login |
| TM-15 | D11 | Guru "Unknown" di persetujuan materi |
| TM-16 | D10 | Footer duplikat |
| TM-17 | D11 | FK materi→guru rusak |
| TM-18 | D6 | Jurnal harian anak tidak tersedia di ortu |
| TM-19 | D4/D3 | Tahun ajaran demo tidak sesuai |
| TM-20 | D8 / D14 | 404 logo & gambar landing — diselesaikan oleh migrasi VPS (D14.4) |
| TM-21 | D1 | Platform admin = tenant admin (conflation) |
| TM-22 | D6 | Alur upload media kegiatan menyulitkan |
| TM-23 | D7 | Kepala sekolah hanya teks bebas |
| TM-24 | D2/D5 | Nama ortu tidak auto-fetch |
| TM-25 | D3 | Tidak ada semester chooser global |
| TM-26 | D4 | Tidak ada paginasi di seluruh daftar |
| TM-27 | D4 | Data uji terlalu kecil |
| TM-28 | D2 | Hanya 1 wali per siswa |
| TM-29 | D5 | Form pendaftaran 46 field, 1 halaman |
| TM-30 | D12 | Aksi hapus pada data transaksional |
| TM-31 | D13 | "Tambah Siswa" admin pakai layout & header landing publik + school_id hardcode |
| TM-32 | D14 | Belum ada custom domain per sekolah/yayasan; resolusi hanya via ?school_id |
| TM-33 | D15 | Registrasi publik terbuka; field `role` tersembunyi dimanipulasi; tanpa scope tenant; tanpa verifikasi email |
| TM-34 | D16 | Tidak ada alur reset/lupa password; /forgot-password 404 |
| TM-35 | D17 | Kebijakan Privasi & S&K hanya placeholder href="#"; tidak ada consent; relevan UU PDP |
| TM-36 | D18 | Halaman error (404/500) polos tanpa branding & navigasi |
| TM-37 | D19 | Portal ortu: menu terkunci anak pertama; Jurnal/Materi generik (tanpa child_id); periode tidak konsisten; "Tambah Anak" → form publik |
| TM-38 | D21 | Daftar tanpa paginasi nyata (->get()) + form inline per-baris → 208KB/33 form untuk 15 siswa; proyeksi ~7MB @500 siswa |
| TM-39 | D20 | Mode Pusat Super Admin: counter "18 Siswa" tapi tabel 0 baris (scope COUNT vs LIST tidak konsisten) |
