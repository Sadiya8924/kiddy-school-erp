@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

    @if(session('success'))
        <div class="alert alert-success border border-success-subtle shadow-none rounded-3 py-2 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
            <div><i class="ti ti-check me-2"></i>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border border-danger-subtle shadow-none rounded-3 py-2 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
            <div><i class="ti ti-alert-circle me-2"></i>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ========================================================== --}}
    {{-- 1. HERO WELCOME CARD WITH SOFT BLUE GRADIENT --}}
    {{-- ========================================================== --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%); border-radius: 16px !important;">
        <div class="card-body p-4 text-white">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                
                {{-- Title & Context --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid rgba(255,255,255,0.4);">
                            <iconify-icon icon="solar:journal-bookmark-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">
                                @if(isset($pageType) && $pageType === 'lms')
                                    Manajemen Materi LMS
                                @else
                                    Jurnal Harian Siswa
                                @endif
                            </h5>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            @if(isset($pageType) && $pageType === 'lms')
                                <span>Kelola materi pembelajaran dan modul untuk siswa secara terstruktur.</span>
                            @else
                                <span>Pilih tanggal dan topik materi untuk mengisikan catatan jurnal perkembangan anak.</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Action Controls --}}
                <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                    @if(auth()->user()->role == 'admin' || auth()->user()->isSuperAdmin() || auth()->user()->role == 'guru')
                        <button type="button" class="btn btn-warning text-dark rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#modalTambahMateri">
                            <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon> Buat Topik Jurnal
                        </button>
                    @endif
                    <button type="button" class="btn btn-light text-primary rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#modalQuickViewJurnal">
                        <iconify-icon icon="solar:eye-bold-duotone" class="fs-5"></iconify-icon> Rekap Jurnal
                    </button>
                    <a href="{{ route('jurnal.zip') }}" class="btn btn-outline-light text-white rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
                        <iconify-icon icon="solar:archive-bold-duotone" class="fs-5"></iconify-icon> Unduh ZIP
                    </a>
                </div>

            </div>
        </div>
    </div>

    @include('partials.owner_stepped_filter')

    @if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))
    
    {{-- STATS CARDS --}}
    <div class="row g-3">
        <div class="col-6 col-md-3">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
                <div class="card-body p-3.5">
                    <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">TOTAL TOPIK</span>
                    <h3 class="fw-bold mb-0 text-primary">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #10b981 !important; border-radius: 16px !important;">
                <div class="card-body p-3.5">
                    <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">HARI INI</span>
                    <h3 class="fw-bold mb-0 text-success">{{ $stats['today'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #06b6d4 !important; border-radius: 16px !important;">
                <div class="card-body p-3.5">
                    <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">KELOMPOK A</span>
                    <h3 class="fw-bold mb-0 text-info">{{ $stats['kelompok_a'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #f59e0b !important; border-radius: 16px !important;">
                <div class="card-body p-3.5">
                    <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">KELOMPOK B</span>
                    <h3 class="fw-bold mb-0 text-warning">{{ $stats['kelompok_b'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER BAR --}}
    <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
        <div class="card-body p-3.5">
            <form method="GET" action="{{ route('jurnal.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small font-semibold text-dark mb-1" style="font-size: 12px;">
                        <iconify-icon icon="solar:calendar-bold-duotone" class="align-middle me-1 text-primary"></iconify-icon> Filter Tanggal:
                    </label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control rounded-3 font-medium" onchange="this.form.submit()" style="font-size: 12.5px;">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label small font-semibold text-dark mb-1" style="font-size: 12px;">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="align-middle me-1 text-primary"></iconify-icon> Filter Kelompok:
                    </label>
                    <select name="kelompok" class="form-select rounded-3 font-medium" onchange="this.form.submit()" style="font-size: 12.5px;">
                        <option value="semua" {{ request('kelompok') == 'semua' || !request('kelompok') ? 'selected' : '' }}>Semua Kelompok</option>
                        <option value="A" {{ request('kelompok') == 'A' ? 'selected' : '' }}>Kelompok A (TK)</option>
                        <option value="B" {{ request('kelompok') == 'B' ? 'selected' : '' }}>Kelompok B (TK)</option>
                        <option value="PAUD" {{ request('kelompok') == 'PAUD' ? 'selected' : '' }}>PAUD</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small font-semibold text-dark mb-1" style="font-size: 12px;">
                        <iconify-icon icon="solar:magnifer-bold-duotone" class="align-middle me-1 text-primary"></iconify-icon> Cari Topik / Tema:
                    </label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control rounded-start-3 font-medium" placeholder="Ketik kata kunci tema..." value="{{ request('search') }}" style="font-size: 12.5px;">
                        <button type="submit" class="btn btn-primary rounded-end-3 px-3 shadow-none font-semibold" style="font-size: 12px;">
                            <iconify-icon icon="solar:magnifer-linear" class="align-middle"></iconify-icon> Cari
                        </button>
                    </div>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <a href="{{ route('jurnal.index', ['tanggal' => date('Y-m-d')]) }}" class="btn btn-outline-primary rounded-3 w-100 font-semibold py-1.5 {{ request('tanggal') == date('Y-m-d') ? 'active' : '' }}" style="font-size: 12px;" title="Tampilkan materi hari ini">
                        Hari Ini
                    </a>
                    @if(request('tanggal') || (request('kelompok') && request('kelompok') != 'semua') || request('search'))
                        <a href="{{ route('jurnal.index') }}" class="btn btn-light text-danger border rounded-3 px-3 py-1.5" title="Reset Filter">
                            <iconify-icon icon="solar:restart-bold" class="align-middle fs-5"></iconify-icon>
                        </a>
                    @endif
                </div>
            </form>

            @if(request('tanggal') || (request('kelompok') && request('kelompok') != 'semua') || request('search'))
                <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top flex-wrap">
                    <span class="small text-muted font-semibold" style="font-size: 11.5px;">Filter Aktif:</span>
                    @if(request('tanggal'))
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1.5 font-semibold" style="font-size: 11px;">
                            Tanggal: {{ \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') }}
                            <a href="{{ route('jurnal.index', request()->except('tanggal')) }}" class="text-primary ms-1"><iconify-icon icon="solar:close-circle-bold"></iconify-icon></a>
                        </span>
                    @endif
                    @if(request('kelompok') && request('kelompok') != 'semua')
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1.5 font-semibold" style="font-size: 11px;">
                            Kelompok: {{ request('kelompok') }}
                            <a href="{{ route('jurnal.index', request()->except('kelompok')) }}" class="text-success ms-1"><iconify-icon icon="solar:close-circle-bold"></iconify-icon></a>
                        </span>
                    @endif
                    @if(request('search'))
                        <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1.5 font-semibold" style="font-size: 11px;">
                            Pencarian: "{{ request('search') }}"
                            <a href="{{ route('jurnal.index', request()->except('search')) }}" class="text-info ms-1"><iconify-icon icon="solar:close-circle-bold"></iconify-icon></a>
                        </span>
                    @endif
                    <a href="{{ route('jurnal.index') }}" class="small text-danger text-decoration-none ms-auto font-semibold" style="font-size: 11.5px;">Bersihkan Semua</a>
                </div>
            @endif
        </div>
    </div>

    {{-- DAFTAR TOPIK JURNAL --}}
    <div class="row g-3">
        @forelse($materis as $materi)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border shadow-sm overflow-hidden" style="border-top: 3.5px solid {{ $materi->kelompok == 'A' ? '#3b82f6' : ($materi->kelompok == 'B' ? '#10b981' : '#06b6d4') }} !important; border-radius: 16px !important;">
                    <div class="card-header border-0 bg-white p-3.5 border-bottom border-light-subtle">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge {{ $materi->kelompok == 'A' ? 'bg-primary-subtle text-primary' : ($materi->kelompok == 'B' ? 'bg-success-subtle text-success' : 'bg-info-subtle text-info') }} font-semibold rounded-pill px-2.5 py-1" style="font-size: 11px;">
                                <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="align-middle me-1"></iconify-icon>
                                Kelompok {{ $materi->kelompok }}
                            </span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $materi->judul_materi }}" style="font-size: 14.5px;">{{ $materi->judul_materi }}</h6>
                        <div class="d-flex align-items-center gap-1.5 text-muted font-medium" style="font-size: 11.5px;">
                            <iconify-icon icon="solar:calendar-bold-duotone" class="text-primary"></iconify-icon>
                            <span>{{ \Carbon\Carbon::parse($materi->tanggal_dibuat)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>

                    <div class="card-body p-3.5 d-flex flex-column">
                        <p class="text-muted small mb-3 flex-grow-1 text-truncate-2" style="min-height: 38px; font-size: 12px;">
                            {{ $materi->deskripsi ?: 'Tidak ada deskripsi tambahan untuk topik ini.' }}
                        </p>
                        
                        <div class="bg-slate-50 p-2.5 rounded-3 border border-slate-100 mb-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <iconify-icon icon="solar:notebook-bold-duotone" class="fs-5 text-primary"></iconify-icon>
                                <span class="text-muted font-semibold" style="font-size: 11.5px;">Jurnal Terisi:</span>
                            </div>
                            <span class="badge bg-primary rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11.5px;">{{ $materi->total_jurnal ?? 0 }} Siswa</span>
                        </div>

                        <span class="fw-bold text-dark small mb-2 d-flex align-items-center gap-1" style="font-size: 11.5px;">
                            <iconify-icon icon="solar:eye-bold-duotone" class="text-primary"></iconify-icon> Detail Jurnal Per Kelas:
                        </span>
                        
                        <div class="d-grid gap-2">
                            @php
                                $kelas_match = $list_kelas->where('kelompok', $materi->kelompok);
                            @endphp
                            
                            @forelse($kelas_match as $kelas)
                                <a href="{{ route('jurnal.kelas.show', ['materi_id' => $materi->id, 'kelas_id' => $kelas->id]) }}" class="btn btn-outline-primary rounded-3 d-flex justify-content-between align-items-center px-3 py-1.5 font-semibold" style="font-size: 12px;">
                                    <span><iconify-icon icon="solar:eye-bold-duotone" class="align-middle me-1 text-primary"></iconify-icon> Lihat Jurnal - Kelas {{ $kelas->nama_kelas }}</span>
                                    <iconify-icon icon="solar:alt-arrow-right-bold-duotone" class="fs-5"></iconify-icon>
                                </a>
                            @empty
                                <span class="text-danger small fst-italic py-2 text-center" style="font-size: 11px;">Belum ada kelas terdaftar di kelompok ini.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="card border shadow-sm p-5 bg-white" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
                    <div class="card-body">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px;">
                            <iconify-icon icon="solar:file-remove-bold-duotone" class="fs-2"></iconify-icon>
                        </div>
                        <h5 class="text-dark fw-bold mb-1" style="font-size: 16px;">Tidak Ada Topik Jurnal</h5>
                        <p class="text-muted small mb-4" style="font-size: 12.5px;">
                            @if(request('tanggal') || request('kelompok') || request('search'))
                                Tidak ditemukan materi kegiatan jurnal yang sesuai dengan filter pilihan Anda.
                            @else
                                Belum ada topik kegiatan jurnal yang dibuat. Silakan tambahkan topik baru untuk memulai.
                            @endif
                        </p>
                        @if(request('tanggal') || request('kelompok') || request('search'))
                            <a href="{{ route('jurnal.index') }}" class="btn btn-primary rounded-3 px-4 py-2 font-semibold shadow-xs" style="font-size: 12.5px;">
                                <iconify-icon icon="solar:restart-bold" class="align-middle me-1"></iconify-icon> Reset Filter Pilihan
                            </a>
                        @else
                            @if(auth()->user()->role == 'admin' || auth()->user()->isSuperAdmin() || auth()->user()->role == 'guru')
                                <button type="button" class="btn btn-primary rounded-3 px-4 py-2 font-semibold shadow-xs" style="font-size: 12.5px;" data-bs-toggle="modal" data-bs-target="#modalTambahMateri">
                                    <iconify-icon icon="solar:add-circle-bold-duotone" class="align-middle me-1"></iconify-icon> Buat Topik Jurnal Pertama
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    @endif

    {{-- ========================================================== --}}
    {{-- SUBTLE LOW-KEY FOOTER --}}
    {{-- ========================================================== --}}
    <footer class="text-center py-2 mt-2">
        <span class="text-muted" style="font-size: 11px;">© 2026 TK Ceria - Web TK Al-Hidayah v1.0</span>
    </footer>

</div>

{{-- MODAL QUICK VIEW JURNAL HARIAN --}}
<div class="modal fade" id="modalQuickViewJurnal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-slate-50 border-bottom p-3.5">
                <div class="d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:eye-bold-duotone" class="fs-4 text-primary"></iconify-icon>
                    <h6 class="modal-title fw-bold text-dark mb-0" style="font-size: 15px;">Detail Jurnal Harian Siswa</h6>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-start">
                <p class="text-muted small mb-3" style="font-size: 12.5px;">Pilih topik materi dan kelas di bawah ini untuk melihat seluruh isian jurnal perkembangan harian anak:</p>
                <div class="list-group rounded-3 shadow-xs border">
                    @forelse($materis as $m)
                        @php $kMatch = $list_kelas->where('kelompok', $m->kelompok); @endphp
                        @foreach($kMatch as $k)
                            <a href="{{ route('jurnal.kelas.show', ['materi_id' => $m->id, 'kelas_id' => $k->id]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 border-bottom border-light-subtle">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 13.5px;">{{ $m->judul_materi }}</h6>
                                    <small class="text-muted" style="font-size: 11.5px;">
                                        <iconify-icon icon="solar:calendar-bold-duotone" class="align-middle text-primary me-1"></iconify-icon> 
                                        {{ \Carbon\Carbon::parse($m->tanggal_dibuat)->translatedFormat('d M Y') }} | 
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-0.5" style="font-size: 10.5px;">Kelas {{ $k->nama_kelas }}</span>
                                    </small>
                                </div>
                                <span class="btn btn-sm btn-outline-primary rounded-3 px-3 py-1 font-semibold" style="font-size: 11.5px;">
                                    <iconify-icon icon="solar:eye-bold-duotone" class="align-middle me-1"></iconify-icon> Lihat Detail
                                </span>
                            </a>
                        @endforeach
                    @empty
                        <div class="p-4 text-center text-muted small">Belum ada data jurnal.</div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer border-top p-3.5">
                <button type="button" class="btn btn-outline-secondary rounded-3 font-semibold px-4 py-2" style="font-size: 12.5px;" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH MATERI (KHUSUS ADMIN/GURU) --}}
@if(auth()->user()->role == 'admin' || auth()->user()->isSuperAdmin() || auth()->user()->role == 'guru')
<div class="modal fade" id="modalTambahMateri" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('jurnal.materi.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-slate-50 border-bottom p-3.5">
                    <div class="d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:folder-with-files-bold-duotone" class="fs-4 text-primary"></iconify-icon>
                        <h6 class="modal-title fw-bold text-dark mb-0" style="font-size: 15px;">Buat Topik Kegiatan Jurnal</h6>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label font-semibold text-dark small">Target Kelompok <span class="text-danger">*</span></label>
                        <select name="kelompok" class="form-select rounded-3 font-medium" required style="font-size: 13px;">
                            <option value="A">Kelompok A (TK)</option>
                            <option value="B">Kelompok B (TK)</option>
                            <option value="PAUD">PAUD</option>
                        </select>
                        <span class="text-muted d-block mt-1" style="font-size: 11px;">Topik ini akan berlaku untuk seluruh kelas dalam kelompok ini.</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold text-dark small">Tanggal Pembelajaran <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_dibuat" class="form-control rounded-3 font-medium" value="{{ date('Y-m-d') }}" required style="font-size: 13px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold text-dark small">Judul Topik / Tema <span class="text-danger">*</span></label>
                        <input type="text" name="judul_materi" class="form-control rounded-3 font-medium" placeholder="Contoh: Mewarnai Gambar Panca Indera" required style="font-size: 13px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold text-dark small">Deskripsi / Indikator <span class="text-muted font-normal">(Opsional)</span></label>
                        <textarea name="deskripsi" class="form-control rounded-3 font-medium" rows="3" placeholder="Garis besar kegiatan pembelajaran anak..." style="font-size: 13px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top p-3.5">
                    <button type="button" class="btn btn-outline-secondary rounded-3 font-semibold px-4 py-2" style="font-size: 12.5px;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 font-semibold px-4 py-2 shadow-xs" style="font-size: 12.5px;">Simpan &amp; Sync Topik</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection