@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        @if(isset($pageType) && $pageType === 'lms')
            <h2 class="fw-bold text-dark mb-1">Manajemen Materi LMS</h2>
            <span class="text-muted fs-3">Kelola materi pembelajaran dan modul untuk siswa secara terstruktur.</span>
        @else
            <h2 class="fw-bold text-dark mb-1">Jurnal Harian Siswa</h2>
            <span class="text-muted fs-3">Pilih tanggal dan topik materi untuk mengisikan catatan jurnal perkembangan anak.</span>
        @endif
    </div>
    
    <div class="d-flex gap-2 flex-wrap">
        @if(auth()->user()->role == 'admin' || auth()->user()->isSuperAdmin() || auth()->user()->role == 'guru')
            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahMateri">
                <iconify-icon icon="solar:add-circle-bold-duotone" class="fs-5"></iconify-icon> Buat Topik Jurnal Baru
            </button>
        @endif
        <button type="button" class="btn btn-info text-white rounded-pill px-4 shadow-sm fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalQuickViewJurnal">
            <iconify-icon icon="solar:eye-bold-duotone" class="fs-5"></iconify-icon> Lihat Rekap Jurnal
        </button>
        <a href="{{ route('jurnal.zip') }}" class="btn btn-warning rounded-pill px-4 shadow-sm fw-bold d-flex align-items-center gap-2">
            <iconify-icon icon="solar:archive-bold-duotone" class="fs-5"></iconify-icon> Unduh Arsip (ZIP)
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <iconify-icon icon="solar:check-circle-bold" class="fs-5"></iconify-icon>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <iconify-icon icon="solar:danger-circle-bold" class="fs-5"></iconify-icon>
            <div>{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@include('partials.owner_stepped_filter')

@if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))
{{-- RINGKASAN STATISTIK QUICK VIEW --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-4 p-3 d-flex align-items-center justify-content-center">
                    <iconify-icon icon="solar:folder-with-files-bold-duotone" class="fs-3"></iconify-icon>
                </div>
                <div>
                    <small class="text-muted fw-semibold d-block">Total Topik</small>
                    <h4 class="fw-bold text-dark mb-0">{{ $stats['total'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 text-success rounded-4 p-3 d-flex align-items-center justify-content-center">
                    <iconify-icon icon="solar:calendar-today-bold-duotone" class="fs-3"></iconify-icon>
                </div>
                <div>
                    <small class="text-muted fw-semibold d-block">Hari Ini</small>
                    <h4 class="fw-bold text-dark mb-0">{{ $stats['today'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="bg-info bg-opacity-10 text-info rounded-4 p-3 d-flex align-items-center justify-content-center">
                    <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-3"></iconify-icon>
                </div>
                <div>
                    <small class="text-muted fw-semibold d-block">Kelompok A</small>
                    <h4 class="fw-bold text-dark mb-0">{{ $stats['kelompok_a'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="bg-warning bg-opacity-10 text-warning rounded-4 p-3 d-flex align-items-center justify-content-center">
                    <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-3"></iconify-icon>
                </div>
                <div>
                    <small class="text-muted fw-semibold d-block">Kelompok B</small>
                    <h4 class="fw-bold text-dark mb-0">{{ $stats['kelompok_b'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER BAR INTUITIF --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('jurnal.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">
                    <iconify-icon icon="solar:calendar-bold-duotone" class="align-middle me-1 text-primary"></iconify-icon> Filter Tanggal:
                </label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control rounded-pill border-secondary" onchange="this.form.submit()">
            </div>
            
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">
                    <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="align-middle me-1 text-primary"></iconify-icon> Filter Kelompok:
                </label>
                <select name="kelompok" class="form-select rounded-pill border-secondary" onchange="this.form.submit()">
                    <option value="semua" {{ request('kelompok') == 'semua' || !request('kelompok') ? 'selected' : '' }}>Semua Kelompok</option>
                    <option value="A" {{ request('kelompok') == 'A' ? 'selected' : '' }}>Kelompok A (TK)</option>
                    <option value="B" {{ request('kelompok') == 'B' ? 'selected' : '' }}>Kelompok B (TK)</option>
                    <option value="PAUD" {{ request('kelompok') == 'PAUD' ? 'selected' : '' }}>PAUD</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted mb-1">
                    <iconify-icon icon="solar:magnifer-bold-duotone" class="align-middle me-1 text-primary"></iconify-icon> Cari Topik / Tema:
                </label>
                <div class="input-group">
                    <input type="text" name="search" class="form-control rounded-start-pill border-secondary" placeholder="Ketik kata kunci tema..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary rounded-end-pill px-3 shadow-none">
                        <iconify-icon icon="solar:magnifer-linear" class="align-middle"></iconify-icon> Cari
                    </button>
                </div>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <a href="{{ route('jurnal.index', ['tanggal' => date('Y-m-d')]) }}" class="btn btn-outline-primary rounded-pill w-100 fw-semibold {{ request('tanggal') == date('Y-m-d') ? 'active' : '' }}" title="Tampilkan materi hari ini">
                    Hari Ini
                </a>
                @if(request('tanggal') || (request('kelompok') && request('kelompok') != 'semua') || request('search'))
                    <a href="{{ route('jurnal.index') }}" class="btn btn-light text-danger rounded-pill px-3" title="Reset Filter">
                        <iconify-icon icon="solar:restart-bold" class="align-middle fs-5"></iconify-icon>
                    </a>
                @endif
            </div>
        </form>

        {{-- PILLS INDIKATOR FILTER AKTIF --}}
        @if(request('tanggal') || (request('kelompok') && request('kelompok') != 'semua') || request('search'))
            <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top flex-wrap">
                <span class="small text-muted fw-semibold">Filter Aktif:</span>
                @if(request('tanggal'))
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                        Tanggal: {{ \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') }}
                        <a href="{{ route('jurnal.index', request()->except('tanggal')) }}" class="text-primary ms-1"><iconify-icon icon="solar:close-circle-bold"></iconify-icon></a>
                    </span>
                @endif
                @if(request('kelompok') && request('kelompok') != 'semua')
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                        Kelompok: {{ request('kelompok') }}
                        <a href="{{ route('jurnal.index', request()->except('kelompok')) }}" class="text-success ms-1"><iconify-icon icon="solar:close-circle-bold"></iconify-icon></a>
                    </span>
                @endif
                @if(request('search'))
                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                        Pencarian: "{{ request('search') }}"
                        <a href="{{ route('jurnal.index', request()->except('search')) }}" class="text-info ms-1"><iconify-icon icon="solar:close-circle-bold"></iconify-icon></a>
                    </span>
                @endif
                <a href="{{ route('jurnal.index') }}" class="small text-danger text-decoration-none ms-auto fw-bold">Bersihkan Semua</a>
            </div>
        @endif
    </div>
</div>

{{-- DAFTAR MATERI / TOPIK JURNAL --}}
<div class="row">
    @forelse($materis as $materi)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative hover-shadow transition-all">
                <div class="card-header border-0 
                    {{ $materi->kelompok == 'A' ? 'bg-primary' : ($materi->kelompok == 'B' ? 'bg-success' : 'bg-info') }} text-white p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-white text-dark fw-bold rounded-pill">
                            <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="align-middle me-1"></iconify-icon>
                            Kelompok {{ $materi->kelompok }}
                        </span>
                        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'guru' || auth()->user()->isSuperAdmin())
                            <form action="{{ route('jurnal.materi.destroy', $materi->id) }}" method="POST" onsubmit="return confirm('Hapus topik ini? Semua jurnal siswa yang terhubung akan terhapus.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger p-1 rounded-circle shadow-none" title="Hapus Topik">
                                    <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                </button>
                            </form>
                        @endif
                    </div>
                    <h5 class="fw-bold text-white mb-1 text-truncate" title="{{ $materi->judul_materi }}">{{ $materi->judul_materi }}</h5>
                    <div class="d-flex align-items-center gap-2 small opacity-90">
                        <iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon>
                        <span>{{ \Carbon\Carbon::parse($materi->tanggal_dibuat)->translatedFormat('d F Y') }}</span>
                    </div>
                </div>

                <div class="card-body p-4 d-flex flex-column">
                    <p class="text-muted small mb-3 flex-grow-1 text-truncate-2" style="min-height: 40px;">
                        {{ $materi->deskripsi ?: 'Tidak ada deskripsi tambahan untuk topik ini.' }}
                    </p>
                    
                    <div class="bg-light p-3 rounded-3 mb-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:notebook-bold-duotone" class="fs-4 text-primary"></iconify-icon>
                            <small class="text-muted fw-semibold">Jurnal Terisi:</small>
                        </div>
                        <span class="badge bg-primary rounded-pill px-3 py-2 fs-3 fw-bold">{{ $materi->total_jurnal ?? 0 }} Siswa</span>
                    </div>

                    <h6 class="fw-bold text-dark small mb-2 d-flex align-items-center gap-1">
                        <iconify-icon icon="solar:eye-bold-duotone" class="text-primary"></iconify-icon> Detail Jurnal Per Kelas:
                    </h6>
                    
                    <div class="d-grid gap-2">
                        @php
                            $kelas_match = $list_kelas->where('kelompok', $materi->kelompok);
                        @endphp
                        
                        @forelse($kelas_match as $kelas)
                            <a href="{{ route('jurnal.kelas.show', ['materi_id' => $materi->id, 'kelas_id' => $kelas->id]) }}" class="btn btn-outline-primary rounded-pill d-flex justify-content-between align-items-center px-3 py-2 fw-semibold">
                                <span><iconify-icon icon="solar:eye-bold-duotone" class="align-middle me-1 text-primary"></iconify-icon> Lihat Jurnal - Kelas {{ $kelas->nama_kelas }}</span>
                                <iconify-icon icon="solar:alt-arrow-right-bold-duotone" class="fs-5"></iconify-icon>
                            </a>
                        @empty
                            <span class="text-danger small fst-italic py-2 text-center">Belum ada kelas terdaftar di kelompok ini.</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="card border-0 shadow-sm rounded-4 p-5 bg-white">
                <div class="card-body">
                    <iconify-icon icon="solar:file-remove-bold-duotone" class="display-1 text-muted opacity-25 mb-3"></iconify-icon>
                    <h4 class="text-dark fw-bold mb-2">Tidak Ada Topik Jurnal</h4>
                    <p class="text-muted small mb-4">
                        @if(request('tanggal') || request('kelompok') || request('search'))
                            Tidak ditemukan materi kegiatan jurnal yang sesuai dengan filter pilihan Anda.
                        @else
                            Belum ada topik kegiatan jurnal yang dibuat. Silakan tambahkan topik baru untuk memulai.
                        @endif
                    </p>
                    @if(request('tanggal') || request('kelompok') || request('search'))
                        <a href="{{ route('jurnal.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                            <iconify-icon icon="solar:restart-bold" class="align-middle me-1"></iconify-icon> Reset Filter Pilihan
                        </a>
                    @else
                        @if(auth()->user()->role == 'admin' || auth()->user()->isSuperAdmin() || auth()->user()->role == 'guru')
                            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahMateri">
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

{{-- MODAL QUICK VIEW JURNAL HARIAN --}}
<div class="modal fade" id="modalQuickViewJurnal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-info text-white rounded-top-4 p-4">
                <div class="d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:eye-bold-duotone" class="fs-3"></iconify-icon>
                    <h5 class="modal-title fw-bold text-white mb-0">Detail Jurnal Harian Siswa</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">Pilih topik materi dan kelas di bawah ini untuk melihat seluruh isian jurnal perkembangan harian anak:</p>
                <div class="list-group rounded-3 shadow-sm border-0">
                    @forelse($materis as $m)
                        @php $kMatch = $list_kelas->where('kelompok', $m->kelompok); @endphp
                        @foreach($kMatch as $k)
                            <a href="{{ route('jurnal.kelas.show', ['materi_id' => $m->id, 'kelas_id' => $k->id]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">{{ $m->judul_materi }}</h6>
                                    <small class="text-muted">
                                        <iconify-icon icon="solar:calendar-bold-duotone" class="align-middle text-primary me-1"></iconify-icon> 
                                        {{ \Carbon\Carbon::parse($m->tanggal_dibuat)->translatedFormat('d M Y') }} | 
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2">Kelas {{ $k->nama_kelas }}</span>
                                    </small>
                                </div>
                                <span class="btn btn-sm btn-outline-info rounded-pill px-3 fw-semibold">
                                    <iconify-icon icon="solar:eye-bold-duotone" class="align-middle me-1"></iconify-icon> Lihat Detail
                                </span>
                            </a>
                        @endforeach
                    @empty
                        <div class="p-4 text-center text-muted">Belum ada data jurnal.</div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer border-0 bg-light rounded-bottom-4 p-3 px-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH MATERI (KHUSUS ADMIN/GURU) --}}
@if(auth()->user()->role == 'admin' || auth()->user()->isSuperAdmin() || auth()->user()->role == 'guru')
<div class="modal fade" id="modalTambahMateri" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('jurnal.materi.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white rounded-top-4 p-4">
                    <div class="d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:folder-with-files-bold-duotone" class="fs-3"></iconify-icon>
                        <h5 class="modal-title fw-bold text-white mb-0">Buat Topik Kegiatan Jurnal</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Target Kelompok <span class="text-danger">*</span></label>
                        <select name="kelompok" class="form-select rounded-3 border-secondary" required>
                            <option value="A">Kelompok A (TK)</option>
                            <option value="B">Kelompok B (TK)</option>
                            <option value="PAUD">PAUD</option>
                        </select>
                        <small class="text-muted d-block mt-1">Topik ini akan berlaku untuk seluruh kelas dalam kelompok ini.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Pembelajaran <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_dibuat" class="form-control rounded-3 border-secondary" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Topik / Tema <span class="text-danger">*</span></label>
                        <input type="text" name="judul_materi" class="form-control rounded-3 border-secondary" placeholder="Contoh: Mewarnai Gambar Panca Indera" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi / Indikator <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea name="deskripsi" class="form-control rounded-3 border-secondary" rows="3" placeholder="Garis besar kegiatan pembelajaran anak..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-4 p-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Simpan & Sync Topik</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection