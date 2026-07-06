@extends('layouts.admin')

@section('konten_halaman')
@php
    $isGrouped = isset($schools);
@endphp

<div class="row">
    <div class="col-12 mb-4">
        <h2 class="fw-semibold text-dark mb-1">Pengumuman Sekolah</h2>
        <span class="text-muted fs-3">Informasi terbaru untuk seluruh warga sekolah.</span>
    </div>

    {{-- Form Input (Hanya muncul untuk Admin) --}}
    @if(auth()->user()->role == 'admin')
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Buat Pengumuman Baru</h5>
                <form action="{{ route('pengumuman.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul</label>
                        <input type="text" name="judul" class="form-control border-primary shadow-none" required>
                    </div>
                    
                    {{-- TAMBAHAN: Input Tanggal Kegiatan Manual --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Kegiatan / Acara</label>
                        <input type="date" name="tanggal_kegiatan" class="form-control border-primary shadow-none" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Isi Pesan</label>
                        <textarea name="isi" class="form-control border-primary shadow-none" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Terbitkan</button>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Daftar Pengumuman --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Pengumuman Aktif</h5>
                        <p class="text-muted mb-0 small">Pengumuman akan otomatis pindah ke arsip setelah tanggal kegiatan lewat.</p>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">Tampil Hari Ini</span>
                </div>

                @if($isGrouped)
                    @forelse($schools as $schoolItem)
                        @php $items = $pengumumanAktif[$schoolItem->id] ?? collect(); @endphp
                        <div class="mb-4 pb-4 border-bottom">
                            <h6 class="fw-bold text-dark mb-3">{{ $schoolItem->nama_sekolah }}</h6>
                            @forelse($items as $p)
                                <div class="card border-0 bg-light-subtle shadow-none rounded-4 mb-3">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <div>
                                                <div class="d-flex gap-2 mb-2 flex-wrap">
                                                    <span class="badge bg-primary-subtle text-primary">{{ $p->created_at->diffForHumans() }}</span>
                                                    <span class="badge bg-danger-subtle text-danger">
                                                        <i class="ti ti-calendar-event me-1"></i> Pelaksanaan: {{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d F Y') }}
                                                    </span>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-2">{{ $p->judul }}</h6>
                                                <p class="mb-0 text-muted" style="text-align: justify;">{{ $p->isi }}</p>
                                            </div>
                                            @if(auth()->user()->role == 'admin')
                                                <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Hapus pengumuman ini?')">
                                                        <i class="ti ti-trash fs-5"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted small fst-italic">Tidak ada pengumuman aktif untuk sekolah ini.</div>
                            @endforelse
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Belum ada pengumuman aktif.</div>
                    @endforelse
                @else
                    @forelse($pengumumanAktif as $p)
                        <div class="card border-0 bg-light-subtle shadow-none rounded-4 mb-3">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="d-flex gap-2 mb-2 flex-wrap">
                                            <span class="badge bg-primary-subtle text-primary">{{ $p->created_at->diffForHumans() }}</span>
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ti ti-calendar-event me-1"></i> Pelaksanaan: {{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d F Y') }}
                                            </span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-2">{{ $p->judul }}</h6>
                                        <p class="mb-0 text-muted" style="text-align: justify;">{{ $p->isi }}</p>
                                    </div>
                                    @if(auth()->user()->role == 'admin')
                                    <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Hapus pengumuman ini?')">
                                            <i class="ti ti-trash fs-5"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Belum ada pengumuman aktif.</div>
                    @endforelse
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Arsip Pengumuman</h5>
                        <p class="text-muted mb-0 small">Pengumuman yang tanggal kegiatannya sudah lewat akan tampil di sini.</p>
                    </div>
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-2">Tersimpan</span>
                </div>

                @if($isGrouped)
                    @forelse($schools as $schoolItem)
                        @php $items = $arsipPengumuman[$schoolItem->id] ?? collect(); @endphp
                        <div class="mb-4 pb-4 border-bottom">
                            <h6 class="fw-bold text-dark mb-3">{{ $schoolItem->nama_sekolah }}</h6>
                            @forelse($items as $p)
                                <div class="card border-0 bg-light-subtle shadow-none rounded-4 mb-3">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <div>
                                                <div class="d-flex gap-2 mb-2 flex-wrap">
                                                    <span class="badge bg-primary-subtle text-primary">{{ $p->created_at->diffForHumans() }}</span>
                                                    <span class="badge bg-dark-subtle text-dark">
                                                        <i class="ti ti-archive me-1"></i> Arsip: {{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d F Y') }}
                                                    </span>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-2">{{ $p->judul }}</h6>
                                                <p class="mb-0 text-muted" style="text-align: justify;">{{ $p->isi }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted small fst-italic">Belum ada arsip pengumuman untuk sekolah ini.</div>
                            @endforelse
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Belum ada arsip pengumuman.</div>
                    @endforelse
                @else
                    @forelse($arsipPengumuman as $p)
                        <div class="card border-0 bg-light-subtle shadow-none rounded-4 mb-3">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="d-flex gap-2 mb-2 flex-wrap">
                                            <span class="badge bg-primary-subtle text-primary">{{ $p->created_at->diffForHumans() }}</span>
                                            <span class="badge bg-dark-subtle text-dark">
                                                <i class="ti ti-archive me-1"></i> Arsip: {{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d F Y') }}
                                            </span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-2">{{ $p->judul }}</h6>
                                        <p class="mb-0 text-muted" style="text-align: justify;">{{ $p->isi }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Belum ada arsip pengumuman.</div>
                    @endforelse
                @endif
            </div>
        </div>
    </div>
</div>
@endsection