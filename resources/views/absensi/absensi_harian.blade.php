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
        background: #ffffff !important;
    }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
        background: #3b82f6 !important;
        border-color: #3b82f6 !important;
        border-radius: 8px !important;
        color: #fff !important;
        font-weight: bold !important;
    }
    .flatpickr-day.today {
        border-color: #3b82f6 !important;
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
    <div class="d-flex flex-column gap-3.5 mt-0 pb-4">

        @if(session('success'))
            <div class="alert alert-success border border-success-subtle shadow-none rounded-3 py-2 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
                <div><i class="ti ti-check me-2"></i>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                                <iconify-icon icon="solar:user-check-bold-duotone"></iconify-icon>
                            </div>
                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Presensi Harian Siswa</h5>
                            </div>
                            <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                                <span>Input kehadiran siswa kelas Anda untuk hari ini.</span>
                            </p>
                        </div>
                    </div>

                    {{-- Action Controls: Filter & Datepicker --}}
                    <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                        @if(isset($list_kelas) && count($list_kelas) > 0)
                        <div class="bg-white rounded-3 px-3 py-1.5 shadow-sm">
                            <select name="kelas_id_filter" class="form-select form-select-sm border-0 shadow-none font-semibold text-dark bg-transparent p-0" onchange="window.location.href='{{ route('absensi.harian') }}?kelas_id=' + this.value" style="font-size: 12px; min-width: 130px;">
                                <option value="all">-- Semua Kelas --</option>
                                @foreach($list_kelas as $k)
                                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        @php
                            $selectedTanggal = request('tanggal', date('Y-m-d'));
                            $isWeekendHarian = date('N', strtotime($selectedTanggal)) >= 6;
                        @endphp
                        <div class="d-flex gap-2 align-items-center bg-white rounded-3 px-3 py-1.5 shadow-sm position-relative">
                            <iconify-icon icon="solar:calendar-mark-bold-duotone" class="text-primary fs-5"></iconify-icon>
                            <span class="font-semibold text-dark" style="font-size: 12px;">Tanggal:</span>
                            <input type="text" name="tanggal" id="datepickerHarian" class="form-control form-control-sm border-0 shadow-none font-semibold text-primary bg-transparent p-0" value="{{ $selectedTanggal }}" style="width: 125px; cursor: pointer; font-size: 12px;" required readonly>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @include('partials.owner_stepped_filter')

        @if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))

        {{-- Notifikasi Hari Libur / Akhir Pekan --}}
        @if($isWeekendHarian)
            <div class="alert alert-warning border border-warning-subtle shadow-sm rounded-3 p-3.5 mb-0 d-flex align-items-center gap-3">
                <iconify-icon icon="solar:calendar-minimalistic-bold-duotone" class="fs-2 text-warning flex-shrink-0"></iconify-icon>
                <div>
                    <h6 class="fw-bold text-dark mb-0.5" style="font-size: 14px;">Hari Libur Akhir Pekan (Sabtu / Minggu)</h6>
                    <p class="mb-0 small text-muted" style="font-size: 12px;">Tanggal yang Anda pilih adalah hari libur sekolah. Presensi harian tidak dihitung pada hari libur akhir pekan.</p>
                </div>
            </div>
        @endif

        {{-- TABEL PRESENSI --}}
        <div class="card border shadow-sm" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
            <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3.5 border-bottom pb-3 gap-2">
                    <div>
                        <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15px;">
                            <iconify-icon icon="solar:clipboard-list-bold-duotone" class="text-primary fs-5"></iconify-icon> Daftar Kehadiran Siswa
                        </h6>
                        <span class="text-muted small" style="font-size: 11.5px;">Pilih opsi status kehadiran untuk masing-masing siswa di bawah ini</span>
                    </div>
                    <div class="input-group input-group-sm" style="max-width: 280px;">
                        <span class="input-group-text bg-light border-end-0"><iconify-icon icon="solar:magnifer-linear" class="text-muted"></iconify-icon></span>
                        <input type="text" id="searchAbsensi" class="form-control border-start-0 shadow-none font-medium" placeholder="Cari nama siswa..." onkeyup="filterSiswaAbsensi()" style="font-size: 12.5px;">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0" id="tableAbsensi">
                        <thead>
                            <tr class="border-bottom border-light-subtle">
                                <th width="40" class="ps-0 pb-2 text-center">#</th>
                                <th class="pb-2">NAMA SISWA</th>
                                <th class="text-center pb-2" width="100">HADIR</th>
                                <th class="text-center pb-2" width="100">SAKIT</th>
                                <th class="text-center pb-2" width="100">IZIN</th>
                                <th class="text-center pb-2" width="100">ALFA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswas as $index => $siswa)
                            <tr class="border-bottom border-light-subtle row-siswa">
                                <td class="ps-0 py-3 text-center text-muted font-semibold index-no" style="font-size: 13px;">{{ $index + 1 }}</td>
                                <td class="py-3 fw-bold text-dark nama-siswa" style="font-size: 14px;">{{ $siswa->nama_lengkap }}</td>
                                
                                {{-- Radio Buttons Absensi --}}
                                <td class="text-center py-3"><input type="radio" name="status_{{ $siswa->id }}" value="hadir" class="form-check-input mt-0 shadow-none border-primary" checked style="transform: scale(1.2); cursor: pointer;"></td>
                                <td class="text-center py-3"><input type="radio" name="status_{{ $siswa->id }}" value="sakit" class="form-check-input mt-0 shadow-none border-primary" style="transform: scale(1.2); cursor: pointer;"></td>
                                <td class="text-center py-3"><input type="radio" name="status_{{ $siswa->id }}" value="izin" class="form-check-input mt-0 shadow-none border-warning" style="transform: scale(1.2); cursor: pointer;"></td>
                                <td class="text-center py-3"><input type="radio" name="status_{{ $siswa->id }}" value="alfa" class="form-check-input mt-0 shadow-none border-danger" style="transform: scale(1.2); cursor: pointer;"></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                                            <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-2"></iconify-icon>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada Data Siswa</h6>
                                        <p class="text-muted small mb-0">Belum ada siswa yang terdaftar untuk kelas ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($siswas->count() > 0)
                <div class="pt-3 border-top text-end mt-2">
                    <button type="submit" class="btn btn-primary rounded-3 font-semibold px-4 py-2 shadow-xs" style="font-size: 12.5px;">
                        <iconify-icon icon="solar:diskette-bold-duotone" class="align-middle me-1 fs-5"></iconify-icon> Simpan Presensi
                    </button>
                </div>
                @endif
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