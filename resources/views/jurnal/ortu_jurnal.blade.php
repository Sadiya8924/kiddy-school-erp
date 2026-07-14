@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Jurnal Aktivitas Harian</h3>
        <p class="text-muted mb-0">Pantau kegiatan harian anak Anda di sekolah.</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success border-0 shadow-sm rounded-4">{{ session('success') }}</div>
@endif

<div class="row g-4">
    @forelse($jurnals as $jurnal)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative">
            <div class="card-header border-0 bg-info bg-gradient text-white p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-white text-info rounded-pill">{{ $jurnal->siswa->nama_lengkap ?? 'Siswa' }}</span>
                    <small class="opacity-75"><iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon> {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y') }}</small>
                </div>
                <h5 class="fw-bold text-white mb-0 lh-base">{{ $jurnal->tema }}</h5>
            </div>
            
            <div class="card-body p-4 d-flex flex-column">
                
                @if($jurnal->kegiatan)
                <h6 class="fw-bold mb-2">Kegiatan Anak:</h6>
                <p class="text-dark mb-3" style="font-size: 14px; line-height: 1.6;">
                    {!! nl2br(e($jurnal->kegiatan)) !!}
                </p>
                @endif
                
                @if($jurnal->catatan)
                <div class="bg-warning-subtle text-dark p-3 rounded-3 mb-3 border border-warning-subtle">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <iconify-icon icon="solar:notes-bold-duotone" class="text-warning fs-5"></iconify-icon>
                        <span class="fw-bold text-warning-emphasis" style="font-size: 13px;">Catatan Khusus Guru:</span>
                    </div>
                    <span class="fst-italic" style="font-size: 13px;">{{ $jurnal->catatan }}</span>
                </div>
                @endif
                
                {{-- Media --}}
                @if($jurnal->foto || $jurnal->video)
                <div class="mt-auto pt-3 border-top d-flex gap-2">
                    @if($jurnal->foto)
                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill flex-grow-1" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $jurnal->id }}">
                        <iconify-icon icon="solar:gallery-bold-duotone" class="fs-5 align-middle"></iconify-icon> Lihat Foto
                    </button>
                    @endif
                    
                    @if($jurnal->video)
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill flex-grow-1" data-bs-toggle="modal" data-bs-target="#modalVideo{{ $jurnal->id }}">
                        <iconify-icon icon="solar:video-frame-bold-duotone" class="fs-5 align-middle"></iconify-icon> Lihat Video
                    </button>
                    @endif
                </div>
                @endif
                
                <hr class="my-3">
                
                <button type="button" class="btn btn-light w-100 rounded-pill text-muted fw-medium d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#modalKomentar{{ $jurnal->id }}">
                    <iconify-icon icon="solar:chat-line-bold-duotone" class="fs-5"></iconify-icon> Beri Komentar
                </button>
            </div>
        </div>
    </div>
    
    {{-- MODAL FOTO --}}
    @if($jurnal->foto)
    <div class="modal fade" id="modalFoto{{ $jurnal->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bg-transparent shadow-none">
                <div class="modal-header border-0 pb-0 justify-content-end">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: invert(1);"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="{{ asset('storage/' . $jurnal->foto) }}" class="img-fluid rounded-4 shadow" style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
    @endif
    
    {{-- MODAL VIDEO --}}
    @if($jurnal->video)
    <div class="modal fade" id="modalVideo{{ $jurnal->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bg-dark shadow">
                <div class="modal-header border-secondary">
                    <h6 class="modal-title text-white">Video Kegiatan</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <video controls class="w-100 rounded-bottom-3" style="max-height: 75vh;">
                        <source src="{{ asset('storage/' . $jurnal->video) }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    {{-- MODAL KOMENTAR --}}
    <div class="modal fade" id="modalKomentar{{ $jurnal->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header bg-light border-0">
                    <h6 class="modal-title fw-bold">Komentar / Balasan Orang Tua</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    @php
                        $comments = \Illuminate\Support\Facades\DB::table('jurnal_comments')
                            ->join('users', 'jurnal_comments.user_id', '=', 'users.id')
                            ->where('jurnal_id', $jurnal->id)
                            ->select('jurnal_comments.*', 'users.name as user_name')
                            ->orderBy('created_at', 'asc')
                            ->get();
                    @endphp
                    
                    <div class="d-flex flex-column gap-3 mb-4" style="max-height: 300px; overflow-y: auto;">
                        @forelse($comments as $comment)
                            <div class="p-3 rounded-3 {{ $comment->user_id == Auth::id() ? 'bg-primary-subtle align-self-end' : 'bg-light' }}" style="max-width: 85%;">
                                <div class="d-flex justify-content-between align-items-center mb-1 gap-3">
                                    <span class="fw-bold small {{ $comment->user_id == Auth::id() ? 'text-primary' : 'text-dark' }}">{{ $comment->user_name }}</span>
                                    <span class="text-muted" style="font-size: 10px;">{{ \Carbon\Carbon::parse($comment->created_at)->format('d M H:i') }}</span>
                                </div>
                                <p class="mb-0" style="font-size: 13px;">{{ $comment->komentar }}</p>
                            </div>
                        @empty
                            <div class="text-center text-muted small py-3 fst-italic">Belum ada komentar.</div>
                        @endforelse
                    </div>
                    
                    <form action="{{ route('walimurid.materi.comment', $jurnal->id) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="komentar" class="form-control rounded-start-pill border-secondary" placeholder="Tulis komentar..." required>
                            <button class="btn btn-primary rounded-end-pill px-4 shadow-sm" type="submit">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <iconify-icon icon="solar:folder-error-bold-duotone" class="display-1 text-muted opacity-25 mb-3"></iconify-icon>
                <h4 class="fw-bold text-dark">Belum Ada Jurnal Aktivitas</h4>
                <p class="text-muted">Guru belum mengisi jurnal aktivitas harian untuk anak Anda.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection
