@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Ringkasan Data Siswa (Mode Pusat)</h3>
        <p class="text-muted mb-0">Ringkasan agregat populasi siswa di seluruh sekolah yang terdaftar.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Sekolah</th>
                        <th class="text-center">Jumlah Siswa</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ringkasan as $item)
                    <tr>
                        <td class="fw-bold text-dark">{{ $item->sekolah->nama_sekolah ?? 'Sekolah ID #' . $item->school_id }}</td>
                        <td class="text-center fw-bold"><span class="badge bg-primary rounded-pill px-3 py-2 fs-6">{{ $item->total }} Siswa</span></td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('superadmin.switch_school') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="active_school_id" value="{{ $item->school_id }}">
                                <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <iconify-icon icon="solar:eye-bold-duotone" class="align-middle me-1"></iconify-icon> Pantau Sekolah Ini
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">Belum ada data sekolah/siswa.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td>Total Seluruh Sekolah</td>
                        <td class="text-center"><span class="badge bg-dark rounded-pill px-3 py-2 fs-6">{{ $ringkasan->sum('total') }} Siswa</span></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
