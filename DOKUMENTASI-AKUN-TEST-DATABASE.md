# 🔑 Dokumentasi Akun & Credentials Testing - Kiddy School ERP

> **Catatan Penting Security & Multi-Tenant Portal Routing:**  
> 1. **Password Default Seluruh Akun:** `password`  
> 2. **URL Portal Login Resmi Terisolasi:**  
>    * 🔑 **Owner SaaS & Platform Admin**: `http://127.0.0.1:8000/login` *(Khusus Pengelola Platform)*  
>    * 🏛️ **Yayasan Pendidikan Al-Hikmah 2**: `http://127.0.0.1:8000/y/YAH2/login`  
>    * 🏛️ **Yayasan Bina Insani Ceria**: `http://127.0.0.1:8000/y/YBIC/login`  
> 3. **Proteksi Login Lintas-Portal (Cross-Tenant Blocker):**  
>    Pengguna yayasan yang mencoba login di URL `/login` utama atau di portal yayasan lain akan **ditolak secara aman**, dan disajikan pop-up **SweetAlert2** interaktif dengan tombol *direct link* 1-klik menuju portal resmi yayasannya.

---

## 📌 1. Akun Level Platform / Owner (Lintas Tenant)
* **URL Login Resmi**: `http://127.0.0.1:8000/login`

| Role | Nama Pengguna | Email Login | Hak Akses & Deskripsi |
| :--- | :--- | :--- | :--- |
| **Owner / Super Admin SaaS** | Owner Platform SaaS | `owner@demo.com` | Akses penuh ke seluruh Yayasan (Tenant), Manajemen Sekolah, Modul System Hardening & Log Audit Pusat. |

---

## 🏛️ 2. Tenant 1: Yayasan Pendidikan Al-Hikmah 2
* **URL Portal Login Resmi**: `http://127.0.0.1:8000/y/YAH2/login` (atau `/y/YYS-001/login`)
* **URL Landing Page Yayasan**: `http://127.0.0.1:8000/y/YAH2`
* **Tema Visual**: `Natural Sage` (Dapat disesuaikan di /superadmin/landing-page)

### A. Level Yayasan (Pusat)
| Role | Nama Pengguna | Email Login | Hak Akses & Deskripsi |
| :--- | :--- | :--- | :--- |
| **Super Admin Yayasan** | Super Admin Al-Hikmah | `superadmin1@alhikmah.com` | Pengawasan agregat seluruh sekolah di bawah Yayasan Al-Hikmah 2 (TK Permata & TK Matahari). |

### B. Sekolah 1: TK Permata Al-Hikmah (`school_id = 1`)
* **Domain Internal / Setting**: `tkpermata.sch.id`

| Role | Nama Pengguna | Email Login | Kelas / Rombel |
| :--- | :--- | :--- | :--- |
| **Admin Sekolah** | Admin TK Permata | `admin1@tkpermata.sch.id` | Pengelolaan data master, user, rombel, & pengumuman. |
| **Kepala Sekolah** | Dr. Hj. Rahmawati, M.Pd | `kepsek1@tkpermata.sch.id` | Persetujuan materi LMS, pemantauan jurnal & rapor. |
| **Guru Kelas A** | Naning Setyowati, S.Pd | `guru.a1@tkpermata.sch.id` | Wali Kelas **TK-A Mangga** (20 Siswa) |
| **Guru Kelas B** | Siti Nurhaliza, S.Pd | `guru.b1@tkpermata.sch.id` | Wali Kelas **TK-B Apel** (20 Siswa) |

#### Contoh Akun Wali Murid (TK Permata):
* `ortu.1A.1@gmail.com` s/d `ortu.1A.20@gmail.com` *(Orang Tua Siswa TK-A Mangga)*
* `ortu.1B.1@gmail.com` s/d `ortu.1B.20@gmail.com` *(Orang Tua Siswa TK-B Apel)*
* 💡 **Akun Wali Murid Multi-Anak (Testing Child Switcher)**:  
  * **Email**: `ortu.1A.1@gmail.com`  
  * **Anak 1**: Siswa di TK-A Mangga  
  * **Anak 2**: Siswa di TK-B Apel (`1B-1`)

---

### C. Sekolah 2: TK Matahari Al-Hikmah (`school_id = 2`)
* **Domain Internal / Setting**: `tkmatahari.sch.id`

| Role | Nama Pengguna | Email Login | Kelas / Rombel |
| :--- | :--- | :--- | :--- |
| **Admin Sekolah** | Admin TK Matahari | `admin2@tkmatahari.sch.id` | Pengelolaan data master TK Matahari. |
| **Kepala Sekolah** | Drs. Bambang Hariyanto, M.M | `kepsek2@tkmatahari.sch.id` | Approval materi & manajemen sekolah. |
| **Guru Kelas A** | Dewi Lestari, S.Pd | `guru2@tkmatahari.sch.id` | Wali Kelas **TK-A Melati** (20 Siswa) |

#### Contoh Akun Wali Murid (TK Matahari):
* `ortu.2A.1@gmail.com` s/d `ortu.2A.20@gmail.com` *(Orang Tua Siswa TK-A Melati)*

---

## 🏛️ 3. Tenant 2: Yayasan Bina Insani Ceria
* **URL Portal Login Resmi**: `http://127.0.0.1:8000/y/YBIC/login` (atau `/y/YYS-002/login`)
* **URL Landing Page Yayasan**: `http://127.0.0.1:8000/y/YBIC`
* **Tema Visual**: `Ceria Playful` (Dapat disesuaikan di /superadmin/landing-page)

### A. Level Yayasan (Pusat)
| Role | Nama Pengguna | Email Login | Hak Akses & Deskripsi |
| :--- | :--- | :--- | :--- |
| **Super Admin Yayasan** | Super Admin Bina Insani | `superadmin2@binainsani.com` | Pengawasan agregat sekolah di bawah Yayasan Bina Insani Ceria (TK Bintang & TK Cendekia). |

### B. Sekolah 3: TK Bintang Insani (`school_id = 3`)
* **Domain Internal / Setting**: `tkbintang.sch.id`

| Role | Nama Pengguna | Email Login | Kelas / Rombel |
| :--- | :--- | :--- | :--- |
| **Admin Sekolah** | Admin TK Bintang | `admin3@tkbintang.sch.id` | Pengelolaan data master & pengguna TK Bintang. |
| **Kepala Sekolah** | Hj. Anisa Fitriani, M.Pd | `kepsek3@tkbintang.sch.id` | Persetujuan materi LMS & evaluasi rapor. |
| **Guru Kelas A** | Rina Gunawan, S.Pd | `guru.a3@tkbintang.sch.id` | Wali Kelas **TK-A Bintang Kecil** (20 Siswa) |
| **Guru Kelas B** | Budi Santoso, S.Pd | `guru.b3@tkbintang.sch.id` | Wali Kelas **TK-B Bintang Kejora** (20 Siswa) |

#### Contoh Akun Wali Murid (TK Bintang):
* `ortu.3A.1@gmail.com` s/d `ortu.3A.20@gmail.com` *(Orang Tua Siswa TK-A Bintang Kecil)*
* `ortu.3B.1@gmail.com` s/d `ortu.3B.20@gmail.com` *(Orang Tua Siswa TK-B Bintang Kejora)*

---

### C. Sekolah 4: TK Cendekia Insani (`school_id = 4`)
* **Domain Internal / Setting**: `tkcendekia.sch.id`

| Role | Nama Pengguna | Email Login | Kelas / Rombel |
| :--- | :--- | :--- | :--- |
| **Admin Sekolah** | Admin TK Cendekia | `admin4@tkcendekia.sch.id` | Pengelolaan data master TK Cendekia. |
| **Kepala Sekolah** | Ahmad Fauzi, M.Pd | `kepsek4@tkcendekia.sch.id` | Monitoring & approval. |
| **Guru Kelas A** | Maya Indah, S.Pd | `guru4@tkcendekia.sch.id` | Wali Kelas **TK-A Cerdas** (20 Siswa) |

#### Contoh Akun Wali Murid (TK Cendekia):
* `ortu.4A.1@gmail.com` s/d `ortu.4A.20@gmail.com` *(Orang Tua Siswa TK-A Cerdas)*

---

## 📊 4. Struktur Data Siswa per Kelas

Setiap kelas diisi secara konsisten **20 anak per kelas** (Total **120 Siswa** aktif di seluruh sistem):

1. **TK Permata**:
   * **TK-A Mangga**: 20 Anak (`Aditya Pratama (1A-1)` s/d `Zayyan Syahputra (1A-20)`)
   * **TK-B Apel**: 20 Anak (`Aditya Pratama (1B-1)` s/d `Zayyan Syahputra (1B-20)`)
2. **TK Matahari**:
   * **TK-A Melati**: 20 Anak (`Aditya Pratama (2A-1)` s/d `Zayyan Syahputra (2A-20)`)
3. **TK Bintang**:
   * **TK-A Bintang Kecil**: 20 Anak (`Aditya Pratama (3A-1)` s/d `Zayyan Syahputra (3A-20)`)
   * **TK-B Bintang Kejora**: 20 Anak (`Aditya Pratama (3B-1)` s/d `Zayyan Syahputra (3B-20)`)
4. **TK Cendekia**:
   * **TK-A Cerdas**: 20 Anak (`Aditya Pratama (4A-1)` s/d `Zayyan Syahputra (4A-20)`)

---

## 🧪 5. Skenario Pengujian yang Siap Dilakukan:

1. **Uji Isolasi Tenant & Security Hardening**:
   * Login sebagai `guru.a1@tkpermata.sch.id` dan coba akses data sekolah lain (sistem akan menolak/403 IDOR Protection).
2. **Uji Mode Pusat (Super Admin)**:
   * Login sebagai `superadmin1@alhikmah.com` untuk melihat agregasi data 2 sekolah di bawah Yayasan Al-Hikmah.
3. **Uji Approval Materi LMS (Guru -> Kepsek -> Ortu)**:
   * Login `guru.a1@tkpermata.sch.id`, buat materi baru (status `Pending`).
   * Login `kepsek1@tkpermata.sch.id`, buka menu **Persetujuan Materi** lalu setujui (status `Approved`).
   * Login `ortu.1A.1@gmail.com`, buka menu **Materi Belajar** untuk membaca materi.
4. **Uji Child Switcher (Wali Murid)**:
   * Login sebagai `ortu.1A.1@gmail.com`, gunakan dropdown pemilih anak di navbar untuk berpindah antara anak di TK-A dan anak di TK-B.
