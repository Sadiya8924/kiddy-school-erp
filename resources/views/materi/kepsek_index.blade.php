@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Persetujuan Materi (Kepala Sekolah)</h3>
        <p class="text-muted mb-0">Tinjau dan setujui materi yang diajukan oleh guru</p>
    </div>
    @if($pendingCount > 0)
    <div class="bg-warning bg-opacity-10 text-warning px-4 py-2 rounded-pill fw-bold border border-warning shadow-sm">
        <iconify-icon icon="solar:bell-bing-bold-duotone" class="align-middle me-1 fs-5"></iconify-icon>
        {{ $pendingCount }} Menunggu Persetujuan
    </div>
    @endif
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom p-4">
        <form action="{{ route('materi.kepsek.index') }}" method="GET" class="d-flex gap-2">
            <select name="status" class="form-select border-light-subtle rounded-pill w-auto shadow-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                <option value="Published" {{ request('status') == 'Published' ? 'selected' : '' }}>Disetujui (Published)</option>
                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
            </select>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Judul & Mata Pelajaran</th>
                        <th>Guru Pengunggah</th>
                        <th>Kelas</th>
                        <th>Tanggal Kirim</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materis as $materi)
                    <tr data-bs-toggle="modal" data-bs-target="#modalReview{{ $materi->id }}" style="cursor: pointer;" class="table-hover-custom" title="Klik untuk review materi">
                        <td class="ps-4">
                            <span class="fw-bold text-dark d-block">{{ $materi->judul }}</span>
                            <small class="text-muted">{{ $materi->mata_pelajaran }}</small>
                            @if($materi->status == 'Rejected')
                                <div class="mt-1 small text-danger"><iconify-icon icon="solar:danger-circle-bold"></iconify-icon> Alasan: {{ $materi->rejection_note }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <iconify-icon icon="solar:user-bold-duotone"></iconify-icon>
                                </div>
                                <span class="fw-medium text-dark">{{ $materi->creator->nama_lengkap ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td>{{ $materi->kelas }}</td>
                        <td>{{ $materi->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            @if($materi->status == 'Pending')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu Review</span>
                            @elseif($materi->status == 'Approved' || $materi->status == 'Published')
                                <span class="badge bg-success px-3 py-2 rounded-pill"><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Disetujui</span>
                            @elseif($materi->status == 'Rejected')
                                <span class="badge bg-danger px-3 py-2 rounded-pill"><iconify-icon icon="solar:close-circle-bold"></iconify-icon> Ditolak</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $materi->status }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <iconify-icon icon="solar:alt-arrow-right-bold-duotone" class="text-muted fs-5 mt-1"></iconify-icon>
                        </td>
                    </tr>

                    <!-- MODAL REVIEW -->
                    <div class="modal fade" id="modalReview{{ $materi->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0 shadow rounded-4">
                                <div class="modal-header border-bottom px-4 py-3">
                                    <h5 class="modal-title fw-bold">Review Materi</h5>
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
                                            <p class="text-muted small mb-1">Dibuat Oleh</p>
                                            <h6 class="fw-bold mb-0">{{ $materi->creator->nama_lengkap ?? 'Guru' }}</h6>
                                            <small class="text-muted">{{ $materi->created_at->format('d M Y, H:i') }}</small>
                                        </div>
                                    </div>
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
                                                <small class="text-muted">Klik tombol di samping untuk melihat</small>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $materi->file) }}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3">Buka File</a>
                                    </div>
                                    @endif
                                </div>
                                
                                @if($materi->status == 'Pending')
                                <div class="modal-footer bg-light border-0 rounded-bottom-4 px-4 py-3 justify-content-between">
                                    <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalReject{{ $materi->id }}" onclick="event.stopPropagation();">Tolak Materi</button>
                                    <form action="{{ route('materi.approve', $materi->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2" onclick="event.stopPropagation();">
                                            <iconify-icon icon="solar:check-circle-bold"></iconify-icon> Setujui & Publikasikan
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- MODAL REJECT -->
                    @if($materi->status == 'Pending')
                    <div class="modal fade" id="modalReject{{ $materi->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow rounded-4">
                                <form action="{{ route('materi.reject', $materi->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header border-bottom px-4 py-3 bg-danger-subtle text-danger">
                                        <h5 class="modal-title fw-bold">Tolak Materi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <p>Anda akan menolak materi <b>{{ $materi->judul }}</b>. Silakan berikan alasan penolakan agar guru dapat memperbaikinya.</p>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Catatan Revisi <span class="text-danger">*</span></label>
                                            <textarea name="rejection_note" class="form-control border-danger border-opacity-50" rows="3" required placeholder="Tulis catatan revisi..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 px-4 pb-4 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">Kirim Penolakan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <iconify-icon icon="solar:inbox-line-bold-duotone" class="fs-1 opacity-50 mb-2"></iconify-icon>
                            <p class="mb-0">Belum ada data materi.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
