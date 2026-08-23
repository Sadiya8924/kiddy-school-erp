@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column gap-3.5 mt-0">

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
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 20px; border: 2px solid rgba(255,255,255,0.4);">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Selamat datang, {{ Auth::user()->name }} 👋</h5>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Portal: <strong class="text-white font-semibold">Wali Murid</strong></span>
                            <span>•</span>
                            <span>Pantau akademik & perkembangan ananda di sini</span>
                            <span>•</span>
                            <span><iconify-icon icon="solar:calendar-mark-line-duotone" class="align-middle text-warning me-1"></iconify-icon>{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</span>
                        </p>
                    </div>
                </div>

                {{-- Action Controls --}}
                <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                    <a href="/pendaftaran-siswa" class="btn btn-warning text-dark rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
                        <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-5"></iconify-icon> + Tambah Anak
                    </a>
                </div>

            </div>
        </div>
    </div>



    {{-- ========================================================== --}}
    {{-- 3. DAFTAR ANAK ANDA & PENGUMUMAN SEKOLAH --}}
    {{-- ========================================================== --}}
    <div class="row g-3">
        
        {{-- Daftar Anak Anda --}}
        <div class="col-lg-7">
            <div class="card h-100 border shadow-sm" style="border-radius: 16px !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
                        <div>
                            <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15px;">
                                <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="text-primary fs-5"></iconify-icon> Daftar Anak Anda
                            </h6>
                            <span class="text-muted small" style="font-size: 11.5px;">Akses jurnal harian, laporan fisik, materi & rapor ananda</span>
                        </div>
                        <a href="/pendaftaran-siswa" class="btn btn-outline-primary btn-sm rounded-3 font-semibold px-3 py-1.5 d-flex align-items-center gap-1" style="font-size: 12px;">
                            <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon> Tambah
                        </a>
                    </div>

                    @if($anak->isEmpty())
                        {{-- BEAUTIFUL EMPTY STATE FOR ANAK --}}
                        <div class="text-center py-5">
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center mb-3 shadow-xs" style="width: 64px; height: 64px;">
                                <iconify-icon icon="solar:user-cross-bold-duotone" class="fs-1"></iconify-icon>
                            </div>
                            <h5 class="fw-bold text-dark mb-1" style="font-size: 16px;">Belum Ada Data Anak</h5>
                            <p class="text-muted small mb-4 mx-auto" style="max-width: 300px; font-size: 12px;">Sepertinya Anda belum mendaftarkan putra/putri Anda ke sistem kami.</p>
                            <a href="/pendaftaran-siswa" class="btn btn-primary rounded-3 font-semibold px-4 py-2 shadow-sm" style="font-size: 13px;">
                                <iconify-icon icon="solar:user-plus-bold-duotone" class="align-middle me-1 fs-5"></iconify-icon> Daftarkan Anak Sekarang
                            </a>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($anak as $a)
                                @php
                                    $schoolObj = \App\Models\School::find($a->school_id);
                                @endphp
                                <div class="col-12">
                                    <div class="card border shadow-sm mb-0 p-3" style="border-radius: 14px !important; background-color: #f8fafc;">
                                        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 pb-3 border-bottom">
                                            <div class="d-flex align-items-center gap-3">
                                                @if($a->foto_anak)
                                                    <img src="{{ asset('storage/' . $a->foto_anak) }}" alt="Foto Anak" class="rounded-circle object-fit-cover shadow-sm flex-shrink-0" style="width: 52px; height: 52px; border: 2px solid #ffffff;">
                                                @else
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 52px; height: 52px; background-color: #eff6ff; color: #2563eb; border: 2px solid #ffffff;">
                                                        <iconify-icon icon="solar:user-bold-duotone" style="font-size: 26px;"></iconify-icon>
                                                    </div>
                                                @endif
                                                
                                                <div>
                                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                                        <h6 class="fw-bold text-dark mb-0" style="font-size: 15px;">{{ $a->nama_lengkap }}</h6>
                                                        <span class="badge {{ $a->status == 'aktif' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill px-2.5 py-1" style="font-size: 10.5px;">
                                                            {{ ucfirst($a->status) }}
                                                        </span>
                                                    </div>
                                                    <p class="text-muted mb-0 small" style="font-size: 12px;">
                                                        Sekolah: <strong class="text-dark font-semibold">{{ $schoolObj->nama_sekolah ?? '-' }}</strong>
                                                    </p>
                                                </div>
                                            </div>

                                            <a href="/rapor-anak/{{ $a->id }}" class="btn btn-sm btn-outline-primary rounded-3 font-semibold px-3 py-1.5 text-nowrap" style="font-size: 12px;">
                                                Detail Rapor →
                                            </a>
                                        </div>

                                        {{-- Quick Actions Row --}}
                                        <div class="pt-3 d-flex align-items-center gap-2 flex-wrap">
                                            <a href="/rapor-anak/{{ $a->id }}" class="btn btn-white border flex-grow-1 fw-semibold text-dark shadow-xs bg-white rounded-3 py-2" style="font-size: 12px;">
                                                <iconify-icon icon="solar:notebook-bold-duotone" class="text-primary me-1 align-middle"></iconify-icon> Lihat Rapor
                                            </a>
                                            <a href="/perkembangan-anak/{{ $a->id }}" class="btn btn-white border flex-grow-1 fw-semibold text-dark shadow-xs bg-white rounded-3 py-2" style="font-size: 12px;">
                                                <iconify-icon icon="solar:pulse-bold-duotone" class="text-success me-1 align-middle"></iconify-icon> Cek Fisik
                                            </a>
                                            <a href="{{ route('walimurid.materi') }}" class="btn btn-primary border flex-grow-1 fw-semibold shadow-xs rounded-3 py-2" style="font-size: 12px;">
                                                <iconify-icon icon="solar:book-bookmark-bold-duotone" class="me-1 align-middle"></iconify-icon> Jurnal Harian
                                            </a>
                                            <a href="{{ route('materi.ortu.index') }}" class="btn btn-info text-white border flex-grow-1 fw-semibold shadow-xs rounded-3 py-2" style="font-size: 12px; background-color: #8b5cf6; border-color: #8b5cf6;">
                                                <iconify-icon icon="solar:folder-with-files-bold-duotone" class="me-1 align-middle"></iconify-icon> Materi Pelajaran
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Pengumuman Sekolah (With Beautiful Empty State) --}}
        <div class="col-lg-5">
            <div class="card h-100 border shadow-sm" style="border-radius: 16px !important;">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 15px;">
                            <iconify-icon icon="solar:bell-bold-duotone" class="text-warning fs-5"></iconify-icon> Pengumuman Sekolah
                        </h6>
                    </div>

                    @if($pengumuman->count() > 0)
                        <div class="d-flex flex-column gap-2.5">
                            @foreach($pengumuman->take(4) as $p)
                                <div class="p-3 rounded-3 border border-slate-100 bg-slate-50">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 13px; max-width: 220px;">{{ $p->judul }}</h6>
                                        <span class="text-muted" style="font-size: 10.5px;">
                                            <iconify-icon icon="solar:calendar-mark-line-duotone" class="me-0.5 align-middle"></iconify-icon>
                                            {{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-0 text-truncate" style="font-size: 11.5px;">{{ $p->isi }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- BEAUTIFUL EMPTY STATE --}}
                        <div class="my-auto text-center py-4 px-2">
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center mb-3 shadow-xs" style="width: 58px; height: 58px;">
                                <iconify-icon icon="solar:bell-off-bold-duotone" class="fs-2"></iconify-icon>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada Pengumuman</h6>
                            <p class="text-muted small mb-0 mx-auto" style="max-width: 240px; font-size: 11.5px;">Belum ada informasi pengumuman sekolah terbaru untuk orang tua murid.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>

<style>
    .card-clickable {
        transition: all 0.2s ease-in-out !important;
        cursor: pointer;
    }
    .card-clickable:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05) !important;
        border-color: var(--primary-blue-border) !important;
        background-color: var(--primary-blue-soft) !important;
    }
</style>
@endsection