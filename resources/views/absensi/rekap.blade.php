@extends('layouts.admin')

@push('styles')
<style>
    .badge-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-size: 11px;
        font-weight: 700;
        line-height: 1;
        text-transform: uppercase;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .badge-h { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-i { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .badge-s { background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
    .badge-a { background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    
    .table-attendance th, .table-attendance td {
        vertical-align: middle !important;
        border-color: #f1f5f9 !important;
    }
    .table-attendance tbody tr:hover {
        background-color: #f8fafc;
    }
</style>
@endpush

@section('konten_halaman')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-semibold text-dark mb-1">Rekap Presensi Siswa</h2>
        <span class="text-muted fs-3">Pantau kehadiran siswa berdasarkan rentang tanggal.</span>
    </div>
</div>

<!-- Form Filter -->
<div class="card w-100 border-0 shadow-sm position-relative overflow-hidden mb-4">
    <div class="card-body p-4">
        <h5 class="fw-semibold text-dark mb-3">Filter Rentang Waktu</h5>
        <form action="{{ route('absensi.rekap') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-medium text-dark small mb-1">Dari Tanggal</label>
                <input type="date" name="tgl_mulai" class="form-control border-1 text-dark shadow-none" value="{{ $mulai ?? '' }}" required>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-medium text-dark small mb-1">Sampai Tanggal</label>
                <input type="date" name="tgl_selesai" class="form-control border-1 text-dark shadow-none" value="{{ $selesai ?? '' }}" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary fw-medium rounded-pill w-100 py-2 shadow-none" style="background-color: #2d5cf7; border: none;">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Calculate Stats --}}
@php
    $totalH = 0;
    $totalI = 0;
    $totalS = 0;
    $totalA = 0;
    if(isset($rekap) && isset($range_tanggal)) {
        foreach($rekap as $item) {
            foreach($range_tanggal as $tgl) {
                $huruf = strtoupper(substr($item['absen'][$tgl] ?? '', 0, 1));
                if ($huruf == 'H') $totalH++;
                elseif ($huruf == 'I') $totalI++;
                elseif ($huruf == 'S') $totalS++;
                elseif ($huruf == 'A') $totalA++;
            }
        }
    }
@endphp

@if(isset($rekap) && count($rekap) > 0)
<!-- Stats Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border mb-0 shadow-none p-3 h-100" style="border-left: 4px solid #10b981 !important; background-color: #f0fdf4;">
            <div class="text-muted small fw-bold mb-1" style="color: #047857 !important;">TOTAL HADIR</div>
            <h3 class="fw-bold text-dark mb-0">{{ $totalH }} <span class="fs-3 fw-normal text-muted">hari</span></h3>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border mb-0 shadow-none p-3 h-100" style="border-left: 4px solid #f59e0b !important; background-color: #fffbeb;">
            <div class="text-muted small fw-bold mb-1" style="color: #b45309 !important;">TOTAL IZIN</div>
            <h3 class="fw-bold text-dark mb-0">{{ $totalI }} <span class="fs-3 fw-normal text-muted">hari</span></h3>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border mb-0 shadow-none p-3 h-100" style="border-left: 4px solid #3b82f6 !important; background-color: #eff6ff;">
            <div class="text-muted small fw-bold mb-1" style="color: #1d4ed8 !important;">TOTAL SAKIT</div>
            <h3 class="fw-bold text-dark mb-0">{{ $totalS }} <span class="fs-3 fw-normal text-muted">hari</span></h3>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border mb-0 shadow-none p-3 h-100" style="border-left: 4px solid #ef4444 !important; background-color: #fef2f2;">
            <div class="text-muted small fw-bold mb-1" style="color: #b91c1c !important;">TOTAL ALFA</div>
            <h3 class="fw-bold text-dark mb-0">{{ $totalA }} <span class="fs-3 fw-normal text-muted">hari</span></h3>
        </div>
    </div>
</div>
@endif

<!-- Tabel Matriks -->
<div class="card w-100 border-0 shadow-sm position-relative overflow-hidden">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <h5 class="fw-bold text-dark mb-0">Matriks Presensi Siswa</h5>
            
            <!-- Legend / Keterangan Badges -->
            <div class="d-flex gap-2 flex-wrap">
                <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-success-subtle text-success border border-success-subtle fw-semibold" style="font-size: 11px;">
                    <span class="badge-status badge-h" style="width: 16px; height: 16px; font-size: 8px;">H</span> Hadir
                </span>
                <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-warning-subtle text-warning border border-warning-subtle fw-semibold" style="font-size: 11px;">
                    <span class="badge-status badge-i" style="width: 16px; height: 16px; font-size: 8px;">I</span> Izin
                </span>
                <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-info-subtle text-info border border-info-subtle fw-semibold" style="font-size: 11px;">
                    <span class="badge-status badge-s" style="width: 16px; height: 16px; font-size: 8px;">S</span> Sakit
                </span>
                <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-danger-subtle text-danger border border-danger-subtle fw-semibold" style="font-size: 11px;">
                    <span class="badge-status badge-a" style="width: 16px; height: 16px; font-size: 8px;">A</span> Alfa
                </span>
            </div>
        </div>

        <div class="table-responsive rounded-3 border">
            <table class="table table-attendance align-middle mb-0 text-nowrap">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 ps-3 text-start text-dark fw-bold" style="min-width: 240px; border-bottom: 2px solid #e2e8f0;">Nama Siswa</th>
                        @if(isset($range_tanggal))
                            @foreach($range_tanggal as $tgl)
                                @php $isWeekend = date('N', strtotime($tgl)) >= 6; @endphp
                                <th class="py-2 text-center text-dark fw-bold @if($isWeekend) bg-light text-muted @endif" style="width: 55px; border-bottom: 2px solid #e2e8f0; border-left: 1px solid #f1f5f9;">
                                    <div class="lh-1">{{ date('d', strtotime($tgl)) }}</div>
                                    <div class="text-muted fw-medium mt-1" style="font-size: 9px; letter-spacing: 0.5px;">{{ date('M', strtotime($tgl)) }}</div>
                                </th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(isset($rekap) && count($rekap) > 0)
                        @foreach($rekap as $item)
                        <tr>
                            <td class="fw-semibold text-dark text-start py-3 ps-3">{{ $item['nama'] }}</td>
                            @foreach($range_tanggal as $tgl)
                                @php 
                                    $isWeekend = date('N', strtotime($tgl)) >= 6;
                                    $status = $item['absen'][$tgl];
                                    $huruf = strtoupper(substr($status, 0, 1)); 
                                @endphp
                                <td class="py-3 text-center @if($isWeekend) bg-light @endif" style="border-left: 1px solid #f1f5f9;">
                                    @if($huruf == 'H')
                                        <span class="badge-status badge-h">H</span>
                                    @elseif($huruf == 'I')
                                        <span class="badge-status badge-i">I</span>
                                    @elseif($huruf == 'S')
                                        <span class="badge-status badge-s">S</span>
                                    @elseif($huruf == 'A')
                                        <span class="badge-status badge-a">A</span>
                                    @else
                                        <span class="text-muted opacity-50">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="100" class="text-center py-5 text-muted bg-transparent">
                                <iconify-icon icon="solar:calendar-mark-line-duotone" class="fs-1 d-block mb-2 text-muted"></iconify-icon>
                                Pilih rentang tanggal terlebih dahulu untuk melihat data rekap presensi.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection