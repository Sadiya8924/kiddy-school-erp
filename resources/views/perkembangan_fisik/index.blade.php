@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-semibold text-dark mb-1">Perkembangan Fisik Siswa</h2>
        <span class="text-muted fs-3">Kelola data berat badan, tinggi badan, dan lingkar kepala siswa.</span>
    </div>
</div>

<!-- Menambahkan border-0 dan shadow-sm agar sama dengan halaman lain -->
<div class="card w-100 border-0 shadow-sm position-relative overflow-hidden">
    <div class="card-body p-4">
        <h5 class="fw-semibold mb-4 text-dark">Daftar Siswa</h5>
        <div class="table-responsive">
            <!-- Menghapus text-nowrap agar konsisten -->
            <table class="table align-middle mb-0">
                <thead style="border-bottom: 2px solid #2d5cf7;">
                    <tr>
                        <th width="5%" class="border-0 bg-transparent text-dark fw-semibold py-3 ps-0 text-center">No</th>
                        <th width="60%" class="border-0 bg-transparent text-dark fw-semibold py-3">Nama Siswa</th>
                        <th width="35%" class="border-0 bg-transparent text-dark fw-semibold py-3 text-center pe-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $index => $s)
                    <tr class="border-bottom" style="border-color: #f3f4f6;">
                        <td class="text-center py-3  ps-0">{{ $index + 1 }}</td>
                        <td class="fw-medium py-3 text-dark">{{ $s->nama_lengkap }}</td>
                        <td class="text-center py-3 pe-0">
                            <a href="{{ route('perkembangan.show', $s->id) }}" class="btn btn-sm btn-primary rounded-pill px-4 mx-1 shadow-none" style="background-color: #2d5cf7; border: none;">
                                Laporan & Grafik
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection