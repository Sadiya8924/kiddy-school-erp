@extends('layouts.admin')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style id="theme-root-styles">
    :root {
        --theme-primary: #2563eb;
        --theme-primary-dark: #0f172a;
        --theme-accent: #f59e0b;
        --theme-surface: #f8fafc;
        --theme-pill-bg: #eff6ff;
        --theme-pill-border: #dbeafe;
        --theme-hero-bg: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);
    }

    .note-editor .note-editing-area { font-family: 'Inter', sans-serif; font-size: 13px; }
    .note-editor.note-frame { border-radius: 12px !important; overflow: hidden; border-color: #e2e8f0 !important; box-shadow: none !important; }
    .note-toolbar { background-color: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; padding: 6px 10px !important; }
    .note-btn { border-radius: 6px !important; background: white !important; border: 1px solid #e2e8f0 !important; font-size: 12px !important; }

    .upload-drag-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        background-color: var(--theme-surface);
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    .upload-drag-area:hover {
        border-color: var(--theme-primary);
        background-color: var(--theme-pill-bg);
    }
    .static-save-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
        border: 1px solid #e2e8f0;
        border-top: 4px solid var(--theme-primary) !important;
        transition: border-color 0.4s ease;
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
        border-color: var(--theme-primary);
    }
    .template-card-option.active {
        border-color: var(--theme-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
    }
    .template-card-option input[type="radio"] {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 20px;
        height: 20px;
        accent-color: var(--theme-primary);
    }
    .color-swatch {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: inline-block;
        border: 1px solid rgba(0,0,0,0.1);
    }

    /* Dynamic Theme Helper Classes */
    .theme-card-container {
        border-top: 4px solid var(--theme-primary) !important;
        border-radius: 16px !important;
        transition: border-color 0.4s ease, box-shadow 0.4s ease;
    }
    .theme-card-header {
        background-color: var(--theme-surface) !important;
        border-bottom: 1px solid var(--theme-pill-border) !important;
        transition: background-color 0.4s ease, border-color 0.4s ease;
    }
    .theme-card-header h6 {
        color: var(--theme-primary-dark) !important;
        transition: color 0.4s ease;
    }
    .theme-text-primary {
        color: var(--theme-primary) !important;
        transition: color 0.4s ease;
    }
    .theme-btn-primary {
        background: var(--theme-primary) !important;
        border-color: var(--theme-primary) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        transition: all 0.3s ease;
    }
    .theme-btn-primary:hover {
        opacity: 0.92;
        transform: translateY(-1px);
    }
    .theme-btn-light {
        color: var(--theme-primary) !important;
    }
    .theme-subcard {
        background-color: var(--theme-surface) !important;
        border-top: 3.5px solid var(--theme-primary) !important;
        border-radius: 14px !important;
        transition: all 0.4s ease;
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

    @php
        $currentTemplate = $content['template'] ?? 'ocean';
    @endphp

    {{-- HERO CARD (Dinamis Mengikuti Template Aktif) --}}
    <div class="card border-0 shadow-sm overflow-hidden mb-4" id="hero-banner-card" style="background: var(--theme-hero-bg); border-radius: 16px !important; transition: background 0.4s ease, transform 0.2s ease;">
        <div class="card-body p-4 text-white">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                        <div class="rounded-circle bg-white theme-text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid rgba(255,255,255,0.4);">
                            <iconify-icon icon="solar:monitor-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Edit Landing Page {{ $yayasan->nama_yayasan }}</h5>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Kelola informasi publik, profil sekolah, program &amp; teks promosi khusus yayasan Anda</span>
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('yayasan.landing', $yayasan->kode_yayasan) }}" target="_blank" class="btn btn-light theme-btn-light font-semibold rounded-3 btn-sm px-3 py-2 d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12.5px;">
                        <iconify-icon icon="solar:eye-bold-duotone" class="fs-5"></iconify-icon> Lihat Halaman Publik
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('superadmin.landing.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- SECTION TEMPLATE CHOOSER --}}
        <div class="card border shadow-sm mb-4 theme-card-container">
            <div class="card-header theme-card-header p-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:palette-bold-duotone" class="theme-text-primary fs-5"></iconify-icon> Pilih Template Desain Landing Page
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Pilih dari 12 tema visual profesional yang akan digunakan pada landing page yayasan Anda.</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    
                    {{-- 1. OCEAN --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'ocean' ? 'active' : '' }}" onclick="selectTemplate('ocean')">
                            <input type="radio" name="template" value="ocean" {{ $currentTemplate === 'ocean' ? 'checked' : '' }}>
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
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'ceria' ? 'active' : '' }}" onclick="selectTemplate('ceria')">
                            <input type="radio" name="template" value="ceria" {{ $currentTemplate === 'ceria' ? 'checked' : '' }}>
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
                                    <span class="color-swatch" style="background: #D4537E;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- 3. TRUSTWORTHY --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'trustworthy' ? 'active' : '' }}" onclick="selectTemplate('trustworthy')">
                            <input type="radio" name="template" value="trustworthy" {{ $currentTemplate === 'trustworthy' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 mb-2" style="background: #ffffff; border: 1px solid #e2e8f0; height: 90px;">
                                <span class="badge rounded-pill px-2 py-0.5" style="font-size: 9.5px; background: #185FA5; color: #fff;">Corporate Navy</span>
                                <h6 class="fw-bold mb-0 mt-2" style="font-size: 13px; color: #185FA5;">Trustworthy Navy</h6>
                                <span class="small text-muted" style="font-size: 10.5px;">Bersih, kredibel &amp; terpercaya</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Trustworthy</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #0B2545;"></span>
                                    <span class="color-swatch" style="background: #185FA5;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- 4. NATURAL --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'natural' ? 'active' : '' }}" onclick="selectTemplate('natural')">
                            <input type="radio" name="template" value="natural" {{ $currentTemplate === 'natural' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 mb-2" style="background: #F6F3EC; border: 1px solid #E6DFC5; height: 90px;">
                                <span class="badge rounded-pill px-2 py-0.5" style="font-size: 9.5px; background: #639922; color: #fff;">Sage Montessori</span>
                                <h6 class="fw-bold mb-0 mt-2" style="font-size: 13px; color: #355312;">Natural Sage</h6>
                                <span class="small" style="font-size: 10.5px; color: #566D38;">Organik, Montessori &amp; alam</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Natural Sage</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #1C3309;"></span>
                                    <span class="color-swatch" style="background: #639922;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- 5. SUNSET --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'sunset' ? 'active' : '' }}" onclick="selectTemplate('sunset')">
                            <input type="radio" name="template" value="sunset" {{ $currentTemplate === 'sunset' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 text-white mb-2" style="background: linear-gradient(135deg, #4C0519, #E11D48); height: 90px;">
                                <span class="badge bg-white text-dark font-semibold rounded-pill px-2 py-0.5" style="font-size: 9.5px;">Luxury Sunset</span>
                                <h6 class="fw-bold text-white mb-0 mt-2" style="font-size: 13px;">Royal Sunset</h6>
                                <span class="small text-white-50" style="font-size: 10.5px;">Hangat, premium &amp; elegan</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Royal Sunset</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #4C0519;"></span>
                                    <span class="color-swatch" style="background: #E11D48;"></span>
                                    <span class="color-swatch" style="background: #F59E0B;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- 6. EMERALD --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'emerald' ? 'active' : '' }}" onclick="selectTemplate('emerald')">
                            <input type="radio" name="template" value="emerald" {{ $currentTemplate === 'emerald' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 text-white mb-2" style="background: linear-gradient(135deg, #064E3B, #059669); height: 90px;">
                                <span class="badge bg-white text-dark font-semibold rounded-pill px-2 py-0.5" style="font-size: 9.5px;">Royal Emerald</span>
                                <h6 class="fw-bold text-white mb-0 mt-2" style="font-size: 13px;">Emerald Luxury</h6>
                                <span class="small text-white-50" style="font-size: 10.5px;">Prestisius, Islami &amp; mewah</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Emerald Luxury</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #064E3B;"></span>
                                    <span class="color-swatch" style="background: #059669;"></span>
                                    <span class="color-swatch" style="background: #FBBF24;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- 7. VIOLET --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'violet' ? 'active' : '' }}" onclick="selectTemplate('violet')">
                            <input type="radio" name="template" value="violet" {{ $currentTemplate === 'violet' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 text-white mb-2" style="background: linear-gradient(135deg, #3B0764, #7C3AED); height: 90px;">
                                <span class="badge bg-white text-dark font-semibold rounded-pill px-2 py-0.5" style="font-size: 9.5px;">Futuristic Violet</span>
                                <h6 class="fw-bold text-white mb-0 mt-2" style="font-size: 13px;">Creative Violet</h6>
                                <span class="small text-white-50" style="font-size: 10.5px;">Kreatif, inovatif &amp; modern</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Creative Violet</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #3B0764;"></span>
                                    <span class="color-swatch" style="background: #7C3AED;"></span>
                                    <span class="color-swatch" style="background: #EC4899;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- 8. MINIMAL --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'minimal' ? 'active' : '' }}" onclick="selectTemplate('minimal')">
                            <input type="radio" name="template" value="minimal" {{ $currentTemplate === 'minimal' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 mb-2" style="background: #1E293B; border: 1px solid #334155; height: 90px;">
                                <span class="badge rounded-pill px-2 py-0.5" style="font-size: 9.5px; background: #0EA5E9; color: #fff;">Scandinavian Clean</span>
                                <h6 class="fw-bold text-white mb-0 mt-2" style="font-size: 13px;">Nordic Minimalist</h6>
                                <span class="small text-white-50" style="font-size: 10.5px;">Minimalis, rapi &amp; estetik</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Nordic Minimalist</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #0F172A;"></span>
                                    <span class="color-swatch" style="background: #334155;"></span>
                                    <span class="color-swatch" style="background: #0EA5E9;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- 9. AMBER --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'amber' ? 'active' : '' }}" onclick="selectTemplate('amber')">
                            <input type="radio" name="template" value="amber" {{ $currentTemplate === 'amber' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 text-white mb-2" style="background: linear-gradient(135deg, #78350F, #D97706); height: 90px;">
                                <span class="badge bg-white text-dark font-semibold rounded-pill px-2 py-0.5" style="font-size: 9.5px;">Sunshine Gold</span>
                                <h6 class="fw-bold text-white mb-0 mt-2" style="font-size: 13px;">Golden Sunshine</h6>
                                <span class="small text-white-50" style="font-size: 10.5px;">Ceria, cerdas &amp; berharapan</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Golden Sunshine</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #78350F;"></span>
                                    <span class="color-swatch" style="background: #D97706;"></span>
                                    <span class="color-swatch" style="background: #10B981;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- 10. CYAN --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'cyan' ? 'active' : '' }}" onclick="selectTemplate('cyan')">
                            <input type="radio" name="template" value="cyan" {{ $currentTemplate === 'cyan' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 text-white mb-2" style="background: linear-gradient(135deg, #164E63, #0891B2); height: 90px;">
                                <span class="badge bg-white text-dark font-semibold rounded-pill px-2 py-0.5" style="font-size: 9.5px;">Tech Horizon</span>
                                <h6 class="fw-bold text-white mb-0 mt-2" style="font-size: 13px;">Cyber Teal</h6>
                                <span class="small text-white-50" style="font-size: 10.5px;">Digital, canggih &amp; dinamis</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Cyber Teal</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #164E63;"></span>
                                    <span class="color-swatch" style="background: #0891B2;"></span>
                                    <span class="color-swatch" style="background: #A855F7;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- 11. ROSE --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'rose' ? 'active' : '' }}" onclick="selectTemplate('rose')">
                            <input type="radio" name="template" value="rose" {{ $currentTemplate === 'rose' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 text-white mb-2" style="background: linear-gradient(135deg, #881337, #E11D48); height: 90px;">
                                <span class="badge bg-white text-dark font-semibold rounded-pill px-2 py-0.5" style="font-size: 9.5px;">Quartz Rose Gold</span>
                                <h6 class="fw-bold text-white mb-0 mt-2" style="font-size: 13px;">Rose Gold Quartz</h6>
                                <span class="small text-white-50" style="font-size: 10.5px;">Feminin, anggun &amp; kasih</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Rose Gold</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #881337;"></span>
                                    <span class="color-swatch" style="background: #E11D48;"></span>
                                    <span class="color-swatch" style="background: #FB7185;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- 12. MIDNIGHT --}}
                    <div class="col-md-6 col-xl-3">
                        <label class="template-card-option p-3 h-100 d-block {{ $currentTemplate === 'midnight' ? 'active' : '' }}" onclick="selectTemplate('midnight')">
                            <input type="radio" name="template" value="midnight" {{ $currentTemplate === 'midnight' ? 'checked' : '' }}>
                            <div class="rounded-3 p-3 text-white mb-2" style="background: linear-gradient(135deg, #030712, #1E293B); height: 90px; border: 1px solid #334155;">
                                <span class="badge rounded-pill px-2 py-0.5" style="font-size: 9.5px; background: #38BDF8; color: #0F172A;">Obsidian Dark</span>
                                <h6 class="fw-bold text-white mb-0 mt-2" style="font-size: 13px;">Midnight Obsidian</h6>
                                <span class="small text-white-50" style="font-size: 10.5px;">Eksklusif &amp; premium dark</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="fw-bold text-dark" style="font-size: 12px;">Midnight Obsidian</span>
                                <div class="d-flex gap-1">
                                    <span class="color-swatch" style="background: #030712;"></span>
                                    <span class="color-swatch" style="background: #1E293B;"></span>
                                    <span class="color-swatch" style="background: #38BDF8;"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                </div>
            </div>
        </div>

        {{-- SECTION 0: IDENTITAS & BRANDING YAYASAN --}}
        <div class="card border shadow-sm mb-4 theme-card-container">
            <div class="card-header theme-card-header p-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:globus-bold-duotone" class="theme-text-primary fs-5"></iconify-icon> Identitas &amp; Branding Yayasan
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Pengaturan nama publik, logo yayasan, serta nomor telepon kontak</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Nama Publik Yayasan</label>
                        <input type="text" class="form-control rounded-3" name="site_title" value="{{ $content['site_title'] ?? $yayasan->nama_yayasan }}" placeholder="Contoh: Yayasan Al-Hikmah">
                        <span class="text-muted d-block mt-1" style="font-size: 11px;">Nama ini akan tampil di bagian header, footer, dan tab browser.</span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Logo Yayasan</label>
                        <div class="upload-drag-area position-relative">
                            <input type="file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" name="site_logo" accept="image/*">
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                @if(!empty($content['site_logo']))
                                    <img src="{{ asset($content['site_logo']) }}" width="44" height="44" class="rounded object-fit-contain border p-1 bg-white shadow-xs">
                                @elseif($yayasan->logo_yayasan)
                                    <img src="{{ asset('storage/' . $yayasan->logo_yayasan) }}" width="44" height="44" class="rounded object-fit-contain border p-1 bg-white shadow-xs">
                                @else
                                    <div class="rounded-circle bg-primary bg-opacity-10 theme-text-primary p-2 d-flex align-items-center justify-content-center">
                                        <iconify-icon icon="solar:upload-bold-duotone" class="fs-4"></iconify-icon>
                                    </div>
                                @endif
                                <div class="text-start">
                                    <span class="fw-bold text-dark d-block" style="font-size: 12.5px;">Klik atau seret file gambar logo di sini</span>
                                    <span class="text-muted" style="font-size: 11px;">Format PNG/JPG (Maks. 2MB)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 1: HERO ATAS --}}
        <div class="card border shadow-sm mb-4 theme-card-container">
            <div class="card-header theme-card-header p-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:star-bold-duotone" class="theme-text-primary fs-5"></iconify-icon> Bagian Hero Promosi (Atas)
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Judul utama banner, deskripsi slogan, serta gambar banner utama</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Judul Hero Utama (Teks Hitam)</label>
                        <input type="text" class="form-control rounded-3" name="hero_title_1" value="{{ $content['hero_title_1'] ?? 'Ciptakan Generasi' }}" placeholder="Contoh: Ciptakan Generasi">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Judul Tambahan (Teks Warna Bawah)</label>
                        <input type="text" class="form-control rounded-3" name="hero_title_2" value="{{ $content['hero_title_2'] ?? 'Cerdas & Ceria' }}" placeholder="Contoh: Cerdas & Ceria">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label font-semibold text-dark small">Judul Banner Gabungan (Opsional)</label>
                        <input type="text" class="form-control rounded-3" name="hero_title" value="{{ $content['hero_title'] ?? '' }}" placeholder="Mengganti judul di atas jika diisi">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label font-semibold text-dark small">Subjudul Teks Deskripsi Hero</label>
                    <textarea class="form-control summernote" name="hero_subtitle" rows="3" placeholder="Deskripsi ringkas komitmen pendidikan yayasan...">{{ $content['hero_subtitle'] ?? 'Memberikan pendidikan terbaik untuk generasi masa depan dengan fasilitas lengkap dan tenaga pengajar profesional.' }}</textarea>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Gambar Banner Hero Kanan</label>
                        <input type="file" class="form-control rounded-3" name="hero_image" accept="image/*">
                        @if(!empty($content['hero_image']))
                            <div class="mt-2.5 d-flex align-items-center gap-2.5">
                                <img src="{{ asset($content['hero_image']) }}" style="max-height: 80px; border-radius: 10px;" class="border p-1 bg-white shadow-xs">
                                <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1" style="font-size: 11px;">Gambar Hero Aktif</span>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Nomor WhatsApp Pendaftaran (CTA)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3"><iconify-icon icon="solar:phone-calling-bold-duotone" class="fs-5 theme-text-primary"></iconify-icon></span>
                            <input type="text" class="form-control rounded-end-3" name="whatsapp_cta" value="{{ $content['whatsapp_cta'] ?? $yayasan->telepon }}" placeholder="Contoh: 081234567890">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Alamat Tampilan Publik</label>
                        <input type="text" class="form-control rounded-3" name="alamat_tampil" value="{{ $content['alamat_tampil'] ?? $yayasan->alamat }}" placeholder="Contoh: Jl. Merdeka No. 45, Surabaya">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Tahun Berdiri Yayasan</label>
                        <input type="number" class="form-control rounded-3" name="tahun_berdiri" value="{{ $content['tahun_berdiri'] ?? '' }}" placeholder="Contoh: 2015">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small d-block">Status Badge Floating Foto Kanan</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="show_hero_badge" id="badge_active_1" value="1" {{ ($content['show_hero_badge'] ?? '1') == '1' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success font-semibold rounded-start-3 py-2 text-center" for="badge_active_1" style="font-size: 13px;">
                                <iconify-icon icon="solar:check-circle-bold" class="me-1 align-middle fs-5"></iconify-icon> Aktifkan
                            </label>

                            <input type="radio" class="btn-check" name="show_hero_badge" id="badge_active_0" value="0" {{ ($content['show_hero_badge'] ?? '1') == '0' ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary font-semibold rounded-end-3 py-2 text-center" for="badge_active_0" style="font-size: 13px;">
                                <iconify-icon icon="solar:close-circle-bold" class="me-1 align-middle fs-5"></iconify-icon> Tidak Aktif
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Judul Badge Floating Foto Kanan</label>
                        <input type="text" class="form-control rounded-3" name="hero_badge_title" value="{{ $content['hero_badge_title'] ?? 'Pendidikan Holistik Ramah Anak' }}" placeholder="Contoh: Pendidikan Holistik Ramah Anak">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label font-semibold text-dark small">Deskripsi Badge Floating Foto Kanan</label>
                        <input type="text" class="form-control rounded-3" name="hero_badge_desc" value="{{ $content['hero_badge_desc'] ?? 'Membimbing motorik, logika, & adab islami sejak usia dini.' }}" placeholder="Contoh: Membimbing motorik, logika, & adab islami sejak usia dini.">
                    </div>
                </div>

                {{-- Template Specific Extra Fields --}}
                <div class="row g-3 mt-2">
                    <div class="col-md-6 field-extra field-ceria field-natural" style="display: none;">
                        <label class="form-label font-semibold text-dark small">Teks Badge / Pill Atas Hero</label>
                        <input type="text" class="form-control rounded-3" name="badge_text" value="{{ $content['badge_text'] ?? '' }}" placeholder="Contoh: Penerimaan siswa baru 2026/2027">
                    </div>
                    <div class="col-md-6 field-extra field-trustworthy" style="display: none;">
                        <label class="form-label font-semibold text-dark small">Akreditasi Yayasan / TK</label>
                        <input type="text" class="form-control rounded-3" name="akreditasi" value="{{ $content['akreditasi'] ?? 'A' }}" placeholder="Contoh: A (Sangat Baik)">
                    </div>
                    <div class="col-md-6 field-extra field-trustworthy" style="display: none;">
                        <label class="form-label font-semibold text-dark small">Jumlah Tenaga Pengajar</label>
                        <input type="number" class="form-control rounded-3" name="jumlah_pengajar" value="{{ $content['jumlah_pengajar'] ?? '18' }}" placeholder="Contoh: 18">
                    </div>

                    <div class="col-md-4 field-extra field-ceria" style="display: none;">
                        <label class="form-label font-semibold text-dark small">Judul Card 1 (Pill Orange)</label>
                        <input type="text" class="form-control rounded-3" name="card1_title" value="{{ $content['card1_title'] ?? 'Bermain aktif' }}">
                    </div>
                    <div class="col-md-4 field-extra field-ceria" style="display: none;">
                        <label class="form-label font-semibold text-dark small">Judul Card 2 (Pill Biru)</label>
                        <input type="text" class="form-control rounded-3" name="card2_title" value="{{ $content['card2_title'] ?? 'Calistung dasar' }}">
                    </div>
                    <div class="col-md-4 field-extra field-ceria" style="display: none;">
                        <label class="form-label font-semibold text-dark small">Judul Card 3 (Pill Pink)</label>
                        <input type="text" class="form-control rounded-3" name="card3_title" value="{{ $content['card3_title'] ?? 'Seni dan musik' }}">
                    </div>

                    <div class="col-md-6 field-extra field-natural" style="display: none;">
                        <label class="form-label font-semibold text-dark small">Judul Card Fitur 1 (Hijau)</label>
                        <input type="text" class="form-control rounded-3 mb-2" name="card1_title_nat" value="{{ $content['card1_title'] ?? 'Kelas berkebun' }}" placeholder="Judul">
                        <input type="text" class="form-control rounded-3" name="card1_desc" value="{{ $content['card1_desc'] ?? 'Anak belajar merawat tanaman sendiri.' }}" placeholder="Deskripsi">
                    </div>
                    <div class="col-md-6 field-extra field-natural" style="display: none;">
                        <label class="form-label font-semibold text-dark small">Judul Card Fitur 2 (Amber)</label>
                        <input type="text" class="form-control rounded-3 mb-2" name="card2_title_nat" value="{{ $content['card2_title'] ?? 'Eksplorasi mandiri' }}" placeholder="Judul">
                        <input type="text" class="form-control rounded-3" name="card2_desc" value="{{ $content['card2_desc'] ?? 'Metode Montessori, anak memilih aktivitasnya.' }}" placeholder="Deskripsi">
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION: CABANG SEKOLAH DI BAWAH YAYASAN (GAMBAR 2) --}}
        <div class="card border shadow-sm mb-4 theme-card-container">
            <div class="card-header theme-card-header p-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:buildings-3-bold-duotone" class="theme-text-primary fs-5"></iconify-icon> Tampilan Seksi Cabang Sekolah
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Pengaturan judul, subjudul, dan teks tombol pendaftaran pada daftar cabang TK / PAUD</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label font-semibold text-dark small">Badge Label Seksi</label>
                        <input type="text" class="form-control rounded-3" name="cabang_pill_tag" value="{{ $content['cabang_pill_tag'] ?? 'Unit Pendidikan' }}" placeholder="Contoh: Unit Pendidikan">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label font-semibold text-dark small">Judul Utama Seksi Cabang</label>
                        <input type="text" class="form-control rounded-3" name="cabang_title" value="{{ $content['cabang_title'] ?? 'Cabang Sekolah di Bawah Yayasan' }}" placeholder="Contoh: Cabang Sekolah di Bawah Yayasan">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label font-semibold text-dark small">Subjudul Deskripsi Seksi Cabang</label>
                        <input type="text" class="form-control rounded-3" name="cabang_subtitle" value="{{ $content['cabang_subtitle'] ?? 'Pilih cabang TK / PAUD terdekat dengan domisili Anda untuk melihat informasi kuota dan mendaftarkan anak Anda.' }}" placeholder="Deskripsi ringkas...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label font-semibold text-dark small">Teks Tombol Pendaftaran Card</label>
                        <input type="text" class="form-control rounded-3" name="cabang_btn_text" value="{{ $content['cabang_btn_text'] ?? 'Daftar ke Sekolah Ini →' }}" placeholder="Contoh: Daftar ke Sekolah Ini →">
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION: GALERI KEGIATAN & FASILITAS UNGGULAN (GAMBAR 1) --}}
        <div class="card border shadow-sm mb-4 theme-card-container">
            <div class="card-header theme-card-header p-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:gallery-bold-duotone" class="theme-text-primary fs-5"></iconify-icon> Seksi Galeri Kegiatan &amp; Fasilitas Unggulan
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Kelola judul seksi serta 6 card foto, tag, dan deskripsi kegiatan unggulan</span>
            </div>
            <div class="card-body p-4">
                {{-- Header Seksi Galeri --}}
                <div class="row g-3 mb-4 border-bottom pb-4">
                    <div class="col-md-4">
                        <label class="form-label font-semibold text-dark small">Badge Label Seksi Galeri</label>
                        <input type="text" class="form-control rounded-3" name="galeri_pill_tag" value="{{ $content['galeri_pill_tag'] ?? 'Aktivitas & Fasilitas' }}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label font-semibold text-dark small">Judul Utama Seksi Galeri</label>
                        <input type="text" class="form-control rounded-3" name="galeri_title" value="{{ $content['galeri_title'] ?? 'Galeri Kegiatan & Fasilitas Unggulan' }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label font-semibold text-dark small">Subjudul Seksi Galeri</label>
                        <textarea class="form-control rounded-3" name="galeri_subtitle" rows="2">{{ $content['galeri_subtitle'] ?? 'Dokumentasi nyata keseruan belajar, bermain, pembiasaan ibadah, dan eksplorasi bakat anak-anak di sekolah kami.' }}</textarea>
                    </div>
                </div>

                {{-- Grid 6 Cards Galeri --}}
                <div class="row g-4">
                    @php
                        $defaultGaleri = [
                            1 => ['tag' => 'Kreativitas & Motorik', 'title' => 'Eksplorasi Belajar & Sensorik', 'desc' => 'Mewarnai, meronce, menyusun balok, dan melatih motorik halus dengan metode bermain bermakna.', 'img' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=700&auto=format&fit=crop&q=80'],
                            2 => ['tag' => 'Karakter & Keagamaan', 'title' => 'Tahfidz Cilik & Pembiasaan Doa', 'desc' => 'Hafalan surat-surat pendek juz 30, doa harian, dan pengenalan adab sopan santun sejak dini.', 'img' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=700&auto=format&fit=crop&q=80'],
                            3 => ['tag' => 'Ekstrakurikuler', 'title' => 'Minat Bakat & Drumband Cilik', 'desc' => 'Melatih kekompakan, rasa percaya diri, dan kepekaan musikalitas anak melalui ekskul pilihan.', 'img' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=700&auto=format&fit=crop&q=80'],
                            4 => ['tag' => 'Area Bermain Outbound', 'title' => 'Taman Bermain Aman & Asri', 'desc' => 'Fasilitas playground indoor & outdoor yang aman untuk melatih ketangkasan serta motorik kasar.', 'img' => 'https://images.unsplash.com/photo-1596464716127-f2a829822301?w=700&auto=format&fit=crop&q=80'],
                            5 => ['tag' => 'Pentas Seni & Field Trip', 'title' => 'Panggung Apresiasi & Wisata Edukasi', 'desc' => 'Kunjungan profesi edukatif serta panggung tahunan untuk melatih keberanian anak tampil di depan publik.', 'img' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=700&auto=format&fit=crop&q=80'],
                            6 => ['tag' => 'Sains & Eksplorasi', 'title' => 'Eksperimen Sains Anak Usia Dini', 'desc' => 'Mengenalkan logika fenomena alam dan rasa ingin tahu ilmiah melalui percobaan sederhana yang aman.', 'img' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=700&auto=format&fit=crop&q=80'],
                        ];
                    @endphp

                    @for($i = 1; $i <= 6; $i++)
                        @php
                            $def = $defaultGaleri[$i];
                            $cTag = $content["galeri_{$i}_tag"] ?? $def['tag'];
                            $cTitle = $content["galeri_{$i}_title"] ?? $def['title'];
                            $cDesc = $content["galeri_{$i}_desc"] ?? $def['desc'];
                            $cImg = $content["galeri_{$i}_image"] ?? $def['img'];
                        @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="p-3.5 rounded-3 border theme-subcard h-100">
                                <h6 class="fw-bold mb-3 theme-text-primary d-flex align-items-center justify-content-between" style="font-size: 13.5px;">
                                    <span><iconify-icon icon="solar:camera-bold-duotone" class="fs-5 me-1"></iconify-icon> Card Galeri #{{ $i }}</span>
                                    <span class="badge bg-primary-subtle theme-text-primary rounded-pill px-2 py-0.5" style="font-size: 10px;">Item {{ $i }}</span>
                                </h6>
                                <div class="mb-2.5">
                                    <label class="form-label font-semibold text-dark small mb-1">Badge Tag Pill</label>
                                    <input type="text" class="form-control form-control-sm rounded-3" name="galeri_{{ $i }}_tag" value="{{ $cTag }}">
                                </div>
                                <div class="mb-2.5">
                                    <label class="form-label font-semibold text-dark small mb-1">Judul Kegiatan / Fasilitas</label>
                                    <input type="text" class="form-control form-control-sm rounded-3" name="galeri_{{ $i }}_title" value="{{ $cTitle }}">
                                </div>
                                <div class="mb-2.5">
                                    <label class="form-label font-semibold text-dark small mb-1">Deskripsi Ringkas</label>
                                    <textarea class="form-control form-control-sm rounded-3" name="galeri_{{ $i }}_desc" rows="2">{{ $cDesc }}</textarea>
                                </div>
                                <div>
                                    <label class="form-label font-semibold text-dark small mb-1">Upload Foto Galeri #{{ $i }}</label>
                                    <input type="file" class="form-control form-control-sm rounded-3" name="galeri_{{ $i }}_image" accept="image/*">
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <img src="{{ asset($cImg) }}" class="rounded border object-fit-cover shadow-xs" width="60" height="44" onerror="this.src='{{ $def['img'] }}'">
                                        <span class="text-muted" style="font-size: 10.5px;">Preview foto saat ini</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- SECTION 2: PROFIL & PROGRAM (3 COLUMN GRID) --}}
        <div class="card border shadow-sm mb-4 theme-card-container">
            <div class="card-header theme-card-header p-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="theme-text-primary fs-5"></iconify-icon> Bagian Profil &amp; Program Unggulan
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Profil institusi, program TK, PAUD, serta pimpinan unit</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Judul Seksi Profil</label>
                        <input type="text" class="form-control rounded-3" name="profil_title" value="{{ $content['profil_title'] ?? 'Kenali Lebih Dekat' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-semibold text-dark small">Subjudul Seksi Profil</label>
                        <textarea class="form-control summernote" name="profil_subtitle" rows="2">{{ $content['profil_subtitle'] ?? 'Pelajari lebih lanjut tentang institusi dan program unggulan kami untuk putra-putri Anda.' }}</textarea>
                    </div>
                </div>

                {{-- 3 Column Tabs Grid --}}
                <div class="row g-4 border-top pt-4">
                    {{-- Tab Yayasan --}}
                    <div class="col-lg-4">
                        <div class="p-3.5 rounded-3 border theme-subcard h-100">
                            <h6 class="fw-bold mb-3 theme-text-primary d-flex align-items-center gap-2" style="font-size: 14px;">
                                <iconify-icon icon="solar:buildings-bold-duotone" class="fs-5"></iconify-icon> Profil Tab Yayasan
                            </h6>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Nama Yayasan</label>
                                <input type="text" class="form-control rounded-3" name="yayasan_title" value="{{ $content['yayasan_title'] ?? $yayasan->nama_yayasan }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Deskripsi Singkat</label>
                                <textarea class="form-control summernote" name="yayasan_desc" rows="4">{{ $content['yayasan_desc'] ?? 'Yayasan kami berkomitmen penuh untuk mencetak generasi Islami yang unggul.' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Visi</label>
                                <textarea class="form-control summernote" name="yayasan_visi" rows="2">{{ $content['yayasan_visi'] ?? 'Mewujudkan lembaga pendidikan yang berpijak pada penanaman aqidah Islam.' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Misi</label>
                                <textarea class="form-control summernote" name="yayasan_misi" rows="2">{{ $content['yayasan_misi'] ?? 'Menyelenggarakan proses pembelajaran yang berkualitas.' }}</textarea>
                            </div>
                            <hr class="my-3 text-muted">
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Nama Profil Pimpinan</label>
                                <input type="text" class="form-control rounded-3" name="yayasan_profile_name" value="{{ $content['yayasan_profile_name'] ?? ($yayasan->penanggung_jawab ?? 'Pimpinan Yayasan') }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Jabatan</label>
                                <input type="text" class="form-control rounded-3" name="yayasan_profile_title" value="{{ $content['yayasan_profile_title'] ?? 'Ketua Yayasan' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Foto Pimpinan</label>
                                <input type="file" class="form-control rounded-3" name="yayasan_image" accept="image/*">
                                @if(!empty($content['yayasan_image']))
                                    <div class="mt-2"><img src="{{ asset($content['yayasan_image']) }}" height="50" class="rounded border p-1 bg-white"></div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Tab Program TK --}}
                    <div class="col-lg-4">
                        <div class="p-3.5 rounded-3 border theme-subcard h-100">
                            <h6 class="fw-bold mb-3 theme-text-primary d-flex align-items-center gap-2" style="font-size: 14px;">
                                <iconify-icon icon="solar:black-hole-bold-duotone" class="fs-5"></iconify-icon> Profil Tab Program TK
                            </h6>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Judul Program TK</label>
                                <input type="text" class="form-control rounded-3" name="tk_title" value="{{ $content['tk_title'] ?? 'Taman Kanak-Kanak (TK)' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Deskripsi Singkat</label>
                                <textarea class="form-control summernote" name="tk_desc" rows="4">{{ $content['tk_desc'] ?? 'Program TK berfokus pada pengembangan karakter, motorik, sosial, dan kesiapan sekolah.' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Visi</label>
                                <textarea class="form-control summernote" name="tk_visi" rows="2">{{ $content['tk_visi'] ?? 'Membentuk anak usia dini yang cerdas, mandiri, dan berakhlak mulia.' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Misi</label>
                                <textarea class="form-control summernote" name="tk_misi" rows="2">{{ $content['tk_misi'] ?? 'Pembelajaran berbasis bermain ramah anak.' }}</textarea>
                            </div>
                            <hr class="my-3 text-muted">
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Nama Pimpinan TK</label>
                                <input type="text" class="form-control rounded-3" name="tk_profile_name" value="{{ $content['tk_profile_name'] ?? 'Kepala Sekolah TK' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Jabatan</label>
                                <input type="text" class="form-control rounded-3" name="tk_profile_title" value="{{ $content['tk_profile_title'] ?? 'Kepala TK' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Foto Pimpinan TK</label>
                                <input type="file" class="form-control rounded-3" name="tk_image" accept="image/*">
                                @if(!empty($content['tk_image']))
                                    <div class="mt-2"><img src="{{ asset($content['tk_image']) }}" height="50" class="rounded border p-1 bg-white"></div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Tab Program PAUD --}}
                    <div class="col-lg-4">
                        <div class="p-3.5 rounded-3 border theme-subcard h-100">
                            <h6 class="fw-bold mb-3 theme-text-primary d-flex align-items-center gap-2" style="font-size: 14px;">
                                <iconify-icon icon="solar:smile-circle-bold-duotone" class="fs-5"></iconify-icon> Profil Tab Program PAUD
                            </h6>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Judul Program PAUD</label>
                                <input type="text" class="form-control rounded-3" name="paud_title" value="{{ $content['paud_title'] ?? 'Pendidikan Anak Usia Dini (PAUD)' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Deskripsi Singkat</label>
                                <textarea class="form-control summernote" name="paud_desc" rows="4">{{ $content['paud_desc'] ?? 'Program PAUD stimulasi usia golden age dengan lingkungan belajar yang hangat.' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Visi</label>
                                <textarea class="form-control summernote" name="paud_visi" rows="2">{{ $content['paud_visi'] ?? 'Menjadi wadah stimulasi tumbuh kembang balita yang aman dan menyenangkan.' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-semibold text-dark small">Misi</label>
                                <textarea class="form-control summernote" name="paud_misi" rows="2">{{ $content['paud_misi'] ?? 'Aktivitas motorik dan sosialisasi sejak usia dini.' }}</textarea>
                            </div>
                            <hr class="my-3 text-muted">
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Nama Pimpinan PAUD</label>
                                <input type="text" class="form-control rounded-3" name="paud_profile_name" value="{{ $content['paud_profile_name'] ?? 'Pengelola PAUD' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Jabatan</label>
                                <input type="text" class="form-control rounded-3" name="paud_profile_title" value="{{ $content['paud_profile_title'] ?? 'Kepala PAUD' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-semibold text-dark small">Foto Pimpinan PAUD</label>
                                <input type="file" class="form-control rounded-3" name="paud_image" accept="image/*">
                                @if(!empty($content['paud_image']))
                                    <div class="mt-2"><img src="{{ asset($content['paud_image']) }}" height="50" class="rounded border p-1 bg-white"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TOMBOL SIMPAN PERUBAHAN DI AKHIR HALAMAN (STATIC, TIDAK NGAMBANG) --}}
        <div class="card border shadow-sm static-save-card theme-card-container mb-4 mt-2">
            <div class="card-body p-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 theme-card-header" style="border-radius: 14px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-2.5 theme-brand-bg-soft theme-text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                        <iconify-icon icon="solar:info-circle-bold-duotone" class="fs-4"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0.5 text-dark" style="font-size: 14px;">Konfirmasi Simpan Tampilan &amp; Konten</h6>
                        <span class="text-muted small" style="font-size: 12px;">Seluruh perubahan profil &amp; template landing page yayasan akan langsung diterapkan secara publik.</span>
                    </div>
                </div>
                <button type="submit" class="btn theme-btn-primary rounded-3 px-4 py-2.5 font-semibold d-flex align-items-center gap-2 shadow-sm" style="font-size: 13px;">
                    <iconify-icon icon="solar:diskette-bold-duotone" class="fs-5"></iconify-icon> Simpan Perubahan Tampilan
                </button>
            </div>
        </div>

    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    const themeConfigMap = {
        ocean: { primary: '#2563eb', primaryDark: '#0f172a', accent: '#f59e0b', surface: '#f8fafc', pillBg: '#eff6ff', pillBorder: '#dbeafe', gradient: 'linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%)' },
        ceria: { primary: '#EF9F27', primaryDark: '#7A5310', accent: '#F0997B', surface: '#FDFBF7', pillBg: '#FFF7ED', pillBorder: '#FFEDD5', gradient: 'linear-gradient(135deg, #7A5310 0%, #D4537E 60%, #EF9F27 100%)' },
        trustworthy: { primary: '#185FA5', primaryDark: '#0B2545', accent: '#60A5FA', surface: '#F4F6F9', pillBg: '#F0F9FF', pillBorder: '#E0F2FE', gradient: 'linear-gradient(135deg, #0B2545 0%, #134074 60%, #185FA5 100%)' },
        natural: { primary: '#639922', primaryDark: '#1C3309', accent: '#FAC775', surface: '#F6F3EC', pillBg: '#F7FEE7', pillBorder: '#ECFCCB', gradient: 'linear-gradient(135deg, #1C3309 0%, #355312 60%, #4D7C0F 100%)' },
        sunset: { primary: '#E11D48', primaryDark: '#4C0519', accent: '#F59E0B', surface: '#FFFDFB', pillBg: '#FFF1F2', pillBorder: '#FFE4E6', gradient: 'linear-gradient(135deg, #4C0519 0%, #9F1239 50%, #E11D48 100%)' },
        emerald: { primary: '#059669', primaryDark: '#064E3B', accent: '#FBBF24', surface: '#F4FBF7', pillBg: '#ECFDF5', pillBorder: '#D1FAE5', gradient: 'linear-gradient(135deg, #064E3B 0%, #047857 60%, #10B981 100%)' },
        violet: { primary: '#7C3AED', primaryDark: '#3B0764', accent: '#EC4899', surface: '#FAF5FF', pillBg: '#F3E8FF', pillBorder: '#E9D5FF', gradient: 'linear-gradient(135deg, #3B0764 0%, #6D28D9 60%, #8B5CF6 100%)' },
        minimal: { primary: '#334155', primaryDark: '#0F172A', accent: '#0EA5E9', surface: '#F8FAFC', pillBg: '#F1F5F9', pillBorder: '#E2E8F0', gradient: 'linear-gradient(135deg, #0F172A 0%, #1E293B 60%, #334155 100%)' },
        amber: { primary: '#D97706', primaryDark: '#78350F', accent: '#10B981', surface: '#FFFBEB', pillBg: '#FEF3C7', pillBorder: '#FDE68A', gradient: 'linear-gradient(135deg, #78350F 0%, #B45309 60%, #F59E0B 100%)' },
        cyan: { primary: '#0891B2', primaryDark: '#164E63', accent: '#A855F7', surface: '#F0FDFA', pillBg: '#CCFBF1', pillBorder: '#99F6E4', gradient: 'linear-gradient(135deg, #164E63 0%, #0E7490 60%, #06B6D4 100%)' },
        rose: { primary: '#E11D48', primaryDark: '#881337', accent: '#FB7185', surface: '#FFF1F2', pillBg: '#FFE4E6', pillBorder: '#FECDD3', gradient: 'linear-gradient(135deg, #881337 0%, #BE123C 60%, #FB7185 100%)' },
        midnight: { primary: '#38BDF8', primaryDark: '#030712', accent: '#818CF8', surface: '#0F172A', pillBg: '#1E293B', pillBorder: '#334155', gradient: 'linear-gradient(135deg, #030712 0%, #0F172A 60%, #1E293B 100%)' }
    };

    function selectTemplate(tmpl) {
        document.querySelectorAll('.template-card-option').forEach(el => el.classList.remove('active'));
        const activeRadio = document.querySelector(`input[name="template"][value="${tmpl}"]`);
        if (activeRadio) {
            activeRadio.checked = true;
            activeRadio.closest('.template-card-option').classList.add('active');
        }

        const cfg = themeConfigMap[tmpl] || themeConfigMap.ocean;
        const root = document.documentElement;
        root.style.setProperty('--theme-primary', cfg.primary);
        root.style.setProperty('--theme-primary-dark', cfg.primaryDark);
        root.style.setProperty('--theme-accent', cfg.accent);
        root.style.setProperty('--theme-surface', cfg.surface);
        root.style.setProperty('--theme-pill-bg', cfg.pillBg);
        root.style.setProperty('--theme-pill-border', cfg.pillBorder);
        root.style.setProperty('--theme-hero-bg', cfg.gradient);

        // Hide/Show template conditional fields
        document.querySelectorAll('.field-extra').forEach(el => el.style.display = 'none');
        document.querySelectorAll(`.field-${tmpl}`).forEach(el => el.style.display = 'block');
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ !== 'undefined' && $.fn.summernote) {
            $('.summernote').summernote({
                height: 100,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });
        }

        const selected = document.querySelector('input[name="template"]:checked')?.value || 'ocean';
        selectTemplate(selected);
    });
</script>
@endpush
@endsection
