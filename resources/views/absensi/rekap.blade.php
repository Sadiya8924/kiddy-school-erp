@extends('layouts.admin')

@section('title', 'Rekap Presensi Siswa - ' . ($school->nama_sekolah ?? 'TK CERIA'))

@push('styles')
<!-- Flatpickr CSS & Custom Styling -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-calendar {
        border-radius: 16px !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.12) !important;
        border: 1px solid #e2e8f0 !important;
        font-family: inherit !important;
        padding: 8px !important;
        background: #ffffff !important;
    }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
        background: #5d87ff !important;
        border-color: #5d87ff !important;
        border-radius: 8px !important;
        color: #fff !important;
        font-weight: bold !important;
    }
    .flatpickr-day.today {
        border-color: #5d87ff !important;
    }
    .flatpickr-day:hover {
        background: #eef2ff !important;
        border-radius: 8px !important;
    }
    .flatpickr-months .flatpickr-month {
        background: transparent !important;
        color: #1e293b !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months {
        font-weight: 700 !important;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
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

    /* PRINT STYLES (Dioptimalkan untuk Rekap Bulanan 1 Halaman Landscape) */
    @media print {
        @page {
            size: A4 landscape;
            margin: 8mm 10mm 8mm 10mm;
        }
        html, body {
            background-color: #fff !important;
            color: #000 !important;
            font-size: 9pt !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .left-sidebar, .top-header, .no-print, header, nav, .btn, .card-filter, .alert, .modal {
            display: none !important;
        }
        .body-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .container-fluid {
            padding: 0 !important;
            margin: 0 !important;
        }
        .print-only {
            display: block !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            padding: 0 !important;
            margin-bottom: 5px !important;
        }
        .card-body {
            padding: 0 !important;
        }
        .table-responsive {
            overflow: visible !important;
        }
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-bottom: 10px !important;
        }
        .table th, .table td {
            border: 1px solid #333 !important;
            padding: 3px 4px !important;
            font-size: 8.5pt !important;
            vertical-align: middle !important;
        }
        .table th {
            background-color: #f1f5f9 !important;
            color: #000 !important;
            font-weight: bold !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .badge-status {
            width: 18px !important;
            height: 18px !important;
            font-size: 8pt !important;
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
            color: #000 !important;
            font-weight: bold !important;
        }
        .badge-h { color: #15803d !important; }
        .badge-i { color: #b45309 !important; }
        .badge-s { color: #1d4ed8 !important; }
        .badge-a { color: #b91c1c !important; }
        
        .print-header-kop {
            margin-bottom: 10px !important;
            padding-bottom: 5px !important;
            border-bottom: 2px solid #000 !important;
        }
        .print-signatures {
            margin-top: 15px !important;
            page-break-inside: avoid;
        }
        .print-signatures p {
            font-size: 8.5pt !important;
            margin-bottom: 35px !important;
        }
    }
    .print-only {
        display: none;
    }
</style>
@endpush

@section('konten_halaman')

@php
    // HELPER PENERJEMAH TANGGAL, BULAN, & HARI BAHASA INDONESIA
    $bulanIndo = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $hariIndo = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
    ];
    
    $formatTglIndo = function($tglStr) use ($bulanIndo) {
        if(!$tglStr) return '-';
        $time = strtotime($tglStr);
        $d = date('d', $time);
        $m = (int)date('m', $time);
        $y = date('Y', $time);
        return $d . ' ' . ($bulanIndo[$m] ?? date('F', $time)) . ' ' . $y;
    };
    
    $formatHariIndo = function($tglStr) use ($hariIndo) {
        if(!$tglStr) return '-';
        $dayEng = date('l', strtotime($tglStr));
        return $hariIndo[$dayEng] ?? $dayEng;
    };

    $formatBulanSingkat = function($tglStr) use ($bulanIndo) {
        if(!$tglStr) return '-';
        $m = (int)date('m', strtotime($tglStr));
        return substr($bulanIndo[$m] ?? '', 0, 3);
    };

    $alamatSekolah = !empty($school->alamat) ? $school->alamat : ($school->yayasan->alamat ?? 'Alamat Sekolah Belum Diatur');
    $teleponSekolah = !empty($school->telepon) ? '| Telp: ' . $school->telepon : '';
@endphp

<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

{{-- HEADER KOP UNTUK PRINT / CETAK KELAS (COMPACT & RINGKAS) --}}
<div class="print-only print-header-kop text-center">
    <h5 class="fw-bold mt-2 text-decoration-underline text-dark mb-1">REKAPITULASI PRESENSI KEHADIRAN SISWA</h5>
    <p class="small mb-0">Periode: {{ $formatTglIndo($mulai) }} s/d {{ $formatTglIndo($selesai) }}</p>
</div>

{{-- ========================================================== --}}
{{-- 1. HERO WELCOME CARD WITH SOFT BLUE GRADIENT --}}
{{-- ========================================================== --}}
<div class="card border-0 shadow-sm overflow-hidden no-print" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%); border-radius: 16px !important;">
    <div class="card-body p-4 text-white">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            
            {{-- User Greeting & Context --}}
            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid rgba(255,255,255,0.4);">
                        <iconify-icon icon="solar:file-check-bold-duotone"></iconify-icon>
                    </div>
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Rekap Presensi Siswa</h5>
                    </div>
                    <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                        <span>{{ $school->nama_sekolah ?? 'TK CERIA' }} — Pantau &amp; cetak rekapitulasi kehadiran per kelas maupun per siswa.</span>
                    </p>
                </div>
            </div>

            {{-- Action Controls --}}
            @if(isset($rekap) && count($rekap) > 0)
            <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                <button onclick="window.print()" class="btn btn-warning text-dark rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
                    <iconify-icon icon="solar:printer-bold-duotone" class="fs-5"></iconify-icon> Cetak Rekap Semua Siswa
                </button>
            </div>
            @endif

        </div>
    </div>
</div>

@include('partials.owner_stepped_filter')

@if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))

<!-- FORM FILTER (KELAS, SEARCH SISWA, TANGGAL) -->
<div class="card border shadow-sm no-print card-filter" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
    <div class="card-body p-3.5">
        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 14.5px;">
            <iconify-icon icon="solar:filter-bold-duotone" class="text-primary fs-5"></iconify-icon> Filter Data Presensi ({{ $school->nama_sekolah ?? 'TK CERIA' }})
        </h6>
        <form action="{{ route('absensi.rekap') }}" method="GET" class="row g-3 align-items-end">
            @if(isset($list_kelas) && count($list_kelas) > 0)
            <div class="col-md-3">
                <label class="form-label font-semibold text-dark small mb-1">Filter Kelas</label>
                <select name="kelas_id" class="form-select rounded-3 text-dark shadow-none font-medium" style="font-size: 12.5px;">
                    <option value="all">-- Semua Kelas --</option>
                    @foreach($list_kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="col-md-3">
                <label class="form-label font-semibold text-dark small mb-1">Cari Nama Siswa / NIK</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><iconify-icon icon="solar:magnifer-linear" class="text-muted"></iconify-icon></span>
                    <input type="text" name="search" id="searchBackendInput" class="form-control border-start-0 shadow-none font-medium" placeholder="Kata kunci nama..." value="{{ request('search') }}" style="font-size: 12.5px;">
                </div>
            </div>

            <div class="col-md-2">
                <label class="form-label font-semibold text-dark small mb-1">Dari Tanggal</label>
                <input type="text" name="tgl_mulai" id="tglMulaiPicker" class="form-control rounded-3 text-dark shadow-none bg-white font-medium" value="{{ $mulai ?? date('Y-m-01') }}" required readonly style="font-size: 12.5px;">
            </div>

            <div class="col-md-2">
                <label class="form-label font-semibold text-dark small mb-1">Sampai Tanggal</label>
                <input type="text" name="tgl_selesai" id="tglSelesaiPicker" class="form-control rounded-3 text-dark shadow-none bg-white font-medium" value="{{ $selesai ?? date('Y-m-d') }}" required readonly style="font-size: 12.5px;">
            </div>

            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary font-semibold rounded-3 py-1.5 shadow-xs" style="font-size: 12.5px;">
                    Tampilkan Rekap
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Calculate Total Stats --}}
@php
    $totalH = 0; $totalI = 0; $totalS = 0; $totalA = 0;
    if(isset($rekap) && isset($range_tanggal)) {
        foreach($rekap as $item) {
            $totalH += $item['total_h'];
            $totalI += $item['total_i'];
            $totalS += $item['total_s'];
            $totalA += $item['total_a'];
        }
    }
@endphp

@if(isset($rekap) && count($rekap) > 0)
<!-- STATS SUMMARY CARDS (MODE DASHBOARD) -->
<div class="row g-3 mb-4 no-print">
    <div class="col-6 col-lg-3">
        <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #10b981 !important; border-radius: 16px !important;">
            <div class="card-body p-3.5">
                <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">TOTAL HADIR</span>
                <h3 class="fw-bold mb-0 text-success">{{ $totalH }} <span class="fs-6 font-normal text-muted">kali</span></h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #f59e0b !important; border-radius: 16px !important;">
            <div class="card-body p-3.5">
                <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">TOTAL IZIN</span>
                <h3 class="fw-bold mb-0 text-warning">{{ $totalI }} <span class="fs-6 font-normal text-muted">kali</span></h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #06b6d4 !important; border-radius: 16px !important;">
            <div class="card-body p-3.5">
                <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">TOTAL SAKIT</span>
                <h3 class="fw-bold mb-0 text-info">{{ $totalS }} <span class="fs-6 font-normal text-muted">kali</span></h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #ef4444 !important; border-radius: 16px !important;">
            <div class="card-body p-3.5">
                <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">TOTAL ALFA</span>
                <h3 class="fw-bold mb-0 text-danger">{{ $totalA }} <span class="fs-6 font-normal text-muted">kali</span></h3>
            </div>
        </div>
    </div>
</div>

<!-- STATS SUMMARY RINGKAS (MODE CETAK PRINT) -->
<div class="print-only mb-2 p-2 bg-light border text-dark fw-bold small text-center rounded">
    TOTAL HADIR: {{ $totalH }} kali &nbsp;|&nbsp; TOTAL IZIN: {{ $totalI }} kali &nbsp;|&nbsp; TOTAL SAKIT: {{ $totalS }} kali &nbsp;|&nbsp; TOTAL ALFA: {{ $totalA }} kali
</div>
@endif

<!-- TABEL REKAP & MATRIKS PRESENSI -->
<div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 no-print">
            <div class="d-flex align-items-center gap-3">
                <h6 class="fw-bold text-dark mb-0" style="font-size: 15px;">Matriks Presensi Kehadiran Siswa</h6>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 font-semibold" style="font-size: 11.5px;">
                    Total: {{ count($rekap ?? []) }} Siswa
                </span>
            </div>
            
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <!-- Search Live Input JS -->
                <div class="input-group input-group-sm style-search" style="width: 250px;">
                    <span class="input-group-text bg-light border-end-0"><iconify-icon icon="solar:magnifer-linear" class="text-muted"></iconify-icon></span>
                    <input type="text" id="liveSearchRekap" class="form-control border-start-0 shadow-none" placeholder="Filter nama siswa di sini..." onkeyup="filterLiveRekap()">
                </div>

                <!-- Legend Badges -->
                <div class="d-flex gap-2 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-1 px-2.5 py-1 rounded-pill bg-success-subtle text-success border border-success-subtle fw-semibold" style="font-size: 11px;">
                        <span class="badge-status badge-h" style="width: 16px; height: 16px; font-size: 8px;">H</span> Hadir
                    </span>
                    <span class="d-inline-flex align-items-center gap-1 px-2.5 py-1 rounded-pill bg-warning-subtle text-warning border border-warning-subtle fw-semibold" style="font-size: 11px;">
                        <span class="badge-status badge-i" style="width: 16px; height: 16px; font-size: 8px;">I</span> Izin
                    </span>
                    <span class="d-inline-flex align-items-center gap-1 px-2.5 py-1 rounded-pill bg-info-subtle text-info border border-info-subtle fw-semibold" style="font-size: 11px;">
                        <span class="badge-status badge-s" style="width: 16px; height: 16px; font-size: 8px;">S</span> Sakit
                    </span>
                    <span class="d-inline-flex align-items-center gap-1 px-2.5 py-1 rounded-pill bg-danger-subtle text-danger border border-danger-subtle fw-semibold" style="font-size: 11px;">
                        <span class="badge-status badge-a" style="width: 16px; height: 16px; font-size: 8px;">A</span> Alfa
                    </span>
                </div>
            </div>
        </div>

        <div class="table-responsive rounded-3 border">
            <table class="table table-attendance align-middle mb-0 text-nowrap" id="tableRekapPresensi">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 ps-3 text-start text-dark fw-bold" style="min-width: 180px; border-bottom: 2px solid #e2e8f0;">Nama Siswa</th>
                        <th class="py-3 text-center text-dark fw-bold" style="width: 80px; border-bottom: 2px solid #e2e8f0;">Kelas</th>
                        @if(isset($range_tanggal))
                            @foreach($range_tanggal as $tgl)
                                @php $isWeekend = date('N', strtotime($tgl)) >= 6; @endphp
                                <th class="py-2 text-center text-dark fw-bold @if($isWeekend) bg-light text-muted @endif" style="width: 32px; border-bottom: 2px solid #e2e8f0; border-left: 1px solid #f1f5f9;">
                                    <div class="lh-1" style="font-size: 10px;">{{ date('d', strtotime($tgl)) }}</div>
                                    <div class="text-muted fw-medium mt-1" style="font-size: 8px;">{{ $formatBulanSingkat($tgl) }}</div>
                                </th>
                            @endforeach
                        @endif
                        <th class="py-3 text-center text-success fw-bold" style="width: 35px; border-bottom: 2px solid #e2e8f0; border-left: 2px solid #e2e8f0;">H</th>
                        <th class="py-3 text-center text-warning fw-bold" style="width: 35px; border-bottom: 2px solid #e2e8f0;">I</th>
                        <th class="py-3 text-center text-info fw-bold" style="width: 35px; border-bottom: 2px solid #e2e8f0;">S</th>
                        <th class="py-3 text-center text-danger fw-bold" style="width: 35px; border-bottom: 2px solid #e2e8f0;">A</th>
                        <th class="py-3 text-center text-dark fw-bold no-print" style="width: 45px; border-bottom: 2px solid #e2e8f0; border-left: 2px solid #e2e8f0;">Rekap</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($rekap) && count($rekap) > 0)
                        @foreach($rekap as $item)
                        <tr class="rekap-row">
                            <td class="fw-bold text-dark text-start py-3 ps-3 rekap-nama">{{ $item['nama'] }}</td>
                            <td class="text-center py-3">
                                <span class="badge bg-light text-dark border fw-medium">{{ $item['kelas'] }}</span>
                            </td>
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
                            <td class="py-3 text-center fw-bold text-success" style="border-left: 2px solid #e2e8f0;">{{ $item['total_h'] }}</td>
                            <td class="py-3 text-center fw-bold text-warning">{{ $item['total_i'] }}</td>
                            <td class="py-3 text-center fw-bold text-info">{{ $item['total_s'] }}</td>
                            <td class="py-3 text-center fw-bold text-danger">{{ $item['total_a'] }}</td>
                            
                            {{-- TOMBOL REKAP PER SISWA (HANYA ICON TOMBOL TANPA TEKS) --}}
                            <td class="py-3 text-center no-print" style="border-left: 2px solid #e2e8f0;">
                                <button type="button" class="btn btn-sm btn-primary rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" data-bs-toggle="modal" data-bs-target="#modalRekapIndividu{{ $item['siswa_id'] }}" title="Cetak / Rekap Absensi {{ $item['nama'] }}">
                                    <iconify-icon icon="solar:printer-bold-duotone" class="fs-5"></iconify-icon>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="100" class="text-center py-5 text-muted bg-transparent">
                                <iconify-icon icon="solar:calendar-mark-line-duotone" class="fs-1 d-block mb-2 text-muted"></iconify-icon>
                                Pilih rentang tanggal & kelas terlebih dahulu untuk melihat data rekap presensi.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- LEMBAR TANDA TANGAN UNTUK PRINT KELAS --}}
<div class="print-only print-signatures mt-4">
    <div class="row text-center">
        <div class="col-6">
            <p class="mb-4">Mengetahui,<br><strong>Kepala Sekolah</strong></p>
            <p class="mt-4 mb-0"><u>( {{ $school->kepala_sekolah ?? '......................' }} )</u></p>
        </div>
        <div class="col-6">
            <p class="mb-4">Tanggal: {{ $formatTglIndo(date('Y-m-d')) }}<br><strong>Wali Kelas</strong></p>
            <p class="mt-4 mb-0"><u>( {{ auth()->user()->name }} )</u></p>
        </div>
    </div>
</div>

{{-- MODAL REKAP INDIVIDUAL SISWA --}}
@if(isset($rekap))
    @foreach($rekap as $item)
    <div class="modal fade" id="modalRekapIndividu{{ $item['siswa_id'] }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4 p-4">
                    <div class="d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:user-bold-duotone" class="fs-3"></iconify-icon>
                        <h5 class="modal-title fw-bold text-white mb-0">Rekap Presensi Siswa: {{ $item['nama'] }}</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="printableRekapSiswa{{ $item['siswa_id'] }}">
                    {{-- KOP TK ASAL SISWA (DINAMIS DARI DATABASE) --}}
                    <div class="text-center border-bottom pb-3 mb-4">
                        <h5 class="fw-bold mt-3 text-decoration-underline text-dark">REKAPITULASI PRESENSI KEHADIRAN SISWA INDIVIDUAL</h5>
                        <span class="small text-muted">Periode: {{ $formatTglIndo($mulai) }} s/d {{ $formatTglIndo($selesai) }}</span>
                    </div>

                    {{-- INFORMASI DATA SISWA --}}
                    <div class="row bg-light p-3 rounded-3 mb-4 border">
                        <div class="col-6 col-md-3 mb-2">
                            <small class="text-muted d-block">Nama Siswa:</small>
                            <strong class="text-dark">{{ $item['nama'] }}</strong>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <small class="text-muted d-block">NIK / NISN:</small>
                            <strong class="text-dark">{{ $item['nisn'] }}</strong>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <small class="text-muted d-block">Kelas:</small>
                            <span class="badge bg-primary rounded-pill px-3">{{ $item['kelas'] }}</span>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <small class="text-muted d-block">Persentase Kehadiran:</small>
                            @php 
                                $effectiveDaysCount = 0;
                                foreach($range_tanggal as $tgl) {
                                    if(date('N', strtotime($tgl)) < 6) $effectiveDaysCount++;
                                }
                                $persenKehadiran = $effectiveDaysCount > 0 ? round(($item['total_h'] / $effectiveDaysCount) * 100, 1) : 0;
                            @endphp
                            <strong class="text-success">{{ $persenKehadiran }}%</strong>
                        </div>
                    </div>

                    {{-- STATISTIK KEHADIRAN SISWA --}}
                    <div class="row g-2 mb-4 text-center">
                        <div class="col-3">
                            <div class="p-2 rounded-3 bg-success-subtle border border-success-subtle text-success">
                                <div class="small fw-bold">HADIR (H)</div>
                                <h4 class="fw-bold mb-0">{{ $item['total_h'] }}</h4>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 rounded-3 bg-warning-subtle border border-warning-subtle text-warning">
                                <div class="small fw-bold">IZIN (I)</div>
                                <h4 class="fw-bold mb-0">{{ $item['total_i'] }}</h4>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 rounded-3 bg-info-subtle border border-info-subtle text-info">
                                <div class="small fw-bold">SAKIT (S)</div>
                                <h4 class="fw-bold mb-0">{{ $item['total_s'] }}</h4>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 rounded-3 bg-danger-subtle border border-danger-subtle text-danger">
                                <div class="small fw-bold">ALFA (A)</div>
                                <h4 class="fw-bold mb-0">{{ $item['total_a'] }}</h4>
                            </div>
                        </div>
                    </div>

                    {{-- TABEL RINCIAN KEHADIRAN HARIAN (BAHASA INDONESIA) --}}
                    <h6 class="fw-bold text-dark mb-2">Rincian Kehadiran Harian:</h6>
                    <div class="table-responsive border rounded-3 mb-4">
                        <table class="table table-sm table-striped align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="10%">NO</th>
                                    <th width="30%">TANGGAL</th>
                                    <th width="30%">HARI</th>
                                    <th width="30%" class="text-center">STATUS KEHADIRAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $rowNo = 1; @endphp
                                @foreach($range_tanggal as $tgl)
                                    @php 
                                        if (date('N', strtotime($tgl)) >= 6) continue;
                                        $st = $item['absen'][$tgl];
                                        $hrf = strtoupper(substr($st, 0, 1));
                                    @endphp
                                    <tr>
                                        <td>{{ $rowNo++ }}</td>
                                        <td>{{ $formatTglIndo($tgl) }}</td>
                                        <td>{{ $formatHariIndo($tgl) }}</td>
                                        <td class="text-center">
                                            @if($hrf == 'H')
                                                <span class="badge bg-success rounded-pill px-3 py-1">Hadir</span>
                                            @elseif($hrf == 'I')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1">Izin</span>
                                            @elseif($hrf == 'S')
                                                <span class="badge bg-info text-dark rounded-pill px-3 py-1">Sakit</span>
                                            @elseif($hrf == 'A')
                                                <span class="badge bg-danger rounded-pill px-3 py-1">Alfa</span>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- LEMBAR TANDA TANGAN SISWA INDIVIDUAL --}}
                    <div class="mt-4 pt-3 border-top">
                        <div class="row text-center">
                            <div class="col-6">
                                <p class="mb-5">Mengetahui,<br><strong>Kepala Sekolah</strong></p>
                                <p class="mt-5 mb-0"><u>( {{ $school->kepala_sekolah ?? '......................' }} )</u></p>
                            </div>
                            <div class="col-6">
                                <p class="mb-5">Tanggal: {{ $formatTglIndo(date('Y-m-d')) }}<br><strong>Wali Kelas</strong></p>
                                <p class="mt-5 mb-0"><u>( {{ auth()->user()->name }} )</u></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light rounded-bottom-4 p-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold d-flex align-items-center gap-2" onclick="printIndividualDiv('printableRekapSiswa{{ $item['siswa_id'] }}')">
                        <iconify-icon icon="solar:printer-bold-duotone" class="fs-5"></iconify-icon> Cetak Rekap Siswa Ini
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
    
    {{-- ========================================================== --}}
    {{-- SUBTLE LOW-KEY FOOTER --}}
    {{-- ========================================================== --}}
    <footer class="text-center py-2 mt-2 no-print">
        <span class="text-muted" style="font-size: 11px;">© 2026 TK Ceria - Web TK Al-Hidayah v1.0</span>
    </footer>

</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    flatpickr("#tglMulaiPicker", {
        locale: "id",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d F Y",
        defaultDate: "{{ $mulai ?? date('Y-m-01') }}"
    });
    flatpickr("#tglSelesaiPicker", {
        locale: "id",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d F Y",
        defaultDate: "{{ $selesai ?? date('Y-m-d') }}"
    });
});

function filterLiveRekap() {
    let input = document.getElementById('liveSearchRekap').value.toLowerCase();
    let rows = document.querySelectorAll('#tableRekapPresensi .rekap-row');
    rows.forEach(row => {
        let nama = row.querySelector('.rekap-nama').textContent.toLowerCase();
        if (nama.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function printIndividualDiv(divId) {
    var printContents = document.getElementById(divId).innerHTML;
    var iframe = document.createElement('iframe');
    iframe.style.position = 'absolute';
    iframe.style.width = '0px';
    iframe.style.height = '0px';
    iframe.style.border = 'none';
    document.body.appendChild(iframe);
    
    var doc = iframe.contentWindow.document;
    doc.open();
    doc.write('<html><head><title>Rekap Presensi Siswa</title>');
    doc.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
    doc.write('<style>@page{size: A4 portrait; margin:10mm;} body{padding:10px; font-family:sans-serif;} .badge{border:1px solid #ccc; padding:3px 8px;} table{width:100%; border-collapse:collapse;} th,td{border:1px solid #ddd; padding:5px; font-size:10pt;}</style>');
    doc.write('</head><body>');
    doc.write(printContents);
    doc.write('</body></html>');
    doc.close();

    setTimeout(function() {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
        document.body.removeChild(iframe);
    }, 500);
}
</script>
@endpush
@endsection