@extends('layouts.admin')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    <div class="col-12 mb-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Manajemen Kelas</h4>
                <p class="text-muted mb-0 small">Buat dan kelola daftar rombongan belajar di sekolah Anda.</p>
            </div>
            
            {{-- Tombol Tambah HANYA muncul jika BUKAN Kepsek --}}
            @if(auth()->user()->role !== 'kepsek')
            <button class="btn btn-primary rounded px-4 py-2 d-flex align-items-center gap-2 shadow-sm fw-medium" style="font-size: 13px;" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                <iconify-icon icon="solar:add-circle-bold-duotone" class="fs-5"></iconify-icon> Tambah Kelas Baru
            </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="col-12 mt-0">
            <div class="alert alert-success border border-success-subtle shadow-none rounded py-2 px-3 mb-0 small fw-medium">{{ session('success') }}</div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="col-12 mt-0">
            <div class="alert alert-danger border border-danger-subtle shadow-none rounded py-2 px-3 mb-0 small fw-medium">{{ session('error') }}</div>
        </div>
    @endif

    @include('partials.owner_stepped_filter')

    @if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))
    {{-- KARTU DAFTAR KELAS --}}
    @forelse($list_kelas as $kelas)
        <div class="col-md-4">
            <div class="card border shadow-none h-100 position-relative overflow-hidden">
                <div class="card-body p-4 text-center">
                    <div class="rounded d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 60px; height: 60px; background-color: var(--primary-blue-soft); color: var(--primary-blue);">
                        <iconify-icon icon="solar:black-hole-bold-duotone" style="font-size: 30px;"></iconify-icon>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1">{{ $kelas->nama_kelas }}</h5>
                    
                    {{-- MUNCULKAN NAMA SEKOLAH JIKA SUPER ADMIN SEDANG DI MODE PUSAT --}}
                    @if(auth()->user()->isSuperAdmin() && !$active_school_id)
                        <div class="mb-2">
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1" style="font-size: 10px;">
                                <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon> {{ $kelas->school->nama_sekolah ?? 'Tanpa Sekolah' }}
                            </span>
                        </div>
                    @endif

                    <span class="badge bg-light text-muted border border-light-subtle rounded-pill mb-3 px-3 py-1" style="font-size: 11px;">
                        Tingkat: {{ $kelas->tingkat ?? '-' }}
                    </span>
                    
                    <div class="d-flex justify-content-center border-top pt-3 mt-2 mb-4">
                        <div class="text-center px-3">
                            <span class="d-block fw-bold text-dark fs-3">{{ $kelas->siswas_count ?? $kelas->siswas()->count() }}</span>
                            <span class="text-muted fw-medium" style="font-size: 11px;">Total Siswa</span>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('kelas.show', $kelas->id) }}" class="btn btn-outline-primary bg-light border-primary-subtle border text-primary btn-sm rounded fw-bold py-2 shadow-none" style="font-size: 12px;">
                            Lihat Daftar Siswa
                        </a>
                        
                        {{-- Tombol Hapus Kelas HANYA muncul jika BUKAN Kepsek --}}
                        @if(auth()->user()->role !== 'kepsek')
                        <form action="{{ route('kelas.destroy', $kelas->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger text-decoration-none btn-sm fw-medium w-100" style="font-size: 12px;">
                                <iconify-icon icon="solar:trash-bin-trash-bold" class="align-middle me-1"></iconify-icon> Hapus Kelas
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border shadow-none">
                <div class="card-body text-center py-5">
                    <iconify-icon icon="solar:folder-error-bold-duotone" class="text-muted opacity-25 mb-3" style="font-size: 80px;"></iconify-icon>
                    <p class="text-muted mb-0 fw-medium" style="font-size: 15px;">Belum ada kelas yang dibuat.</p>
                    @if(auth()->user()->role !== 'kepsek')
                    <small class="text-muted" style="font-size: 12px;">Klik tombol "Tambah Kelas Baru" di pojok kanan atas untuk memulai.</small>
                    @endif
                </div>
            </div>
        </div>
    @endforelse
    @endif
</div>

{{-- MODAL TAMBAH KELAS HANYA DI-RENDER JIKA BUKAN KEPSEK --}}
@if(auth()->user()->role !== 'kepsek')
<div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded border shadow-sm">
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light border-bottom p-3">
                    <h6 class="fw-bold mb-0 text-dark">Buat Kelas Baru</h6>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    
                    {{-- DROPDOWN PILIH SEKOLAH KHUSUS SUPER ADMIN --}}
                    @if(auth()->user()->isSuperAdmin())
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Pilih Sekolah <span class="text-danger">*</span></label>
                            <select name="school_id" class="form-select border-light-subtle shadow-none bg-light" required>
                                <option value="" disabled {{ !$active_school_id ? 'selected' : '' }}>-- Tentukan Sekolah --</option>
                                @foreach($schools as $s)
                                    <option value="{{ $s->id }}" {{ $active_school_id == $s->id ? 'selected' : '' }}>{{ $s->nama_sekolah }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kelas" class="form-control border-light-subtle shadow-none bg-light" placeholder="Contoh: TK A Semangka" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Tingkat Pendidikan <span class="text-danger">*</span></label>
                        <select name="tingkat" class="form-select border-light-subtle shadow-none bg-light">
                            <option value="KB">Kelompok Bermain (KB)</option>
                            <option value="TK A">TK A</option>
                            <option value="TK B">TK B</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-outline-secondary rounded fw-medium px-4" data-bs-dismiss="modal" style="font-size: 12px;">Batal</button>
                    <button type="submit" class="btn btn-primary rounded fw-bold px-4 shadow-none" style="font-size: 12px;">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection