@extends('layouts.admin')

@section('konten_halaman')
<div class="row">
    <div class="col-12 mb-4">
        <h2 class="fw-semibold text-dark mb-1">Laporan Fisik Anak</h2>
        <span class="text-muted fs-3">Riwayat pertumbuhan dan perkembangan ananda.</span>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Profil: {{ $siswa->nama_lengkap }}</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal Periksa</th>
                                <th>Tinggi Badan</th>
                                <th>Berat Badan</th>
                                <th>Lingkar Kepala</th>
                                <th>Catatan Guru</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $fisik)
                            <tr>
                                <td class="fw-medium">{{ \Carbon\Carbon::parse($fisik->tgl_periksa)->format('d F Y') }}</td>
                                <td>{{ $fisik->tinggi_badan }} cm</td>
                                <td>{{ $fisik->berat_badan }} kg</td>
                                <td>{{ $fisik->lingkar_kepala ?? '-' }} cm</td>
                                <td>{{ $fisik->catatan ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="ti ti-clipboard-x fs-1 d-block mb-2"></i>
                                    Belum ada catatan perkembangan fisik dari pihak sekolah.
                                </td>
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