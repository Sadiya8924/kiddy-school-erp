@extends('layouts.admin')

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
    .flatpickr-day.flatpickr-disabled, .flatpickr-day.flatpickr-disabled:hover {
        color: #cbd5e1 !important;
    }
</style>
@endpush

@section('konten_halaman')
<!-- Form membungkus seluruh halaman agar input tanggal ikut ter-submit -->
<form action="{{ route('absensi.store') }}" method="POST">
    @csrf
    <div class="row gx-3 gy-3 mt-0">
        
        <div class="col-12 mb-2">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Presensi Harian Siswa</h4>
                    <p class="text-muted mb-0 small">Input kehadiran siswa kelas Anda untuk hari ini.</p>
                </div>
                
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    @if(isset($list_kelas) && count($list_kelas) > 0)
                    <div class="bg-white border border-light-subtle px-3 py-2 rounded-3 shadow-sm">
                        <select name="kelas_id_filter" class="form-select form-select-sm border-0 shadow-none fw-medium text-dark bg-transparent" onchange="window.location.href='{{ route('absensi.harian') }}?kelas_id=' + this.value">
                            <option value="all">-- Semua Kelas --</option>
                            @foreach($list_kelas as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Input Tanggal Modern (Flatpickr Bahasa Indonesia) --}}
                    @php
                        $selectedTanggal = request('tanggal', date('Y-m-d'));
                        $isWeekendHarian = date('N', strtotime($selectedTanggal)) >= 6;
                    @endphp
                    <div class="d-flex gap-2 align-items-center bg-white border border-light-subtle px-3 py-2 rounded-3 shadow-sm position-relative">
                        <iconify-icon icon="solar:calendar-mark-bold-duotone" class="text-primary fs-5"></iconify-icon>
                        <span class="fw-bold text-dark" style="font-size: 13px;">Tanggal:</span>
                        <input type="text" name="tanggal" id="datepickerHarian" class="form-control form-control-sm border-0 shadow-none fw-bold text-primary bg-transparent p-0" value="{{ $selectedTanggal }}" style="width: 135px; cursor: pointer;" required readonly>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifikasi Hari Libur / Akhir Pekan --}}
        @if($isWeekendHarian)
            <div class="col-12 mt-2">
                <div class="alert alert-warning border border-warning-subtle shadow-sm rounded-4 p-3 mb-0 d-flex align-items-center gap-3">
                    <iconify-icon icon="solar:calendar-minimalistic-bold-duotone" class="fs-2 text-warning"></iconify-icon>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Hari Libur Akhir Pekan (Sabtu / Minggu)</h6>
                        <p class="mb-0 small text-muted">Tanggal yang Anda pilih adalah hari libur sekolah. Presensi harian tidak dihitung pada hari libur akhir pekan.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="col-12 mt-2">
                <div class="alert alert-success border border-success-subtle shadow-none rounded py-2 px-3 mb-0 small fw-medium">{{ session('success') }}</div>
            </div>
        @endif

        <div class="col-12">
            <div class="card border shadow-none mb-0">
                <div class="card-header bg-light border-bottom p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:clipboard-list-bold-duotone" class="text-primary fs-5"></iconify-icon> Daftar Kehadiran
                    </h6>
                    <div class="input-group input-group-sm" style="max-width: 320px;">
                        <span class="input-group-text bg-white border-end-0"><iconify-icon icon="solar:magnifer-linear" class="text-muted"></iconify-icon></span>
                        <input type="text" id="searchAbsensi" class="form-control border-start-0 shadow-none" placeholder="Cari nama siswa..." onkeyup="filterSiswaAbsensi()">
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 border-0" id="tableAbsensi">
                            <thead class="bg-white border-bottom border-light-subtle">
                                <tr>
                                    <th class="text-center text-muted fw-semibold py-3" style="width: 5%; font-size: 11px;">NO</th>
                                    <th class="text-muted fw-semibold py-3" style="font-size: 11px;">NAMA SISWA</th>
                                    <th class="text-center text-muted fw-semibold py-3" style="width: 10%; font-size: 11px;">HADIR</th>
                                    <th class="text-center text-muted fw-semibold py-3" style="width: 10%; font-size: 11px;">SAKIT</th>
                                    <th class="text-center text-muted fw-semibold py-3" style="width: 10%; font-size: 11px;">IZIN</th>
                                    <th class="text-center text-muted fw-semibold py-3" style="width: 10%; font-size: 11px;">ALFA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswas as $index => $siswa)
                                <tr class="border-bottom border-light-subtle row-siswa">
                                    <td class="text-center text-muted py-3 index-no" style="font-size: 13px;">{{ $index + 1 }}</td>
                                    <td class="py-3 fw-bold text-dark nama-siswa" style="font-size: 14px;">{{ $siswa->nama_lengkap }}</td>
                                    
                                    {{-- Radio Buttons Absensi --}}
                                    <td class="text-center py-3"><input type="radio" name="status_{{ $siswa->id }}" value="hadir" class="form-check-input mt-0 shadow-none border-primary" checked style="transform: scale(1.3); cursor: pointer;"></td>
                                    <td class="text-center py-3"><input type="radio" name="status_{{ $siswa->id }}" value="sakit" class="form-check-input mt-0 shadow-none border-primary" style="transform: scale(1.3); cursor: pointer;"></td>
                                    <td class="text-center py-3"><input type="radio" name="status_{{ $siswa->id }}" value="izin" class="form-check-input mt-0 shadow-none border-warning" style="transform: scale(1.3); cursor: pointer;"></td>
                                    <td class="text-center py-3"><input type="radio" name="status_{{ $siswa->id }}" value="alfa" class="form-check-input mt-0 shadow-none border-danger" style="transform: scale(1.3); cursor: pointer;"></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted small">Belum ada data siswa di kelas ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- Tombol Simpan HANYA muncul jika ada siswa --}}
                @if($siswas->count() > 0)
                <div class="card-footer bg-light border-top p-3 text-end">
                    <button type="submit" class="btn btn-primary rounded fw-bold px-4 shadow-none" style="font-size: 12px;">
                        <iconify-icon icon="solar:diskette-bold-duotone" class="align-middle me-1"></iconify-icon> Simpan Presensi
                    </button>
                </div>
                @endif
                
            </div>
        </div>
        
    </div>
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    flatpickr("#datepickerHarian", {
        locale: "id",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d F Y",
        defaultDate: "{{ $selectedTanggal }}",
        onChange: function(selectedDates, dateStr, instance) {
            window.location.href = "{{ route('absensi.harian') }}?kelas_id={{ request('kelas_id', 'all') }}&tanggal=" + dateStr;
        }
    });
});

function filterSiswaAbsensi() {
    let input = document.getElementById('searchAbsensi').value.toLowerCase();
    let rows = document.querySelectorAll('#tableAbsensi .row-siswa');
    rows.forEach(row => {
        let nama = row.querySelector('.nama-siswa').textContent.toLowerCase();
        if (nama.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endpush
@endsection