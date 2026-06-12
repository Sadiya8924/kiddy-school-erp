@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-semibold text-dark mb-1">Data Siswa</h2>
        <span class="text-muted fs-3">Kelola data peserta didik dan penempatan kelas.</span>
    </div>

    <div class="d-flex gap-2 align-items-center flex-wrap">
        {{-- TOMBOL TAMBAH SISWA & IMPORT DAPODIK (Hanya untuk Admin) --}}
        @if(auth()->user()->role == 'admin')
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success rounded-pill px-4 d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImportCSV">
                    <iconify-icon icon="solar:file-download-bold-duotone" class="fs-5"></iconify-icon> Tarik Data Dapodik (CSV)
                </button>
                <a href="/pendaftaran-siswa?school_id={{ auth()->user()->school_id }}" class="btn btn-primary rounded-pill px-4 d-flex align-items-center gap-2 shadow-sm">
                    <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-5"></iconify-icon> Tambah Siswa
                </a>
            </div>
        @endif

        {{-- FORM FILTER KELAS --}}
        <form action="/data-siswa" method="GET" class="d-flex gap-2 align-items-center mb-0">
            <select name="kelas_id" class="form-select border-1 text-dark shadow-none" onchange="this.form.submit()" style="width: auto;">
                <option value="">-- Semua Siswa Aktif --</option>
                <optgroup label="Data Kelas">
                    @foreach($list_kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </optgroup>
                <optgroup label="Lainnya">
                    <option value="none" {{ request('kelas_id') == 'none' ? 'selected' : '' }}>Siswa Baru (Belum Ada Kelas)</option>
                    <option value="alumni" {{ request('kelas_id') == 'alumni' ? 'selected' : '' }}>Data Alumni (Lulus)</option>
                </optgroup>
            </select>
            @if(request('kelas_id'))
                <a href="/data-siswa" class="btn btn-light rounded-pill px-3">Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card w-100 border-0 shadow-sm position-relative overflow-hidden mb-4">
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-0">No</th>
                        <th>Nama Lengkap</th>
                        <th>NIK</th>
                        <th>Kelas</th>
                        <th class="text-center pe-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $index => $siswa)
                    <tr>
                        <td class="ps-0">{{ $index + 1 }}</td>
                        <td class="fw-medium text-dark">
                            {{ $siswa->nama_lengkap }}
                            <div class="small text-muted">NISN: {{ $siswa->nisn ?? '-' }} | NIPD: {{ $siswa->no_induk ?? '-' }}</div>
                        </td>
                        <td>{{ $siswa->nik }}</td>
                        <td>
                            @if($siswa->status == 'lulus')
                                <span class="badge bg-success">Alumni (Lulus)</span>
                            @else
                                <span class="badge {{ $siswa->nama_kelas_aktif == 'Belum ada kelas' ? 'bg-warning' : 'bg-info' }} text-dark">
                                    {{ $siswa->nama_kelas_aktif }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center py-3 pe-0">
                            <a href="/siswa/{{ $siswa->id }}" class="btn btn-sm btn-primary rounded-pill px-4 mx-1 shadow-none">Profil</a>
                            
                            @auth
                                @if(auth()->user()->role == 'admin' && $siswa->status != 'lulus')
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 mx-1 shadow-none" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalSetKelas{{ $siswa->id }}">
                                        Set Kelas
                                    </button>
                                    
                                    <div class="modal fade" id="modalSetKelas{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered text-start">
                                            <div class="modal-content rounded-4 border-0 shadow">
                                                <form action="{{ route('siswa.set-kelas') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                                    <div class="modal-header border-0 p-4 pb-0">
                                                        <h5 class="fw-bold mb-0">Pilih Kelas untuk {{ $siswa->nama_lengkap }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <select name="kelas_id" class="form-select border-primary" required>
                                                            <option value="" disabled selected>-- Pilih Kelas --</option>
                                                            @php
                                                                $kelasAktifSiswa = $siswa->kelasAktif()->first();
                                                            @endphp
                                                            @foreach($list_kelas as $k)
                                                                <option value="{{ $k->id }}" {{ ($kelasAktifSiswa && $kelasAktifSiswa->id == $k->id) ? 'selected' : '' }}>
                                                                    {{ $k->nama_kelas }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer border-0 p-4 pt-0">
                                                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">Update Kelas Siswa</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endauth
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada data siswa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL IMPORT CSV DAPODIK (Hanya Admin) --}}
@if(auth()->user()->role == 'admin')
<div class="modal fade" id="modalImportCSV" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('siswa.import-nisn') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white border-bottom-0">
                    <h5 class="modal-title fw-bold text-white">Import Data Dapodik (CSV)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <div class="alert alert-warning border-0 rounded-3 small mb-4">
                        <iconify-icon icon="solar:info-circle-bold" class="fs-5 align-middle me-1"></iconify-icon>
                        <strong>Perhatian:</strong> Fitur ini digunakan untuk menarik data <strong>NISN</strong> dan <strong>No. Induk (NIPD)</strong> dari file Dapodik secara otomatis.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Pilih File Dapodik (.csv) <span class="text-danger">*</span></label>
                        <input type="file" name="file_csv" class="form-control" accept=".csv" required>
                        <div class="form-text text-muted mt-2">
                            * Pastikan file yang diupload adalah hasil download dari sistem Dapodik dengan format <strong>.csv</strong>.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">
                        <iconify-icon icon="solar:upload-square-bold" class="align-middle me-1"></iconify-icon> Mulai Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection