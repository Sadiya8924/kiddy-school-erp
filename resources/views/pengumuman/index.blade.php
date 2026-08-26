@extends('layouts.admin')

@section('konten_halaman')
@php
    $isGrouped = isset($schools);
@endphp

<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

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
                            <iconify-icon icon="solar:bell-bing-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Pengumuman Sekolah</h5>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Informasi terbaru untuk seluruh warga sekolah.</span>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('partials.owner_stepped_filter')

    @if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))
    <div class="row g-3.5">
        {{-- Form Input (Hanya muncul untuk Admin) --}}
        @if(auth()->user()->role == 'admin')
        <div class="col-lg-4">
            <div class="card border shadow-sm" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 15px;">
                        <iconify-icon icon="solar:pen-new-square-bold-duotone" class="text-primary fs-5"></iconify-icon> Buat Pengumuman Baru
                    </h6>
                    <form action="{{ route('pengumuman.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-semibold text-dark small mb-1">Judul Pengumuman</label>
                            <input type="text" name="judul" class="form-control rounded-3 font-medium text-dark shadow-none" required placeholder="Judul pengumuman..." style="font-size: 12.5px;">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label font-semibold text-dark small mb-1">Tanggal Kegiatan / Acara</label>
                            <input type="date" name="tanggal_kegiatan" class="form-control rounded-3 font-medium text-dark shadow-none" required style="font-size: 12.5px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-semibold text-dark small mb-1">Isi Pesan / Informasi</label>
                            <textarea name="isi" class="form-control rounded-3 font-medium text-dark shadow-none" rows="4" required placeholder="Tulis rincian pengumuman..." style="font-size: 12.5px;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-3 font-semibold shadow-xs py-2" style="font-size: 12.5px;">Terbitkan Pengumuman</button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- Daftar Pengumuman --}}
        <div class="col-lg-{{ auth()->user()->role == 'admin' ? '8' : '12' }}">
            <div class="d-flex flex-column gap-3.5">
                
                {{-- PENGUMUMAN AKTIF --}}
                <div class="card border shadow-sm" style="border-top: 3.5px solid #10b981 !important; border-radius: 16px !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3.5 border-bottom pb-3">
                            <div>
                                <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15px;">
                                    <iconify-icon icon="solar:bell-bing-bold-duotone" class="text-success fs-5"></iconify-icon> Pengumuman Aktif
                                </h6>
                                <p class="text-muted mb-0 small" style="font-size: 11.5px;">Pengumuman akan otomatis pindah ke arsip setelah tanggal kegiatan lewat.</p>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 font-semibold" style="font-size: 11px;">Tampil Hari Ini</span>
                        </div>

                        @if($isGrouped)
                            @forelse($schools as $schoolItem)
                                @php $items = $pengumumanAktif[$schoolItem->id] ?? collect(); @endphp
                                <div class="mb-4 pb-3 border-bottom">
                                    <h6 class="fw-bold text-dark mb-2.5" style="font-size: 13.5px;">{{ $schoolItem->nama_sekolah }}</h6>
                                    @forelse($items as $p)
                                        <div class="card border border-slate-100 bg-slate-50 shadow-none rounded-3 mb-3">
                                            <div class="card-body p-3.5">
                                                <div class="d-flex justify-content-between align-items-start gap-3">
                                                    <div>
                                                        <div class="d-flex gap-2 mb-2 flex-wrap align-items-center">
                                                            <span class="badge bg-primary-subtle text-primary font-semibold rounded-pill px-2.5 py-1" style="font-size: 10.5px;">{{ $p->created_at->diffForHumans() }}</span>
                                                            <span class="badge bg-danger-subtle text-danger font-semibold rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" style="font-size: 10.5px;">
                                                                <iconify-icon icon="solar:calendar-bold-duotone" class="fs-6"></iconify-icon> Pelaksanaan: {{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d F Y') }}
                                                            </span>
                                                        </div>
                                                        <h6 class="fw-bold text-dark mb-1.5" style="font-size: 14px;">{{ $p->judul }}</h6>
                                                        <p class="mb-0 text-muted font-normal" style="text-align: justify; font-size: 12.5px; line-height: 1.6;">{{ $p->isi }}</p>
                                                    </div>
                                                    @if(auth()->user()->role == 'admin')
                                                        <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-light text-danger p-1 rounded-3 border-0 shadow-none" onclick="return confirm('Hapus pengumuman ini?')" title="Hapus Pengumuman">
                                                                <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-5"></iconify-icon>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-muted small fst-italic" style="font-size: 12px;">Tidak ada pengumuman aktif untuk sekolah ini.</div>
                                    @endforelse
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted small">Belum ada pengumuman aktif.</div>
                            @endforelse
                        @else
                            @forelse($pengumumanAktif as $p)
                                <div class="card border border-slate-100 bg-slate-50 shadow-none rounded-3 mb-3">
                                    <div class="card-body p-3.5">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <div>
                                                <div class="d-flex gap-2 mb-2 flex-wrap align-items-center">
                                                    <span class="badge bg-primary-subtle text-primary font-semibold rounded-pill px-2.5 py-1" style="font-size: 10.5px;">{{ $p->created_at->diffForHumans() }}</span>
                                                    <span class="badge bg-danger-subtle text-danger font-semibold rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" style="font-size: 10.5px;">
                                                        <iconify-icon icon="solar:calendar-bold-duotone" class="fs-6"></iconify-icon> Pelaksanaan: {{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d F Y') }}
                                                    </span>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-1.5" style="font-size: 14px;">{{ $p->judul }}</h6>
                                                <p class="mb-0 text-muted font-normal" style="text-align: justify; font-size: 12.5px; line-height: 1.6;">{{ $p->isi }}</p>
                                            </div>
                                            @if(auth()->user()->role == 'admin')
                                            <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger p-1 rounded-3 border-0 shadow-none" onclick="return confirm('Hapus pengumuman ini?')" title="Hapus Pengumuman">
                                                    <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-5"></iconify-icon>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted small">Belum ada pengumuman aktif.</div>
                            @endforelse
                        @endif
                    </div>
                </div>

                {{-- ARSIP PENGUMUMAN --}}
                <div class="card border shadow-sm" style="border-top: 3.5px solid #94a3b8 !important; border-radius: 16px !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3.5 border-bottom pb-3">
                            <div>
                                <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15px;">
                                    <iconify-icon icon="solar:box-minimalistic-bold-duotone" class="text-secondary fs-5"></iconify-icon> Arsip Pengumuman
                                </h6>
                                <p class="text-muted mb-0 small" style="font-size: 11.5px;">Pengumuman yang tanggal kegiatannya sudah lewat akan tampil di sini.</p>
                            </div>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1 font-semibold" style="font-size: 11px;">Tersimpan</span>
                        </div>

                        @if($isGrouped)
                            @forelse($schools as $schoolItem)
                                @php $items = $arsipPengumuman[$schoolItem->id] ?? collect(); @endphp
                                <div class="mb-4 pb-3 border-bottom">
                                    <h6 class="fw-bold text-dark mb-2.5" style="font-size: 13.5px;">{{ $schoolItem->nama_sekolah }}</h6>
                                    @forelse($items as $p)
                                        <div class="card border border-slate-100 bg-slate-50 shadow-none rounded-3 mb-3">
                                            <div class="card-body p-3.5">
                                                <div class="d-flex justify-content-between align-items-start gap-3">
                                                    <div>
                                                        <div class="d-flex gap-2 mb-2 flex-wrap align-items-center">
                                                            <span class="badge bg-primary-subtle text-primary font-semibold rounded-pill px-2.5 py-1" style="font-size: 10.5px;">{{ $p->created_at->diffForHumans() }}</span>
                                                            <span class="badge bg-secondary-subtle text-secondary font-semibold rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" style="font-size: 10.5px;">
                                                                <iconify-icon icon="solar:box-minimalistic-bold-duotone" class="fs-6"></iconify-icon> Arsip: {{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d F Y') }}
                                                            </span>
                                                        </div>
                                                        <h6 class="fw-bold text-dark mb-1.5" style="font-size: 14px;">{{ $p->judul }}</h6>
                                                        <p class="mb-0 text-muted font-normal" style="text-align: justify; font-size: 12.5px; line-height: 1.6;">{{ $p->isi }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-muted small fst-italic" style="font-size: 12px;">Belum ada arsip pengumuman untuk sekolah ini.</div>
                                    @endforelse
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted small">Belum ada arsip pengumuman.</div>
                            @endforelse
                        @else
                            @forelse($arsipPengumuman as $p)
                                <div class="card border border-slate-100 bg-slate-50 shadow-none rounded-3 mb-3">
                                    <div class="card-body p-3.5">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <div>
                                                <div class="d-flex gap-2 mb-2 flex-wrap align-items-center">
                                                    <span class="badge bg-primary-subtle text-primary font-semibold rounded-pill px-2.5 py-1" style="font-size: 10.5px;">{{ $p->created_at->diffForHumans() }}</span>
                                                    <span class="badge bg-secondary-subtle text-secondary font-semibold rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" style="font-size: 10.5px;">
                                                        <iconify-icon icon="solar:box-minimalistic-bold-duotone" class="fs-6"></iconify-icon> Arsip: {{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d F Y') }}
                                                    </span>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-1.5" style="font-size: 14px;">{{ $p->judul }}</h6>
                                                <p class="mb-0 text-muted font-normal" style="text-align: justify; font-size: 12.5px; line-height: 1.6;">{{ $p->isi }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted small">Belum ada arsip pengumuman.</div>
                            @endforelse
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endif

    {{-- ========================================================== --}}
    {{-- SUBTLE LOW-KEY FOOTER --}}
    {{-- ========================================================== --}}
    <footer class="text-center py-2 mt-2">
        <span class="text-muted" style="font-size: 11px;">© 2026 TK Ceria - Web TK Al-Hidayah v1.0</span>
    </footer>

</div>
@endsection