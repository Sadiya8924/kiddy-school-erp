@extends('layouts.admin')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    
    @if(session('success'))
        <div class="col-12 mb-0">
            <div class="alert alert-success border border-success-subtle shadow-none rounded py-2 px-3 mb-0 small fw-medium"><i class="ti ti-check me-2"></i>{{ session('success') }}</div>
        </div>
    @endif

    {{-- 1. HEADER WELCOME (Gaya Baru Solid) --}}
    <div class="col-12">
        <div class="card border mb-0 shadow-none" style="background-color: var(--primary-blue-soft); border-color: var(--primary-blue-border) !important;">
            <div class="card-body px-4 py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; background-color: var(--primary-blue); color: white;">
                        <iconify-icon icon="solar:user-hands-bold-duotone" class="fs-3"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: var(--primary-blue-dark); font-size: 16px;">Selamat datang, {{ Auth::user()->name }}</h6>
                        <p class="mb-0" style="color: var(--primary-blue); font-size: 13px;">
                            TA Aktif: <strong class="fw-bold">{{ $semesterAktif ? $semesterAktif->tahun_ajaran . ' (' . $semesterAktif->jenis . ')' : 'Belum Diset' }}</strong>
                        </p>
                    </div>
                </div>
                <div class="d-none d-md-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2" style="color: var(--primary-blue); font-size: 13px; font-weight: 500;">
                        <iconify-icon icon="solar:calendar-mark-line-duotone" class="fs-5"></iconify-icon>
                        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</span>
                    </div>
                    @if(in_array(Auth::user()->role, ['admin', 'super_admin']))
                        <a href="/pengaturan" class="btn btn-primary rounded btn-sm fw-medium px-3 py-1.5 d-flex align-items-center gap-1" style="font-size: 12px;">
                            <iconify-icon icon="solar:settings-bold-duotone"></iconify-icon> Pengaturan
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- 2. DASHBOARD GURU --}}
    {{-- ========================================================== --}}
    @if(Auth::user()->role == 'guru')
        <div class="col-12 mt-3">
            @if($school->is_rapor_open)
                <div class="alert bg-success-subtle text-success border border-success-subtle shadow-none rounded py-2 px-3 d-flex align-items-center mb-0" style="font-size: 13px;">
                    <iconify-icon icon="solar:unlock-bold-duotone" class="fs-5 me-2"></iconify-icon>
                    <div><strong class="fw-bold">Akses Rapor Terbuka.</strong> Anda dapat mengisi nilai rapor saat ini.</div>
                </div>
            @else
                <div class="alert bg-danger-subtle text-danger border border-danger-subtle shadow-none rounded py-2 px-3 d-flex align-items-center mb-0" style="font-size: 13px;">
                    <iconify-icon icon="solar:lock-keyhole-bold-duotone" class="fs-5 me-2"></iconify-icon>
                    <div><strong class="fw-bold">Akses Rapor Ditutup.</strong> Anda hanya dapat melihat data saat ini.</div>
                </div>
            @endif
        </div>

        <div class="col-md-6 mt-3">
            <div class="card mb-0 h-100 border shadow-none">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 text-dark d-flex align-items-center gap-2 border-bottom pb-3" style="font-size: 15px;">
                        <iconify-icon icon="solar:black-hole-bold-duotone" class="text-primary fs-5"></iconify-icon> Info Kelas Anda
                    </h6>
                    @if($guru_kelas)
                        <h4 class="fw-bold text-dark mb-1">{{ $guru_kelas->nama_kelas }}</h4>
                        <p class="text-muted fw-medium mb-4" style="font-size: 13px;">Tingkat {{ $guru_kelas->tingkat }} - Klp {{ $guru_kelas->kelompok }}</p>
                        
                        <div class="row text-center border-top pt-3 mt-1 g-2">
                            <div class="col-6 border-end">
                                <h3 class="fw-bold mb-0" style="color: var(--primary-blue);">{{ $guru_kelas->jumlah_siswa }}</h3>
                                <span class="text-muted fw-medium" style="font-size: 12px;">Total Siswa</span>
                            </div>
                            <div class="col-6">
                                @php $raporTerisi = $semesterAktif ? \App\Models\Rapor::whereHas('siswa', fn($q) => $q->whereHas('kelas', fn($q2) => $q2->where('kelas_id', $guru_kelas->id)))->where('semester_id', $semesterAktif->id)->count() : 0; @endphp
                                <h3 class="fw-bold text-success mb-0">{{ $raporTerisi }}</h3>
                                <span class="text-muted fw-medium" style="font-size: 12px;">Rapor Terisi</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <iconify-icon icon="solar:ghost-smile-bold-duotone" class="text-warning fs-1 mb-2"></iconify-icon>
                            <p class="mb-0 text-muted small fw-medium">Anda belum ditugaskan ke kelas manapun.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    {{-- ========================================================== --}}
    {{-- 3. DASHBOARD ADMIN / KEPSEK --}}
    {{-- ========================================================== --}}
    @elseif(in_array(Auth::user()->role, ['admin', 'kepsek', 'super_admin']))
        
        <div class="col-md-4 mt-3">
            <div class="card p-4 mb-0 h-100 border shadow-none d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: var(--primary-blue-soft); color: var(--primary-blue-border);">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-4"></iconify-icon>
                    </div>
                </div>
                <div>
                    <h2 class="fw-bold text-dark mb-0">{{ $school->total_siswa_aktif ?? 0 }}</h2>
                    <span class="text-muted fw-medium" style="font-size: 13px;">Total Siswa Aktif</span>
                </div>
            </div>
        </div>

        <div class="col-md-4 mt-3">
            <div class="card p-4 mb-0 h-100 border shadow-none d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #dcfce7; color: #86efac;">
                        <iconify-icon icon="solar:black-hole-bold-duotone" class="fs-4"></iconify-icon>
                    </div>
                </div>
                <div>
                    <h2 class="fw-bold text-dark mb-0">{{ $school->total_kelas ?? 0 }}</h2>
                    <span class="text-muted fw-medium" style="font-size: 13px;">Jumlah Rombel</span>
                </div>
            </div>
        </div>

        <div class="col-md-4 mt-3">
            <div class="card p-4 mb-0 h-100 border shadow-none d-flex flex-column justify-content-between" style="background-color: {{ $school->is_rapor_open ? '#f0fdf4' : '#fef2f2' }}; border-color: {{ $school->is_rapor_open ? '#bbf7d0' : '#fecaca' }} !important;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: {{ $school->is_rapor_open ? '#dcfce7' : '#fee2e2' }}; color: {{ $school->is_rapor_open ? '#166534' : '#991b1b' }};">
                        <iconify-icon icon="{{ $school->is_rapor_open ? 'solar:unlock-bold-duotone' : 'solar:lock-keyhole-bold-duotone' }}" class="fs-4"></iconify-icon>
                    </div>
                    @if(in_array(Auth::user()->role, ['admin', 'super_admin']))
                        <form action="{{ route('rapor.toggle') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm rounded fw-bold py-1 px-3" style="font-size: 11px; background-color: #fff; border: 1px solid {{ $school->is_rapor_open ? '#86efac' : '#fca5a5' }}; color: {{ $school->is_rapor_open ? '#166534' : '#991b1b' }};">Ubah</button>
                        </form>
                    @endif
                </div>
                <div>
                    <h2 class="fw-bold mb-0" style="color: {{ $school->is_rapor_open ? '#166534' : '#991b1b' }};">{{ $school->is_rapor_open ? 'TERBUKA' : 'DITUTUP' }}</h2>
                    <span class="fw-medium" style="font-size: 13px; color: {{ $school->is_rapor_open ? '#15803d' : '#b91c1c' }};">Status Pengisian Rapor</span>
                </div>
            </div>
        </div>

        {{-- Row Distribusi Kelas --}}
        <div class="col-lg-7 mt-3">
            <div class="card mb-0 h-100 border shadow-none">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-dark border-bottom pb-3" style="font-size: 15px;">Distribusi Siswa per Kelas</h6>
                    <div class="row gx-2 gy-2">
                        @forelse($kelas_stats as $k)
                        <div class="col-md-4 col-6">
                            <div class="card mb-0 shadow-none" style="border: 1px solid var(--border-color) !important; background-color: #f8fafc;">
                                <div class="card-body p-3 d-flex flex-column align-items-center text-center">
                                    <h6 class="fw-bold mb-2 text-dark" style="font-size: 13px;">{{ $k->nama_kelas }}</h6>
                                    <span class="badge rounded px-3 py-1.5" style="background-color: var(--primary-blue-soft); color: var(--primary-blue-dark); font-size: 12px; font-weight: 600;">
                                        {{ $k->jumlah_siswa }} Anak
                                    </span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12"><p class="text-muted small py-3 text-center">Belum ada data kelas.</p></div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ========================================================== --}}
    {{-- 4. PENGUMUMAN WIDGET (Semua Role) --}}
    {{-- ========================================================== --}}
    <div class="col-lg-{{ Auth::user()->role == 'guru' ? '6' : '5' }} mt-3">
        <div class="card mb-0 h-100 border shadow-none">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                        <iconify-icon icon="solar:bell-line-duotone" class="text-warning fs-5"></iconify-icon> Papan Pengumuman
                    </h6>
                    @if(in_array(Auth::user()->role, ['admin', 'super_admin']))
                        <a href="/pengumuman" class="btn btn-outline-light border rounded btn-sm px-3 py-1.5 text-muted fw-medium" style="font-size: 12px;">Kelola</a>
                    @endif
                </div>
                
                <div class="d-flex flex-column gap-3">
                    @forelse($pengumuman->take(4) as $p)
                        <div class="d-flex flex-column border-bottom border-light-subtle pb-3">
                            <h6 class="fw-semibold mb-1 text-dark text-truncate" style="font-size: 13px;">{{ $p->judul }}</h6>
                            <p class="text-muted mb-1 text-truncate" style="font-size: 12px;">{{ $p->isi }}</p>
                            <div class="d-flex align-items-center gap-1 text-muted mt-1" style="font-size: 11px;">
                                <iconify-icon icon="solar:calendar-mark-line-duotone"></iconify-icon>
                                <span>{{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-muted small fw-medium mb-0">Belum ada pengumuman.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection