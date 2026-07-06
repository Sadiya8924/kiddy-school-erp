@extends('layouts.admin')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    <div class="col-12 mb-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Data Peserta Didik</h4>
                <p class="text-muted mb-0 small">Kelola data siswa, penempatan kelas, dan tautan akun wali murid.</p>
            </div>

            <div class="d-flex gap-2 align-items-center flex-wrap">
                {{-- TOMBOL TAMBAH SISWA & IMPORT (Hanya Admin / Super Admin) --}}
                @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-success border-success-subtle bg-light text-success rounded px-3 py-2 d-flex align-items-center gap-2 shadow-none fw-medium" style="font-size: 13px;" data-bs-toggle="modal" data-bs-target="#modalImportCSV">
                            <iconify-icon icon="solar:file-download-bold-duotone" class="fs-5"></iconify-icon> Tarik Data Dapodik
                        </button>
                        <a href="/pendaftaran-siswa?school_id={{ auth()->user()->isSuperAdmin() ? session('active_school_id') : auth()->user()->school_id }}" class="btn btn-primary rounded px-3 py-2 d-flex align-items-center gap-2 shadow-sm fw-medium" style="font-size: 13px;">
                            <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-5"></iconify-icon> Tambah Siswa
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- FILTER KELAS & TAHUN --}}
    <div class="col-12 mb-2">
        <div class="card border shadow-none bg-light mb-0">
            <div class="card-body p-3">
                <form action="/data-siswa" method="GET" class="d-flex gap-3 align-items-center flex-wrap mb-0">
                    <label class="fw-bold text-dark small mb-0 d-none d-md-block"><iconify-icon icon="solar:filter-bold-duotone" class="text-primary fs-5 align-middle"></iconify-icon> Filter Data:</label>
                    
                    {{-- Dropdown Filter Status / Kelas --}}
                    <select name="kelas_id" class="form-select border-light-subtle text-dark shadow-none w-auto fw-medium" onchange="this.form.submit()" style="font-size: 13px;">
                        <option value="">-- Semua Siswa Aktif --</option>
                        <optgroup label="Data Kelas">
                            @foreach($list_kelas as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Lainnya">
                            <option value="none" {{ request('kelas_id') == 'none' ? 'selected' : '' }}>Siswa Baru (Belum Ada Kelas)</option>
                            <option value="menunggu" {{ request('kelas_id') == 'menunggu' ? 'selected' : '' }}>Calon Siswa (Menunggu Verifikasi)</option>
                            <option value="alumni" {{ request('kelas_id') == 'alumni' ? 'selected' : '' }}>Data Alumni (Lulus)</option>
                        </optgroup>
                    </select>

                    {{-- Dropdown Filter Tahun Pendaftaran (BARU) --}}
                    <select name="tahun_inden" class="form-select border-light-subtle text-dark shadow-none w-auto fw-medium" onchange="this.form.submit()" style="font-size: 13px;">
                        <option value="">-- Semua Tahun Pendaftaran --</option>
                        @foreach($list_tahun_inden as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun_inden') == $tahun ? 'selected' : '' }}>Pendaftaran {{ $tahun }}</option>
                        @endforeach
                    </select>

                    @if(request('kelas_id') || request('tahun_inden'))
                        <a href="/data-siswa" class="btn btn-outline-secondary bg-white border rounded px-3 py-1.5 fw-medium" style="font-size: 12px;">Reset Filter</a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="col-12 mt-0">
            <div class="alert alert-success border border-success-subtle shadow-none rounded py-2 px-3 mb-0 small fw-medium">{{ session('success') }}</div>
        </div>
    @endif
    @if($errors->any())
        <div class="col-12 mt-0">
            <div class="alert alert-danger border border-danger-subtle shadow-none rounded py-2 px-3 mb-0 small fw-medium">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- TABEL DATA SISWA --}}
    <div class="col-12">
        <div class="card border shadow-none mb-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 border-0">
                        <thead class="bg-light border-bottom border-light-subtle">
                            <tr>
                                <th class="text-center text-muted fw-semibold py-3" style="width: 5%; font-size: 11px;">NO</th>
                                <th class="text-muted fw-semibold py-3" style="font-size: 11px;">NAMA LENGKAP & IDENTITAS</th>
                                <th class="text-muted fw-semibold py-3" style="font-size: 11px;">STATUS KELAS / PENDAFTARAN</th>
                                <th class="text-muted fw-semibold py-3" style="font-size: 11px;">AKUN ORTU (PORTAL)</th>
                                <th class="text-end text-muted fw-semibold py-3 pe-4" style="font-size: 11px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswas as $index => $siswa)
                            <tr class="border-bottom border-light-subtle table-hover-custom" style="cursor: pointer;" onclick="window.location.href='/siswa/{{ $siswa->id }}'" title="Klik untuk lihat detail siswa">
                                <td class="text-center text-muted py-3" style="font-size: 13px;">{{ $index + 1 }}</td>
                                <td class="py-3">
                                    <div class="fw-bold text-dark mb-1" style="font-size: 14px;">{{ $siswa->nama_lengkap }}</div>
                                    <div class="text-muted" style="font-size: 11px;">NISN: {{ $siswa->nisn ?? '-' }} | NIK: {{ $siswa->nik ?? '-' }}</div>
                                </td>
                                <td class="py-3">
                                    @if($siswa->status == 'lulus')
                                        <span class="badge bg-success border rounded px-2 py-1" style="font-size: 10px;">Alumni (Lulus)</span>
                                    @elseif($siswa->status == 'menunggu')
                                        <span class="badge bg-warning-subtle text-warning border-warning-subtle border rounded px-2 py-1" style="font-size: 11px; font-weight: 600;">
                                            <iconify-icon icon="solar:hourglass-line-duotone" class="align-middle"></iconify-icon> Menunggu Verifikasi
                                        </span>
                                        <div class="text-muted mt-1 fw-bold" style="font-size: 10px;">{{ $siswa->program_pilihan }}</div>
                                    @else
                                        <span class="badge {{ $siswa->nama_kelas_aktif == 'Belum ada kelas' ? 'bg-danger-subtle text-danger border-danger-subtle' : 'bg-primary-subtle text-primary border-primary-subtle' }} border rounded px-2 py-1" style="font-size: 11px; font-weight: 600;">
                                            {{ $siswa->nama_kelas_aktif }}
                                        </span>
                                        {{-- Tampilkan Tahun kalau dia belum punya kelas --}}
                                        @if($siswa->nama_kelas_aktif == 'Belum ada kelas')
                                            <div class="text-muted mt-1 fw-bold" style="font-size: 10px;">{{ $siswa->program_pilihan }}</div>
                                        @endif
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if($siswa->email)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded px-2 py-1 mb-1 d-inline-flex align-items-center gap-1" style="font-size: 10px;"><iconify-icon icon="solar:link-bold"></iconify-icon> Terhubung</span>
                                        <div class="text-muted fw-medium" style="font-size: 11px;">{{ $siswa->email }}</div>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded px-2 py-1 d-inline-flex align-items-center gap-1" style="font-size: 10px;"><iconify-icon icon="solar:link-broken-bold"></iconify-icon> Belum Terhubung</span>
                                    @endif
                                <td class="text-end py-3 pe-4">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        
                                        @auth
                                            @if(in_array(auth()->user()->role, ['admin', 'super_admin']) && $siswa->status != 'lulus')
                                                
                                                {{-- JIKA STATUS MENUNGGU (CALON SISWA BARU) --}}
                                                @if($siswa->status == 'menunggu')
                                                    <button type="button" class="btn btn-sm btn-warning rounded shadow-none px-3 fw-bold text-dark" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#modalVerifikasi{{ $siswa->id }}" onclick="event.stopPropagation();">
                                                        Verifikasi & Terima
                                                    </button>

                                                    {{-- Modal Verifikasi Pendaftaran --}}
                                                    <div class="modal fade text-start" id="modalVerifikasi{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content rounded border shadow-sm">
                                                                <div class="modal-header bg-warning-subtle border-bottom p-3">
                                                                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2"><iconify-icon icon="solar:wallet-bold-duotone" class="fs-5 text-warning"></iconify-icon> Verifikasi Calon Siswa</h6>
                                                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body p-4 text-center">
                                                                    <p class="mb-3 text-dark fw-medium">Bukti Pembayaran Pendaftaran:</p>
                                                                    @if($siswa->bukti_pembayaran)
                                                                        <img src="{{ asset('storage/' . $siswa->bukti_pembayaran) }}" alt="Bukti Transfer" class="img-fluid rounded border shadow-sm mb-3" style="max-height: 300px; object-fit: contain;">
                                                                    @else
                                                                        <div class="alert alert-secondary text-muted small py-4 mb-3">
                                                                            <iconify-icon icon="solar:gallery-remove-bold-duotone" class="fs-1 d-block mx-auto mb-2 opacity-50"></iconify-icon>
                                                                            Siswa ini tidak melampirkan bukti pembayaran.
                                                                        </div>
                                                                    @endif
                                                                    
                                                                    <p class="text-muted small mb-0">Apakah pembayaran sudah masuk dan Anda ingin menerima <strong>{{ $siswa->nama_lengkap }}</strong> sebagai siswa resmi?</p>
                                                                </div>
                                                                <div class="modal-footer border-top p-3 d-flex justify-content-between">
                                                                    <button type="button" class="btn btn-outline-secondary rounded fw-medium px-4 shadow-none" style="font-size: 12px;" data-bs-dismiss="modal">Tutup</button>
                                                                    <form action="{{ route('siswa.terima', $siswa->id) }}" method="POST" class="m-0">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-success rounded fw-bold px-4 shadow-none" style="font-size: 12px;">Terima & Aktifkan Siswa</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                {{-- JIKA STATUS AKTIF (SUDAH DITERIMA) --}}
                                                @else
                                                    {{-- Tombol Tautkan Ortu --}}
                                                    <button type="button" class="btn btn-sm btn-outline-success bg-success-subtle border-success-subtle text-success rounded shadow-none px-3 fw-bold" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#modalTautkanOrtu{{ $siswa->id }}" onclick="event.stopPropagation();">
                                                        Link Ortu
                                                    </button>

                                                    {{-- Tombol Set Kelas --}}
                                                    <button type="button" class="btn btn-sm btn-primary rounded shadow-none px-3 fw-bold" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#modalSetKelas{{ $siswa->id }}" onclick="event.stopPropagation();">
                                                        Set Kelas
                                                    </button>

                                                    {{-- Modal Tautkan Ortu --}}
                                                    <div class="modal fade text-start" id="modalTautkanOrtu{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content rounded border shadow-sm">
                                                                <form action="{{ route('siswa.tautkan-ortu') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                                                    <div class="modal-header bg-light border-bottom p-3">
                                                                        <h6 class="fw-bold mb-0 text-success d-flex align-items-center gap-2"><iconify-icon icon="solar:link-bold-duotone" class="fs-5"></iconify-icon> Tautkan Akun Ortu</h6>
                                                                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body p-4">
                                                                        <p class="text-muted small mb-3">Masukkan email akun Wali Murid yang sudah terdaftar di sistem untuk menautkan data <strong>{{ $siswa->nama_lengkap }}</strong> ke Dashboard mereka.</p>
                                                                        <div class="mb-2">
                                                                            <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Email Akun Wali Murid</label>
                                                                            <input type="email" name="email" class="form-control border-success-subtle bg-light shadow-none" placeholder="contoh: ortu@tk.com" value="{{ $siswa->email }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer border-top p-3">
                                                                        <button type="submit" class="btn btn-success rounded fw-bold px-4 shadow-none" style="font-size: 12px;">Simpan Tautan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Modal Set Kelas --}}
                                                    <div class="modal fade text-start" id="modalSetKelas{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content rounded border shadow-sm">
                                                                <form action="{{ route('siswa.set-kelas') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                                                    <div class="modal-header bg-light border-bottom p-3">
                                                                        <h6 class="fw-bold mb-0 text-dark">Pilih Kelas untuk {{ $siswa->nama_lengkap }}</h6>
                                                                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body p-4">
                                                                        <select name="kelas_id" class="form-select border-primary-subtle shadow-none bg-light fw-medium" required>
                                                                            <option value="" disabled selected>-- Pilih Kelas Tujuan --</option>
                                                                            @php $kelasAktifSiswa = $siswa->kelasAktif()->first(); @endphp
                                                                            @foreach($list_kelas as $k)
                                                                                <option value="{{ $k->id }}" {{ ($kelasAktifSiswa && $kelasAktifSiswa->id == $k->id) ? 'selected' : '' }}>
                                                                                    {{ $k->nama_kelas }} (Tingkat: {{ $k->tingkat }})
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="modal-footer border-top p-3">
                                                                        <button type="submit" class="btn btn-primary rounded fw-bold px-4 shadow-none" style="font-size: 12px;">Simpan Penempatan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                            @endif
                                        @endauth
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-muted opacity-25 mb-3" style="font-size: 50px;"></iconify-icon>
                                    <p class="text-muted mb-0 fw-medium">Belum ada data siswa.</p>
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

{{-- MODAL IMPORT CSV DAPODIK --}}
@if(in_array(auth()->user()->role, ['admin', 'super_admin']))
<div class="modal fade" id="modalImportCSV" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded border shadow-sm">
            <form action="{{ route('siswa.import-nisn') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success-subtle border-bottom p-3">
                    <h6 class="modal-title fw-bold text-success d-flex align-items-center gap-2"><iconify-icon icon="solar:file-download-bold-duotone" class="fs-5"></iconify-icon> Import Data Dapodik (CSV)</h6>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <div class="alert alert-warning border border-warning-subtle rounded-3 small mb-4 py-2 px-3">
                        <iconify-icon icon="solar:info-circle-bold" class="fs-5 align-middle me-1"></iconify-icon>
                        Gunakan fitur ini untuk menarik data <strong>NISN</strong> dan <strong>No. Induk (NIPD)</strong> dari file Dapodik secara otomatis.
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label fw-bold text-dark" style="font-size: 13px;">Upload File Dapodik (.csv) <span class="text-danger">*</span></label>
                        <input type="file" name="file_csv" class="form-control shadow-none bg-light border-light-subtle" accept=".csv" required>
                        <div class="form-text text-muted mt-2" style="font-size: 11px;">
                            * File wajib berformat <strong>.csv</strong> hasil export dari sistem Dapodik.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-outline-secondary rounded fw-medium px-4" data-bs-dismiss="modal" style="font-size: 12px;">Batal</button>
                    <button type="submit" class="btn btn-success rounded fw-bold px-4 shadow-none" style="font-size: 12px;">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection