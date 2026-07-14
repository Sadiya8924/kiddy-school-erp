@extends('layouts.admin')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    <div class="col-12">
        <div class="card border shadow-none mb-0" style="background-color: var(--primary-blue-soft); border-color: var(--primary-blue-border) !important;">
            <div class="card-body px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Cetak Rapor Siswa</h4>
                    <p class="text-muted mb-0 small">Pilih siswa untuk mencetak hasil pembelajaran.</p>
                </div>
                <div class="d-flex align-items-center gap-2 text-muted small fw-medium">
                    <iconify-icon icon="solar:printer-bold-duotone" class="fs-5 text-primary"></iconify-icon>
                    <span>Siap dicetak ke PDF</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border shadow-none mb-0">
            <div class="card-body p-4">
                <form action="/cetak-rapor" method="GET" class="row g-3 align-items-end">
                    @if(auth()->user()->role == 'admin')
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-dark small mb-2">Filter Kelas</label>
                        <select name="kelas_id" class="form-select shadow-none border-light-subtle py-2" onchange="this.form.submit()">
                            <option value="all">-- Semua Kelas --</option>
                            @foreach($list_kelas as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-{{ auth()->user()->role == 'admin' ? '7' : '10' }}">
                        <label class="form-label fw-semibold text-dark small mb-2">Cari Siswa</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light-subtle border-end-0"><iconify-icon icon="solar:magnifer-linear" class="text-muted"></iconify-icon></span>
                            <input type="text" name="search" class="form-control shadow-none border-light-subtle border-start-0 py-2" placeholder="Cari berdasarkan Nama Lengkap atau NIK..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary rounded fw-bold shadow-none py-2">Cari Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border shadow-none mb-0">
            <div class="card-body p-0">
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-primary fs-5"></iconify-icon> Daftar Siswa
                    </h6>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light border-bottom border-light-subtle">
                            <tr>
                                <th width="5%" class="text-center py-3 ps-4 text-muted fw-semibold small">No</th>
                                <th width="35%" class="py-3 text-dark fw-semibold small">Nama Siswa</th>
                                <th width="20%" class="py-3 text-center text-dark fw-semibold small">NIK</th>
                                <th width="20%" class="py-3 text-center text-dark fw-semibold small">Kelas</th>
                                <th width="20%" class="py-3 text-center pe-4 text-dark fw-semibold small">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswas as $index => $s)
                            <tr class="border-bottom border-light-subtle">
                                <td class="text-center py-3 ps-4 text-muted fw-medium">{{ $index + 1 }}</td>
                                <td class="fw-medium text-dark py-3">{{ $s->nama_lengkap }}</td>
                                <td class="text-center py-3 text-muted">{{ $s->nik }}</td>
                                <td class="text-center py-3">
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">{{ $s->nama_kelas_aktif }}</span>
                                </td>
                                <td class="text-center py-3 pe-4">
                                    <a href="/rapor/{{ $s->id }}" class="btn btn-sm btn-danger rounded-pill px-4 fw-medium shadow-none">
                                        <iconify-icon icon="solar:printer-bold-duotone" class="align-middle"></iconify-icon> Cetak PDF
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Data siswa tidak ditemukan.</td>
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