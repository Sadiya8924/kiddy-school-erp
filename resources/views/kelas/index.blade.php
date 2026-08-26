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
    {{-- 1. HERO WELCOME CARD WITH DYNAMIC THEME GRADIENT --}}
    {{-- ========================================================== --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="background: var(--theme-hero-gradient, linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%)) !important; border-radius: 16px !important;">
        <div class="card-body p-4 text-white">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                
                {{-- User Greeting & Context --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid rgba(255,255,255,0.4);">
                            <iconify-icon icon="solar:black-hole-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Manajemen Kelas &amp; Rombel</h5>
                            @if(isset($list_kelas) && count($list_kelas) > 0)
                                <span class="badge bg-white text-dark bg-opacity-25 px-2.5 py-1 rounded-pill small font-semibold" style="font-size: 11px;">
                                    {{ count($list_kelas) }} Kelas Terdaftar
                                </span>
                            @endif
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Kelola daftar rombongan belajar, penataan tingkat pendidikan, dan anggota kelas.</span>
                        </p>
                    </div>
                </div>

                {{-- Action Controls --}}
                @if(auth()->user()->role !== 'kepsek')
                <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                    <button class="btn btn-warning text-dark rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                        <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon> + Tambah Kelas Baru
                    </button>
                </div>
                @endif

            </div>
        </div>
    </div>

    @include('partials.owner_stepped_filter')

    @if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))
    
    {{-- SEARCH & FILTER BAR FOR CLASSES --}}
    @if(count($list_kelas) > 0)
    <div class="card border shadow-sm mb-3.5" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
        <div class="card-body p-3.5">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:filter-bold-duotone" class="text-primary fs-5"></iconify-icon>
                    <span class="fw-bold text-dark small mb-0" style="font-size: 13px;">Daftar Rombongan Belajar</span>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="input-group input-group-sm" style="max-width: 280px;">
                        <span class="input-group-text bg-white border-end-0 text-muted"><iconify-icon icon="solar:magnifer-linear"></iconify-icon></span>
                        <input type="text" id="searchKelasInput" class="form-control border-start-0 shadow-none ps-0" placeholder="Cari nama kelas..." style="font-size: 12.5px;" onkeyup="filterKelasCards()">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- KARTU DAFTAR KELAS --}}
    <div class="row g-4" id="kelasContainer">
        @forelse($list_kelas as $kelas)
            @php
                $tingkatUpper = strtoupper($kelas->tingkat ?? '');
                $badgeStyle = 'bg-primary-subtle text-primary';
                if (str_contains($tingkatUpper, 'KB')) {
                    $badgeStyle = 'bg-warning-subtle text-warning-emphasis';
                } elseif (str_contains($tingkatUpper, 'TK A')) {
                    $badgeStyle = 'bg-info-subtle text-info-emphasis';
                } elseif (str_contains($tingkatUpper, 'TK B')) {
                    $badgeStyle = 'bg-success-subtle text-success-emphasis';
                }
            @endphp
            <div class="col-md-6 col-lg-4 kelas-item" data-nama="{{ strtolower($kelas->nama_kelas) }}" data-tingkat="{{ strtolower($kelas->tingkat ?? '') }}">
                <div class="card border shadow-sm h-100 position-relative overflow-hidden card-clickable" style="border-radius: 16px !important; border-top: 3.5px solid var(--primary-blue) !important;">
                    <div class="card-body p-4 text-center d-flex flex-column justify-content-between">
                        <div>
                            {{-- Header Badges --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge {{ $badgeStyle }} rounded-pill px-3 py-1 font-semibold" style="font-size: 11px;">
                                    <iconify-icon icon="solar:bookmark-square-bold-duotone" class="align-middle me-1"></iconify-icon> Tingkat: {{ $kelas->tingkat ?? '-' }}
                                </span>

                                @if(auth()->user()->isSuperAdmin() && !$active_school_id)
                                    <span class="badge bg-slate-100 text-dark border rounded-pill px-2.5 py-1 font-semibold" style="font-size: 10.5px;">
                                        <iconify-icon icon="solar:buildings-bold-duotone" class="align-middle me-1 text-primary"></iconify-icon> {{ $kelas->school->nama_sekolah ?? 'Tanpa Sekolah' }}
                                    </span>
                                @endif
                            </div>

                            {{-- Class Icon Circle --}}
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-xs" style="width: 62px; height: 62px; background-color: var(--primary-blue-soft); color: var(--primary-blue);">
                                <iconify-icon icon="solar:black-hole-bold-duotone" style="font-size: 30px;"></iconify-icon>
                            </div>
                            
                            {{-- Class Name --}}
                            <h5 class="fw-bold text-dark mb-1" style="font-size: 16.5px;">{{ $kelas->nama_kelas }}</h5>
                            
                            {{-- Stat Box for Active Students --}}
                            <div class="p-3.5 rounded-3 bg-slate-50 border border-slate-100 mb-3.5 mt-3">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-primary fs-3"></iconify-icon>
                                    <span class="fw-bold text-dark fs-2 mb-0 me-1">{{ $kelas->siswas_count ?? $kelas->siswas()->count() }}</span>
                                </div>
                                <span class="text-muted font-semibold d-block mt-0.5" style="font-size: 11.5px;">Total Siswa Terdaftar</span>
                            </div>
                        </div>
                        
                        {{-- Action Buttons --}}
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('kelas.show', $kelas->id) }}" class="btn btn-primary rounded-3 font-semibold py-2 shadow-xs d-flex align-items-center justify-content-center gap-1.5" style="font-size: 12.5px;">
                                <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-5"></iconify-icon> Lihat Daftar Siswa
                            </a>
                            
                            @if(auth()->user()->role !== 'kepsek')
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 w-50 py-1.5 font-semibold d-flex align-items-center justify-content-center gap-1" style="font-size: 11.5px;" data-bs-toggle="modal" data-bs-target="#modalEditKelas{{ $kelas->id }}">
                                    <iconify-icon icon="solar:pen-bold-duotone" class="fs-5 text-primary"></iconify-icon> Edit
                                </button>
                                
                                <form action="{{ route('kelas.destroy', $kelas->id) }}" method="POST" class="m-0 w-50" onsubmit="return confirm('Yakin ingin menghapus kelas {{ $kelas->nama_kelas }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 w-100 py-1.5 font-semibold d-flex align-items-center justify-content-center gap-1" style="font-size: 11.5px;">
                                        <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-5"></iconify-icon> Hapus
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL EDIT KELAS --}}
            @if(auth()->user()->role !== 'kepsek')
            <div class="modal fade text-start" id="modalEditKelas{{ $kelas->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow-lg">
                        <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header bg-slate-50 border-bottom p-3.5">
                                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                                    <iconify-icon icon="solar:pen-bold-duotone" class="text-primary fs-5"></iconify-icon> Edit Data Kelas
                                </h6>
                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label font-semibold text-dark small">Nama Kelas <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_kelas" class="form-control rounded-3" value="{{ $kelas->nama_kelas }}" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label font-semibold text-dark small">Tingkat Pendidikan <span class="text-danger">*</span></label>
                                    <select name="tingkat" class="form-select rounded-3 font-medium">
                                        <option value="KB" {{ $kelas->tingkat == 'KB' ? 'selected' : '' }}>Kelompok Bermain (KB)</option>
                                        <option value="TK A" {{ $kelas->tingkat == 'TK A' ? 'selected' : '' }}>TK A</option>
                                        <option value="TK B" {{ $kelas->tingkat == 'TK B' ? 'selected' : '' }}>TK B</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer border-top p-3.5">
                                <button type="button" class="btn btn-outline-secondary rounded-3 font-semibold px-4 py-2" data-bs-dismiss="modal" style="font-size: 12.5px;">Batal</button>
                                <button type="submit" class="btn btn-primary rounded-3 font-semibold px-4 py-2 shadow-xs d-inline-flex align-items-center gap-1.5" style="font-size: 12.5px;">
                                    <iconify-icon icon="solar:diskette-bold-duotone" class="fs-5"></iconify-icon> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="col-12 text-center py-5">
                <div class="card border shadow-sm" style="border-radius: 16px !important; border-top: 3.5px solid var(--primary-blue) !important;">
                    <div class="card-body py-5">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                            <iconify-icon icon="solar:folder-error-bold-duotone" class="fs-2"></iconify-icon>
                        </div>
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 15px;">Belum Ada Kelas Terdaftar</h6>
                        <p class="text-muted small mb-0">Belum ada rombongan belajar yang dibuat untuk sekolah ini.</p>
                        @if(auth()->user()->role !== 'kepsek')
                        <small class="text-muted d-block mt-1" style="font-size: 11.5px;">Klik tombol "+ Tambah Kelas Baru" di kanan atas untuk membuat kelas baru.</small>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    @endif

</div>

{{-- MODAL TAMBAH KELAS HANYA DI-RENDER JIKA BUKAN KEPSEK --}}
@if(auth()->user()->role !== 'kepsek')
<div class="modal fade text-start" id="modalTambahKelas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-slate-50 border-bottom p-3.5">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                        <iconify-icon icon="solar:add-square-bold-duotone" class="text-primary fs-5"></iconify-icon> Buat Kelas Baru
                    </h6>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    
                    {{-- DROPDOWN PILIH SEKOLAH KHUSUS SUPER ADMIN --}}
                    @if(auth()->user()->isSuperAdmin())
                        <div class="mb-3">
                            <label class="form-label font-semibold text-dark small">Pilih Sekolah <span class="text-danger">*</span></label>
                            <select name="school_id" class="form-select rounded-3 font-medium" required>
                                <option value="" disabled {{ !$active_school_id ? 'selected' : '' }}>-- Tentukan Sekolah --</option>
                                @foreach($schools as $s)
                                    <option value="{{ $s->id }}" {{ $active_school_id == $s->id ? 'selected' : '' }}>{{ $s->nama_sekolah }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label font-semibold text-dark small">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kelas" class="form-control rounded-3" placeholder="Contoh: TK A Semangka" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label font-semibold text-dark small">Tingkat Pendidikan <span class="text-danger">*</span></label>
                        <select name="tingkat" class="form-select rounded-3 font-medium">
                            <option value="KB">Kelompok Bermain (KB)</option>
                            <option value="TK A">TK A</option>
                            <option value="TK B">TK B</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top p-3.5">
                    <button type="button" class="btn btn-outline-secondary rounded-3 font-semibold px-4 py-2" data-bs-dismiss="modal" style="font-size: 12.5px;">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 font-semibold px-4 py-2 shadow-xs d-inline-flex align-items-center gap-1.5" style="font-size: 12.5px;">
                        <iconify-icon icon="solar:diskette-bold-duotone" class="fs-5"></iconify-icon> Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
function filterKelasCards() {
    const val = document.getElementById('searchKelasInput').value.toLowerCase();
    const items = document.querySelectorAll('.kelas-item');
    items.forEach(el => {
        const nama = el.getAttribute('data-nama') || '';
        const tingkat = el.getAttribute('data-tingkat') || '';
        if (nama.includes(val) || tingkat.includes(val)) {
            el.style.display = 'block';
        } else {
            el.style.display = 'none';
        }
    });
}
</script>
@endsection