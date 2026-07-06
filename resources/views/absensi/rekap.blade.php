@extends('layouts.admin')

@push('styles')
<style>
    .cell-status { width: 40px; text-align: center; font-weight: 600; }
    .status-H { color: #13deb9; } /* Hadir - Hijau */
    .status-I { color: #ffae1f; } /* Izin - Kuning */
    .status-S { color: #539bff; } /* Sakit - Biru */
    .status-A { color: #fa896b; } /* Alfa - Merah */
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

<!-- Tabel Matriks -->
<div class="card w-100 border-0 shadow-sm position-relative overflow-hidden">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <h5 class="fw-semibold text-dark mb-0 d-none d-md-block">Data Matriks Presensi</h5>
            
            <!-- Legend / Keterangan Minimalis Modern -->
            <div class="d-flex gap-4 bg-light px-4 py-2 rounded-pill shadow-sm border-0">
                <span class="fs-3 d-flex align-items-center gap-1">
                    <span class="fw-bold fs-4 text-dark">H</span> <span class="text-muted fw-medium">: Hadir</span>
                </span>
                <span class="fs-3 d-flex align-items-center gap-1">
                    <span class="fw-bold fs-4 text-dark">I</span> <span class="text-muted fw-medium">: Izin</span>
                </span>
                <span class="fs-3 d-flex align-items-center gap-1">
                    <span class="fw-bold fs-4 text-dark">S</span> <span class="text-muted fw-medium">: Sakit</span>
                </span>
                <span class="fs-3 d-flex align-items-center gap-1">
                    <span class="fw-bold fs-4 text-dark">A</span> <span class="text-muted fw-medium">: Alfa</span>
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 text-nowrap">
                <thead style="border-bottom: 2px solid #2d5cf7;">
                    <tr>
                        <th class="border-0 bg-transparent text-dark fw-semibold py-3 ps-0 text-start" style="min-width: 200px;">Nama Siswa</th>
                        @if(isset($range_tanggal))
                            @foreach($range_tanggal as $tgl)
                                <th class="border-0 bg-transparent text-dark fw-semibold py-3 text-center px-1">{{ date('d/m', strtotime($tgl)) }}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(isset($rekap) && count($rekap) > 0)
                        @foreach($rekap as $item)
                        <tr class="border-bottom" style="border-color: #f3f4f6;">
                            <td class="fw-medium text-dark text-start py-3 ps-0">{{ $item['nama'] }}</td>
                            @foreach($range_tanggal as $tgl)
                                @php 
                                    $status = $item['absen'][$tgl];
                                    $huruf = substr($status, 0, 1); 
                                @endphp
                                <td class="cell-status status-{{ $huruf }} py-3 text-center">
                                    {{ $huruf == '-' ? '-' : $huruf }}
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="100" class="text-center py-5 text-muted bg-transparent">
                                Pilih rentang tanggal terlebih dahulu untuk melihat data
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection