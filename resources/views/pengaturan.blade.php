@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

    @if(session('success'))
        <div class="alert alert-success border border-success-subtle shadow-none rounded-3 py-2 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
            <div><i class="ti ti-check me-2"></i>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ========================================================== --}}
    {{-- 1. HERO WELCOME CARD WITH SOFT BLUE GRADIENT --}}
    {{-- ========================================================== --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%); border-radius: 16px !important;">
        <div class="card-body p-4 text-white">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                
                {{-- User Greeting & Context --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid rgba(255,255,255,0.4);">
                            <iconify-icon icon="solar:settings-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Pengaturan Sekolah</h5>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Kelola akses fitur, status rapor, dan penugasan wali kelas.</span>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('partials.owner_stepped_filter')

    @if((!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id')) && $school)
    
    {{-- 1. PENGATURAN PENDAFTARAN & KUOTA --}}
    <div class="card border shadow-sm" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
        <div class="card-header bg-white border-bottom p-4">
            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                <iconify-icon icon="solar:settings-minimalistic-bold-duotone" class="text-primary fs-5"></iconify-icon> Pengaturan Pendaftaran &amp; Kuota
            </h6>
            <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Batas kuota siswa per kelompok dan batas maksimal inden tahun ajaran</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('pengaturan.update-pendaftaran') }}" method="POST">
                @csrf
                <input type="hidden" name="school_id" value="{{ $school->id }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label font-semibold text-dark small">Kuota TK Pagi</label>
                        <input type="number" name="kuota_tk_pagi" class="form-control rounded-3" value="{{ $school->kuota_tk_pagi }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-semibold text-dark small">Kuota TK Siang</label>
                        <input type="number" name="kuota_tk_siang" class="form-control rounded-3" value="{{ $school->kuota_tk_siang }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-semibold text-dark small">Kuota PAUD</label>
                        <input type="number" name="kuota_paud" class="form-control rounded-3" value="{{ $school->kuota_paud }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-semibold text-dark small">Buka Inden (Berapa Tahun)</label>
                        <select name="max_indent_years" class="form-select rounded-3 font-medium" required>
                            @for($i=1; $i<=5; $i++)
                                <option value="{{ $i }}" {{ ($school->max_indent_years ?? 3) == $i ? 'selected' : '' }}>{{ $i }} Tahun ke Depan</option>
                            @endfor
                        </select>
                        <span class="text-muted d-block mt-1" style="font-size: 11px;">Maksimal tahun ajaran di form daftar.</span>
                    </div>
                </div>
                <div class="text-end mt-3 pt-2 border-top">
                    <button type="submit" class="btn btn-primary rounded-3 font-semibold px-4 py-2 shadow-xs d-inline-flex align-items-center gap-1.5" style="font-size: 12.5px;">
                        <iconify-icon icon="solar:diskette-bold-duotone" class="fs-5"></iconify-icon> Simpan Pengaturan Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. PENUGASAN WALI KELAS --}}
    <div class="card border shadow-sm" style="border-top: 3.5px solid #10b981 !important; border-radius: 16px !important;">
        <div class="card-header bg-white border-bottom p-4">
            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                <iconify-icon icon="solar:user-id-bold-duotone" class="text-success fs-5"></iconify-icon> Penugasan Wali Kelas (Guru)
            </h6>
            <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Plotting guru untuk mengampu kelas rombel aktif</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr class="border-bottom border-light-subtle">
                            <th class="ps-4 pb-2 text-muted font-semibold" style="font-size: 11px;">NAMA GURU</th>
                            <th class="pb-2 text-muted font-semibold" style="font-size: 11px;">EMAIL</th>
                            <th class="pb-2 text-muted font-semibold" style="font-size: 11px;">TUGAS DI KELAS</th>
                            <th class="text-center pe-4 pb-2 text-muted font-semibold" style="width: 15%; font-size: 11px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gurus as $guru)
                        <tr class="border-bottom border-light-subtle">
                            <td class="py-3 ps-4 fw-bold text-dark" style="font-size: 14px;">{{ $guru->name }}</td>
                            <td class="py-3 text-muted font-medium" style="font-size: 13px;">{{ $guru->email }}</td>
                            <td class="py-3">
                                @if($guru->kelas_id)
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 font-semibold" style="font-size: 11px;">
                                        {{ $guru->kelas->nama_kelas ?? 'Data Kelas Hilang' }}
                                    </span>
                                @else
                                    <span class="badge bg-slate-100 text-muted rounded-pill px-3 py-1 font-semibold" style="font-size: 11px;">
                                        Belum Ditugaskan
                                    </span>
                                @endif
                            </td>
                            <td class="text-center py-3 pe-4">
                                <button class="btn btn-sm btn-primary rounded-3 px-3 py-1 font-semibold d-inline-flex align-items-center gap-1 shadow-xs" style="font-size: 11.5px;" data-bs-toggle="modal" data-bs-target="#modalPlot{{ $guru->id }}">
                                    <iconify-icon icon="solar:pen-bold-duotone" class="fs-5"></iconify-icon> Atur Kelas
                                </button>

                                {{-- Modal Atur Kelas Guru --}}
                                <div class="modal fade text-start" id="modalPlot{{ $guru->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow-lg">
                                            <form action="{{ route('pengaturan.update-guru') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $guru->id }}">
                                                <div class="modal-header bg-slate-50 border-bottom p-3.5">
                                                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                                                        <iconify-icon icon="solar:user-bold-duotone" class="text-primary fs-5"></iconify-icon> Tugaskan: {{ $guru->name }}
                                                    </h6>
                                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <label class="form-label font-semibold text-dark small">Pilih Kelas yang Diampu:</label>
                                                    <select name="kelas_id" class="form-select rounded-3 font-medium" style="font-size: 13px;">
                                                        <option value="">-- Bebas Tugas (Tidak Pegang Kelas) --</option>
                                                        @foreach($list_kelas as $kls)
                                                            <option value="{{ $kls->id }}" {{ $guru->kelas_id == $kls->id ? 'selected' : '' }}>
                                                                {{ $kls->nama_kelas }} (Tingkat: {{ $kls->tingkat }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-muted d-block mt-2.5" style="font-size: 11px;">* Guru hanya dapat mengisi presensi dan rapor pada kelas yang dipilih.</span>
                                                </div>
                                                <div class="modal-footer border-top p-3.5">
                                                    <button type="submit" class="btn btn-primary rounded-3 font-semibold px-4 py-2 w-100 shadow-xs" style="font-size: 12.5px;">Simpan Penugasan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted small">Belum ada user dengan role Guru terdaftar di sekolah ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 3. TAHUN AJARAN & KONTROL RAPOR --}}
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #8b5cf6 !important; border-radius: 16px !important;">
                <div class="card-header bg-white border-bottom p-4">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                        <iconify-icon icon="solar:calendar-bold-duotone" class="text-purple fs-5" style="color: #8b5cf6;"></iconify-icon> Manajemen Tahun Ajaran &amp; Semester
                    </h6>
                    <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Pengaturan periode aktif pembelajaran dan penambahan semester</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3.5 rounded-3 border bg-slate-50 h-100" style="border-radius: 12px !important;">
                                <h6 class="fw-bold text-dark mb-2" style="font-size: 13px;">Ubah Semester Aktif</h6>
                                <form action="{{ route('pengaturan.semester.aktifkan') }}" method="POST" class="d-flex flex-column gap-2">
                                    @csrf
                                    <select name="semester_id" class="form-select rounded-3 font-medium" style="font-size: 12px;" required>
                                        @foreach($semesters as $sem)
                                            <option value="{{ $sem->id }}" {{ $sem->is_active ? 'selected' : '' }}>
                                                {{ $sem->tahun_ajaran }} - {{ $sem->jenis }} {{ $sem->is_active ? '(Aktif)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm rounded-3 font-semibold shadow-xs py-1.5" style="font-size: 12px;">Aktifkan</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3.5 rounded-3 border bg-white h-100" style="border-radius: 12px !important;">
                                <h6 class="fw-bold text-dark mb-2" style="font-size: 13px;">Buat Semester Baru</h6>
                                <form action="{{ route('pengaturan.semester.store') }}" method="POST" class="row g-2">
                                    @csrf
                                    <div class="col-7">
                                        <input type="text" name="tahun_ajaran" class="form-control rounded-3" style="font-size: 12px;" placeholder="Cth: 2026/2027" required>
                                    </div>
                                    <div class="col-5">
                                        <select name="jenis" class="form-select rounded-3" style="font-size: 12px;" required>
                                            <option value="Ganjil">Ganjil</option>
                                            <option value="Genap">Genap</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-outline-success border-success-subtle bg-success-subtle text-success btn-sm w-100 font-semibold rounded-3 py-1.5" style="font-size: 12px;">+ Tambah Baru</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KONTROL AKSES RAPOR --}}
        <div class="col-lg-5">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #f59e0b !important; border-radius: 16px !important;">
                <div class="card-header bg-white border-bottom p-4">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                        <iconify-icon icon="solar:lock-keyhole-minimalistic-bold-duotone" class="text-warning fs-5"></iconify-icon> Kontrol Akses Rapor
                    </h6>
                    <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Buka atau kunci pengisian nilai rapor semester aktif</span>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center justify-content-between p-3.5 rounded-3 border {{ $school->is_rapor_open ? 'bg-success-subtle border-success-subtle' : 'bg-danger-subtle border-danger-subtle' }}">
                        <div>
                            <span class="d-block fw-bold {{ $school->is_rapor_open ? 'text-success' : 'text-danger' }}" style="font-size: 14.5px;">
                                Status: {{ $school->is_rapor_open ? 'TERBUKA' : 'DITUTUP' }}
                            </span>
                            <span class="{{ $school->is_rapor_open ? 'text-success' : 'text-danger' }} opacity-75 d-block mt-0.5" style="font-size: 11.5px;">Guru {{ $school->is_rapor_open ? 'bisa' : 'tidak bisa' }} mengisi rapor.</span>
                        </div>
                        
                        @if($school->is_rapor_open && isset($belumPermanen) && $belumPermanen > 0)
                            <button type="button" class="btn btn-danger btn-sm rounded-3 px-3 py-1.5 font-semibold shadow-xs" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#modalConfirmTutup">
                                Tutup Akses
                            </button>

                            <div class="modal fade text-start" id="modalConfirmTutup" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow-lg">
                                        <div class="modal-header bg-danger-subtle border-bottom border-danger-subtle p-3.5">
                                            <h6 class="fw-bold text-danger mb-0 d-flex align-items-center gap-2" style="font-size: 15px;">
                                                <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-5"></iconify-icon> Peringatan Penutupan!
                                            </h6>
                                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="alert alert-danger border-0 rounded-3 small mb-3">
                                                Ada <strong>{{ $belumPermanen }} Siswa</strong> di semester aktif yang rapornya belum diisi atau masih berstatus Draft.
                                            </div>
                                            <p class="mb-0 text-muted" style="font-size: 12px;">Menutup akses sekarang akan otomatis mengunci semua rapor draft menjadi permanen.</p>
                                        </div>
                                        <div class="modal-footer border-top p-3.5">
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 font-semibold px-4 py-2" style="font-size: 12px;" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('rapor.toggle') }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm rounded-3 font-semibold px-4 py-2 shadow-xs" style="font-size: 12px;">Ya, Tutup &amp; Kunci</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('rapor.toggle') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn {{ $school->is_rapor_open ? 'btn-danger' : 'btn-success' }} btn-sm rounded-3 px-3 py-1.5 font-semibold shadow-xs" style="font-size: 12px;">
                                    {{ $school->is_rapor_open ? 'Tutup Akses' : 'Buka Akses' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. HAK AKSES MENU --}}
    <div class="card border shadow-sm" style="border-top: 3.5px solid #06b6d4 !important; border-radius: 16px !important;">
        <div class="card-header bg-white border-bottom p-4">
            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                <iconify-icon icon="solar:shield-keyhole-bold-duotone" class="text-info fs-5"></iconify-icon> Manajemen Hak Akses Menu
            </h6>
            <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">Atur visibilitas dan hak akses modul sistem per masing-masing role pengguna</span>
        </div>
        <div class="card-body p-4">
            
            <ul class="nav nav-pills nav-fill bg-slate-50 p-1.5 rounded-3 mb-3.5 border border-slate-100" id="menuTabs" role="tablist">
                @if(auth()->user()->isSuperAdmin())
                    <li class="nav-item" role="presentation"><button class="nav-link active rounded-3 font-semibold small py-2" data-bs-toggle="tab" data-bs-target="#tab-admin" style="font-size: 12.5px;">Admin</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link rounded-3 font-semibold small py-2" data-bs-toggle="tab" data-bs-target="#tab-kepsek" style="font-size: 12.5px;">Kepsek</button></li>
                @endif
                <li class="nav-item" role="presentation"><button class="nav-link {{ !auth()->user()->isSuperAdmin() ? 'active' : '' }} rounded-3 font-semibold small py-2" data-bs-toggle="tab" data-bs-target="#tab-guru" style="font-size: 12.5px;">Guru</button></li>
                @if(auth()->user()->isSuperAdmin())
                    <li class="nav-item" role="presentation"><button class="nav-link rounded-3 font-semibold small py-2" data-bs-toggle="tab" data-bs-target="#tab-ortu" style="font-size: 12.5px;">Ortu</button></li>
                @endif
            </ul>

            <div class="tab-content">
                @php $tabs = auth()->user()->isSuperAdmin() ? ['admin' => 'Admin', 'kepsek' => 'Kepala Sekolah', 'guru' => 'Guru', 'wali_murid' => 'Wali Murid'] : ['guru' => 'Guru']; @endphp
                @foreach($tabs as $roleKey => $roleName)
                    @php 
                        $isActiveTab = (auth()->user()->isSuperAdmin() && $roleKey == 'admin') || (!auth()->user()->isSuperAdmin() && $roleKey == 'guru');
                        $tabId = $roleKey == 'wali_murid' ? 'ortu' : $roleKey;
                        $dbColumn = 'is_active_for_' . $roleKey;
                    @endphp
                    
                    <div class="tab-pane fade {{ $isActiveTab ? 'show active' : '' }}" id="tab-{{ $tabId }}">
                        <form action="{{ route('pengaturan.update-menu-access') }}" method="POST">
                            @csrf
                            <input type="hidden" name="target_role" value="{{ $roleKey }}">
                            <div class="table-responsive border rounded-3 bg-white" style="max-height: 280px; overflow-y: auto;">
                                <table class="table table-borderless table-hover align-middle mb-0">
                                    <thead class="bg-slate-50 sticky-top border-bottom">
                                        <tr>
                                            <th class="ps-4 py-2.5 text-muted font-semibold" style="font-size: 11px;">MODUL / MENU</th>
                                            <th class="text-center py-2.5 text-muted font-semibold" style="font-size: 11px;">STATUS AKSES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($school->modules as $modul)
                                            @if(str_contains($modul->role_access, $roleKey))
                                            <tr class="border-bottom border-light-subtle">
                                                <td class="ps-4 py-2.5 fw-semibold text-dark" style="font-size: 13px;">
                                                    <iconify-icon icon="{{ $modul->icon }}" class="me-2 text-primary fs-5 align-middle"></iconify-icon>{{ $modul->name }}
                                                </td>
                                                <td class="text-center py-2.5">
                                                    <div class="form-check form-switch d-inline-block m-0">
                                                        <input class="form-check-input shadow-none cursor-pointer border-primary" type="checkbox" name="{{ $dbColumn }}[{{ $modul->id }}]" {{ $modul->pivot->$dbColumn ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary rounded-3 font-semibold px-4 py-2 shadow-xs d-inline-flex align-items-center gap-1.5" style="font-size: 12.5px;">
                                    <iconify-icon icon="solar:diskette-bold-duotone" class="fs-5"></iconify-icon> Simpan Akses {{ $roleName }}
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @endif

</div>
@endsection