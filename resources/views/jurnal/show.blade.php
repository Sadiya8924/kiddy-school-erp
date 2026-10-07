@extends('layouts.admin')

@section('konten_halaman')
@php
    $kodeYayasan = request()->route('kode_yayasan') ?: (request()->segment(1) === 'y' ? request()->segment(2) : null);
    $getJurnalUrl = function($routeName, $params = []) use ($kodeYayasan) {
        if ($kodeYayasan) {
            $yayasanRouteName = 'yayasan.' . $routeName;
            $params = array_merge(['kode_yayasan' => $kodeYayasan], $params);
            return route($yayasanRouteName, $params);
        }
        return route($routeName, $params);
    };
@endphp
<div class="mb-4">
    <a href="{{ $getJurnalUrl('jurnal.index') }}" class="btn btn-light text-primary border rounded-pill px-4 shadow-sm mb-3">
        <iconify-icon icon="solar:alt-arrow-left-bold-duotone" class="align-middle me-1"></iconify-icon> Kembali ke Daftar Materi
    </a>
    <div class="d-flex align-items-center gap-3 bg-white p-4 rounded-4 shadow-sm border-start border-5 border-primary">
        <div>
            <h3 class="fw-bold text-dark mb-1">{{ $materi->judul_materi }}</h3>
            <div class="d-flex gap-3 text-muted small">
                <span><iconify-icon icon="solar:calendar-bold-duotone" class="align-middle"></iconify-icon> {{ \Carbon\Carbon::parse($materi->tanggal_dibuat)->translatedFormat('d F Y') }}</span>
                <span><iconify-icon icon="solar:black-hole-bold-duotone" class="align-middle"></iconify-icon> Kelas: {{ $kelas->nama_kelas }} (Kelompok {{ $kelas->kelompok }})</span>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
@endif

<div class="card w-100 border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 border-bottom pb-3">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:clipboard-list-bold-duotone" class="text-primary fs-5"></iconify-icon>
                Daftar Siswa & Pengisian Jurnal
            </h5>
            <button type="button" class="btn btn-info text-white rounded-pill px-4 shadow-sm fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalQuickViewSemuaJurnal">
                <iconify-icon icon="solar:eye-bold-duotone" class="fs-5"></iconify-icon> Lihat Rekap Jurnal Kelas
            </button>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="20%">Nama Siswa</th>
                        <th width="35%">Deskripsi Kegiatan & Media</th>
                        <th width="20%">Catatan Khusus</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $index => $siswa)
                        @php 
                            $jurnal = $jurnals[$siswa->id] ?? null; 
                        @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                            <td class="fw-bold text-dark">{{ $siswa->nama_lengkap }}</td>
                            <td>
                                @if($jurnal)
                                    <span class="d-block small text-dark mb-2">{!! nl2br(e($jurnal->kegiatan)) !!}</span>
                                    
                                    <div class="d-flex gap-2 mt-1 flex-wrap">
                                        @if($jurnal->foto)
                                            <span class="badge bg-info-subtle text-info border border-info-subtle small">
                                                <iconify-icon icon="solar:gallery-bold-duotone" class="align-middle me-1"></iconify-icon> Ada Foto
                                            </span>
                                        @endif
                                        @if($jurnal->video)
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle small">
                                                <iconify-icon icon="solar:video-frame-bold-duotone" class="align-middle me-1"></iconify-icon> Ada Video
                                            </span>
                                        @endif
                                        <button type="button" class="btn btn-xs btn-outline-primary rounded-pill py-1 px-2 text-decoration-none border-0 bg-primary-subtle text-primary small" data-bs-toggle="modal" data-bs-target="#modalDetailJurnal{{ $siswa->id }}">
                                            <iconify-icon icon="solar:chat-line-bold-duotone" class="align-middle me-1"></iconify-icon> Chat / Diskusi
                                        </button>
                                    </div>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning">Belum diisi</span>
                                @endif
                            </td>
                            <td>
                                <span class="small text-muted fst-italic">{{ $jurnal->catatan ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <button type="button" class="btn btn-sm btn-info text-white rounded-pill px-3 shadow-none fw-semibold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalDetailJurnal{{ $siswa->id }}">
                                        <iconify-icon icon="solar:eye-bold-duotone" class="fs-5"></iconify-icon> Detail Jurnal
                                    </button>
                                    <button type="button" class="btn btn-sm {{ $jurnal ? 'btn-outline-primary' : 'btn-primary' }} rounded-pill shadow-none px-3 fw-semibold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalJurnal{{ $siswa->id }}">
                                        <iconify-icon icon="solar:pen-new-round-bold-duotone" class="fs-5"></iconify-icon> {{ $jurnal ? 'Edit' : 'Isi' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada siswa di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODALS SECTION (DITARUH DI LUAR TABEL BIAR TIDAK TABRAKAN EVENT/MODAL) --}}
@foreach($siswas as $siswa)
    @php $jurnal = $jurnals[$siswa->id] ?? null; @endphp

    {{-- MODAL ISI / EDIT JURNAL --}}
    <div class="modal fade text-start" id="modalJurnal{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form action="{{ $getJurnalUrl('jurnal.siswa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="materi_id" value="{{ $materi->id }}">
                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                    <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                    
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold text-white">Jurnal: {{ $siswa->nama_lengkap }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi Kegiatan Anak <span class="text-danger">*</span></label>
                            <textarea name="kegiatan" class="form-control border-primary" rows="4" placeholder="Ceritakan bagaimana anak mengikuti materi ini..." required>{{ $jurnal->kegiatan ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan Khusus <span class="text-muted fw-normal">(Opsional)</span></label>
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Anak sangat antusias, atau sedang rewel hari ini...">{{ $jurnal->catatan ?? '' }}</textarea>
                        </div>

                        <div class="row mt-3 border-top pt-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Upload Foto <span class="text-muted fw-normal">(Ops. Max 5MB)</span></label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                @if($jurnal && $jurnal->foto)
                                    <small class="text-success d-block mt-1"><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Tersimpan</small>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Upload Video <span class="text-muted fw-normal">(Ops. Max 20MB)</span></label>
                                <input type="file" name="video" class="form-control" accept="video/mp4,video/*">
                                @if($jurnal && $jurnal->video)
                                    <small class="text-success d-block mt-1"><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Tersimpan</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL JURNAL --}}
    <div class="modal fade text-start" id="modalDetailJurnal{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header bg-info bg-gradient text-white">
                    <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:notebook-bold-duotone" class="fs-4"></iconify-icon>
                        Detail Jurnal: {{ $siswa->nama_lengkap }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    @if($jurnal)
                        <h6 class="fw-bold mb-2 text-dark">Kegiatan Anak:</h6>
                        <p class="text-dark mb-4" style="line-height: 1.6;">{!! nl2br(e($jurnal->kegiatan)) !!}</p>
                        
                        @if($jurnal->catatan)
                        <div class="bg-warning-subtle text-dark p-3 rounded-3 mb-4 border border-warning-subtle">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <iconify-icon icon="solar:notes-bold-duotone" class="text-warning fs-5"></iconify-icon>
                                <span class="fw-bold text-warning-emphasis">Catatan Khusus:</span>
                            </div>
                            <span class="fst-italic">{{ $jurnal->catatan }}</span>
                        </div>
                        @endif

                        {{-- FOTO & VIDEO LANGSUNG DITAMPILKAN DI SINI --}}
                        @if($jurnal->foto || $jurnal->video)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 text-dark">Dokumentasi Foto & Media:</h6>
                            <div class="row g-3">
                                @if($jurnal->foto)
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-light">
                                            <img src="{{ $jurnal->foto_url }}" class="img-fluid w-100" style="max-height: 320px; object-fit: contain; background-color: #f8fafc;" alt="Foto Kegiatan">
                                            <div class="card-footer bg-white border-0 py-2 text-center">
                                                <small class="text-muted fw-semibold"><iconify-icon icon="solar:gallery-bold-duotone" class="align-middle me-1 text-info"></iconify-icon> Foto Kegiatan</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($jurnal->video)
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-dark">
                                            <video controls class="w-100" style="max-height: 320px;">
                                                <source src="{{ $jurnal->video_url }}" type="video/mp4">
                                                Browser tidak mendukung pemutaran video.
                                            </video>
                                            <div class="card-footer bg-dark border-top border-secondary py-2 text-center">
                                                <small class="text-white fw-semibold"><iconify-icon icon="solar:video-frame-bold-duotone" class="align-middle me-1 text-danger"></iconify-icon> Video Kegiatan</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <hr>
                        
                        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:chat-line-bold-duotone" class="text-primary fs-5"></iconify-icon> Diskusi / Komentar Wali Murid
                        </h6>
                        
                        @php
                            $comments = \Illuminate\Support\Facades\DB::table('jurnal_comments')
                                ->join('users', 'jurnal_comments.user_id', '=', 'users.id')
                                ->where('jurnal_id', $jurnal->id)
                                ->select('jurnal_comments.*', 'users.name as user_name')
                                ->orderBy('created_at', 'asc')
                                ->get();
                        @endphp
                        
                        <div class="d-flex flex-column gap-3 mb-4 bg-light p-3 rounded-3" style="max-height: 300px; overflow-y: auto;">
                            @forelse($comments as $comment)
                                <div class="p-3 rounded-3 {{ $comment->user_id == Auth::id() ? 'bg-primary-subtle align-self-end' : 'bg-white border' }}" style="max-width: 85%;">
                                    <div class="d-flex justify-content-between align-items-center mb-1 gap-3">
                                        <span class="fw-bold small {{ $comment->user_id == Auth::id() ? 'text-primary' : 'text-dark' }}">{{ $comment->user_name }}</span>
                                        <span class="text-muted" style="font-size: 10px;">{{ \Carbon\Carbon::parse($comment->created_at)->format('d M H:i') }}</span>
                                    </div>
                                    <p class="mb-0" style="font-size: 13px;">{{ $comment->komentar }}</p>
                                </div>
                            @empty
                                <div class="text-center text-muted small py-3 fst-italic">Belum ada diskusi untuk jurnal ini.</div>
                            @endforelse
                        </div>
                        
                        <form action="{{ $getJurnalUrl('jurnal.comment.store', ['id' => $jurnal->id]) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="komentar" class="form-control rounded-start-pill border-secondary" placeholder="Tulis balasan..." required>
                                <button class="btn btn-primary rounded-end-pill px-4 shadow-sm" type="submit">Kirim Balasan</button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <iconify-icon icon="solar:notebook-square-bold-duotone" class="display-1 text-warning opacity-50 mb-3"></iconify-icon>
                            <h5 class="fw-bold text-dark">Data Jurnal Belum Diisi</h5>
                            <p class="text-muted small mb-4">Jurnal harian untuk {{ $siswa->nama_lengkap }} belum diisi oleh guru.</p>
                            <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalJurnal{{ $siswa->id }}">
                                <iconify-icon icon="solar:pen-new-round-bold-duotone" class="align-middle me-1"></iconify-icon> Isi Jurnal Sekarang
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- MODAL QUICK VIEW SEMUA JURNAL KELAS --}}
<div class="modal fade" id="modalQuickViewSemuaJurnal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-info text-white rounded-top-4 p-4">
                <div class="d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:eye-bold-duotone" class="fs-3"></iconify-icon>
                    <h5 class="modal-title fw-bold text-white mb-0">View Data Jurnal Kelas {{ $kelas->nama_kelas }}</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3 text-muted small">
                    Topik: <strong>{{ $materi->judul_materi }}</strong> | Tanggal: {{ \Carbon\Carbon::parse($materi->tanggal_dibuat)->translatedFormat('d F Y') }}
                </div>
                <div class="list-group rounded-3 shadow-sm border-0">
                    @forelse($siswas as $siswa)
                        @php $j = $jurnals[$siswa->id] ?? null; @endphp
                        <div class="list-group-item p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold text-dark mb-0">{{ $siswa->nama_lengkap }}</h6>
                                @if($j)
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-semibold">Sudah Diisi</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1 fw-semibold">Belum Diisi</span>
                                @endif
                            </div>
                            @if($j)
                                <p class="text-dark small mb-2">{!! nl2br(e($j->kegiatan)) !!}</p>
                                @if($j->catatan)
                                    <small class="text-muted d-block fst-italic mb-2">Catatan: {{ $j->catatan }}</small>
                                @endif
                                <button type="button" class="btn btn-xs btn-outline-info rounded-pill px-3 py-1" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalDetailJurnal{{ $siswa->id }}">
                                    <iconify-icon icon="solar:eye-bold-duotone" class="align-middle me-1"></iconify-icon> View Detail & Chat
                                </button>
                            @else
                                <p class="text-muted small fst-italic mb-0">Belum ada jurnal terisi.</p>
                            @endif
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">Belum ada siswa di kelas ini.</div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer border-0 bg-light rounded-bottom-4 p-3 px-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection