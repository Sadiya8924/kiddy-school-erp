@extends('layouts.admin')

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
                
                {{-- Input Tanggal --}}
                <div class="d-flex gap-2 align-items-center bg-white border border-light-subtle px-3 py-2 rounded shadow-sm">
                    <iconify-icon icon="solar:calendar-mark-bold-duotone" class="text-primary fs-5"></iconify-icon>
                    <span class="fw-bold text-dark" style="font-size: 13px;">Tanggal:</span>
                    <input type="date" name="tanggal" class="form-control form-control-sm border-0 shadow-none fw-medium text-primary bg-transparent" value="{{ date('Y-m-d') }}" style="width: 130px; cursor: pointer;" required>
                </div>
            </div>
        </div>

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="col-12 mt-0">
                <div class="alert alert-success border border-success-subtle shadow-none rounded py-2 px-3 mb-0 small fw-medium">{{ session('success') }}</div>
            </div>
        @endif

        <div class="col-12">
            <div class="card border shadow-none mb-0">
                <div class="card-header bg-light border-bottom p-3">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:clipboard-list-bold-duotone" class="text-primary fs-5"></iconify-icon> Daftar Kehadiran
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 border-0">
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
                                <tr class="border-bottom border-light-subtle">
                                    <td class="text-center text-muted py-3" style="font-size: 13px;">{{ $index + 1 }}</td>
                                    <td class="py-3 fw-bold text-dark" style="font-size: 14px;">{{ $siswa->nama_lengkap }}</td>
                                    
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
@endsection