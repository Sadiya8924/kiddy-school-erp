@extends('layouts.admin')

@section('konten_halaman')
<div class="row">
    {{-- BARIS ATAS / HEADER --}}
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <a href="{{ route('data-siswa') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
                    <iconify-icon icon="solar:arrow-left-bold" class="align-middle"></iconify-icon> Kembali ke Data Siswa
                </a>
                <h2 class="fw-semibold text-dark mb-1">Profil Lengkap Siswa</h2>
                <span class="text-muted fs-3">Informasi biodata pribadi dan status akademik peserta didik.</span>
            </div>
            
            {{-- TOMBOL NAVIGATION KE RAPOR --}}
            <div>
                <a href="/rapor/{{ $siswa->id }}" class="btn btn-primary rounded-pill px-4 d-flex align-items-center gap-2 shadow-sm py-2">
                    <iconify-icon icon="solar:file-text-bold-duotone" class="fs-5"></iconify-icon> Lihat Nilai Rapor Ananda
                </a>
            </div>
        </div>
    </div>

    {{-- KARTU FOTO & RINGKASAN PROFIL --}}
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100 bg-white">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div class="mb-3 position-relative">
                    @if($siswa->foto_anak)
                        <img src="{{ asset('storage/' . $siswa->foto_anak) }}" alt="Foto {{ $siswa->nama_lengkap }}" class="rounded-circle border border-4 border-light-subtle shadow-sm object-cover" style="width: 140px; height: 140px; object-fit: cover;">
                    @else
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto border border-4 border-white shadow-sm" style="width: 140px; height: 140px;">
                            <iconify-icon icon="solar:user-circle-bold-duotone" style="font-size: 100px;"></iconify-icon>
                        </div>
                    @endif
                    <span class="position-absolute bottom-0 end-0 badge rounded-pill {{ $siswa->status == 'aktif' ? 'bg-success' : 'bg-secondary' }} px-3 py-2 border border-3 border-white">
                        {{ ucfirst($siswa->status) }}
                    </span>
                </div>
                
                <h4 class="fw-bold text-dark mb-1">{{ $siswa->nama_lengkap }}</h4>
                <p class="text-muted small mb-3">NIK: {{ $siswa->nik ?? '-' }}</p>
                
                <div class="w-100 border-top pt-3 mt-2">
                    <div class="row text-start g-2">
                        <div class="col-5 text-muted small">Kelas Aktif</div>
                        <div class="col-7 fw-semibold text-dark text-end">
                            <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill fs-2">
                                {{ $siswa->nama_kelas_aktif }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DETAIL BIODATA LENGKAP --}}
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:document-text-bold-duotone" class="text-primary fs-5"></iconify-icon> Biodata Pribadi Peserta Didik
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <tbody>
                            <tr class="border-bottom" style="border-color: #f8f9fa;">
                                <td width="30%" class="text-muted py-3 ps-0">Nama Lengkap</td>
                                <td class="fw-bold text-dark py-3">{{ $siswa->nama_lengkap }}</td>
                            </tr>
                            <tr class="border-bottom" style="border-color: #f8f9fa;">
                                <td class="text-muted py-3 ps-0">Nomor Induk Kependudukan (NIK)</td>
                                <td class="fw-medium text-dark py-3">{{ $siswa->nik ?? '-' }}</td>
                            </tr>
                            {{-- INI BARIS BARU UNTUK NISN --}}
                            <tr class="border-bottom" style="border-color: #f8f9fa;">
                                <td class="text-muted py-3 ps-0">Nomor Induk Siswa Nasional (NISN)</td>
                                <td class="fw-medium text-dark py-3">{{ $siswa->nisn ?? '-' }}</td>
                            </tr>
                            <tr class="border-bottom" style="border-color: #f8f9fa;">
                                <td class="text-muted py-3 ps-0">Jenis Kelamin</td>
                                <td class="fw-medium text-dark py-3">
                                    <span class="badge {{ $siswa->jenis_kelamin == 'Laki-laki' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} px-3 py-2 rounded-pill">
                                        {{ $siswa->jenis_kelamin ?? 'Laki-laki' }}
                                    </span>
                                </td>
                            </tr>
                            <tr class="border-bottom" style="border-color: #f8f9fa;">
                                <td class="text-muted py-3 ps-0">Tempat, Tanggal Lahir</td>
                                <td class="fw-medium text-dark py-3">
                                    {{ $siswa->tempat_lahir ?? '-' }}, 
                                    {{ $siswa->tgl_lahir ? \Carbon\Carbon::parse($siswa->tgl_lahir)->translatedFormat('d F Y') : '-' }}
                                </td>
                            </tr>
                            <tr class="border-bottom" style="border-color: #f8f9fa;">
                                <td class="text-muted py-3 ps-0">Nama Orang Tua / Wali</td>
                                <td class="fw-semibold text-dark py-3">{{ $siswa->nama_ortu ?? '-' }}</td>
                            </tr>
                            <tr class="border-bottom" style="border-color: #f8f9fa;">
                                <td class="text-muted py-3 ps-0">Nama Ibu Kandung</td>
                                <td class="fw-medium text-dark py-3">{{ $siswa->nama_ibu ?? '-' }}</td>
                            </tr>
                            <tr class="border-bottom" style="border-color: #f8f9fa;">
                                <td class="text-muted py-3 ps-0">Nama Ayah Kandung</td>
                                <td class="fw-medium text-dark py-3">{{ $siswa->nama_ayah ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection