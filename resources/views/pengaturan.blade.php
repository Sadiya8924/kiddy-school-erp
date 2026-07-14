@extends('layouts.admin')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    
    {{-- HEADER --}}
    <div class="col-12 mb-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Pengaturan Sekolah</h4>
                <p class="text-muted mb-0 small">Kelola akses fitur, status rapor, dan penugasan wali kelas.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="col-12 mt-0">
            <div class="alert alert-success border border-success-subtle shadow-none rounded py-2 px-3 mb-0 small fw-medium">{{ session('success') }}</div>
        </div>
    @endif

    {{-- 1. PENGATURAN PENDAFTARAN & KUOTA (Form Baru!) --}}
    <div class="col-12">
        <div class="card border shadow-none mb-0">
            <div class="card-header bg-light border-bottom p-3">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:settings-minimalistic-bold-duotone" class="text-primary fs-5"></iconify-icon> Pengaturan Pendaftaran & Kuota
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('pengaturan.update-pendaftaran') }}" method="POST">
                    @csrf
                    <input type="hidden" name="school_id" value="{{ $school->id }}">
                    <div class="row gx-3">
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold text-dark small">Kuota TK Pagi</label>
                            <input type="number" name="kuota_tk_pagi" class="form-control shadow-none" value="{{ $school->kuota_tk_pagi }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold text-dark small">Kuota TK Siang</label>
                            <input type="number" name="kuota_tk_siang" class="form-control shadow-none" value="{{ $school->kuota_tk_siang }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold text-dark small">Kuota PAUD</label>
                            <input type="number" name="kuota_paud" class="form-control shadow-none" value="{{ $school->kuota_paud }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold text-dark small">Buka Inden (Berapa Tahun)</label>
                            <select name="max_indent_years" class="form-select shadow-none fw-medium" required>
                                @for($i=1; $i<=5; $i++)
                                    <option value="{{ $i }}" {{ ($school->max_indent_years ?? 3) == $i ? 'selected' : '' }}>{{ $i }} Tahun ke Depan</option>
                                @endfor
                            </select>
                            <div class="form-text text-muted" style="font-size: 11px;">Maksimal tahun ajaran di form daftar.</div>
                        </div>
                    </div>
                    <div class="text-end mt-2">
                        <button type="submit" class="btn btn-primary btn-sm rounded fw-bold px-4 shadow-none">Simpan Pengaturan Pendaftaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 2. PENUGASAN WALI KELAS --}}
    <div class="col-12">
        <div class="card border shadow-none mb-0">
            <div class="card-header bg-light border-bottom p-3">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:user-id-bold-duotone" class="text-primary fs-5"></iconify-icon> Penugasan Wali Kelas (Guru)
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 border-0">
                        <thead class="bg-white border-bottom border-light-subtle">
                            <tr>
                                <th class="text-muted fw-semibold py-3 ps-4" style="font-size: 11px;">NAMA GURU</th>
                                <th class="text-muted fw-semibold py-3" style="font-size: 11px;">EMAIL</th>
                                <th class="text-muted fw-semibold py-3" style="font-size: 11px;">TUGAS DI KELAS</th>
                                <th class="text-center text-muted fw-semibold py-3 pe-4" style="width: 15%; font-size: 11px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gurus as $guru)
                            <tr class="border-bottom border-light-subtle">
                                <td class="py-3 ps-4 fw-bold text-dark" style="font-size: 14px;">{{ $guru->name }}</td>
                                <td class="py-3 text-muted" style="font-size: 13px;">{{ $guru->email }}</td>
                                <td class="py-3">
                                    @if($guru->kelas_id)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded px-3 py-1" style="font-size: 11px; font-weight: 600;">
                                            {{ $guru->kelas->nama_kelas ?? 'Data Kelas Hilang' }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded px-3 py-1" style="font-size: 11px; font-weight: 600;">
                                            Belum Ditugaskan
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center py-3 pe-4">
                                    <button class="btn btn-sm btn-primary rounded shadow-none px-3 fw-bold" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#modalPlot{{ $guru->id }}">
                                        Atur Kelas
                                    </button>

                                    {{-- Modal Atur Kelas Guru --}}
                                    <div class="modal fade text-start" id="modalPlot{{ $guru->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded border shadow-sm">
                                                <form action="{{ route('pengaturan.update-guru') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $guru->id }}">
                                                    <div class="modal-header bg-light border-bottom p-3">
                                                        <h6 class="fw-bold mb-0 text-dark">Tugaskan: {{ $guru->name }}</h6>
                                                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Pilih Kelas yang Diampu:</label>
                                                        <select name="kelas_id" class="form-select border-primary-subtle shadow-none bg-light fw-medium" style="font-size: 13px;">
                                                            <option value="">-- Bebas Tugas (Tidak Pegang Kelas) --</option>
                                                            @foreach($list_kelas as $kls)
                                                                <option value="{{ $kls->id }}" {{ $guru->kelas_id == $kls->id ? 'selected' : '' }}>
                                                                    {{ $kls->nama_kelas }} (Tingkat: {{ $kls->tingkat }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-muted d-block mt-3" style="font-size: 11px;">* Guru hanya dapat mengisi presensi dan rapor pada kelas yang dipilih.</small>
                                                    </div>
                                                    <div class="modal-footer border-top p-3">
                                                        <button type="submit" class="btn btn-primary rounded fw-bold px-4 shadow-none w-100" style="font-size: 12px;">Simpan Penugasan</button>
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
    </div>

    {{-- 3. TAHUN AJARAN & KONTROL RAPOR --}}
    <div class="col-lg-7">
        <div class="card border shadow-none mb-0 h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4 border-bottom pb-2">Manajemen Tahun Ajaran & Semester</h6>
                <div class="row gx-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="p-3 rounded border border-light-subtle bg-light h-100">
                            <h6 class="fw-bold text-dark mb-2" style="font-size: 13px;">Ubah Semester Aktif</h6>
                            <form action="{{ route('pengaturan.semester.aktifkan') }}" method="POST" class="d-flex flex-column gap-2">
                                @csrf
                                <select name="semester_id" class="form-select shadow-none border-light-subtle" style="font-size: 12px;" required>
                                    @foreach($semesters as $sem)
                                        <option value="{{ $sem->id }}" {{ $sem->is_active ? 'selected' : '' }}>
                                            {{ $sem->tahun_ajaran }} - {{ $sem->jenis }} {{ $sem->is_active ? '(Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm rounded fw-bold shadow-none">Aktifkan</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded border border-light-subtle h-100">
                            <h6 class="fw-bold text-dark mb-2" style="font-size: 13px;">Buat Semester Baru</h6>
                            <form action="{{ route('pengaturan.semester.store') }}" method="POST" class="row gx-2 gy-2">
                                @csrf
                                <div class="col-7">
                                    <input type="text" name="tahun_ajaran" class="form-control shadow-none border-light-subtle" style="font-size: 12px;" placeholder="Cth: 2026/2027" required>
                                </div>
                                <div class="col-5">
                                    <select name="jenis" class="form-select shadow-none border-light-subtle" style="font-size: 12px;" required>
                                        <option value="Ganjil">Ganjil</option>
                                        <option value="Genap">Genap</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-outline-success border-success-subtle bg-success-subtle text-success btn-sm w-100 fw-bold shadow-none rounded">Tambah Baru</button>
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
        <div class="card border shadow-none h-100 mb-0">
            <div class="card-body p-4 d-flex flex-column justify-content-center">
                <h6 class="fw-bold mb-3">Kontrol Akses Rapor</h6>
                <div class="d-flex align-items-center justify-content-between p-3 rounded border {{ $school->is_rapor_open ? 'bg-success-subtle border-success-subtle' : 'bg-danger-subtle border-danger-subtle' }}">
                    <div>
                        <span class="d-block fw-bold {{ $school->is_rapor_open ? 'text-success' : 'text-danger' }}" style="font-size: 14px;">
                            Status: {{ $school->is_rapor_open ? 'TERBUKA' : 'DITUTUP' }}
                        </span>
                        <small class="{{ $school->is_rapor_open ? 'text-success' : 'text-danger' }} opacity-75" style="font-size: 11px;">Guru {{ $school->is_rapor_open ? 'bisa' : 'tidak bisa' }} mengisi rapor.</small>
                    </div>
                    
                    @if($school->is_rapor_open && isset($belumPermanen) && $belumPermanen > 0)
                        <button type="button" class="btn btn-danger btn-sm rounded px-3 fw-bold shadow-none" data-bs-toggle="modal" data-bs-target="#modalConfirmTutup">
                            Tutup Akses
                        </button>

                        <div class="modal fade" id="modalConfirmTutup" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded border shadow-sm">
                                    <div class="modal-header bg-danger-subtle border-bottom border-danger-subtle p-3">
                                        <h6 class="fw-bold text-danger mb-0"><iconify-icon icon="solar:danger-triangle-bold" class="align-middle me-1"></iconify-icon> Peringatan Penutupan!</h6>
                                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="alert alert-danger border-0 rounded small mb-3">
                                            Ada <strong>{{ $belumPermanen }} Siswa</strong> di semester aktif yang rapornya belum diisi atau masih berstatus Draft.
                                        </div>
                                        <p class="mb-0 text-muted" style="font-size: 12px;">Menutup akses sekarang akan otomatis mengunci semua rapor draft menjadi permanen.</p>
                                    </div>
                                    <div class="modal-footer border-top p-3">
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded fw-medium px-4" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('rapor.toggle') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm rounded fw-bold px-4">Ya, Tutup & Kunci</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('rapor.toggle') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn {{ $school->is_rapor_open ? 'btn-danger' : 'btn-success' }} btn-sm rounded px-3 fw-bold shadow-none">
                                {{ $school->is_rapor_open ? 'Tutup Akses' : 'Buka Akses' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 4. HAK AKSES MENU --}}
    <div class="col-12">
        <div class="card border shadow-none mb-0">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Manajemen Hak Akses Menu</h6>
                
                <ul class="nav nav-pills nav-fill bg-light p-1 rounded mb-3" id="menuTabs" role="tablist">
                    @if(auth()->user()->isSuperAdmin())
                        <li class="nav-item" role="presentation"><button class="nav-link active rounded fw-medium small py-2" data-bs-toggle="tab" data-bs-target="#tab-admin">Admin</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link rounded fw-medium small py-2" data-bs-toggle="tab" data-bs-target="#tab-kepsek">Kepsek</button></li>
                    @endif
                    <li class="nav-item" role="presentation"><button class="nav-link {{ !auth()->user()->isSuperAdmin() ? 'active' : '' }} rounded fw-medium small py-2" data-bs-toggle="tab" data-bs-target="#tab-guru">Guru</button></li>
                    @if(auth()->user()->isSuperAdmin())
                        <li class="nav-item" role="presentation"><button class="nav-link rounded fw-medium small py-2" data-bs-toggle="tab" data-bs-target="#tab-ortu">Ortu</button></li>
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
                                <div class="table-responsive border rounded bg-white" style="max-height: 250px; overflow-y: auto;">
                                    <table class="table table-sm table-hover align-middle mb-0 border-0">
                                        <thead class="bg-light sticky-top">
                                            <tr>
                                                <th class="ps-4 py-2 text-muted fw-semibold" style="font-size: 11px;">MODUL / MENU</th>
                                                <th class="text-center py-2 text-muted fw-semibold" style="font-size: 11px;">STATUS AKSES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($school->modules as $modul)
                                                @if(str_contains($modul->role_access, $roleKey))
                                                <tr class="border-bottom border-light-subtle">
                                                    <td class="ps-4 py-2 fw-medium text-dark" style="font-size: 13px;">
                                                        <iconify-icon icon="{{ $modul->icon }}" class="me-2 text-primary fs-5 align-middle"></iconify-icon>{{ $modul->name }}
                                                    </td>
                                                    <td class="text-center py-2">
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
                                    <button type="submit" class="btn btn-primary rounded fw-bold px-4 shadow-none" style="font-size: 12px;">Simpan Akses {{ $roleName }}</button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection