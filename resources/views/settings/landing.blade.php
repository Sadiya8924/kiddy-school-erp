@extends('layouts.admin')

@push('styles')
<style>
    .upload-drag-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        background-color: #f8fafc;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    .upload-drag-area:hover {
        border-color: #2563eb;
        background-color: #eff6ff;
    }
    .sticky-save-bar {
        position: sticky;
        bottom: 20px;
        z-index: 99;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        border-radius: 16px;
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.15);
        border: 1px solid #e2e8f0;
    }
    .section-icon-badge {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
</style>
@endpush

@section('konten_halaman')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

    @if(session('success'))
        <div class="alert alert-success border border-success-subtle shadow-none rounded-3 py-2.5 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
            <div><iconify-icon icon="solar:check-circle-bold" class="fs-5 me-2 align-middle text-success"></iconify-icon>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ========================================================== --}}
    {{-- 1. HERO WELCOME CARD --}}
    {{-- ========================================================== --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%); border-radius: 16px !important;">
        <div class="card-body p-4 text-white">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid rgba(255,255,255,0.4);">
                            <iconify-icon icon="solar:monitor-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Edit Landing Page Platform Utama (SaaS Owner)</h5>
                            <span class="badge bg-warning text-dark font-bold rounded-pill px-2.5 py-1" style="font-size:11px;">Owner Only</span>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Kelola teks, branding, statistik, dan cara kerja yang tampil di website utama (http://127.0.0.1:8000/)</span>
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                    <a href="/" target="_blank" class="btn btn-light rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm text-primary" style="font-size: 12.5px;">
                        <iconify-icon icon="solar:plain-bold-duotone" class="fs-5"></iconify-icon> Pratinjau Landing Page ↗
                    </a>
                </div>

            </div>
        </div>
    </div>

    <form action="{{ route('settings.landing.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ========================================================== --}}
        {{-- 2. IDENTITAS & BRANDING UTAMA (NAVBAR & HEADER) --}}
        {{-- ========================================================== --}}
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #2563eb !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <span class="section-icon-badge bg-primary-subtle text-primary">
                        <iconify-icon icon="solar:buildings-2-bold-duotone"></iconify-icon>
                    </span>
                    1. Identitas & Branding Platform (Top Bar)
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Pengaturan nama platform, tagline, logo utama, dan tombol aksi di navbar atas.</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Nama Platform SaaS <span class="text-danger">*</span></label>
                        <input type="text" name="saas_platform_name" class="form-control bg-white border shadow-none fw-medium" value="{{ $settings['saas_platform_name'] ?? 'Kiddy School ERP' }}" required placeholder="Contoh: Kiddy School ERP">
                        <span class="text-muted small" style="font-size: 11px;">Tampil di kiri atas navbar dan tab browser.</span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Tagline Platform <span class="text-danger">*</span></label>
                        <input type="text" name="saas_platform_tagline" class="form-control bg-white border shadow-none fw-medium" value="{{ $settings['saas_platform_tagline'] ?? 'MULTI-TENANT PLATFORM' }}" required placeholder="Contoh: MULTI-TENANT PLATFORM">
                        <span class="text-muted small" style="font-size: 11px;">Teks kecil di bawah nama platform pada navbar.</span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Teks Tombol Aksi Navbar (CTA) <span class="text-danger">*</span></label>
                        <input type="text" name="saas_header_cta_text" class="form-control bg-white border shadow-none fw-medium" value="{{ $settings['saas_header_cta_text'] ?? 'Daftar Sekarang' }}" required placeholder="Contoh: Daftar Sekarang">
                        <span class="text-muted small" style="font-size: 11px;">Tombol biru di kanan atas navbar yang mengarahkan ke form pendaftaran yayasan.</span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Logo Utama Platform</label>
                        <div class="upload-drag-area p-2.5 d-flex align-items-center justify-content-between gap-3" onclick="document.getElementById('site_logo_input').click();">
                            <div class="d-flex align-items-center gap-3">
                                @if(!empty($settings['site_logo']))
                                    <img src="{{ $settings['site_logo'] }}" alt="Logo" class="rounded object-fit-contain bg-white p-1 border shadow-xs" style="width: 44px; height: 44px;">
                                @else
                                    <div class="rounded bg-primary text-white d-flex align-items-center justify-content-center shadow-xs" style="width: 44px; height: 44px; font-size: 20px;">
                                        🏫
                                    </div>
                                @endif
                                <div class="text-start">
                                    <div class="fw-bold text-dark small">Pilih Logo Baru</div>
                                    <div class="text-muted" style="font-size: 11px;">Format PNG / JPG transparan (Maks. 2MB)</div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3">Pilih File</button>
                        </div>
                        <input type="file" name="site_logo" id="site_logo_input" class="d-none" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- 3. HERO SECTION (BAGIAN UTAMA ATAS) --}}
        {{-- ========================================================== --}}
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #f59e0b !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <span class="section-icon-badge bg-warning-subtle text-warning">
                        <iconify-icon icon="solar:star-bold-duotone"></iconify-icon>
                    </span>
                    2. Bagian Hero Promosi (Focal Point Atas)
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Teks judul utama, badge jaminan, deskripsi promosi, dan tombol aksi di banner biru depan.</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Badge Jaminan Kepercayaan (Paling Atas) <span class="text-danger">*</span></label>
                        <input type="text" name="saas_hero_badge" class="form-control bg-white border shadow-none fw-medium" value="{{ $settings['saas_hero_badge'] ?? 'SaaS Multi-Tenant Terpercaya Khusus Yayasan & Sekolah Anak Usia Dini' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Judul Utama Baris 1 (Teks Putih) <span class="text-danger">*</span></label>
                        <input type="text" name="saas_hero_title_1" class="form-control bg-white border shadow-none fw-medium" value="{{ $settings['saas_hero_title_1'] ?? 'Solusi ERP Modern & Terpadu untuk' }}" required placeholder="Solusi ERP Modern & Terpadu untuk">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Judul Highlight Baris 2 (Teks Biru Muda Gradient) <span class="text-danger">*</span></label>
                        <input type="text" name="saas_hero_title_highlight" class="form-control bg-white border shadow-none fw-medium text-primary font-bold" value="{{ $settings['saas_hero_title_highlight'] ?? 'Manajemen Yayasan TK & PAUD' }}" required placeholder="Manajemen Yayasan TK & PAUD">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Teks Deskripsi / Slogan Hero <span class="text-danger">*</span></label>
                        <textarea name="saas_hero_desc" class="form-control bg-white border shadow-none" rows="3" required style="font-size: 13px;">{{ $settings['saas_hero_desc'] ?? 'Kelola seluruh cabang sekolah, ribuan siswa, e-rapor Kurikulum Merdeka, presensi harian, hingga portal mandiri orang tua dalam satu sistem cloud terintegrasi dengan jaminan isolasi data penuh.' }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Teks Tombol Utama (Biru Terang) <span class="text-danger">*</span></label>
                        <input type="text" name="saas_hero_btn_primary" class="form-control bg-white border shadow-none fw-medium" value="{{ $settings['saas_hero_btn_primary'] ?? 'Daftarkan Yayasan Anda' }}" required>
                        <span class="text-muted small" style="font-size: 11px;">Mengarahkan pengunjung ke form pendaftaran yayasan baru.</span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Teks Tombol Kedua (Outline Transparan) <span class="text-danger">*</span></label>
                        <input type="text" name="saas_hero_btn_secondary" class="form-control bg-white border shadow-none fw-medium" value="{{ $settings['saas_hero_btn_secondary'] ?? 'Pelajari Fitur & Modul' }}" required>
                        <span class="text-muted small" style="font-size: 11px;">Scroll halus ke bagian fitur dan modul lengkap.</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- 4. STATISTIK UTAMA (4 ANGKA PENCAPAIAN) --}}
        {{-- ========================================================== --}}
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #10b981 !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <span class="section-icon-badge bg-success-subtle text-success">
                        <iconify-icon icon="solar:chart-square-bold-duotone"></iconify-icon>
                    </span>
                    3. Angka Statistik Pencapaian Platform
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">4 angka metrik kredibilitas yang tampil di bawah tombol hero.</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="p-3 border rounded-3 bg-light">
                            <label class="form-label fw-bold text-dark" style="font-size: 11.5px;">Statistik 1: Angka</label>
                            <input type="text" name="saas_stat_1_num" class="form-control bg-white border shadow-none fw-bold text-primary mb-2" value="{{ $settings['saas_stat_1_num'] ?? '500+' }}" required>
                            <label class="form-label fw-bold text-dark" style="font-size: 11.5px;">Statistik 1: Label</label>
                            <input type="text" name="saas_stat_1_label" class="form-control bg-white border shadow-none" value="{{ $settings['saas_stat_1_label'] ?? 'Cabang Sekolah Terdaftar' }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded-3 bg-light">
                            <label class="form-label fw-bold text-dark" style="font-size: 11.5px;">Statistik 2: Angka</label>
                            <input type="text" name="saas_stat_2_num" class="form-control bg-white border shadow-none fw-bold text-primary mb-2" value="{{ $settings['saas_stat_2_num'] ?? '15.000+' }}" required>
                            <label class="form-label fw-bold text-dark" style="font-size: 11.5px;">Statistik 2: Label</label>
                            <input type="text" name="saas_stat_2_label" class="form-control bg-white border shadow-none" value="{{ $settings['saas_stat_2_label'] ?? 'Siswa Aktif & Alumni' }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded-3 bg-light">
                            <label class="form-label fw-bold text-dark" style="font-size: 11.5px;">Statistik 3: Angka</label>
                            <input type="text" name="saas_stat_3_num" class="form-control bg-white border shadow-none fw-bold text-primary mb-2" value="{{ $settings['saas_stat_3_num'] ?? '100%' }}" required>
                            <label class="form-label fw-bold text-dark" style="font-size: 11.5px;">Statistik 3: Label</label>
                            <input type="text" name="saas_stat_3_label" class="form-control bg-white border shadow-none" value="{{ $settings['saas_stat_3_label'] ?? 'Isolasi Data Antar-Tenant' }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded-3 bg-light">
                            <label class="form-label fw-bold text-dark" style="font-size: 11.5px;">Statistik 4: Angka</label>
                            <input type="text" name="saas_stat_4_num" class="form-control bg-white border shadow-none fw-bold text-primary mb-2" value="{{ $settings['saas_stat_4_num'] ?? '99.9%' }}" required>
                            <label class="form-label fw-bold text-dark" style="font-size: 11.5px;">Statistik 4: Label</label>
                            <input type="text" name="saas_stat_4_label" class="form-control bg-white border shadow-none" value="{{ $settings['saas_stat_4_label'] ?? 'Cloud Server Uptime' }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- 5. CARA KERJA (4 LANGKAH PROSES) --}}
        {{-- ========================================================== --}}
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #8b5cf6 !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <span class="section-icon-badge bg-primary-subtle text-primary" style="background: rgba(139,92,246,0.1) !important; color: #8b5cf6 !important;">
                        <iconify-icon icon="solar:route-bold-duotone"></iconify-icon>
                    </span>
                    4. Alur "Cara Kerja" (4 Langkah Menuju Go-Live)
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Penjelasan alur 4 langkah dari pendaftaran hingga sistem siap dipakai yayasan.</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <label class="form-label fw-bold text-dark" style="font-size: 12px;">Langkah 1: Judul</label>
                            <input type="text" name="saas_step_1_title" class="form-control bg-white border shadow-none fw-bold mb-2" value="{{ $settings['saas_step_1_title'] ?? '1. Daftarkan Yayasan' }}" required>
                            <label class="form-label fw-bold text-dark" style="font-size: 12px;">Langkah 1: Deskripsi</label>
                            <textarea name="saas_step_1_desc" class="form-control bg-white border shadow-none" rows="2" style="font-size: 12px;">{{ $settings['saas_step_1_desc'] ?? 'Isi formulir identitas yayasan dan lokasi GPS melalui portal pendaftaran.' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <label class="form-label fw-bold text-dark" style="font-size: 12px;">Langkah 2: Judul</label>
                            <input type="text" name="saas_step_2_title" class="form-control bg-white border shadow-none fw-bold mb-2" value="{{ $settings['saas_step_2_title'] ?? '2. Verifikasi & Aktivasi' }}" required>
                            <label class="form-label fw-bold text-dark" style="font-size: 12px;">Langkah 2: Deskripsi</label>
                            <textarea name="saas_step_2_desc" class="form-control bg-white border shadow-none" rows="2" style="font-size: 12px;">{{ $settings['saas_step_2_desc'] ?? 'Admin platform memverifikasi data dan akun Super Admin otomatis dibuat.' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <label class="form-label fw-bold text-dark" style="font-size: 12px;">Langkah 3: Judul</label>
                            <input type="text" name="saas_step_3_title" class="form-control bg-white border shadow-none fw-bold mb-2" value="{{ $settings['saas_step_3_title'] ?? '3. Atur Cabang & Guru' }}" required>
                            <label class="form-label fw-bold text-dark" style="font-size: 12px;">Langkah 3: Deskripsi</label>
                            <textarea name="saas_step_3_desc" class="form-control bg-white border shadow-none" rows="2" style="font-size: 12px;">{{ $settings['saas_step_3_desc'] ?? 'Super Admin menambahkan cabang TK/PAUD, akun guru, dan kelas.' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <label class="form-label fw-bold text-dark" style="font-size: 12px;">Langkah 4: Judul</label>
                            <input type="text" name="saas_step_4_title" class="form-control bg-white border shadow-none fw-bold mb-2" value="{{ $settings['saas_step_4_title'] ?? '4. Siap Digunakan' }}" required>
                            <label class="form-label fw-bold text-dark" style="font-size: 12px;">Langkah 4: Deskripsi</label>
                            <textarea name="saas_step_4_desc" class="form-control bg-white border shadow-none" rows="2" style="font-size: 12px;">{{ $settings['saas_step_4_desc'] ?? 'Sistem siap melayani PPDB, absensi, e-rapor, dan portal wali murid.' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- 6. BANNER AJAKAN BAWAH (BOTTOM CTA) --}}
        {{-- ========================================================== --}}
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #0284c7 !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <span class="section-icon-badge bg-info-subtle text-info">
                        <iconify-icon icon="solar:rocket-2-bold-duotone"></iconify-icon>
                    </span>
                    5. Banner Ajakan Bawah (Bottom Call-to-Action)
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Banner biru besar sebelum footer untuk mendorong yayasan segera mendaftar.</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Judul Banner Ajakan <span class="text-danger">*</span></label>
                        <input type="text" name="saas_cta_title" class="form-control bg-white border shadow-none fw-bold" value="{{ $settings['saas_cta_title'] ?? 'Siap Mentransformasi Digital Yayasan & Sekolah Anda?' }}" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Teks Deskripsi Banner <span class="text-danger">*</span></label>
                        <input type="text" name="saas_cta_desc" class="form-control bg-white border shadow-none" value="{{ $settings['saas_cta_desc'] ?? 'Bergabunglah bersama puluhan yayasan pendidikan lainnya yang telah menggunakan platform Kiddy School ERP.' }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Teks Tombol Aksi <span class="text-danger">*</span></label>
                        <input type="text" name="saas_cta_btn_text" class="form-control bg-white border shadow-none fw-bold text-primary" value="{{ $settings['saas_cta_btn_text'] ?? 'Daftarkan Yayasan Anda Sekarang' }}" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- 7. INFORMASI KONTAK & FOOTER --}}
        {{-- ========================================================== --}}
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #475569 !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <span class="section-icon-badge bg-secondary-subtle text-secondary">
                        <iconify-icon icon="solar:mailbox-bold-duotone"></iconify-icon>
                    </span>
                    6. Informasi Kontak Resmi & Footer
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Kontak bantuan, email support, dan ringkasan platform pada footer bawah.</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Ringkasan Tentang Platform (Footer About) <span class="text-danger">*</span></label>
                        <textarea name="saas_footer_about" class="form-control bg-white border shadow-none" rows="2" style="font-size: 12.5px;" required>{{ $settings['saas_footer_about'] ?? 'Platform SaaS ERP Manajemen Yayasan TK & PAUD terintegrasi dengan arsitektur multi-tenant modern.' }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Email Support / Kontak <span class="text-danger">*</span></label>
                        <input type="email" name="saas_contact_email" class="form-control bg-white border shadow-none fw-medium" value="{{ $settings['saas_contact_email'] ?? 'support@kiddyschool.id' }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="saas_contact_phone" class="form-control bg-white border shadow-none fw-medium" value="{{ $settings['saas_contact_phone'] ?? '+62 812-3456-7890' }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Alamat Kantor / Operasional <span class="text-danger">*</span></label>
                        <input type="text" name="saas_contact_address" class="form-control bg-white border shadow-none fw-medium" value="{{ $settings['saas_contact_address'] ?? 'Gedung Cyber Tech Lt. 5, Jakarta, Indonesia' }}" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- STICKY SAVE BAR --}}
        <div class="sticky-save-bar p-3 d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <span class="p-2 rounded-circle bg-success-subtle text-success d-inline-flex">
                    <iconify-icon icon="solar:check-read-bold" class="fs-5"></iconify-icon>
                </span>
                <span class="text-dark small fw-semibold">Pastikan seluruh data platform sudah sesuai sebelum menyimpan.</span>
            </div>
            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm">
                <iconify-icon icon="solar:diskette-bold" class="fs-5"></iconify-icon> Simpan Perubahan Landing Page
            </button>
        </div>

    </form>

</div>
@endsection
