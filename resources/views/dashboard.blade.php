@extends('layouts.admin')

@section('konten_halaman')
<div class="row">
    @if(session('success'))
        <div class="col-12 mb-3">
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="col-12 mb-3">
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(Auth::user()->role == 'admin' && isset($school))
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-4 {{ $school->is_rapor_open ? 'bg-primary-subtle' : 'bg-danger-subtle' }}">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center p-4">
                <div class="mb-3 mb-md-0 d-flex align-items-center gap-3">
                    <iconify-icon icon="solar:lock-keyhole-bold-duotone" class="{{ $school->is_rapor_open ? 'text-primary' : 'text-danger' }}" style="font-size: 40px;"></iconify-icon>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark">Status Akses Pengisian Rapor</h5>
                        <p class="mb-0 text-muted small">Saat ini akses pengisian rapor oleh Guru sedang 
                            <strong class="{{ $school->is_rapor_open ? 'text-primary' : 'text-danger' }}">
                                {{ $school->is_rapor_open ? 'DIBUKA' : 'DITUTUP' }}
                            </strong>.
                        </p>
                    </div>
                </div>
                <form action="{{ route('rapor.toggle') }}" method="POST">
                    @csrf
                    @if($school->is_rapor_open)
                        <button type="submit" class="btn btn-danger rounded-pill fw-bold shadow-sm px-4">
                            <i class="bi bi-lock-fill me-1"></i> Tutup Akses Rapor
                        </button>
                    @else
                        <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-sm px-4">
                            <i class="bi bi-unlock-fill me-1"></i> Buka Akses Rapor
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
    @endif

    <div class="col-12 mb-4">
        <div class="card bg-success-subtle shadow-none position-relative overflow-hidden mb-4">
            <div class="card-body px-4 py-4">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-bold text-success mb-2">👋 Selamat Datang, {{ Auth::user()->name }}!</h4>
                        <p class="mb-0 fs-4 text-dark">Semangat mendidik anak-anak hari ini. Berikut adalah ringkasan data sistem.</p>
                    </div>
                    <div class="col-3 text-end d-none d-md-block">
                        <iconify-icon icon="solar:school-bold-duotone" class="text-success opacity-50" style="font-size: 60px;"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- WIDGET PENGUMUMAN --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
            <h5 class="fw-bold text-dark mb-0">
                <i class="ti ti-bell-ringing text-warning me-2"></i> Pengumuman Terbaru
            </h5>
        </div>
        <div class="card-body p-4">

            {{-- TAMPILKAN 3 PENGUMUMAN PERTAMA --}}
            @forelse($pengumuman->take(3) as $p)
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="fw-bold text-primary mb-0">{{ $p->judul }}</h6>
                        {{-- Badge Tanggal Dibuat --}}
                        <span class="badge bg-light text-muted small border">
                            <i class="ti ti-calendar me-1"></i> Dibuat: {{ $p->created_at->format('d M Y') }}
                        </span>
                    </div>
                    {{-- Info Tanggal Pelaksanaan --}}
                    <div class="text-danger fw-medium small mb-2">
                        <i class="ti ti-clock me-1"></i> Pelaksanaan: {{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d F Y') }}
                    </div>
                    <p class="mb-0 text-dark small" style="text-align: justify;">{{ $p->isi }}</p>
                </div>
            @empty
                <div class="text-center py-3">
                    <p class="text-muted small mb-0">Belum ada pengumuman dari sekolah.</p>
                </div>
            @endforelse

            {{-- SISA PENGUMUMAN (DISEMBUNYIKAN DALAM COLLAPSE) --}}
            @if($pengumuman->count() > 3)
                <div class="collapse" id="pengumumanLainnya">
                    @foreach($pengumuman->skip(3) as $p)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold text-primary mb-0">{{ $p->judul }}</h6>
                                <span class="badge bg-light text-muted small border">
                                    <i class="ti ti-calendar me-1"></i> Dibuat: {{ $p->created_at->format('d M Y') }}
                                </span>
                            </div>
                            <div class="text-danger fw-medium small mb-2">
                                <i class="ti ti-clock me-1"></i> Pelaksanaan: {{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d F Y') }}
                            </div>
                            <p class="mb-0 text-dark small" style="text-align: justify;">{{ $p->isi }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- TOMBOL TOGGLE COLLAPSE --}}
                <div class="text-center mt-2">
                    <button class="btn btn-light btn-sm rounded-pill px-4 small fw-medium text-primary shadow-none" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#pengumumanLainnya" 
                            aria-expanded="false" 
                            aria-controls="pengumumanLainnya"
                            onclick="this.innerHTML = this.getAttribute('aria-expanded') === 'true' ? 'Tutup Pengumuman <i class=\'ti ti-chevron-up ms-1\'></i>' : 'Lihat Semua Pengumuman <i class=\'ti ti-chevron-down ms-1\'></i>'">
                        Lihat Semua Pengumuman <i class="ti ti-chevron-down ms-1"></i>
                    </button>
                </div>
            @endif

        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-top border-success border-3 shadow-sm position-relative overflow-hidden h-100">
            <div class="card-body text-center p-4">
                <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-success" style="font-size: 110px; position: absolute; right: -15px; bottom: -20px; opacity: 0.15; z-index: 0;"></iconify-icon>

                <div class="position-relative" style="z-index: 1;">
                    <p class="text-uppercase text-muted fw-bold mb-2 fs-3">Total Siswa Aktif</p>
                    <h2 class="fw-bold text-success mb-0 d-flex align-items-center justify-content-center gap-2" style="font-size: 2.5rem;">
                        {{ $school->total_siswa_aktif ?? 0 }} <span class="fs-4 text-muted fw-medium mt-2">Anak</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-top border-primary border-3 shadow-sm position-relative overflow-hidden h-100">
            <div class="card-body text-center p-4">
                <iconify-icon icon="solar:document-text-bold-duotone" class="text-primary" style="font-size: 110px; position: absolute; right: -15px; bottom: -20px; opacity: 0.1; z-index: 0;"></iconify-icon>

                <div class="position-relative" style="z-index: 1;">
                    <p class="text-uppercase text-muted fw-bold mb-2 fs-3">Rapor Terisi (Smt Ini)</p>
                    <h2 class="fw-bold text-primary mb-0 d-flex align-items-center justify-content-center gap-2" style="font-size: 2.5rem;">
                        {{-- Logika untuk menghitung rapor yang sudah diisi di semester aktif --}}
                        @php
                            $raporTerisi = $semesterAktif ? \App\Models\Rapor::where('school_id', Auth::user()->school_id)->where('semester_id', $semesterAktif->id)->count() : 0;
                        @endphp
                        {{ $raporTerisi }} <span class="fs-4 text-muted fw-medium mt-2">Dokumen</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-top border-warning border-3 shadow-sm position-relative overflow-hidden h-100">
            <div class="card-body text-center p-4">
                <iconify-icon icon="solar:calendar-date-bold-duotone" class="text-warning" style="font-size: 110px; position: absolute; right: -15px; bottom: -20px; opacity: 0.15; z-index: 0;"></iconify-icon>

                <div class="position-relative" style="z-index: 1;">
                    <p class="text-uppercase text-muted fw-bold mb-2 fs-3">Tahun Ajaran</p>
                    <h2 class="fw-bold text-warning mb-0 d-flex align-items-center justify-content-center gap-2" style="font-size: 2.5rem;">
                        {{ $semesterAktif ? $semesterAktif->tahun_ajaran : '-' }} 
                        <span class="fs-4 text-muted fw-medium mt-2">{{ $semesterAktif ? $semesterAktif->jenis : '' }}</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection