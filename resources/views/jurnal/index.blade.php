@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
<<<<<<< HEAD
        <h2 class="fw-semibold text-dark mb-1">Materi & Jurnal Harian</h2>
        <span class="text-muted fs-3">Pilih materi yang tersedia untuk mengisi jurnal perkembangan anak.</span>
=======
        @if(isset($pageType) && $pageType === 'lms')
            <h2 class="fw-semibold text-dark mb-1">Manajemen Materi</h2>
            <span class="text-muted fs-3">Kelola materi pembelajaran dan modul untuk siswa.</span>
        @else
            <h2 class="fw-semibold text-dark mb-1">Jurnal Harian</h2>
            <span class="text-muted fs-3">Pilih materi yang tersedia untuk mengisi jurnal perkembangan anak.</span>
        @endif
>>>>>>> 00c4646d314d37b51f95aed1e4223ee0c2d787f3
    </div>
    
    <div class="d-flex gap-2">
        @if(auth()->user()->role == 'admin' || auth()->user()->isSuperAdmin())
            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahMateri">
                <iconify-icon icon="solar:folder-with-files-bold-duotone" class="align-middle fs-5 me-1"></iconify-icon> Buat Materi Baru
            </button>
        @endif
        <a href="{{ route('jurnal.zip') }}" class="btn btn-warning rounded-pill px-4 shadow-sm">
            <iconify-icon icon="solar:archive-bold-duotone" class="align-middle fs-5 me-1"></iconify-icon> Unduh Arsip (ZIP)
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
@endif

<div class="row">
    @forelse($materis as $materi)
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header border-0 
                    {{ $materi->kelompok == 'A' ? 'bg-primary' : ($materi->kelompok == 'B' ? 'bg-success' : 'bg-info') }} text-white p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge bg-white text-dark mb-2">Kelompok {{ $materi->kelompok }}</span>
                        @if(auth()->user()->role == 'admin')
                            <form action="{{ route('jurnal.materi.destroy', $materi->id) }}" method="POST" onsubmit="return confirm('Hapus materi ini? Semua jurnal yang terhubung juga akan terhapus lho.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger p-1 rounded-circle"><iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon></button>
                            </form>
                        @endif
                    </div>
                    <h5 class="fw-bold text-white mb-1">{{ $materi->judul_materi }}</h5>
                    <small class="opacity-75"><iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon> {{ \Carbon\Carbon::parse($materi->tanggal_dibuat)->translatedFormat('d F Y') }}</small>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-4">{{ $materi->deskripsi ?: 'Tidak ada deskripsi tambahan untuk materi ini.' }}</p>
                    
                    <h6 class="fw-bold text-dark small mb-3">Pilih Kelas untuk Isi Jurnal:</h6>
                    <div class="d-grid gap-2">
                        @php
                            // Cari kelas yang sesuai dengan kelompok materi ini
                            $kelas_match = $list_kelas->where('kelompok', $materi->kelompok);
                        @endphp
                        
                        @forelse($kelas_match as $kelas)
                            <a href="{{ route('jurnal.kelas.show', ['materi_id' => $materi->id, 'kelas_id' => $kelas->id]) }}" class="btn btn-outline-primary rounded-pill d-flex justify-content-between align-items-center px-4">
                                <span>Kelas {{ $kelas->nama_kelas }}</span>
                                <iconify-icon icon="solar:alt-arrow-right-bold-duotone"></iconify-icon>
                            </a>
                        @empty
                            <span class="text-danger small fst-italic">Belum ada kelas di kelompok ini.</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <iconify-icon icon="solar:file-smile-bold-duotone" class="fs-1 text-muted opacity-50 mb-3"></iconify-icon>
            <h5 class="text-muted fw-bold">Belum ada materi pembelajaran</h5>
            <p class="text-muted small">Admin perlu membuat materi jurnal terlebih dahulu.</p>
        </div>
    @endforelse
</div>

{{-- MODAL TAMBAH MATERI (KHUSUS ADMIN) --}}
@if(auth()->user()->role == 'admin' || auth()->user()->isSuperAdmin())
<div class="modal fade" id="modalTambahMateri" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('jurnal.materi.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-bold text-white">Buat Materi Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Target Kelompok <span class="text-danger">*</span></label>
                        <select name="kelompok" class="form-select border-primary" required>
                            <option value="A">Kelompok A (TK)</option>
                            <option value="B">Kelompok B (TK)</option>
                        </select>
                        <small class="text-muted d-block mt-1">Materi ini akan muncul di semua kelas dalam kelompok ini.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Pembelajaran <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_dibuat" class="form-control border-primary" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Materi / Tema <span class="text-danger">*</span></label>
                        <input type="text" name="judul_materi" class="form-control border-primary" placeholder="Contoh: Mewarnai Panca Indera" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi / Indikator <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Garis besar kegiatan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan Materi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection