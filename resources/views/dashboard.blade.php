@extends('layouts.admin')

@section('konten_halaman')
@php
    $current_school_id = Auth::user()->role === 'super_admin' ? session('active_school_id') : Auth::user()->school_id;
    $pendingMateriCount = 0;
    $totalGuruCount = 0;
    if ($current_school_id) {
        $pendingMateriCount = \App\Models\Materi::where('school_id', $current_school_id)->where('status', 'Pending')->count();
        $totalGuruCount = \App\Models\User::where('school_id', $current_school_id)->where('role', 'guru')->count();
    } else {
        $pendingMateriCount = \App\Models\Materi::where('status', 'Pending')->count();
        $totalGuruCount = \App\Models\User::where('role', 'guru')->count();
    }

    $totalSiswaCount = $school->total_siswa_aktif ?? 0;
    $maxSiswaInClass = $kelas_stats->max('jumlah_siswa') ?: 1;
@endphp

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
    <div class="card border-0 shadow-sm overflow-hidden" style="background: var(--theme-hero-gradient, linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%)); border-radius: 16px !important;">
        <div class="card-body p-4 text-white">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                
                {{-- User Greeting & School Context --}}
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
                            <span>Sekolah: <strong class="text-white font-semibold">{{ $school->nama_sekolah ?? 'TK Ceria' }}</strong></span>
                            <span>•</span>
                            <span>Tahun Ajaran: <strong class="text-warning font-semibold">{{ $semesterAktif ? $semesterAktif->tahun_ajaran . ' (' . $semesterAktif->jenis . ')' : '2027/2028 (Ganjil)' }}</strong></span>
                            <span>•</span>
                            <span><iconify-icon icon="solar:calendar-mark-line-duotone" class="align-middle text-warning me-1"></iconify-icon>{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</span>
                        </p>
                    </div>
                </div>

                {{-- Action Controls --}}
                <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                    @if(in_array(Auth::user()->role, ['admin', 'super_admin']))
                        <a href="/pengaturan" class="btn btn-light text-primary rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
                            <iconify-icon icon="solar:settings-bold-duotone" class="fs-5"></iconify-icon> Pengaturan
                        </a>
                        <a href="/pendaftaran-siswa" class="btn btn-warning text-dark rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
                            <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-5"></iconify-icon> + Siswa
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- 2. KPI STATISTICS CARDS WITH COLORED TOP BORDER --}}
    {{-- ========================================================== --}}
    <div class="row g-3">
        
        {{-- Total Siswa --}}
        <div class="col-xl-3 col-md-6">
            <a href="/data-siswa" class="card h-100 border shadow-sm card-clickable text-decoration-none" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
                <div class="card-body p-3.5 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted font-semibold d-block mb-1" style="font-size: 11.5px; letter-spacing: 0.02em;">TOTAL SISWA AKTIF</span>
                        <h2 class="fw-bold text-dark mb-0" style="letter-spacing: -0.03em; font-size: 26px;">{{ $totalSiswaCount }}</h2>
                        <span class="text-muted small mt-1 d-block" style="font-size: 11px;">Peserta didik terdaftar</span>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background-color: #eff6ff; color: #3b82f6; width: 54px; height: 54px;">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-2"></iconify-icon>
                    </div>
                </div>
            </a>
        </div>

        {{-- Total Kelas --}}
        <div class="col-xl-3 col-md-6">
            <a href="/kelas" class="card h-100 border shadow-sm card-clickable text-decoration-none" style="border-top: 3.5px solid #10b981 !important; border-radius: 16px !important;">
                <div class="card-body p-3.5 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted font-semibold d-block mb-1" style="font-size: 11.5px; letter-spacing: 0.02em;">ROMBONGAN BELAJAR</span>
                        <h2 class="fw-bold text-dark mb-0" style="letter-spacing: -0.03em; font-size: 26px;">{{ $school->total_kelas ?? 0 }}</h2>
                        <span class="text-muted small mt-1 d-block" style="font-size: 11px;">Total kelas aktif</span>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background-color: #ecfdf5; color: #10b981; width: 54px; height: 54px;">
                        <iconify-icon icon="solar:black-hole-bold-duotone" class="fs-2"></iconify-icon>
                    </div>
                </div>
            </a>
        </div>

        {{-- Akses Rapor --}}
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border shadow-sm" style="border-top: 3.5px solid #f59e0b !important; border-radius: 16px !important;">
                <div class="card-body p-3.5 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted font-semibold d-block mb-1" style="font-size: 11.5px; letter-spacing: 0.02em;">AKSES PENGISIAN RAPOR</span>
                        <h4 class="fw-bold mb-1" style="letter-spacing: -0.02em; color: {{ $school->is_rapor_open ? '#15803d' : '#b91c1c' }}; font-size: 20px;">
                            {{ $school->is_rapor_open ? 'TERBUKA' : 'DITUTUP' }}
                        </h4>
                        @if(in_array(Auth::user()->role, ['admin', 'super_admin']))
                            <form action="{{ route('rapor.toggle') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-xs rounded-2 font-semibold py-1 px-2.5 bg-slate-100 text-dark border shadow-xs" style="font-size: 10.5px;">Ganti Status</button>
                            </form>
                        @endif
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background-color: {{ $school->is_rapor_open ? '#f0fdf4' : '#fef2f2' }}; color: {{ $school->is_rapor_open ? '#16a34a' : '#dc2626' }}; width: 54px; height: 54px;">
                        <iconify-icon icon="{{ $school->is_rapor_open ? 'solar:lock-keyhole-minimalistic-unlocked-bold-duotone' : 'solar:lock-keyhole-minimalistic-bold-duotone' }}" class="fs-2"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>

        {{-- ACC Materi (Kepsek/Admin) vs Manajemen Materi (Guru) --}}
        @if(in_array(Auth::user()->role, ['kepsek', 'admin', 'super_admin', 'owner']))
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('materi.kepsek.index') }}" class="card h-100 border shadow-sm card-clickable text-decoration-none" style="border-top: 3.5px solid #8b5cf6 !important; border-radius: 16px !important;">
                <div class="card-body p-3.5 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted font-semibold d-block mb-1" style="font-size: 11.5px; letter-spacing: 0.02em;">ANTREAN ACC MATERI</span>
                        <h2 class="fw-bold text-dark mb-0" style="letter-spacing: -0.03em; font-size: 26px;">{{ $pendingMateriCount }}</h2>
                        <span class="text-muted small mt-1 d-block" style="font-size: 11px;">Menunggu persetujuan</span>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background-color: #f3e8ff; color: #8b5cf6; width: 54px; height: 54px;">
                        <iconify-icon icon="solar:shield-check-bold-duotone" class="fs-2"></iconify-icon>
                    </div>
                </div>
            </a>
        </div>
        @else
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('materi.guru.index') }}" class="card h-100 border shadow-sm card-clickable text-decoration-none" style="border-top: 3.5px solid #8b5cf6 !important; border-radius: 16px !important;">
                <div class="card-body p-3.5 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted font-semibold d-block mb-1" style="font-size: 11.5px; letter-spacing: 0.02em;">MANAJEMEN MATERI</span>
                        <h2 class="fw-bold text-dark mb-0" style="letter-spacing: -0.03em; font-size: 26px;">Upload</h2>
                        <span class="text-muted small mt-1 d-block" style="font-size: 11px;">Kelola materi pelajaran</span>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background-color: #f3e8ff; color: #8b5cf6; width: 54px; height: 54px;">
                        <iconify-icon icon="solar:document-add-bold-duotone" class="fs-2"></iconify-icon>
                    </div>
                </div>
            </a>
        </div>
        @endif

    </div>

    {{-- ========================================================== --}}
    {{-- 3. STUDENT DISTRIBUTION WITH PROGRESS BARS & ANNOUNCEMENT EMPTY STATE --}}
    {{-- ========================================================== --}}
    <div class="row g-3">
        
        {{-- Student Distribution Analytics (Progress Bars) --}}
        <div class="col-lg-7">
            <div class="card h-100 border shadow-sm" style="border-radius: 16px !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
                        <div>
                            <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15px;">
                                <iconify-icon icon="solar:chart-2-bold-duotone" class="text-primary fs-5"></iconify-icon> Distribusi Siswa per Kelas
                            </h6>
                            <span class="text-muted small" style="font-size: 11.5px;">Proporsi pendaftaran rombongan belajar</span>
                        </div>
                        {{-- TM-13: "Kelola Rombel" hanya tampil untuk admin/kepsek/super_admin, bukan guru --}}
                        @if(in_array(Auth::user()->role, ['admin', 'super_admin', 'kepsek', 'owner']))
                        <a href="/kelas" class="text-decoration-none text-primary font-semibold small" style="font-size: 12px;">Kelola Rombel &rarr;</a>
                        @endif
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @forelse($kelas_stats as $k)
                            @php
                                $percent = min(100, round(($k->jumlah_siswa / max(1, $maxSiswaInClass)) * 100));
                            @endphp
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark" style="font-size: 13px;">{{ $k->nama_kelas }}</span>
                                    <span class="badge bg-primary-subtle text-primary font-semibold" style="font-size: 11px;">{{ $k->jumlah_siswa }} Siswa</span>
                                </div>
                                <div class="progress" style="height: 10px; border-radius: 10px; background-color: #f1f5f9;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ max(10, $percent) }}%; border-radius: 10px; transition: width 0.6s ease;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4 small">Belum ada data kelas terdaftar.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Announcement Section (With Beautiful Empty State) --}}
        <div class="col-lg-5">
            <div class="card h-100 border shadow-sm" style="border-radius: 16px !important;">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 15px;">
                            <iconify-icon icon="solar:bell-bold-duotone" class="text-warning fs-5"></iconify-icon> Pengumuman Sekolah
                        </h6>
                        {{-- TM-13: "Kelola" pengumuman hanya untuk admin/kepsek/super_admin --}}
                        @if(in_array(Auth::user()->role, ['admin', 'super_admin', 'kepsek', 'owner']))
                        <a href="/pengumuman" class="text-decoration-none text-primary font-semibold small" style="font-size: 12px;">Kelola &rarr;</a>
                        @endif
                    </div>

                    @if($pengumuman->count() > 0)
                        <div class="d-flex flex-column gap-2.5">
                            @foreach($pengumuman->take(3) as $p)
                                <div class="p-3 rounded-3 border border-slate-100 bg-slate-50">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 13px; max-width: 220px;">{{ $p->judul }}</h6>
                                        <span class="text-muted" style="font-size: 10.5px;">{{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d M Y') }}</span>
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
                            <p class="text-muted small mb-3 mx-auto" style="max-width: 240px; font-size: 11.5px;">Tidak ada informasi kegiatan sekolah yang dipublikasikan saat ini.</p>
                            @if(in_array(Auth::user()->role, ['admin', 'super_admin', 'owner']))
                            <a href="/pengumuman" class="btn btn-outline-primary btn-sm rounded-3 font-semibold px-3 py-1.5" style="font-size: 11.5px;">
                                + Buat Pengumuman
                            </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>
@endsection