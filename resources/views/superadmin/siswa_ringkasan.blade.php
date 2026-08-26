@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

    @if(session('success'))
        <div class="alert alert-success border border-success-subtle shadow-none rounded-3 py-2 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
            <div><i class="ti ti-check me-2"></i>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ========================================================== --}}
    {{-- 1. HERO WELCOME CARD WITH DYNAMIC THEME GRADIENT --}}
    {{-- ========================================================== --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="background: var(--theme-hero-gradient, linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%)) !important; border-radius: 16px !important;">
        <div class="card-body p-4 text-white">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                
                {{-- Context & Title --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid rgba(255,255,255,0.4);">
                            <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Ringkasan Data Siswa (Mode Pusat)</h5>
                            <span class="badge bg-white text-dark bg-opacity-25 px-2.5 py-1 rounded-pill small font-semibold" style="font-size: 11px;">
                                Total {{ $ringkasan->sum('total') }} Siswa
                            </span>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Ringkasan agregat populasi peserta didik di seluruh sekolah (TK) yang terdaftar.</span>
                        </p>
                    </div>
                </div>

                {{-- Action Controls --}}
                @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
                <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                    <a href="/pendaftaran-siswa" class="btn btn-warning text-dark rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
                        <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-5"></iconify-icon> + Pendaftaran Siswa
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>

    @include('partials.owner_stepped_filter')

    {{-- STATS OVERVIEW CARDS --}}
    <div class="row g-3 mb-1">
        <div class="col-6 col-md-6">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
                <div class="card-body p-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">TOTAL SISWA TERDAFTAR</span>
                        <h3 class="fw-bold mb-0 text-primary">{{ $ringkasan->sum('total') }} <span class="fs-6 font-normal text-muted">Siswa</span></h3>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background-color: var(--primary-blue-soft); color: var(--primary-blue); width: 48px; height: 48px;">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-3"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #10b981 !important; border-radius: 16px !important;">
                <div class="card-body p-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">TOTAL SEKOLAH TERKONEKSI</span>
                        <h3 class="fw-bold mb-0 text-success">{{ $ringkasan->count() }} <span class="fs-6 font-normal text-muted">Sekolah</span></h3>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background-color: #d1fae5; color: #047857; width: 48px; height: 48px;">
                        <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-3"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL RINGKASAN DATA SISWA PER SEKOLAH --}}
    <div class="card border shadow-sm" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
        <div class="card-body p-4 d-flex flex-column">
            
            <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
                <div>
                    <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15.5px;">
                        <iconify-icon icon="solar:buildings-bold-duotone" class="text-primary fs-5"></iconify-icon> Populasi Siswa per Unit Sekolah (TK)
                    </h6>
                    <span class="text-muted small" style="font-size: 11.5px;">Pilih tombol "Pantau Sekolah Ini" untuk mengaktifkan filter sekolah tersebut.</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr class="border-bottom border-light-subtle">
                            <th width="45" class="ps-0 pb-2 text-center text-muted font-semibold" style="font-size: 11.5px;">#</th>
                            <th class="pb-2 text-muted font-semibold" style="font-size: 11.5px;">NAMA SEKOLAH (TK)</th>
                            <th class="pb-2 text-center text-muted font-semibold" style="font-size: 11.5px;">JUMLAH SISWA</th>
                            <th class="text-end pe-0 pb-2 text-muted font-semibold" style="font-size: 11.5px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ringkasan as $index => $item)
                        @php
                            $namaSekolah = $item->sekolah->nama_sekolah ?? ('Sekolah ID #' . $item->school_id);
                            $initials = strtoupper(substr($namaSekolah, 0, 2));
                        @endphp
                        <tr class="border-bottom border-light-subtle">
                            <td class="ps-0 py-3 text-center text-muted font-semibold" style="font-size: 13px;">{{ $index + 1 }}</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded text-primary bg-primary-subtle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 38px; height: 38px; font-size: 13px;">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0" style="font-size: 14px;">{{ $namaSekolah }}</div>
                                        @if(isset($item->sekolah->npsn))
                                            <div class="text-muted font-medium" style="font-size: 11px;">NPSN: {{ $item->sekolah->npsn }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1.5 font-semibold" style="font-size: 12px;">
                                    <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="align-middle me-1 me-0.5"></iconify-icon> {{ $item->total }} Siswa
                                </span>
                            </td>
                            <td class="py-3 text-end pe-0">
                                <form method="POST" action="{{ route('superadmin.switch_school') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="active_school_id" value="{{ $item->school_id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-3 px-3 py-1.5 font-semibold shadow-xs" style="font-size: 11.5px;">
                                        <iconify-icon icon="solar:eye-bold-duotone" class="align-middle me-1"></iconify-icon> Pantau Sekolah Ini
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="py-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                                        <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-2"></iconify-icon>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 14.5px;">Belum Ada Data Sekolah / Siswa</h6>
                                    <p class="text-muted small mb-0">Belum ada populasi siswa yang terdaftar di sekolah manapun.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold bg-slate-50">
                            <td colspan="2" class="ps-3 py-3 text-dark">Total Populer Seluruh Sekolah</td>
                            <td class="text-center py-3">
                                <span class="badge bg-dark rounded-pill px-3 py-1.5 font-semibold text-white" style="font-size: 12px;">
                                    {{ $ringkasan->sum('total') }} Siswa
                                </span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection
