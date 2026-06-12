@extends('layouts.admin') 

@section('konten_halaman')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <a href="{{ route('kelas.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
                    <iconify-icon icon="solar:arrow-left-bold" class="align-middle"></iconify-icon> Kembali ke Manajemen Kelas
                </a>
                <h2 class="fw-semibold text-dark mb-1">Detail Kelas: {{ $kelas->nama_kelas }}</h2>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-6">
                    Tingkat: {{ $kelas->tingkat ?? '-' }}
                </span>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-0">Daftar Siswa</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center text-secondary py-3" style="width: 5%">No</th>
                                <th class="text-secondary py-3">Nama Lengkap</th>
                                <th class="text-secondary py-3">NIK</th>
                                <th class="text-secondary py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- LOGIKA BARU: Panggil variabel $siswas langsung dari controller --}}
                            @forelse($siswas as $index => $siswa)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-medium text-dark">{{ $siswa->nama_lengkap ?? $siswa->nama }}</td>
                                <td>{{ $siswa->nik ?? '-' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light text-danger rounded-pill" title="Keluarkan dari kelas">
                                        <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="text-muted opacity-25 mb-3" style="font-size: 60px;"></iconify-icon>
                                    <p class="text-muted mb-0">Belum ada siswa yang terdaftar di kelas ini pada semester aktif.</p>
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