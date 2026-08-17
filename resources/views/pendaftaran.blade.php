{{-- Halaman pendaftaran selalu pakai layout bersih: tanpa navbar, sidebar, atau footer --}}
@extends('layouts.pendaftaran')

@section('konten_halaman')

{{-- PANGGIL LIBRARY LEAFLET UNTUK PETA (GRATIS, TANPA API KEY!) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

{{-- PANGGIL LIBRARY LEAFLET GEOCODER (Buat fitur cari alamat) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

@php
    $activeYayasan = $yayasan ?? null;
    if (!$activeYayasan && isset($currentSchool) && !empty($currentSchool->yayasan_id)) {
        $activeYayasan = \App\Models\Yayasan::find($currentSchool->yayasan_id);
    }
    if (!$activeYayasan) {
        $activeYayasan = \App\Models\Yayasan::where('status', 'aktif')->first();
    }
    $template = ($activeYayasan && isset($activeYayasan->landing_content['template'])) 
        ? $activeYayasan->landing_content['template'] 
        : \App\Models\Setting::get('landing_template', 'ocean');
    
    $themeColor = '#2563eb';
    $themeBg    = '#F7F9FC';
    
    if ($template === 'ceria') {
        $themeColor = '#EF9F27';
        $themeBg    = '#FDFBF7';
    } elseif ($template === 'trustworthy') {
        $themeColor = '#185FA5';
        $themeBg    = '#f8fafc';
    } elseif ($template === 'natural') {
        $themeColor = '#639922';
        $themeBg    = '#F6F3EC';
    }
@endphp
<style>
    body { background-color: {{ $themeBg }} !important; }
    .bg-primary { background-color: {{ $themeColor }} !important; }
    .btn-primary { background-color: {{ $themeColor }} !important; border-color: {{ $themeColor }} !important; }
    .border-primary { border-color: {{ $themeColor }} !important; }
    .text-primary { color: {{ $themeColor }} !important; }
</style>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card border shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center py-4 border-bottom-0">
                    <h4 class="mb-1 fw-bold text-white">Formulir Pendaftaran & Inden Siswa Baru</h4>
                    <p class="mb-0 small opacity-75" id="header_school_name">
                        {{ $currentSchool->nama_sekolah ?? 'Pendaftaran Siswa Baru' }} {{ $activeYayasan ? '— ' . $activeYayasan->nama_yayasan : '' }}
                    </p> 
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    @if(session('success'))
                        <div class="alert alert-success border-success-subtle bg-success-subtle text-success rounded py-3 px-4 mb-4 fw-medium d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:check-circle-bold" class="fs-4"></iconify-icon> {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger rounded py-3 px-4 mb-4 fw-medium d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:danger-triangle-bold" class="fs-4"></iconify-icon> {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger rounded py-3 px-4 mb-4 fw-medium">
                            <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 1-WAY UNIFIED REGISTRATION FORM --}}
                    <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- SECTION 1: PILIH CABANG SEKOLAH, TAHUN AJARAN, & PROGRAM KELAS --}}
                        <div class="p-3.5 p-md-4 rounded-3 border border-primary-subtle bg-light mb-4 shadow-xs">
                            <div class="row gx-3 gy-3">
                                <div class="col-md-5">
                                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 13px;">
                                        1. Pilih Cabang TK / PAUD Tujuan <span class="text-danger">*</span>
                                    </label>
                                    @if(isset($is_locked) && $is_locked && $currentSchool)
                                        <input type="text" class="form-control bg-white border shadow-none fw-bold text-primary" value="{{ $currentSchool->nama_sekolah }}" readonly style="font-size: 13.5px;">
                                        <input type="hidden" name="school_id" id="school_id_field" value="{{ $currentSchool->id }}">
                                    @else
                                        <select name="school_id" id="school_id_field" class="form-select bg-white border shadow-none fw-bold text-primary" required style="font-size: 13.5px;">
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
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4 text-uppercase" style="letter-spacing: 0.5px;">A. Data Pribadi Siswa</h6>
                        <div class="row gx-3">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Nama Lengkap Anak <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control bg-white border shadow-none" value="{{ old('nama_lengkap') }}" style="font-size: 13px;" required placeholder="Nama lengkap sesuai akta">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">NIK Anak <span class="text-danger">*</span></label>
                                <input type="number" name="nik" class="form-control bg-white border shadow-none" value="{{ old('nik') }}" style="font-size: 13px;" required placeholder="16 digit NIK anak">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Nomor KK (Dapodik) <span class="text-danger">*</span></label>
                                <input type="number" name="no_kk" class="form-control bg-white border shadow-none" value="{{ old('no_kk') }}" style="font-size: 13px;" required placeholder="Cek di Kartu Keluarga">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="jenis_kelamin" class="form-select bg-white border shadow-none" style="font-size: 13px;" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" name="tempat_lahir" class="form-control bg-white border shadow-none" value="{{ old('tempat_lahir') }}" style="font-size: 13px;" required placeholder="Kota kelahiran">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_lahir" class="form-control bg-white border shadow-none" value="{{ old('tgl_lahir') }}" style="font-size: 13px;" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Agama <span class="text-danger">*</span></label>
                                <select name="agama" class="form-select bg-white border shadow-none" style="font-size: 13px;" required>
                                    <option value="">-- Pilih --</option>
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
                                <input type="number" name="anak_ke" class="form-control bg-white border shadow-none" value="{{ old('anak_ke') }}" style="font-size: 13px;" required placeholder="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Jumlah Saudara <span class="text-danger">*</span></label>
                                <input type="number" name="jml_saudara" class="form-control bg-white border shadow-none" value="{{ old('jml_saudara') }}" style="font-size: 13px;" required placeholder="1">
                            </div>
                        </div>

                        {{-- B. ALAMAT & KONTAK --}}
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4 text-uppercase" style="letter-spacing: 0.5px;">B. Alamat, Koordinat, & Kontak</h6>
                        
                        {{-- PETA INTERAKTIF --}}
                        <div class="row gx-3 mb-3">
                            <div class="col-12 mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                    <label class="form-label fw-bold text-dark mb-0" style="font-size: 13px;"><iconify-icon icon="solar:map-point-bold-duotone" class="text-primary"></iconify-icon> Cari Lokasi Rumah di Peta (Wajib Dapodik) <span class="text-danger">*</span></label>
                                    <button type="button" id="btnGps" class="btn btn-sm btn-outline-primary rounded-pill font-semibold px-3 py-1 d-inline-flex align-items-center gap-1.5" style="font-size: 11.5px;">
                                        <iconify-icon icon="solar:gps-bold-duotone" class="fs-6"></iconify-icon> Deteksi Lokasi Saya (GPS)
                                    </button>
                                </div>
                                <p class="small text-muted mb-2">Gunakan kotak pencarian atau klik/geser pin di peta untuk menentukan lokasi rumah Anda. Lintang & Bujur akan terisi otomatis.</p>
                                <div id="map" class="rounded border shadow-sm" style="height: 300px; width: 100%; z-index: 1;"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Garis Lintang (Latitude) <span class="text-danger">*</span></label>
                                <input type="text" id="lintang" name="lintang" class="form-control bg-light border shadow-none font-semibold text-primary" value="{{ old('lintang') }}" style="font-size: 13px;" readonly required placeholder="Otomatis dari Peta">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Garis Bujur (Longitude) <span class="text-danger">*</span></label>
                                <input type="text" id="bujur" name="bujur" class="form-control bg-light border shadow-none font-semibold text-primary" value="{{ old('bujur') }}" style="font-size: 13px;" readonly required placeholder="Otomatis dari Peta">
                            </div>
                        </div>

                        <div class="row gx-3">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Alamat Jalan <span class="text-danger">*</span></label>
                                <input type="text" name="alamat" class="form-control bg-white border shadow-none" value="{{ old('alamat') }}" style="font-size: 13px;" required placeholder="Jl. Raya Bangsa No. 12">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">RT <span class="text-danger">*</span></label>
                                <input type="text" name="rt" class="form-control bg-white border shadow-none" value="{{ old('rt') }}" style="font-size: 13px;" required placeholder="01">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">RW <span class="text-danger">*</span></label>
                                <input type="text" name="rw" class="form-control bg-white border shadow-none" value="{{ old('rw') }}" style="font-size: 13px;" required placeholder="02">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Dusun <span class="text-danger">*</span></label>
                                <input type="text" name="dusun" class="form-control bg-white border shadow-none" value="{{ old('dusun') }}" style="font-size: 13px;" required placeholder="Dusun...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Desa/Kel. <span class="text-danger">*</span></label>
                                <input type="text" name="kelurahan" class="form-control bg-white border shadow-none" value="{{ old('kelurahan') }}" style="font-size: 13px;" required placeholder="Kelurahan...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Kecamatan <span class="text-danger">*</span></label>
                                <input type="text" name="kecamatan" class="form-control bg-white border shadow-none" value="{{ old('kecamatan') }}" style="font-size: 13px;" required placeholder="Kecamatan...">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Kode Pos <span class="text-danger">*</span></label>
                                <input type="number" name="kode_pos" class="form-control bg-white border shadow-none" value="{{ old('kode_pos') }}" style="font-size: 13px;" required placeholder="66171">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Jenis Tinggal <span class="text-danger">*</span></label>
                                <select name="jenis_tinggal" class="form-select bg-white border shadow-none" style="font-size: 13px;" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Bersama orang tua" {{ old('jenis_tinggal', 'Bersama orang tua') == 'Bersama orang tua' ? 'selected' : '' }}>Bersama orang tua</option>
                                    <option value="Wali" {{ old('jenis_tinggal') == 'Wali' ? 'selected' : '' }}>Wali</option>
                                    <option value="Asrama" {{ old('jenis_tinggal') == 'Asrama' ? 'selected' : '' }}>Asrama</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Transportasi ke Sekolah <span class="text-danger">*</span></label>
                                <input type="text" name="alat_transportasi" class="form-control bg-white border shadow-none" value="{{ old('alat_transportasi') }}" style="font-size: 13px;" required placeholder="Jalan kaki, Motor, Mobil, dll">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Jarak ke Sekolah (Km) <span class="text-danger">*</span></label>
                                <input type="number" name="jarak_rumah" class="form-control bg-white border shadow-none" value="{{ old('jarak_rumah') }}" style="font-size: 13px;" required placeholder="Contoh: 1">
                            </div>

                            @if(Auth::check())
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark" style="font-size: 12px;">No HP / WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" name="hp" class="form-control bg-white border shadow-none" value="{{ old('hp') }}" style="font-size: 13px;" required placeholder="08xxxxxxxxxx">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Email Akun Portal Anda</label>
                                    <input type="email" name="email" class="form-control bg-light border text-muted shadow-none fw-semibold" value="{{ Auth::user()->email }}" style="font-size: 13px;" readonly>
                                    <div class="form-text text-success d-flex align-items-center gap-1 mt-1" style="font-size: 11.5px;">
                                        <iconify-icon icon="solar:check-circle-bold"></iconify-icon> Terhubung ke akun: <strong>{{ Auth::user()->name }}</strong>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark" style="font-size: 12px;">No HP / WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" name="hp" class="form-control bg-white border shadow-none" value="{{ old('hp') }}" style="font-size: 13px;" required placeholder="08xxxxxxxxxx">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Email Orang Tua <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="parent_email" class="form-control bg-white border shadow-none" value="{{ old('email') }}" style="font-size: 13px;" required placeholder="email@anda.com">
                                    <div id="email_feedback_box" class="mt-2" style="display:none;"></div>
                                </div>
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
                                            Kata sandi ini digunakan untuk login ke portal wali murid guna memantau kelulusan, rapor, & absensi anak.
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- C. DATA ORANG TUA --}}
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4 text-uppercase" style="letter-spacing: 0.5px;">C. Data Orang Tua</h6>
                        <div class="row gx-3">
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded bg-light h-100">
                                    <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;">Data Ayah Kandung</h6>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Nama Ayah <span class="text-danger">*</span></label><input type="text" name="nama_ayah" class="form-control bg-white border shadow-none" value="{{ old('nama_ayah') }}" style="font-size: 12px;" required placeholder="Nama lengkap ayah"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">NIK Ayah <span class="text-danger">*</span></label><input type="number" name="nik_ayah" class="form-control bg-white border shadow-none" value="{{ old('nik_ayah') }}" style="font-size: 12px;" required placeholder="16 digit NIK ayah"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Tahun Lahir <span class="text-danger">*</span></label><input type="number" name="tahun_lahir_ayah" class="form-control bg-white border shadow-none" value="{{ old('tahun_lahir_ayah') }}" style="font-size: 12px;" required placeholder="1980"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Pendidikan <span class="text-danger">*</span></label><input type="text" name="pendidikan_ayah" class="form-control bg-white border shadow-none" value="{{ old('pendidikan_ayah') }}" style="font-size: 12px;" required placeholder="SMA / S1 / dll"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Pekerjaan <span class="text-danger">*</span></label><input type="text" name="pekerjaan_ayah" class="form-control bg-white border shadow-none" value="{{ old('pekerjaan_ayah') }}" style="font-size: 12px;" required placeholder="Karyawan / Wiraswasta / dll"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Penghasilan Bulanan <span class="text-danger">*</span></label><input type="text" name="penghasilan_ayah" class="form-control bg-white border shadow-none" value="{{ old('penghasilan_ayah') }}" style="font-size: 12px;" required placeholder="Contoh: Rp. 3.000.000"></div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded bg-light h-100">
                                    <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;">Data Ibu Kandung</h6>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Nama Ibu <span class="text-danger">*</span></label><input type="text" name="nama_ibu" class="form-control bg-white border shadow-none" value="{{ old('nama_ibu') }}" style="font-size: 12px;" required placeholder="Nama lengkap ibu"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">NIK Ibu <span class="text-danger">*</span></label><input type="number" name="nik_ibu" class="form-control bg-white border shadow-none" value="{{ old('nik_ibu') }}" style="font-size: 12px;" required placeholder="16 digit NIK ibu"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Tahun Lahir <span class="text-danger">*</span></label><input type="number" name="tahun_lahir_ibu" class="form-control bg-white border shadow-none" value="{{ old('tahun_lahir_ibu') }}" style="font-size: 12px;" required placeholder="1985"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Pendidikan <span class="text-danger">*</span></label><input type="text" name="pendidikan_ibu" class="form-control bg-white border shadow-none" value="{{ old('pendidikan_ibu') }}" style="font-size: 12px;" required placeholder="SMA / S1 / dll"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Pekerjaan <span class="text-danger">*</span></label><input type="text" name="pekerjaan_ibu" class="form-control bg-white border shadow-none" value="{{ old('pekerjaan_ibu') }}" style="font-size: 12px;" required placeholder="Ibu Rumah Tangga / dll"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Penghasilan Bulanan <span class="text-danger">*</span></label><input type="text" name="penghasilan_ibu" class="form-control bg-white border shadow-none" value="{{ old('penghasilan_ibu') }}" style="font-size: 12px;" required placeholder="Contoh: Rp. 2.000.000"></div>
                                </div>
                            </div>
                        </div>

                        {{-- D. FISIK & DOKUMEN --}}
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4 text-uppercase" style="letter-spacing: 0.5px;">D. Fisik & Dokumen Upload</h6>
                        <div class="row gx-3">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Tinggi Badan (cm) <span class="text-danger">*</span></label>
                                <input type="number" name="tinggi_badan" class="form-control bg-white border shadow-none" value="{{ old('tinggi_badan') }}" style="font-size: 13px;" required placeholder="110">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Berat Badan (kg) <span class="text-danger">*</span></label>
                                <input type="number" name="berat_badan" class="form-control bg-white border shadow-none" value="{{ old('berat_badan') }}" style="font-size: 13px;" required placeholder="18">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Lingkar Kepala (cm) <span class="text-danger">*</span></label>
                                <input type="number" name="lingkar_kepala" class="form-control bg-white border shadow-none" value="{{ old('lingkar_kepala') }}" style="font-size: 13px;" required placeholder="48">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Pas Foto Anak (Wajib) <span class="text-danger">*</span></label>
                                <input type="file" name="foto_anak" accept="image/*" class="form-control bg-white border-primary shadow-none" required>
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
                                <input type="file" name="bukti_pembayaran" accept="image/*" class="form-control bg-white border-success shadow-none" required>
                                <div class="form-text text-muted" style="font-size: 11px;">Format: JPG/PNG, Max: 2MB. Upload struk transfer pendaftaran.</div>
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-none py-3 d-flex justify-content-center align-items-center gap-2" style="font-size: 15px;">
                                <iconify-icon icon="solar:send-square-bold-duotone" class="fs-5"></iconify-icon> Kirim Formulir Pendaftaran
                            </button>
                        </div>
                    </form>
                    
                    {{-- SCRIPT JAVASCRIPT: PETA LEAFLET & OTOMASI KUOTA PER SEKOLAH (1-WAY) --}}
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
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

                            // 2. Data Kuota & Tahun Per Sekolah (1-WAY CLIENT-SIDE DYNAMIC)
                            const schoolsData = @json($schools_data ?? []);
                            const schoolSelect = document.getElementById('school_id_field');
                            const tahunSelect = document.getElementById('tahun_inden');
                            const programSelect = document.getElementById('program_pilihan');
                            const headerSchoolName = document.getElementById('header_school_name');
                            
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
                                        headerSchoolName.textContent = schoolInfo.nama_sekolah;
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
                                        if (sisa <= 0) opt.disabled = true;
                                        if (val === oldProgram) opt.selected = true;
                                        programSelect.appendChild(opt);
                                    };

                                    addOption('TK Pagi', 'Taman Kanak-Kanak (Pagi)', kuota.tk_pagi);
                                    addOption('TK Siang', 'Taman Kanak-Kanak (Siang)', kuota.tk_siang);
                                    addOption('PAUD', 'Kelompok Bermain / PAUD', kuota.paud);
                                } else {
                                    programSelect.disabled = true;
                                }
                            }

                            if (schoolSelect && schoolSelect.tagName === 'SELECT') {
                                schoolSelect.addEventListener('change', updateTahunOptions);
                            }
                            if (tahunSelect) {
                                tahunSelect.addEventListener('change', updateProgramOptions);
                            }

                            // Inisialisasi awal saat form pertama kali dimuat
                            updateTahunOptions();

                            // 3. Real-time Email Check for Parent Account
                            const emailInput = document.getElementById('parent_email');
                            const feedbackBox = document.getElementById('email_feedback_box');
                            const passLabel = document.getElementById('password_label');
                            const passHint = document.getElementById('password_hint');
                            let emailCheckTimeout = null;

                            if (emailInput && feedbackBox) {
                                function checkEmail(emailVal) {
                                    if (!emailVal || !emailVal.includes('@') || emailVal.length < 5) {
                                        feedbackBox.style.display = 'none';
                                        return;
                                    }

                                    feedbackBox.style.display = 'block';
                                    feedbackBox.innerHTML = '<span class="text-muted small d-inline-flex align-items-center gap-1"><iconify-icon icon="solar:spinner-line-duotone" class="spin"></iconify-icon> Memeriksa status email...</span>';

                                    fetch('{{ route('api.check_parent_email') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ email: emailVal })
                                    })
                                    .then(res => res.json())
                                    .then(data => {
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
                                                        <strong>Email Baru:</strong> Akun portal wali murid akan otomatis dibuatkan menggunakan email & kata sandi yang Anda tentukan di bawah.
                                                    </div>
                                                </div>
                                            `;
                                            if (passLabel) passLabel.innerHTML = 'Buat Kata Sandi Akun Baru <span class="text-danger">*</span>';
                                            if (passHint) passHint.innerHTML = 'Kata sandi ini akan menjadi kata sandi akun portal wali murid baru Anda (Min. 6 karakter).';
                                        }
                                    })
                                    .catch(() => {
                                        feedbackBox.style.display = 'none';
                                    });
                                }

                                emailInput.addEventListener('input', function() {
                                    clearTimeout(emailCheckTimeout);
                                    emailCheckTimeout = setTimeout(() => {
                                        checkEmail(this.value.trim());
                                    }, 400);
                                });

                                // Check on initial load if old email is present
                                if (emailInput.value.trim()) {
                                    checkEmail(emailInput.value.trim());
                                }
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
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection