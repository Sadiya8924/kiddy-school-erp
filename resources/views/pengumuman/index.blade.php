@extends('layouts.admin')

@section('konten_halaman')
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
    <div class="{{ auth()->user()->role == 'admin' ? 'col-md-8' : 'col-12' }}">
        @forelse($pengumuman as $p)
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="d-flex gap-2 mb-2 flex-wrap">
                            {{-- Tanggal Pembuatan (Auto dari sistem) --}}
                            <span class="badge bg-primary-subtle text-primary">{{ $p->created_at->diffForHumans() }}</span>
                            
                            {{-- Tanggal Kegiatan (Manual dari form) --}}
                            @if($p->tanggal_kegiatan)
                                <span class="badge bg-danger-subtle text-danger">
                                    <i class="ti ti-calendar-event me-1"></i> Pelaksanaan: {{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d F Y') }}
                                </span>
                            @endif
                        </div>
                        
                        <h5 class="fw-bold text-dark">{{ $p->judul }}</h5>
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
        <div class="text-center py-5">
            <p class="text-muted italic">Belum ada pengumuman saat ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection