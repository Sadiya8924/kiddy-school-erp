@extends('layouts.admin')

@section('konten_halaman')
<div class="mb-4">
    <a href="{{ route('jurnal.index') }}" class="btn btn-light text-primary border rounded-pill px-4 shadow-sm mb-3">
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
        <h5 class="fw-bold text-dark mb-4 border-bottom pb-3">Daftar Siswa & Pengisian Jurnal</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="20%">Nama Siswa</th>
                        <th width="40%">Deskripsi Kegiatan & Media</th>
                        <th width="20%">Catatan Khusus</th>
                        <th width="15%" class="text-center">Aksi</th>
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
                                    <span class="d-block small text-dark mb-2">{{ $jurnal->kegiatan }}</span>
                                    
                                    {{-- TOMBOL LIHAT MEDIA & DISKUSI --}}
                                    <div class="d-flex gap-2 mt-1 flex-wrap">
                                        @if($jurnal->foto)
                                            <button type="button" class="btn btn-xs btn-outline-info rounded-pill py-1 px-2 text-decoration-none border-0 bg-info-subtle text-info small" data-bs-toggle="modal" data-bs-target="#modalLihatFoto{{ $jurnal->id }}">
                                                <iconify-icon icon="solar:gallery-bold-duotone" class="align-middle me-1"></iconify-icon> Lihat Foto
                                            </button>
                                        @endif
                                        @if($jurnal->video)
                                            <button type="button" class="btn btn-xs btn-outline-danger rounded-pill py-1 px-2 text-decoration-none border-0 bg-danger-subtle text-danger small" data-bs-toggle="modal" data-bs-target="#modalLihatVideo{{ $jurnal->id }}">
                                                <iconify-icon icon="solar:video-frame-bold-duotone" class="align-middle me-1"></iconify-icon> Lihat Video
                                            </button>
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
                                <button type="button" class="btn btn-sm {{ $jurnal ? 'btn-outline-primary' : 'btn-primary' }} rounded-pill shadow-none px-3" data-bs-toggle="modal" data-bs-target="#modalJurnal{{ $siswa->id }}">
                                    <iconify-icon icon="solar:pen-new-round-bold-duotone"></iconify-icon> {{ $jurnal ? 'Edit' : 'Isi' }}
                                </button>
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
                <form action="{{ route('jurnal.siswa.store') }}" method="POST" enctype="multipart/form-data">
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

    @if($jurnal)
        {{-- MODAL DETAIL JURNAL & CHAT DISKUSI WALI MURID --}}
        <div class="modal fade text-start" id="modalDetailJurnal{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header bg-info bg-gradient text-white">
                        <h5 class="modal-title fw-bold text-white">Detail Jurnal & Chat Ortu: {{ $siswa->nama_lengkap }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <h6 class="fw-bold mb-2">Kegiatan Anak:</h6>
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
                        
                        <form action="{{ route('jurnal.comment.store', $jurnal->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="komentar" class="form-control rounded-start-pill border-secondary" placeholder="Tulis balasan..." required>
                                <button class="btn btn-primary rounded-end-pill px-4 shadow-sm" type="submit">Kirim Balasan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- MODAL LIHAT FOTO --}}
        @if($jurnal->foto)
        <div class="modal fade" id="modalLihatFoto{{ $jurnal->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 bg-transparent shadow-none">
                    <div class="modal-header border-0 pb-0 justify-content-end">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: invert(1);"></button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <img src="{{ $jurnal->foto_url }}" class="img-fluid rounded-3 shadow" style="max-height: 80vh; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- MODAL LIHAT VIDEO --}}
        @if($jurnal->video)
        <div class="modal fade" id="modalLihatVideo{{ $jurnal->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 bg-dark shadow">
                    <div class="modal-header border-secondary">
                        <h6 class="modal-title text-white">Video Kegiatan</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <video controls class="w-100 rounded-bottom-3" style="max-height: 75vh;">
                            <source src="{{ $jurnal->video_url }}" type="video/mp4">
                            Browser Anda tidak mendukung tag video.
                        </video>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
@endforeach
@endsection