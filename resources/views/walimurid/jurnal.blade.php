@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Jurnal Harian — {{ $siswa->nama_lengkap }}</h3>
        <p class="text-muted mb-0">Catatan kegiatan harian dan perkembangan putra-putri Anda di sekolah</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Tema / Topik</th>
                        <th>Kegiatan</th>
                        <th>Dokumentasi Foto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurnals as $jurnal)
                    <tr>
                        <td class="fw-semibold text-dark" style="width: 140px;">
                            {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y') }}
                        </td>
                        <td class="fw-bold text-primary" style="width: 180px;">{{ $jurnal->tema ?: 'Kegiatan Harian' }}</td>
                        <td>{{ $jurnal->kegiatan }}</td>
                        <td style="width: 120px;">
                            @if($jurnal->foto)
                                <a href="{{ asset('storage/' . $jurnal->foto) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <iconify-icon icon="solar:camera-bold-duotone" class="align-middle me-1"></iconify-icon> Foto
                                </a>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <iconify-icon icon="solar:notes-bold-duotone" class="fs-1 opacity-50 mb-2 d-block"></iconify-icon>
                            Belum ada catatan jurnal harian untuk {{ $siswa->nama_lengkap }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $jurnals->links() }}
        </div>
    </div>
</div>
@endsection
