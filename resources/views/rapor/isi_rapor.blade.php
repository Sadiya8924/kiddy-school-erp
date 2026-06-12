@extends('layouts.admin')

@push('styles')
<style>
    .nav-pills .nav-link.active {
        background-color: #2d5cf7 !important; 
        color: white !important;
    }
    .nav-pills .nav-link {
        color: #2d5cf7 !important;
        border-color: #2d5cf7 !important;
    }
    .locked-card {
        background: linear-gradient(135deg, #e0e8ff 0%, #f4f7ff 100%);
        border-left: 5px solid #2d5cf7;
    }
    .input-error {
        border: 2px solid #dc3545 !important;
        background-color: #fff8f8 !important;
    }
    .accordion-button:not(.collapsed) {
        background-color: #e0e8ff;
        color: #2d5cf7;
    }
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
</style>
@endpush

@section('konten_halaman')
@php 
    $smtAktif = \App\Models\Semester::where('is_active', true)->first(); 
    $templateAktif = \App\Models\RaporTemplate::where('is_active', true)->with('items.anak_isian')->first();
@endphp

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-semibold text-dark mb-1">Form Pengisian Rapor</h2>
        <span class="text-muted fs-3">Input dan kelola nilai akademik peserta didik.</span>
    </div>
    
    @if($smtAktif)
    <div class="text-md-end">
        <span class="badge bg-warning-subtle text-warning border border-warning px-3 py-2 rounded-pill shadow-sm fs-4">
            <iconify-icon icon="solar:calendar-bold-duotone" class="align-middle me-1"></iconify-icon> 
            Semester Aktif: {{ $smtAktif->tahun_ajaran }} ({{ $smtAktif->jenis }})
        </span>
    </div>
    @endif
</div>

<div class="card w-100 border-0 shadow-sm position-relative overflow-hidden mb-4">
    <div class="card-body p-4"> 
        @if(auth()->user()->school && !auth()->user()->school->is_rapor_open)
            <div class="alert alert-danger rounded-3 fw-medium border-0 mb-4">
                <iconify-icon icon="solar:danger-triangle-bold" class="fs-5 align-middle me-2"></iconify-icon> Perhatian: Akses pengisian rapor sedang <strong>DITUTUP</strong> oleh Admin Sekolah.
            </div>
        @endif

        @if(!$templateAktif)
            <div class="alert alert-danger rounded-3 fw-medium border-0 mb-4">
                <iconify-icon icon="solar:shield-warning-bold" class="fs-5 align-middle me-2"></iconify-icon> Admin Sekolah belum mengaktifkan Template Rapor apapun!
            </div>
        @endif

        <div id="alert-status-rapor" class="d-none alert rounded-3 fw-medium border-0 mb-4 align-items-center">
            <iconify-icon icon="solar:info-circle-bold" class="fs-5 align-middle me-2"></iconify-icon>
            <span id="teks-status-rapor"></span>
        </div>

        <form method="POST" action="/isi-rapor" id="formRapor">
            @csrf

            {{-- BANNER BIRU KUNCI DATA SISWA + IDENTITAS TAMBAHAN --}}
            <div id="row-locked-siswa" class="d-none locked-card p-3 rounded-4 mb-4 d-flex justify-content-between align-items-center shadow-sm">
                <div>
                    <span class="badge bg-primary rounded-pill mb-2 px-3">Sedang Mengisi Rapor</span>
                    <h4 class="fw-bold text-dark mb-1" id="locked_nama">-</h4>
                    <div class="d-flex gap-4 text-muted fs-3 mt-1">
                        <span>🏫 <strong>Kelas:</strong> <span id="locked_kelas" class="text-dark fw-semibold">-</span></span>
                        <span>🆔 <strong>No Induk / NISN:</strong> <span id="locked_identitas" class="text-dark fw-semibold">-</span></span>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary bg-white rounded-pill px-4 fw-medium shadow-sm" onclick="confirmGantiSiswa()">
                    <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="align-middle me-1"></iconify-icon> Ganti Siswa
                </button>
            </div>

            <div id="row-pilih-siswa" class="row mb-4 align-items-end p-3 bg-light rounded-4 mx-0 border">
                @if(auth()->user()->role == 'admin')
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label fw-bold text-dark small mb-2">1. Filter Kelas (Admin)</label>
                    <select id="filter_kelas" class="form-select border-1 text-dark shadow-none py-2 border-success" onchange="window.location.href='?kelas_id='+this.value">
                        <option value="all">-- Semua Kelas --</option>
                        @foreach($list_kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-{{ auth()->user()->role == 'admin' ? '8' : '12' }}">
                    <label class="form-label fw-bold text-dark small mb-2">{{ auth()->user()->role == 'admin' ? '2.' : '1.' }} Pilih Siswa yang Akan Dinilai</label>
                    <select name="siswa_id" class="form-select border-1 text-dark shadow-none py-2 border-primary" id="pilih_siswa" required>
                        <option disabled selected value="">-- Klik untuk Pilih Data Siswa --</option>
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
                                {{ $siswa->nama_lengkap }} - ({{ $statusTxt }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <ul class="nav nav-pills nav-fill mb-4 gap-2" id="pills-tab" role="tablist">
                <li class="nav-item"><button class="nav-link active py-2 fw-bold border rounded-pill shadow-sm" id="pills-biodata-tab" data-bs-toggle="pill" data-bs-target="#pills-biodata" type="button">1. Cek Biodata</button></li>
                <li class="nav-item"><button class="nav-link py-2 fw-bold border rounded-pill shadow-sm" id="pills-rapor-tab" data-bs-toggle="pill" data-bs-target="#pills-rapor" type="button">2. Isi Narasi</button></li>
                <li class="nav-item"><button class="nav-link py-2 fw-bold border rounded-pill shadow-sm" id="pills-mulok-tab" data-bs-toggle="pill" data-bs-target="#pills-mulok" type="button">3. Isi Mulok</button></li>
            </ul>

            <div class="tab-content bg-white border border-light rounded-4 p-4 shadow-sm" id="pills-tabContent">
                
                {{-- TAB 1: BIODATA --}}
                <div class="tab-pane fade show active" id="pills-biodata" role="tabpanel">
                    <div class="row">
                        <div class="col-md-9">
                            <h6 class="text-dark fw-bold mb-3 border-bottom border-light pb-2">A. Identitas Peserta Didik</h6>
                            <div class="mb-2 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-medium text-muted small">Nama Lengkap</label>
                                <div class="col-sm-9"><input type="text" class="form-control-plaintext py-1 fw-bold text-primary" id="nama_lengkap" readonly value="-"></div>
                            </div>
                            <div class="mb-2 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-medium text-muted small">No Induk / NISN</label>
                                <div class="col-sm-9 d-flex gap-2">
                                    <input type="text" class="form-control-plaintext py-1 fw-semibold text-dark w-50" id="no_induk" readonly value="-" placeholder="No Induk">
                                    <input type="text" class="form-control-plaintext py-1 fw-semibold text-dark w-50 border-start ps-3" id="nisn" readonly value="-" placeholder="NISN">
                                </div>
                            </div>
                            <div class="mb-2 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-medium text-muted small">Tempat, Tgl Lahir</label>
                                <div class="col-sm-9 d-flex gap-2">
                                    <input type="text" class="form-control-plaintext py-1 fw-semibold text-dark w-50" id="tempat_lahir" readonly value="-">
                                    <input type="text" class="form-control-plaintext py-1 fw-semibold text-dark w-50" id="tgl_lahir" readonly value="-">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border border-light rounded-4 p-2 mb-2 bg-light d-inline-block shadow-sm">
                                <img id="foto_siswa" src="https://ui-avatars.com/api/?name=Pilih+Siswa&color=2d5cf7&background=e9ecef&size=150" 
                                    class="img-fluid rounded-3" style="width: 130px; height: 160px; object-fit: cover;">
                            </div>
                        </div>
                    </div>

                    <h6 class="text-dark fw-bold mt-5 mb-3 border-bottom border-light pb-2">B. Rekap Kehadiran & Fisik</h6>
                    <div class="row bg-primary-subtle p-3 rounded-4 border-0 mx-0">
                        <div class="col-md-2 mb-2 text-center">
                            <label class="small text-primary fw-bold text-uppercase d-block">Sakit</label>
                            <input type="text" class="form-control-plaintext text-center fw-bold text-dark fs-4" id="db_sakit" value="-" readonly>
                        </div>
                        <div class="col-md-2 mb-2 text-center border-start border-end border-light">
                            <label class="small text-primary fw-bold text-uppercase d-block">Izin</label>
                            <input type="text" class="form-control-plaintext text-center fw-bold text-dark fs-4" id="db_izin" value="-" readonly>
                        </div>
                        <div class="col-md-2 mb-2 text-center border-end border-light">
                            <label class="small text-primary fw-bold text-uppercase d-block">Alfa</label>
                            <input type="text" class="form-control-plaintext text-center fw-bold text-dark fs-4" id="db_alfa" value="-" readonly>
                        </div>
                        <div class="col-md-3 mb-2 text-center border-end border-light">
                            <label class="small fw-bold text-primary text-uppercase mb-1">Tinggi Badan (cm)</label>
                            <input type="text" class="form-control-plaintext text-center fw-bold text-dark fs-5" id="tinggi_badan" value="-" readonly>
                        </div>
                        <div class="col-md-3 mb-2 text-center">
                            <label class="small fw-bold text-primary text-uppercase mb-1">Berat Badan (kg)</label>
                            <input type="text" class="form-control-plaintext text-center fw-bold text-dark fs-5" id="berat_badan" value="-" readonly>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: NARASI (DINAMIS DARI DATABASE) --}}
                <div class="tab-pane fade" id="pills-rapor" role="tabpanel">
                    <h5 class="text-dark fw-bold mb-4 border-bottom border-light pb-2">Laporan Perkembangan Anak (Narasi)</h5>
                    <div class="alert alert-light border text-muted small mb-4">
                        <iconify-icon icon="solar:info-circle-bold" class="align-middle text-primary"></iconify-icon> Kolom narasi perkembangan anak di bawah ini <strong>wajib diisi seluruhnya</strong>.
                    </div>
                    
                    @if($templateAktif)
                        <div class="accordion" id="accordionNarasi">
                            @foreach($templateAktif->items->where('is_bab', true)->sortBy('urutan') as $bab)
                                @php
                                    $hasNarasi = $bab->anak_isian->where('tipe', 'textarea')->count() > 0;
                                    $itemNarasi = $bab->anak_isian->whereIn('tipe', ['textarea', 'sub_judul'])->sortBy('urutan')->values();
                                @endphp
                                
                                @if($hasNarasi)
                                <div class="accordion-item mb-3 border shadow-sm rounded-4 overflow-hidden">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#narasi-{{ $bab->id }}">
                                            {{ $bab->label }}
                                        </button>
                                    </h2>
                                    <div id="narasi-{{ $bab->id }}" class="accordion-collapse collapse show">
                                        <div class="accordion-body bg-white p-4">
                                            @foreach($itemNarasi as $isian)
                                                @if($isian->tipe == 'sub_judul')
                                                    <h6 class="fw-bold mt-4 mb-3" style="color: #2d5cf7; border-bottom: 2px dashed #e0e8ff; padding-bottom: 5px;">{{ $isian->label }}</h6>
                                                @elseif($isian->tipe == 'textarea')
                                                    <div class="mb-4 input-group-rapor">
                                                        <label class="form-label fw-bold text-dark mb-2">{{ $isian->label }} <span class="text-danger">*</span></label>
                                                        <textarea name="nilai[{{ $isian->id }}]" class="form-control border-1 text-dark shadow-none req-field" rows="3" placeholder="Ketik narasi di sini..."></textarea>
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
                    <h5 class="text-dark fw-bold mb-4 border-bottom border-light pb-2">Penilaian Muatan Lokal</h5>
                    <div class="alert alert-light border text-muted small mb-4">
                        <iconify-icon icon="solar:mouse-circle-bold" class="align-middle text-primary"></iconify-icon> Klik masing-masing kategori di bawah ini untuk mengisi nilai secara spesifik.
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
                                <div class="accordion-item mb-3 border border-light shadow-sm rounded-4 overflow-hidden">
                                    <h2 class="accordion-header" id="heading{{ $accIndex }}">
                                        <button class="accordion-button collapsed fw-bold fs-6 bg-light text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMulok{{ $accIndex }}">
                                            {{ $bab->label }}
                                        </button>
                                    </h2>
                                    <div id="collapseMulok{{ $accIndex }}" class="accordion-collapse collapse show" data-bs-parent="#accordionMulok">
                                        <div class="accordion-body p-0">
                                            <div class="table-responsive">
                                                <table class="table align-middle mb-0 text-nowrap table-hover">
                                                    <thead class="table-light border-bottom border-primary border-3">
                                                        <tr>
                                                            <th width="5%" rowspan="2" class="text-dark fw-bold py-3 text-center align-middle">No</th>
                                                            <th width="55%" rowspan="2" class="text-dark fw-bold py-3 align-middle">Mata Pelajaran</th>
                                                            <th colspan="{{ count($templateAktif->opsi_mulok ?? []) }}" class="text-dark fw-bold py-3 text-center border-bottom">Hasil Penilaian <span class="text-danger">*</span></th>
                                                        </tr>
                                                        <tr>
                                                            @foreach($templateAktif->opsi_mulok ?? [] as $opsi)
                                                                <th class="fw-bold text-center bg-light" style="width: 10%;">{{ $opsi }}</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $no = 1; @endphp
                                                        @foreach($itemMulok as $isian)
                                                            @if($isian->tipe == 'sub_judul')
                                                                <tr>
                                                                    <td colspan="{{ 2 + count($templateAktif->opsi_mulok ?? []) }}" class="bg-primary-subtle text-primary fw-bold py-2 ps-4">
                                                                        {{ $isian->label }}
                                                                    </td>
                                                                </tr>
                                                            @elseif($isian->tipe == 'radio')
                                                                <tr class="mulok-row border-bottom">
                                                                    <td class="text-center py-3 text-muted">{{ $no++ }}</td>
                                                                    <td class="ps-4 text-dark py-3 fw-medium" style="white-space: normal;">{{ $isian->label }}</td>
                                                                    @foreach($templateAktif->opsi_mulok ?? [] as $opsi)
                                                                        <td class="text-center py-3 bg-light">
                                                                            <input class="form-check-input req-radio border-primary" style="transform: scale(1.3);" type="radio" name="nilai[{{ $isian->id }}]" value="{{ $opsi }}">
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
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                <div>
                    <button type="button" class="btn btn-outline-secondary rounded-pill fw-medium px-4 d-none shadow-none" id="btn-kembali">
                        <iconify-icon icon="solar:arrow-left-bold" class="align-middle"></iconify-icon> Kembali
                    </button>
                </div>

                <div class="d-flex gap-2" id="area-simpan">
                    @if(auth()->user()->school && auth()->user()->school->is_rapor_open)
                        <button type="submit" name="action_type" value="draft" class="btn btn-warning text-dark rounded-pill fw-bold px-4 shadow-sm btn-submit-rapor">
                            <iconify-icon icon="solar:diskette-bold-duotone" class="align-middle"></iconify-icon> Simpan Sementara
                        </button>
                        <button type="submit" name="action_type" value="permanen" class="btn btn-success rounded-pill fw-bold px-4 shadow-sm btn-submit-rapor" onclick="return confirm('Yakin ingin Simpan Permanen? Rapor akan terkunci dan tidak dapat diubah lagi.')">
                            <iconify-icon icon="solar:check-circle-bold-duotone" class="align-middle"></iconify-icon> Simpan Permanen
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary rounded-pill fw-medium px-4" disabled>Rapor Ditutup Admin</button>
                    @endif

                    <button type="button" class="btn btn-primary rounded-pill fw-bold px-5 shadow-sm" id="btn-next">
                        Selanjutnya <iconify-icon icon="solar:arrow-right-bold" class="align-middle"></iconify-icon>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const tabs = ['pills-biodata-tab', 'pills-rapor-tab', 'pills-mulok-tab'];
        const panes = ['pills-biodata', 'pills-rapor', 'pills-mulok'];
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
                rowLocked.classList.remove('d-none');
                
                // ISI DATA IDENTITAS TAMBAHAN KE BANNER BIRU ATAS
                if(pilihSiswa.selectedIndex >= 0) {
                    const selectedOpt = pilihSiswa.options[pilihSiswa.selectedIndex];
                    document.getElementById('locked_nama').textContent = selectedOpt.text.split(' - ')[0];
                    document.getElementById('locked_kelas').textContent = selectedOpt.getAttribute('data-kelas') || '-';
                    
                    // Gabungkan No Induk dan NISN
                    let noInduk = selectedOpt.getAttribute('data-noinduk') || '-';
                    let nisn = selectedOpt.getAttribute('data-nisn') || '-';
                    document.getElementById('locked_identitas').textContent = noInduk + ' / ' + nisn;
                }
            } else {
                rowPilih.classList.remove('d-none');
                rowLocked.classList.add('d-none');
            }

            if (currentIndex === 0) {
                btnKembali.classList.add('d-none');
                btnNext.classList.remove('d-none');
                btnSubmits.forEach(b => b.classList.add('d-none'));
            } else {
                btnKembali.classList.remove('d-none');
                btnSubmits.forEach(b => b.classList.remove('d-none'));
            }

            if (currentIndex === tabs.length - 1) {
                btnNext.classList.add('d-none');
            } else {
                if(currentIndex !== 0) btnNext.classList.remove('d-none');
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

        btnSubmits.forEach(btn => {
            btn.addEventListener('click', function(e) {
                let isValid = true;
                let firstErrorElement = null;

                document.querySelectorAll('.req-field').forEach(el => {
                    if (!el.value.trim()) {
                        isValid = false;
                        el.classList.add('input-error');
                        if (!firstErrorElement) firstErrorElement = el;
                    } else {
                        el.classList.remove('input-error');
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
                        tr.classList.add('table-danger');
                        if (!firstErrorElement) firstErrorElement = tr;
                        
                        let accordionCollapse = tr.closest('.accordion-collapse');
                        if (accordionCollapse && !accordionCollapse.classList.contains('show')) {
                            new bootstrap.Collapse(accordionCollapse, { toggle: false }).show();
                        }
                    } else {
                        tr.classList.remove('table-danger');
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

        const originalSiswas = @json($siswas);
        if(pilihSiswa) {
            pilihSiswa.addEventListener('change', function() {
                const siswa = originalSiswas.find(s => s.id == this.value);
                if(siswa) {
                    document.getElementById('nama_lengkap').value = siswa.nama_lengkap || '-';
                    document.getElementById('no_induk').value = siswa.no_induk || '-';
                    document.getElementById('nisn').value = siswa.nisn || '-';
                    document.getElementById('tempat_lahir').value = siswa.tempat_lahir || '-';
                    document.getElementById('tgl_lahir').value = siswa.tgl_lahir || '-';
                    
                    const fotoElement = document.getElementById('foto_siswa');
                    fotoElement.src = siswa.foto_anak ? "/storage/" + siswa.foto_anak : "https://ui-avatars.com/api/?name=" + encodeURIComponent(siswa.nama_lengkap) + "&color=2d5cf7&background=e9ecef&size=150";

                    document.getElementById('db_sakit').value = (siswa.absensi_sakit || 0);
                    document.getElementById('db_izin').value = (siswa.absensi_izin || 0);
                    document.getElementById('db_alfa').value = (siswa.absensi_alfa || 0);
                    document.getElementById('tinggi_badan').value = siswa.tinggi_badan_db ? siswa.tinggi_badan_db : '-';
                    document.getElementById('berat_badan').value = siswa.berat_badan_db ? siswa.berat_badan_db : '-';

                    document.querySelectorAll('.req-field').forEach(el => { el.value = ''; el.classList.remove('input-error'); });
                    document.querySelectorAll('.req-radio').forEach(el => { el.checked = false; el.closest('tr').classList.remove('table-danger'); });
                    alertStatus.classList.add('d-none');
                    btnSubmits.forEach(b => b.disabled = false);

                    if (siswa.rapor_aktif) {
                        alertStatus.classList.remove('d-none', 'alert-warning', 'alert-success', 'alert-danger');
                        
                        if(siswa.rapor_aktif.status === 'permanen') {
                            alertStatus.classList.add('alert-success');
                            document.getElementById('teks-status-rapor').innerHTML = "Rapor siswa ini sudah berstatus <strong>PERMANEN</strong> dan terkunci. Tidak bisa disimpan ulang.";
                            btnSubmits.forEach(b => b.disabled = true);
                        } else {
                            alertStatus.classList.add('alert-warning');
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
                document.getElementById('nama_lengkap').value = "-";
                document.getElementById('no_induk').value = "-";
                document.getElementById('nisn').value = "-";
                document.getElementById('tempat_lahir').value = "-";
                document.getElementById('tgl_lahir').value = "-";
                document.getElementById('db_sakit').value = "-";
                document.getElementById('db_izin').value = "-";
                document.getElementById('db_alfa').value = "-";
                document.getElementById('tinggi_badan').value = "-";
                document.getElementById('berat_badan').value = "-";
                
                // Reset data identitas locked card
                document.getElementById('locked_kelas').textContent = "-";
                document.getElementById('locked_identitas').textContent = "-";
                
                document.querySelectorAll('.req-field').forEach(el => el.value = '');
                document.querySelectorAll('.req-radio').forEach(el => el.checked = false);
                alertStatus.classList.add('d-none');
            }
        }
    });
</script>
@endpush