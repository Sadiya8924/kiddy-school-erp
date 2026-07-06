@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Materi Pelajaran Anak</h3>
        <p class="text-muted mb-0">Materi yang telah dipublikasikan untuk anak Anda (Berdasarkan Kelas Aktif)</p>
    </div>
</div>

<div class="row g-4">
    @forelse($materis as $materi)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative">
            <div class="card-header border-0 bg-primary bg-gradient text-white p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-white text-primary rounded-pill">{{ $materi->mata_pelajaran }}</span>
                    <small class="opacity-75"><iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon> {{ $materi->approved_at ? $materi->approved_at->format('d M Y') : $materi->created_at->format('d M Y') }}</small>
                </div>
                <h5 class="fw-bold text-white mb-0 lh-base">{{ $materi->judul }}</h5>
            </div>
            <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center p-2">
                        <iconify-icon icon="solar:user-bold-duotone" class="fs-5"></iconify-icon>
                    </div>
                    <div>
                        <small class="text-muted d-block lh-1">Pengajar</small>
                        <span class="fw-bold text-dark">{{ $materi->creator->nama_lengkap ?? 'Guru' }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block lh-1 mb-1">Target Kelas</small>
                    <span class="badge bg-light text-dark border">{{ $materi->kelas }}</span>
                </div>
                
                <p class="text-muted small mb-4 flex-grow-1 text-truncate" style="max-height: 40px;">
                    {{ Str::limit($materi->deskripsi, 60, '...') ?: 'Tidak ada deskripsi tambahan.' }}
                </p>
                
                <button type="button" class="btn btn-primary w-100 rounded-pill shadow-sm fw-bold d-flex align-items-center justify-content-center gap-2 mt-auto" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $materi->id }}">
                    <iconify-icon icon="solar:book-bookmark-bold-duotone" class="fs-5"></iconify-icon> Buka Detail Materi
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL MATERI -->
    <div class="modal fade" id="modalDetail{{ $materi->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0 bg-primary bg-gradient p-4 pb-3">
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-white text-primary rounded-pill">{{ $materi->mata_pelajaran }}</span>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <h4 class="fw-bold text-white mb-0">{{ $materi->judul }}</h4>
                    </div>
                </div>
                <div class="modal-body p-4">
                    <div class="row mb-4">
                        <div class="col-6">
                            <small class="text-muted d-block mb-1">Dibuat Oleh</small>
                            <div class="d-flex align-items-center gap-2">
                                <iconify-icon icon="solar:user-bold-duotone" class="fs-4 text-primary"></iconify-icon>
                                <span class="fw-bold text-dark">{{ $materi->creator->nama_lengkap ?? 'Guru' }}</span>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted d-block mb-1">Tanggal Publikasi</small>
                            <span class="fw-bold text-dark">{{ $materi->approved_at ? $materi->approved_at->format('d M Y') : $materi->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-light rounded-3 p-3 mb-4">
                        <small class="text-muted d-block mb-2">Target Kelas</small>
                        <span class="badge bg-white text-dark border">{{ $materi->kelas }}</span>
                    </div>

                    <h6 class="fw-bold mb-2">Deskripsi Materi:</h6>
                    <div class="text-dark" style="line-height: 1.6;">
                        {!! nl2br(e($materi->deskripsi ?: 'Tidak ada deskripsi tambahan.')) !!}
                    </div>

                    @if($materi->file)
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="fw-bold mb-3">File Lampiran:</h6>
                        <a href="{{ asset('storage/' . $materi->file) }}" target="_blank" class="btn btn-outline-primary rounded-pill shadow-sm fw-bold d-inline-flex align-items-center gap-2 px-4">
                            <iconify-icon icon="solar:cloud-download-bold-duotone" class="fs-5"></iconify-icon> Unduh / Buka File
                        </a>
                    </div>
                    @endif
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <iconify-icon icon="solar:box-minimalistic-bold-duotone" class="display-1 text-muted opacity-25 mb-3"></iconify-icon>
                <h4 class="fw-bold text-dark">Belum Ada Materi</h4>
                <p class="text-muted">Materi pembelajaran yang telah disetujui akan muncul di sini.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection
