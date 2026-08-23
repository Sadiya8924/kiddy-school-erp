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
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Manajemen Kelas</h5>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Buat dan kelola daftar rombongan belajar di sekolah Anda.</span>
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
    {{-- KARTU DAFTAR KELAS --}}
    <div class="row g-4">
        @forelse($list_kelas as $kelas)
            <div class="col-md-6 col-lg-4">
                <div class="card border shadow-sm h-100 position-relative overflow-hidden" style="border-radius: 16px !important; border-top: 3.5px solid #3b82f6 !important;">
                    <div class="card-body p-4 text-center d-flex flex-column justify-content-between">
                        <div>
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-xs" style="width: 58px; height: 58px; background-color: #eff6ff; color: #2563eb;">
                                <iconify-icon icon="solar:black-hole-bold-duotone" style="font-size: 28px;"></iconify-icon>
                            </div>
                            
                            <h5 class="fw-bold text-dark mb-1" style="font-size: 16px;">{{ $kelas->nama_kelas }}</h5>
                            
                            {{-- MUNCULKAN NAMA SEKOLAH JIKA SUPER ADMIN SEDANG DI MODE PUSAT --}}
                            @if(auth()->user()->isSuperAdmin() && !$active_school_id)
                                <div class="mb-2">
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-2.5 py-1 font-semibold" style="font-size: 10.5px;">
                                        <iconify-icon icon="solar:buildings-bold-duotone" class="align-middle me-1"></iconify-icon> {{ $kelas->school->nama_sekolah ?? 'Tanpa Sekolah' }}
                                    </span>
                                </div>
                            @endif

                            <span class="badge bg-slate-100 text-muted border border-slate-200 rounded-pill mb-3 px-3 py-1 font-semibold" style="font-size: 11px;">
                                Tingkat: {{ $kelas->tingkat ?? '-' }}
                            </span>
                            
                            <div class="p-3 rounded-3 bg-slate-50 border border-slate-100 mb-3.5">
                                <span class="d-block fw-bold text-dark fs-3 mb-0">{{ $kelas->siswas_count ?? $kelas->siswas()->count() }}</span>
                                <span class="text-muted font-semibold" style="font-size: 11px;">Total Siswa Aktif</span>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('kelas.show', $kelas->id) }}" class="btn btn-outline-primary rounded-3 font-semibold py-2 shadow-xs d-flex align-items-center justify-content-center gap-1.5" style="font-size: 12.5px;">
                                <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-5"></iconify-icon> Lihat Daftar Siswa
                            </a>
                            
                            {{-- Tombol Hapus Kelas HANYA muncul jika BUKAN Kepsek --}}
                            @if(auth()->user()->role !== 'kepsek')
                            <form action="{{ route('kelas.destroy', $kelas->id) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger text-decoration-none btn-sm font-semibold w-100 py-1 d-flex align-items-center justify-content-center gap-1" style="font-size: 12px;">
                                    <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-5"></iconify-icon> Hapus Kelas
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="card border shadow-sm" style="border-radius: 16px !important;">
                    <div class="card-body py-5">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                            <iconify-icon icon="solar:folder-error-bold-duotone" class="fs-2"></iconify-icon>
                        </div>
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada Kelas</h6>
                        <p class="text-muted small mb-0">Belum ada kelas yang dibuat untuk sekolah ini.</p>
                        @if(auth()->user()->role !== 'kepsek')
                        <small class="text-muted d-block mt-1" style="font-size: 11.5px;">Klik tombol "+ Tambah Kelas Baru" di bagian kanan atas untuk membuat kelas.</small>
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
<div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-slate-50 border-bottom p-3.5">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                        <iconify-icon icon="solar:black-hole-bold-duotone" class="text-primary fs-5"></iconify-icon> Buat Kelas Baru
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
@endsection