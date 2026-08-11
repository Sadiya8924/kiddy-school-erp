@extends('layouts.admin')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    
    {{-- Header Halaman --}}
    <div class="col-12 mb-2">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('data-siswa') }}" class="btn btn-outline-light border shadow-none text-muted p-2 rounded d-inline-flex"><iconify-icon icon="solar:arrow-left-line-duotone" class="fs-5"></iconify-icon></a>
                <div>
                    <h4 class="fw-bold text-dark mb-0">Profil Lengkap Siswa</h4>
                    <p class="text-muted mb-0 small">Detail biodata, alamat, dan data orang tua peserta didik.</p>
                </div>
            </div>
            @if($siswa->status == 'menunggu')
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold border border-warning">Status: Menunggu Verifikasi</span>
            @elseif($siswa->status == 'aktif')
                <span class="badge bg-success px-3 py-2 rounded-pill fw-bold border border-success">Status: Siswa Aktif</span>
            @else
                <span class="badge bg-secondary px-3 py-2 rounded-pill fw-bold border border-secondary">Status: Alumni (Lulus)</span>
            @endif
        </div>
    </div>

    <div class="row mt-3">
        {{-- KARTU KIRI: FOTO & IDENTITAS SINGKAT --}}
        <div class="col-lg-4 col-xl-3 mb-4">
            <div class="card border shadow-sm rounded-4 text-center">
                <div class="card-body p-4">
                    <div class="position-relative d-inline-block mb-3">
                        @if($siswa->foto_anak)
                            <img src="{{ asset('storage/' . $siswa->foto_anak) }}" alt="Foto Siswa" class="rounded-circle border border-4 border-white shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center mx-auto border border-4 border-white shadow-sm" style="width: 120px; height: 120px;">
                                <iconify-icon icon="solar:user-bold-duotone" style="font-size: 60px;"></iconify-icon>
                            </div>
                        @endif
                        
                        <span class="position-absolute bottom-0 end-0 translate-middle p-2 bg-success border border-light rounded-circle" title="Aktif"></span>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1">{{ $siswa->nama_lengkap }}</h5>
                    <p class="text-muted small mb-3">NIK: {{ $siswa->nik }}</p>

                    <div class="d-flex justify-content-between align-items-center bg-light rounded p-2 px-3 border border-light-subtle">
                        <span class="small text-muted fw-medium">Kelas Aktif</span>
                        <span class="badge bg-primary-subtle text-primary fw-bold">{{ $siswa->nama_kelas_aktif }}</span>
                    </div>
                    
                    @if($siswa->program_pilihan)
                        <div class="d-flex justify-content-between align-items-center bg-light rounded p-2 px-3 border border-light-subtle mt-2">
                            <span class="small text-muted fw-medium">Program</span>
                            <span class="badge bg-secondary-subtle text-secondary fw-bold" style="font-size: 10px;">{{ $siswa->program_pilihan }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KARTU KANAN: DATA LENGKAP DAPODIK --}}
        <div class="col-lg-8 col-xl-9">
            <div class="card border shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:document-text-bold-duotone" class="text-primary"></iconify-icon> Biodata Lengkap Peserta Didik
                    </h5>
                </div>
                
                <div class="card-body p-0">
                    
                    {{-- A. INFORMASI PRIBADI --}}
                    <div class="p-4 border-bottom">
                        <h6 class="fw-bold text-primary mb-3 text-uppercase" style="letter-spacing: 0.5px; font-size: 12px;">A. Informasi Pribadi</h6>
                        <div class="row gy-3">
                            <div class="col-md-6"><span class="d-block small text-muted">Nama Lengkap</span><strong class="text-dark">{{ $siswa->nama_lengkap ?? '-' }}</strong></div>
                            <div class="col-md-3"><span class="d-block small text-muted">NISN</span><strong class="text-dark">{{ $siswa->nisn ?? '-' }}</strong></div>
                            <div class="col-md-3"><span class="d-block small text-muted">No. Induk (NIPD)</span><strong class="text-dark">{{ $siswa->no_induk ?? '-' }}</strong></div>
                            
                            <div class="col-md-6"><span class="d-block small text-muted">Tempat, Tanggal Lahir</span><strong class="text-dark">{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tgl_lahir ? date('d F Y', strtotime($siswa->tgl_lahir)) : '-' }}</strong></div>
                            <div class="col-md-3"><span class="d-block small text-muted">Jenis Kelamin</span><strong class="text-dark">{{ $siswa->jenis_kelamin == 'L' || $siswa->jenis_kelamin == 'Laki-laki' ? 'Laki-laki' : 'Perempuan' }}</strong></div>
                            <div class="col-md-3"><span class="d-block small text-muted">Agama</span><strong class="text-dark">{{ $siswa->agama ?? '-' }}</strong></div>
                            
                            <div class="col-md-3"><span class="d-block small text-muted">Anak ke-</span><strong class="text-dark">{{ $siswa->anak_ke ?? '-' }}</strong></div>
                            <div class="col-md-3"><span class="d-block small text-muted">Jumlah Saudara</span><strong class="text-dark">{{ $siswa->jml_saudara ?? '-' }}</strong></div>
                        </div>
                    </div>

                    {{-- B. ALAMAT & KONTAK --}}
                    <div class="p-4 border-bottom bg-light bg-opacity-50">
                        <h6 class="fw-bold text-primary mb-3 text-uppercase" style="letter-spacing: 0.5px; font-size: 12px;">B. Alamat & Kontak</h6>
                        <div class="row gy-3">
                            <div class="col-md-12"><span class="d-block small text-muted">Alamat Lengkap</span>
                                <strong class="text-dark">
                                    {{ $siswa->alamat ?? '-' }} 
                                    @if($siswa->rt || $siswa->rw) RT {{ $siswa->rt ?? '-' }}/RW {{ $siswa->rw ?? '-' }} @endif
                                    @if($siswa->dusun) Dusun {{ $siswa->dusun }} @endif
                                </strong>
                            </div>
                            <div class="col-md-4"><span class="d-block small text-muted">Desa / Kelurahan</span><strong class="text-dark">{{ $siswa->kelurahan ?? '-' }}</strong></div>
                            <div class="col-md-4"><span class="d-block small text-muted">Kecamatan</span><strong class="text-dark">{{ $siswa->kecamatan ?? '-' }}</strong></div>
                            <div class="col-md-4"><span class="d-block small text-muted">Kode Pos</span><strong class="text-dark">{{ $siswa->kode_pos ?? '-' }}</strong></div>
                            
                            <div class="col-md-4"><span class="d-block small text-muted">Jenis Tempat Tinggal</span><strong class="text-dark">{{ $siswa->jenis_tinggal ?? '-' }}</strong></div>
                            <div class="col-md-4"><span class="d-block small text-muted">Alat Transportasi</span><strong class="text-dark">{{ $siswa->alat_transportasi ?? '-' }}</strong></div>
                            <div class="col-md-4"><span class="d-block small text-muted">Jarak ke Sekolah</span><strong class="text-dark">{{ $siswa->jarak_rumah ? $siswa->jarak_rumah . ' Km' : '-' }}</strong></div>
                            
                            <div class="col-md-4"><span class="d-block small text-muted">No. HP / WhatsApp</span><strong class="text-dark">{{ $siswa->hp ?? '-' }}</strong></div>
                            <div class="col-md-8"><span class="d-block small text-muted">Email Wali Murid</span><strong class="text-dark">{{ $siswa->email ?? '-' }}</strong></div>

                            @php
                                $latVal = $siswa->lintang;
                                $lngVal = $siswa->bujur;

                                // Fallback jika lintang/bujur belum terisi terpisah tetapi koordinat_rumah berisi pasangan angka "lat, lng"
                                if ((!$latVal || $latVal == '0' || $latVal == '-') && $siswa->koordinat_rumah && str_contains($siswa->koordinat_rumah, ',')) {
                                    $parts = explode(',', $siswa->koordinat_rumah);
                                    if (count($parts) >= 2 && is_numeric(trim($parts[0])) && is_numeric(trim($parts[1]))) {
                                        $latVal = trim($parts[0]);
                                        $lngVal = trim($parts[1]);
                                    }
                                }
                            @endphp

                            <div class="col-md-12 border-top pt-3 mt-2">
                                <div class="row gy-3">
                                    <div class="col-md-4">
                                        <span class="d-block small text-muted"><iconify-icon icon="solar:map-point-wave-bold-duotone" class="text-primary me-1 align-middle fs-6"></iconify-icon> Garis Lintang (Latitude)</span>
                                        <strong class="text-dark font-monospace" style="font-size: 13.5px;">{{ ($latVal && $latVal != '0') ? $latVal : '-' }}</strong>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="d-block small text-muted"><iconify-icon icon="solar:map-point-wave-bold-duotone" class="text-primary me-1 align-middle fs-6"></iconify-icon> Garis Bujur (Longitude)</span>
                                        <strong class="text-dark font-monospace" style="font-size: 13.5px;">{{ ($lngVal && $lngVal != '0') ? $lngVal : '-' }}</strong>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="d-block small text-muted">Koordinat / Lokasi Peta</span>
                                        <strong class="text-dark d-block" style="font-size: 12.5px;">{{ $siswa->koordinat_rumah && $siswa->koordinat_rumah != '-' ? $siswa->koordinat_rumah : '-' }}</strong>
                                        @if(($latVal && $latVal != '0') && ($lngVal && $lngVal != '0'))
                                            <a href="https://www.google.com/maps?q={{ $latVal }},{{ $lngVal }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill font-semibold px-2.5 py-0.5 mt-1.5 d-inline-flex align-items-center gap-1" style="font-size: 11px;">
                                                <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon> Buka di Google Maps ↗
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- C. DATA ORANG TUA --}}
                    <div class="p-4 border-bottom">
                        <h6 class="fw-bold text-primary mb-3 text-uppercase" style="letter-spacing: 0.5px; font-size: 12px;">C. Data Orang Tua / Wali</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <div class="border border-light-subtle rounded p-3 h-100">
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-2">Ayah Kandung</h6>
                                    <table class="table table-sm table-borderless mb-0 small">
                                        <tr><td width="40%" class="text-muted">Nama</td><td>: <strong>{{ $siswa->nama_ayah ?? '-' }}</strong></td></tr>
                                        <tr><td class="text-muted">NIK</td><td>: <strong>{{ $siswa->nik_ayah ?? '-' }}</strong></td></tr>
                                        <tr><td class="text-muted">Tahun Lahir</td><td>: <strong>{{ $siswa->tahun_lahir_ayah ?? '-' }}</strong></td></tr>
                                        <tr><td class="text-muted">Pendidikan</td><td>: <strong>{{ $siswa->pendidikan_ayah ?? '-' }}</strong></td></tr>
                                        <tr><td class="text-muted">Pekerjaan</td><td>: <strong>{{ $siswa->pekerjaan_ayah ?? '-' }}</strong></td></tr>
                                        <tr><td class="text-muted">Penghasilan</td><td>: <strong>{{ $siswa->penghasilan_ayah ?? '-' }}</strong></td></tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border border-light-subtle rounded p-3 h-100">
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-2">Ibu Kandung</h6>
                                    <table class="table table-sm table-borderless mb-0 small">
                                        <tr><td width="40%" class="text-muted">Nama</td><td>: <strong>{{ $siswa->nama_ibu ?? '-' }}</strong></td></tr>
                                        <tr><td class="text-muted">NIK</td><td>: <strong>{{ $siswa->nik_ibu ?? '-' }}</strong></td></tr>
                                        <tr><td class="text-muted">Tahun Lahir</td><td>: <strong>{{ $siswa->tahun_lahir_ibu ?? '-' }}</strong></td></tr>
                                        <tr><td class="text-muted">Pendidikan</td><td>: <strong>{{ $siswa->pendidikan_ibu ?? '-' }}</strong></td></tr>
                                        <tr><td class="text-muted">Pekerjaan</td><td>: <strong>{{ $siswa->pekerjaan_ibu ?? '-' }}</strong></td></tr>
                                        <tr><td class="text-muted">Penghasilan</td><td>: <strong>{{ $siswa->penghasilan_ibu ?? '-' }}</strong></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- D. BERKAS DOKUMEN --}}
                    <div class="p-4 bg-light bg-opacity-50">
                        <h6 class="fw-bold text-primary mb-3 text-uppercase" style="letter-spacing: 0.5px; font-size: 12px;">D. Berkas & Dokumen Pendaftaran</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="d-block small text-muted mb-2">Bukti Pembayaran Pendaftaran</span>
                                @if($siswa->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $siswa->bukti_pembayaran) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $siswa->bukti_pembayaran) }}" class="img-fluid rounded border shadow-sm" style="max-height: 200px; object-fit: cover;" alt="Bukti Pembayaran">
                                    </a>
                                @else
                                    <div class="alert alert-secondary small py-2 d-inline-block">Tidak ada bukti pembayaran</div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection