@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

    @if(session('success'))
        <div class="alert alert-success border border-success-subtle shadow-none rounded-3 py-2 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
            <div><i class="ti ti-check me-2"></i>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger border border-danger-subtle shadow-none rounded-3 py-2 px-3 mb-0 small fw-semibold">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    {{-- HERO WELCOME CARD WITH SOFT BLUE GRADIENT --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%); border-radius: 16px !important;">
        <div class="card-body p-4 text-white">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                
                {{-- User Greeting & Context --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid rgba(255,255,255,0.4);">
                            <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Data Peserta Didik</h5>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Kelola data siswa, penempatan kelas, dan tautan akun wali murid.</span>
                        </p>
                    </div>
                </div>

                {{-- Action Controls --}}
                @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
                <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                    <button type="button" class="btn btn-light text-primary rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#modalImportCSV">
                        <iconify-icon icon="solar:file-download-bold-duotone" class="fs-5"></iconify-icon> Tarik Data Dapodik
                    </button>
                    <a href="/pendaftaran-siswa?school_id={{ auth()->user()->isSuperAdmin() ? session('active_school_id') : auth()->user()->school_id }}" class="btn btn-warning text-dark rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
                        <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-5"></iconify-icon> + Tambah Siswa
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>

    @include('partials.owner_stepped_filter')

    @if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))
    
    {{-- FILTER KELAS & TAHUN --}}
    <div class="card border shadow-sm" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
        <div class="card-body p-3.5">
            <form action="/data-siswa" method="GET" class="d-flex gap-3 align-items-center flex-wrap mb-0">
                <label class="font-semibold text-dark small mb-0 d-none d-md-block" style="font-size: 12.5px;">
                    <iconify-icon icon="solar:filter-bold-duotone" class="text-primary fs-5 align-middle me-1"></iconify-icon> Filter Data:
                </label>
                
                {{-- Dropdown Filter Status / Kelas --}}
                <select name="kelas_id" class="form-select rounded-3 text-dark shadow-none w-auto font-medium" onchange="this.form.submit()" style="font-size: 12.5px;">
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

                {{-- Dropdown Filter Tahun Pendaftaran --}}
                <select name="tahun_inden" class="form-select rounded-3 text-dark shadow-none w-auto font-medium" onchange="this.form.submit()" style="font-size: 12.5px;">
                    <option value="">-- Semua Tahun Pendaftaran --</option>
                    @foreach($list_tahun_inden as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun_inden') == $tahun ? 'selected' : '' }}>Pendaftaran {{ $tahun }}</option>
                    @endforeach
                </select>

                {{-- Search Input (TM-38) --}}
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama / NIK / NISN..." class="form-control form-control-sm border-light-subtle shadow-none w-auto" style="font-size: 13px; min-width: 200px;">

                @if(request('kelas_id') || request('tahun_inden') || request('q'))
                    <a href="/data-siswa" class="btn btn-outline-secondary rounded-3 px-3 py-1.5 font-semibold" style="font-size: 12px;">Reset Filter</a>
                @endif
            </form>
        </div>
    </div>

    {{-- TABEL DATA SISWA --}}
    <div class="card border shadow-sm" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
        <div class="card-body p-4 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
                <div>
                    <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15px;">
                        <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="text-primary fs-5"></iconify-icon> Daftar Peserta Didik
                    </h6>
                    <span class="text-muted small" style="font-size: 11.5px;">Klik pada baris siswa untuk melihat detail lengkap profil &amp; akademik</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr class="border-bottom border-light-subtle">
                            <th width="40" class="ps-0 pb-2 text-center">#</th>
                            <th class="pb-2">NAMA LENGKAP &amp; IDENTITAS</th>
                            <th class="pb-2">STATUS KELAS / PENDAFTARAN</th>
                            <th class="pb-2">AKUN ORTU (PORTAL)</th>
                            <th class="text-end pe-0 pb-2">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $index => $siswa)
                        <tr class="border-bottom border-light-subtle table-hover-custom" style="cursor: pointer;" onclick="window.location.href='/siswa/{{ $siswa->id }}'" title="Klik untuk lihat detail siswa">
                            <td class="ps-0 py-3 text-center text-muted font-semibold" style="font-size: 13px;">{{ $index + 1 }}</td>
                            <td class="py-3">
                                <div class="fw-bold text-dark mb-0.5" style="font-size: 14px;">{{ $siswa->nama_lengkap }}</div>
                                <div class="text-muted font-medium" style="font-size: 11.5px;">NISN: {{ $siswa->nisn ?? '-' }} | NIK: {{ $siswa->nik ?? '-' }}</div>
                            </td>
                            <td class="py-3">
                                @if($siswa->status == 'lulus')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px;">Alumni (Lulus)</span>
                                @elseif($siswa->status == 'menunggu')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px;">
                                        <iconify-icon icon="solar:hourglass-line-duotone" class="align-middle me-1"></iconify-icon> Menunggu Verifikasi
                                    </span>
                                    <div class="text-muted mt-1 font-semibold" style="font-size: 10.5px;">{{ $siswa->program_pilihan }}</div>
                                @else
                                    <span class="badge {{ $siswa->nama_kelas_aktif == 'Belum ada kelas' ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' }} rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px;">
                                        {{ $siswa->nama_kelas_aktif }}
                                    </span>
                                    @if($siswa->nama_kelas_aktif == 'Belum ada kelas')
                                        <div class="text-muted mt-1 font-semibold" style="font-size: 10.5px;">{{ $siswa->program_pilihan }}</div>
                                    @endif
                                @endif
                            </td>
                            <td class="py-3">
                                @if($siswa->email)
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 font-semibold d-inline-flex align-items-center gap-1" style="font-size: 10.5px;">
                                        <iconify-icon icon="solar:link-bold"></iconify-icon> Terhubung
                                    </span>
                                    <div class="text-muted font-medium mt-0.5" style="font-size: 11.5px;">{{ $siswa->email }}</div>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1 font-semibold d-inline-flex align-items-center gap-1" style="font-size: 10.5px;">
                                        <iconify-icon icon="solar:link-broken-bold"></iconify-icon> Belum Terhubung
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-end pe-0">
                                <div class="d-flex justify-content-end gap-1.5 flex-wrap" onclick="event.stopPropagation();">
                                    
                                    @auth
                                        @if(in_array(auth()->user()->role, ['admin', 'super_admin']) && $siswa->status != 'lulus')
                                            
                                            {{-- JIKA STATUS MENUNGGU --}}
                                            @if($siswa->status == 'menunggu')
                                                <button type="button" class="btn btn-warning text-dark btn-sm rounded-3 px-3 py-1 font-semibold shadow-xs" style="font-size: 11.5px;" data-bs-toggle="modal" data-bs-target="#modalVerifikasi{{ $siswa->id }}">
                                                    Verifikasi &amp; Terima
                                                </button>

                                                {{-- Modal Verifikasi --}}
                                                <div class="modal fade text-start" id="modalVerifikasi{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content rounded-4 border-0 shadow-lg">
                                                            <div class="modal-header bg-warning-subtle border-bottom p-3.5">
                                                                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                                                                    <iconify-icon icon="solar:wallet-bold-duotone" class="fs-5 text-warning"></iconify-icon> Verifikasi Calon Siswa
                                                                </h6>
                                                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body p-4 text-center">
                                                                <p class="mb-3 text-dark font-semibold">Bukti Pembayaran Pendaftaran:</p>
                                                                @if($siswa->bukti_pembayaran)
                                                                    <img src="{{ asset('storage/' . $siswa->bukti_pembayaran) }}" alt="Bukti Transfer" class="img-fluid rounded-3 border shadow-xs mb-3" style="max-height: 280px; object-fit: contain;">
                                                                @else
                                                                    <div class="alert alert-secondary text-muted small py-4 mb-3 rounded-3">
                                                                        <iconify-icon icon="solar:gallery-remove-bold-duotone" class="fs-1 d-block mx-auto mb-2 opacity-50"></iconify-icon>
                                                                        Siswa ini tidak melampirkan bukti pembayaran.
                                                                    </div>
                                                                @endif
                                                                
                                                                <p class="text-muted small mb-0">Apakah pembayaran sudah masuk dan Anda ingin menerima <strong>{{ $siswa->nama_lengkap }}</strong> sebagai siswa resmi?</p>
                                                            </div>
                                                            <div class="modal-footer border-top p-3.5 d-flex justify-content-between">
                                                                <button type="button" class="btn btn-outline-secondary rounded-3 font-semibold px-4 py-2" style="font-size: 12.5px;" data-bs-dismiss="modal">Tutup</button>
                                                                <form action="{{ route('siswa.terima', $siswa->id) }}" method="POST" class="m-0">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success rounded-3 font-semibold px-4 py-2 shadow-xs" style="font-size: 12.5px;">Terima &amp; Aktifkan Siswa</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            {{-- JIKA STATUS AKTIF --}}
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-success border-success-subtle bg-success-subtle text-success rounded-3 px-2.5 py-1 font-semibold" style="font-size: 11.5px;" data-bs-toggle="modal" data-bs-target="#modalTautkanOrtu{{ $siswa->id }}">
                                                    Link Ortu
                                                </button>

                                                <button type="button" class="btn btn-sm btn-primary rounded-3 px-2.5 py-1 font-semibold shadow-xs" style="font-size: 11.5px;" data-bs-toggle="modal" data-bs-target="#modalSetKelas{{ $siswa->id }}">
                                                    Set Kelas
                                                </button>

                                                {{-- Modal Tautkan Ortu --}}
                                                <div class="modal fade text-start" id="modalTautkanOrtu{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content rounded-4 border-0 shadow-lg">
                                                            <form action="{{ route('siswa.tautkan-ortu') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                                                <div class="modal-header bg-slate-50 border-bottom p-3.5">
                                                                    <h6 class="fw-bold mb-0 text-success d-flex align-items-center gap-2" style="font-size: 15px;">
                                                                        <iconify-icon icon="solar:link-bold-duotone" class="fs-5"></iconify-icon> Tautkan Akun Ortu
                                                                    </h6>
                                                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body p-4">
                                                                    <p class="text-muted small mb-3">Masukkan email akun Wali Murid yang sudah terdaftar di sistem untuk menautkan data <strong>{{ $siswa->nama_lengkap }}</strong> ke Dashboard mereka.</p>
                                                                    <div class="mb-2">
                                                                        <label class="form-label font-semibold text-dark small">Email Akun Wali Murid</label>
                                                                        <input type="email" name="email" class="form-control rounded-3" placeholder="contoh: ortu@tk.com" value="{{ $siswa->email }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-top p-3.5">
                                                                    <button type="submit" class="btn btn-success rounded-3 font-semibold px-4 py-2 shadow-xs" style="font-size: 12.5px;">Simpan Tautan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Modal Set Kelas --}}
                                                <div class="modal fade text-start" id="modalSetKelas{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content rounded-4 border-0 shadow-lg">
                                                            <form action="{{ route('siswa.set-kelas') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                                                <div class="modal-header bg-slate-50 border-bottom p-3.5">
                                                                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                                                                        <iconify-icon icon="solar:black-hole-bold-duotone" class="text-primary fs-5"></iconify-icon> Pilih Kelas untuk {{ $siswa->nama_lengkap }}
                                                                    </h6>
                                                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body p-4">
                                                                    <select name="kelas_id" class="form-select rounded-3 font-medium" required style="font-size: 13px;">
                                                                        <option value="" disabled selected>-- Pilih Kelas Tujuan --</option>
                                                                        @php $kelasAktifSiswa = $siswa->kelasAktif()->first(); @endphp
                                                                        @foreach($list_kelas as $k)
                                                                            <option value="{{ $k->id }}" {{ ($kelasAktifSiswa && $kelasAktifSiswa->id == $k->id) ? 'selected' : '' }}>
                                                                                {{ $k->nama_kelas }} (Tingkat: {{ $k->tingkat }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="modal-footer border-top p-3.5">
                                                                    <button type="submit" class="btn btn-primary rounded-3 font-semibold px-4 py-2 shadow-xs" style="font-size: 12.5px;">Simpan Penempatan</button>
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
                                <div class="py-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                                        <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-2"></iconify-icon>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada Data Siswa</h6>
                                    <p class="text-muted small mb-0">Tidak ada data siswa ditemukan untuk kriteria filter ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination Footer (TM-26, TM-38) --}}
            <div class="card-footer bg-white border-top border-light-subtle py-3 px-4">
                {{ $siswas->links() }}
            </div>
        </div>
    </div>
    @endif

    {{-- SUBTLE LOW-KEY FOOTER --}}
    <footer class="text-center py-2 mt-2">
        <span class="text-muted" style="font-size: 11px;">© 2026 TK Ceria Educational Management Platform • Internal Web System v1.0</span>
    </footer>

</div>

{{-- MODAL IMPORT CSV DAPODIK --}}
@if(in_array(auth()->user()->role, ['admin', 'super_admin']))
<div class="modal fade" id="modalImportCSV" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('siswa.import-nisn') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success-subtle border-bottom p-3.5">
                    <h6 class="modal-title fw-bold text-success d-flex align-items-center gap-2" style="font-size: 15px;">
                        <iconify-icon icon="solar:file-download-bold-duotone" class="fs-5"></iconify-icon> Import Data Dapodik (CSV)
                    </h6>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <div class="alert alert-warning border border-warning-subtle rounded-3 small mb-4 py-2 px-3">
                        <iconify-icon icon="solar:info-circle-bold-duotone" class="fs-5 align-middle me-1 text-warning"></iconify-icon>
                        Gunakan fitur ini untuk menarik data <strong>NISN</strong> dan <strong>No. Induk (NIPD)</strong> dari file Dapodik secara otomatis.
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label font-semibold text-dark small">Upload File Dapodik (.csv) <span class="text-danger">*</span></label>
                        <input type="file" name="file_csv" class="form-control rounded-3" accept=".csv" required>
                        <span class="text-muted d-block mt-2" style="font-size: 11px;">
                            * File wajib berformat <strong>.csv</strong> hasil export dari sistem Dapodik.
                        </span>
                    </div>
                </div>
                <div class="modal-footer border-top p-3.5">
                    <button type="button" class="btn btn-outline-secondary rounded-3 font-semibold px-4 py-2" data-bs-dismiss="modal" style="font-size: 12.5px;">Batal</button>
                    <button type="submit" class="btn btn-success rounded-3 font-semibold px-4 py-2 shadow-xs" style="font-size: 12.5px;">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection