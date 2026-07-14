@extends('layouts.admin')
@section('title', 'Laporan Perkembangan Fisik')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    <div class="col-12">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="/dashboard" class="btn btn-outline-light border shadow-none text-muted p-2 rounded d-flex"><iconify-icon icon="solar:arrow-left-line-duotone" class="fs-5"></iconify-icon></a>
            <div>
                <h4 class="fw-bold mb-1 text-dark">Laporan Fisik & Kesehatan</h4>
                <p class="text-muted mb-0 small">Riwayat pemeriksaan fisik ananda {{ $siswa->nama_lengkap ?? '' }}</p>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border shadow-none">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="bg-light border-bottom">
                            <tr>
                                <th class="text-muted fw-semibold py-3 ps-4" style="font-size: 11px;">TANGGAL PEMERIKSAAN</th>
                                <th class="text-muted fw-semibold py-3" style="font-size: 11px;">TINGGI (cm)</th>
                                <th class="text-muted fw-semibold py-3" style="font-size: 11px;">BERAT (kg)</th>
                                <th class="text-muted fw-semibold py-3" style="font-size: 11px;">LINGKAR KEPALA (cm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat ?? [] as $r)
                            <tr class="border-bottom border-light-subtle">
                                <td class="ps-4 py-3 fw-medium text-dark" style="font-size: 13px;">{{ \Carbon\Carbon::parse($r->tgl_periksa)->translatedFormat('d F Y') }}</td>
                                <td class="py-3 text-muted" style="font-size: 13px;">{{ $r->tinggi_badan ?? '-' }}</td>
                                <td class="py-3 text-muted" style="font-size: 13px;">{{ $r->berat_badan ?? '-' }}</td>
                                <td class="py-3 text-muted" style="font-size: 13px;">{{ $r->lingkar_kepala ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5 small">Belum ada data pemeriksaan fisik tercatat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection