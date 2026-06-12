@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-semibold text-dark mb-1">Cetak Rapor Siswa</h2>
        <span class="text-muted fs-3">Pilih siswa untuk mencetak hasil pembelajaran.</span>
    </div>
</div>

<div class="card w-100 border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="/cetak-rapor" method="GET" class="row g-2 align-items-center">
            @if(auth()->user()->role == 'admin')
            <div class="col-md-3">
                <select name="kelas_id" class="form-select border-1 text-dark shadow-none" onchange="this.form.submit()">
                    <option value="all">-- Semua Kelas --</option>
                    @foreach($list_kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            
            <div class="col-md-{{ auth()->user()->role == 'admin' ? '7' : '10' }}">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><iconify-icon icon="solar:magnifer-linear" class="text-muted"></iconify-icon></span>
                    <input type="text" name="search" class="form-control border-start-0 shadow-none" placeholder="Cari berdasarkan Nama Lengkap atau NIK..." value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 rounded-2 fw-bold shadow-none">Cari Data</button>
            </div>
        </form>
    </div>
</div>

<div class="card w-100 border-0 shadow-sm position-relative overflow-hidden">
    <div class="card-body p-4">
        <h5 class="fw-semibold mb-4 text-dark">Daftar Siswa</h5>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="border-bottom: 2px solid #2d5cf7;">
                    <tr>
                        <th width="5%" class="border-0 bg-transparent text-dark fw-semibold py-3 ps-0 text-center">No</th>
                        <th width="35%" class="border-0 bg-transparent text-dark fw-semibold py-3 text-start">Nama Siswa</th>
                        <th width="20%" class="border-0 bg-transparent text-dark fw-semibold py-3 text-center">NIK</th>
                        <th width="20%" class="border-0 bg-transparent text-dark fw-semibold py-3 text-center">Kelas</th>
                        <th width="20%" class="border-0 bg-transparent text-dark fw-semibold py-3 text-center pe-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $index => $s)
                    <tr class="border-bottom" style="border-color: #f3f4f6;">
                        <td class="text-center py-3 ps-0">{{ $index + 1 }}</td>
                        <td class="fw-medium text-dark text-start py-3">{{ $s->nama_lengkap }}</td>
                        <td class="text-center py-3">{{ $s->nik }}</td>
                        <td class="text-center py-3">
                            <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">{{ $s->nama_kelas_aktif }}</span>
                        </td>
                        <td class="text-center py-3 pe-0">
                            {{-- Target blank biar preview rapornya kebuka di tab baru --}}
                            <a href="/rapor/{{ $s->id }}" class="btn btn-sm btn-danger rounded-pill px-4 mx-1 shadow-sm fw-medium">
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
@endsection