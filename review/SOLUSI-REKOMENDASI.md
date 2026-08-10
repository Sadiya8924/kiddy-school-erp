# Laporan Solusi & Rekomendasi — Sistem TK Ceria
**Review Black-Box | Tanggal: 07 Agustus 2026**

> File ini dipetakan 1:1 ke kode temuan di `TEMUAN-MASALAH.md`.
> Prioritas: 🔴 Kritis (pra-launch wajib) → 🟠 Tinggi (sprint berikutnya) → 🟡 Sedang → 🔵 Rendah

---

## A. SECURITY

### [TM-01] Form Login — Ubah HTML `method` Menjadi `POST`

**Prioritas: 🔴 Kritis | Estimasi: 5 menit**

Ubah atribut `method` pada elemen `<form>` di Blade login dari `get` menjadi `post`. Jangan andalkan JavaScript untuk mengubah method HTTP — security tidak boleh bergantung pada JS.

```blade
{{-- resources/views/auth/login.blade.php --}}
<form method="POST" action="{{ route('login') }}">
    @csrf
    {{-- ... --}}
</form>
```

Hapus semua kode JavaScript yang saat ini mengubah method form secara runtime. Laravel's `Auth::attempt()` di controller sudah siap menerima POST.

---

### [TM-02] Security Headers — Tambahkan via `.htaccess` Web Server

**Prioritas: 🔴 Kritis | Estimasi: 30 menit**

*Selaras keputusan D9.2: header keamanan diatur di level web server (Apache `.htaccess`), bukan middleware Laravel.*

Tambahkan blok berikut di `public/.htaccess` (atau root `.htaccess`):

```apache
<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Permissions-Policy "camera=(), microphone=(), geolocation=()"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' cdn.jsdelivr.net unpkg.com; style-src 'self' 'unsafe-inline' cdn.jsdelivr.net; img-src 'self' data: *.tile.openstreetmap.org; font-src 'self' cdn.jsdelivr.net"
</IfModule>
```

Verifikasi: buka halaman lalu periksa tab **Network → Response Headers** di DevTools —
pastikan header-header di atas muncul.

> **Catatan InfinityFree:** `mod_headers` mungkin dibatasi di shared hosting. Jika header
> tidak muncul, gunakan fallback middleware Laravel sebagai cadangan:
> ```php
> // app/Http/Middleware/SecurityHeaders.php
> public function handle($request, Closure $next) {
>     $response = $next($request);
>     $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
>     $response->headers->set('X-Content-Type-Options', 'nosniff');
>     return $response;
> }
> ```

---

### [TM-03] Kredensial Demo — Kelola Risiko Selama Beta

**Prioritas: 🟡 Sedang | Estimasi: 30 menit**

*Selaras keputusan D9.1: kredensial demo DIPERTAHANKAN untuk keperluan showcase sistem beta.*

Karena sistem masih dalam tahap beta dan demo credentials memudahkan showcase ke calon klien,
tampilan kredensial di `/login` dipertahankan. Yang wajib dilakukan:

**1. Tambahkan label peringatan yang jelas:**
```blade
{{-- Di halaman login, di atas atau di bawah blok akun demo --}}
<div class="alert alert-warning small text-center mb-2">
    ⚠️ <strong>Sistem Beta</strong> — Akun di bawah untuk demonstrasi.
    Jangan masukkan data pribadi nyata.
</div>
```

**2. Pastikan data lingkungan beta hanya berisi data dummy** — tidak ada nama anak sungguhan,
NIK, nomor KK, atau dokumen nyata.

**3. Batasi hak akun demo** — akun demo tidak boleh bisa hapus data massal atau mengubah
konfigurasi sistem secara permanen.

**4. Wajib dilakukan sebelum go-live produksi (dengan data nyata):**
```php
// Hapus tampilan kredensial dari login page produksi:
// Di Blade login, bungkus blok demo dengan kondisi lingkungan:
@if(app()->environment('local', 'staging', 'demo'))
    {{-- blok akun demo --}}
@endif

// Ganti semua password default sebelum terima pengguna nyata:
User::whereIn('email', [
    'superadmin@tkceria.com', 'admin@tk.com', 'naning@tk.com', 'ortu@tk.com'
])->each(fn($u) => $u->update(['password' => bcrypt(str()->random(16))]));
```

---

### [TM-04] IDOR `/siswa/{id}` — Tambahkan Policy Authorization

**Prioritas: 🟠 Tinggi | Estimasi: 3-4 jam**

Buat Laravel Policy untuk model Siswa yang memverifikasi bahwa user hanya dapat mengakses siswa milik sekolahnya sendiri:

```php
// app/Policies/SiswaPolicy.php
public function view(User $user, Siswa $siswa): bool
{
    // Ortu hanya bisa lihat anak sendiri
    if ($user->role === 'ortu') {
        return $user->siswa()->where('id', $siswa->id)->exists();
    }
    // Guru & Admin hanya bisa lihat siswa sekolahnya
    return $user->school_id === $siswa->school_id;
}
```

```php
// app/Http/Controllers/SiswaController.php
public function show(Siswa $siswa)
{
    $this->authorize('view', $siswa);
    // ...
}
```

Daftarkan policy di `AuthServiceProvider`:
```php
protected $policies = [
    Siswa::class => SiswaPolicy::class,
];
```

Lakukan hal yang sama untuk endpoint `/kelas/{id}`, `/rapor/{id}`, `/perkembangan-fisik/{id}`.

---

### [TM-05] Storage PDF 404 — Setup Laravel Storage Link

**Prioritas: 🟠 Tinggi | Estimasi: 30 menit**

Jalankan perintah berikut di server (atau via deployment script):
```bash
php artisan storage:link
```

Jika InfinityFreeApp tidak mendukung symlink (free hosting sering tidak), gunakan alternatif:
1. Pindahkan file PDF ke direktori `public/arsip_rapor/` dan update path di database.
2. Atau buat controller yang melayani file dari storage dengan otorisasi:

```php
// routes/web.php
Route::get('/arsip/{filename}', [ArsipController::class, 'download'])->middleware('auth');

// app/Http/Controllers/ArsipController.php
public function download($filename)
{
    $path = storage_path('app/arsip_rapor/' . $filename);
    abort_unless(Storage::exists('arsip_rapor/' . $filename), 404);
    // tambahkan cek otorisasi di sini
    return response()->download($path);
}
```

---

### [TM-06] Pesan Error Login — Gunakan Pesan Generik

**Prioritas: 🟡 Sedang | Estimasi: 30 menit**

Pastikan controller Auth tidak membedakan pesan antara "email salah" dan "password salah":

```php
// Hindari:
return back()->withErrors(['email' => 'Email tidak ditemukan']);
return back()->withErrors(['password' => 'Password salah']);

// Gunakan pesan generik:
return back()->withErrors(['email' => 'Kombinasi email dan password tidak valid.']);
```

Aktifkan throttle pada login untuk mencegah brute-force:
```php
// routes/web.php
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
```

---

## B. MULTITENANCY & ISOLASI DATA

### [TM-07] Isolasi Antar-Yayasan — Tambahkan Tenant Kedua untuk Validasi

**Prioritas: 🟠 Tinggi | Estimasi: 1 hari**

Untuk memverifikasi isolasi multitenant yang benar, tambahkan minimal satu yayasan dan satu sekolah kedua ke dalam database seeder:

```php
// database/seeders/MultitenantTestSeeder.php
$yayasan2 = Yayasan::create(['nama' => 'Yayasan Al-Falah']);
$tk2 = Sekolah::create(['nama' => 'TK Al-Falah', 'yayasan_id' => $yayasan2->id]);
$superadmin2 = User::create([...role: 'superadmin', yayasan_id: $yayasan2->id]);
$admin2 = User::create([...role: 'admin', school_id: $tk2->id]);
```

Kemudian verifikasi secara manual bahwa `superadmin@yayasan2.com` **tidak dapat melihat** data sekolah, siswa, atau user dari Yayasan pertama.

Secara arsitektural, setiap query yang menyentuh data yang ter-scope ke yayasan harus menggunakan **Global Scope**:
```php
// app/Models/Siswa.php
protected static function booted(): void
{
    static::addGlobalScope('yayasan', function (Builder $builder) {
        if (auth()->check() && auth()->user()->yayasan_id) {
            $builder->whereHas('sekolah', fn($q) =>
                $q->where('yayasan_id', auth()->user()->yayasan_id)
            );
        }
    });
}
```

---

### [TM-08] Arsitektur Routing — Unifikasi Dashboard atau Dokumentasikan Pemisahan

**Prioritas: 🟠 Tinggi | Estimasi: 2-3 jam**

**Opsi A (Direkomendasikan):** Gunakan satu route `/dashboard` untuk semua peran, render berbeda via satu controller dengan kondisi:
```php
// app/Http/Controllers/DashboardController.php
public function index()
{
    return match(auth()->user()->role) {
        'superadmin' => view('dashboard.superadmin', [...]),
        'admin'      => view('dashboard.admin', [...]),
        'guru'       => view('dashboard.guru', [...]),
        'ortu'       => view('dashboard.ortu', [...]),
    };
}
```

Redirect `/superadmin/dashboard` ke `/dashboard` agar konsisten:
```php
Route::get('/superadmin/dashboard', fn() => redirect('/dashboard'));
```

**Opsi B:** Pertahankan pemisahan tapi pastikan semua middleware authorization diterapkan secara eksplisit pada setiap prefix:
```php
Route::prefix('superadmin')->middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard']);
    // ...
});
```

---

### [TM-09] Tahun Ajaran Multi-Sekolah — Tampilkan Konteks TA per Sekolah

**Prioritas: 🟡 Sedang | Estimasi: 2-3 jam**

Di dashboard Superadmin dan semua laporan lintas-sekolah, tampilkan selalu label "Tahun Ajaran Aktif" per-sekolah:

```blade
{{-- Di tabel daftar sekolah --}}
<td>{{ $sekolah->nama }}</td>
<td>{{ $sekolah->tahunAjaranAktif->label ?? '-' }}</td>  {{-- tampilkan TA tiap sekolah --}}
```

Buat validasi agar laporan rekap hanya membandingkan data antar-sekolah dalam TA yang sama, atau beri peringatan jika TA berbeda:
```php
$conflictingTA = $schools->where('active_ta', '!=', $baseTA)->count();
if ($conflictingTA > 0) {
    // kirim warning ke view
}
```

---

### [TM-10] Mode Pantau — Read-Only + "Ambil Alih" dengan Audit Log

**Prioritas: 🟡 Sedang | Estimasi: 2-3 jam**

*Selaras keputusan D1.2: Mode Pantau = read-only; untuk mengedit perlu klik "Ambil Alih"
eksplisit yang tercatat di audit log.*

**Alur:** Admin Yayasan buka daftar sekolah → klik "Pantau" → masuk mode baca saja →
bila perlu intervensi klik "Ambil Alih" → konfirmasi → aksi tercatat → selesai → "Lepas Kendali".

```blade
{{-- Indikator mode di header --}}
@if(session('ambil_alih_mode'))
<div class="alert alert-warning py-1 text-center mb-0">
    ⚡ Mode Ambil Alih: <strong>{{ $pantauSekolah->nama }}</strong>
    — <small>Semua aksi tercatat di audit log</small>
    <a href="{{ route('superadmin.lepas-kendali') }}"
       class="btn btn-sm btn-outline-danger ms-2">Lepas Kendali</a>
</div>
@elseif(session('pantau_mode'))
<div class="alert alert-info py-1 text-center mb-0">
    👁 Mode Pantau (Read-Only): <strong>{{ $pantauSekolah->nama }}</strong>
    <a href="{{ route('superadmin.ambil-alih', $pantauSekolah) }}"
       class="btn btn-sm btn-warning ms-2">⚡ Ambil Alih</a>
    <a href="{{ route('superadmin.clear-pantau') }}"
       class="btn btn-sm btn-outline-secondary ms-1">← Keluar</a>
</div>
@endif
```

```php
// Middleware: blokir aksi write saat mode pantau
// app/Http/Middleware/PantauReadOnly.php
public function handle($request, Closure $next)
{
    if (session('pantau_mode') && !session('ambil_alih_mode')) {
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            return back()->withErrors([
                'mode' => 'Anda dalam mode pantau (read-only). Klik "Ambil Alih" untuk mengedit.'
            ]);
        }
    }
    return $next($request);
}
```

```php
// Controller Ambil Alih:
public function ambilAlih(Sekolah $sekolah)
{
    $this->authorize('ambil-alih', $sekolah);
    session(['pantau_mode' => true, 'ambil_alih_mode' => true, 'pantau_school_id' => $sekolah->id]);
    AuditLog::create([
        'user_id' => auth()->id(), 'action' => 'ambil_alih',
        'entity_type' => 'school', 'entity_id' => $sekolah->id,
    ]);
    return redirect()->route('dashboard')->with('info', "Mode Ambil Alih aktif: {$sekolah->nama}");
}
```

---

## C. PRODUCT & UX

### [TM-11] Login Page — Sesuaikan Pesan Berdasarkan Konteks Multi-Role

**Prioritas: 🟠 Tinggi | Estimasi: 2 jam**

Ganti deskripsi yang spesifik "portal orang tua" menjadi narasi yang inklusif untuk semua peran, atau implementasikan dua halaman login terpisah:

**Opsi A — Satu halaman, narasi netral:**
```blade
<p>Masuk untuk mengakses Sistem Informasi TK Ceria sesuai peran Anda.</p>
```

**Opsi B (Direkomendasikan untuk UX yang lebih baik) — Dua pintu masuk:**
- `/login` → Portal Staf (Admin, Guru, Kepala Sekolah, Super Admin) — minimalis, profesional
- `/portal-ortu` → Portal Wali Murid — ramah, informatif, dengan fitur-fitur yang relevan

Ini juga memudahkan penerapan UI yang berbeda antara portal internal dan portal publik.

---

### [TM-12] Branding Footer — Generate Dinamis dari Data Tenant

**Prioritas: 🟡 Sedang | Estimasi: 1-2 jam**

Ganti teks footer hardcoded dengan data dari database:

```blade
{{-- resources/views/layouts/app.blade.php --}}
<footer>
    &copy; {{ date('Y') }}
    {{ $sekolahAktif->yayasan->nama ?? config('app.name') }}
    — {{ $sekolahAktif->nama ?? 'Sistem TK Ceria' }}
    v{{ config('app.version', '1.0') }}
</footer>
```

Hapus baris duplikat footer dan nama "Al-Hidayah" yang hardcoded.

Pastikan `$sekolahAktif` tersedia di semua view via View Composer:
```php
// app/Providers/AppServiceProvider.php
View::composer('layouts.app', function ($view) {
    $view->with('sekolahAktif', auth()->user()?->sekolah);
});
```

---

### [TM-13] Dashboard Guru — Sembunyikan Kontrol Admin

**Prioritas: 🟡 Sedang | Estimasi: 30 menit**

Gunakan directive Blade untuk menyembunyikan elemen UI yang bukan wewenang guru:

```blade
{{-- Tombol "Kelola Rombel" hanya untuk admin/kepsek --}}
@role('admin', 'kepsek')
<a href="{{ route('kelas.index') }}" class="btn btn-outline-primary btn-sm">
    Kelola Rombel →
</a>
@endrole
```

Atau jika tidak pakai package Spatie, gunakan:
```blade
@if(in_array(auth()->user()->role, ['admin', 'kepsek']))
    {{-- tombol kelola rombel --}}
@endif
```

---

### [TM-14] Halaman Publik — Deteksi Status Login

**Prioritas: 🔵 Rendah | Estimasi: 30 menit**

Di halaman `/pendaftaran-siswa` dan semua halaman publik, sesuaikan navigasi berdasarkan status autentikasi:

```blade
@auth
    <a href="{{ route('dashboard') }}" class="btn btn-primary">Ke Dashboard</a>
@else
    <a href="{{ route('login') }}" class="btn btn-primary">Login Portal</a>
    <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-primary">Daftar Sekarang</a>
@endauth
```

---

### [TM-15] Nama Guru "Unknown" — Perbaiki Relasi Database

**Prioritas: 🔵 Rendah | Estimasi: 1-2 jam**

Investigasi tabel materi/konten untuk memastikan kolom `user_id` atau `guru_id` terisi dengan benar. Kemungkinan penyebab:

1. Seeder memasukkan data materi dengan ID user yang tidak ada
2. Relasi Eloquent tidak didefinisikan dengan benar

Perbaikan:
```php
// app/Models/Materi.php
public function guru()
{
    return $this->belongsTo(User::class, 'user_id');
}
```

```blade
{{-- Di view persetujuan materi --}}
{{ $materi->guru->name ?? 'Tidak Diketahui' }}
```

Jalankan query untuk menemukan orphaned records:
```sql
SELECT * FROM materis WHERE user_id NOT IN (SELECT id FROM users);
```

---

### [TM-16] Duplikat Footer — Hapus Satu Instance

**Prioritas: 🔵 Rendah | Estimasi: 15 menit**

Cari duplikasi di layout Blade. Kemungkinan ada `@include('partials.footer')` yang dipanggil dua kali di `layouts/app.blade.php` atau di child view yang mengextend layout.

```bash
grep -rn "footer" resources/views/layouts/ resources/views/partials/
```

Hapus satu dari dua instance yang menghasilkan footer duplikat.

---

## D. FUNCTIONALITY & QA

### [TM-17] Data Materi Guru — Investigasi dan Perbaiki Broken Foreign Key

**Prioritas: 🟠 Tinggi | Estimasi: 2-4 jam**

Jalankan investigasi database:
```sql
-- Cek materi yang user_id-nya null atau tidak ada di tabel users
SELECT m.id, m.judul, m.user_id, u.name
FROM materis m
LEFT JOIN users u ON m.user_id = u.id
WHERE u.id IS NULL;
```

Jika seeder mengisi data materi dengan `user_id` yang salah, update sesuai akun guru yang benar:
```sql
UPDATE materis
SET user_id = (SELECT id FROM users WHERE email = 'naning@tk.com' LIMIT 1)
WHERE user_id IS NULL OR user_id NOT IN (SELECT id FROM users);
```

Untuk mencegah kejadian ulang, tambahkan foreign key constraint:
```php
// Di migration
$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
```

---

### [TM-18] Jurnal Harian Anak untuk Ortu — Selesaikan Route & Data Binding

**Prioritas: 🟡 Sedang | Estimasi: 3-4 jam**

Pastikan route `/jurnal-anak` atau fitur setara tersedia dan data jurnal ter-filter berdasarkan anak milik wali murid yang login:

```php
// routes/web.php
Route::middleware(['auth', 'role:ortu'])->group(function () {
    Route::get('/jurnal-anak/{siswa}', [JurnalOrtuController::class, 'show'])
         ->can('view', 'siswa');
});
```

```php
// app/Http/Controllers/JurnalOrtuController.php
public function show(Siswa $siswa)
{
    // Policy sudah verifikasi siswa adalah anak dari ortu yang login
    $jurnal = JurnalHarian::where('siswa_id', $siswa->id)
                ->orderByDesc('tanggal')
                ->paginate(20);
    return view('ortu.jurnal', compact('siswa', 'jurnal'));
}
```

---

### [TM-19] Tahun Ajaran Demo — Tambahkan Keterangan di UI atau Seed Ulang

**Prioritas: 🔵 Rendah | Estimasi: 30 menit**

Untuk menghindari kebingungan selama demo/beta, tambahkan label kecil "Data Demo" di header:

```blade
@if(app()->environment('staging', 'local'))
<div class="text-muted text-center small">
    ⚠️ Sistem dalam mode beta — beberapa data merupakan data demonstrasi
</div>
@endif
```

Atau seed ulang dengan tahun ajaran yang sesuai tanggal sistem (2025/2026 atau 2026/2027).

---

## E. TEMUAN TAMBAHAN (TM-20 s/d TM-29)
> Solusi untuk temuan iterasi kedua dan ketiga, selaras keputusan D1–D11.

### [TM-20] Storage 404 — Perbaiki Akses Aset Publik & Layanan File Privat

**Prioritas: 🟠 Tinggi | Estimasi: 2-3 jam**

*Selaras D8.1 (tetap InfinityFree, perbaiki path) dan D8.2 (file sensitif via route terotorisasi).*

**Track A — Aset publik (logo, gambar landing):**

InfinityFree tidak mendukung symlink `php artisan storage:link`. Buat Artisan command untuk
menyalin file ke `public/` saat deploy:

```php
// app/Console/Commands/PublishStorage.php
class PublishStorage extends Command
{
    protected $signature   = 'storage:publish';
    protected $description = 'Copy storage/app/public → public/storage (InfinityFree workaround)';

    public function handle(): void
    {
        $src  = storage_path('app/public');
        $dest = public_path('storage');
        if (!is_dir($dest)) mkdir($dest, 0755, true);
        $this->copyRecursive($src, $dest);
        $this->info('Storage published: ' . $dest);
    }
}
```

Jalankan `php artisan storage:publish` setiap kali ada file baru di-deploy.

**Track B — File sensitif (dokumen pendaftaran, rapor, media siswa):**

```php
// routes/web.php
Route::middleware('auth')->get('/file/{type}/{path}', [FileController::class, 'serve'])
     ->where('path', '.*')->name('file.serve');

// app/Http/Controllers/FileController.php
public function serve(string $type, string $path)
{
    abort_unless(in_array($type, ['rapor', 'dokumen', 'media']), 404);

    // Cek otorisasi per tipe (Policy masing-masing)
    match($type) {
        'rapor'   => $this->authorize('view-rapor', $path),
        'dokumen' => $this->authorize('view-dokumen', $path),
        'media'   => $this->authorize('view-media', $path),
    };

    $fullPath = storage_path("app/private/{$type}/{$path}");
    abort_unless(file_exists($fullPath), 404);
    return response()->file($fullPath);
}
```

---

### [TM-21] Pisahkan Platform Admin dari Admin Yayasan (3-Lapis Tenancy)

**Prioritas: 🔴 Kritis | Estimasi: 1-2 hari**

*Selaras D1.1 (3-lapis, DB bersama), D1.2 (Mode Pantau + audit log), D1.4 (onboarding manual).*

```php
// Gunakan spatie/laravel-permission. Role baru:
// platform_admin  → vendor/operator, akses semua yayasan
// admin_yayasan   → ter-scope ke satu yayasan_id (ganti "super admin" lama)
// admin_sekolah, kepala_sekolah, guru, wali_murid → tidak berubah namanya

// app/Scopes/YayasanScope.php
class YayasanScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();
        if ($user && !$user->hasRole('platform_admin') && $user->yayasan_id) {
            $builder->where($model->getTable() . '.yayasan_id', $user->yayasan_id);
        }
    }
}
// Terapkan ke model Sekolah, User (saat list), dan model lain yang ber-yayasan_id.
```

```php
// Migration: tabel audit_logs
Schema::create('audit_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->string('action');                                // 'ambil_alih', 'lepas_kendali', dll.
    $table->string('entity_type')->nullable();
    $table->unsignedBigInteger('entity_id')->nullable();
    $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
    $table->json('metadata')->nullable();
    $table->timestamps();
    $table->index(['user_id', 'created_at']);
    $table->index(['school_id', 'action']);
});
```

Pemisahan route: prefix `/platform` untuk platform_admin, prefix `/yayasan` untuk admin_yayasan,
`/dashboard` tetap untuk semua peran sekolah ke bawah.

---

### [TM-22] Alur Upload Media Kegiatan: Upload Sekali, Tag Banyak Siswa

**Prioritas: 🟡 Sedang | Estimasi: 4-6 jam**

*Selaras D6.1 (upload sekali → tag banyak), D6.2 (scope kelas guru), D6.3 (izin publikasi).*

```php
// Migration: tabel media_kegiatan
Schema::create('media_kegiatan', function (Blueprint $table) {
    $table->id();
    $table->foreignId('school_id')->constrained();
    $table->foreignId('uploaded_by')->constrained('users');
    $table->string('judul');
    $table->text('deskripsi')->nullable();
    $table->enum('tipe', ['foto', 'video']);
    $table->string('path');
    $table->date('tanggal_kegiatan');
    $table->timestamps();
    $table->index(['school_id', 'tanggal_kegiatan']);
});

// Migration: pivot media_kegiatan_siswa
Schema::create('media_kegiatan_siswa', function (Blueprint $table) {
    $table->id();
    $table->foreignId('media_kegiatan_id')->constrained('media_kegiatan')->cascadeOnDelete();
    $table->foreignId('siswa_id')->constrained()->cascadeOnDelete();
    $table->timestamps();
    $table->unique(['media_kegiatan_id', 'siswa_id']);
});

// Tambahkan ke tabel siswas:
$table->boolean('izin_publikasi_foto')->default(true);
```

```php
// Policy: guru hanya tag siswa di kelasnya
public function tagSiswa(User $user, MediaKegiatan $media): bool
{
    return $user->hasAnyRole(['guru', 'admin_sekolah', 'kepala_sekolah'])
        && $user->school_id === $media->school_id;
}
```

```blade
{{-- Galeri wali: hanya media yang men-tag anaknya --}}
@foreach($siswa->mediaKegiatan()->latest('tanggal_kegiatan')->get() as $media)
    <img src="{{ route('file.serve', ['media', $media->path]) }}"
         alt="{{ $media->judul }}" class="img-thumbnail">
@endforeach
```

---

### [TM-23] Tetapkan Kepala Sekolah Lewat Akun, Bukan Teks Bebas

**Prioritas: 🟡 Sedang | Estimasi: 2-3 jam**

*Selaras D7.1 (pilih dari akun), D7.2 (one-to-one), D7.3 (nama+TTD rapor otomatis + approver).*

```php
// Migration: ganti kolom teks → FK
Schema::table('sekolahs', function (Blueprint $table) {
    $table->dropColumn('kepala_sekolah');
    $table->foreignId('kepala_sekolah_user_id')
          ->nullable()->constrained('users')->nullOnDelete()->after('nama');
});
```

```php
// Validasi one-to-one di SekolahController:
$request->validate([
    'kepala_sekolah_user_id' => [
        'nullable',
        Rule::exists('users', 'id')->where(fn($q) =>
            $q->whereHas('roles', fn($r) => $r->where('name', 'kepala_sekolah'))
        ),
        Rule::unique('sekolahs', 'kepala_sekolah_user_id')
            ->ignore($sekolah->id)->whereNull('deleted_at'),
    ],
]);

// Model Sekolah:
public function kepalaSekolah()
{
    return $this->belongsTo(User::class, 'kepala_sekolah_user_id');
}
```

```blade
{{-- Di template rapor: TTD kepala sekolah otomatis --}}
<div class="ttd-kepsek text-center">
    <p>Mengetahui,</p><br><br>
    <p><strong>{{ $sekolah->kepalaSekolah?->name ?? '-' }}</strong></p>
    <p>Kepala {{ $sekolah->nama }}</p>
</div>
```

---

### [TM-24] Auto-Fetch Nama Orang Tua dari Data Pendaftaran

**Prioritas: 🟡 Sedang | Estimasi: 2 jam**

*Selaras D2.2 (auto dari pendaftaran, read-only) dan D2.4 (admin tautkan dari data pendaftaran).*

```php
// Di controller Link Ortu:
public function linkOrtu(Siswa $siswa)
{
    $this->authorize('manage-ortu', $siswa);
    $p = $siswa->pendaftaran; // relasi Siswa → Pendaftaran

    return view('siswa.link-ortu', [
        'siswa'      => $siswa,
        'nama_ayah'  => $p?->nama_ayah,
        'email_ayah' => $p?->email_ayah,
        'nama_ibu'   => $p?->nama_ibu,
        'email_ibu'  => $p?->email_ibu,
    ]);
}
```

```blade
{{-- Form Link Ortu: nama read-only, bersumber dari pendaftaran --}}
<div class="form-group">
    <label>Nama Ayah</label>
    <input type="text" value="{{ $nama_ayah ?? '-' }}" class="form-control bg-light" readonly>
    <small class="text-muted">Dari data pendaftaran.
        <a href="{{ route('pendaftaran.edit', $siswa->pendaftaran) }}">Edit data pendaftaran</a>
    </small>
</div>

{{-- Di rapor: ambil langsung dari relasi --}}
<tr><th>Nama Ayah</th><td>{{ $siswa->pendaftaran?->nama_ayah ?? '-' }}</td></tr>
<tr><th>Nama Ibu</th><td>{{ $siswa->pendaftaran?->nama_ibu ?? '-' }}</td></tr>
```

---

### [TM-25] Pemilih Semester Aktif Global Per-Sesi

**Prioritas: 🟡 Sedang | Estimasi: 4-6 jam**

*Selaras D3.1 (per-sesi, global), D3.2 (per-sekolah), D3.3 (scope semester_id, histori utuh).*

```php
// Migration: tabel semesters
Schema::create('semesters', function (Blueprint $table) {
    $table->id();
    $table->foreignId('school_id')->constrained();
    $table->string('tahun_ajaran');          // "2025/2026"
    $table->enum('semester', ['ganjil', 'genap']);
    $table->date('tanggal_mulai');
    $table->date('tanggal_selesai');
    $table->boolean('is_aktif')->default(false);
    $table->timestamps();
    $table->unique(['school_id', 'tahun_ajaran', 'semester']);
    $table->index(['school_id', 'is_aktif']);
});
```

```php
// Middleware: set semester aktif default saat sesi dimulai
public function handle($request, Closure $next)
{
    if (auth()->check() && !session()->has('active_semester_id')) {
        $sem = Semester::where('school_id', auth()->user()->school_id)
                       ->where('is_aktif', true)->first();
        if ($sem) session(['active_semester_id' => $sem->id]);
    }
    return $next($request);
}

// Route switcher:
Route::post('/semester/switch', function (Request $request) {
    $sem = Semester::where('id', $request->semester_id)
                   ->where('school_id', auth()->user()->school_id)->firstOrFail();
    session(['active_semester_id' => $sem->id]);
    return back()->with('success', "{$sem->tahun_ajaran} " . ucfirst($sem->semester));
})->middleware('auth')->name('semester.switch');
```

```blade
{{-- Switcher di header --}}
<form method="POST" action="{{ route('semester.switch') }}" class="d-inline">
    @csrf
    <select name="semester_id" class="form-select form-select-sm d-inline-block w-auto"
            onchange="this.form.submit()">
        @foreach($semesters as $s)
            <option value="{{ $s->id }}" {{ session('active_semester_id') == $s->id ? 'selected' : '' }}>
                {{ $s->tahun_ajaran }} {{ ucfirst($s->semester) }}
            </option>
        @endforeach
    </select>
</form>
```

Semua query yang memerlukan filter semester gunakan `session('active_semester_id')`:
```php
$absensi = Absensi::where('semester_id', session('active_semester_id'))
                  ->where('siswa_id', $siswa->id)->paginate(25);
```

---

### [TM-26] Paginasi & Filter Server-Side di Semua Daftar

**Prioritas: 🟠 Tinggi | Estimasi: 4-6 jam (per halaman daftar)**

*Selaras D4.1 (paginate + filter WHERE), D4.3 (indeks + eager loading).*

Pola standar yang diterapkan ke **semua** Controller `index`:

```php
// SiswaController@index (template berlaku untuk user, materi, absensi, rekap, dll.)
public function index(Request $request)
{
    $query = Siswa::with(['kelas', 'pendaftaran'])    // cegah N+1
        ->when($request->filled('search'), fn($q) =>
            $q->where('nama_lengkap', 'like', "%{$request->search}%"))
        ->when($request->filled('kelas_id'), fn($q) =>
            $q->where('kelas_id', $request->kelas_id))
        ->when($request->filled('status'), fn($q) =>
            $q->where('status', $request->status))
        ->orderBy('nama_lengkap');

    return view('siswa.index', [
        'siswa' => $query->paginate(25)->withQueryString(),
        'kelas' => Kelas::where('school_id', auth()->user()->school_id)->get(),
    ]);
}
```

```blade
{{-- Form filter (GET) + pagination links --}}
<form method="GET" action="{{ route('siswa.index') }}" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control" placeholder="Cari nama...">
    </div>
    <div class="col-auto">
        <select name="status" class="form-select">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
            <option value="alumni" {{ request('status')=='alumni'?'selected':'' }}>Alumni</option>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>
{{ $siswa->links() }}
```

```php
// Migration: indeks untuk kolom yang sering difilter (D4.3)
Schema::table('siswas', function (Blueprint $table) {
    $table->index(['school_id', 'status', 'nama_lengkap']);
    $table->index(['kelas_id', 'status']);
});
// Ulangi secara analog untuk: absensi (siswa_id, semester_id), jurnal, rapor, perkembangan
```

**Hapus semua:** DataTables client-side, filter JavaScript yang mengambil seluruh dataset,
`->get()` tanpa paginate pada daftar besar.

---

### [TM-27] Seeder Volume Realistis & Uji Beban

**Prioritas: 🟡 Sedang | Estimasi: 4 jam**

*Selaras D4.2 (seeder volume + uji beban sebelum go-live).*

```php
// database/seeders/VolumeSeeder.php
class VolumeSeeder extends Seeder
{
    public function run(): void
    {
        // 2 yayasan × 2 sekolah × 4 kelas × 50 siswa = 800 siswa
        // Tiap siswa: 132 absensi (22 hari × 6 bulan)
        Yayasan::factory(2)->has(
            Sekolah::factory(2)->has(
                Kelas::factory(4)->has(
                    Siswa::factory(50)
                         ->has(Absensi::factory(132))
                         ->has(PerkembanganFisik::factory(2))
                )
            )
        )->create();

        $this->command->info('VolumeSeeder: ' . Siswa::count() . ' siswa, '
            . Absensi::count() . ' absensi.');
    }
}
```

Setelah seeder, verifikasi performa:
```bash
# Aktifkan Laravel Telescope/Debugbar di staging, lalu buka:
# /data-siswa — periksa query count & response time
# Target: < 500 ms response, < 10 query per halaman
```

```sql
-- Audit query plan:
EXPLAIN ANALYZE
SELECT * FROM siswas
WHERE school_id = 1 AND status = 'aktif'
ORDER BY nama_lengkap LIMIT 25 OFFSET 0;
-- Pastikan ada "Index Scan" bukan "Seq Scan"
```

---

### [TM-28] Relasi Many-to-Many Wali–Siswa (Pivot `student_guardian`)

**Prioritas: 🟠 Tinggi | Estimasi: 4-6 jam**

*Selaras D2.1 (many-to-many + peran), D2.3 (satu akun wali, banyak anak lintas-status).*

```php
// Migration: pivot student_guardian
Schema::create('student_guardian', function (Blueprint $table) {
    $table->id();
    $table->foreignId('siswa_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->enum('peran', ['ayah', 'ibu', 'wali'])->default('wali');
    $table->boolean('is_wali_utama')->default(false);
    $table->timestamps();
    $table->unique(['siswa_id', 'user_id']);
    $table->index(['user_id']);
});
```

```php
// Model Siswa:
public function guardians()
{
    return $this->belongsToMany(User::class, 'student_guardian')
                ->withPivot('peran', 'is_wali_utama')->withTimestamps();
}

// Model User:
public function children()                // default: hanya anak aktif
{
    return $this->belongsToMany(Siswa::class, 'student_guardian', 'user_id', 'siswa_id')
                ->withPivot('peran', 'is_wali_utama')->withTimestamps()
                ->where('siswas.status', 'aktif');
}

public function allChildren()             // termasuk alumni (untuk riwayat)
{
    return $this->belongsToMany(Siswa::class, 'student_guardian', 'user_id', 'siswa_id')
                ->withPivot('peran', 'is_wali_utama')->withTimestamps();
}
```

```php
// Backfill dari relasi lama (jalankan sekali via Artisan command):
User::where('role', 'ortu')->whereNotNull('siswa_id')->each(function ($user) {
    DB::table('student_guardian')->insertOrIgnore([
        'siswa_id' => $user->siswa_id, 'user_id' => $user->id,
        'peran' => 'wali', 'is_wali_utama' => true,
        'created_at' => now(), 'updated_at' => now(),
    ]);
});
```

---

### [TM-29] Wizard Pendaftaran 7-Langkah dengan Resumable Draft

**Prioritas: 🟠 Tinggi | Estimasi: 2-3 hari**

*Selaras D5.1 (7 langkah), D5.2 (validasi per-langkah tapi resumable/incomplete),
D5.3 (auto-save), D5.4 (preview+validasi+kompres upload).*

**Pembagian langkah:**
1. Data Anak (nama, NIK, tempat/tgl lahir, jenis kelamin, agama, program — ~9 field)
2. Alamat & Lokasi (alamat lengkap, RT/RW, kelurahan, kecamatan, GPS Leaflet — ~10 field)
3. Data Ayah (nama, NIK, pendidikan, pekerjaan, penghasilan, no HP — ~6 field)
4. Data Ibu (nama, NIK, pendidikan, pekerjaan, penghasilan, no HP — ~6 field)
5. Data Fisik (tinggi, berat, golongan darah, riwayat penyakit — ~5 field)
6. Upload Dokumen (foto anak, foto KK, foto akte, bukti pembayaran — 4 file)
7. Review & Kirim (tampilkan semua data, tombol Submit)

```php
// Migration: kolom status + current_step di tabel pendaftarans
Schema::table('pendaftarans', function (Blueprint $table) {
    $table->string('session_token')->unique()->nullable()->after('school_id');
    $table->enum('status', ['incomplete','submitted','diproses','diterima','ditolak'])
          ->default('incomplete')->after('session_token');
    $table->unsignedTinyInteger('current_step')->default(1)->after('status');
});
```

```php
// PendaftaranController@saveStep
public function saveStep(Request $request, Pendaftaran $pendaftaran, int $step)
{
    $this->authorize('update', $pendaftaran);

    // Validasi field step ini (ringan — tidak blocking bila partial)
    $validated = $request->validate($this->rulesForStep($step));

    // Handle file upload step 6
    if ($step === 6) {
        foreach (['foto_anak','foto_kk','foto_akte','bukti_pembayaran'] as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)
                    ->store("private/dokumen/{$pendaftaran->id}");
            }
        }
    }

    $pendaftaran->fill($validated);
    $pendaftaran->current_step = max($pendaftaran->current_step, $step);
    $pendaftaran->save(); // status tetap 'incomplete' sampai submit final

    // Submit final di step 7: validasi menyeluruh baru dijalankan
    if ($step === 7 && $request->boolean('submit_final')) {
        $this->validateAllSteps($pendaftaran); // throw ValidationException bila ada yg kurang
        $pendaftaran->update(['status' => 'submitted']);
        return redirect()->route('pendaftaran.success', $pendaftaran);
    }

    return redirect()->route('pendaftaran.step', [
        'token' => $pendaftaran->session_token,
        'step'  => min($step + 1, 7),
    ])->with('success', "Langkah {$step} tersimpan. Anda bisa lanjut kapan saja.");
}
```

```blade
{{-- Indikator progres wizard --}}
<div class="d-flex justify-content-between mb-4">
    @foreach(['Data Anak','Alamat','Ayah','Ibu','Fisik','Dokumen','Review'] as $i => $label)
        <div class="text-center {{ $step > $i+1 ? 'text-success' : ($step == $i+1 ? 'fw-bold' : 'text-muted') }}">
            <div class="rounded-circle border d-inline-flex align-items-center justify-content-center"
                 style="width:32px;height:32px">{{ $i+1 }}</div>
            <div class="small">{{ $label }}</div>
        </div>
    @endforeach
</div>
```

Untuk kompres gambar di sisi klien sebelum unggah:
```html
<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2/dist/browser-image-compression.js"></script>
<script>
document.querySelectorAll('input[type="file"][accept*="image"]').forEach(input => {
    input.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;
        const compressed = await imageCompression(file, { maxSizeMB: 0.8, maxWidthOrHeight: 1280 });
        const dt = new DataTransfer();
        dt.items.add(new File([compressed], file.name, { type: compressed.type }));
        e.target.files = dt.files;
    });
});
</script>
```

---

### [TM-30] Hapus Aksi Delete dari Data Transaksional — Ganti dengan Edit Tercatat & Void

**Prioritas: 🟠 Tinggi | Estimasi: 3-4 jam**

*Selaras keputusan D12.1 (append-only, tanpa tombol hapus), D12.2 (hapus hanya untuk draft),
D12.3 (edit dengan riwayat + arsip/nonaktif), D12.4 (void finansial butuh approval kepsek).*

**Langkah 1 — Hapus route `destroy` dari controller transaksional:**

```php
// routes/web.php — JANGAN daftarkan method destroy untuk entitas transaksional
// Sebelum:
Route::resource('absensi', AbsensiController::class);          // include DELETE
Route::resource('jurnal', JurnalController::class);
Route::resource('rapor', RaporController::class);
Route::resource('perkembangan', PerkembanganController::class);
Route::resource('pembayaran', PembayaranController::class);

// Sesudah — kecualikan destroy:
Route::resource('absensi', AbsensiController::class)->except('destroy');
Route::resource('jurnal', JurnalController::class)->except('destroy');
Route::resource('rapor', RaporController::class)->except('destroy');
Route::resource('perkembangan', PerkembanganController::class)->except('destroy');
Route::resource('pembayaran', PembayaranController::class)->except(['destroy']);
// Pembayaran punya route void tersendiri (butuh approval):
Route::post('pembayaran/{pembayaran}/void', [PembayaranController::class, 'void'])
     ->middleware(['auth', 'can:void,pembayaran'])
     ->name('pembayaran.void');
```

**Langkah 2 — Proteksi FK `restrictOnDelete` untuk data master yang punya transaksi:**

```php
// Migration: FK dari transaksi ke data master wajib pakai restrictOnDelete
// Sehingga upaya hapus data master yang masih punya transaksi otomatis ditolak DB

Schema::table('absensi', function (Blueprint $table) {
    $table->foreign('siswa_id')->references('id')->on('siswas')
          ->restrictOnDelete(); // DB level protection
});
Schema::table('absensi', function (Blueprint $table) {
    $table->foreign('semester_id')->references('id')->on('semesters')
          ->restrictOnDelete();
});
// Terapkan ke semua tabel transaksional: jurnal, rapor, perkembangan, pembayaran
```

**Langkah 3 — Tabel `record_revisions` untuk audit per-field:**

```php
// Migration: riwayat perubahan tiap field transaksional
Schema::create('record_revisions', function (Blueprint $table) {
    $table->id();
    $table->string('model_type');              // 'Absensi', 'Jurnal', 'Rapor', dll.
    $table->unsignedBigInteger('model_id');
    $table->foreignId('user_id')->constrained(); // siapa yang mengubah
    $table->string('field');                   // nama field yang diubah
    $table->text('old_value')->nullable();
    $table->text('new_value')->nullable();
    $table->text('alasan')->nullable();        // alasan perubahan (wajib untuk rapor/finansial)
    $table->timestamps();
    $table->index(['model_type', 'model_id']);
    $table->index('user_id');
});
```

```php
// Trait RevisableModel — tambahkan ke model transaksional
trait RevisableModel
{
    protected static function bootRevisableModel(): void
    {
        static::updating(function ($model) {
            foreach ($model->getDirty() as $field => $newValue) {
                RecordRevision::create([
                    'model_type' => get_class($model),
                    'model_id'   => $model->getKey(),
                    'user_id'    => auth()->id(),
                    'field'      => $field,
                    'old_value'  => $model->getOriginal($field),
                    'new_value'  => $newValue,
                ]);
            }
        });
    }
}

// Gunakan di model transaksional:
class Absensi extends Model { use RevisableModel; }
class JurnalHarian extends Model { use RevisableModel; }
```

**Langkah 4 — Void/Batal untuk transaksi finansial dengan approval Kepsek:**

```php
// Enum status pembayaran yang mencakup void:
// 'pending', 'lunas', 'void' (bukan 'dihapus')

// PembayaranController@requestVoid (Admin Sekolah):
public function requestVoid(Request $request, Pembayaran $pembayaran)
{
    $this->authorize('request-void', $pembayaran); // hanya admin_sekolah
    $request->validate(['alasan' => 'required|min:10']);

    $pembayaran->update([
        'void_requested_at'  => now(),
        'void_reason'        => $request->alasan,
        'void_requested_by'  => auth()->id(),
    ]);
    // Kirim notifikasi ke Kepala Sekolah untuk approve
    $pembayaran->sekolah->kepalaSekolah->notify(new VoidPembayaranNotification($pembayaran));
    return back()->with('success', 'Permintaan void dikirim ke Kepala Sekolah untuk disetujui.');
}

// PembayaranController@approveVoid (Kepala Sekolah):
public function approveVoid(Request $request, Pembayaran $pembayaran)
{
    $this->authorize('approve-void', $pembayaran); // hanya kepala_sekolah
    $pembayaran->update(['status' => 'void', 'voided_at' => now(), 'voided_by' => auth()->id()]);
    AuditLog::create([
        'user_id' => auth()->id(), 'action' => 'void_pembayaran',
        'entity_type' => 'pembayaran', 'entity_id' => $pembayaran->id,
        'metadata' => ['alasan' => $pembayaran->void_reason],
    ]);
    return back()->with('success', "Pembayaran #{$pembayaran->id} berhasil di-void.");
}
```

**Langkah 5 — Hapus tombol Hapus dari Blade transaksional:**

```blade
{{-- Sebelum (HAPUS baris ini): --}}
<a href="{{ route('absensi.destroy', $absensi) }}"
   onclick="confirm('Hapus?')" class="btn btn-danger btn-sm">Hapus</a>

{{-- Sesudah: tampilkan tombol Edit, bukan Hapus --}}
<a href="{{ route('absensi.edit', $absensi) }}" class="btn btn-warning btn-sm">Edit/Koreksi</a>
{{-- Untuk pembayaran: tampilkan tombol Minta Void (bukan Hapus) --}}
@can('request-void', $pembayaran)
<button type="button" class="btn btn-outline-danger btn-sm"
        onclick="voidModal('{{ $pembayaran->id }}')">Ajukan Batal</button>
@endcan
```

**Draft yang boleh dihapus — hapus permanen hanya pada pendaftaran `incomplete`:**

```php
// PendaftaranController@destroy — hanya izinkan hapus bila masih draft
public function destroy(Pendaftaran $pendaftaran)
{
    $this->authorize('delete', $pendaftaran);
    abort_unless($pendaftaran->status === 'incomplete', 403,
        'Pendaftaran yang sudah disubmit tidak dapat dihapus.');
    $pendaftaran->delete();
    return redirect()->route('pendaftaran.index')->with('success', 'Draft pendaftaran dihapus.');
}
```

---

## F. SKENARIO PENGUJIAN & DATA SEED (S1–S5)

### S1 — Wali Murid dengan Banyak Anak

**Tujuan:** Pastikan satu akun wali bisa mengelola banyak anak dengan benar (UX, data, notifikasi).

**Prasyarat:** TM-28 (student_guardian) sudah diimplementasi. Akun `ortu@tk.com` ditautkan ke 3 anak.

**Langkah uji:**
1. Login sebagai `ortu@tk.com`
2. Dashboard harus menampilkan daftar semua anak dengan switcher/tab per anak
3. Klik tiap anak → absensi/jurnal/rapor ter-filter ke anak yang dipilih
4. Pastikan data anak A tidak tampil di profil anak B (zero cross-child leakage)
5. Trigger notifikasi untuk anak Rania → hanya notifikasi Rania yang muncul

**Hasil yang diharapkan:** Nol kebocoran data antar-anak; UI jelas menampilkan konteks anak aktif.

---

### S2 — Wali dengan Anak Alumni Lalu Punya Anak Baru

**Tujuan:** Data alumni tersimpan utuh tapi tidak mengotori tampilan aktif; wali bisa tautkan anak baru.

**Prasyarat:** Kolom `status` siswa (`aktif`, `alumni`); query default filter `status = 'aktif'`.

**Langkah uji:**
1. Set siswa "Anak Lama" → status `alumni`
2. Login wali → Dashboard hanya tampilkan anak aktif (Anak Lama tidak muncul)
3. Buka tab "Alumni" → Anak Lama muncul dengan data historis utuh
4. Admin buat siswa baru → tautkan ke akun wali yang sama
5. Login ulang → Dashboard tampilkan Anak Baru (aktif); Anak Lama tetap di tab Alumni
6. Data Anak Lama tidak hilang/tertimpa

**Hasil yang diharapkan:** Wali melihat anak aktif secara default; riwayat alumni tetap tersedia; satu akun untuk keduanya.

---

### S3 — Satu Anak Tertaut ke Dua Akun Wali (Ayah & Ibu)

**Tujuan:** Validasi relasi many-to-many student_guardian bekerja untuk dual guardian.

**Prasyarat:** TM-28 diimplementasi; dua akun user tersedia (`ayah@example.com`, `ibu@example.com`).

**Langkah uji:**
1. Admin tautkan "Siswa X" ke akun ayah (peran=ayah, is_wali_utama=true)
2. Admin tautkan "Siswa X" ke akun ibu (peran=ibu)
3. Login sebagai ayah → Siswa X muncul; lihat profil, absensi, rapor ✓
4. Login sebagai ibu → Siswa X muncul; data identik ✓
5. Guru input absensi → notifikasi terkirim ke KEDUA akun ✓
6. Uji isolasi: ayah tidak bisa akses siswa lain yang tidak ditautkan ke akunnya

**Hasil yang diharapkan:** Dua wali independen melihat data yang sama; notifikasi ke keduanya; isolasi dari siswa lain tetap terjaga.

---

### S4 — Performa dengan Dataset Volume Besar

**Tujuan:** Pastikan paginasi, filter server-side, dan indeks DB mampu menangani skala produksi.

**Prasyarat:** TM-26 (paginate) dan TM-27 (VolumeSeeder) sudah diimplementasi.

**Langkah uji:**
1. Jalankan `php artisan db:seed --class=VolumeSeeder`
2. Login sebagai admin sekolah terbesar (200+ siswa)
3. Buka `/data-siswa` → ukur response time (target < 500 ms)
4. Filter: cari nama, filter kelas, filter status → ukur tiap skenario
5. Buka rekap absensi semester aktif → ukur response time (dataset terbesar)
6. Aktifkan Laravel Debugbar: pastikan query count < 10 per halaman
7. Jalankan `EXPLAIN ANALYZE` pada query terberat; pastikan INDEX SCAN (bukan SEQ SCAN)
8. Navigasi ke halaman 5, 10, terakhir → data konsisten, tidak ada duplikat/lompat

**Hasil yang diharapkan:** Response time < 500 ms; tidak ada N+1; indeks digunakan; data konsisten di semua halaman.

---

### S5 — Kebijakan Delete: Tidak Ada Tombol Hapus pada Data Transaksional

**Tujuan:** Pastikan semua entitas transaksional tidak memiliki aksi hapus; koreksi via edit tercatat; void finansial butuh approval Kepala Sekolah.

**Prasyarat:** TM-30 sudah diimplementasi (route destroy dihapus, tombol hapus dibuang dari Blade, tabel `record_revisions` ada, route void pembayaran ada).

**Langkah uji:**

*Bagian A — Tidak ada tombol/route hapus:*
1. Login sebagai Admin Sekolah → buka daftar Absensi → pastikan tidak ada tombol "Hapus"
2. Coba akses URL `DELETE /absensi/{id}` langsung (via curl/DevTools) → harus 405 Method Not Allowed
3. Ulangi untuk: Jurnal, Rapor, Perkembangan Fisik
4. Pastikan `php artisan route:list` tidak menampilkan method DELETE untuk entitas-entitas di atas

*Bagian B — Koreksi via Edit tercatat:*
5. Edit status absensi siswa (misal: hadir → sakit) → simpan
6. Cek tabel `record_revisions`: harus ada 1 baris dengan `field='status'`, `old_value='hadir'`, `new_value='sakit'`, `user_id=<admin>`
7. Edit nilai rapor → cek riwayat di `record_revisions`

*Bagian C — Void pembayaran butuh approval Kepsek:*
8. Login Admin Sekolah → klik "Ajukan Batal" pada pembayaran → isi alasan → submit
9. Cek: status pembayaran berubah ke "pending_void" (bukan langsung void)
10. Login sebagai Kepala Sekolah → ada notifikasi permintaan void
11. Kepala Sekolah approve → status berubah ke "void", tercatat di `audit_logs`
12. Admin Sekolah tidak bisa langsung void tanpa approval Kepsek (coba akses route approve → 403)

*Bagian D — Draft boleh dihapus:*
13. Buat pendaftaran baru, tinggalkan di step 1 (status `incomplete`)
14. Login Admin → hapus draft tersebut → berhasil
15. Coba hapus pendaftaran yang sudah `submitted` → harus 403 Forbidden

**Hasil yang diharapkan:** Nol route DELETE pada entitas transaksional; riwayat edit tersimpan; void finansial melewati approval Kepsek; hanya draft yang bisa dihapus permanen.

---

### [TM-31] 🟡 SEDANG — Aksi Admin "Tambah Siswa" Memakai Halaman & Header Landing Page Publik

**Akar Masalah:** Tombol "Tambah Siswa" di `/data-siswa` me-link ke `/pendaftaran-siswa?school_id=1` (form publik, layout landing, `school_id` hardcode).

**Keputusan (D13):**
- D13.1 → Layout dinamis: admin terautentikasi mendapat layout aplikasi (sidebar, header admin)
- D13.2 → Form sama (wizard D5), tapi cabang terkunci & bukti_pembayaran opsional
- D13.3 → `school_id` dari konteks sekolah admin, bukan hardcode

---

**Langkah 1 — Layout Dinamis di Blade View Pendaftaran**

Ubah baris `@extends` di `resources/views/pendaftaran-siswa.blade.php` agar memilih layout berdasarkan status autentikasi:

```blade
{{-- resources/views/pendaftaran-siswa.blade.php --}}
@extends(auth()->check() ? 'layouts.app' : 'layouts.public')

@section('content')
    @if(auth()->check())
        {{-- Breadcrumb admin --}}
        <div class="page-header">
            <h4>Tambah Siswa Baru</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('data-siswa.index') }}">Data Siswa</a></li>
                    <li class="breadcrumb-item active">Tambah Siswa</li>
                </ol>
            </nav>
        </div>
    @endif

    {{-- Form wizard (sama untuk publik & admin) --}}
    @include('partials.wizard-pendaftaran')
@endsection
```

> **Alternatif lebih bersih:** Buat route admin terpisah `GET /data-siswa/create` yang me-render partial form yang sama dalam layout admin, dan biarkan `/pendaftaran-siswa` murni untuk publik. Pendekatan ini memisahkan concern lebih tegas.

---

**Langkah 2 — Prefill & Lock `school_id` dari Konteks Admin**

Di controller, turunkan `school_id` dari sekolah aktif admin (bukan dari query string):

```php
// app/Http/Controllers/PendaftaranController.php

public function create(Request $request): View
{
    // Untuk admin: ambil dari sekolah aktif scope, bukan ?school_id=...
    if (auth()->check()) {
        $schoolId = auth()->user()->school_id
            ?? session('active_school_id');

        // Validasi: school_id harus milik yayasan admin ini (cegah IDOR lintas-yayasan)
        $school = School::whereHas('yayasan', function ($q) {
            $q->where('id', auth()->user()->yayasan_id);
        })->findOrFail($schoolId);
    } else {
        // Publik: ambil dari ?school_id query param, validasi aktif & terbuka
        $school = School::where('is_open_registration', true)
            ->findOrFail($request->query('school_id'));
    }

    $tahunAjaran  = TahunAjaran::where('school_id', $school->id)->get();
    $isAdminInput = auth()->check();   // flag untuk view: matikan dropdown cabang & wajibkan bukti bayar

    return view('pendaftaran-siswa', compact('school', 'tahunAjaran', 'isAdminInput'));
}
```

---

**Langkah 3 — Sembunyikan Pilih Cabang & Buat Bukti Pembayaran Opsional (view)**

```blade
{{-- Di dalam form wizard, langkah "Pilih Cabang" --}}
@if(!$isAdminInput)
    {{-- Hanya publik yang memilih cabang --}}
    <div class="mb-3">
        <label>Pilih Cabang TK / PAUD Tujuan *</label>
        <select name="school_id" required>...</select>
    </div>
@else
    {{-- Admin: cabang dikunci, tampilkan nama saja --}}
    <input type="hidden" name="school_id" value="{{ $school->id }}">
    <div class="alert alert-info">
        Mendaftarkan siswa ke: <strong>{{ $school->nama }}</strong>
    </div>
@endif

{{-- Langkah Dokumen: bukti pembayaran opsional untuk admin --}}
<div class="mb-3">
    <label>Bukti Pembayaran {{ $isAdminInput ? '(Opsional — input admin)' : '*' }}</label>
    <input type="file" name="bukti_pembayaran"
           {{ $isAdminInput ? '' : 'required' }}
           accept="image/*,application/pdf">
</div>
```

---

**Langkah 4 — Update Link "Tambah Siswa" di Daftar Siswa**

Ubah link di `resources/views/data-siswa/index.blade.php` agar tidak hardcode `school_id`:

```blade
{{-- SEBELUM (salah) --}}
<a href="/pendaftaran-siswa?school_id=1" class="btn btn-primary">
    Tambah Siswa
</a>

{{-- SESUDAH (benar) --}}
<a href="{{ route('pendaftaran.create') }}" class="btn btn-primary">
    + Tambah Siswa
</a>
```

Dan daftarkan route yang tepat:

```php
// routes/web.php

// Route publik (tanpa auth)
Route::get('/pendaftaran-siswa', [PendaftaranController::class, 'create'])
    ->name('pendaftaran.create');

// Route admin (dengan auth + middleware scope sekolah)
Route::middleware(['auth', 'role:admin_sekolah|admin_yayasan'])
    ->get('/data-siswa/create', [PendaftaranController::class, 'create'])
    ->name('data-siswa.create');
```

> Kedua route bisa mengarah ke method `create()` yang sama karena `auth()->check()` di controller akan menangani perbedaan perilaku.

---

**Langkah 5 — Validasi Form Request: school_id Wajib Milik Yayasan Admin**

```php
// app/Http/Requests/StorePendaftaranRequest.php

public function rules(): array
{
    return [
        'school_id' => [
            'required', 'integer',
            Rule::exists('schools', 'id')->where(function ($query) {
                // Cegah IDOR: school harus dalam yayasan user ini
                if (auth()->check()) {
                    $query->where('yayasan_id', auth()->user()->yayasan_id);
                }
            }),
        ],
        'bukti_pembayaran' => [
            auth()->check() ? 'nullable' : 'required',
            'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048',
        ],
        // ... field lainnya
    ];
}
```

---

### S6 — Konteks Admin "Tambah Siswa": Layout & Scope Sekolah

**Tujuan:** Pastikan admin mendapat layout aplikasi (bukan landing), `school_id` otomatis dari konteks admin, dan bukti_pembayaran tidak diwajibkan.

**Prasyarat:** TM-31 sudah diimplementasi (layout dinamis, route admin, prefill `school_id`).

**Langkah uji:**

*Bagian A — Layout admin:*
1. Login sebagai Admin Sekolah → klik tombol "Tambah Siswa" di `/data-siswa`
2. Pastikan halaman yang terbuka menggunakan **layout aplikasi** (ada sidebar, header admin, tombol Logout)
3. Pastikan **tidak ada** link marketing "Beranda / Tentang Kami / Hubungi Kami / Login Portal" di navigasi
4. Pastikan ada breadcrumb: `Dashboard > Data Siswa > Tambah Siswa`

*Bagian B — `school_id` dari konteks admin:*
5. Buka DevTools → periksa hidden input `school_id` → nilainya harus sesuai sekolah admin yang login (bukan `1` hardcode)
6. Uji dengan akun admin sekolah lain (sekolah ID 2) → `school_id` harus berubah ke `2`
7. Coba submit form dengan `school_id` dimanipulasi (sekolah di luar yayasan) → harus gagal validasi (422 / pesan error)

*Bagian C — Bukti pembayaran opsional:*
8. Isi semua field wizard (sebagai admin) tanpa upload `bukti_pembayaran` → submit berhasil
9. Akses form yang sama tanpa login (publik) → `bukti_pembayaran` wajib diisi (validasi error jika dikosongkan)

*Bagian D — Pengguna publik tidak terpengaruh:*
10. Buka `/pendaftaran-siswa?school_id=1` tanpa login → tampil layout landing (nav marketing) seperti biasa
11. Field "Pilih Cabang" tampil (tidak dikunci) untuk pengguna publik

**Hasil yang diharapkan:** Admin mendapat UX konsisten dalam aplikasi; tidak ada context-switch ke landing page; school_id aman (dari scope tenant, tidak bisa dimanipulasi); bukti_pembayaran opsional hanya untuk admin.

---

### [TM-32] 🏗️ ARSITEKTUR — Custom Domain per Sekolah & per Yayasan

**Keputusan (D14):**
- D14.1 → Custom domain di level sekolah DAN yayasan
- D14.2 → Tabel `domains` + middleware `ResolveTenantFromHost`
- D14.3 → Provisioning manual + verifikasi DNS TXT
- D14.4 → Pindah ke VPS (Caddy/nginx) — prasyarat fitur ini; menggantikan D8.1

---

**Langkah 1 — Migrasi ke VPS (Prasyarat, menggantikan D8.1)**

Pindahkan aplikasi ke VPS dengan reverse proxy Caddy atau nginx:

```nginx
# /etc/caddy/Caddyfile — wildcard + on-demand TLS
{
    on_demand_tls {
        ask http://localhost:8080/check-domain
        interval 2m
        burst 5
    }
}

:443 {
    tls {
        on_demand
    }
    reverse_proxy 127.0.0.1:9000  # PHP-FPM atau Laravel Octane
}
```

Endpoint `GET /check-domain?domain=tkpermata.sch.id` (route internal, tidak diproteksi auth) memverifikasi bahwa domain ada di tabel `domains` sebelum Caddy menerbitkan sertifikat:

```php
// routes/web.php (internal Caddy check — bind ke 127.0.0.1 saja)
Route::get('/check-domain', function (Request $request) {
    $exists = \App\Models\Domain::where('domain', $request->query('domain'))
        ->where('is_verified', true)
        ->exists();
    return response('', $exists ? 200 : 404);
})->middleware('throttle:60,1');
```

Keuntungan migrasi VPS untuk temuan lama:
- **TM-05 & TM-20:** `php artisan storage:link` bekerja normal → 404 logo/landing terselesaikan
- **TM-02:** Header keamanan di `nginx.conf` / `Caddyfile` lebih andal dari `.htaccess` InfinityFree
- **S4 (TM-27):** Uji beban nyata diizinkan di VPS (InfinityFree melarangnya)

---

**Langkah 2 — Tabel `domains`**

```php
// database/migrations/xxxx_create_domains_table.php
Schema::create('domains', function (Blueprint $table) {
    $table->id();
    $table->foreignId('yayasan_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
    $table->string('domain')->unique();          // mis. tkpermata.sch.id
    $table->boolean('is_primary')->default(false);
    $table->boolean('is_verified')->default(false);
    $table->string('verification_token')->nullable(); // token untuk TXT record DNS
    $table->timestamp('verified_at')->nullable();
    $table->timestamps();

    // Satu dari dua FK harus terisi (level sekolah atau level yayasan)
    $table->index(['school_id', 'is_verified']);
    $table->index(['yayasan_id', 'is_verified']);
});
```

---

**Langkah 3 — Middleware `ResolveTenantFromHost`**

```php
// app/Http/Middleware/ResolveTenantFromHost.php
namespace App\Http\Middleware;

use App\Models\Domain;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResolveTenantFromHost
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $platformHost = parse_url(config('app.platform_url'), PHP_URL_HOST);

        // Host platform utama — tidak perlu resolve domain kustom
        if ($host === $platformHost) {
            return $next($request);
        }

        // Cari domain kustom yang sudah terverifikasi
        $domain = Domain::with(['school', 'yayasan'])
            ->where('domain', $host)
            ->where('is_verified', true)
            ->first();

        if (! $domain) {
            throw new NotFoundHttpException("Domain tidak dikenali: {$host}");
        }

        // Set konteks tenant di session & config dinamis
        if ($domain->school_id) {
            session(['active_school_id' => $domain->school_id]);
            session(['active_yayasan_id' => $domain->school->yayasan_id]);
        } elseif ($domain->yayasan_id) {
            session(['active_yayasan_id' => $domain->yayasan_id]);
            // school_id belum ditetapkan — portal yayasan (landing + pilih sekolah)
        }

        // Isolasi cookie/session per-domain
        Config::set('session.domain', $host);
        Config::set('app.url', 'https://' . $host);

        return $next($request);
    }
}
```

Daftarkan middleware **paling awal** di grup `web`:

```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\ResolveTenantFromHost::class,  // ← pertama
        // ... middleware lainnya
    ],
];
```

---

**Langkah 4 — Fallback Kompatibilitas `?school_id`**

Untuk kompatibilitas mundur (URL lama dengan `?school_id`) dan akses dari domain platform:

```php
// app/Http/Middleware/ResolveTenantFromHost.php — tambah di akhir handle()

// Fallback: jika school_id belum ter-set via host, cek query param
if (! session()->has('active_school_id') && $request->has('school_id')) {
    $schoolId = (int) $request->query('school_id');
    // Validasi school_id ada di DB sebelum set ke session
    if (\App\Models\School::where('id', $schoolId)->exists()) {
        session(['active_school_id' => $schoolId]);
    }
}
```

---

**Langkah 5 — Alur Verifikasi Domain (Provisioning DNS)**

```php
// app/Http/Controllers/Platform/DomainController.php

public function requestVerification(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'domain' => ['required', 'string', 'regex:/^[a-z0-9.-]+\.[a-z]{2,}$/i',
                     Rule::unique('domains', 'domain')],
        'school_id' => ['nullable', 'exists:schools,id'],
        'yayasan_id' => ['nullable', 'exists:yayasans,id'],
    ]);

    $token = 'tkceria-verify=' . Str::random(32);

    Domain::create([
        ...$validated,
        'verification_token' => $token,
        'is_verified' => false,
    ]);

    // Instruksikan Platform Admin untuk meminta klien tambah TXT record:
    // _tkceria-verify.tkpermata.sch.id → "tkceria-verify=<token>"

    return back()->with('success', "Tambahkan TXT record: _tkceria-verify.{$validated['domain']} → \"{$token}\"");
}

public function verifyDns(Domain $domain): RedirectResponse
{
    $txtRecords = dns_get_record('_tkceria-verify.' . $domain->domain, DNS_TXT);
    $verified = collect($txtRecords)->pluck('txt')->contains($domain->verification_token);

    if ($verified) {
        $domain->update(['is_verified' => true, 'verified_at' => now()]);
        return back()->with('success', 'Domain berhasil diverifikasi!');
    }

    return back()->withErrors(['domain' => 'TXT record belum ditemukan. Tunggu propagasi DNS (maks. 24 jam).']);
}
```

---

**Langkah 6 — Isolasi Cookie Session per Domain**

Agar cookie login `tkpermata.sch.id` tidak bocor ke `alhikmah.sch.id`:

```php
// config/session.php — nilai default saat tidak ada domain kustom
'domain' => env('SESSION_DOMAIN', null),
'secure' => env('SESSION_SECURE_COOKIE', true),
'same_site' => 'lax',
```

Middleware `ResolveTenantFromHost` di Langkah 3 sudah menangani override `Config::set('session.domain', $host)` secara dinamis.

---

**Langkah 7 — Guard Anti-Hijack Domain**

Validasi bahwa domain yang diklaim tidak bisa meng-hijack data sekolah/yayasan yang bukan miliknya:

```php
// app/Http/Requests/Platform\StoreDomainRequest.php
public function withValidator($validator): void
{
    $validator->after(function ($v) {
        // Platform Admin bisa menambah domain untuk siapa saja
        if (auth()->user()->hasRole('platform_admin')) return;

        // Admin Yayasan hanya bisa klaim domain untuk yayasannya
        if ($this->yayasan_id && $this->yayasan_id !== auth()->user()->yayasan_id) {
            $v->errors()->add('yayasan_id', 'Tidak bisa klaim domain untuk yayasan lain.');
        }

        // Admin Sekolah hanya bisa klaim domain untuk sekolahnya
        if ($this->school_id) {
            $school = \App\Models\School::find($this->school_id);
            if ($school?->yayasan_id !== auth()->user()->yayasan_id) {
                $v->errors()->add('school_id', 'Tidak bisa klaim domain untuk sekolah di luar yayasan Anda.');
            }
        }
    });
}
```

---

### S7 — Custom Domain: Resolusi Host & Isolasi Tenant

**Tujuan:** Pastikan domain kustom sekolah memetakan konteks yang benar; branding tenant tampil sesuai; session/cookie terisolasi; domain tidak sah ditolak.

**Prasyarat:** TM-32 sudah diimplementasi (VPS, tabel `domains`, middleware `ResolveTenantFromHost`, Caddy/nginx + SSL).

**Langkah uji:**

*Bagian A — Resolusi domain ke konteks sekolah:*
1. Tambahkan domain `tkpermata-test.local` ke tabel `domains` dengan `school_id=1`, `is_verified=true` (via seeder/artisan tinker untuk uji lokal)
2. Tambahkan entri `/etc/hosts`: `127.0.0.1 tkpermata-test.local`
3. Akses `https://tkpermata-test.local/` → halaman landing tampil dengan nama & logo TK Permata (bukan yayasan generik)
4. Akses `https://tkpermata-test.local/pendaftaran-siswa` → dropdown cabang menampilkan hanya TK Permata (tidak ada TK lain)
5. Cek `session('active_school_id')` via Tinker → harus `1`

*Bagian B — Isolasi session/cookie antar domain:*
6. Login sebagai admin di `tkpermata-test.local` → cookie session terikat ke domain ini
7. Akses `tkmatahari-test.local` (sekolah lain) dengan cookie yang sama → harus memerlukan login ulang (sesi tidak dikenali)
8. Pastikan `Set-Cookie` header memuat `Domain=tkpermata-test.local` (bukan domain wildcard)

*Bagian C — Domain tidak sah ditolak:*
9. Akses `unknown-school.test` yang tidak ada di tabel `domains` → HTTP 404
10. Coba klaim domain sekolah lain sebagai Admin Yayasan B → validasi gagal (403/422)

*Bagian D — SSL & provisioning:*
11. Akses domain kustom via HTTPS → sertifikat valid (tidak ada browser warning)
12. Jalankan `verifyDns()` tanpa TXT record → pesan error "TXT record belum ditemukan"
13. Tambahkan TXT record → `verifyDns()` → `is_verified=true`, `verified_at` terisi

*Bagian E — Fallback kompatibilitas:*
14. Akses `tkceria.example.com/pendaftaran-siswa?school_id=2` (platform host) → `active_school_id` di session = `2` (fallback `?school_id` masih bekerja)

**Hasil yang diharapkan:** Domain → sekolah ter-resolve dengan benar; branding menyesuaikan; cookie terisolasi; domain tak dikenal = 404; SSL valid; fallback `?school_id` tetap bekerja.

---

## [TM-33] Batasi Registrasi Publik: Hanya Wali, Role Dipaksa Server-Side

**Keputusan D15:** Registrasi publik tetap tersedia tetapi dikunci ke peran **Wali Murid**; nilai role dari klien diabaikan (dipaksa di server); wajib verifikasi email; tenant/sekolah ditentukan eksplisit.

### Langkah Implementasi

**a. Paksa role di Controller (jangan percaya input)**

```php
// app/Http/Controllers/Auth/RegisteredUserController.php

public function store(RegisterRequest $request): RedirectResponse
{
    // Role SELALU wali_murid, abaikan nilai apapun dari request
    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        // 'role' TIDAK diisi dari $request → tidak pernah dipercaya
    ]);

    // Assign role via spatie/laravel-permission
    $user->assignRole('wali_murid');

    // Pilih school dari konteks host (D14) atau dropdown eksplisit
    $schoolId = app('active_school_id') ?? $request->validated('school_id');
    if ($schoolId) {
        $user->schools()->attach($schoolId);
    }

    event(new Registered($user));

    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
}
```

**b. Hapus field `role` dari form dan dari `$fillable` model**

```php
// app/Models/User.php
protected $fillable = [
    'name', 'email', 'password',
    // TIDAK ada 'role' di sini
];
```

```blade
{{-- Hapus dari resources/views/auth/register.blade.php --}}
{{-- <input type="hidden" name="role" value="..."> ← HAPUS INI --}}
```

**c. Tambah pemilih sekolah ke form registrasi**

```blade
{{-- Untuk konteks multi-sekolah tanpa resolusi host --}}
<div class="mb-3">
    <label for="school_id">Sekolah Anak Anda</label>
    <select name="school_id" id="school_id" required>
        <option value="">— Pilih Sekolah —</option>
        @foreach($schools as $school)
            <option value="{{ $school->id }}">{{ $school->nama }}</option>
        @endforeach
    </select>
</div>
```

Jika akses lewat custom domain (D14), `active_school_id` sudah diketahui dari host → sembunyikan dropdown, isi otomatis.

**d. Form Request: validasi school_id ∈ sekolah yang diketahui publik**

```php
// app/Http/Requests/Auth/RegisterRequest.php
public function rules(): array
{
    return [
        'name'      => ['required', 'string', 'max:255'],
        'email'     => ['required', 'email', 'unique:users'],
        'password'  => ['required', 'confirmed', Rules\Password::defaults()],
        'school_id' => ['required', 'exists:schools,id,is_active,1'],
        // 'role' tidak ada di rules → otomatis diabaikan
    ];
}
```

**e. Implementasi `MustVerifyEmail` + middleware `verified`**

```php
// app/Models/User.php
class User extends Authenticatable implements MustVerifyEmail
{
    // ...
}
```

```php
// routes/web.php — lindungi route yang membutuhkan akun terverifikasi
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // dst.
});
```

**f. Throttle registrasi**

```php
// app/Http/Kernel.php atau routes/web.php
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('throttle:5,1');  // maks 5 registrasi per menit per IP
```

### Skenario S8 — Validasi Keamanan Registrasi

**Tujuan:** Pastikan manipulasi field role tidak berhasil; verifikasi email ditegakkan; throttle aktif.

**Prasyarat:** TM-33 sudah diimplementasi, mailer aktif (TM-34).

**Langkah uji:**

*Bagian A — Role tidak bisa dimanipulasi:*
1. Buka `/register` → DevTools → ubah `<input type="hidden" name="role" value="super_admin">`
2. Submit form dengan data valid
3. ✅ Periksa database: `users` baru harus memiliki role `wali_murid`, **bukan** `super_admin`
4. Verifikasi via Tinker: `User::latest()->first()->getRoleNames()` → `["wali_murid"]`

*Bagian B — Verifikasi email ditegakkan:*
5. Registrasi dengan email baru yang valid
6. **Tanpa** mengklik link verifikasi email → akses `/dashboard`
7. ✅ Harus di-redirect ke halaman "Verifikasi Email Anda" (bukan dashboard)
8. Klik link verifikasi → `/dashboard` berhasil diakses

*Bagian C — Throttle aktif:*
9. Kirim POST ke `/register` 6 kali berturut-turut dalam 1 menit
10. ✅ Percobaan ke-6 harus mendapat respons 429 Too Many Requests

**Hasil yang diharapkan:** Role dari klien diabaikan; akun wali terbentuk dengan benar; email wajib diverifikasi sebelum akses penuh; IP yang berlebihan di-throttle.

---

## [TM-34] Alur Reset/Lupa Password via Email Token

**Keputusan D16:** Aktifkan alur reset password mandiri standar Laravel; tambah link "Lupa Password?" di halaman login; setup SMTP sebagai infrastruktur email bersama (fondasi untuk TM-33 verifikasi email dan notifikasi masa depan).

### Langkah Implementasi

**a. Aktifkan rute Laravel bawaan**

```php
// routes/web.php
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email')
    ->middleware('throttle:3,1');  // maks 3 permintaan reset/menit/IP

Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');
Route::post('reset-password', [NewPasswordController::class, 'update'])
    ->name('password.update');
```

**b. Tambah link di halaman login**

```blade
{{-- resources/views/auth/login.blade.php --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <label for="password">Password</label>
    @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="text-sm text-muted">
            Lupa Password?
        </a>
    @endif
</div>
```

**c. Konfigurasi SMTP mailer**

```env
# .env (VPS — D14.4)
MAIL_MAILER=smtp
MAIL_HOST=mail.example.com      # host SMTP sekolah/yayasan, atau Mailgun/SES
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=noreply@tkceria.com
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=noreply@tkceria.com
MAIL_FROM_NAME="TK Ceria"
```

**d. Notifikasi reset berbahasa Indonesia**

```php
// app/Notifications/ResetPasswordNotification.php
public function toMail($notifiable): MailMessage
{
    return (new MailMessage)
        ->subject('Permintaan Reset Password — ' . config('app.name'))
        ->greeting('Halo ' . $notifiable->name . '!')
        ->line('Kami menerima permintaan reset password untuk akun Anda.')
        ->action('Reset Password Sekarang', $this->resetUrl($notifiable))
        ->line('Link ini berlaku selama **60 menit**.')
        ->line('Jika Anda tidak meminta reset password, abaikan email ini.')
        ->salutation('Salam, Tim ' . config('app.name'));
}
```

**e. Throttle pesan error anti-enumerasi**

```php
// Pesan error SAMA untuk email terdaftar maupun tidak terdaftar
// (sudah default di Laravel — jangan ubah menjadi "email tidak ditemukan")
return back()->with('status', __('passwords.sent'));
```

**Perlu diperhatikan:** TM-34 dan TM-33 (verifikasi email) berbagi infrastruktur SMTP yang sama. Setup SMTP cukup dilakukan sekali untuk keduanya. Migrasi ke VPS (D14.4) sangat mempermudah konfigurasi ini.

---

## [TM-35] Halaman Legal Nyata + Mekanisme Consent (UU PDP)

**Keputusan D17:** Buat halaman Kebijakan Privasi dan Syarat & Ketentuan yang dapat diakses; ganti `href="#"` di footer; tambahkan checkbox consent tersurat di form pendaftaran dan registrasi; simpan timestamp dan versi consent.

### Langkah Implementasi

**a. Rute dan view halaman legal**

```php
// routes/web.php
Route::get('/kebijakan-privasi', function () {
    return view('legal.privacy');
})->name('legal.privacy');

Route::get('/syarat-ketentuan', function () {
    return view('legal.terms');
})->name('legal.terms');
```

```blade
{{-- resources/views/legal/privacy.blade.php --}}
@extends('layouts.public')

@section('title', 'Kebijakan Privasi — ' . config('app.name'))

@section('content')
<div class="container py-5">
    <h1>Kebijakan Privasi</h1>
    <p><em>Berlaku sejak: 1 Agustus 2026 | Versi: 1.0</em></p>

    <h2>1. Data yang Kami Kumpulkan</h2>
    <p>Sistem TK Ceria mengumpulkan data berikut demi keperluan administrasi pendidikan anak:</p>
    <ul>
        <li>Data identitas anak: nama, NIK, tanggal lahir, jenis kelamin, foto</li>
        <li>Dokumen keluarga: Kartu Keluarga (scan), akta kelahiran (scan)</li>
        <li>Lokasi domisili: alamat, koordinat geografis (untuk keperluan Dapodik)</li>
        <li>Data orang tua/wali: nama, NIK, pekerjaan, nomor telepon</li>
        <li>Data akademik: absensi, perkembangan belajar, rapor, jurnal guru</li>
    </ul>

    <h2>2. Dasar Hukum Pemrosesan</h2>
    <p>Pemrosesan data dilakukan berdasarkan persetujuan tersurat orang tua/wali (UU PDP Pasal 20)
    dan pemenuhan kewajiban administrasi pendidikan (Permendikbud terkait Dapodik).</p>

    <h2>3. Hak Subjek Data</h2>
    <p>Sesuai UU PDP, Anda berhak untuk: mengakses data, mengoreksi data yang tidak akurat,
    dan mengajukan keberatan atas pemrosesan tertentu. Hubungi admin sekolah atau email
    <a href="mailto:privasi@tkceria.com">privasi@tkceria.com</a>.</p>

    {{-- Lanjutkan sesuai kebutuhan dan review konsultan hukum --}}
</div>
@endsection
```

**b. Ganti `href="#"` di footer**

```blade
{{-- resources/views/layouts/public.blade.php atau komponen footer --}}
{{-- SEBELUM: --}}
<a href="#">Kebijakan Privasi</a>
<a href="#">Syarat & Ketentuan</a>

{{-- SESUDAH: --}}
<a href="{{ route('legal.privacy') }}">Kebijakan Privasi</a>
<a href="{{ route('legal.terms') }}">Syarat & Ketentuan</a>
```

**c. Checkbox consent di form pendaftaran siswa (wizard D5, langkah terakhir "Review & Kirim")**

```blade
{{-- Langkah 7: Review & Kirim --}}
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="consent_privasi"
           id="consent_privasi" value="1" required>
    <label class="form-check-label" for="consent_privasi">
        Saya menyetujui <a href="{{ route('legal.privacy') }}" target="_blank">Kebijakan Privasi</a>
        dan <a href="{{ route('legal.terms') }}" target="_blank">Syarat & Ketentuan</a>
        terkait pemrosesan data pribadi anak saya sesuai UU PDP No. 27/2022.
    </label>
</div>
```

**d. Simpan timestamp dan versi consent**

```php
// Migrasi: tambah kolom ke tabel pendaftaran / users
Schema::table('pendaftaran', function (Blueprint $table) {
    $table->timestamp('consent_at')->nullable();
    $table->string('consent_version', 20)->nullable();  // mis. "1.0"
    $table->string('consent_ip', 45)->nullable();
});

// Controller saat menyimpan pendaftaran
$pendaftaran->update([
    'consent_at'      => now(),
    'consent_version' => '1.0',
    'consent_ip'      => $request->ip(),
]);
```

**e. Checkbox consent di form registrasi wali (`/register`)**

```blade
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="consent" id="consent" value="1" required>
    <label class="form-check-label" for="consent">
        Saya setuju dengan <a href="{{ route('legal.privacy') }}" target="_blank">Kebijakan Privasi</a>
        dan <a href="{{ route('legal.terms') }}" target="_blank">Syarat & Ketentuan</a>.
    </label>
</div>
```

> **Catatan:** Dokumen Kebijakan Privasi dan Syarat & Ketentuan harus ditinjau oleh konsultan hukum sebelum go-live. Konten di atas adalah template minimal — isi lengkap disesuaikan dengan praktik pemrosesan data aktual dan kewajiban UU PDP spesifik usaha.

---

## [TM-36] Halaman Error Ber-Branding Tenant

**Keputusan D18:** Buat view error kustom untuk kode 404, 403, 419, dan 500 yang menggunakan branding tenant dan menyediakan navigasi balik yang kontekstual.

### Langkah Implementasi

**a. Buat file view error kustom**

Laravel secara otomatis menggunakan view dari `resources/views/errors/{code}.blade.php` jika ada.

```blade
{{-- resources/views/errors/layout.blade.php (shared untuk semua error) --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') — {{ $tenant->nama ?? config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
<div class="text-center p-5">
    @if($tenant?->logo)
        <img src="{{ Storage::url($tenant->logo) }}" alt="{{ $tenant->nama }}" class="mb-4" style="max-height:64px">
    @endif
    <h1 class="display-1 text-muted fw-bold">@yield('code')</h1>
    <h2 class="h4 mb-3">@yield('title')</h2>
    <p class="text-muted mb-4">@yield('message')</p>

    @auth
        <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">Kembali ke Dashboard</a>
    @else
        <a href="{{ url('/') }}" class="btn btn-primary me-2">Kembali ke Beranda</a>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary">Login</a>
    @endauth
</div>
</body>
</html>
```

```blade
{{-- resources/views/errors/404.blade.php --}}
@extends('errors.layout')
@section('code', '404')
@section('title', 'Halaman Tidak Ditemukan')
@section('message', 'Maaf, halaman yang Anda cari tidak ada atau sudah dipindahkan.')
```

```blade
{{-- resources/views/errors/403.blade.php --}}
@extends('errors.layout')
@section('code', '403')
@section('title', 'Akses Ditolak')
@section('message', 'Anda tidak memiliki izin untuk mengakses halaman ini.')
```

```blade
{{-- resources/views/errors/419.blade.php --}}
@extends('errors.layout')
@section('code', '419')
@section('title', 'Sesi Kedaluwarsa')
@section('message', 'Sesi Anda telah habis. Silakan muat ulang halaman dan coba lagi.')
```

```blade
{{-- resources/views/errors/500.blade.php --}}
@extends('errors.layout')
@section('code', '500')
@section('title', 'Terjadi Kesalahan Server')
@section('message', 'Maaf, terjadi kesalahan pada server. Tim kami telah diberitahu. Silakan coba beberapa saat lagi.')
```

**b. Inject branding tenant ke semua error view**

```php
// app/Exceptions/Handler.php
public function render($request, Throwable $e)
{
    $response = parent::render($request, $e);

    // Inject tenant branding ke semua error view
    if ($response instanceof \Illuminate\Http\Response) {
        try {
            $tenant = app('active_tenant');  // dari ResolveTenantFromHost (D14) atau session
        } catch (\Throwable) {
            $tenant = null;
        }
        View::share('tenant', $tenant);
    }

    return $response;
}
```

---

## [TM-37] Portal Ortu Multi-Anak: Pemilih Anak Global + Scope Per-Anak

**Keputusan D19:**
- D19.1 Pemilih anak global di header (mirip pola switcher semester D3); simpan `active_child_id` di sesi; semua modul otomatis mengikuti anak terpilih.
- D19.2 Perbaiki routing Jurnal & Materi agar membawa `child_id` anak aktif; Materi query berdasarkan kelas anak aktif.
- D19.3 Semua modul ortu menggunakan semester aktif (D3); label tahun ajaran seragam.
- D19.4 "Tambah Anak" = tautkan anak yang sudah terdaftar via NISN, bukan form pendaftaran ulang.

### Langkah Implementasi

**a. Session active_child_id + middleware/view-composer**

```php
// app/Http/Middleware/SetActiveChild.php
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetActiveChild
{
    public function handle(Request $request, \Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole('wali_murid')) {
            $children = Auth::user()->siswa()  // relasi via student_guardian (D2)
                ->where('status', 'aktif')
                ->orderBy('nama_lengkap')
                ->get(['id', 'nama_lengkap', 'kelas_id']);

            // Set anak aktif dari sesi, atau default ke anak pertama
            $activeId = session('active_child_id');
            if (!$activeId || !$children->pluck('id')->contains($activeId)) {
                $activeId = $children->first()?->id;
                session(['active_child_id' => $activeId]);
            }

            // Inject ke semua view
            view()->share('waliChildren', $children);
            view()->share('activeChild', $children->firstWhere('id', $activeId));
        }
        return $next($request);
    }
}
```

```php
// routes/web.php — rute ganti anak aktif
Route::post('/switch-child', function (Request $request) {
    $childId = (int) $request->input('child_id');
    $children = Auth::user()->siswa()->pluck('id');
    abort_unless($children->contains($childId), 403);  // validasi kepemilikan
    session(['active_child_id' => $childId]);
    return back();
})->middleware(['auth', 'role:wali_murid'])->name('switch-child');
```

**b. Komponen switcher di header (mirip pola D3)**

```blade
{{-- resources/views/components/child-switcher.blade.php --}}
@if(Auth::check() && Auth::user()->hasRole('wali_murid'))
<form method="POST" action="{{ route('switch-child') }}" class="d-inline-flex align-items-center me-3">
    @csrf
    <label class="me-2 text-sm fw-semibold">Anak:</label>
    <select name="child_id" class="form-select form-select-sm w-auto"
            onchange="this.form.submit()">
        @foreach($waliChildren as $child)
            <option value="{{ $child->id }}"
                {{ $activeChild?->id == $child->id ? 'selected' : '' }}>
                {{ $child->nama_lengkap }}
            </option>
        @endforeach
    </select>
</form>
@endif
```

**c. Controller rapor/fisik/jurnal/materi — default ke active_child_id**

```php
// app/Http/Controllers/Wali/RaporController.php
public function index()
{
    $childId = session('active_child_id');
    $siswa = Auth::user()->siswa()->findOrFail($childId); // 404 jika bukan milik wali
    // ... query rapor berdasarkan $siswa->id dan session('active_semester_id') (D3)
    return view('wali.rapor', compact('siswa', 'rapor'));
}

// Fungsi bantu: validasi anak milik wali (Policy)
// app/Policies/SiswaPolicy.php
public function viewChild(User $user, Siswa $siswa): bool
{
    return $user->siswa()->where('id', $siswa->id)->exists();
}
```

**d. Perbaiki blade tombol kartu dashboard — sertakan anak_id**

```blade
{{-- resources/views/wali/dashboard.blade.php (sebelumnya — SALAH) --}}
<a href="/materi-anak">Jurnal Harian</a>    {{-- ← tidak membawa child_id --}}
<a href="/materi">Materi Pelajaran</a>       {{-- ← tidak membawa child_id --}}

{{-- SESUDAH PERBAIKAN --}}
<a href="{{ route('wali.jurnal', $anak->id) }}">Jurnal Harian</a>
<a href="{{ route('wali.materi', $anak->id) }}">Materi Pelajaran</a>
```

**e. Materi — query berdasarkan kelas anak aktif**

```php
// app/Http/Controllers/Wali/MateriController.php
public function index()
{
    $childId = session('active_child_id');
    $siswa = Auth::user()->siswa()->with('kelas')->findOrFail($childId);
    $semesterId = session('active_semester_id');  // D3

    $materi = Materi::where('kelas_id', $siswa->kelas_id)
        ->where('semester_id', $semesterId)
        ->where('status', 'disetujui')
        ->latest()
        ->get();

    return view('wali.materi', compact('siswa', 'materi'));
}
```

**f. Alur "Tautkan Anak" via NISN (menggantikan link ke /pendaftaran-siswa)**

```php
// routes/web.php
Route::get('/tambah-anak', [Wali\TautkanAnakController::class, 'create'])
    ->middleware(['auth', 'role:wali_murid'])->name('wali.tambah-anak');
Route::post('/tambah-anak', [Wali\TautkanAnakController::class, 'store'])
    ->middleware(['auth', 'role:wali_murid']);
```

```php
// app/Http/Controllers/Wali/TautkanAnakController.php
public function store(Request $request)
{
    $request->validate(['nisn' => 'required|digits:10']);

    $siswa = Siswa::where('nisn', $request->nisn)
        ->where('school_id', /* sekolah konteks */)
        ->firstOrFail(); // 404 jika NISN tidak ditemukan di sekolah yang benar

    // Cegah duplikat tautan
    if (!Auth::user()->siswa()->where('id', $siswa->id)->exists()) {
        Auth::user()->siswa()->attach($siswa->id, [
            'peran' => 'wali',
            'is_wali_utama' => false,
        ]);
    }

    return redirect()->route('dashboard')->with('success',
        "Anak {$siswa->nama_lengkap} berhasil ditautkan ke akun Anda.");
}
```

### Skenario S9 — Uji Portal Ortu Multi-Anak

**Tujuan:** Pastikan pemilih anak berfungsi lintas modul; routing per-anak benar; IDOR diblokir; periode semester konsisten.

**Prasyarat:** TM-37 sudah diimplementasi; `ortu@tk.com` memiliki 3 anak (Rania id=1, Sasa id=15, Amar id=18); `session('active_semester_id')` terdefinisi (D3).

**Langkah uji:**

*Bagian A — Pemilih anak global:*
1. Login sebagai `ortu@tk.com` → dashboard tampilkan 3 anak (Rania, Sasa, Amar)
2. Pilih "Sasa" di switcher header → halaman ter-refresh
3. ✅ `/rapor-anak` menampilkan rapor Sasa (bukan Rania)
4. ✅ `/perkembangan-anak` menampilkan data fisik Sasa
5. ✅ `/materi-anak` (jurnal) menampilkan jurnal Sasa
6. ✅ `/materi` menampilkan materi sesuai kelas Sasa

*Bagian B — Routing per-anak dari kartu dashboard:*
7. Di dashboard, klik "Jurnal Harian" pada kartu Amar
8. ✅ Halaman menampilkan jurnal Amar (bukan Rania)
9. ✅ Switcher header otomatis menampilkan "Amar" (active_child_id = 18)

*Bagian C — IDOR diblokir:*
10. Login sebagai `ortu@tk.com` → akses `/rapor-anak/2` (bukan milik wali)
11. ✅ Respons HTTP 404 (bukan redirect ke rapor anak yang salah)
12. Akses `/rapor-anak/1`, `/rapor-anak/15`, `/rapor-anak/18` → ✅ HTTP 200 (anak milik)

*Bagian D — Semester konsisten:*
13. Atur `active_semester_id` = semester X → semua modul menampilkan data semester X
14. ✅ Label tahun ajaran seragam di rapor/fisik/jurnal/materi

**Hasil yang diharapkan:** Satu switcher anak di header mengontrol semua modul; routing per-anak dari kartu benar; IDOR 404; periode semester seragam.

---

## [TM-38] Paginasi + Hapus Form Inline Per-Baris + Kotak Cari

**Keputusan D21:** Refactor semua daftar ke `->paginate()`; pindahkan aksi per-baris (set-kelas, tautkan-ortu) ke modal on-demand; tambah cari nama/NISN server-side; whitelist `per_page`.

### Langkah Implementasi

**a. Refactor Controller index → paginate + filter server-side**

```php
// app/Http/Controllers/Admin/SiswaController.php
public function index(Request $request)
{
    $query = Siswa::with(['kelas', 'wali'])  // eager load untuk N+1 prevention (D4)
        ->where('school_id', auth()->user()->activeSchoolId());

    // Filter kelas
    if ($request->filled('kelas_id')) {
        $query->where('kelas_id', $request->kelas_id);
    }

    // Filter tahun induk
    if ($request->filled('tahun_inden')) {
        $query->where('tahun_inden', $request->tahun_inden);
    }

    // Kotak cari nama/NISN server-side
    if ($request->filled('q')) {
        $q = $request->q;
        $query->where(function ($sub) use ($q) {
            $sub->where('nama_lengkap', 'LIKE', "%{$q}%")
                ->orWhere('nisn', 'LIKE', "%{$q}%")
                ->orWhere('nik', 'LIKE', "%{$q}%");
        });
    }

    // Whitelist per_page — mencegah per_page=999999 DoS
    $perPage = in_array((int) $request->per_page, [10, 25, 50, 100]) ? (int) $request->per_page : 25;

    $siswa = $query->orderBy('nama_lengkap')->paginate($perPage)->withQueryString();

    return view('admin.siswa.index', compact('siswa'));
}
```

**b. Hapus form inline per-baris — ganti dengan modal on-demand**

```blade
{{-- SEBELUM (berat: form set-kelas + tautkan-ortu di setiap baris) --}}
@foreach($siswa as $s)
<tr>
    <td>{{ $s->nama_lengkap }}</td>
    <td>
        <form method="POST" action="/data-siswa/set-kelas">  {{-- 12 kontrol/baris --}}
            @csrf <select name="kelas_id">...</select>
            <input type="hidden" name="siswa_id" value="{{ $s->id }}">
            <button type="submit">Simpan</button>
        </form>
    </td>
    ...
</tr>
@endforeach

{{-- SESUDAH (ringan: tombol buka modal — 2 kontrol/baris) --}}
@foreach($siswa as $s)
<tr>
    <td>{{ $s->nama_lengkap }}</td>
    <td>{{ $s->kelas?->nama_kelas ?? '—' }}</td>
    <td>{{ $s->wali_utama?->name ?? '—' }}</td>
    <td>
        <button type="button" class="btn btn-sm btn-outline-secondary"
                data-bs-toggle="modal"
                data-bs-target="#modalAksi"
                data-siswa-id="{{ $s->id }}"
                data-siswa-nama="{{ $s->nama_lengkap }}">
            Kelola
        </button>
    </td>
</tr>
@endforeach
```

```blade
{{-- Modal tunggal di bawah halaman — load form via AJAX saat dibuka --}}
<div class="modal fade" id="modalAksi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" id="modalAksiBody">
            {{-- Konten dimuat via fetch('/data-siswa/{id}/modal') saat modal dibuka --}}
            <div class="text-center p-4"><div class="spinner-border"></div></div>
        </div>
    </div>
</div>

<script>
document.getElementById('modalAksi').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    const id  = btn.dataset.siswaId;
    fetch(`/data-siswa/${id}/modal`)
        .then(r => r.text())
        .then(html => { document.getElementById('modalAksiBody').innerHTML = html; });
});
</script>
```

**c. Kotak cari + kontrol paginasi di Blade**

```blade
{{-- resources/views/admin/siswa/index.blade.php --}}
<form method="GET" action="{{ route('admin.siswa.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="q" class="form-control"
               placeholder="Cari nama atau NISN..." value="{{ request('q') }}">
    </div>
    <div class="col-md-3">
        <select name="kelas_id" class="form-select">
            <option value="">Semua Kelas</option>
            @foreach($kelasList as $k)
                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="per_page" class="form-select">
            @foreach([10,25,50,100] as $n)
                <option value="{{ $n }}" {{ $siswa->perPage() == $n ? 'selected' : '' }}>{{ $n }}/hal</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Cari</button>
    </div>
</form>

{{-- Tabel (ringan, tanpa form inline) --}}
<table class="table table-sm">
    ...
</table>

{{-- Paginasi bawaan Laravel --}}
{{ $siswa->links() }}
<p class="text-muted small">
    Menampilkan {{ $siswa->firstItem() }}–{{ $siswa->lastItem() }} dari {{ $siswa->total() }} siswa
</p>
```

**d. Tambah indeks kolom filter di migrasi**

```php
// database/migrations/xxxx_add_filter_indexes_to_siswa.php
Schema::table('siswas', function (Blueprint $table) {
    $table->index(['school_id', 'kelas_id']);         // filter utama
    $table->index(['school_id', 'status']);            // filter status aktif/alumni
    $table->index(['school_id', 'nama_lengkap']);      // cari nama
    $table->index('nisn');                             // cari NISN
});
```

### Skenario S10 — Uji Skala Daftar & Mode Pusat

**Tujuan:** Pastikan daftar responsif pada data besar; paginasi berfungsi; cari bekerja di server; Mode Pusat menampilkan ringkasan; `per_page` besar ditolak.

**Prasyarat:** TM-38 & TM-39 sudah diimplementasi; seeder 500+ siswa sudah berjalan (S4/TM-27).

**Langkah uji:**

1. Jalankan `php artisan db:seed --class=LargeSiswaSeeder` (500 siswa di TK Permata)
2. Login sebagai Admin TK → akses `/data-siswa`
3. ✅ Halaman muat ≤1 detik (paginasi 25/hal, bukan 500 baris sekaligus)
4. ✅ Ukuran HTML < 50KB (bukan 208KB apalagi 7MB)
5. ✅ Kontrol paginasi muncul di bawah tabel (halaman 1/20)
6. Klik "halaman berikutnya" → ✅ 25 baris berikutnya muncul (server-side)
7. Isi kotak cari "Rania" → ✅ Hanya baris bernama Rania yang tampil (bukan filter JS)
8. Akses `/data-siswa?per_page=99999` → ✅ Tetap menampilkan 25/hal (whitelist)
9. Login sebagai Super Admin → Mode Pusat → `/data-siswa`
10. ✅ Tampil ringkasan: jumlah siswa per sekolah (bukan daftar detail kosong)
11. ✅ Tidak ada counter "18 Siswa" di atas tabel kosong
12. Pilih "Pantau: TK Permata" → ✅ Daftar detail muncul dengan paginasi

**Hasil yang diharapkan:** Daftar 500+ siswa tetap cepat; paginasi, filter, dan cari berfungsi di server; Mode Pusat = ringkasan; `per_page` besar ditolak; form modal tidak muncul di tabel (hanya tombol).

---

## [TM-39] Mode Pusat Super Admin: Ringkasan Agregat + Counter Konsisten

**Keputusan D20:** Mode Pusat menampilkan **ringkasan jumlah per sekolah** (bukan daftar detail per baris); daftar detail baru muncul setelah sekolah dipilih; counter diselaraskan dengan scope query tabel.

### Langkah Implementasi

**a. Deteksi mode pusat di Controller**

```php
// app/Http/Controllers/Admin/SiswaController.php
public function index(Request $request)
{
    $activeSchool = session('active_school_id');  // null = Mode Pusat

    // Mode Pusat → tampilkan ringkasan per sekolah
    if (!$activeSchool && Auth::user()->hasRole('platform_admin')) {
        $ringkasan = Siswa::select('school_id', \DB::raw('count(*) as total'))
            ->groupBy('school_id')
            ->with('sekolah:id,nama')
            ->get();

        return view('superadmin.siswa.ringkasan', compact('ringkasan'));
    }

    // Mode Pantau / sekolah dipilih → daftar normal (+ paginasi TM-38)
    $query = Siswa::where('school_id', $activeSchool)->with('kelas');
    // ... (sama dengan TM-38)
    $siswa = $query->paginate(25)->withQueryString();
    $total = $query->toBase()->getCountForPagination(); // sama query = counter konsisten

    return view('admin.siswa.index', compact('siswa', 'total'));
}
```

**b. View ringkasan Mode Pusat**

```blade
{{-- resources/views/superadmin/siswa/ringkasan.blade.php --}}
@extends('layouts.superadmin')
@section('title', 'Ringkasan Siswa — Semua Sekolah')

@section('content')
<h2>Ringkasan Data Siswa (Mode Pusat)</h2>
<p class="text-muted">Pilih sekolah di atas untuk melihat daftar detail siswa.</p>

<table class="table table-bordered">
    <thead>
        <tr><th>Sekolah</th><th class="text-center">Jumlah Siswa</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    @foreach($ringkasan as $r)
        <tr>
            <td>{{ $r->sekolah->nama }}</td>
            <td class="text-center">{{ $r->total }}</td>
            <td>
                <form method="POST" action="{{ route('switch-school') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="school_id" value="{{ $r->school_id }}">
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        Pantau Sekolah Ini
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr class="fw-bold">
            <td>Total</td>
            <td class="text-center">{{ $ringkasan->sum('total') }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
@endsection
```

**c. Counter konsisten dengan tabel (mode non-pusat)**

```blade
{{-- Gunakan $siswa->total() dari paginator — BUKAN query COUNT terpisah --}}
<div class="badge bg-secondary mb-2">{{ $siswa->total() }} Siswa</div>
{{-- Ini selalu konsisten karena paginator menghitung dari query yang sama --}}
```

---

## Roadmap Prioritas Perbaikan (Diperbarui)

```
SPRINT -1 — Migrasi Infra (sebelum sprint lain, 1 minggu):
  [TM-32] Setup VPS + Caddy/nginx reverse proxy            1 hari
  [TM-32] Migrasi aplikasi InfinityFree → VPS              1 hari
  [TM-32] Tabel domains + middleware ResolveTenantFromHost  4 jam
  [TM-32] On-demand TLS (Caddy) + endpoint /check-domain   3 jam
  [TM-05, TM-20] Storage:link bekerja di VPS (otomatis)   30 menit
  ──────────────────────────────────────────────────────────────
  Total estimasi: ~3 hari

SPRINT 0 — Pra-Launch Wajib (≤ 1 minggu):
  [TM-01] Form login method → POST                         5 menit
  [TM-02] Security headers via nginx.conf/Caddyfile (VPS)  30 menit
  [TM-06] Throttle login + pesan seragam + kebijakan pw   1 jam
  [TM-04] Policy IDOR + Global Scope tenant/sekolah        4 jam
  [TM-20] Perbaiki path aset publik (logo/landing)         2 jam
  [TM-21] Pisahkan role platform_admin vs admin_yayasan    1 hari
  [TM-33] Paksa role wali di server + hapus hidden field   2 jam
  [TM-34] Aktifkan rute forgot/reset password + SMTP       3 jam
  ──────────────────────────────────────────────────────────────
  Total estimasi: ~3 hari

SPRINT 1 — Fondasi Arsitektur (minggu 2-3):
  [TM-30] Hapus route destroy transaksional + void flow    4 jam
  [TM-28] Pivot student_guardian + backfill                6 jam
  [TM-25] Tabel semesters + session switcher global        6 jam
  [TM-38] Paginate(25) + hapus form inline + cari + indeks 6 jam  ← D21 (perkuat D4)
  [TM-39] Mode Pusat → view ringkasan + counter konsisten  3 jam  ← D20
  [TM-26] (terselesaikan oleh TM-38 di atas)
  [TM-23] FK kepala_sekolah_user_id (ganti teks bebas)    3 jam
  [TM-07] Seed tenant kedua + verifikasi isolasi           1 hari
  [TM-08] Unifikasi routing dashboard per peran            3 jam
  [TM-35] Halaman legal + consent form (checkbox + DB)     4 jam
  S8 — Uji keamanan registrasi (role, verifikasi, throttle) 1 jam

SPRINT 2 — Fitur Alur Utama (minggu 4-5):
  [TM-37] Switcher anak global + scope per-anak + tautkan 1 hari  ← D19
  S9 — Uji portal ortu multi-anak (switcher, IDOR, semester) 1 jam
  [TM-29] Wizard pendaftaran 7-langkah + resumable         3 hari
  [TM-31] Layout dinamis "Tambah Siswa" + scope school_id  4 jam
  [TM-22] Media kegiatan: upload-sekali → tag siswa        6 jam
  [TM-24] Auto-fetch nama ortu dari pendaftaran            2 jam
  [TM-27] Volume seeder + uji beban (S4, S10)              4 jam
  S1, S2, S3 — Skenario uji multi-wali & alumni            2 jam

SPRINT 3 — Branding & Kualitas (minggu 6-7):
  [TM-11,12,14,16] Branding white-label dinamis            1 hari
  [TM-13] UI per-peran permission-driven (D11.3)           4 jam
  [TM-17,15] FK materi→guru + constraint + backfill        4 jam
  [TM-09,10] Konteks TA + mode pantau + audit log          3 jam
  [TM-05] Private file controller (dokumen/rapor/media)    3 jam
  [TM-36] View error ber-branding (404/403/419/500)        2 jam

SPRINT 4 — Polish & Teknis (minggu 8):
  [TM-18] Jurnal harian untuk wali murid (diselesaikan TM-37) lihat S9
  [TM-19] Seed data TA sesuai tanggal sistem               30 menit
  [TM-03] Hapus/kondisionalkan kredensial demo (go-live)   15 menit
  Audit IDOR + isolasi antar-tenant (S1–S4 formal)         1 hari
  Uji beban final                                          1 hari
  S5 — Uji kebijakan delete transaksional                  2 jam
  S6 — Uji konteks admin "Tambah Siswa" (layout + scope)  1 jam
  [TM-32] Verifikasi domain kustom + provisioning SSL      4 jam
  S7 — Uji custom domain: resolusi, isolasi cookie, SSL   2 jam
```

---

## Catatan Arsitektural (Diperbarui)

1. **Global Scopes per Model** — Setiap model yang ter-scope ke yayasan/sekolah/semester harus memiliki Global Scope otomatis (`YayasanScope`, `SekolahScope`). Developer tidak perlu mengingat kondisi `where()` manual di setiap query — ini fondasi isolasi multitenant.

2. **spatie/laravel-permission** — Gunakan sejak awal untuk keenam role (`platform_admin`, `admin_yayasan`, `admin_sekolah`, `kepala_sekolah`, `guru`, `wali_murid`). Permission granular per-fitur (`view-rapor`, `approve-materi`, `manage-siswa`) memungkinkan menu navigasi dibangkitkan dari permission secara terpusat (selaras D11.3).

3. **Audit Log** — Setiap aksi penting (Ambil Alih sekolah, edit data siswa, cetak rapor, kenaikan kelas, edit pengaturan tenant) dicatat di `audit_logs`. Krusial untuk sistem yang mengelola data anak-anak; menjadi bukti akuntabilitas saat Mode Ambil Alih digunakan (D1.2).

4. **Storage dua-jalur** — Aset publik (logo, landing): `php artisan storage:publish` → `public/storage/`. File sensitif (dokumen pendaftaran, rapor, media siswa): `storage/app/private/`, dilayani via route terotorisasi dengan Policy. Jangan pernah expose path file sensitif di URL yang bisa ditebak/diakses tanpa autentikasi.

5. **Semester sebagai context** — Semua entitas yang ber-scope semester (`absensi`, `jurnal`, `rapor`, `perkembangan`, `rekap`) harus menyimpan `semester_id`. Gunakan `session('active_semester_id')` sebagai global filter. Data lama tidak pernah dihapus — hanya berbeda lensa tampilan (D3.3).

6. **Wizard dengan status `incomplete`** — Jangan simpan draft pendaftaran di localStorage; simpan di DB dengan `status='incomplete'`. Browser bisa tutup, ganti device, atau sesi habis — draft harus tetap bisa di-resume via `session_token` atau login akun wali yang sedang mendaftar (D5.2).

7. **Struktur folder storage per tenant** — File upload disimpan dalam struktur `private/{tipe}/{yayasan_id}/{school_id}/{filename}` untuk memudahkan isolasi, backup per-tenant, dan audit trail kepemilikan file.

8. **Custom domain & infra VPS (D14)** — Migrasi ke VPS adalah prasyarat custom domain (D14.4) dan sekaligus menyelesaikan TM-05, TM-20 (storage symlink), TM-02 (header keamanan via nginx/Caddy lebih andal dari `.htaccess`), serta memungkinkan uji beban nyata (S4). Tabel `domains` + middleware `ResolveTenantFromHost` memetakan `Host` header ke konteks sekolah/yayasan. Cookie diisolasi per-domain (`Config::set('session.domain', $host)`). Verifikasi kepemilikan domain via DNS TXT record. Caddy on-demand TLS untuk SSL otomatis per-domain.

9. **Infrastruktur email/SMTP sebagai fondasi bersama** — TM-33 (verifikasi email registrasi) dan TM-34 (reset password) keduanya membutuhkan mailer yang aktif. Setup SMTP dilakukan **sekali** di Sprint 0 dan menjadi fondasi untuk notifikasi masa depan (pengumuman, rapor tersedia, pengingat absensi). Di VPS (D14.4), konfigurasi SMTP via Postfix/relay atau layanan transaksional (Mailgun, SES, Brevo) jauh lebih mudah dibanding InfinityFree yang membatasi koneksi keluar. Gunakan `config/mail.php` dengan nilai dari `.env`; jangan hardcode kredensial SMTP di kode sumber. Pertimbangkan queue (`QUEUE_CONNECTION=database`) untuk pengiriman email asinkron agar response time form tidak terpengaruh.

10. **Aksi per-baris = modal on-demand, bukan form tersemat** — Setiap aksi yang hanya relevan untuk satu entitas dalam daftar (set kelas, tautkan ortu, atur status, cetak) harus dibuka via **modal yang dimuat secara lazy** (fetch/AJAX saat tombol diklik), bukan form HTML yang dirender di setiap baris. Pola ini memotong bobot DOM secara drastis: dari ~12 kontrol/baris menjadi ~2 kontrol/baris (tombol), sehingga tabel 500 baris tetap ringan (~50KB vs ~7MB). Form tunggal di dalam modal menanggung semua beban markup. Keuntungan bonus: satu modal dapat digunakan untuk semua baris (DRY), dan aksi seperti "link ortu" yang membutuhkan UI kaya tidak perlu dikompres ke sel tabel. Pola ini berlaku untuk semua daftar admin: siswa, guru, kelas, absensi, materi, pembayaran.
