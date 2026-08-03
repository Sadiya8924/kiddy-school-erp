@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Manajemen Materi (Guru)</h3>
        <p class="text-muted mb-0">Kelola dan unggah materi pembelajaran Anda</p>
    </div>
    <a href="{{ route('materi.guru.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
        <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon> Tambah Materi
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
    {{ session('success') }}
</div>
@endif
@include('partials.owner_stepped_filter')

@if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <p class="text-muted small fw-semibold mb-1 uppercase">Total Materi</p>
                <h3 class="fw-bold mb-0 text-primary">{{ $stats['total'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <p class="text-muted small fw-semibold mb-1 uppercase">Menunggu Persetujuan</p>
                <h3 class="fw-bold mb-0 text-warning">{{ $stats['pending'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <p class="text-muted small fw-semibold mb-1 uppercase">Disetujui</p>
                <h3 class="fw-bold mb-0 text-success">{{ $stats['approved'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <p class="text-muted small fw-semibold mb-1 uppercase">Ditolak</p>
                <h3 class="fw-bold mb-0 text-danger">{{ $stats['rejected'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Judul Materi</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materis as $materi)
                    <tr data-bs-toggle="modal" data-bs-target="#modalDetail{{ $materi->id }}" style="cursor: pointer;" class="table-hover-custom" title="Klik untuk lihat detail materi">
                        <td class="ps-4">
                            <span class="fw-bold text-dark">{{ $materi->judul }}</span>
                            @if($materi->status == 'Rejected')
                                <br><small class="text-danger"><iconify-icon icon="solar:danger-circle-bold"></iconify-icon> Catatan: {{ $materi->rejection_note }}</small>
                            @endif
                        </td>
                        <td>{{ $materi->mata_pelajaran }}</td>
                        <td>{{ $materi->kelas }}</td>
                        <td>{{ $materi->created_at->format('d M Y') }}</td>
                        <td>
                            @if($materi->status == 'Pending')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                            @elseif($materi->status == 'Approved' || $materi->status == 'Published')
                                <span class="badge bg-success px-3 py-2 rounded-pill">Disetujui</span>
                            @elseif($materi->status == 'Rejected')
                                <span class="badge bg-danger px-3 py-2 rounded-pill">Ditolak</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $materi->status }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <iconify-icon icon="solar:alt-arrow-right-bold-duotone" class="text-muted fs-5 mt-1"></iconify-icon>
                        </td>
                    </tr>

                    <!-- MODAL DETAIL MATERI GURU -->
                    <div class="modal fade" id="modalDetail{{ $materi->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0 shadow rounded-4">
                                <div class="modal-header border-bottom px-4 py-3">
                                    <h5 class="modal-title fw-bold">Detail Materi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <p class="text-muted small mb-1">Informasi Materi</p>
                                            <h5 class="fw-bold text-dark">{{ $materi->judul }}</h5>
                                            <p class="mb-1"><iconify-icon icon="solar:book-bold-duotone" class="text-primary"></iconify-icon> {{ $materi->mata_pelajaran }}</p>
                                            <p class="mb-0"><iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-primary"></iconify-icon> Kelas: {{ $materi->kelas }}</p>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <p class="text-muted small mb-1">Status Persetujuan</p>
                                            @if($materi->status == 'Pending')
                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><iconify-icon icon="solar:hourglass-line-duotone"></iconify-icon> Menunggu Review</span>
                                            @elseif($materi->status == 'Approved' || $materi->status == 'Published')
                                                <span class="badge bg-success px-3 py-2 rounded-pill"><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Disetujui</span>
                                            @elseif($materi->status == 'Rejected')
                                                <span class="badge bg-danger px-3 py-2 rounded-pill"><iconify-icon icon="solar:close-circle-bold"></iconify-icon> Ditolak</span>
                                            @endif
                                            <div class="text-muted small mt-2">Dibuat: {{ $materi->created_at->format('d M Y, H:i') }}</div>
                                        </div>
                                    </div>

                                    @if($materi->status == 'Rejected')
                                        <div class="alert alert-danger border-0 bg-danger-subtle text-danger mb-4 rounded-3 p-3">
                                            <div class="d-flex align-items-start gap-2">
                                                <iconify-icon icon="solar:danger-circle-bold" class="fs-4 mt-1"></iconify-icon>
                                                <div>
                                                    <h6 class="fw-bold mb-1">Catatan Penolakan:</h6>
                                                    <p class="mb-0 small">{{ $materi->rejection_note }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="bg-light p-3 rounded-3 mb-4">
                                        <p class="fw-semibold small mb-2">Deskripsi:</p>
                                        <p class="mb-0">{{ $materi->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                                    </div>
                                    
                                    @if($materi->file)
                                    <div class="border rounded-3 p-3 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <iconify-icon icon="solar:file-bold-duotone" class="fs-1 text-primary"></iconify-icon>
                                            <div>
                                                <h6 class="fw-bold mb-0">File Terlampir</h6>
                                                <small class="text-muted">Klik tombol untuk mengunduh / melihat</small>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $materi->file) }}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3" onclick="event.stopPropagation();">Buka File</a>
                                    </div>
                                    @endif
                                </div>
                                <div class="modal-footer bg-light border-0 rounded-bottom-4 px-4 py-3">
                                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada materi yang diunggah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
