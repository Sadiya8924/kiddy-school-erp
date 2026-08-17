@extends('layouts.admin')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor .note-editing-area { font-family: 'Inter', sans-serif; font-size: 13px; }
    .note-editor.note-frame { border-radius: 12px !important; overflow: hidden; border-color: #e2e8f0 !important; box-shadow: none !important; }
    .note-toolbar { background-color: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; padding: 6px 10px !important; }
    .note-btn { border-radius: 6px !important; background: white !important; border: 1px solid #e2e8f0 !important; font-size: 12px !important; }

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

    .template-card-option {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.25s ease;
        background: #fff;
        position: relative;
        overflow: hidden;
    }
    .template-card-option:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        border-color: #93c5fd;
    }
    .template-card-option.active {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
    }
    .template-card-option input[type="radio"] {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 20px;
        height: 20px;
        accent-color: #2563eb;
    }
    .color-swatch {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: inline-block;
        border: 1px solid rgba(0,0,0,0.1);
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
    {{-- 1. HERO WELCOME CARD WITH SOFT BLUE GRADIENT --}}
    {{-- ========================================================== --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%); border-radius: 16px !important;">
        <div class="card-body p-4 text-white">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                
                {{-- Title & Subtitle Context --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid rgba(255,255,255,0.4);">
                            <iconify-icon icon="solar:monitor-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Pengaturan Konten Landing Page</h5>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Kelola informasi publik, profil sekolah &amp; teks promosi tanpa perlu pengodean HTML</span>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <form action="{{ route('settings.landing.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @php
            $currentTemplate = $settings['landing_template'] ?? 'ocean';
        @endphp

        {{-- SECTION TEMPLATE CHOOSER --}}
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:palette-bold-duotone" class="text-primary fs-5"></iconify-icon> Pilih Template Desain Landing Page Utama
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Pilih tema visual yang akan digunakan pada landing page utama portal web SaaS.</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    
                    {{-- 1. OCEAN --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'ocean' ? 'active' : '' }}">
                            <input type="radio" name="landing_template" value="ocean" {{ $currentTemplate === 'ocean' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 text-white mb-2" style="background: linear-gradient(135deg, #0f172a, #2563eb); height: 90px;">
                                <span class="badge bg-white text-dark font-semibold rounded-pill px-2 py-0.5" style="font-size: 9.5px;">Ocean Gradient</span>
                                <h6 class="fw-bold text-white mb-0 mt-2" style="font-size: 13px;">Modern Ocean</h6>
                                <span class="small text-white-50" style="font-size: 10.5px;">Sleek, elegan &amp; modern</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Modern Ocean</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #0f172a;"></span>
                                    <span class="color-swatch" style="background: #2563eb;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- 2. CERIA --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'ceria' ? 'active' : '' }}">
                            <input type="radio" name="landing_template" value="ceria" {{ $currentTemplate === 'ceria' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 mb-2" style="background: #FDFBF7; border: 1px solid #F0EAE1; height: 90px;">
                                <span class="badge rounded-pill px-2 py-0.5" style="font-size: 9.5px; background: #F0997B; color: #fff;">Playful &amp; Ceria</span>
                                <h6 class="fw-bold mb-0 mt-2" style="font-size: 13px; color: #7A5310;">Ceria Playful</h6>
                                <span class="small" style="font-size: 10.5px; color: #9A7B45;">Warm, energik &amp; ramah anak</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Ceria Playful</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #EF9F27;"></span>
                                    <span class="color-swatch" style="background: #F0997B;"></span>
                                    <span class="color-swatch" style="background: #378ADD;"></span>
                                    <span class="color-swatch" style="background: #D4537E;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- 3. TRUSTWORTHY --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'trustworthy' ? 'active' : '' }}">
                            <input type="radio" name="landing_template" value="trustworthy" {{ $currentTemplate === 'trustworthy' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 mb-2" style="background: #ffffff; border: 1px solid #e2e8f0; height: 90px;">
                                <span class="badge rounded-pill px-2 py-0.5" style="font-size: 9.5px; background: #185FA5; color: #fff;">Corporate Navy</span>
                                <h6 class="fw-bold mb-0 mt-2" style="font-size: 13px; color: #185FA5;">Trustworthy Navy</h6>
                                <span class="small text-muted" style="font-size: 10.5px;">Bersih, kredibel &amp; terpercaya</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Trustworthy</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #185FA5;"></span>
                                    <span class="color-swatch" style="background: #f8fafc;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- 4. NATURAL --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'natural' ? 'active' : '' }}">
                            <input type="radio" name="landing_template" value="natural" {{ $currentTemplate === 'natural' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 mb-2" style="background: #F6F3EC; border: 1px solid #E6DFC5; height: 90px;">
                                <span class="badge rounded-pill px-2 py-0.5" style="font-size: 9.5px; background: #639922; color: #fff;">Sage Montessori</span>
                                <h6 class="fw-bold mb-0 mt-2" style="font-size: 13px; color: #355312;">Natural Sage</h6>
                                <span class="small" style="font-size: 10.5px; color: #566D38;">Organik, Montessori &amp; alam</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Natural Sage</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #639922;"></span>
                                    <span class="color-swatch" style="background: #F6F3EC;"></span>
                                    <span class="color-swatch" style="background: #FAC775;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                </div>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- SECTION 0: IDENTITAS & LOGO WEB --}}
        {{-- ========================================================== --}}
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:globus-bold-duotone" class="text-primary fs-5"></iconify-icon> Identitas &amp; Branding Utama Web
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Pengaturan nama institusi dan logo utama yang muncul pada header portal web</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Nama Situs / Institusi</label>
                        <input type="text" class="form-control rounded-3" name="site_title" value="{{ $settings['site_title'] ?? 'Yayasan Al-Hikmah' }}" placeholder="Contoh: Yayasan Al-Hikmah">
                        <span class="text-muted d-block mt-1" style="font-size: 11px;">Nama ini akan tampil di bagian header, footer, dan tab browser portal.</span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Logo Utama Website</label>
                        <div class="upload-drag-area position-relative">
                            <input type="file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" name="site_logo" accept="image/*">
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                @if(isset($settings['site_logo']))
                                    <img src="{{ $settings['site_logo'] }}" width="44" height="44" class="rounded object-fit-contain border p-1 bg-white shadow-xs">
                                @else
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center">
                                        <iconify-icon icon="solar:upload-bold-duotone" class="fs-4"></iconify-icon>
                                    </div>
                                @endif
                                <div class="text-start">
                                    <span class="fw-bold text-dark d-block" style="font-size: 12.5px;">Klik atau seret file gambar logo di sini</span>
                                    <span class="text-muted" style="font-size: 11px;">Format PNG/JPG transparan (Maks. 2MB)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- SECTION 1: HERO ATAS --}}
        {{-- ========================================================== --}}
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #f59e0b !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:star-bold-duotone" class="text-warning fs-5"></iconify-icon> Bagian Hero Promosi (Atas)
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Teks judul utama, slogan promosi, dan gambar banner halaman depan</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Judul Hero Utama (Teks Hitam)</label>
                        <input type="text" class="form-control rounded-3" name="hero_title_1" value="{{ $settings['hero_title_1'] ?? 'Ciptakan Generasi' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Judul Tambahan (Teks Biru Bawah)</label>
                        <input type="text" class="form-control rounded-3" name="hero_title_2" value="{{ $settings['hero_title_2'] ?? 'Cerdas & Ceria' }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label font-semibold text-dark small">Subjudul Teks Deskripsi Hero</label>
                    <textarea class="form-control summernote" name="hero_subtitle" rows="3">{{ $settings['hero_subtitle'] ?? 'Memberikan pendidikan terbaik untuk generasi masa depan dengan fasilitas lengkap dan tenaga pengajar profesional. Amankan kuota putra-putri Anda sekarang.' }}</textarea>
                </div>
                <div>
                    <label class="form-label font-semibold text-dark small">Gambar Hero Utama / Kanan (Menggantikan Ilustrasi GSAP Bawaan)</label>

                    {{-- Upload area --}}
                    <div class="upload-drag-area position-relative mb-2" style="cursor:pointer;" onclick="document.getElementById('hero_image_input').click()">
                        <input type="file" id="hero_image_input" name="hero_image" accept="image/*"
                               class="d-none"
                               onchange="previewHeroImage(this)">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-2 d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:gallery-add-bold-duotone" class="fs-4"></iconify-icon>
                            </div>
                            <div class="text-start">
                                <span class="fw-bold text-dark d-block" style="font-size: 12.5px;">Klik untuk pilih gambar hero baru</span>
                                <span class="text-muted" style="font-size: 11px;">Format PNG/JPG/WebP. Upload untuk mengganti ilustrasi animasi GSAP bawaan.</span>
                            </div>
                        </div>
                    </div>

                    {{-- Preview gambar baru yang dipilih (belum disimpan) --}}
                    <div id="hero-new-preview" class="d-none mt-2 p-2 border rounded-3 bg-light">
                        <div class="d-flex align-items-center gap-3">
                            <img id="hero-preview-img" src="" style="max-height: 90px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; padding: 4px;">
                            <div>
                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1 d-block mb-1" style="font-size: 11px;">
                                    Preview — Belum Disimpan
                                </span>
                                <span class="text-muted" style="font-size: 10.5px;">Gambar ini akan menggantikan ilustrasi GSAP setelah klik Simpan</span>
                            </div>
                        </div>
                    </div>

                    {{-- Gambar hero aktif saat ini --}}
                    @php $activeHero = $settings['hero_image'] ?? null; @endphp
                    <input type="hidden" name="delete_hero_image" id="delete_hero_image_flag" value="0">

                    @if(!empty($activeHero) && $activeHero !== 'null')
                        <div id="hero-current-preview" class="mt-2 p-3 border border-success-subtle rounded-3" style="background: #f0fdf4;">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $activeHero }}" style="max-height: 90px; border-radius: 10px; border: 1px solid #d1fae5; background: #fff; padding: 4px;">
                                <div class="flex-grow-1">
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 d-block mb-1" style="font-size: 11px;">
                                        <iconify-icon icon="solar:check-circle-bold" class="align-middle me-1"></iconify-icon>Gambar Hero Aktif Saat Ini
                                    </span>
                                    <span class="text-muted d-block" style="font-size: 10.5px;">Upload baru di atas untuk mengganti, atau hapus untuk kembali ke ilustrasi animasi</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-3" style="font-size: 11px; white-space: nowrap; flex-shrink: 0;"
                                        onclick="deleteHeroImage()">
                                    <iconify-icon icon="solar:trash-bin-2-bold-duotone" class="me-1"></iconify-icon>Hapus &amp; Kembali ke Ilustrasi GSAP
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="mt-1.5 d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:info-circle-bold-duotone" class="text-info fs-6"></iconify-icon>
                            <span class="text-muted" style="font-size: 11px;">Saat ini menggunakan <strong>ilustrasi animasi GSAP bawaan</strong>. Upload gambar di atas untuk menggantinya.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- SECTION 2: PROFIL & PROGRAM (3 COLUMN GRID) --}}
        {{-- ========================================================== --}}
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #8b5cf6 !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="text-primary fs-5"></iconify-icon> Bagian Profil &amp; Program Unggulan
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Profil institusi, program TK, PAUD, serta pimpinan unit</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Judul Seksi Profil</label>
                        <input type="text" class="form-control rounded-3" name="profil_title" value="{{ $settings['profil_title'] ?? 'Kenali Lebih Dekat' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Subjudul Seksi Profil</label>
                        <textarea class="form-control summernote" name="profil_subtitle" rows="2">{{ $settings['profil_subtitle'] ?? 'Pelajari lebih lanjut tentang institusi dan program unggulan kami untuk putra-putri Anda.' }}</textarea>
                    </div>
                </div>

                {{-- 3 Column Tabs Grid --}}
                <div class="row g-4 border-top pt-4">
                    {{-- Tab Yayasan --}}
                    <div class="col-lg-4">
                        <div class="p-3.5 rounded-3 border bg-slate-50 h-100" style="border-radius: 14px !important; border-top: 3px solid #2563eb !important;">
                            <h6 class="fw-bold mb-3 text-primary d-flex align-items-center gap-2" style="font-size: 14px;">
                                <iconify-icon icon="solar:buildings-bold-duotone" class="fs-5"></iconify-icon> Profil Tab Yayasan
                            </h6>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Nama Yayasan</label>
                                <input type="text" class="form-control rounded-3" name="yayasan_title" value="{{ $settings['yayasan_title'] ?? 'Yayasan Pendidikan Al-Hikmah' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Deskripsi Singkat</label>
                                <textarea class="form-control summernote" name="yayasan_desc" rows="4">{{ $settings['yayasan_desc'] ?? 'Yayasan kami berdiri sejak tahun 2010 dengan komitmen penuh untuk mencetak generasi Islami yang unggul.' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Visi</label>
                                <textarea class="form-control summernote" name="yayasan_visi" rows="2">{{ $settings['yayasan_visi'] ?? 'Mewujudkan lembaga pendidikan yang berpijak pada penanaman aqidah Islam.' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Misi</label>
                                <textarea class="form-control summernote" name="yayasan_misi" rows="2">{{ $settings['yayasan_misi'] ?? 'Menyelenggarakan proses pembelajaran yang berkualitas.' }}</textarea>
                            </div>
                            <hr class="my-3 text-muted">
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Nama Profil Pimpinan</label>
                                <input type="text" class="form-control rounded-3" name="yayasan_profile_name" value="{{ $settings['yayasan_profile_name'] ?? 'Dr. H. Fulan, M.Pd' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Jabatan</label>
                                <input type="text" class="form-control rounded-3" name="yayasan_profile_title" value="{{ $settings['yayasan_profile_title'] ?? 'Ketua Yayasan' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Foto Pimpinan</label>
                                <input type="file" class="form-control rounded-3" name="yayasan_image" accept="image/*">
                            </div>
                        </div>
                    </div>

                    {{-- Tab Program TK --}}
                    <div class="col-lg-4">
                        <div class="p-3.5 rounded-3 border bg-slate-50 h-100" style="border-radius: 14px !important; border-top: 3px solid #10b981 !important;">
                            <h6 class="fw-bold mb-3 text-success d-flex align-items-center gap-2" style="font-size: 14px;">
                                <iconify-icon icon="solar:black-hole-bold-duotone" class="fs-5"></iconify-icon> Profil Tab Program TK
                            </h6>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Judul Program TK</label>
                                <input type="text" class="form-control rounded-3" name="tk_title" value="{{ $settings['tk_title'] ?? 'Taman Kanak-Kanak (TK)' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Deskripsi Program TK</label>
                                <textarea class="form-control summernote" name="tk_desc" rows="6">{{ $settings['tk_desc'] ?? 'Program Taman Kanak-Kanak dirancang untuk mengasah kognitif, motorik, dan sosio-emosional anak melalui pendekatan learning by playing.' }}</textarea>
                            </div>
                            <hr class="my-3 text-muted">
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Nama Kepala Sekolah</label>
                                <input type="text" class="form-control rounded-3" name="tk_profile_name" value="{{ $settings['tk_profile_name'] ?? 'Bunda Fatimah, S.Pd' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Jabatan</label>
                                <input type="text" class="form-control rounded-3" name="tk_profile_title" value="{{ $settings['tk_profile_title'] ?? 'Kepala Sekolah TK' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Foto Kepala Sekolah</label>
                                <input type="file" class="form-control rounded-3" name="tk_image" accept="image/*">
                            </div>
                        </div>
                    </div>

                    {{-- Tab Program PAUD --}}
                    <div class="col-lg-4">
                        <div class="p-3.5 rounded-3 border bg-slate-50 h-100" style="border-radius: 14px !important; border-top: 3px solid #f59e0b !important;">
                            <h6 class="fw-bold mb-3 text-warning d-flex align-items-center gap-2" style="font-size: 14px;">
                                <iconify-icon icon="solar:heart-bold-duotone" class="fs-5"></iconify-icon> Profil Tab Program PAUD
                            </h6>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Judul Program PAUD</label>
                                <input type="text" class="form-control rounded-3" name="paud_title" value="{{ $settings['paud_title'] ?? 'Pendidikan Anak Usia Dini (PAUD)' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Deskripsi Program PAUD</label>
                                <textarea class="form-control summernote" name="paud_desc" rows="6">{{ $settings['paud_desc'] ?? 'Program PAUD memfasilitasi masa emas golden age anak dalam lingkungan aman dan nyaman.' }}</textarea>
                            </div>
                            <hr class="my-3 text-muted">
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Nama Koordinator</label>
                                <input type="text" class="form-control rounded-3" name="paud_profile_name" value="{{ $settings['paud_profile_name'] ?? 'Bunda Aisyah, S.Psi' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Jabatan</label>
                                <input type="text" class="form-control rounded-3" name="paud_profile_title" value="{{ $settings['paud_profile_title'] ?? 'Koordinator PAUD' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Foto Koordinator</label>
                                <input type="file" class="form-control rounded-3" name="paud_image" accept="image/*">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- SECTION 3: FOOTER & KONTAK --}}
        {{-- ========================================================== --}}
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #10b981 !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:phone-bold-duotone" class="text-primary fs-5"></iconify-icon> Kontak &amp; Footer Website
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Alamat sekolah, nomor kontak pusat, dan tautan WhatsApp publik</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Alamat Lengkap Sekolah</label>
                        <textarea class="form-control rounded-3" name="footer_address" rows="3">{{ $settings['footer_address'] ?? 'Jl. KH. Wahid Hasyim No. 45, Malang, Jawa Timur' }}</textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-semibold text-dark small">Nomor Telepon Kantor</label>
                        <input type="text" class="form-control rounded-3" name="footer_phone" value="{{ $settings['footer_phone'] ?? '(0341) 567-890' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-semibold text-dark small">Nomor WhatsApp Resmi</label>
                        <input type="text" class="form-control rounded-3" name="footer_whatsapp" value="{{ $settings['footer_whatsapp'] ?? '0812-3456-7890' }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- STICKY SAVE BUTTON BAR --}}
        <div class="sticky-save-bar p-3 border d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-2">
                <iconify-icon icon="solar:info-circle-bold-duotone" class="text-primary fs-4"></iconify-icon>
                <span class="fw-medium text-dark small">Pastikan seluruh informasi publik sudah terverifikasi sebelum menyimpan.</span>
            </div>
            <button type="submit" class="btn btn-primary px-4 py-2 font-semibold rounded-3 shadow-sm d-flex align-items-center gap-2 text-nowrap">
                <iconify-icon icon="solar:diskette-bold-duotone" class="fs-5"></iconify-icon> Simpan Perubahan Landing Page
            </button>
        </div>

    </form>

    {{-- ========================================================== --}}
    {{-- 4. SUBTLE LOW-KEY FOOTER --}}
    {{-- ========================================================== --}}
    <footer class="text-center py-2 mt-2">
        <span class="text-muted" style="font-size: 11px;">© 2026 TK Ceria Educational Management Platform • Internal Web System v1.0</span>
    </footer>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 140,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']]
            ]
        });
    });

    function previewHeroImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('hero-preview-img').src = e.target.result;
                document.getElementById('hero-new-preview').classList.remove('d-none');
                document.getElementById('hero-new-preview').classList.add('d-flex');
                // Reset delete flag if uploading new image
                document.getElementById('delete_hero_image_flag').value = '0';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function deleteHeroImage() {
        if (!confirm('Hapus gambar hero ini? Landing page akan kembali menggunakan ilustrasi animasi GSAP bawaan.')) return;
        document.getElementById('delete_hero_image_flag').value = '1';
        // Clear the file input
        document.getElementById('hero_image_input').value = '';
        document.getElementById('hero-new-preview').classList.add('d-none');
        // Hide current preview
        var currentPreview = document.getElementById('hero-current-preview');
        if (currentPreview) {
            currentPreview.innerHTML = '<div class="d-flex align-items-center gap-2"><iconify-icon icon="solar:trash-bin-2-bold-duotone" class="text-danger fs-6"></iconify-icon><span class="text-danger fw-semibold" style="font-size: 12px;">Gambar akan dihapus setelah klik Simpan — Landing page akan pakai ilustrasi GSAP bawaan.</span></div>';
            currentPreview.style.background = '#fff5f5';
            currentPreview.classList.remove('border-success-subtle');
            currentPreview.classList.add('border-danger-subtle');
        }
    }
</script>
@endpush

