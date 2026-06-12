@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <!-- Menggunakan h2 agar ukuran font judul halaman sama persis -->
        <h2 class="fw-semibold text-dark mb-1">Absensi Harian Siswa</h2>
        <span class="text-muted fs-3">Input kehadiran siswa hari ini.</span>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <label class="fw-medium text-dark small mb-0">Tanggal:</label>
        <input type="date" class="form-control border-1 text-dark shadow-none" value="{{ date('Y-m-d') }}" style="width: auto;">
    </div>
</div>

<div class="card w-100 border-0 shadow-sm position-relative overflow-hidden">
    <div class="card-body p-4"> 
        <!-- Ditambahkan judul agar spacing vertikal tabel sama persis dengan Data Siswa -->
        <h5 class="fw-semibold mb-4 text-dark">Daftar Kehadiran</h5>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="border-bottom: 2px solid #2d5cf7;">
                    <tr>
                        <th width="5%" class="border-0 bg-transparent text-dark fw-semibold py-3 ps-0 text-center">No</th>
                        <th width="30%" class="border-0 bg-transparent text-dark fw-semibold py-3">Nama Siswa</th>
                        <th width="15%" class="border-0 bg-transparent text-dark fw-semibold py-3 text-center">Hadir</th>
                        <th width="15%" class="border-0 bg-transparent text-dark fw-semibold py-3 text-center">Sakit</th>
                        <th width="15%" class="border-0 bg-transparent text-dark fw-semibold py-3 text-center">Izin</th>
                        <th width="15%" class="border-0 bg-transparent text-dark fw-semibold py-3 text-center">Alfa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $index => $siswa)
                    <tr class="border-bottom" style="border-color: #f3f4f6;">
                        <td class="text-center py-3 fw-medium ps-0">{{ $index + 1 }}</td>
                        <td class="fw-medium py-3 text-dark">{{ $siswa->nama_lengkap }}</td>
                        <td class="text-center py-3"><input type="radio" name="status_{{ $siswa->id }}" value="H" class="form-check-input mt-0 border-primary shadow-none" checked style="width:1.2em; height:1.2em;"></td>
                        <td class="text-center py-3"><input type="radio" name="status_{{ $siswa->id }}" value="S" class="form-check-input mt-0 border-primary shadow-none" style="width:1.2em; height:1.2em;"></td>
                        <td class="text-center py-3"><input type="radio" name="status_{{ $siswa->id }}" value="I" class="form-check-input mt-0 border-warning shadow-none" style="width:1.2em; height:1.2em;"></td>
                        <td class="text-center py-3"><input type="radio" name="status_{{ $siswa->id }}" value="A" class="form-check-input mt-0 border-danger shadow-none" style="width:1.2em; height:1.2em;"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Button Simpan dipindah ke dalam card-body dan margin-nya disesuaikan -->
        <div class="text-end mt-4">
            <button class="btn btn-success fw-medium px-5 rounded-pill shadow-none" onclick="alert('Absensi hari ini berhasil disimpan!')">
                Simpan Absensi
            </button>
        </div>
    </div>
</div>
@endsection