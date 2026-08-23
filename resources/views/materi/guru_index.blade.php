@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

    @if(session('success'))
        <div class="alert alert-success border border-success-subtle shadow-none rounded-3 py-2 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
            <div><i class="ti ti-check me-2"></i>{{ session('success') }}</div>
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
                            <iconify-icon icon="solar:notebook-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Manajemen Materi (Guru)</h5>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Kelola dan unggah materi pembelajaran Anda</span>
                        </p>
                    </div>
                </div>

                {{-- Action Controls --}}
                <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                    <a href="{{ route('materi.guru.create') }}" class="btn btn-warning text-dark rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
                        <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon> + Tambah Materi
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
                    <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">TOTAL MATERI</span>
                    <h3 class="fw-bold mb-0 text-primary">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #f59e0b !important; border-radius: 16px !important;">
                <div class="card-body p-3.5">
                    <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">MENUNGGU PERSETUJUAN</span>
                    <h3 class="fw-bold mb-0 text-warning">{{ $stats['pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #10b981 !important; border-radius: 16px !important;">
                <div class="card-body p-3.5">
                    <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">DISETUJUI</span>
                    <h3 class="fw-bold mb-0 text-success">{{ $stats['approved'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #ef4444 !important; border-radius: 16px !important;">
                <div class="card-body p-3.5">
                    <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">DITOLAK</span>
                    <h3 class="fw-bold mb-0 text-danger">{{ $stats['rejected'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL MATERI --}}
    <div class="card border shadow-sm" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
        <div class="card-body p-4 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
                <div>
                    <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15px;">
                        <iconify-icon icon="solar:notebook-bold-duotone" class="text-primary fs-5"></iconify-icon> Daftar Materi Pembelajaran
                    </h6>
                    <span class="text-muted small" style="font-size: 11.5px;">Klik pada baris materi untuk melihat rincian detail &amp; unduh lampiran file</span>
                </div>
                <a href="{{ route('materi.guru.create') }}" class="btn btn-primary btn-sm rounded-3 font-semibold px-3 py-1.5 d-flex align-items-center gap-1 shadow-xs" style="font-size: 12px;">
                    <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon> Tambah
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr class="border-bottom border-light-subtle">
                            <th class="ps-0 pb-2">JUDUL MATERI</th>
                            <th class="pb-2">MATA PELAJARAN</th>
                            <th class="pb-2">KELAS</th>
                            <th class="pb-2">TANGGAL</th>
                            <th class="pb-2">STATUS</th>
                            <th class="text-end pe-0 pb-2" width="60"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materis as $materi)
                        <tr data-bs-toggle="modal" data-bs-target="#modalDetail{{ $materi->id }}" style="cursor: pointer;" class="border-bottom border-light-subtle table-hover-custom" title="Klik untuk lihat detail materi">
                            <td class="ps-0 py-3">
                                <span class="fw-bold text-dark d-block" style="font-size: 14px;">{{ $materi->judul }}</span>
                                @if($materi->status == 'Rejected')
                                    <small class="text-danger font-medium mt-0.5 d-block" style="font-size: 11px;">
                                        <iconify-icon icon="solar:danger-circle-bold-duotone" class="align-middle me-0.5"></iconify-icon> Catatan: {{ $materi->rejection_note }}
                                    </small>
                                @endif
                            </td>
                            <td class="py-3 text-dark font-medium" style="font-size: 13px;">{{ $materi->mata_pelajaran }}</td>
                            <td class="py-3 text-dark font-medium" style="font-size: 13px;">{{ $materi->kelas }}</td>
                            <td class="py-3 text-muted font-medium" style="font-size: 12.5px;">{{ $materi->created_at->translatedFormat('d M Y') }}</td>
                            <td class="py-3">
                                @if($materi->status == 'Pending')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px;">Pending</span>
                                @elseif($materi->status == 'Approved' || $materi->status == 'Published')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px;">Disetujui</span>
                                @elseif($materi->status == 'Rejected')
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px;">Ditolak</span>
                                @else
                                    <span class="badge bg-slate-100 text-muted rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px;">{{ $materi->status }}</span>
                                @endif
                            </td>
                            <td class="py-3 text-end pe-0">
                                <iconify-icon icon="solar:alt-arrow-right-line-duotone" class="text-muted fs-5 align-middle"></iconify-icon>
                            </td>
                        </tr>

                        <!-- MODAL DETAIL MATERI GURU -->
                        <div class="modal fade" id="modalDetail{{ $materi->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow-lg text-start">
                                    <div class="modal-header bg-slate-50 border-bottom p-3.5">
                                        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                                            <iconify-icon icon="solar:notebook-bold-duotone" class="text-primary fs-5"></iconify-icon> Detail Materi Pembelajaran
                                        </h6>
                                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">INFORMASI MATERI</span>
                                                <h5 class="fw-bold text-dark mb-2" style="font-size: 16px;">{{ $materi->judul }}</h5>
                                                <p class="mb-1 text-dark font-medium small"><iconify-icon icon="solar:book-bold-duotone" class="text-primary me-1.5 align-middle fs-5"></iconify-icon> Mapel: {{ $materi->mata_pelajaran }}</p>
                                                <p class="mb-0 text-dark font-medium small"><iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-primary me-1.5 align-middle fs-5"></iconify-icon> Kelas: {{ $materi->kelas }}</p>
                                            </div>
                                            <div class="col-md-6 text-md-end">
                                                <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">STATUS PERSETUJUAN</span>
                                                @if($materi->status == 'Pending')
                                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1 font-semibold" style="font-size: 11.5px;">
                                                        <iconify-icon icon="solar:hourglass-line-duotone" class="me-1 align-middle"></iconify-icon> Menunggu Review
                                                    </span>
                                                @elseif($materi->status == 'Approved' || $materi->status == 'Published')
                                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 font-semibold" style="font-size: 11.5px;">
                                                        <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1 align-middle"></iconify-icon> Disetujui
                                                    </span>
                                                @elseif($materi->status == 'Rejected')
                                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1 font-semibold" style="font-size: 11.5px;">
                                                        <iconify-icon icon="solar:close-circle-bold-duotone" class="me-1 align-middle"></iconify-icon> Ditolak
                                                    </span>
                                                @endif
                                                <div class="text-muted small mt-2" style="font-size: 11.5px;">Dibuat: {{ $materi->created_at->translatedFormat('d M Y, H:i') }}</div>
                                            </div>
                                        </div>

                                        @if($materi->status == 'Rejected')
                                            <div class="alert alert-danger border-0 bg-danger-subtle text-danger mb-4 rounded-3 p-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <iconify-icon icon="solar:danger-circle-bold-duotone" class="fs-4 mt-0.5 flex-shrink-0"></iconify-icon>
                                                    <div>
                                                        <h6 class="fw-bold mb-1" style="font-size: 13px;">Catatan Penolakan:</h6>
                                                        <p class="mb-0 small" style="font-size: 12px;">{{ $materi->rejection_note }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="bg-slate-50 p-3.5 rounded-3 border border-slate-100 mb-4">
                                            <span class="fw-bold text-dark small d-block mb-1">Deskripsi Materi:</span>
                                            <p class="mb-0 text-dark small" style="font-size: 12.5px;">{{ $materi->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                                        </div>
                                        
                                        @if($materi->file)
                                        <div class="border rounded-3 p-3 d-flex align-items-center justify-content-between bg-white shadow-xs">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                                    <iconify-icon icon="solar:file-bold-duotone" class="fs-3"></iconify-icon>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 13.5px;">File Terlampir</h6>
                                                    <span class="text-muted small" style="font-size: 11.5px;">Klik tombol untuk mengunduh / melihat file</span>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . $materi->file) }}" target="_blank" class="btn btn-primary btn-sm rounded-3 font-semibold px-3 py-1.5 shadow-xs" style="font-size: 12px;" onclick="event.stopPropagation();">Buka File</a>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer border-top p-3.5">
                                        <button type="button" class="btn btn-outline-secondary rounded-3 font-semibold px-4 py-2" style="font-size: 12.5px;" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                                        <iconify-icon icon="solar:notebook-bold-duotone" class="fs-2"></iconify-icon>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada Materi</h6>
                                    <p class="text-muted small mb-0">Belum ada materi pembelajaran yang diunggah.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
