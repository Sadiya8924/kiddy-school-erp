@extends('layouts.admin') 

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    <div class="col-12 mb-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <a href="{{ route('kelas.index') }}" class="btn btn-outline-light border shadow-none text-muted p-2 rounded d-inline-flex mb-3"><iconify-icon icon="solar:arrow-left-line-duotone" class="fs-5"></iconify-icon></a>
                <h4 class="fw-bold text-dark mb-1">Daftar Siswa Kelas: {{ $kelas->nama_kelas }}</h4>
                <p class="text-muted mb-0 small">Menampilkan data siswa yang aktif pada tingkat {{ $kelas->tingkat ?? '-' }}.</p>
            </div>
            
            {{-- Tombol Pindah Massal HANYA muncul jika BUKAN Kepsek --}}
            @if(auth()->user()->role !== 'kepsek' && $siswas->count() > 0)
            <button class="btn btn-outline-primary border-primary-subtle bg-light text-primary rounded px-4 py-2 d-flex align-items-center gap-2 shadow-none fw-bold" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#modalPindahMassal">
                <iconify-icon icon="solar:transfer-horizontal-bold-duotone" class="fs-5"></iconify-icon> Pindah Kelas Massal
            </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="col-12 mt-0">
            <div class="alert alert-success border border-success-subtle shadow-none rounded py-2 px-3 mb-0 small fw-medium">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="col-12 mt-0">
            <div class="alert alert-danger border border-danger-subtle shadow-none rounded py-2 px-3 mb-0 small fw-medium">{{ session('error') }}</div>
        </div>
    @endif

    <div class="col-12">
        <div class="card border shadow-none mb-0">
            <div class="card-header bg-light border-bottom p-3">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-primary fs-5"></iconify-icon> Siswa Terdaftar
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 border-0">
                        <thead class="bg-white border-bottom border-light-subtle">
                            <tr>
                                <th class="text-center text-muted fw-semibold py-3" style="width: 5%; font-size: 11px;">NO</th>
                                <th class="text-muted fw-semibold py-3" style="font-size: 11px;">NAMA LENGKAP</th>
                                <th class="text-muted fw-semibold py-3" style="font-size: 11px;">NISN / NIK</th>
                                <th class="text-center text-muted fw-semibold py-3" style="width: 15%; font-size: 11px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswas as $index => $siswa)
                            <tr class="border-bottom border-light-subtle">
                                <td class="text-center text-muted py-3" style="font-size: 13px;">{{ $index + 1 }}</td>
                                <td class="py-3">
                                    <div class="fw-bold text-dark" style="font-size: 14px;">{{ $siswa->nama_lengkap ?? $siswa->nama }}</div>
                                </td>
                                <td class="py-3 text-muted" style="font-size: 13px;">
                                    {{ $siswa->nisn ?? '-' }} <br> <span style="font-size: 11px;">NIK: {{ $siswa->nik ?? '-' }}</span>
                                </td>
                                <td class="text-center py-3">
                                    {{-- Form Hapus Siswa HANYA muncul jika BUKAN Kepsek --}}
                                    @if(auth()->user()->role !== 'kepsek')
                                    <form action="{{ route('kelas.remove_student', ['kelas_id' => $kelas->id, 'siswa_id' => $siswa->id]) }}" method="POST" onsubmit="return confirm('Keluarkan {{ $siswa->nama_lengkap }} dari kelas {{ $kelas->nama_kelas }}? (Biodata siswa tetap aman)')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 bg-transparent text-danger p-2" title="Keluarkan dari kelas">
                                            <iconify-icon icon="solar:trash-bin-trash-bold" class="fs-5"></iconify-icon>
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-muted" style="font-size: 11px;">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="text-muted opacity-25 mb-3" style="font-size: 60px;"></iconify-icon>
                                    <p class="text-muted mb-0 fw-medium" style="font-size: 14px;">Belum ada siswa di kelas ini.</p>
                                    @if(auth()->user()->role !== 'kepsek')
                                    <span class="text-muted small">Silakan plot siswa dari menu <b>Data Siswa</b>.</span>
                                    @endif
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

{{-- MODAL PINDAH KELAS MASSAL HANYA DI-RENDER JIKA BUKAN KEPSEK --}}
@if(auth()->user()->role !== 'kepsek')
<div class="modal fade" id="modalPindahMassal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded border shadow-sm">
            <form action="{{ route('kelas.move', $kelas->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-light border-bottom p-3">
                    <h6 class="fw-bold mb-0 text-dark">Pindah Kelas Massal</h6>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning border border-warning-subtle rounded-3 small mb-4 py-2 px-3">
                        <iconify-icon icon="solar:info-circle-bold" class="align-middle me-1 fs-5"></iconify-icon>
                        Aksi ini akan memindahkan <strong>semua siswa ({{ $siswas->count() }} anak)</strong> dari kelas {{ $kelas->nama_kelas }} ke kelas tujuan.
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Pilih Kelas Tujuan <span class="text-danger">*</span></label>
                        <select name="target_kelas_id" class="form-select border-light-subtle shadow-none bg-light" required>
                            <option value="" disabled selected>-- Pilih Kelas Tujuan --</option>
                            @forelse($list_kelas as $lk)
                                <option value="{{ $lk->id }}">{{ $lk->nama_kelas }} (Tingkat: {{ $lk->tingkat }})</option>
                            @empty
                                <option value="" disabled>Belum ada kelas lain di sekolah ini.</option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-outline-secondary rounded fw-medium px-4" data-bs-dismiss="modal" style="font-size: 12px;">Batal</button>
                    <button type="submit" class="btn btn-primary rounded fw-bold px-4 shadow-none" style="font-size: 12px;">Proses Pindah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection