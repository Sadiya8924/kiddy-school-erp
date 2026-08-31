@extends('layouts.admin')

@section('konten_halaman')
@php 
    $smtAktif = \App\Models\Semester::where('is_active', true)->first(); 
    $templateAktif = \App\Models\RaporTemplate::where('is_active', true)->with('items.anak_isian')->first();
@endphp

<div class="d-flex flex-column gap-4 mt-0 pb-5">

    {{-- ========================================================== --}}
    {{-- 1. HERO HEADER CARD WITH MODERN ENTERPRISE STYLE --}}
    {{-- ========================================================== --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 55%, #2563eb 100%); border-radius: 18px !important;">
        <div class="card-body p-4 p-md-4 text-white">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative flex-shrink-0">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 54px; height: 54px; font-size: 26px; border: 2px solid rgba(255,255,255,0.4);">
                            <iconify-icon icon="solar:pen-new-square-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 13px; height: 13px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h4 class="fw-bold mb-0 text-white" style="font-size: 20px; letter-spacing: -0.02em;">Formulir Pengisian E-Rapor</h4>
                            @if($templateAktif)
                                <span class="badge rounded-pill px-3 py-1 font-semibold" style="font-size: 11px; background: rgba(255, 255, 255, 0.18); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3); backdrop-filter: blur(4px);">
                                    ✨ {{ $templateAktif->nama_template }}
                                </span>
                            @endif
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 13px;">
                            <span>Kelola penilaian autentik, narasi capaian belajar, muatan lokal &amp; dokumentasi kegiatan peserta didik.</span>
                        </p>
                    </div>
                </div>

                {{-- Semester Badge --}}
                @if($smtAktif)
                <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                    <div class="badge bg-warning text-dark font-semibold px-3.5 py-2 rounded-pill shadow-sm d-flex align-items-center gap-2" style="font-size: 12.5px;">
                        <iconify-icon icon="solar:calendar-bold-duotone" class="fs-5"></iconify-icon>
                        <span>Semester: <strong>{{ $smtAktif->tahun_ajaran }} ({{ $smtAktif->jenis }})</strong></span>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    @include('partials.owner_stepped_filter')

    @if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-body p-4 p-md-5"> 
        
            {{-- ALERT: RAPOR DITUTUP ADMIN --}}
            @if(auth()->user()->school && !auth()->user()->school->is_rapor_open)
                <div class="alert border-0 rounded-4 p-3.5 mb-4 d-flex align-items-center gap-3 shadow-xs" style="background: #fef2f2; border: 1px solid #fecaca !important;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; font-size: 20px; background: #dc2626; color: #ffffff;">
                        <iconify-icon icon="solar:lock-keyhole-bold-duotone"></iconify-icon>
                    </div>
                    <div>
                        <strong class="d-block mb-0.5" style="font-size: 13.5px; color: #991b1b;">Akses Pengisian Rapor Sedang Ditutup</strong>
                        <span class="small" style="color: #7f1d1d; font-weight: 500;">Perubahan nilai saat ini dikunci oleh Admin Sekolah. Anda hanya dapat melihat data yang telah tersimpan.</span>
                    </div>
                </div>
            @endif

            {{-- ALERT: TEMPLATE BELUM DIAKTIFKAN --}}
            @if(!$templateAktif)
                <div class="alert border-0 rounded-4 p-3.5 mb-4 d-flex align-items-center gap-3 shadow-xs" style="background: #fffbeb; border: 1px solid #fde68a !important;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; font-size: 20px; background: #d97706; color: #ffffff;">
                        <iconify-icon icon="solar:shield-warning-bold-duotone"></iconify-icon>
                    </div>
                    <div>
                        <strong class="d-block mb-0.5" style="font-size: 13.5px; color: #92400e;">Template Rapor Belum Aktif</strong>
                        <span class="small" style="color: #78350f; font-weight: 500;">Admin Sekolah belum mengaktifkan template rapor. Silakan aktifkan template pada menu <strong>Template Rapor</strong>.</span>
                    </div>
                </div>
            @endif

            {{-- DRAFT / PERMANENT STATUS ALERT --}}
            <div id="alert-status-rapor" class="d-none alert border-0 rounded-4 p-3.5 mb-4 align-items-center gap-3 shadow-xs">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" id="alert-status-icon-wrap" style="width: 40px; height: 40px; font-size: 20px;">
                    <iconify-icon id="alert-status-icon" icon="solar:info-circle-bold-duotone"></iconify-icon>
                </div>
                <div id="teks-status-rapor" style="font-size: 13px;"></div>
            </div>

            <form method="POST" action="/isi-rapor" id="formRapor" enctype="multipart/form-data">
                @csrf

                {{-- ========================================================== --}}
                {{-- 2. ACTIVE STUDENT HERO STRIP (LOCKED STATE) --}}
                {{-- ========================================================== --}}
                <div id="row-locked-siswa" class="d-none rounded-4 p-3.5 p-md-4 mb-4 flex-column flex-md-row justify-content-between align-items-md-center gap-3 border shadow-xs" style="background: linear-gradient(135deg, #f0f7ff 0%, #e0effe 100%); border-color: #bae6fd !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="position-relative flex-shrink-0">
                            <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-xs" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid #3b82f6;">
                                <iconify-icon icon="solar:user-bold"></iconify-icon>
                            </div>
                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <span class="badge bg-primary text-white rounded-pill px-2.5 py-0.5 font-bold" style="font-size: 10.5px; letter-spacing: 0.5px;">SEDANG DINILAI</span>
                                <h5 class="fw-bold text-dark mb-0" id="locked_nama" style="font-size: 17px;">-</h5>
                            </div>
                            <div class="d-flex flex-wrap gap-2 text-muted small mt-1" style="font-size: 12px;">
                                <span class="badge bg-white text-dark border px-2.5 py-1 rounded-pill shadow-xs">
                                    <iconify-icon icon="solar:backpack-bold-duotone" class="text-primary align-middle me-1"></iconify-icon>
                                    Kelas: <strong id="locked_kelas" class="text-primary">-</strong>
                                </span>
                                <span class="badge bg-white text-dark border px-2.5 py-1 rounded-pill shadow-xs">
                                    <iconify-icon icon="solar:user-id-bold-duotone" class="text-primary align-middle me-1"></iconify-icon>
                                    No. Induk / NISN: <strong id="locked_identitas" class="text-dark">-</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-white bg-white text-primary border border-primary-subtle rounded-pill px-4 py-2 fw-bold shadow-xs d-flex align-items-center gap-2 flex-shrink-0 hover-lift" onclick="confirmGantiSiswa()" style="font-size: 13px;">
                        <iconify-icon icon="solar:refresh-circle-bold-duotone" class="fs-5"></iconify-icon>
                        <span>Ganti Siswa</span>
                    </button>
                </div>

                {{-- ========================================================== --}}
                {{-- 3. FORM PILIH SISWA (INITIAL STATE) --}}
                {{-- ========================================================== --}}
                <div id="row-pilih-siswa" class="card border rounded-4 mb-4 shadow-xs overflow-hidden" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                    <div class="card-body p-3.5 p-md-4">
                        <div class="row align-items-end g-3">
                            @if(auth()->user()->role == 'admin')
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark small mb-1.5 d-flex align-items-center gap-1.5" style="font-size: 12.5px;">
                                    <iconify-icon icon="solar:filter-bold-duotone" class="text-primary fs-5"></iconify-icon>
                                    <span>Filter Rombel (Admin)</span>
                                </label>
                                <select id="filter_kelas" class="form-select bg-white border text-dark shadow-none py-2 rounded-3" onchange="window.location.href='?kelas_id='+this.value" style="font-size: 13px;">
                                    <option value="all">-- Semua Rombongan Belajar --</option>
                                    @foreach($list_kelas as $k)
                                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="col-md-{{ auth()->user()->role == 'admin' ? '8' : '12' }}">
                                <label class="form-label fw-bold text-dark small mb-1.5 d-flex align-items-center gap-1.5" style="font-size: 12.5px;">
                                    <iconify-icon icon="solar:user-check-bold-duotone" class="text-primary fs-5"></iconify-icon>
                                    <span>Pilih Peserta Didik yang Akan Dinilai <span class="text-danger">*</span></span>
                                </label>
                                <select name="siswa_id" class="form-select bg-white border-2 border-primary text-dark shadow-none py-2.5 fw-bold rounded-3" id="pilih_siswa" required style="font-size: 13.5px;">
                                    <option disabled selected value="">-- Klik untuk Memilih Data Siswa --</option>
                                    @foreach($siswas as $siswa)
                                        @php
                                            $statusTxt = 'Belum Diisi';
                                            if($siswa->rapor_aktif) {
                                                $statusTxt = $siswa->rapor_aktif->status == 'permanen' ? 'Sudah Permanen' : 'Ada Draft';
                                            }
                                            $namaKelas = $siswa->kelas->first()->nama_kelas ?? '-';
                                            $noInduk = $siswa->no_induk ?? '-';
                                            $nisn = $siswa->nisn ?? '-';
                                        @endphp
                                        <option value="{{ $siswa->id }}" data-kelas="{{ $namaKelas }}" data-noinduk="{{ $noInduk }}" data-nisn="{{ $nisn }}">
                                            {{ $siswa->nama_lengkap }} — ({{ $namaKelas }}) [Status: {{ $statusTxt }}]
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========================================================== --}}
                {{-- 4. ULTRA-CLEAN STEPPER PROGRESS BAR --}}
                {{-- ========================================================== --}}
                <div class="rapor-stepper-wrapper mb-4">
                    <ul class="nav nav-pills nav-fill rapor-stepper-list gap-2 p-1.5 rounded-4 border bg-light shadow-xs" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-3 py-2.5 px-3 fw-bold d-flex align-items-center justify-content-center gap-2.5 stepper-node" id="pills-biodata-tab" data-bs-toggle="pill" data-bs-target="#pills-biodata" type="button" role="tab">
                                <span class="step-badge">1</span>
                                <span class="step-text-wrap text-start">
                                    <span class="d-block step-heading">Cek Biodata</span>
                                    <span class="d-none d-lg-block step-caption text-truncate">Identitas &amp; Kehadiran</span>
                                </span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-3 py-2.5 px-3 fw-bold d-flex align-items-center justify-content-center gap-2.5 stepper-node" id="pills-rapor-tab" data-bs-toggle="pill" data-bs-target="#pills-rapor" type="button" role="tab">
                                <span class="step-badge">2</span>
                                <span class="step-text-wrap text-start">
                                    <span class="d-block step-heading">Isi Narasi</span>
                                    <span class="d-none d-lg-block step-caption text-truncate">Capaian Belajar</span>
                                </span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-3 py-2.5 px-3 fw-bold d-flex align-items-center justify-content-center gap-2.5 stepper-node" id="pills-mulok-tab" data-bs-toggle="pill" data-bs-target="#pills-mulok" type="button" role="tab">
                                <span class="step-badge">3</span>
                                <span class="step-text-wrap text-start">
                                    <span class="d-block step-heading">Muatan Lokal</span>
                                    <span class="d-none d-lg-block step-caption text-truncate">Skala Penilaian</span>
                                </span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-3 py-2.5 px-3 fw-bold d-flex align-items-center justify-content-center gap-2.5 stepper-node" id="pills-foto-tab" data-bs-toggle="pill" data-bs-target="#pills-foto" type="button" role="tab">
                                <span class="step-badge">4</span>
                                <span class="step-text-wrap text-start">
                                    <span class="d-block step-heading">Foto Kegiatan</span>
                                    <span class="d-none d-lg-block step-caption text-truncate">Lampiran Dokumentasi</span>
                                </span>
                            </button>
                        </li>
                    </ul>
                </div>

                {{-- ========================================================== --}}
                {{-- 5. TAB CONTENT PANELS --}}
                {{-- ========================================================== --}}
                <div class="tab-content" id="pills-tabContent">
                    
                    {{-- ── TAB 1: BIODATA & REKAP KEHADIRAN ── --}}
                    <div class="tab-pane fade show active" id="pills-biodata" role="tabpanel">
                        
                        {{-- D. REKAP DATA OTOMATIS METRIC CHIPS (TOP HIGHLIGHT) --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 14px;">
                                    <iconify-icon icon="solar:chart-square-bold-duotone" class="text-primary fs-5"></iconify-icon>
                                    <span>Rekapitulasi Kehadiran &amp; Status Fisik (Otomatis dari Sistem)</span>
                                </h6>
                                <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill" style="font-size: 11px;">Otomatis Sinkron</span>
                            </div>
                            
                            <div class="row g-2.5 g-md-3">
                                {{-- Sakit --}}
                                <div class="col-6 col-md-4 col-lg">
                                    <div class="metric-card border rounded-3 p-3 text-center bg-white shadow-xs">
                                        <div class="d-flex align-items-center justify-content-center gap-1.5 text-warning mb-1">
                                            <iconify-icon icon="solar:hospital-bold-duotone" class="fs-5"></iconify-icon>
                                            <span class="fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">SAKIT</span>
                                        </div>
                                        <input type="text" class="form-control-plaintext text-center fw-extrabold text-dark fs-3 p-0" id="db_sakit" value="-" readonly>
                                        <span class="text-muted small" style="font-size: 11px;">Hari</span>
                                    </div>
                                </div>

                                {{-- Izin --}}
                                <div class="col-6 col-md-4 col-lg">
                                    <div class="metric-card border rounded-3 p-3 text-center bg-white shadow-xs">
                                        <div class="d-flex align-items-center justify-content-center gap-1.5 text-info mb-1">
                                            <iconify-icon icon="solar:calendar-date-bold-duotone" class="fs-5"></iconify-icon>
                                            <span class="fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">IZIN</span>
                                        </div>
                                        <input type="text" class="form-control-plaintext text-center fw-extrabold text-dark fs-3 p-0" id="db_izin" value="-" readonly>
                                        <span class="text-muted small" style="font-size: 11px;">Hari</span>
                                    </div>
                                </div>

                                {{-- Alfa --}}
                                <div class="col-6 col-md-4 col-lg">
                                    <div class="metric-card border rounded-3 p-3 text-center bg-white shadow-xs">
                                        <div class="d-flex align-items-center justify-content-center gap-1.5 text-danger mb-1">
                                            <iconify-icon icon="solar:close-circle-bold-duotone" class="fs-5"></iconify-icon>
                                            <span class="fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">ALFA</span>
                                        </div>
                                        <input type="text" class="form-control-plaintext text-center fw-extrabold text-danger fs-3 p-0" id="db_alfa" value="-" readonly>
                                        <span class="text-muted small" style="font-size: 11px;">Hari</span>
                                    </div>
                                </div>

                                {{-- Tinggi Badan --}}
                                <div class="col-6 col-md-6 col-lg">
                                    <div class="metric-card border rounded-3 p-3 text-center bg-white shadow-xs">
                                        <div class="d-flex align-items-center justify-content-center gap-1.5 text-success mb-1">
                                            <iconify-icon icon="solar:ruler-bold-duotone" class="fs-5"></iconify-icon>
                                            <span class="fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">TINGGI BADAN</span>
                                        </div>
                                        <div class="d-flex align-items-baseline justify-content-center gap-1">
                                            <input type="text" class="form-control-plaintext text-center fw-extrabold text-success fs-3 p-0 w-auto" id="tinggi_badan" value="-" readonly>
                                            <span class="text-success fw-bold small">cm</span>
                                        </div>
                                        <span class="text-muted small" style="font-size: 11px;">Data Medis</span>
                                    </div>
                                </div>

                                {{-- Berat Badan --}}
                                <div class="col-12 col-md-6 col-lg">
                                    <div class="metric-card border rounded-3 p-3 text-center bg-white shadow-xs">
                                        <div class="d-flex align-items-center justify-content-center gap-1.5 text-primary mb-1">
                                            <iconify-icon icon="solar:scale-bold-duotone" class="fs-5"></iconify-icon>
                                            <span class="fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">BERAT BADAN</span>
                                        </div>
                                        <div class="d-flex align-items-baseline justify-content-center gap-1">
                                            <input type="text" class="form-control-plaintext text-center fw-extrabold text-primary fs-3 p-0 w-auto" id="berat_badan" value="-" readonly>
                                            <span class="text-primary fw-bold small">kg</span>
                                        </div>
                                        <span class="text-muted small" style="font-size: 11px;">Data Medis</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            {{-- Form Fields --}}
                            <div class="col-lg-9">
                                
                                {{-- 1. IDENTITAS PESERTA DIDIK --}}
                                <div class="form-section-card p-4 rounded-4 border bg-white mb-4 shadow-xs">
                                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                        <iconify-icon icon="solar:user-id-bold-duotone" class="text-primary fs-5"></iconify-icon>
                                        <h6 class="fw-bold text-dark mb-0" style="font-size: 14px;">1. Identitas Lengkap Peserta Didik</h6>
                                    </div>
                                    
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label-custom">Nama Lengkap Anak</label>
                                            <input type="text" name="biodata[nama_lengkap]" class="form-control form-control-custom fw-bold" id="nama_lengkap" placeholder="Nama lengkap sesuai akta kelahiran">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label-custom">Nomor Induk Siswa</label>
                                            <input type="text" name="biodata[no_induk]" class="form-control form-control-custom" id="no_induk" placeholder="Contoh: IND-1-001">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label-custom">NISN (Nomor Induk Siswa Nasional)</label>
                                            <input type="text" name="biodata[nisn]" class="form-control form-control-custom" id="nisn" placeholder="10 Digit NISN">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label-custom">Tempat Lahir</label>
                                            <input type="text" name="biodata[tempat_lahir]" class="form-control form-control-custom" id="tempat_lahir" placeholder="Kota/Kabupaten">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label-custom">Tanggal Lahir</label>
                                            <input type="date" name="biodata[tgl_lahir]" class="form-control form-control-custom" id="tgl_lahir">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label-custom">Jenis Kelamin</label>
                                            <select name="biodata[jenis_kelamin]" id="jenis_kelamin" class="form-select form-control-custom">
                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                <option value="Laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label-custom">Agama</label>
                                            <select name="biodata[agama]" id="agama" class="form-select form-control-custom">
                                                <option value="">-- Pilih Agama --</option>
                                                <option value="Islam">Islam</option>
                                                <option value="Kristen">Kristen</option>
                                                <option value="Katolik">Katolik</option>
                                                <option value="Hindu">Hindu</option>
                                                <option value="Budha">Budha</option>
                                                <option value="Konghucu">Konghucu</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label-custom">Anak Ke-</label>
                                            <input type="number" name="biodata[anak_ke]" id="anak_ke" class="form-control form-control-custom" placeholder="1">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label-custom">Jumlah Saudara</label>
                                            <input type="number" name="biodata[jml_saudara]" id="jml_saudara" class="form-control form-control-custom" placeholder="2">
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. ALAMAT & TEMPAT TINGGAL --}}
                                <div class="form-section-card p-4 rounded-4 border bg-white mb-4 shadow-xs">
                                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                        <iconify-icon icon="solar:map-point-wave-bold-duotone" class="text-primary fs-5"></iconify-icon>
                                        <h6 class="fw-bold text-dark mb-0" style="font-size: 14px;">2. Alamat &amp; Kontak Tempat Tinggal</h6>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label-custom">Alamat Lengkap / Nama Jalan</label>
                                            <input type="text" name="biodata[alamat]" class="form-control form-control-custom" id="alamat" placeholder="Nama jalan, nomor rumah, RT/RW">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label-custom">RT</label>
                                            <input type="text" name="biodata[rt]" id="rt" class="form-control form-control-custom" placeholder="01">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label-custom">RW</label>
                                            <input type="text" name="biodata[rw]" id="rw" class="form-control form-control-custom" placeholder="02">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label-custom">Dusun / Lingkungan</label>
                                            <input type="text" name="biodata[dusun]" id="dusun" class="form-control form-control-custom" placeholder="Nama dusun">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label-custom">Kelurahan / Desa</label>
                                            <input type="text" name="biodata[kelurahan]" id="kelurahan" class="form-control form-control-custom" placeholder="Kelurahan">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label-custom">Kecamatan</label>
                                            <input type="text" name="biodata[kecamatan]" id="kecamatan" class="form-control form-control-custom" placeholder="Kecamatan">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label-custom">Kode Pos</label>
                                            <input type="number" name="biodata[kode_pos]" id="kode_pos" class="form-control form-control-custom" placeholder="65145">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label-custom">Nomor WhatsApp / HP Orang Tua</label>
                                            <input type="text" name="biodata[hp]" class="form-control form-control-custom" id="hp" placeholder="Contoh: 081234567890">
                                        </div>
                                    </div>
                                </div>

                                {{-- 3. DATA ORANG TUA / WALI --}}
                                <div class="form-section-card p-4 rounded-4 border bg-white shadow-xs">
                                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                        <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="text-primary fs-5"></iconify-icon>
                                        <h6 class="fw-bold text-dark mb-0" style="font-size: 14px;">3. Data Orang Tua / Wali Peserta Didik</h6>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Nama Lengkap Ayah</label>
                                            <input type="text" name="biodata[nama_ayah]" id="nama_ayah" class="form-control form-control-custom mb-2" placeholder="Nama ayah">
                                            <label class="form-label-custom">Pekerjaan Ayah</label>
                                            <input type="text" name="biodata[pekerjaan_ayah]" id="pekerjaan_ayah" class="form-control form-control-custom" placeholder="Pekerjaan ayah">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label-custom">Nama Lengkap Ibu</label>
                                            <input type="text" name="biodata[nama_ibu]" id="nama_ibu" class="form-control form-control-custom mb-2" placeholder="Nama ibu">
                                            <label class="form-label-custom">Pekerjaan Ibu</label>
                                            <input type="text" name="biodata[pekerjaan_ibu]" id="pekerjaan_ibu" class="form-control form-control-custom" placeholder="Pekerjaan ibu">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                            {{-- Right: Profile Card Preview --}}
                            <div class="col-lg-3">
                                <div class="card border rounded-4 p-4 text-center bg-light shadow-xs position-sticky" style="top: 85px;">
                                    <span class="small text-muted fw-bold text-uppercase d-block mb-3" style="font-size: 11px; letter-spacing: 0.5px;">Pas Foto Resmi Anak</span>
                                    <div class="border rounded-4 p-2 bg-white d-inline-block shadow-xs mb-3 mx-auto overflow-hidden" style="max-width: 170px;">
                                        <img id="foto_siswa" src="https://ui-avatars.com/api/?name=Pilih+Siswa&color=2563eb&background=eff6ff&size=180" 
                                            class="img-fluid rounded-3" style="width: 150px; height: 190px; object-fit: cover;" alt="Foto Siswa">
                                    </div>
                                    <div class="small text-muted text-center" style="font-size: 12px;">
                                        Foto profil otomatis terhubung dari pendaftaran &amp; Dapodik siswa.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- ── TAB 2: ISI NARASI CAPAIAN PEMBELAJARAN ── --}}
                    <div class="tab-pane fade" id="pills-rapor" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2" style="font-size: 17px;">
                                    <iconify-icon icon="solar:document-text-bold-duotone" class="text-primary fs-4"></iconify-icon>
                                    <span>Laporan Perkembangan &amp; Capaian Pembelajaran</span>
                                </h5>
                                <p class="text-muted small mb-0">Tuliskan narasi deskriptif autentik mengenai capaian kompetensi ananda. Kolom bertanda <span class="text-danger fw-bold">* Wajib</span> wajib diisi.</p>
                            </div>
                        </div>
                        
                        @if($templateAktif)
                            <div class="d-flex flex-column gap-3.5" id="containerNarasi">
                                @php $babIndex = 0; @endphp
                                @foreach($templateAktif->items->where('is_bab', true)->sortBy('urutan') as $bab)
                                    @php
                                        $hasNarasi = $bab->anak_isian->where('tipe', 'textarea')->count() > 0;
                                        $itemNarasi = $bab->anak_isian->whereIn('tipe', ['textarea', 'sub_judul'])->sortBy('urutan')->values();
                                        $babIndex++;
                                        $colorPalette = ['#2563eb', '#7c3aed', '#059669', '#d97706'];
                                        $accentColor = $colorPalette[($babIndex - 1) % count($colorPalette)];
                                    @endphp
                                    
                                    @if($hasNarasi)
                                    <div class="card border rounded-4 shadow-xs overflow-hidden bg-white mb-3">
                                        {{-- Subject Card Header --}}
                                        <div class="p-3.5 px-4 d-flex align-items-center gap-2.5 border-bottom" style="background: #f8fafc;">
                                            <div class="rounded-pill" style="width: 4px; height: 20px; background-color: {{ $accentColor }};"></div>
                                            <iconify-icon icon="solar:bookmark-opened-bold-duotone" class="fs-5" style="color: {{ $accentColor }};"></iconify-icon>
                                            <h6 class="fw-bold text-dark mb-0" style="font-size: 15px;">{{ $bab->label }}</h6>
                                        </div>
                                        
                                        <div class="card-body p-4">
                                            @foreach($itemNarasi as $isian)
                                                @if($isian->tipe == 'sub_judul')
                                                    <div class="d-flex align-items-center gap-2 mt-3 mb-3 pb-1 border-bottom">
                                                        <iconify-icon icon="solar:folder-with-files-bold-duotone" class="text-primary fs-5"></iconify-icon>
                                                        <h6 class="fw-bold mb-0 text-primary" style="font-size: 13.5px;">
                                                            {{ $isian->label }}
                                                        </h6>
                                                    </div>
                                                @elseif($isian->tipe == 'textarea')
                                                    <div class="mb-4 p-3.5 rounded-3 border bg-light-subtle shadow-xs">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <label class="form-label fw-bold text-dark mb-0" style="font-size: 13px;">
                                                                {{ $isian->label }}
                                                            </label>
                                                            <span class="badge bg-danger-subtle text-danger font-semibold rounded-pill px-2.5 py-0.5" style="font-size: 10.5px;">* Wajib Diisi</span>
                                                        </div>
                                                        <textarea name="nilai[{{ $isian->id }}]" class="form-control form-control-custom req-field" rows="4" placeholder="Tuliskan deskripsi narasi capaian belajar ananda di sini (misal: Ananda menunjukkan antusiasme yang tinggi dalam...)"></textarea>
                                                        <div class="invalid-feedback fw-medium mt-1">Kolom narasi ini wajib diisi sebelum menyimpan rapor.</div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- ── TAB 3: PENILAIAN MUATAN LOKAL ── --}}
                    <div class="tab-pane fade" id="pills-mulok" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2" style="font-size: 17px;">
                                    <iconify-icon icon="solar:checklist-minimalistic-bold-duotone" class="text-primary fs-4"></iconify-icon>
                                    <span>Penilaian Muatan Lokal &amp; Karakter Khusus</span>
                                </h5>
                                <p class="text-muted small mb-0">Klik salah satu skala capaian (<span class="badge bg-danger-subtle text-danger px-1.5 py-0.5">BB</span> = Belum Berkembang, <span class="badge bg-warning-subtle text-warning-emphasis px-1.5 py-0.5">MB</span> = Mulai Berkembang, <span class="badge bg-primary-subtle text-primary px-1.5 py-0.5">BSH</span> = Sesuai Harapan, <span class="badge bg-success-subtle text-success px-1.5 py-0.5">BSB</span> = Sangat Baik) untuk tiap indikator.</p>
                            </div>
                        </div>
                        
                        @if($templateAktif)
                            <div class="d-flex flex-column gap-4" id="containerMulok">
                                @foreach($templateAktif->items->where('is_bab', true)->sortBy('urutan') as $bab)
                                    @php
                                        $itemMulok = $bab->anak_isian->whereIn('tipe', ['radio', 'sub_judul'])->sortBy('urutan')->values();
                                        $hasMulok = $bab->anak_isian->where('tipe', 'radio')->count() > 0;
                                    @endphp
                                    
                                    @if($hasMulok)
                                    <div class="card border rounded-4 shadow-xs overflow-hidden bg-white mb-3">
                                        <div class="p-3.5 px-4 d-flex align-items-center gap-2.5 border-bottom bg-light">
                                            <iconify-icon icon="solar:star-bold-duotone" class="text-primary fs-5"></iconify-icon>
                                            <h6 class="fw-bold text-dark mb-0" style="font-size: 15px;">{{ $bab->label }}</h6>
                                        </div>
                                        
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table align-middle mb-0 border-0 table-hover">
                                                    <thead>
                                                        <tr class="bg-light border-bottom">
                                                            <th width="6%" class="text-center py-3 text-muted small fw-bold text-uppercase">No</th>
                                                            <th width="48%" class="py-3 text-dark small fw-bold text-uppercase">Indikator Capaian Pembelajaran</th>
                                                            <th colspan="{{ count($templateAktif->opsi_mulok ?? []) }}" class="text-center py-3 bg-primary-subtle text-primary small fw-bold text-uppercase">
                                                                Skala Capaian <span class="text-danger">*</span>
                                                            </th>
                                                        </tr>
                                                        <tr class="bg-white border-bottom">
                                                            <th colspan="2" class="py-2 border-end"></th>
                                                            @foreach($templateAktif->opsi_mulok ?? [] as $opsi)
                                                                <th class="text-center py-2 text-dark small fw-bold border-start" style="width: {{ 46 / max(1, count($templateAktif->opsi_mulok ?? [])) }}%;">
                                                                    <span class="badge bg-light text-dark border shadow-none px-2.5 py-1 rounded-pill" style="font-size: 11px;">
                                                                        {{ $opsi }}
                                                                    </span>
                                                                </th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $no = 1; @endphp
                                                        @foreach($itemMulok as $isian)
                                                            @if($isian->tipe == 'sub_judul')
                                                                <tr>
                                                                    <td colspan="{{ 2 + count($templateAktif->opsi_mulok ?? []) }}" class="bg-light text-primary fw-bold py-2.5 ps-4 border-bottom border-top" style="font-size: 12.5px;">
                                                                        <iconify-icon icon="solar:folder-with-files-bold-duotone" class="fs-5 align-middle me-1.5"></iconify-icon> {{ $isian->label }}
                                                                    </td>
                                                                </tr>
                                                            @elseif($isian->tipe == 'radio')
                                                                <tr class="mulok-row border-bottom bg-white">
                                                                    <td class="text-center py-3 text-muted border-end fw-medium" style="font-size: 13px;">{{ $no++ }}</td>
                                                                    <td class="ps-4 text-dark py-3 fw-semibold border-end" style="white-space: normal; font-size: 13px;">{{ $isian->label }}</td>
                                                                    @foreach($templateAktif->opsi_mulok ?? [] as $opsi)
                                                                        <td class="text-center py-3 border-start">
                                                                            <label class="rating-pill-label d-inline-flex align-items-center justify-content-center p-2 rounded-circle cursor-pointer hover-scale">
                                                                                <input class="form-check-input req-radio border-secondary-subtle" style="transform: scale(1.35); cursor: pointer;" type="radio" name="nilai[{{ $isian->id }}]" value="{{ $opsi }}">
                                                                            </label>
                                                                        </td>
                                                                    @endforeach
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- ── TAB 4: LAMPIRAN FOTO KEGIATAN ── --}}
                    <div class="tab-pane fade" id="pills-foto" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2" style="font-size: 17px;">
                                    <iconify-icon icon="solar:gallery-wide-bold-duotone" class="text-primary fs-4"></iconify-icon>
                                    <span>Dokumentasi Foto Kegiatan Siswa (Lampiran Rapor)</span>
                                </h5>
                                <p class="text-muted small mb-0">Pilih foto dari rekaman Jurnal Harian yang ingin disertakan ke lembar cetak rapor. Pengisian ini bersifat <strong>Opsional (Tidak Wajib)</strong>.</p>
                            </div>
                        </div>

                        <div id="wrapper-foto-jurnal" class="mb-4">
                            <div class="text-center text-muted py-5 border rounded-4 bg-light shadow-xs" id="empty-foto-jurnal">
                                <div class="rounded-circle bg-white text-primary d-inline-flex align-items-center justify-content-center p-3 shadow-xs mb-3" style="width: 64px; height: 64px;">
                                    <iconify-icon icon="solar:gallery-wide-bold-duotone" class="fs-1"></iconify-icon>
                                </div>
                                <h6 class="fw-bold text-dark mb-1" id="empty-foto-text">Silakan pilih siswa terlebih dahulu pada bagian atas</h6>
                                <p class="text-muted small mb-0">Dokumentasi foto kegiatan jurnal harian siswa akan otomatis muncul di sini.</p>
                            </div>
                            
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 d-none" id="grid-foto-jurnal">
                                <!-- Populated dynamically via JS -->
                            </div>
                        </div>

                        {{-- Upload Foto Tambahan --}}
                        <div class="card border rounded-4 p-4 mt-4 shadow-xs" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center p-2.5 shadow-xs flex-shrink-0" style="width: 46px; height: 46px;">
                                    <iconify-icon icon="solar:cloud-upload-bold-duotone" class="fs-4"></iconify-icon>
                                </div>
                                <div class="flex-grow-1">
                                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 14px;">
                                        Unggah Foto Tambahan Lainnya (Opsional)
                                    </label>
                                    <p class="text-muted small mb-2.5" style="font-size: 12.5px;">
                                        Jika foto dokumentasi belum ada di Jurnal Harian, Anda dapat mengunggah file foto baru dari laptop atau ponsel (format JPG, PNG).
                                    </p>
                                    <input type="file" name="foto_kegiatan_custom[]" class="form-control bg-white border shadow-none rounded-3" multiple accept="image/*" style="font-size: 13px;">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ========================================================== --}}
                {{-- 6. DOCKED ACTION FOOTER BUTTONS --}}
                {{-- ========================================================== --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-5 pt-3.5 border-top gap-3">
                    <div>
                        <button type="button" class="btn btn-outline-secondary rounded-pill fw-bold px-4 py-2.5 d-none shadow-xs align-items-center gap-2 hover-lift" id="btn-kembali" style="font-size: 13px;">
                            <iconify-icon icon="solar:arrow-left-bold" class="fs-5"></iconify-icon>
                            <span>Kembali</span>
                        </button>
                    </div>

                    <div class="d-flex flex-wrap gap-2.5 justify-content-center align-items-center" id="area-simpan">
                        @if(auth()->user()->school && auth()->user()->school->is_rapor_open)
                            <button type="submit" name="action_type" value="draft" class="btn btn-warning text-dark border-0 rounded-pill fw-bold px-4 py-2.5 shadow-sm btn-submit-rapor d-flex align-items-center gap-2 hover-lift" style="font-size: 13px;">
                                <iconify-icon icon="solar:diskette-bold-duotone" class="fs-5"></iconify-icon>
                                <span>Simpan Sementara (Draft)</span>
                            </button>
                            
                            <button type="submit" name="action_type" value="permanen" class="btn btn-success border-0 rounded-pill fw-bold px-4 py-2.5 shadow-sm btn-submit-rapor d-flex align-items-center gap-2 hover-lift" onclick="return confirm('Apakah Anda yakin ingin Simpan Permanen?\n\nSetelah disimpan permanen, rapor siswa ini akan difinalisasi dan terkunci.')" style="font-size: 13px;">
                                <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-5"></iconify-icon>
                                <span>Simpan Permanen</span>
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary rounded-pill fw-bold px-4 py-2.5 opacity-60 d-flex align-items-center gap-2" disabled style="font-size: 13px;">
                                <iconify-icon icon="solar:lock-keyhole-bold-duotone" class="fs-5"></iconify-icon>
                                <span>Rapor Ditutup Admin</span>
                            </button>
                        @endif

                        <button type="button" class="btn btn-primary rounded-pill fw-bold px-5 py-2.5 shadow-sm d-flex align-items-center gap-2 hover-lift" id="btn-next" style="font-size: 13px;">
                            <span>Selanjutnya</span>
                            <iconify-icon icon="solar:arrow-right-bold" class="fs-5"></iconify-icon>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    @endif

</div>

<style>
    /* =======================================================
       STEPPER PROGRESS BAR STYLING
       ======================================================= */
    .rapor-stepper-list .nav-link {
        background: #ffffff;
        color: #475569;
        border: 1px solid #e2e8f0;
        transition: all 0.22s ease;
    }
    .rapor-stepper-list .nav-link:hover {
        background: #f1f5f9;
        color: #1e40af;
        border-color: #cbd5e1;
    }
    .rapor-stepper-list .nav-link.active {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%) !important;
        color: #ffffff !important;
        border-color: #1d4ed8 !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28) !important;
    }
    .step-badge {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #334155;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    .rapor-stepper-list .nav-link.active .step-badge {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }
    .step-heading {
        font-size: 13.5px;
        font-weight: 700;
        line-height: 1.2;
    }
    .step-caption {
        font-size: 11px;
        font-weight: 500;
        opacity: 0.8;
    }

    /* Form Controls & Labels */
    .form-label-custom {
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 5px;
    }
    .form-control-custom {
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        padding: 9px 13px;
        font-size: 13.5px;
        background-color: #ffffff;
        color: #0f172a;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control-custom:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }
    .form-section-card {
        transition: box-shadow 0.2s ease;
    }
    .metric-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .hover-scale {
        transition: transform 0.18s ease;
    }
    .hover-scale:hover {
        transform: scale(1.15);
    }
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const tabs = ['pills-biodata-tab', 'pills-rapor-tab', 'pills-mulok-tab', 'pills-foto-tab'];
        const panes = ['pills-biodata', 'pills-rapor', 'pills-mulok', 'pills-foto'];
        const btnNext = document.getElementById('btn-next');
        const btnKembali = document.getElementById('btn-kembali');
        const pilihSiswa = document.getElementById('pilih_siswa');
        
        const rowPilih = document.getElementById('row-pilih-siswa');
        const rowLocked = document.getElementById('row-locked-siswa');
        const alertStatus = document.getElementById('alert-status-rapor');
        const alertStatusIcon = document.getElementById('alert-status-icon');
        const alertStatusIconWrap = document.getElementById('alert-status-icon-wrap');
        const btnSubmits = document.querySelectorAll('.btn-submit-rapor');

        let isSiswaLocked = false; 

        function getCurrentTabIndex() {
            const activePane = document.querySelector('.tab-pane.active');
            if (!activePane) return 0;
            return panes.indexOf(activePane.id);
        }

        function updateNavigation() {
            const currentIndex = getCurrentTabIndex();
            
            if (isSiswaLocked) {
                rowPilih.classList.add('d-none');
                rowLocked.classList.remove('d-none');
                rowLocked.classList.add('d-flex');
                
                if(pilihSiswa.selectedIndex >= 0) {
                    const selectedOpt = pilihSiswa.options[pilihSiswa.selectedIndex];
                    document.getElementById('locked_nama').textContent = selectedOpt.text.split(' — (')[0];
                    document.getElementById('locked_kelas').textContent = selectedOpt.getAttribute('data-kelas') || '-';
                    
                    let noInduk = selectedOpt.getAttribute('data-noinduk') || '-';
                    let nisn = selectedOpt.getAttribute('data-nisn') || '-';
                    document.getElementById('locked_identitas').textContent = noInduk + ' / ' + nisn;
                }
            } else {
                rowPilih.classList.remove('d-none');
                rowLocked.classList.remove('d-flex');
                rowLocked.classList.add('d-none');
            }

            if (currentIndex === 0) {
                btnKembali.classList.remove('d-flex');
                btnKembali.classList.add('d-none');
                btnNext.classList.remove('d-none');
                btnNext.classList.add('d-flex');
                btnSubmits.forEach(b => { b.classList.remove('d-flex'); b.classList.add('d-none'); });
            } else {
                btnKembali.classList.remove('d-none');
                btnKembali.classList.add('d-flex');
                btnSubmits.forEach(b => { b.classList.remove('d-none'); b.classList.add('d-flex'); });
            }

            if (currentIndex === tabs.length - 1) {
                btnNext.classList.remove('d-flex');
                btnNext.classList.add('d-none');
            } else {
                if(currentIndex !== 0) {
                    btnNext.classList.remove('d-none');
                    btnNext.classList.add('d-flex');
                }
            }
        }

        document.querySelectorAll('#pills-tab button[data-bs-toggle="pill"]').forEach(tab => {
            tab.addEventListener('show.bs.tab', function(e) {
                if (e.target.id !== 'pills-biodata-tab') {
                    if(!pilihSiswa.value) {
                        e.preventDefault();
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Pilih Siswa Terlebih Dahulu',
                                text: 'Silakan pilih nama peserta didik yang akan dinilai sebelum melanjutkan ke tahap pengisian rapor.',
                                confirmButtonColor: '#2563eb'
                            });
                        } else {
                            alert("Pilih data siswa terlebih dahulu sebelum mulai mengisi rapor!");
                        }
                    } else {
                        isSiswaLocked = true; 
                    }
                }
            });
            
            tab.addEventListener('shown.bs.tab', function(e) {
                updateNavigation();
            });
        });

        btnNext.addEventListener('click', function(e) {
            e.preventDefault();
            if(!pilihSiswa.value) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Siswa Terlebih Dahulu',
                        text: 'Silakan pilih peserta didik pada kotak pilihan di atas.',
                        confirmButtonColor: '#2563eb'
                    });
                } else {
                    alert("Pilih data siswa terlebih dahulu!");
                }
                pilihSiswa.focus();
                return;
            }
            const currentIndex = getCurrentTabIndex();
            if (currentIndex >= 0 && currentIndex < tabs.length - 1) {
                const nextTabId = tabs[currentIndex + 1];
                new bootstrap.Tab(document.getElementById(nextTabId)).show();
                setTimeout(updateNavigation, 50);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        btnKembali.addEventListener('click', function(e) {
            e.preventDefault();
            const currentIndex = getCurrentTabIndex();
            if (currentIndex > 0) {
                const prevTabId = tabs[currentIndex - 1];
                new bootstrap.Tab(document.getElementById(prevTabId)).show();
                setTimeout(updateNavigation, 50);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        // VALIDASI FIELD WAJIB MENGGUNAKAN CLASS BOOTSTRAP: is-invalid
        btnSubmits.forEach(btn => {
            btn.addEventListener('click', function(e) {
                let isValid = true;
                let firstErrorElement = null;

                document.querySelectorAll('.req-field').forEach(el => {
                    if (!el.value.trim()) {
                        isValid = false;
                        el.classList.add('is-invalid');
                        if (!firstErrorElement) firstErrorElement = el;
                    } else {
                        el.classList.remove('is-invalid');
                    }
                });

                let radioNames = new Set();
                document.querySelectorAll('.req-radio').forEach(el => radioNames.add(el.name));
                
                radioNames.forEach(name => {
                    let checked = document.querySelector(`input[name="${name}"]:checked`);
                    let firstRadio = document.querySelector(`input[name="${name}"]`);
                    let tr = firstRadio ? firstRadio.closest('tr') : null;
                    
                    if (!checked && tr) {
                        isValid = false;
                        tr.classList.add('bg-danger-subtle');
                        if (!firstErrorElement) firstErrorElement = tr;
                    } else if (tr) {
                        tr.classList.remove('bg-danger-subtle');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kolom Belum Lengkap',
                            text: 'Masih ada narasi atau indikator nilai yang belum diisi. Silakan periksa kolom yang ditandai merah.',
                            confirmButtonColor: '#2563eb'
                        });
                    } else {
                        alert("Gagal Menyimpan! Masih ada kolom yang kosong. Silakan lengkapi terlebih dahulu.");
                    }
                    
                    if (firstErrorElement) {
                        let tabPane = firstErrorElement.closest('.tab-pane');
                        if (tabPane && !tabPane.classList.contains('active')) {
                            new bootstrap.Tab(document.querySelector(`[data-bs-target="#${tabPane.id}"]`)).show();
                            setTimeout(updateNavigation, 100);
                        }

                        setTimeout(() => {
                            firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            if(firstErrorElement.focus) firstErrorElement.focus();
                        }, 300);
                    }
                }
            });
        });

        window.toggleFotoCardStyle = function(checkbox) {
            const card = checkbox.closest('.card-foto-selectable');
            if (card) {
                if (checkbox.checked) {
                    card.classList.add('border-primary', 'bg-primary-subtle', 'bg-opacity-25');
                } else {
                    card.classList.remove('border-primary', 'bg-primary-subtle', 'bg-opacity-25');
                }
            }
        };

        function renderFotoJurnal(siswa) {
            const gridFoto = document.getElementById('grid-foto-jurnal');
            const emptyFoto = document.getElementById('empty-foto-jurnal');
            const emptyText = document.getElementById('empty-foto-text');
            if (!gridFoto || !emptyFoto) return;

            gridFoto.innerHTML = '';

            let savedFotoKegiatan = [];
            if (siswa.rapor_aktif && siswa.rapor_aktif.foto_kegiatan) {
                savedFotoKegiatan = Array.isArray(siswa.rapor_aktif.foto_kegiatan) 
                    ? siswa.rapor_aktif.foto_kegiatan 
                    : (typeof siswa.rapor_aktif.foto_kegiatan === 'string' ? JSON.parse(siswa.rapor_aktif.foto_kegiatan) : []);
            }

            if (siswa.jurnal_fotos && siswa.jurnal_fotos.length > 0) {
                emptyFoto.classList.add('d-none');
                gridFoto.classList.remove('d-none');
                
                siswa.jurnal_fotos.forEach(j => {
                    const isChecked = savedFotoKegiatan.includes(j.foto) ? 'checked' : '';
                    const col = document.createElement('div');
                    col.className = 'col';
                    col.innerHTML = `
                        <div class="card h-100 border shadow-xs rounded-4 overflow-hidden position-relative card-foto-selectable ${isChecked ? 'border-primary bg-primary-subtle bg-opacity-25' : ''}">
                            <div class="position-absolute top-0 end-0 p-2 z-2">
                                <input class="form-check-input check-foto-kegiatan fs-5 cursor-pointer shadow-sm" type="checkbox" name="foto_kegiatan[]" value="${j.foto}" ${isChecked} onchange="toggleFotoCardStyle(this)">
                            </div>
                            <img src="${j.foto_url}" class="card-img-top object-fit-cover" style="height: 140px;" alt="Foto Kegiatan">
                            <div class="card-body p-2.5">
                                <span class="badge bg-primary-subtle text-primary small mb-1 font-semibold" style="font-size: 10px;">${j.tanggal || 'Jurnal Harian'}</span>
                                <p class="card-text text-dark fw-semibold small mb-0 lh-sm text-truncate" title="${j.kegiatan || j.tema || ''}">${j.kegiatan || j.tema || 'Foto Jurnal'}</p>
                            </div>
                        </div>
                    `;
                    gridFoto.appendChild(col);
                });
            } else {
                gridFoto.classList.add('d-none');
                emptyFoto.classList.remove('d-none');
                emptyText.innerHTML = `Belum ada foto kegiatan di Jurnal Harian untuk siswa <strong>${siswa.nama_lengkap}</strong>.<br><small class="text-muted">Anda tetap dapat mengunggah foto tambahan di bawah jika diperlukan.</small>`;
            }
        }

        const originalSiswas = @json($siswas);
        if(pilihSiswa) {
            pilihSiswa.addEventListener('change', function() {
                const siswa = originalSiswas.find(s => s.id == this.value);
                if(siswa) {
                    document.getElementById('nama_lengkap').value = siswa.nama_lengkap || '';
                    document.getElementById('no_induk').value = siswa.no_induk || '';
                    document.getElementById('nisn').value = siswa.nisn || '';
                    document.getElementById('tempat_lahir').value = siswa.tempat_lahir || '';
                    document.getElementById('tgl_lahir').value = siswa.tgl_lahir || '';
                    document.getElementById('jenis_kelamin').value = siswa.jenis_kelamin || '';
                    document.getElementById('agama').value = siswa.agama || '';
                    document.getElementById('anak_ke').value = siswa.anak_ke || '';
                    document.getElementById('jml_saudara').value = siswa.jml_saudara || '';
                    document.getElementById('alamat').value = siswa.alamat || '';
                    document.getElementById('rt').value = siswa.rt || '';
                    document.getElementById('rw').value = siswa.rw || '';
                    document.getElementById('dusun').value = siswa.dusun || '';
                    document.getElementById('kelurahan').value = siswa.kelurahan || '';
                    document.getElementById('kecamatan').value = siswa.kecamatan || '';
                    document.getElementById('kode_pos').value = siswa.kode_pos || '';
                    document.getElementById('nama_ayah').value = siswa.nama_ayah || '';
                    document.getElementById('pekerjaan_ayah').value = siswa.pekerjaan_ayah || '';
                    document.getElementById('nama_ibu').value = siswa.nama_ibu || '';
                    document.getElementById('pekerjaan_ibu').value = siswa.pekerjaan_ibu || '';
                    document.getElementById('hp').value = siswa.hp || '';
                    
                    const fotoElement = document.getElementById('foto_siswa');
                    fotoElement.src = siswa.foto_anak ? "/storage/" + siswa.foto_anak : "https://ui-avatars.com/api/?name=" + encodeURIComponent(siswa.nama_lengkap) + "&color=2563eb&background=eff6ff&size=180";

                    document.getElementById('db_sakit').value = (siswa.absensi_sakit || 0);
                    document.getElementById('db_izin').value = (siswa.absensi_izin || 0);
                    document.getElementById('db_alfa').value = (siswa.absensi_alfa || 0);
                    document.getElementById('tinggi_badan').value = siswa.tinggi_badan_db ? siswa.tinggi_badan_db : '-';
                    document.getElementById('berat_badan').value = siswa.berat_badan_db ? siswa.berat_badan_db : '-';

                    document.querySelectorAll('.req-field').forEach(el => { el.value = ''; el.classList.remove('is-invalid'); });
                    document.querySelectorAll('.req-radio').forEach(el => { el.checked = false; if(el.closest('tr')) el.closest('tr').classList.remove('bg-danger-subtle'); });
                    alertStatus.classList.remove('d-flex');
                    alertStatus.classList.add('d-none');
                    btnSubmits.forEach(b => b.disabled = false);

                    // Render Tahap 4 Foto Kegiatan
                    renderFotoJurnal(siswa);

                    if (siswa.rapor_aktif) {
                        alertStatus.classList.remove('d-none');
                        alertStatus.classList.add('d-flex');
                        
                        if(siswa.rapor_aktif.status === 'permanen') {
                            alertStatus.style.background = '#f0fdf4';
                            alertStatus.style.border = '1px solid #bbf7d0';
                            alertStatusIconWrap.style.background = '#16a34a';
                            alertStatusIconWrap.style.color = '#ffffff';
                            document.getElementById('teks-status-rapor').innerHTML = "<strong class='d-block mb-0.5' style='color: #166534; font-size: 13.5px;'>Rapor Berstatus PERMANEN</strong><span style='color: #14532d; font-size: 12.5px; font-weight: 500;'>Data rapor siswa ini sudah difinalisasi dan terkunci.</span>";
                            btnSubmits.forEach(b => b.disabled = true);
                        } else {
                            alertStatus.style.background = '#fffbeb';
                            alertStatus.style.border = '1px solid #fde68a';
                            alertStatusIconWrap.style.background = '#d97706';
                            alertStatusIconWrap.style.color = '#ffffff';
                            document.getElementById('teks-status-rapor').innerHTML = "<strong class='d-block mb-0.5' style='color: #92400e; font-size: 13.5px;'>Menampilkan Draf Simpan Sementara</strong><span style='color: #78350f; font-size: 12.5px; font-weight: 500;'>Rapor ini masih berstatus Draft dan dapat diedit atau difinalisasi.</span>";
                        }

                        let n_json = siswa.rapor_aktif.nilai_json;
                        if(typeof n_json === 'string') {
                            try { n_json = JSON.parse(n_json); } catch(e) { n_json = {}; }
                        }
                        
                        if(n_json) {
                            for(let id_isian in n_json) {
                                let val = n_json[id_isian];
                                let txt = document.querySelector(`textarea[name="nilai[${id_isian}]"]`);
                                if(txt) txt.value = val;
                                
                                let rdo = document.querySelector(`input[name="nilai[${id_isian}]"][value="${val}"]`);
                                if(rdo) rdo.checked = true;
                            }
                        }
                    }
                }
            });
        }

        window.confirmGantiSiswa = function() {
            if(confirm("Apakah Anda yakin ingin mengganti siswa?\nData nilai yang baru diketik dan BELUM DISIMPAN (Draft/Permanen) akan hilang.")) {
                isSiswaLocked = false;
                
                new bootstrap.Tab(document.getElementById('pills-biodata-tab')).show();
                setTimeout(updateNavigation, 50);
                
                pilihSiswa.value = "";
                document.getElementById('nama_lengkap').value = "";
                document.getElementById('no_induk').value = "";
                document.getElementById('nisn').value = "";
                document.getElementById('tempat_lahir').value = "";
                document.getElementById('tgl_lahir').value = "";
                document.getElementById('jenis_kelamin').value = "";
                document.getElementById('agama').value = "";
                document.getElementById('anak_ke').value = "";
                document.getElementById('jml_saudara').value = "";
                document.getElementById('alamat').value = "";
                document.getElementById('rt').value = "";
                document.getElementById('rw').value = "";
                document.getElementById('dusun').value = "";
                document.getElementById('kelurahan').value = "";
                document.getElementById('kecamatan').value = "";
                document.getElementById('kode_pos').value = "";
                document.getElementById('nama_ayah').value = "";
                document.getElementById('pekerjaan_ayah').value = "";
                document.getElementById('nama_ibu').value = "";
                document.getElementById('pekerjaan_ibu').value = "";
                document.getElementById('hp').value = "";
                document.getElementById('db_sakit').value = "-";
                document.getElementById('db_izin').value = "-";
                document.getElementById('db_alfa').value = "-";
                document.getElementById('tinggi_badan').value = "-";
                document.getElementById('berat_badan').value = "-";
                
                document.getElementById('locked_kelas').textContent = "-";
                document.getElementById('locked_identitas').textContent = "-";
                
                const gridFoto = document.getElementById('grid-foto-jurnal');
                const emptyFoto = document.getElementById('empty-foto-jurnal');
                const emptyText = document.getElementById('empty-foto-text');
                if (gridFoto) gridFoto.innerHTML = '';
                if (emptyFoto) emptyFoto.classList.remove('d-none');
                if (emptyText) emptyText.textContent = 'Silakan pilih siswa terlebih dahulu pada bagian atas.';

                document.querySelectorAll('.req-field').forEach(el => el.value = '');
                document.querySelectorAll('.req-radio').forEach(el => el.checked = false);
                alertStatus.classList.remove('d-flex');
                alertStatus.classList.add('d-none');
            }
        }
    });
</script>
@endpush