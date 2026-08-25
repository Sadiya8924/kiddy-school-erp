@extends('layouts.admin')

@section('konten_halaman')
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
                            <iconify-icon icon="solar:health-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Perkembangan Fisik Siswa</h5>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Kelola data berat badan, tinggi badan, dan lingkar kepala siswa.</span>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('partials.owner_stepped_filter')

    @if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))
    
    {{-- FILTER FORM --}}
    <div class="card border shadow-sm" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
        <div class="card-body p-3.5">
            <form action="{{ route('perkembangan.index') }}" method="GET" class="row g-2.5 align-items-center">
                
                @if(in_array(auth()->user()->role, ['admin', 'super_admin', 'kepsek']))
                <div class="col-md-4">
                    <select name="kelas_id" class="form-select rounded-3 text-dark shadow-none font-medium" style="font-size: 12.5px;" onchange="this.form.submit()">
                        <option value="all">-- Semua Kelas --</option>
                        @foreach($list_kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div class="col-md-{{ in_array(auth()->user()->role, ['admin', 'super_admin', 'kepsek']) ? '6' : '10' }}">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><iconify-icon icon="solar:magnifer-linear" class="text-muted"></iconify-icon></span>
                        <input type="text" name="search" class="form-control border-start-0 shadow-none font-medium" style="font-size: 12.5px;" placeholder="Cari nama siswa..." value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 font-semibold shadow-xs" style="font-size: 12.5px;">Cari Data</button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL SISWA --}}
    <div class="card border shadow-sm" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
        <div class="card-body p-4 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
                <div>
                    <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15px;">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-primary fs-5"></iconify-icon> Daftar Siswa
                    </h6>
                    <span class="text-muted small" style="font-size: 11.5px;">Pilih siswa untuk melihat &amp; menginput riwayat grafik pertumbuhan fisik</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr class="border-bottom border-light-subtle">
                            <th width="40" class="ps-0 pb-2 text-center">#</th>
                            <th class="pb-2">NAMA SISWA</th>
                            <th class="text-center pe-0 pb-2" width="220">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $index => $s)
                        <tr class="border-bottom border-light-subtle">
                            <td class="ps-0 py-3 text-center text-muted font-semibold" style="font-size: 13px;">{{ $index + 1 }}</td>
                            <td class="py-3 fw-bold text-dark" style="font-size: 14px;">{{ $s->nama_lengkap }}</td>
                            <td class="text-center py-3 pe-0">
                                <a href="{{ route('perkembangan.show', $s->id) }}" class="btn btn-sm btn-primary rounded-3 font-semibold px-3 py-1 shadow-xs d-inline-flex align-items-center gap-1" style="font-size: 12px;">
                                    <iconify-icon icon="solar:chart-square-bold-duotone" class="fs-5"></iconify-icon> Laporan &amp; Grafik
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="py-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                                        <iconify-icon icon="solar:ghost-broken" class="fs-2"></iconify-icon>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada Data Siswa</h6>
                                    <p class="text-muted small mb-0">Data siswa tidak ditemukan untuk pencarian ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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