@extends('layouts.admin')

@section('konten_halaman')
@php 
    $smtAktif = \App\Models\Semester::where('is_active', true)->first(); 
    $templateAktif = \App\Models\RaporTemplate::where('is_active', true)->with('items.anak_isian')->first();
@endphp

<div class="row gx-3 gy-3 mt-0">
    <div class="col-12">
        <div class="card border shadow-none mb-0" style="background-color: var(--primary-blue-soft); border-color: var(--primary-blue-border) !important;">
            <div class="card-body px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Form Pengisian Rapor</h4>
                    <p class="text-muted mb-0 small">Input dan kelola nilai akademik peserta didik sesuai format yang ditetapkan.</p>
                </div>
                @if($smtAktif)
                <div class="text-md-end">
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill shadow-sm" style="font-size: 12px;">
                        <iconify-icon icon="solar:calendar-bold-duotone" class="align-middle me-1 fs-6"></iconify-icon>
                        Semester Aktif: {{ $smtAktif->tahun_ajaran }} ({{ $smtAktif->jenis }})
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border shadow-none rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 p-md-5"> 
        
        {{-- PERINGATAN JIKA RAPOR DITUTUP / TEMPLATE KOSONG --}}
        @if(auth()->user()->school && !auth()->user()->school->is_rapor_open)
            <div class="alert alert-danger bg-danger-subtle text-danger border-danger-subtle rounded-3 fw-medium mb-4 d-flex align-items-center small">
                <iconify-icon icon="solar:danger-triangle-bold" class="fs-5 me-2"></iconify-icon> 
                Perhatian: Akses pengisian rapor sedang <strong>DITUTUP</strong> oleh Admin Sekolah.
            </div>
        @endif

        @if(!$templateAktif)
            <div class="alert alert-danger bg-danger-subtle text-danger border-danger-subtle rounded-3 fw-medium mb-4 d-flex align-items-center small">
                <iconify-icon icon="solar:shield-warning-bold" class="fs-5 me-2"></iconify-icon> 
                Admin Sekolah belum mengaktifkan Template Rapor apapun!
            </div>
        @endif

        <div id="alert-status-rapor" class="d-none alert rounded-3 fw-medium border-0 mb-4 d-flex align-items-center small shadow-sm">
            <iconify-icon icon="solar:info-circle-bold" class="fs-5 me-2"></iconify-icon>
            <span id="teks-status-rapor"></span>
        </div>

        <form method="POST" action="/isi-rapor" id="formRapor" enctype="multipart/form-data">
            @csrf

            {{-- BANNER BIRU KUNCI DATA SISWA (Murni pakai Utility Class) --}}
            <div id="row-locked-siswa" class="d-none bg-primary-subtle border border-primary-subtle border-start border-5 border-primary p-4 rounded-4 mb-4 flex-column flex-md-row justify-content-between align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <span class="badge bg-primary text-white rounded-pill mb-2 px-3 py-1" style="font-size: 10px; letter-spacing: 0.5px;">SEDANG MENILAI</span>
                    <h4 class="fw-bold text-dark mb-1" id="locked_nama">-</h4>
                    <div class="d-flex flex-wrap gap-3 gap-md-4 text-muted small mt-2">
                        <span class="d-flex align-items-center gap-1"><iconify-icon icon="solar:backpack-bold-duotone" class="text-primary fs-5"></iconify-icon> <strong class="text-dark">Kelas:</strong> <span id="locked_kelas">-</span></span>
                        <span class="d-flex align-items-center gap-1"><iconify-icon icon="solar:user-id-bold-duotone" class="text-primary fs-5"></iconify-icon> <strong class="text-dark">Induk/NISN:</strong> <span id="locked_identitas">-</span></span>
                    </div>
                </div>
                <button type="button" class="btn bg-white text-primary border-primary border rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-2" onclick="confirmGantiSiswa()">
                    <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-5"></iconify-icon> Ganti Siswa
                </button>
            </div>

            {{-- FORM PILIH SISWA --}}
            <div id="row-pilih-siswa" class="row mb-5 align-items-end p-4 bg-light rounded-4 mx-0 border border-light-subtle">
                @if(auth()->user()->role == 'admin')
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label fw-bold text-dark small mb-2"><span class="text-primary me-1">1.</span> Filter Kelas (Admin)</label>
                    <select id="filter_kelas" class="form-select bg-white border-light-subtle text-dark shadow-none py-2" onchange="window.location.href='?kelas_id='+this.value">
                        <option value="all">-- Semua Kelas --</option>
                        @foreach($list_kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-{{ auth()->user()->role == 'admin' ? '8' : '12' }}">
                    <label class="form-label fw-bold text-dark small mb-2"><span class="text-primary me-1">{{ auth()->user()->role == 'admin' ? '2.' : '1.' }}</span> Pilih Siswa yang Akan Dinilai</label>
                    <select name="siswa_id" class="form-select bg-white border-primary border-2 text-dark shadow-none py-2 fw-medium" id="pilih_siswa" required>
                        <option disabled selected value="">-- Klik untuk Menampilkan Data Siswa Aktif --</option>
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
                                {{ $siswa->nama_lengkap }} (Status: {{ $statusTxt }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- TABS NAVIGASI (Murni Bootstrap Nav-Pills) -->
            <ul class="nav nav-pills nav-fill justify-content-center mb-5 gap-2" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active border rounded-pill d-flex align-items-center justify-content-center gap-2 py-2 fw-bold" id="pills-biodata-tab" data-bs-toggle="pill" data-bs-target="#pills-biodata" type="button"><iconify-icon icon="solar:user-circle-bold-duotone" class="fs-5"></iconify-icon> Tahap 1: Cek Biodata</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link border rounded-pill d-flex align-items-center justify-content-center gap-2 py-2 fw-bold" id="pills-rapor-tab" data-bs-toggle="pill" data-bs-target="#pills-rapor" type="button"><iconify-icon icon="solar:document-text-bold-duotone" class="fs-5"></iconify-icon> Tahap 2: Isi Narasi</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link border rounded-pill d-flex align-items-center justify-content-center gap-2 py-2 fw-bold" id="pills-mulok-tab" data-bs-toggle="pill" data-bs-target="#pills-mulok" type="button"><iconify-icon icon="solar:checklist-minimalistic-bold-duotone" class="fs-5"></iconify-icon> Tahap 3: Isi Mulok</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link border rounded-pill d-flex align-items-center justify-content-center gap-2 py-2 fw-bold" id="pills-foto-tab" data-bs-toggle="pill" data-bs-target="#pills-foto" type="button"><iconify-icon icon="solar:gallery-wide-bold-duotone" class="fs-5"></iconify-icon> Tahap 4: Foto Kegiatan</button>
                </li>
            </ul>

            <!-- ISI TABS -->
            <div class="tab-content" id="pills-tabContent">
                
                {{-- TAB 1: BIODATA --}}
                <div class="tab-pane fade show active" id="pills-biodata" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-md-9">
                            <h6 class="text-primary fw-bold mb-3 border-bottom border-light-subtle pb-2 text-uppercase" style="font-size: 12px; letter-spacing: 1px;">A. Identitas Peserta Didik</h6>
                            
                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-semibold text-muted small">Nama Lengkap</label>
                                <div class="col-sm-9"><input type="text" name="biodata[nama_lengkap]" class="form-control bg-white border py-2 fw-bold text-dark shadow-none" id="nama_lengkap" value=""></div>
                            </div>
                            
                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-semibold text-muted small">No Induk / NISN</label>
                                <div class="col-sm-9 d-flex gap-2">
                                    <input type="text" name="biodata[no_induk]" class="form-control bg-white border py-2 fw-medium text-dark shadow-none w-50" id="no_induk" value="" placeholder="No Induk">
                                    <input type="text" name="biodata[nisn]" class="form-control bg-white border py-2 fw-medium text-dark shadow-none w-50" id="nisn" value="" placeholder="NISN">
                                </div>
                            </div>
                            
                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-semibold text-muted small">Tempat, Tgl Lahir</label>
                                <div class="col-sm-9 d-flex gap-2">
                                    <input type="text" name="biodata[tempat_lahir]" class="form-control bg-white border py-2 fw-medium text-dark shadow-none w-50" id="tempat_lahir" value="" placeholder="Tempat Lahir">
                                    <input type="date" name="biodata[tgl_lahir]" class="form-control bg-white border py-2 fw-medium text-dark shadow-none w-50" id="tgl_lahir" value="">
                                </div>
                            </div>

                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-semibold text-muted small">Jenis Kelamin / Agama</label>
                                <div class="col-sm-9 d-flex gap-2">
                                    <select name="biodata[jenis_kelamin]" id="jenis_kelamin" class="form-select bg-white border py-2 text-dark shadow-none w-50">
                                        <option value="">-- Jenis Kelamin --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    <select name="biodata[agama]" id="agama" class="form-select bg-white border py-2 text-dark shadow-none w-50">
                                        <option value="">-- Agama --</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Budha">Budha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-semibold text-muted small">Anak Ke / Jml Saudara</label>
                                <div class="col-sm-9 d-flex gap-2">
                                    <input type="number" name="biodata[anak_ke]" id="anak_ke" class="form-control bg-white border py-2 text-dark shadow-none w-50" placeholder="Anak Ke">
                                    <input type="number" name="biodata[jml_saudara]" id="jml_saudara" class="form-control bg-white border py-2 text-dark shadow-none w-50" placeholder="Jumlah Saudara">
                                </div>
                            </div>

                            <h6 class="text-primary fw-bold mt-4 mb-3 border-bottom border-light-subtle pb-2 text-uppercase" style="font-size: 12px; letter-spacing: 1px;">B. Alamat & Kontak Wali</h6>

                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-semibold text-muted small">Alamat Jalan</label>
                                <div class="col-sm-9"><input type="text" name="biodata[alamat]" class="form-control bg-white border py-2 text-dark shadow-none" id="alamat" value="" placeholder="Alamat lengkap"></div>
                            </div>

                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-semibold text-muted small">RT / RW / Dusun</label>
                                <div class="col-sm-9 d-flex gap-2">
                                    <input type="text" name="biodata[rt]" id="rt" class="form-control bg-white border py-2 text-dark shadow-none" style="width: 20%;" placeholder="RT">
                                    <input type="text" name="biodata[rw]" id="rw" class="form-control bg-white border py-2 text-dark shadow-none" style="width: 20%;" placeholder="RW">
                                    <input type="text" name="biodata[dusun]" id="dusun" class="form-control bg-white border py-2 text-dark shadow-none" style="width: 60%;" placeholder="Dusun">
                                </div>
                            </div>

                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-semibold text-muted small">Desa / Kec / Kode Pos</label>
                                <div class="col-sm-9 d-flex gap-2">
                                    <input type="text" name="biodata[kelurahan]" id="kelurahan" class="form-control bg-white border py-2 text-dark shadow-none w-35" placeholder="Kelurahan/Desa">
                                    <input type="text" name="biodata[kecamatan]" id="kecamatan" class="form-control bg-white border py-2 text-dark shadow-none w-35" placeholder="Kecamatan">
                                    <input type="number" name="biodata[kode_pos]" id="kode_pos" class="form-control bg-white border py-2 text-dark shadow-none w-30" placeholder="Kode Pos">
                                </div>
                            </div>

                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-semibold text-muted small">No HP / WhatsApp</label>
                                <div class="col-sm-9"><input type="text" name="biodata[hp]" class="form-control bg-white border py-2 text-dark shadow-none" id="hp" value="" placeholder="Contoh: 08123456789"></div>
                            </div>

                            <h6 class="text-primary fw-bold mt-4 mb-3 border-bottom border-light-subtle pb-2 text-uppercase" style="font-size: 12px; letter-spacing: 1px;">C. Data Orang Tua</h6>

                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-semibold text-muted small">Data Ayah</label>
                                <div class="col-sm-9 d-flex gap-2">
                                    <input type="text" name="biodata[nama_ayah]" id="nama_ayah" class="form-control bg-white border py-2 text-dark shadow-none w-50" placeholder="Nama Ayah">
                                    <input type="text" name="biodata[pekerjaan_ayah]" id="pekerjaan_ayah" class="form-control bg-white border py-2 text-dark shadow-none w-50" placeholder="Pekerjaan Ayah">
                                </div>
                            </div>

                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-semibold text-muted small">Data Ibu</label>
                                <div class="col-sm-9 d-flex gap-2">
                                    <input type="text" name="biodata[nama_ibu]" id="nama_ibu" class="form-control bg-white border py-2 text-dark shadow-none w-50" placeholder="Nama Ibu">
                                    <input type="text" name="biodata[pekerjaan_ibu]" id="pekerjaan_ibu" class="form-control bg-white border py-2 text-dark shadow-none w-50" placeholder="Pekerjaan Ibu">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 text-center">
                            <div class="border border-light-subtle rounded-4 p-2 bg-light d-inline-block shadow-sm">
                                <img id="foto_siswa" src="https://ui-avatars.com/api/?name=Pilih+Siswa&color=2563eb&background=eff6ff&size=150" 
                                    class="img-fluid rounded-3" style="width: 140px; height: 180px; object-fit: cover;">
                            </div>
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mt-5 mb-3 border-bottom border-light-subtle pb-2 text-uppercase" style="font-size: 12px; letter-spacing: 1px;">B. Rekap Data Otomatis (Kehadiran & Fisik)</h6>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <div class="border border-light-subtle rounded-3 p-3 text-center bg-white h-100 shadow-sm">
                                <span class="small text-muted fw-semibold d-block mb-1">SAKIT</span>
                                <input type="text" class="form-control-plaintext text-center fw-bold text-dark fs-4 p-0" id="db_sakit" value="-" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="border border-light-subtle rounded-3 p-3 text-center bg-white h-100 shadow-sm">
                                <span class="small text-muted fw-semibold d-block mb-1">IZIN</span>
                                <input type="text" class="form-control-plaintext text-center fw-bold text-dark fs-4 p-0" id="db_izin" value="-" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="border border-danger-subtle rounded-3 p-3 text-center bg-danger-subtle h-100 shadow-sm">
                                <span class="small text-danger fw-bold d-block mb-1">ALFA</span>
                                <input type="text" class="form-control-plaintext text-center fw-bold text-danger fs-4 p-0" id="db_alfa" value="-" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border border-primary-subtle rounded-3 p-3 text-center bg-primary-subtle h-100 shadow-sm">
                                <span class="small text-primary fw-bold d-block mb-1">TINGGI BADAN</span>
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <input type="text" class="form-control-plaintext text-center fw-bold text-primary fs-4 p-0 w-auto" id="tinggi_badan" value="-" readonly>
                                    <span class="text-primary fw-medium small">cm</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border border-primary-subtle rounded-3 p-3 text-center bg-primary-subtle h-100 shadow-sm">
                                <span class="small text-primary fw-bold d-block mb-1">BERAT BADAN</span>
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <input type="text" class="form-control-plaintext text-center fw-bold text-primary fs-4 p-0 w-auto" id="berat_badan" value="-" readonly>
                                    <span class="text-primary fw-medium small">kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: NARASI (DINAMIS DARI DATABASE) --}}
                <div class="tab-pane fade" id="pills-rapor" role="tabpanel">
                    <div class="mb-4">
                        <h4 class="fw-bolder text-dark mb-2">Laporan Perkembangan Anak</h4>
                        <p class="text-muted small">Kolom narasi di bawah ini akan diisikan langsung ke format Rapor. <strong class="text-danger">Wajib diisi seluruhnya.</strong></p>
                    </div>
                    
                    @if($templateAktif)
                        <div class="accordion" id="accordionNarasi">
                            @foreach($templateAktif->items->where('is_bab', true)->sortBy('urutan') as $bab)
                                @php
                                    $hasNarasi = $bab->anak_isian->where('tipe', 'textarea')->count() > 0;
                                    $itemNarasi = $bab->anak_isian->whereIn('tipe', ['textarea', 'sub_judul'])->sortBy('urutan')->values();
                                @endphp
                                
                                @if($hasNarasi)
                                <div class="accordion-item border border-light-subtle rounded-4 mb-3 shadow-sm overflow-hidden">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button bg-light fw-bold text-dark shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#narasi-{{ $bab->id }}">
                                            {{ $bab->label }}
                                        </button>
                                    </h2>
                                    <div id="narasi-{{ $bab->id }}" class="accordion-collapse collapse show">
                                        <div class="accordion-body bg-white p-4">
                                            @foreach($itemNarasi as $isian)
                                                @if($isian->tipe == 'sub_judul')
                                                    <h6 class="fw-bold mt-4 mb-3 text-primary border-bottom border-light-subtle pb-2" style="font-size: 13px;">
                                                        <iconify-icon icon="solar:chat-square-call-bold-duotone" class="align-middle me-1 fs-5"></iconify-icon> {{ $isian->label }}
                                                    </h6>
                                                @elseif($isian->tipe == 'textarea')
                                                    <div class="mb-4 input-group-rapor bg-light p-3 rounded-3 border border-light-subtle">
                                                        <label class="form-label fw-semibold text-dark mb-2 d-flex justify-content-between">
                                                            <span>{{ $isian->label }}</span>
                                                            <span class="text-danger small">* Wajib</span>
                                                        </label>
                                                        <!-- Murni pakai class form-control dan validasi Bootstrap (is-invalid) -->
                                                        <textarea name="nilai[{{ $isian->id }}]" class="form-control border bg-white text-dark shadow-none req-field" rows="4" placeholder="Ketik penjabaran deskripsi / narasi perkembangan anak di sini..."></textarea>
                                                        <div class="invalid-feedback">Kolom ini wajib diisi.</div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- TAB 3: MULOK (DINAMIS DARI DATABASE) --}}
                <div class="tab-pane fade" id="pills-mulok" role="tabpanel">
                    <div class="mb-4">
                        <h4 class="fw-bolder text-dark mb-2">Penilaian Muatan Lokal</h4>
                        <p class="text-muted small">Pilih salah satu skala penilaian untuk masing-masing indikator mata pelajaran.</p>
                    </div>
                    
                    @if($templateAktif)
                        <div class="accordion" id="accordionMulok">
                            @foreach($templateAktif->items->where('is_bab', true)->sortBy('urutan') as $bab)
                                @php
                                    $itemMulok = $bab->anak_isian->whereIn('tipe', ['radio', 'sub_judul'])->sortBy('urutan')->values();
                                    $accIndex = $loop->index;
                                    $hasMulok = $bab->anak_isian->where('tipe', 'radio')->count() > 0;
                                @endphp
                                
                                @if($hasMulok)
                                <div class="accordion-item border border-light-subtle rounded-4 mb-3 shadow-sm overflow-hidden">
                                    <h2 class="accordion-header" id="heading{{ $accIndex }}">
                                        <button class="accordion-button bg-light fw-bold text-dark shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMulok{{ $accIndex }}">
                                            {{ $bab->label }}
                                        </button>
                                    </h2>
                                    <div id="collapseMulok{{ $accIndex }}" class="accordion-collapse collapse show" data-bs-parent="#accordionMulok">
                                        <div class="accordion-body p-0">
                                            <div class="table-responsive">
                                                <table class="table align-middle mb-0 border-0">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%" rowspan="2" class="text-center align-middle border-end bg-light text-muted small fw-bold text-uppercase">No</th>
                                                            <th width="55%" rowspan="2" class="align-middle border-end bg-light text-muted small fw-bold text-uppercase">Indikator Pembelajaran</th>
                                                            <th colspan="{{ count($templateAktif->opsi_mulok ?? []) }}" class="text-center border-bottom bg-primary-subtle text-primary small fw-bold text-uppercase">Hasil Penilaian <span class="text-danger">*</span></th>
                                                        </tr>
                                                        <tr>
                                                            @foreach($templateAktif->opsi_mulok ?? [] as $opsi)
                                                                <th class="text-center bg-white border-start text-muted small fw-bold">{{ $opsi }}</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $no = 1; @endphp
                                                        @foreach($itemMulok as $isian)
                                                            @if($isian->tipe == 'sub_judul')
                                                                <tr>
                                                                    <td colspan="{{ 2 + count($templateAktif->opsi_mulok ?? []) }}" class="bg-light text-dark fw-bold py-3 ps-4 border-bottom-0">
                                                                        <iconify-icon icon="solar:folder-with-files-bold-duotone" class="text-primary fs-5 align-middle me-1"></iconify-icon> {{ $isian->label }}
                                                                    </td>
                                                                </tr>
                                                            @elseif($isian->tipe == 'radio')
                                                                <tr class="mulok-row border-bottom border-light-subtle bg-white">
                                                                    <td class="text-center py-3 text-muted border-end fw-medium">{{ $no++ }}</td>
                                                                    <td class="ps-4 text-dark py-3 fw-medium border-end" style="white-space: normal;">{{ $isian->label }}</td>
                                                                    @foreach($templateAktif->opsi_mulok ?? [] as $opsi)
                                                                        <td class="text-center py-3 border-start" style="background-color: #fafafa;">
                                                                            <input class="form-check-input req-radio border-secondary-subtle" style="transform: scale(1.4); cursor: pointer;" type="radio" name="nilai[{{ $isian->id }}]" value="{{ $opsi }}">
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
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- TAB 4: FOTO KEGIATAN (LAMPIRAN) --}}
                <div class="tab-pane fade" id="pills-foto" role="tabpanel">
                    <div class="border-start border-4 border-primary ps-3 mb-4">
                        <h5 class="fw-bold text-dark mb-1">DOKUMENTASI FOTO KEGIATAN SISWA (LAMPIRAN)</h5>
                        <p class="text-muted small mb-0">Pilih foto-foto kegiatan dari Jurnal Harian siswa yang ingin dilampirkan dalam Rapor. Pengisian bersifat <strong>Opsional (Tidak Wajib)</strong>.</p>
                    </div>

                    <div id="wrapper-foto-jurnal" class="mb-4">
                        <div class="text-center text-muted py-5 border rounded-3 bg-light" id="empty-foto-jurnal">
                            <iconify-icon icon="solar:gallery-wide-bold-duotone" class="fs-1 text-primary mb-2 opacity-50"></iconify-icon>
                            <p class="mb-0 fw-medium text-dark" id="empty-foto-text">Silakan pilih siswa terlebih dahulu pada bagian atas.</p>
                        </div>
                        
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 d-none" id="grid-foto-jurnal">
                            <!-- Will be populated via JS -->
                        </div>
                    </div>

                    <div class="card border-primary-subtle bg-primary-subtle bg-opacity-10 border rounded-3 p-3 mt-4">
                        <label class="form-label fw-bold text-dark mb-1">
                            <iconify-icon icon="solar:upload-square-bold-duotone" class="align-middle me-1 fs-5 text-primary"></iconify-icon> Upload Foto Tambahan Lainnya (Opsional)
                        </label>
                        <p class="text-muted small mb-2">Jika foto yang diinginkan belum ada di Jurnal Harian, Anda dapat mengunggah file foto baru di sini.</p>
                        <input type="file" name="foto_kegiatan_custom[]" class="form-control bg-white" multiple accept="image/*">
                    </div>
                </div>
            </div>

            <!-- KONTROL BAWAH (Navigasi Kiri & Kanan) -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 gap-3">
                <div>
                    <button type="button" class="btn bg-white border border-secondary-subtle text-dark rounded-pill fw-medium px-4 py-2 d-none shadow-sm d-flex align-items-center gap-2 hover-lift" id="btn-kembali">
                        <iconify-icon icon="solar:arrow-left-bold" class="fs-5"></iconify-icon> Kembali
                    </button>
                </div>

                <div class="d-flex flex-wrap gap-2 justify-content-center" id="area-simpan">
                    @if(auth()->user()->school && auth()->user()->school->is_rapor_open)
                        <button type="submit" name="action_type" value="draft" class="btn btn-warning text-dark border-0 rounded-pill fw-bold px-4 py-2 shadow-sm btn-submit-rapor d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:diskette-bold-duotone" class="fs-5"></iconify-icon> Simpan Sementara
                        </button>
                        <button type="submit" name="action_type" value="permanen" class="btn btn-success border-0 rounded-pill fw-bold px-4 py-2 shadow-sm btn-submit-rapor d-flex align-items-center gap-2" onclick="return confirm('Yakin ingin Simpan Permanen? Rapor akan terkunci dan tidak dapat diubah lagi.')">
                            <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-5"></iconify-icon> Simpan Permanen
                        </button>
                    @else
                        <button type="button" class="btn bg-secondary text-white rounded-pill fw-medium px-4 py-2 opacity-50 d-flex align-items-center gap-2" disabled>
                            <iconify-icon icon="solar:lock-keyhole-bold-duotone" class="fs-5"></iconify-icon> Rapor Ditutup Admin
                        </button>
                    @endif

                    <button type="button" class="btn btn-primary rounded-pill fw-bold px-5 py-2 shadow-sm d-flex align-items-center gap-2 hover-lift" id="btn-next">
                        Selanjutnya <iconify-icon icon="solar:arrow-right-bold" class="fs-5"></iconify-icon>
                    </button>
                </div>
            </div>

        </form>
            </div>
        </div>
    </div>
</div>
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
                rowLocked.classList.remove('d-none', 'd-md-flex');
                rowLocked.classList.add('d-flex');
                
                if(pilihSiswa.selectedIndex >= 0) {
                    const selectedOpt = pilihSiswa.options[pilihSiswa.selectedIndex];
                    document.getElementById('locked_nama').textContent = selectedOpt.text.split(' (Status:')[0];
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
                        alert("Pilih data siswa terlebih dahulu sebelum mulai mengisi rapor!");
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
                alert("Pilih data siswa terlebih dahulu!");
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

        // VALIDASI MENGGUNAKAN CLASS BOOTSTRAP: is-invalid
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
                    let tr = firstRadio.closest('tr');
                    
                    if (!checked) {
                        isValid = false;
                        tr.classList.add('bg-danger-subtle');
                        if (!firstErrorElement) firstErrorElement = tr;
                        
                        let accordionCollapse = tr.closest('.accordion-collapse');
                        if (accordionCollapse && !accordionCollapse.classList.contains('show')) {
                            new bootstrap.Collapse(accordionCollapse, { toggle: false }).show();
                        }
                    } else {
                        tr.classList.remove('bg-danger-subtle');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert("Gagal Menyimpan! Masih ada kolom yang kosong. Silakan lengkapi terlebih dahulu.");
                    
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
            });
        });

        window.toggleFotoCardStyle = function(checkbox) {
            const card = checkbox.closest('.card-foto-selectable');
            if (card) {
                if (checkbox.checked) {
                    card.classList.add('border-primary', 'bg-primary-subtle');
                } else {
                    card.classList.remove('border-primary', 'bg-primary-subtle');
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
                        <div class="card h-100 border shadow-sm rounded-3 overflow-hidden position-relative card-foto-selectable ${isChecked ? 'border-primary bg-primary-subtle' : ''}">
                            <div class="position-absolute top-0 end-0 p-2 z-2">
                                <input class="form-check-input check-foto-kegiatan fs-5 cursor-pointer shadow-sm" type="checkbox" name="foto_kegiatan[]" value="${j.foto}" ${isChecked} onchange="toggleFotoCardStyle(this)">
                            </div>
                            <img src="${j.foto_url}" class="card-img-top object-fit-cover" style="height: 140px;" alt="Foto Kegiatan">
                            <div class="card-body p-2">
                                <span class="badge bg-secondary-subtle text-dark small mb-1">${j.tanggal || 'Jurnal Harian'}</span>
                                <p class="card-text text-dark fw-medium small mb-0 lh-sm text-truncate" title="${j.kegiatan || j.tema || ''}">${j.kegiatan || j.tema || 'Foto Jurnal'}</p>
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
                    fotoElement.src = siswa.foto_anak ? "/storage/" + siswa.foto_anak : "https://ui-avatars.com/api/?name=" + encodeURIComponent(siswa.nama_lengkap) + "&color=2563eb&background=eff6ff&size=150";

                    document.getElementById('db_sakit').value = (siswa.absensi_sakit || 0);
                    document.getElementById('db_izin').value = (siswa.absensi_izin || 0);
                    document.getElementById('db_alfa').value = (siswa.absensi_alfa || 0);
                    document.getElementById('tinggi_badan').value = siswa.tinggi_badan_db ? siswa.tinggi_badan_db : '-';
                    document.getElementById('berat_badan').value = siswa.berat_badan_db ? siswa.berat_badan_db : '-';

                    document.querySelectorAll('.req-field').forEach(el => { el.value = ''; el.classList.remove('is-invalid'); });
                    document.querySelectorAll('.req-radio').forEach(el => { el.checked = false; el.closest('tr').classList.remove('bg-danger-subtle'); });
                    alertStatus.classList.remove('d-flex');
                    alertStatus.classList.add('d-none');
                    btnSubmits.forEach(b => b.disabled = false);

                    // Render Tahap 4 Foto Kegiatan
                    renderFotoJurnal(siswa);

                    if (siswa.rapor_aktif) {
                        alertStatus.classList.remove('d-none', 'alert-warning', 'alert-success', 'alert-danger');
                        alertStatus.classList.add('d-flex');
                        
                        if(siswa.rapor_aktif.status === 'permanen') {
                            alertStatus.classList.add('alert-success', 'bg-success-subtle', 'text-success');
                            document.getElementById('teks-status-rapor').innerHTML = "Rapor siswa ini sudah berstatus <strong>PERMANEN</strong> dan terkunci. Tidak bisa disimpan ulang.";
                            btnSubmits.forEach(b => b.disabled = true);
                        } else {
                            alertStatus.classList.add('alert-warning', 'bg-warning-subtle', 'text-warning-emphasis');
                            document.getElementById('teks-status-rapor').innerHTML = "Menampilkan draf sebelumnya. Rapor ini masih berstatus <strong>SIMPAN SEMENTARA</strong>.";
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
            if(confirm("Apakah Anda yakin ingin mengganti siswa?\nData yang baru diketik dan BELUM DISIMPAN (sebagai Draft/Permanen) akan HILANG.")) {
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