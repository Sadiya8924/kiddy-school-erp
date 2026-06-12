@extends('layouts.admin')

@section('konten_halaman')
<div class="row">
    <div class="col-12 mb-4">
        <h2 class="fw-semibold text-dark mb-1">Pengaturan Sekolah</h2>
        <span class="text-muted fs-3">Kelola akses fitur, rapor, dan tahun ajaran aktif.</span>
    </div>

    @if(session('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    {{-- 0. MANAJEMEN TAHUN AJARAN & SEMESTER --}}
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Manajemen Tahun Ajaran & Semester</h5>
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="bg-light p-3 rounded-3 h-100">
                            <h6 class="fw-semibold mb-3">Ubah Semester Aktif</h6>
                            <form action="{{ route('pengaturan.semester.aktifkan') }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <select name="semester_id" class="form-select border-1 shadow-none" required>
                                    @foreach($semesters as $sem)
                                        <option value="{{ $sem->id }}" {{ $sem->is_active ? 'selected' : '' }}>
                                            {{ $sem->tahun_ajaran }} - {{ $sem->jenis }} {{ $sem->is_active ? '(Aktif Saat Ini)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary px-4 fw-medium shadow-none">Aktifkan</button>
                            </form>
                            <small class="text-danger d-block mt-2 fw-medium"><iconify-icon icon="solar:danger-circle-bold" class="align-middle"></iconify-icon> Perhatian: Mengubah semester akan otomatis me-reset tampilan data absensi, nilai rapor, dan isi kelas guru.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border border-light p-3 rounded-3 h-100">
                            <h6 class="fw-semibold mb-3">Buat Semester Baru</h6>
                            <form action="{{ route('pengaturan.semester.store') }}" method="POST" class="row g-2">
                                @csrf
                                <div class="col-7">
                                    <input type="text" name="tahun_ajaran" class="form-control shadow-none" placeholder="Cth: 2026/2027" required>
                                </div>
                                <div class="col-5">
                                    <select name="jenis" class="form-select shadow-none" required>
                                        <option value="Ganjil">Ganjil</option>
                                        <option value="Genap">Genap</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-outline-success w-100 fw-medium shadow-none">Tambah Semester Baru</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 1. KONTROL AKSES RAPOR DENGAN ALERT --}}
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Kontrol Akses Rapor</h5>
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 {{ $school->is_rapor_open ? 'bg-primary-subtle' : 'bg-danger-subtle' }}">
                    <div>
                        <span class="d-block fw-bold {{ $school->is_rapor_open ? 'text-primary' : 'text-danger' }}">
                            Status: {{ $school->is_rapor_open ? 'DIBUKA' : 'DITUTUP' }}
                        </span>
                        <small class="text-muted">Guru {{ $school->is_rapor_open ? 'bisa' : 'tidak bisa' }} mengisi rapor.</small>
                    </div>
                    
                    {{-- LOGIKA TOMBOL TUTUP DENGAN PENGECEKAN DATA --}}
                    @if($school->is_rapor_open && isset($belumPermanen) && $belumPermanen > 0)
                        <button type="button" class="btn btn-danger rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalConfirmTutup">
                            Tutup Akses
                        </button>

                        <div class="modal fade" id="modalConfirmTutup" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow">
                                    <div class="modal-header border-0 p-4 pb-0">
                                        <h5 class="fw-bold text-danger mb-0"><iconify-icon icon="solar:danger-triangle-bold" class="align-middle fs-4 me-1"></iconify-icon> Peringatan Penutupan Rapor!</h5>
                                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4 text-dark">
                                        <div class="alert alert-danger border-0 rounded-3 mb-3">
                                            Terdapat <strong>{{ $belumPermanen }} Siswa</strong> di semester aktif ini yang rapornya <strong>belum diisi</strong> atau masih <strong>Simpan Sementara (Draft)</strong>.
                                        </div>
                                        <p class="mb-0 small text-muted">
                                            Jika Anda menutup akses sekarang, semua rapor yang masih berstatus draft akan otomatis <strong>dikunci menjadi permanen</strong>, dan guru tidak bisa lagi mengubah nilainya. Apakah Anda yakin ingin melanjutkan?
                                        </p>
                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill fw-medium px-4 shadow-none" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('rapor.toggle') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-danger rounded-pill fw-bold px-4 shadow-sm">Ya, Tetap Tutup & Kunci</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('rapor.toggle') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn {{ $school->is_rapor_open ? 'btn-danger' : 'btn-primary' }} rounded-pill px-4 shadow-sm">
                                {{ $school->is_rapor_open ? 'Tutup Akses' : 'Buka Akses' }}
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- 2. MANAJEMEN AKSES MENU (MULTI ROLE) --}}
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Manajemen Hak Akses Menu</h5>
                <p class="text-muted small mb-3">Tentukan menu apa saja yang boleh muncul di masing-masing Role.</p>
                
                {{-- TAB NAVIGASI --}}
                <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-3" id="menuTabs" role="tablist">
                    @if(auth()->user()->isSuperAdmin())
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill fw-medium small py-2" data-bs-toggle="tab" data-bs-target="#tab-admin" type="button">Admin</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-medium small py-2" data-bs-toggle="tab" data-bs-target="#tab-kepsek" type="button">Kepsek</button>
                        </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ !auth()->user()->isSuperAdmin() ? 'active' : '' }} rounded-pill fw-medium small py-2" data-bs-toggle="tab" data-bs-target="#tab-guru" type="button">Guru</button>
                    </li>
                    @if(auth()->user()->isSuperAdmin())
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-medium small py-2" data-bs-toggle="tab" data-bs-target="#tab-ortu" type="button">Ortu</button>
                        </li>
                    @endif
                </ul>

                {{-- ISI TAB CONTEN --}}
                <div class="tab-content" id="menuTabsContent">
                    
                    @php 
                        // Daftar tab yang akan di render, Super Admin lihat semua, Admin biasa cuma lihat 'guru'
                        $tabs = auth()->user()->isSuperAdmin() 
                                ? ['admin' => 'Admin', 'kepsek' => 'Kepala Sekolah', 'guru' => 'Guru', 'wali_murid' => 'Wali Murid'] 
                                : ['guru' => 'Guru']; 
                    @endphp

                    @foreach($tabs as $roleKey => $roleName)
                        @php 
                            // Untuk nentuin tab mana yang kebuka duluan
                            $isActiveTab = (auth()->user()->isSuperAdmin() && $roleKey == 'admin') || (!auth()->user()->isSuperAdmin() && $roleKey == 'guru');
                            $tabId = $roleKey == 'wali_murid' ? 'ortu' : $roleKey;
                            $dbColumn = 'is_active_for_' . $roleKey;
                        @endphp
                        
                        <div class="tab-pane fade {{ $isActiveTab ? 'show active' : '' }}" id="tab-{{ $tabId }}" role="tabpanel">
                            {{-- Setiap Tab punya Form masing-masing biar nggak bentrok pas disave --}}
                            <form action="{{ route('pengaturan.update-menu-access') }}" method="POST">
                                @csrf
                                <input type="hidden" name="target_role" value="{{ $roleKey }}">
                                
                                <div class="table-responsive border rounded-3" style="max-height: 220px; overflow-y: auto;">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="ps-3 py-2 text-muted small">Modul / Menu ({{ $roleName }})</th>
                                                <th class="text-center py-2 text-muted small">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($school->modules as $modul)
                                                {{-- Filter: Tampilkan hanya modul yang desainnya emang diperuntukkan untuk role ini --}}
                                                @if(str_contains($modul->role_access, $roleKey))
                                                <tr>
                                                    <td class="small ps-3 py-2 fw-medium">
                                                        <iconify-icon icon="{{ $modul->icon }}" class="me-2 text-primary"></iconify-icon>
                                                        {{ $modul->name }}
                                                    </td>
                                                    <td class="text-center py-2">
                                                        <div class="form-check form-switch d-inline-block m-0">
                                                            <input class="form-check-input shadow-none cursor-pointer" type="checkbox" name="{{ $dbColumn }}[{{ $modul->id }}]" 
                                                                   {{ $modul->pivot->$dbColumn ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <button type="submit" class="btn btn-outline-primary w-100 rounded-pill mt-3 fw-bold btn-sm shadow-none">
                                    Simpan Akses {{ $roleName }}
                                </button>
                            </form>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    {{-- 3. PENUGASAN WALI KELAS --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Penugasan Wali Kelas (Guru)</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Guru</th>
                                <th>Email</th>
                                <th>Tugas Di Kelas</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gurus as $guru)
                            <tr>
                                <td class="fw-bold text-dark">{{ $guru->name }}</td>
                                <td>{{ $guru->email }}</td>
                                <td>
                                    <span class="badge {{ $guru->kelas_id ? 'bg-success' : 'bg-secondary' }} px-3 py-2 rounded-pill">
                                        {{ $guru->kelas->nama_kelas ?? 'Belum Ditugaskan' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary rounded-pill px-4 shadow-none" data-bs-toggle="modal" data-bs-target="#modalPlot{{ $guru->id }}">
                                        Ganti Kelas
                                    </button>

                                    <div class="modal fade" id="modalPlot{{ $guru->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow rounded-4 text-start">
                                                <form action="{{ route('pengaturan.update-guru') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $guru->id }}">
                                                    <div class="modal-header border-0 p-4 pb-0">
                                                        <h5 class="fw-bold">Tugaskan {{ $guru->name }}</h5>
                                                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <label class="form-label fw-medium">Pilih Kelas yang Diampu:</label>
                                                        <select name="kelas_id" class="form-select border-primary shadow-none">
                                                            <option value="">-- Bebas Tugas --</option>
                                                            @foreach($list_kelas as $kls)
                                                                <option value="{{ $kls->id }}" {{ $guru->kelas_id == $kls->id ? 'selected' : '' }}>
                                                                    {{ $kls->nama_kelas }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-muted d-block mt-2">Guru ini hanya akan dapat mengakses data dan absensi pada kelas yang dipilih.</small>
                                                    </div>
                                                    <div class="modal-footer border-0 p-4 pt-0">
                                                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-none">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection