@extends('layouts.admin')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    <div class="col-12 mb-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Perkembangan Fisik Siswa</h4>
                <p class="text-muted mb-0 small">Kelola data berat badan, tinggi badan, dan lingkar kepala siswa.</p>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border shadow-none mb-0">
            <div class="card-body p-3">
                <form action="{{ route('perkembangan.index') }}" method="GET" class="row g-2 align-items-center">
                    
                    {{-- Dropdown Kelas HANYA muncul untuk Admin / Kepsek / Super Admin --}}
                    @if(in_array(auth()->user()->role, ['admin', 'super_admin', 'kepsek']))
                    <div class="col-md-4">
                        <select name="kelas_id" class="form-select border-light-subtle shadow-none bg-light fw-medium" style="font-size: 13px;" onchange="this.form.submit()">
                            <option value="all">-- Semua Kelas --</option>
                            @foreach($list_kelas as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    {{-- Form Cari Nama (Bisa dipakai Guru juga) --}}
                    <div class="col-md-{{ in_array(auth()->user()->role, ['admin', 'super_admin', 'kepsek']) ? '6' : '10' }}">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light-subtle border-end-0"><iconify-icon icon="solar:magnifer-linear" class="text-muted"></iconify-icon></span>
                            <input type="text" name="search" class="form-control border-light-subtle bg-light border-start-0 shadow-none fw-medium" style="font-size: 13px;" placeholder="Cari nama siswa..." value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 rounded fw-bold shadow-none" style="font-size: 13px;">Cari Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 mt-2">
        <div class="card border shadow-none mb-0">
            <div class="card-header bg-light border-bottom p-3">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-primary fs-5"></iconify-icon> Daftar Siswa
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 border-0">
                        <thead class="bg-white border-bottom border-light-subtle">
                            <tr>
                                <th class="text-center text-muted fw-semibold py-3" style="width: 5%; font-size: 11px;">NO</th>
                                <th class="text-muted fw-semibold py-3" style="font-size: 11px;">NAMA SISWA</th>
                                <th class="text-center text-muted fw-semibold py-3 pe-4" style="width: 20%; font-size: 11px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswas as $index => $s)
                            <tr class="border-bottom border-light-subtle">
                                <td class="text-center text-muted py-3" style="font-size: 13px;">{{ $index + 1 }}</td>
                                <td class="py-3 fw-bold text-dark" style="font-size: 14px;">{{ $s->nama_lengkap }}</td>
                                <td class="text-center py-3 pe-4">
                                    <a href="{{ route('perkembangan.show', $s->id) }}" class="btn btn-sm btn-primary rounded fw-bold px-4 shadow-none" style="font-size: 12px;">
                                        <iconify-icon icon="solar:chart-square-bold-duotone" class="align-middle me-1"></iconify-icon> Laporan & Grafik
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted small">
                                    <iconify-icon icon="solar:ghost-broken" class="fs-1 d-block mx-auto mb-2 opacity-50"></iconify-icon>
                                    Data siswa tidak ditemukan.
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