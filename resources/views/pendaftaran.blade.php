{{-- Halaman pendaftaran selalu pakai layout bersih: tanpa navbar, sidebar, atau footer --}}
@extends('layouts.pendaftaran')

@php
    $activeYayasan = $yayasan ?? null;
    if ($activeYayasan && !($activeYayasan instanceof \App\Models\Yayasan) && is_object($activeYayasan) && isset($activeYayasan->id)) {
        $activeYayasan = \App\Models\Yayasan::find($activeYayasan->id);
    }
    if (!$activeYayasan && isset($currentSchool) && !empty($currentSchool->yayasan_id)) {
        $activeYayasan = \App\Models\Yayasan::find($currentSchool->yayasan_id);
    }
    if (!$activeYayasan) {
        $activeYayasan = \App\Models\Yayasan::where('status', 'aktif')->first();
    }
    
    $landingContent = $activeYayasan->landing_content ?? [];
    $templateName   = $landingContent['template'] ?? 'ocean';

    $themeMap = [
        'ocean' => [
            'primary'      => '#1E40AF',
            'primary_dark' => '#1B3A8C',
            'accent'       => '#FCD34D',
            'surface'      => '#F7F9FC',
            'hero_bg'      => 'linear-gradient(135deg, #1B3A8C 0%, #2D55E8 60%, #4F8BFF 100%)',
            'btn_cta'      => 'linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%)',
            'btn_cta_text' => '#FFFFFF',
        ],
        'ceria' => [
            'primary'      => '#EF9F27',
            'primary_dark' => '#7A5310',
            'accent'       => '#F0997B',
            'surface'      => '#FDFBF7',
            'hero_bg'      => 'linear-gradient(135deg, #7A5310 0%, #D4537E 60%, #EF9F27 100%)',
            'btn_cta'      => 'linear-gradient(135deg, #EF9F27 0%, #D4537E 100%)',
            'btn_cta_text' => '#FFFFFF',
        ],
        'trustworthy' => [
            'primary'      => '#185FA5',
            'primary_dark' => '#0B2545',
            'accent'       => '#2563EB',
            'surface'      => '#F4F6F9',
            'hero_bg'      => 'linear-gradient(135deg, #0B2545 0%, #134074 60%, #185FA5 100%)',
            'btn_cta'      => 'linear-gradient(135deg, #185FA5 0%, #1D4ED8 100%)',
            'btn_cta_text' => '#FFFFFF',
        ],
        'natural' => [
            'primary'      => '#639922',
            'primary_dark' => '#1C3309',
            'accent'       => '#D97706',
            'surface'      => '#F6F3EC',
            'hero_bg'      => 'linear-gradient(135deg, #1C3309 0%, #355312 60%, #4D7C0F 100%)',
            'btn_cta'      => 'linear-gradient(135deg, #639922 0%, #355312 100%)',
            'btn_cta_text' => '#FFFFFF',
        ],
        'sunset' => [
            'primary'      => '#DC2626',
            'primary_dark' => '#4C0519',
            'accent'       => '#F59E0B',
            'surface'      => '#FFFDFB',
            'hero_bg'      => 'linear-gradient(135deg, #4C0519 0%, #9F1239 50%, #E11D48 100%)',
            'btn_cta'      => 'linear-gradient(135deg, #E11D48 0%, #BE123C 100%)',
            'btn_cta_text' => '#FFFFFF',
        ],
        'emerald' => [
            'primary'      => '#059669',
            'primary_dark' => '#064E3B',
            'accent'       => '#FBBF24',
            'surface'      => '#F4FBF7',
            'hero_bg'      => 'linear-gradient(135deg, #064E3B 0%, #047857 60%, #10B981 100%)',
            'btn_cta'      => 'linear-gradient(135deg, #059669 0%, #047857 100%)',
            'btn_cta_text' => '#FFFFFF',
        ],
        'violet' => [
            'primary'      => '#7C3AED',
            'primary_dark' => '#3B0764',
            'accent'       => '#EC4899',
            'surface'      => '#FAF5FF',
            'hero_bg'      => 'linear-gradient(135deg, #3B0764 0%, #6D28D9 60%, #8B5CF6 100%)',
            'btn_cta'      => 'linear-gradient(135deg, #5B21B6 0%, #7C3AED 100%)',
            'btn_cta_text' => '#FFFFFF',
        ],
        'minimal' => [
            'primary'      => '#334155',
            'primary_dark' => '#0F172A',
            'accent'       => '#0EA5E9',
            'surface'      => '#F8FAFC',
            'hero_bg'      => 'linear-gradient(135deg, #0F172A 0%, #1E293B 60%, #334155 100%)',
            'btn_cta'      => 'linear-gradient(135deg, #334155 0%, #1E293B 100%)',
            'btn_cta_text' => '#FFFFFF',
        ],
        'amber' => [
            'primary'      => '#D97706',
            'primary_dark' => '#78350F',
            'accent'       => '#10B981',
            'surface'      => '#FFFBEB',
            'hero_bg'      => 'linear-gradient(135deg, #78350F 0%, #B45309 60%, #F59E0B 100%)',
            'btn_cta'      => 'linear-gradient(135deg, #D97706 0%, #B45309 100%)',
            'btn_cta_text' => '#FFFFFF',
        ],
        'cyan' => [
            'primary'      => '#0891B2',
            'primary_dark' => '#164E63',
            'accent'       => '#A855F7',
            'surface'      => '#F0FDFA',
            'hero_bg'      => 'linear-gradient(135deg, #164E63 0%, #0E7490 60%, #06B6D4 100%)',
            'btn_cta'      => 'linear-gradient(135deg, #0891B2 0%, #0E7490 100%)',
            'btn_cta_text' => '#FFFFFF',
        ],
        'rose' => [
            'primary'      => '#E11D48',
            'primary_dark' => '#881337',
            'accent'       => '#FB7185',
            'surface'      => '#FFF1F2',
            'hero_bg'      => 'linear-gradient(135deg, #881337 0%, #BE123C 60%, #FB7185 100%)',
            'btn_cta'      => 'linear-gradient(135deg, #E11D48 0%, #BE123C 100%)',
            'btn_cta_text' => '#FFFFFF',
        ],
        'midnight' => [
            'primary'      => '#38BDF8',
            'primary_dark' => '#030712',
            'accent'       => '#818CF8',
            'surface'      => '#0F172A',
            'hero_bg'      => 'linear-gradient(135deg, #030712 0%, #0F172A 60%, #1E293B 100%)',
            'btn_cta'      => 'linear-gradient(135deg, #38BDF8 0%, #0284C7 100%)',
            'btn_cta_text' => '#0F172A',
        ],
    ];

    $theme = $themeMap[$templateName] ?? $themeMap['ocean'];
    
    $yayasanLoginUrl = '/';
    if ($activeYayasan && $activeYayasan instanceof \App\Models\Yayasan) {
        $yayasanLoginUrl = $activeYayasan->encrypted_login_url;
    }
@endphp

@section('topbar_back_btn')
    <a href="{{ $activeYayasan && $activeYayasan instanceof \App\Models\Yayasan ? route('yayasan.pendaftaran', $activeYayasan->encrypted_token) . '#login' : 'javascript:history.back()' }}" class="back-btn">
        <iconify-icon icon="solar:alt-arrow-left-bold-duotone"></iconify-icon>
        Kembali ke Portal PPDB
    </a>
@endsection

@section('topbar_branding')
    <a href="{{ $yayasanLoginUrl }}" class="brand">
        @if($activeYayasan && $activeYayasan->logo_yayasan)
            <img src="{{ asset('storage/' . $activeYayasan->logo_yayasan) }}" alt="Logo Yayasan" style="height: 28px; border-radius: 6px;">
        @else
            <div style="width: 28px; height: 28px; border-radius: 8px; background: {{ $theme['hero_bg'] }}; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;">
                {{ $activeYayasan ? strtoupper(substr($activeYayasan->nama_yayasan, 0, 2)) : 'KS' }}
            </div>
        @endif
        <span>{{ $activeYayasan ? $activeYayasan->nama_yayasan : 'Kiddy School ERP' }}</span>
    </a>
@endsection

@section('konten_halaman')

{{-- PANGGIL LIBRARY LEAFLET UNTUK PETA --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

{{-- PANGGIL LIBRARY LEAFLET GEOCODER --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<style>
    body { background-color: {{ $theme['surface'] }} !important; }
    
    .hero-banner-card {
        background: {{ $theme['hero_bg'] }} !important;
        color: #ffffff !important;
    }
    
    .text-theme-primary { color: {{ $theme['primary'] }} !important; }
    .bg-theme-primary { background-color: {{ $theme['primary'] }} !important; }
    .border-theme-primary { border-color: {{ $theme['primary'] }} !important; }

    .btn-theme-submit {
        background: {{ $theme['btn_cta'] }} !important;
        color: {{ $theme['btn_cta_text'] }} !important;
        border: none !important;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
        transition: all 0.25s ease !important;
    }
    .btn-theme-submit:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 24px rgba(0,0,0,0.22) !important;
        opacity: 0.95;
    }

    /* =============================================
       FORM INPUT — PLACEHOLDER vs NILAI TERISI
       ============================================= */

    /* Placeholder: warna lebih samar / pudar */
    .form-control::placeholder,
    .form-select::placeholder {
        color: #b0b8c5 !important;
        font-style: italic;
        opacity: 1;
    }
    /* Firefox */
    .form-control::-moz-placeholder,
    .form-select::-moz-placeholder {
        color: #b0b8c5 !important;
        opacity: 1;
    }

    /* Teks yang sudah diisi: gelap dan jelas terbaca */
    .form-control,
    .form-select {
        color: #1a202c !important;
        font-weight: 500 !important;
        border: 1.5px solid #dce3ed !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        height: auto !important;
        min-height: 44px !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease !important;
        background-color: #ffffff !important;
    }

    /* Select yang belum dipilih (value kosong): placeholder samar */
    .form-select option[value=""] {
        color: #b0b8c5 !important;
    }
    .form-select:invalid,
    .form-select option:first-child:checked {
        color: #b0b8c5 !important;
    }

    /* State: focus */
    .form-control:focus, .form-select:focus {
        border-color: {{ $theme['primary'] }} !important;
        box-shadow: 0 0 0 3px {{ $theme['primary'] }}22 !important;
        background-color: #ffffff !important;
        color: #1a202c !important;
    }

    /* State: readonly / latitude-longitude otomatis */
    .form-control[readonly] {
        background-color: #f3f6fa !important;
        color: {{ $theme['primary'] }} !important;
        font-weight: 600 !important;
        border-style: dashed !important;
        cursor: not-allowed;
    }

    /* =============================================
       FORM LABEL — lebih rapi & konsisten
       ============================================= */
    .form-label {
        font-weight: 600 !important;
        color: #374151 !important;
        margin-bottom: 5px !important;
        letter-spacing: 0.01em;
    }

    /* =============================================
       SECTION CARD — pembatas bagian data
       ============================================= */
    .form-section-card {
        background: #f8fafc;
        border: 1px solid #e8edf4;
        border-radius: 14px;
        padding: 20px 20px 8px 20px;
        margin-bottom: 8px;
    }
    .form-section-card .p-3.border.rounded.bg-light {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        border-radius: 12px !important;
    }

    /* =============================================
       HEADING SECTION FORM
       ============================================= */
    .form-section-heading {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: {{ $theme['primary'] }};
        border-bottom: 2px solid {{ $theme['primary'] }}22;
        padding-bottom: 8px;
        margin-bottom: 16px;
        margin-top: 24px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .form-section-heading::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 16px;
        background: {{ $theme['primary'] }};
        border-radius: 3px;
        flex-shrink: 0;
    }

    /* Hide HTML5 spinner arrows for number inputs */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }

    /* Form Validation Styling: Highlight required unfilled fields */
    .was-validated .form-control:invalid, 
    .was-validated .form-select:invalid,
    .form-control.is-invalid-custom,
    .form-select.is-invalid-custom {
        border-color: #EF4444 !important;
        background-color: #FEF2F2 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.18) !important;
    }

    /* =============================================
       FILE INPUT — lebih rapi
       ============================================= */
    input[type="file"].form-control {
        padding: 8px 12px !important;
        font-weight: 400 !important;
        color: #6b7280 !important;
        cursor: pointer;
        font-size: 12px !important;
    }
    input[type="file"].form-control::file-selector-button {
        background: {{ $theme['primary'] }}15;
        color: {{ $theme['primary'] }};
        border: 1px solid {{ $theme['primary'] }}40;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        margin-right: 10px;
    }
    input[type="file"].form-control::file-selector-button:hover {
        background: {{ $theme['primary'] }}25;
    }

    /* =============================================
       ROW GAP — tambah jarak antar baris
       ============================================= */
    .row.gx-3 > [class*="col-"] {
        padding-bottom: 2px;
    }
    .mb-form { margin-bottom: 18px !important; }

    /* =============================================
       CUSTOM BEAUTIFUL DROPDOWN
       ============================================= */

    /* Sembunyikan native select, tapi tetap ada di DOM untuk submit form */
    select.form-select.cs-hidden {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        opacity: 0 !important;
        pointer-events: none !important;
        z-index: -1 !important;
    }

    /* Wrapper custom select */
    .cs-wrapper {
        position: relative;
    }

    /* Trigger button */
    .cs-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        min-height: 44px;
        padding: 10px 14px;
        background: #ffffff;
        border: 1.5px solid #dce3ed;
        border-radius: 10px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 400;
        color: #b0b8c5;
        font-style: italic;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        user-select: none;
        outline: none;
        gap: 8px;
    }
    .cs-trigger.cs-has-value {
        color: #1a202c;
        font-style: normal;
        font-weight: 500;
    }
    .cs-trigger:hover {
        border-color: #aab4c8;
    }
    .cs-trigger.cs-open {
        border-color: {{ $theme['primary'] }};
        box-shadow: 0 0 0 3px {{ $theme['primary'] }}22;
        background: #ffffff;
    }
    .cs-trigger.cs-invalid {
        border-color: #EF4444 !important;
        background-color: #FEF2F2 !important;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.18) !important;
    }
    .cs-trigger.cs-disabled {
        background: #f3f6fa;
        cursor: not-allowed;
        opacity: 0.65;
    }

    /* Caret icon */
    .cs-caret {
        flex-shrink: 0;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        transition: transform 0.2s ease, color 0.2s;
    }
    .cs-trigger.cs-open .cs-caret {
        transform: rotate(180deg);
        color: {{ $theme['primary'] }};
    }
    .cs-caret svg {
        width: 14px;
        height: 14px;
    }

    /* Dropdown panel */
    .cs-dropdown {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1.5px solid {{ $theme['primary'] }}40;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
        z-index: 9999;
        overflow: hidden;
        display: none;
        animation: csDropIn 0.18s cubic-bezier(.4,0,.2,1);
        max-height: 260px;
        overflow-y: auto;
    }
    .cs-dropdown.cs-visible {
        display: block;
    }
    @keyframes csDropIn {
        from { opacity: 0; transform: translateY(-8px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Scrollbar dalam dropdown */
    .cs-dropdown::-webkit-scrollbar { width: 5px; }
    .cs-dropdown::-webkit-scrollbar-track { background: transparent; }
    .cs-dropdown::-webkit-scrollbar-thumb { background: #dce3ed; border-radius: 10px; }

    /* Option item */
    .cs-option {
        display: flex;
        align-items: center;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 400;
        color: #374151;
        cursor: pointer;
        transition: background 0.12s, color 0.12s;
        gap: 10px;
        border-bottom: 1px solid #f1f5f9;
    }
    .cs-option:last-child { border-bottom: none; }
    .cs-option:hover {
        background: {{ $theme['primary'] }}0f;
        color: {{ $theme['primary'] }};
        font-weight: 500;
    }
    .cs-option.cs-selected {
        background: {{ $theme['primary'] }}15;
        color: {{ $theme['primary'] }};
        font-weight: 600;
    }
    .cs-option.cs-selected::after {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: {{ $theme['primary'] }};
        margin-left: auto;
        flex-shrink: 0;
    }

    /* Placeholder option (value kosong) */
    .cs-option.cs-placeholder {
        color: #b0b8c5;
        font-style: italic;
        font-size: 12px;
        padding: 8px 16px;
        background: #f8fafc;
        pointer-events: none;
    }
</style>

<div class="container mt-3 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                {{-- Dynamic Theme Hero Banner --}}
                <div class="card-header hero-banner-card text-center py-4 border-bottom-0">
                    <div class="d-inline-flex align-items-center justify-content-center px-3 py-1 rounded-pill bg-white bg-opacity-20 text-white text-xs font-semibold mb-2" style="font-size: 11.5px; backdrop-filter: blur(4px);">
                        ✨ Pendaftaran Online Resmi — {{ $activeYayasan ? $activeYayasan->nama_yayasan : 'Kiddy School ERP' }}
                    </div>
                    <h3 class="mb-1 fw-extrabold text-white" style="letter-spacing: -0.02em;">Formulir Pendaftaran &amp; Inden Siswa Baru</h3>
                    <p class="mb-0 small text-white-50" id="header_school_name">
                        {{ $currentSchool->nama_sekolah ?? 'Pendaftaran Siswa Baru' }} {{ $activeYayasan ? '— ' . $activeYayasan->nama_yayasan : '' }}
                    </p>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    
                    {{-- 1-WAY UNIFIED REGISTRATION FORM --}}
                    <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data" id="formPendaftaran" novalidate>
                        @csrf

                        {{-- SECTION 1: PILIH CABANG SEKOLAH, TAHUN AJARAN, & PROGRAM KELAS --}}
                        <div class="p-3.5 p-md-4 rounded-3 border border-theme-primary bg-light mb-4 shadow-xs">
                            <div class="row gx-3 gy-3">
                                <div class="col-md-5">
                                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 13px;">
                                        1. Pilih Cabang TK / PAUD Tujuan <span class="text-danger">*</span>
                                    </label>
                                    @if(isset($is_locked) && $is_locked && $currentSchool)
                                        <input type="text" class="form-control bg-white border shadow-none fw-bold text-theme-primary" value="{{ $currentSchool->nama_sekolah }}" readonly style="font-size: 13.5px;">
                                        <input type="hidden" name="school_id" id="school_id_field" value="{{ $currentSchool->id }}">
                                    @else
                                        <select name="school_id" id="school_id_field" class="form-select bg-white border shadow-none fw-bold text-theme-primary" required style="font-size: 13.5px;">
                                            @if($schools->count() > 1)
                                                <option value="">-- Pilih Cabang Sekolah --</option>
                                            @endif
                                            @foreach($schools as $s)
                                                <option value="{{ $s->id }}" {{ ($selected_school_id == $s->id || $schools->count() == 1) ? 'selected' : '' }}>
                                                    {{ $s->nama_sekolah }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 13px;">
                                        2. Tahun Ajaran <span class="text-danger">*</span>
                                    </label>
                                    <select name="tahun_inden" id="tahun_inden" class="form-select bg-white border shadow-none fw-medium" required style="font-size: 13px;">
                                        <option value="">-- Pilih Tahun --</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 13px;">
                                        3. Program Kelas <span class="text-danger">*</span>
                                    </label>
                                    <select name="program_pilihan" id="program_pilihan" class="form-select bg-white border shadow-none fw-medium" required style="font-size: 13px;" disabled>
                                        <option value="">-- Pilih Tahun Dahulu --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- A. DATA PRIBADI --}}
                        <h6 class="fw-bold text-theme-primary border-bottom pb-2 mb-3 mt-4 text-uppercase" style="letter-spacing: 0.5px;">A. Data Pribadi Siswa</h6>
                        <div class="row gx-3">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Nama Lengkap Anak <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control bg-white border shadow-none" value="{{ old('nama_lengkap') }}" style="font-size: 13px;" required placeholder="Nama lengkap sesuai akta kelahiran">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">NIK Anak <span class="text-danger">*</span></label>
                                <input type="text" inputmode="numeric" maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="nik" class="form-control bg-white border shadow-none" value="{{ old('nik') }}" style="font-size: 13px;" required placeholder="16 digit NIK anak">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Nomor KK <span class="text-danger">*</span></label>
                                <input type="text" inputmode="numeric" maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="no_kk" class="form-control bg-white border shadow-none" value="{{ old('no_kk') }}" style="font-size: 13px;" required placeholder="16 digit Nomor Kartu Keluarga">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="jenis_kelamin" class="form-select bg-white border shadow-none" style="font-size: 13px;" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" name="tempat_lahir" class="form-control bg-white border shadow-none" value="{{ old('tempat_lahir') }}" style="font-size: 13px;" required placeholder="Kota kelahiran (Contoh: Malang)">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control bg-white border shadow-none" value="{{ old('tgl_lahir') }}" style="font-size: 13px;" required>
                                <div id="tgl_lahir_feedback" class="mt-1"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Agama <span class="text-danger">*</span></label>
                                <select name="agama" class="form-select bg-white border shadow-none" style="font-size: 13px;" required>
                                    <option value="">-- Pilih Agama --</option>
                                    <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                    <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Budha" {{ old('agama') == 'Budha' ? 'selected' : '' }}>Budha</option>
                                    <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Anak ke- <span class="text-danger">*</span></label>
                                <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="anak_ke" class="form-control bg-white border shadow-none" value="{{ old('anak_ke') }}" style="font-size: 13px;" required placeholder="Contoh: 1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Jumlah Saudara <span class="text-danger">*</span></label>
                                <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="jml_saudara" class="form-control bg-white border shadow-none" value="{{ old('jml_saudara') }}" style="font-size: 13px;" required placeholder="Contoh: 1">
                            </div>
                        </div>

                        {{-- B. ALAMAT & KONTAK --}}
                        <h6 class="fw-bold text-theme-primary border-bottom pb-2 mb-3 mt-4 text-uppercase" style="letter-spacing: 0.5px;">B. Alamat, Koordinat, &amp; Kontak</h6>
                        
                        {{-- PETA INTERAKTIF --}}
                        <div class="row gx-3 mb-3">
                            <div class="col-12 mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                    <label class="form-label fw-bold text-dark mb-0" style="font-size: 13px;"><iconify-icon icon="solar:map-point-bold-duotone" class="text-theme-primary"></iconify-icon> Cari Lokasi Rumah di Peta <span class="text-danger">*</span></label>
                                    <button type="button" id="btnGps" class="btn btn-sm btn-outline-primary rounded-pill font-semibold px-3 py-1 d-inline-flex align-items-center gap-1.5" style="font-size: 11.5px;">
                                        <iconify-icon icon="solar:gps-bold-duotone" class="fs-6"></iconify-icon> Deteksi Lokasi Saya (GPS)
                                    </button>
                                </div>
                                <p class="small text-muted mb-2">Gunakan kotak pencarian atau klik/geser pin di peta untuk menentukan lokasi rumah Anda. Lintang &amp; Bujur akan terisi otomatis.</p>
                                <div id="map" class="rounded border shadow-sm" style="height: 300px; width: 100%; z-index: 1;"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Garis Lintang (Latitude) <span class="text-danger">*</span></label>
                                <input type="text" id="lintang" name="lintang" class="form-control bg-light border shadow-none font-semibold text-theme-primary" value="{{ old('lintang') }}" style="font-size: 13px;" readonly required placeholder="Otomatis dari Peta">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Garis Bujur (Longitude) <span class="text-danger">*</span></label>
                                <input type="text" id="bujur" name="bujur" class="form-control bg-light border shadow-none font-semibold text-theme-primary" value="{{ old('bujur') }}" style="font-size: 13px;" readonly required placeholder="Otomatis dari Peta">
                            </div>
                        </div>

                        <div class="row gx-3">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Alamat Jalan <span class="text-danger">*</span></label>
                                <input type="text" name="alamat" class="form-control bg-white border shadow-none" value="{{ old('alamat') }}" style="font-size: 13px;" required placeholder="Contoh: Jl. Raya Melati No. 12">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">RT <span class="text-danger">*</span></label>
                                <input type="text" name="rt" class="form-control bg-white border shadow-none" value="{{ old('rt') }}" style="font-size: 13px;" required placeholder="Contoh: 01">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">RW <span class="text-danger">*</span></label>
                                <input type="text" name="rw" class="form-control bg-white border shadow-none" value="{{ old('rw') }}" style="font-size: 13px;" required placeholder="Contoh: 02">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Dusun <span class="text-muted fw-normal">(Opsional)</span></label>
                                <input type="text" name="dusun" class="form-control bg-white border shadow-none" value="{{ old('dusun') }}" style="font-size: 13px;" placeholder="Contoh: Dusun Krajan">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Desa/Kel. <span class="text-danger">*</span></label>
                                <input type="text" name="kelurahan" class="form-control bg-white border shadow-none" value="{{ old('kelurahan') }}" style="font-size: 13px;" required placeholder="Contoh: Sumbersari">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Kecamatan <span class="text-danger">*</span></label>
                                <input type="text" name="kecamatan" class="form-control bg-white border shadow-none" value="{{ old('kecamatan') }}" style="font-size: 13px;" required placeholder="Contoh: Lowokwaru">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Kode Pos <span class="text-danger">*</span></label>
                                <input type="text" inputmode="numeric" maxlength="5" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="kode_pos" class="form-control bg-white border shadow-none" value="{{ old('kode_pos') }}" style="font-size: 13px;" required placeholder="65145">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Jenis Tinggal <span class="text-danger">*</span></label>
                                <select name="jenis_tinggal" class="form-select bg-white border shadow-none" style="font-size: 13px;" required>
                                    <option value="">-- Pilih Jenis Tinggal --</option>
                                    <option value="Bersama orang tua" {{ old('jenis_tinggal') == 'Bersama orang tua' ? 'selected' : '' }}>Bersama orang tua</option>
                                    <option value="Wali" {{ old('jenis_tinggal') == 'Wali' ? 'selected' : '' }}>Wali</option>
                                    <option value="Asrama" {{ old('jenis_tinggal') == 'Asrama' ? 'selected' : '' }}>Asrama</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Transportasi ke Sekolah <span class="text-danger">*</span></label>
                                <select name="alat_transportasi" class="form-select bg-white border shadow-none" style="font-size: 13px;" required>
                                    <option value="">-- Pilih Transportasi --</option>
                                    <option value="Jalan kaki" {{ old('alat_transportasi') == 'Jalan kaki' ? 'selected' : '' }}>Jalan kaki</option>
                                    <option value="Sepeda" {{ old('alat_transportasi') == 'Sepeda' ? 'selected' : '' }}>Sepeda</option>
                                    <option value="Sepeda Motor" {{ old('alat_transportasi') == 'Sepeda Motor' ? 'selected' : '' }}>Sepeda Motor</option>
                                    <option value="Mobil Pribadi" {{ old('alat_transportasi') == 'Mobil Pribadi' ? 'selected' : '' }}>Mobil Pribadi</option>
                                    <option value="Antar Jemput Sekolah" {{ old('alat_transportasi') == 'Antar Jemput Sekolah' ? 'selected' : '' }}>Antar Jemput Sekolah</option>
                                    <option value="Angkutan Umum / Bus" {{ old('alat_transportasi') == 'Angkutan Umum / Bus' ? 'selected' : '' }}>Angkutan Umum / Bus</option>
                                    <option value="Lainnya" {{ old('alat_transportasi') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Jarak ke Sekolah (Km) <span class="text-danger">*</span></label>
                                <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="jarak_rumah" class="form-control bg-white border shadow-none" value="{{ old('jarak_rumah') }}" style="font-size: 13px;" required placeholder="Contoh: 1">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">No HP / WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="hp" id="parent_phone" class="form-control bg-white border shadow-none" value="{{ old('hp') }}" style="font-size: 13px;" required placeholder="08xxxxxxxxxx">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Email Orang Tua <span class="text-muted fw-normal">(Opsional)</span></label>
                                <input type="email" name="email" id="parent_email" class="form-control bg-white border shadow-none" value="{{ old('email') }}" style="font-size: 13px;" placeholder="nama@email.com (opsional)">
                                <div id="email_feedback_box" class="mt-2" style="display:none;"></div>
                            </div>
                            
                            @if(!Auth::check())
                                <div class="col-12 mb-3">
                                    <div class="p-3 rounded-3 border bg-light">
                                        <label class="form-label fw-semibold text-dark mb-1" id="password_label" style="font-size: 12.5px;">
                                            Kata Sandi Akun Portal Wali Murid <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="parent_password" class="form-control bg-white border shadow-none" style="font-size: 13px;" required placeholder="Min. 6 karakter" minlength="6">
                                            <button class="btn btn-outline-secondary bg-white border" type="button" id="btnTogglePassword">
                                                <iconify-icon icon="solar:eye-bold" id="eyeIcon"></iconify-icon>
                                            </button>
                                        </div>
                                        <div class="form-text text-muted" id="password_hint" style="font-size: 11px;">
                                            Kata sandi ini digunakan untuk login ke portal wali murid guna memantau status pendaftaran &amp; perkembangan anak.
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- C. DATA ORANG TUA --}}
                        <h6 class="fw-bold text-theme-primary border-bottom pb-2 mb-3 mt-4 text-uppercase" style="letter-spacing: 0.5px;">C. Data Orang Tua</h6>
                        <div class="row gx-3">
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded bg-light h-100">
                                    <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;">Data Ayah Kandung</h6>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-dark" style="font-size: 11px;">Nama Ayah <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_ayah" class="form-control bg-white border shadow-none" value="{{ old('nama_ayah') }}" style="font-size: 12px;" required placeholder="Nama lengkap ayah">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-dark" style="font-size: 11px;">NIK Ayah <span class="text-danger">*</span></label>
                                        <input type="text" inputmode="numeric" maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="nik_ayah" class="form-control bg-white border shadow-none" value="{{ old('nik_ayah') }}" style="font-size: 12px;" required placeholder="16 digit NIK ayah">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-dark" style="font-size: 11px;">Tahun Lahir <span class="text-danger">*</span></label>
                                        <input type="text" inputmode="numeric" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="tahun_lahir_ayah" class="form-control bg-white border shadow-none" value="{{ old('tahun_lahir_ayah') }}" style="font-size: 12px;" required placeholder="Contoh: 1980">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-dark" style="font-size: 11px;">Pendidikan <span class="text-danger">*</span></label>
                                        <select name="pendidikan_ayah" class="form-select bg-white border shadow-none" style="font-size: 12px;" required>
                                            <option value="">-- Pilih Pendidikan --</option>
                                            <option value="Tidak Sekolah / SD" {{ old('pendidikan_ayah') == 'Tidak Sekolah / SD' ? 'selected' : '' }}>Tidak Sekolah / SD</option>
                                            <option value="SMP / Sederajat" {{ old('pendidikan_ayah') == 'SMP / Sederajat' ? 'selected' : '' }}>SMP / Sederajat</option>
                                            <option value="SMA / SMK / Sederajat" {{ old('pendidikan_ayah') == 'SMA / SMK / Sederajat' ? 'selected' : '' }}>SMA / SMK / Sederajat</option>
                                            <option value="D1 / D2 / D3" {{ old('pendidikan_ayah') == 'D1 / D2 / D3' ? 'selected' : '' }}>D1 / D2 / D3</option>
                                            <option value="D4 / S1" {{ old('pendidikan_ayah') == 'D4 / S1' ? 'selected' : '' }}>D4 / S1</option>
                                            <option value="S2" {{ old('pendidikan_ayah') == 'S2' ? 'selected' : '' }}>S2</option>
                                            <option value="S3" {{ old('pendidikan_ayah') == 'S3' ? 'selected' : '' }}>S3</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-dark" style="font-size: 11px;">Pekerjaan <span class="text-danger">*</span></label>
                                        <input type="text" name="pekerjaan_ayah" class="form-control bg-white border shadow-none" value="{{ old('pekerjaan_ayah') }}" style="font-size: 12px;" required placeholder="Contoh: Karyawan Swasta / Wiraswasta">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-dark" style="font-size: 11px;">Penghasilan Bulanan <span class="text-danger">*</span></label>
                                        <input type="text" name="penghasilan_ayah" id="penghasilan_ayah" class="form-control bg-white border shadow-none" value="{{ old('penghasilan_ayah') }}" style="font-size: 12px;" required placeholder="Ketik angka saja (misal: 3000000)" oninput="formatRupiahInput(this)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded bg-light h-100">
                                    <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;">Data Ibu Kandung</h6>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-dark" style="font-size: 11px;">Nama Ibu <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_ibu" class="form-control bg-white border shadow-none" value="{{ old('nama_ibu') }}" style="font-size: 12px;" required placeholder="Nama lengkap ibu">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-dark" style="font-size: 11px;">NIK Ibu <span class="text-danger">*</span></label>
                                        <input type="text" inputmode="numeric" maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="nik_ibu" class="form-control bg-white border shadow-none" value="{{ old('nik_ibu') }}" style="font-size: 12px;" required placeholder="16 digit NIK ibu">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-dark" style="font-size: 11px;">Tahun Lahir <span class="text-danger">*</span></label>
                                        <input type="text" inputmode="numeric" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="tahun_lahir_ibu" class="form-control bg-white border shadow-none" value="{{ old('tahun_lahir_ibu') }}" style="font-size: 12px;" required placeholder="Contoh: 1985">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-dark" style="font-size: 11px;">Pendidikan <span class="text-danger">*</span></label>
                                        <select name="pendidikan_ibu" class="form-select bg-white border shadow-none" style="font-size: 12px;" required>
                                            <option value="">-- Pilih Pendidikan --</option>
                                            <option value="Tidak Sekolah / SD" {{ old('pendidikan_ibu') == 'Tidak Sekolah / SD' ? 'selected' : '' }}>Tidak Sekolah / SD</option>
                                            <option value="SMP / Sederajat" {{ old('pendidikan_ibu') == 'SMP / Sederajat' ? 'selected' : '' }}>SMP / Sederajat</option>
                                            <option value="SMA / SMK / Sederajat" {{ old('pendidikan_ibu') == 'SMA / SMK / Sederajat' ? 'selected' : '' }}>SMA / SMK / Sederajat</option>
                                            <option value="D1 / D2 / D3" {{ old('pendidikan_ibu') == 'D1 / D2 / D3' ? 'selected' : '' }}>D1 / D2 / D3</option>
                                            <option value="D4 / S1" {{ old('pendidikan_ibu') == 'D4 / S1' ? 'selected' : '' }}>D4 / S1</option>
                                            <option value="S2" {{ old('pendidikan_ibu') == 'S2' ? 'selected' : '' }}>S2</option>
                                            <option value="S3" {{ old('pendidikan_ibu') == 'S3' ? 'selected' : '' }}>S3</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-dark" style="font-size: 11px;">Pekerjaan <span class="text-danger">*</span></label>
                                        <input type="text" name="pekerjaan_ibu" class="form-control bg-white border shadow-none" value="{{ old('pekerjaan_ibu') }}" style="font-size: 12px;" required placeholder="Contoh: Ibu Rumah Tangga / PNS">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-dark" style="font-size: 11px;">Penghasilan Bulanan <span class="text-danger">*</span></label>
                                        <input type="text" name="penghasilan_ibu" id="penghasilan_ibu" class="form-control bg-white border shadow-none" value="{{ old('penghasilan_ibu') }}" style="font-size: 12px;" required placeholder="Ketik angka saja (misal: 2000000)" oninput="formatRupiahInput(this)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- D. FISIK & DOKUMEN --}}
                        <h6 class="fw-bold text-theme-primary border-bottom pb-2 mb-3 mt-4 text-uppercase" style="letter-spacing: 0.5px;">D. Fisik &amp; Dokumen Upload</h6>
                        <div class="row gx-3">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Tinggi Badan (cm) <span class="text-danger">*</span></label>
                                <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="tinggi_badan" class="form-control bg-white border shadow-none" value="{{ old('tinggi_badan') }}" style="font-size: 13px;" required placeholder="Contoh: 110">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Berat Badan (kg) <span class="text-danger">*</span></label>
                                <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="berat_badan" class="form-control bg-white border shadow-none" value="{{ old('berat_badan') }}" style="font-size: 13px;" required placeholder="Contoh: 18">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Lingkar Kepala (cm) <span class="text-danger">*</span></label>
                                <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="lingkar_kepala" class="form-control bg-white border shadow-none" value="{{ old('lingkar_kepala') }}" style="font-size: 13px;" required placeholder="Contoh: 48">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Pas Foto Anak (Wajib) <span class="text-danger">*</span></label>
                                <input type="file" name="foto_anak" accept="image/*" class="form-control bg-white border shadow-none" required>
                                <div class="form-text text-muted" style="font-size: 11px;">Format: JPG/PNG, Max: 2MB.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Scan/Foto KK (Wajib) <span class="text-danger">*</span></label>
                                <input type="file" name="foto_kk" accept="image/*,.pdf" class="form-control bg-white border shadow-none" required>
                                <div class="form-text text-muted" style="font-size: 11px;">Format: JPG/PNG/PDF, Max: 2MB.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Scan/Foto Akta Kelahiran (Wajib) <span class="text-danger">*</span></label>
                                <input type="file" name="foto_akte" accept="image/*,.pdf" class="form-control bg-white border shadow-none" required>
                                <div class="form-text text-muted" style="font-size: 11px;">Format: JPG/PNG/PDF, Max: 2MB.</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Bukti Pembayaran (Wajib) <span class="text-danger">*</span></label>
                                <input type="file" name="bukti_pembayaran" accept="image/*" class="form-control bg-white border shadow-none" required>
                                <div class="form-text text-muted" style="font-size: 11px;">Format: JPG/PNG, Max: 2MB. Upload struk transfer pendaftaran.</div>
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4 pt-3 border-top">
                            <button type="submit" id="btnSubmitForm" class="btn btn-theme-submit rounded-pill font-bold py-3 d-flex justify-content-center align-items-center gap-2" style="font-size: 15px; font-weight: 700;">
                                <iconify-icon icon="solar:send-square-bold-duotone" class="fs-5"></iconify-icon> Kirim Formulir Pendaftaran
                            </button>
                        </div>
                    </form>
                    
                    {{-- SCRIPT JAVASCRIPT: PETA LEAFLET, DYNAMIC KUOTA REALTIME, RUPIAH FORMATTER, FORM VALIDATION & SWEETALERT --}}
                    <script>
                        // Live Format Nominal Rupiah Otomatis saat mengetik angka
                        function formatRupiahInput(el) {
                            let value = el.value.replace(/[^0-9]/g, '');
                            if (!value) {
                                el.value = '';
                                return;
                            }
                            let formatted = new Intl.NumberFormat('id-ID').format(value);
                            el.value = 'Rp. ' + formatted;
                        }

                        document.addEventListener('DOMContentLoaded', function() {
                            // Format nilai awal penghasilan jika ada old value
                            const pAyah = document.getElementById('penghasilan_ayah');
                            const pIbu = document.getElementById('penghasilan_ibu');
                            if (pAyah && pAyah.value) formatRupiahInput(pAyah);
                            if (pIbu && pIbu.value) formatRupiahInput(pIbu);

                            {{-- SweetAlert2 Notifikasi Sukses Pendaftaran --}}
                            @if(session('success_pendaftaran'))
                                var pData = @json(session('success_pendaftaran'));
                                Swal.fire({
                                    title: '🎉 Pendaftaran Berhasil!',
                                    html: `
                                        <div style="text-align: left; font-size: 13.5px; line-height: 1.6; color: #374151;">
                                            <p style="margin-bottom: 10px;">Data pendaftaran siswa atas nama <strong style="color: {{ $theme['primary'] }};">${pData.nama_siswa}</strong> di <strong>${pData.nama_sekolah}</strong> (${pData.nama_yayasan}) telah berhasil disimpan ke database.</p>
                                            <p style="margin-bottom: 10px;">Akun portal wali murid dapat diakses menggunakan nomor telepon/email: <strong>${pData.email || pData.hp}</strong>.</p>
                                            <div style="background: #F3F4F6; border: 1px solid #E5E7EB; padding: 10px 14px; border-radius: 10px; font-size: 12px;">
                                                👉 Silakan masuk ke <strong>Portal Login Yayasan</strong> untuk mengecek status pendaftaran &amp; informasi sekolah.
                                            </div>
                                        </div>
                                    `,
                                    icon: 'success',
                                    confirmButtonText: '➡️ Masuk ke Portal Login Yayasan',
                                    confirmButtonColor: '{{ $theme['primary'] }}',
                                    allowOutsideClick: false,
                                    customClass: {
                                        popup: 'rounded-4'
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = pData.login_url;
                                    }
                                });
                            @endif

                            {{-- SweetAlert2 Notifikasi Error Validation --}}
                            @if(session('error') || $errors->any())
                                var errHtml = `{{ session('error') }}`;
                                @if($errors->any())
                                    errHtml += `<ul style="text-align: left; margin-top: 8px; font-size: 13px;">`;
                                    @foreach($errors->all() as $err)
                                        errHtml += `<li>{{ $err }}</li>`;
                                    @endforeach
                                    errHtml += `</ul>`;
                                @endif

                                Swal.fire({
                                    title: '⚠️ Formulir Belum Lengkap!',
                                    html: `<div style="font-size: 13.5px;">${errHtml}</div>`,
                                    icon: 'error',
                                    confirmButtonText: 'Perbaiki Isian Formulir',
                                    confirmButtonColor: '#EF4444',
                                    customClass: {
                                        popup: 'rounded-4'
                                    }
                                });
                            @endif

                            // 1. Script Peta Leaflet
                            var defaultLat = {{ old('lintang', '-7.983908') }};
                            var defaultLng = {{ old('bujur', '112.621391') }};
                            
                            var map = L.map('map').setView([defaultLat, defaultLng], 13); 

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '© OpenStreetMap contributors'
                            }).addTo(map);

                            var geocoder = L.Control.geocoder({
                                defaultMarkGeocode: false
                            })
                            .on('markgeocode', function(e) {
                                var bbox = e.geocode.bbox;
                                var poly = L.polygon([
                                    bbox.getSouthEast(),
                                    bbox.getNorthEast(),
                                    bbox.getNorthWest(),
                                    bbox.getSouthWest()
                                ]).addTo(map);
                                map.fitBounds(poly.getBounds());
                            })
                            .addTo(map);

                            var marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

                            function updateCoordinates(lat, lng) {
                                document.getElementById('lintang').value = typeof lat === 'number' ? lat.toFixed(6) : lat;
                                document.getElementById('bujur').value = typeof lng === 'number' ? lng.toFixed(6) : lng;
                            }

                            // Auto-set nilai awal koordinat
                            updateCoordinates(defaultLat, defaultLng);

                            map.on('click', function(e) {
                                marker.setLatLng(e.latlng);
                                updateCoordinates(e.latlng.lat, e.latlng.lng);
                            });

                            marker.on('dragend', function(e) {
                                var position = marker.getLatLng();
                                updateCoordinates(position.lat, position.lng);
                            });

                            // Tombol Deteksi Lokasi GPS
                            document.getElementById('btnGps').addEventListener('click', function() {
                                if (navigator.geolocation) {
                                    this.disabled = true;
                                    this.innerHTML = '<iconify-icon icon="solar:spinner-line-duotone" class="fs-6 spin"></iconify-icon> Mendeteksi...';
                                    navigator.geolocation.getCurrentPosition(function(position) {
                                        var lat = position.coords.latitude;
                                        var lng = position.coords.longitude;
                                        map.setView([lat, lng], 16);
                                        marker.setLatLng([lat, lng]);
                                        updateCoordinates(lat, lng);
                                        document.getElementById('btnGps').disabled = false;
                                        document.getElementById('btnGps').innerHTML = '<iconify-icon icon="solar:gps-bold-duotone" class="fs-6"></iconify-icon> Deteksi Lokasi Saya (GPS)';
                                    }, function(error) {
                                        alert('Gagal mendeteksi lokasi GPS: ' + error.message);
                                        document.getElementById('btnGps').disabled = false;
                                        document.getElementById('btnGps').innerHTML = '<iconify-icon icon="solar:gps-bold-duotone" class="fs-6"></iconify-icon> Deteksi Lokasi Saya (GPS)';
                                    }, { enableHighAccuracy: true });
                                } else {
                                    alert('Browser Anda tidak mendukung deteksi GPS.');
                                }
                            });

                            // 2. Data Kuota Real-time & Tahun Per Sekolah
                            const schoolsData = @json($schools_data ?? []);
                            const schoolSelect = document.getElementById('school_id_field');
                            const tahunSelect = document.getElementById('tahun_inden');
                            const programSelect = document.getElementById('program_pilihan');
                            const headerSchoolName = document.getElementById('header_school_name');
                            const tglLahirInput = document.getElementById('tgl_lahir');
                            const tglLahirFeedback = document.getElementById('tgl_lahir_feedback');
                            
                            const oldTahun = "{{ old('tahun_inden') }}";
                            const oldProgram = "{{ old('program_pilihan') }}";

                            function getSelectedSchoolId() {
                                if (!schoolSelect) return null;
                                return schoolSelect.value;
                            }

                            function updateTahunOptions() {
                                const schoolId = getSelectedSchoolId();
                                tahunSelect.innerHTML = '<option value="">-- Pilih Tahun Ajaran --</option>';
                                programSelect.innerHTML = '<option value="">-- Pilih Tahun Dahulu --</option>';
                                programSelect.disabled = true;

                                if (schoolId && schoolsData[schoolId]) {
                                    const schoolInfo = schoolsData[schoolId];
                                    if (headerSchoolName) {
                                        headerSchoolName.textContent = schoolInfo.nama_sekolah + ' {{ $activeYayasan ? '— ' . $activeYayasan->nama_yayasan : '' }}';
                                    }

                                    const years = schoolInfo.list_tahun || [];
                                    years.forEach(yr => {
                                        const opt = document.createElement('option');
                                        opt.value = yr;
                                        opt.textContent = yr;
                                        if (yr === oldTahun) {
                                            opt.selected = true;
                                        }
                                        tahunSelect.appendChild(opt);
                                    });

                                    // Jika hanya ada 1 tahun atau oldTahun cocok, langsung trigger program update
                                    if (years.length === 1 && !oldTahun) {
                                        tahunSelect.value = years[0];
                                    }
                                    updateProgramOptions();
                                } else {
                                    updateBirthdateConstraints();
                                }
                            }

                            function updateProgramOptions() {
                                const schoolId = getSelectedSchoolId();
                                const tahun = tahunSelect.value;
                                programSelect.innerHTML = '<option value="">-- Pilih Program Kelas --</option>';

                                if (schoolId && tahun && schoolsData[schoolId] && schoolsData[schoolId].kuota[tahun]) {
                                    programSelect.disabled = false;
                                    const kuota = schoolsData[schoolId].kuota[tahun];

                                    const addOption = (val, text, sisa) => {
                                        const opt = document.createElement('option');
                                        opt.value = val;
                                        opt.text = `${text} - Sisa Kuota: ${sisa}`;
                                        if (sisa <= 0) {
                                            opt.disabled = true;
                                            opt.text += ' (Penuh)';
                                        }
                                        if (val === oldProgram) opt.selected = true;
                                        programSelect.appendChild(opt);
                                    };

                                    addOption('TK Pagi', 'Taman Kanak-Kanak (Pagi)', kuota.tk_pagi);
                                    addOption('TK Siang', 'Taman Kanak-Kanak (Siang)', kuota.tk_siang);
                                    addOption('PAUD', 'Kelompok Bermain / PAUD', kuota.paud);
                                } else {
                                    programSelect.disabled = true;
                                }

                                updateBirthdateConstraints();
                            }

                            function updateBirthdateConstraints(showValidationAlert = false) {
                                if (!tglLahirInput) return true;

                                const tahunVal = tahunSelect ? tahunSelect.value : '';
                                const programVal = programSelect ? programSelect.value : '';
                                const birthVal = tglLahirInput.value;

                                if (!tahunVal) {
                                    tglLahirInput.removeAttribute('min');
                                    tglLahirInput.removeAttribute('max');
                                    if (tglLahirFeedback) {
                                        tglLahirFeedback.innerHTML = '<span class="text-muted" style="font-size:11px;"><iconify-icon icon="solar:info-circle-linear"></iconify-icon> Silakan pilih <strong>Tahun Ajaran</strong> untuk menentukan batasan tanggal lahir anak.</span>';
                                    }
                                    return true;
                                }

                                // Ambil tahun masuk awal (contoh: "2026/2027" -> 2026)
                                const match = tahunVal.match(/^(\d{4})/);
                                const entryYear = match ? parseInt(match[1]) : new Date().getFullYear();

                                const isPaud = programVal.toUpperCase().includes('PAUD');
                                const isTk = programVal.toUpperCase().includes('TK');

                                let minYear, maxYear, minDate, maxDate, ageRuleText;

                                if (isPaud) {
                                    // PAUD: usia 3 - 4 tahun saat masuk per tahun ajaran
                                    minYear = entryYear - 4;
                                    maxYear = entryYear - 3;
                                    minDate = `${minYear}-01-01`;
                                    maxDate = `${maxYear}-12-31`;
                                    ageRuleText = `Syarat Usia PAUD (TA ${tahunVal}): <strong>3 – 4 tahun</strong> (Kelahiran tahun <strong>${minYear} s/d ${maxYear}</strong>)`;
                                } else if (isTk) {
                                    // TK: usia 4 - 6 tahun saat masuk per tahun ajaran
                                    minYear = entryYear - 7;
                                    maxYear = entryYear - 4;
                                    minDate = `${minYear}-01-01`;
                                    maxDate = `${maxYear}-12-31`;
                                    ageRuleText = `Syarat Usia TK (TA ${tahunVal}): <strong>4 – 6 tahun</strong> (Kelahiran tahun <strong>${minYear} s/d ${maxYear}</strong>)`;
                                } else {
                                    // Belum pilih program
                                    minYear = entryYear - 7;
                                    maxYear = entryYear - 3;
                                    minDate = `${minYear}-01-01`;
                                    maxDate = `${maxYear}-12-31`;
                                    ageRuleText = `Batas Usia Masuk (TA ${tahunVal}): <strong>3 – 6 tahun</strong> (Kelahiran tahun <strong>${minYear} s/d ${maxYear}</strong>)`;
                                }

                                // Set pembatasan HTML5 date input
                                tglLahirInput.setAttribute('min', minDate);
                                tglLahirInput.setAttribute('max', maxDate);

                                // Jika belum diisi tanggal lahir
                                if (!birthVal) {
                                    if (tglLahirFeedback) {
                                        tglLahirFeedback.innerHTML = `<span class="text-primary d-inline-flex align-items-center gap-1" style="font-size:11.5px;"><iconify-icon icon="solar:calendar-date-bold"></iconify-icon> ${ageRuleText}</span>`;
                                    }
                                    tglLahirInput.classList.remove('is-invalid-custom', 'is-valid');
                                    return true;
                                }

                                // Validasi nilai tanggal lahir yang diisi
                                const birthDate = new Date(birthVal);
                                const birthYear = birthDate.getFullYear();

                                if (isNaN(birthYear)) {
                                    return true;
                                }

                                // Hitung usia per 1 Juli tahun masuk
                                const cutoffDate = new Date(entryYear, 6, 1);
                                let ageYears = entryYear - birthYear;
                                let ageMonths = 6 - birthDate.getMonth();
                                if (cutoffDate.getDate() < birthDate.getDate()) {
                                    ageMonths--;
                                }
                                if (ageMonths < 0) {
                                    ageYears--;
                                    ageMonths += 12;
                                }

                                let isValid = true;
                                let errorMsg = '';

                                if (birthYear < minYear) {
                                    isValid = false;
                                    if (isPaud) {
                                        errorMsg = `Usia anak <strong>${ageYears} thn ${ageMonths} bln</strong> pada Juli ${entryYear}. Melebihi batas usia PAUD (maksimal 4 tahun). Silakan pilih program <strong>TK</strong>.`;
                                    } else {
                                        errorMsg = `Usia anak <strong>${ageYears} thn ${ageMonths} bln</strong> pada Juli ${entryYear}. Melebihi batas maksimal usia TK (maksimal 6–7 tahun).`;
                                    }
                                } else if (birthYear > maxYear) {
                                    isValid = false;
                                    if (isTk) {
                                        errorMsg = `Usia anak <strong>${ageYears} thn ${ageMonths} bln</strong> pada Juli ${entryYear}. Belum memenuhi usia minimal TK (minimal 4 tahun). Silakan pilih <strong>PAUD</strong> jika berusia 3 tahun, atau daftar inden untuk tahun berikutnya.`;
                                    } else {
                                        errorMsg = `Usia anak <strong>${ageYears} thn ${ageMonths} bln</strong> pada Juli ${entryYear}. Belum memenuhi usia minimal PAUD (minimal 3 tahun). Silakan daftar inden untuk tahun berikutnya.`;
                                    }
                                }

                                if (!isValid) {
                                    tglLahirInput.classList.add('is-invalid-custom');
                                    tglLahirInput.classList.remove('is-valid');
                                    if (tglLahirFeedback) {
                                        tglLahirFeedback.innerHTML = `
                                            <div class="alert alert-danger py-1.5 px-2 mb-0 rounded-2 small d-flex align-items-start gap-1.5 mt-1" style="font-size: 11.5px; line-height: 1.35;">
                                                <iconify-icon icon="solar:danger-triangle-bold" class="fs-6 flex-shrink-0 text-danger mt-0.5"></iconify-icon>
                                                <div>${errorMsg}</div>
                                            </div>
                                        `;
                                    }
                                    if (showValidationAlert) {
                                        Swal.fire({
                                            title: 'Usia Tidak Memenuhi Syarat',
                                            html: `${errorMsg}<br><br><div class="p-2 rounded bg-light text-dark small text-start border">${ageRuleText}</div>`,
                                            icon: 'warning',
                                            confirmButtonText: 'Perbaiki Tanggal / Program',
                                            confirmButtonColor: '#EF4444',
                                            customClass: { popup: 'rounded-4' }
                                        });
                                    }
                                    return false;
                                } else {
                                    tglLahirInput.classList.remove('is-invalid-custom');
                                    tglLahirInput.classList.add('is-valid');
                                    const progLabel = isPaud ? 'PAUD' : (isTk ? 'TK' : 'Sekolah');
                                    if (tglLahirFeedback) {
                                        tglLahirFeedback.innerHTML = `
                                            <div class="alert alert-success py-1.5 px-2 mb-0 rounded-2 small d-flex align-items-center gap-1.5 mt-1" style="font-size: 11.5px;">
                                                <iconify-icon icon="solar:check-circle-bold" class="fs-6 flex-shrink-0 text-success"></iconify-icon>
                                                <div>Usia anak: <strong>${ageYears} tahun ${ageMonths} bulan</strong> pada Juli ${entryYear} (Sesuai syarat ${progLabel}).</div>
                                            </div>
                                        `;
                                    }
                                    return true;
                                }
                            }

                            if (schoolSelect && schoolSelect.tagName === 'SELECT') {
                                schoolSelect.addEventListener('change', updateTahunOptions);
                            }
                            if (tahunSelect) {
                                tahunSelect.addEventListener('change', updateProgramOptions);
                            }
                            if (programSelect) {
                                programSelect.addEventListener('change', () => updateBirthdateConstraints());
                            }
                            if (tglLahirInput) {
                                tglLahirInput.addEventListener('input', () => updateBirthdateConstraints());
                                tglLahirInput.addEventListener('change', () => updateBirthdateConstraints());
                            }

                            // Inisialisasi awal saat form pertama kali dimuat
                            updateTahunOptions();
                            updateBirthdateConstraints();

                            // 3. Real-time Account Check for Parent Account (by Phone or Email)
                            const emailInput = document.getElementById('parent_email');
                            const phoneInput = document.getElementById('parent_phone');
                            const feedbackBox = document.getElementById('email_feedback_box');
                            const passLabel = document.getElementById('password_label');
                            const passHint = document.getElementById('password_hint');
                            let accountCheckTimeout = null;

                            function checkParentAccount() {
                                const emailVal = emailInput ? emailInput.value.trim() : '';
                                const phoneVal = phoneInput ? phoneInput.value.trim() : '';

                                const hasValidEmail = emailVal.includes('@') && emailVal.length >= 5;
                                const hasValidPhone = phoneVal.replace(/[^0-9]/g, '').length >= 9;

                                if (!hasValidEmail && !hasValidPhone) {
                                    if (feedbackBox) feedbackBox.style.display = 'none';
                                    return;
                                }

                                if (feedbackBox) {
                                    feedbackBox.style.display = 'block';
                                    feedbackBox.innerHTML = '<span class="text-muted small d-inline-flex align-items-center gap-1"><iconify-icon icon="solar:spinner-line-duotone" class="spin"></iconify-icon> Memeriksa status akun orang tua...</span>';
                                }

                                fetch('{{ route('api.check_parent_email') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ email: emailVal, phone: phoneVal })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (!feedbackBox) return;
                                    if (!data.valid) {
                                        feedbackBox.style.display = 'none';
                                        return;
                                    }

                                    if (data.exists) {
                                        feedbackBox.innerHTML = `
                                            <div class="alert alert-success py-2 px-3 mb-0 rounded-3 small d-flex align-items-start gap-2">
                                                <iconify-icon icon="solar:check-circle-bold" class="fs-5 flex-shrink-0 text-success mt-0.5"></iconify-icon>
                                                <div>
                                                    <strong>Akun Terdaftar:</strong> ${data.name} ${data.total_anak > 0 ? `(${data.total_anak} anak sebelumnya)` : ''}.<br>
                                                    <span class="text-muted" style="font-size:11px;">Masukkan kata sandi akun Anda di bawah untuk konfirmasi penautan anak ini.</span>
                                                </div>
                                            </div>
                                        `;
                                        if (passLabel) passLabel.innerHTML = 'Kata Sandi Akun Anda (Konfirmasi Penautan Anak) <span class="text-danger">*</span>';
                                        if (passHint) passHint.innerHTML = 'Masukkan kata sandi akun Anda untuk mengonfirmasi penautan data anak ke akun terdaftar Anda.';
                                    } else {
                                        feedbackBox.innerHTML = `
                                            <div class="alert alert-info py-2 px-3 mb-0 rounded-3 small d-flex align-items-start gap-2">
                                                <iconify-icon icon="solar:info-circle-bold" class="fs-5 flex-shrink-0 text-primary mt-0.5"></iconify-icon>
                                                <div>
                                                    <strong>Akun Baru:</strong> Akun portal wali murid akan otomatis dibuatkan menggunakan nomor telepon / email &amp; kata sandi yang Anda tentukan di bawah.
                                                </div>
                                            </div>
                                        `;
                                        if (passLabel) passLabel.innerHTML = 'Buat Kata Sandi Akun Baru <span class="text-danger">*</span>';
                                        if (passHint) passHint.innerHTML = 'Kata sandi ini akan menjadi kata sandi akun portal wali murid baru Anda (Min. 6 karakter).';
                                    }
                                })
                                .catch(() => {
                                    if (feedbackBox) feedbackBox.style.display = 'none';
                                });
                            }

                            if (emailInput) {
                                emailInput.addEventListener('input', function() {
                                    clearTimeout(accountCheckTimeout);
                                    accountCheckTimeout = setTimeout(checkParentAccount, 400);
                                });
                            }
                            if (phoneInput) {
                                phoneInput.addEventListener('input', function() {
                                    clearTimeout(accountCheckTimeout);
                                    accountCheckTimeout = setTimeout(checkParentAccount, 400);
                                });
                            }

                            // Check on initial load if old email or phone is present
                            if ((emailInput && emailInput.value.trim()) || (phoneInput && phoneInput.value.trim())) {
                                checkParentAccount();
                            }

                            // 4. Toggle Show/Hide Password
                            const btnTogglePass = document.getElementById('btnTogglePassword');
                            const passInput = document.getElementById('parent_password');
                            const eyeIcon = document.getElementById('eyeIcon');
                            if (btnTogglePass && passInput) {
                                btnTogglePass.addEventListener('click', function() {
                                    if (passInput.type === 'password') {
                                        passInput.type = 'text';
                                        eyeIcon.setAttribute('icon', 'solar:eye-closed-bold');
                                    } else {
                                        passInput.type = 'password';
                                        eyeIcon.setAttribute('icon', 'solar:eye-bold');
                                    }
                                });
                            }

                            // 5. Form Client Validation Visual Feedback
                            const formPendaftaran = document.getElementById('formPendaftaran');
                            if (formPendaftaran) {
                                formPendaftaran.addEventListener('submit', function(event) {
                                    if (!formPendaftaran.checkValidity()) {
                                        event.preventDefault();
                                        event.stopPropagation();
                                        formPendaftaran.classList.add('was-validated');

                                        // Focus & scroll to first invalid field
                                        const firstInvalid = formPendaftaran.querySelector(':invalid');
                                        if (firstInvalid) {
                                            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                            firstInvalid.focus();
                                        }

                                        Swal.fire({
                                            title: '⚠️ Formulir Belum Lengkap',
                                            text: 'Mohon periksa dan isi seluruh kolom yang wajib diisi (*). Fields yang belum diisi telah ditandai dengan warna merah.',
                                            icon: 'warning',
                                            confirmButtonText: 'Lengkapi Sekarang',
                                            confirmButtonColor: '#EF4444',
                                            customClass: {
                                                popup: 'rounded-4'
                                            }
                                        });
                                        return false;
                                    }

                                    // Validasi kesesuaian batasan usia anak
                                    if (!updateBirthdateConstraints(true)) {
                                        event.preventDefault();
                                        event.stopPropagation();
                                        if (tglLahirInput) {
                                            tglLahirInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                            tglLahirInput.focus();
                                        }
                                        return false;
                                    }
                                }, false);
                            }
                        });

                        // ══════════════════════════════════════════════════════
                        //  CUSTOM BEAUTIFUL DROPDOWN ENGINE
                        //  - Sembunyikan native select, tampilkan UI cantik
                        //  - Sync nilai kembali ke native select untuk submit form
                        //  - Support: disabled, invalid, dynamic options (Ajax)
                        // ══════════════════════════════════════════════════════

                        const CARET_SVG = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>`;

                        function buildCustomSelect(nativeSel) {
                            if (nativeSel.dataset.csBuilt) return; // jangan dobel-build
                            nativeSel.dataset.csBuilt = '1';

                            const wrapper = document.createElement('div');
                            wrapper.className = 'cs-wrapper';

                            // Trigger button
                            const trigger = document.createElement('button');
                            trigger.type = 'button';
                            trigger.className = 'cs-trigger' + (nativeSel.disabled ? ' cs-disabled' : '');
                            trigger.setAttribute('aria-haspopup', 'listbox');
                            trigger.setAttribute('aria-expanded', 'false');

                            const labelSpan = document.createElement('span');
                            labelSpan.className = 'cs-label flex-grow-1 text-truncate';

                            const caret = document.createElement('span');
                            caret.className = 'cs-caret';
                            caret.innerHTML = CARET_SVG;

                            trigger.appendChild(labelSpan);
                            trigger.appendChild(caret);

                            // Dropdown panel
                            const panel = document.createElement('div');
                            panel.className = 'cs-dropdown';
                            panel.setAttribute('role', 'listbox');

                            wrapper.appendChild(trigger);
                            wrapper.appendChild(panel);

                            // Sisipkan wrapper setelah native select
                            nativeSel.parentNode.insertBefore(wrapper, nativeSel);
                            wrapper.insertBefore(nativeSel, wrapper.firstChild);

                            // Sembunyikan native select
                            nativeSel.classList.add('cs-hidden');

                            // Fungsi: render opsi di panel dari native select
                            function renderOptions() {
                                panel.innerHTML = '';
                                Array.from(nativeSel.options).forEach(opt => {
                                    const item = document.createElement('div');
                                    item.className = 'cs-option' + (opt.value === '' ? ' cs-placeholder' : '') + (opt.selected && opt.value !== '' ? ' cs-selected' : '');
                                    item.textContent = opt.text;
                                    item.dataset.value = opt.value;
                                    item.setAttribute('role', 'option');

                                    if (opt.value !== '') {
                                        item.addEventListener('click', () => {
                                            nativeSel.value = opt.value;
                                            nativeSel.dispatchEvent(new Event('change', { bubbles: true }));
                                            syncTrigger();
                                            closePanel();
                                        });
                                    }
                                    panel.appendChild(item);
                                });
                            }

                            // Fungsi: sinkronkan label trigger dari nilai native select
                            function syncTrigger() {
                                const sel = nativeSel;
                                const selectedOpt = sel.options[sel.selectedIndex];
                                if (!selectedOpt || selectedOpt.value === '') {
                                    // Ambil teks placeholder (opsi pertama)
                                    const phText = sel.options[0] ? sel.options[0].text : '-- Pilih --';
                                    labelSpan.textContent = phText;
                                    trigger.classList.remove('cs-has-value');
                                } else {
                                    labelSpan.textContent = selectedOpt.text;
                                    trigger.classList.add('cs-has-value');
                                }
                                // Update highlight di panel
                                panel.querySelectorAll('.cs-option').forEach(item => {
                                    if (item.dataset.value === sel.value) {
                                        item.classList.add('cs-selected');
                                    } else {
                                        item.classList.remove('cs-selected');
                                    }
                                });
                                // Disabled state
                                if (sel.disabled) {
                                    trigger.classList.add('cs-disabled');
                                    trigger.disabled = true;
                                } else {
                                    trigger.classList.remove('cs-disabled');
                                    trigger.disabled = false;
                                }
                            }

                            function openPanel() {
                                if (nativeSel.disabled) return;
                                renderOptions();
                                panel.classList.add('cs-visible');
                                trigger.classList.add('cs-open');
                                trigger.setAttribute('aria-expanded', 'true');
                            }
                            function closePanel() {
                                panel.classList.remove('cs-visible');
                                trigger.classList.remove('cs-open');
                                trigger.setAttribute('aria-expanded', 'false');
                            }

                            trigger.addEventListener('click', (e) => {
                                e.stopPropagation();
                                if (panel.classList.contains('cs-visible')) {
                                    closePanel();
                                } else {
                                    // Tutup semua dropdown lain
                                    document.querySelectorAll('.cs-dropdown.cs-visible').forEach(p => {
                                        p.classList.remove('cs-visible');
                                        p.previousElementSibling && p.previousElementSibling.classList && p.previousElementSibling.classList.remove('cs-open');
                                    });
                                    openPanel();
                                }
                            });

                            // Tutup jika klik di luar
                            document.addEventListener('click', () => closePanel());
                            panel.addEventListener('click', e => e.stopPropagation());

                            // Observe perubahan pada native select (untuk Ajax populate)
                            const observer = new MutationObserver(() => {
                                syncTrigger();
                                if (panel.classList.contains('cs-visible')) renderOptions();
                            });
                            observer.observe(nativeSel, { childList: true, attributes: true, attributeFilter: ['disabled', 'value'] });

                            // Juga listen ke 'change' dari kode lain (Ajax update)
                            nativeSel.addEventListener('change', () => syncTrigger());

                            // Validasi: tambah cs-invalid bila was-validated & kosong
                            const form = nativeSel.closest('form');
                            if (form) {
                                form.addEventListener('submit', () => {
                                    if (nativeSel.required && nativeSel.value === '') {
                                        trigger.classList.add('cs-invalid');
                                    } else {
                                        trigger.classList.remove('cs-invalid');
                                    }
                                }, true);
                            }
                            nativeSel.addEventListener('change', () => {
                                if (nativeSel.value !== '') trigger.classList.remove('cs-invalid');
                            });

                            // Inisiasi awal
                            syncTrigger();
                        }

                        // Bangun custom select untuk semua form-select yang relevan
                        document.querySelectorAll('select.form-select').forEach(buildCustomSelect);

                        // Re-build jika select baru ditambahkan secara dinamis
                        // (misal: tahun_inden & program_pilihan diisi via Ajax)
                        // Untuk itu kita observe parent mereka
                        ['tahun_inden', 'program_pilihan'].forEach(id => {
                            const sel = document.getElementById(id);
                            if (sel) {
                                // Setelah Ajax memperbarui opsi, trigger syncTrigger via MutationObserver
                                // sudah ditangani di buildCustomSelect — tidak perlu kode tambahan.
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection