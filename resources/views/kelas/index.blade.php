@extends('layouts.admin')

@section('konten_halaman')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-semibold text-dark mb-1">Manajemen Kelas</h2>
                <span class="text-muted fs-3">Buat dan kelola daftar kelas di sekolah Anda.</span>
            </div>
            <button class="btn btn-primary rounded-pill px-4 d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                <iconify-icon icon="solar:add-circle-bold-duotone" class="fs-5"></iconify-icon> Tambah Kelas
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <iconify-icon icon="solar:check-circle-bold" class="fs-6 me-2"></iconify-icon>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <iconify-icon icon="solar:danger-circle-bold" class="fs-6 me-2"></iconify-icon>
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @forelse($list_kelas as $kelas)
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <iconify-icon icon="solar:globus-bold-duotone" style="font-size: 35px;"></iconify-icon>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ $kelas->nama_kelas }}</h4>
                    <p class="badge bg-light text-primary border border-primary-subtle rounded-pill mb-3 px-3 py-2 fs-6">
                        Tingkat: {{ $kelas->tingkat ?? '-' }}
                    </p>
                    
                    <div class="d-flex justify-content-center border-top pt-3 mt-2 mb-3">
                        <div class="text-center px-3">
                            <span class="d-block fw-bold text-dark fs-4">{{ $kelas->siswas_count ?? $kelas->siswas()->count() }}</span>
                            <span class="text-muted small">Total Siswa</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('kelas.show', $kelas->id) }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-medium">
                        Lihat Daftar Siswa
                    </a>

                    <div class="mt-2">
                        <form action="{{ route('kelas.destroy', $kelas->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger text-decoration-none btn-sm fw-medium">
                                <iconify-icon icon="solar:trash-bin-trash-bold" class="align-middle me-1"></iconify-icon> Hapus Kelas
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <iconify-icon icon="solar:folder-error-bold-duotone" class="text-muted opacity-25 mb-3" style="font-size: 80px;"></iconify-icon>
                    <p class="text-muted mb-0 fs-4">Belum ada kelas yang dibuat.</p>
                    <small>Klik tombol "Tambah Kelas" untuk memulai.</small>
                </div>
            </div>
        </div>
    @endforelse
</div>

<div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0">Buat Kelas Baru</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control border-primary shadow-none" placeholder="Contoh: TK A Semangka" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary">Tingkat</label>
                        <select name="tingkat" class="form-select border-primary shadow-none">
                            <option value="KB">Kelompok Bermain (KB)</option>
                            <option value="TK A">TK A</option>
                            <option value="TK B">TK B</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-none">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection