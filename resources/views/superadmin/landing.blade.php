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

    {{-- HERO CARD --}}
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
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Edit Landing Page {{ $yayasan->nama_yayasan }}</h5>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Kelola informasi publik, profil sekolah, program &amp; teks promosi khusus yayasan Anda</span>
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('yayasan.landing', $yayasan->kode_yayasan) }}" target="_blank" class="btn btn-light text-primary font-semibold rounded-3 btn-sm px-3 py-2 d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12.5px;">
                        <iconify-icon icon="solar:eye-bold-duotone" class="fs-5"></iconify-icon> Lihat Halaman Publik
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('superadmin.landing.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @php
            $currentTemplate = $content['template'] ?? 'ocean';
        @endphp

        {{-- SECTION TEMPLATE CHOOSER --}}
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:palette-bold-duotone" class="text-primary fs-5"></iconify-icon> Pilih Template Desain Landing Page
                </h6>
                <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Pilih tema visual yang akan digunakan pada landing page yayasan Anda.</span>
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
                                    <span class="color-swatch" style="background: #378ADD;"></span>
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
                                    <span class="color-swatch" style="background: #185FA5;"></span>
                                    <span class="color-swatch" style="background: #f8fafc;"></span>
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

        {{-- SECTION 0: IDENTITAS & BRANDING YAYASAN --}}
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #2563eb !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:globus-bold-duotone" class="text-primary fs-5"></iconify-icon> Identitas &amp; Branding Yayasan
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
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center">
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
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #f59e0b !important; border-radius: 16px !important;">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                    <iconify-icon icon="solar:star-bold-duotone" class="text-warning fs-5"></iconify-icon> Bagian Hero Promosi (Atas)
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
                            <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3"><iconify-icon icon="solar:phone-calling-bold-duotone" class="fs-5"></iconify-icon></span>
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

        {{-- SECTION 2: PROFIL & PROGRAM (3 COLUMN GRID) --}}
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
                        <div class="p-3.5 rounded-3 border bg-slate-50 h-100" style="border-radius: 14px !important; border-top: 3px solid #2563eb !important;">
                            <h6 class="fw-bold mb-3 text-primary d-flex align-items-center gap-2" style="font-size: 14px;">
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
                        <div class="p-3.5 rounded-3 border bg-slate-50 h-100" style="border-radius: 14px !important; border-top: 3px solid #10b981 !important;">
                            <h6 class="fw-bold mb-3 text-success d-flex align-items-center gap-2" style="font-size: 14px;">
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
                        <div class="p-3.5 rounded-3 border bg-slate-50 h-100" style="border-radius: 14px !important; border-top: 3px solid #f59e0b !important;">
                            <h6 class="fw-bold mb-3 text-warning d-flex align-items-center gap-2" style="font-size: 14px;">
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

        {{-- STICKY SAVE BAR --}}
        <div class="sticky-save-bar p-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <iconify-icon icon="solar:info-circle-bold-duotone" class="text-primary fs-4"></iconify-icon>
                <span class="small font-medium text-dark">Seluruh perubahan profil &amp; template landing page yayasan akan langsung diterapkan secara publik.</span>
            </div>
            <button type="submit" class="btn btn-primary rounded-3 px-4 font-semibold d-flex align-items-center gap-2 shadow-sm">
                <iconify-icon icon="solar:diskette-bold-duotone" class="fs-5"></iconify-icon> Simpan Perubahan
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    function selectTemplate(tmpl) {
        document.querySelectorAll('.template-card-option').forEach(el => el.classList.remove('active'));
        const activeRadio = document.querySelector(`input[name="template"][value="${tmpl}"]`);
        if (activeRadio) {
            activeRadio.checked = true;
            activeRadio.closest('.template-card-option').classList.add('active');
        }

        // Hide all extra fields
        document.querySelectorAll('.field-extra').forEach(el => el.style.display = 'none');
        // Show fields for selected template
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
