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
                            <iconify-icon icon="solar:printer-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Cetak Rapor Siswa</h5>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Pilih siswa untuk mencetak hasil pembelajaran.</span>
                        </p>
                    </div>
                </div>

                {{-- Status --}}
                <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                    <span class="badge bg-white text-primary font-semibold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1.5" style="font-size: 12px;">
                        <iconify-icon icon="solar:printer-bold-duotone" class="fs-5"></iconify-icon> Siap dicetak ke PDF
                    </span>
                </div>

            </div>
        </div>
    </div>

    @include('partials.owner_stepped_filter')

    @if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))
    
    {{-- FILTER CARD --}}
    <div class="card border shadow-sm" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
        <div class="card-body p-3.5">
            <form action="/cetak-rapor" method="GET" class="row g-2.5 align-items-center">
                @if(auth()->user()->role == 'admin')
                <div class="col-md-3">
                    <select name="kelas_id" class="form-select rounded-3 font-medium text-dark shadow-none" onchange="this.form.submit()" style="font-size: 12.5px;">
                        <option value="all">-- Semua Kelas --</option>
                        @foreach($list_kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-{{ auth()->user()->role == 'admin' ? '7' : '10' }}">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><iconify-icon icon="solar:magnifer-linear" class="text-muted"></iconify-icon></span>
                        <input type="text" name="search" class="form-control border-start-0 shadow-none font-medium" placeholder="Cari berdasarkan Nama Lengkap atau NIK..." value="{{ request('search') }}" style="font-size: 12.5px;">
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
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-primary fs-5"></iconify-icon> Daftar Siswa Rapor
                    </h6>
                    <span class="text-muted small" style="font-size: 11.5px;">Klik tombol cetak untuk membuka dokumen PDF rapor resmi</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr class="border-bottom border-light-subtle">
                            <th width="40" class="ps-0 pb-2 text-center">#</th>
                            <th class="pb-2">NAMA SISWA</th>
                            <th class="text-center pb-2">NIK</th>
                            <th class="text-center pb-2">KELAS</th>
                            <th class="text-center pe-0 pb-2" width="160">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $index => $s)
                        <tr class="border-bottom border-light-subtle">
                            <td class="ps-0 py-3 text-center text-muted font-semibold" style="font-size: 13px;">{{ $index + 1 }}</td>
                            <td class="fw-bold text-dark py-3" style="font-size: 14px;">{{ $s->nama_lengkap }}</td>
                            <td class="text-center py-3 text-muted font-medium" style="font-size: 12.5px;">{{ $s->nik ?? '-' }}</td>
                            <td class="text-center py-3">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px;">{{ $s->nama_kelas_aktif }}</span>
                            </td>
                            <td class="text-center py-3 pe-0">
                                <a href="/rapor/{{ $s->id }}" class="btn btn-sm btn-danger rounded-3 font-semibold px-3 py-1 shadow-xs d-inline-flex align-items-center gap-1" style="font-size: 12px;">
                                    <iconify-icon icon="solar:printer-bold-duotone" class="fs-5"></iconify-icon> Cetak PDF
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                                        <iconify-icon icon="solar:printer-bold-duotone" class="fs-2"></iconify-icon>
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

</div>
@endsection