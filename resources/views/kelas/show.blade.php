@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

    @if(session('success'))
        <div class="alert alert-success border border-success-subtle shadow-none rounded-3 py-2 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
            <div><i class="ti ti-check me-2"></i>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border border-danger-subtle shadow-none rounded-3 py-2 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
            <div><i class="ti ti-alert-circle me-2"></i>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ========================================================== --}}
    {{-- 1. HERO WELCOME CARD WITH DYNAMIC THEME GRADIENT --}}
    {{-- ========================================================== --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="background: var(--theme-hero-gradient, linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%)) !important; border-radius: 16px !important;">
        <div class="card-body p-4 text-white">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                
                {{-- Context & Title --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid rgba(255,255,255,0.4);">
                            <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">
                                Kelas: {{ $kelas->nama_kelas }}
                            </h5>
                            <span class="badge bg-white text-dark bg-opacity-25 px-2.5 py-1 rounded-pill small font-semibold" style="font-size: 11px;">
                                Tingkat: {{ $kelas->tingkat ?? '-' }}
                            </span>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Daftar peserta didik aktif yang terdaftar di rombongan belajar ini.</span>
                        </p>
                    </div>
                </div>

                {{-- Action Controls --}}
                <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                    <a href="{{ route('kelas.index') }}" class="btn btn-light text-primary rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
                        <iconify-icon icon="solar:alt-arrow-left-line-duotone" class="fs-5"></iconify-icon> Kembali ke Daftar Kelas
                    </a>

                    @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
                        <a href="/data-siswa?kelas_id={{ $kelas->id }}" class="btn btn-success text-white rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
                            <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-5"></iconify-icon> Kelola / Plot Siswa
                        </a>
                    @endif

                    @if(auth()->user()->role !== 'kepsek' && $siswas->count() > 0)
                        <button type="button" class="btn btn-warning text-dark rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#modalPindahMassal">
                            <iconify-icon icon="solar:transfer-horizontal-bold-duotone" class="fs-5"></iconify-icon> Pindah Kelas Massal
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>

    @include('partials.owner_stepped_filter')

    {{-- STATS OVERVIEW CARDS --}}
    <div class="row g-3">
        <div class="col-6 col-md-4">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
                <div class="card-body p-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">TOTAL SISWA</span>
                        <h3 class="fw-bold mb-0 text-primary">{{ $siswas->count() }} <span class="fs-6 font-normal text-muted">Siswa</span></h3>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background-color: var(--primary-blue-soft); color: var(--primary-blue); width: 48px; height: 48px;">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-3"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #8b5cf6 !important; border-radius: 16px !important;">
                <div class="card-body p-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">TINGKAT PENDIDIKAN</span>
                        <h4 class="fw-bold mb-0 text-dark" style="font-size: 17px;">{{ $kelas->tingkat ?? '-' }}</h4>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background-color: #f3e8ff; color: #7e22ce; width: 48px; height: 48px;">
                        <iconify-icon icon="solar:bookmark-square-bold-duotone" class="fs-3"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border shadow-sm h-100" style="border-top: 3.5px solid #10b981 !important; border-radius: 16px !important;">
                <div class="card-body p-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted font-semibold d-block mb-1" style="font-size: 11px;">SEKOLAH / UNIT</span>
                        <h6 class="fw-bold mb-0 text-dark text-truncate" style="max-width: 220px; font-size: 14px;">{{ $kelas->school->nama_sekolah ?? '-' }}</h6>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background-color: #d1fae5; color: #047857; width: 48px; height: 48px;">
                        <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-3"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL DATA SISWA DALAM KELAS --}}
    <div class="card border shadow-sm" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
        <div class="card-body p-4 d-flex flex-column">
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3.5 border-bottom pb-3 gap-2">
                <div>
                    <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15.5px;">
                        <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="text-primary fs-5"></iconify-icon> Anggota Kelas {{ $kelas->nama_kelas }}
                    </h6>
                    <span class="text-muted small" style="font-size: 11.5px;">Menampilkan daftar peserta didik yang saat ini ditempatkan di kelas ini.</span>
                </div>
                
                @if($siswas->count() > 0)
                <div class="input-group input-group-sm" style="max-width: 260px;">
                    <span class="input-group-text bg-white border-end-0 text-muted"><iconify-icon icon="solar:magnifer-linear"></iconify-icon></span>
                    <input type="text" id="searchSiswaInput" class="form-control border-start-0 shadow-none ps-0" placeholder="Cari nama / NISN..." style="font-size: 12.5px;" onkeyup="filterSiswaTable()">
                </div>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0" id="tabelSiswa">
                    <thead>
                        <tr class="border-bottom border-light-subtle">
                            <th width="45" class="ps-0 pb-2 text-center text-muted font-semibold" style="font-size: 11.5px;">#</th>
                            <th class="pb-2 text-muted font-semibold" style="font-size: 11.5px;">NAMA LENGKAP &amp; IDENTITAS</th>
                            <th class="pb-2 text-muted font-semibold" style="font-size: 11.5px;">NISN / NIK</th>
                            <th class="pb-2 text-muted font-semibold" style="font-size: 11.5px;">AKUN ORTU (PORTAL)</th>
                            <th class="text-end pe-0 pb-2 text-muted font-semibold" style="font-size: 11.5px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $index => $siswa)
                        @php
                            $initials = strtoupper(substr($siswa->nama_lengkap ?? $siswa->nama ?? 'S', 0, 2));
                        @endphp
                        <tr class="border-bottom border-light-subtle row-siswa" data-search="{{ strtolower(($siswa->nama_lengkap ?? '') . ' ' . ($siswa->nisn ?? '') . ' ' . ($siswa->nik ?? '')) }}">
                            <td class="ps-0 py-3 text-center text-muted font-semibold" style="font-size: 13px;">{{ $index + 1 }}</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-circle text-primary bg-primary-subtle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 38px; height: 38px; font-size: 13px;">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0" style="font-size: 14px;">{{ $siswa->nama_lengkap ?? $siswa->nama }}</div>
                                        <div class="text-muted font-medium" style="font-size: 11px;">
                                            @if($siswa->jenis_kelamin)
                                                <span class="badge bg-slate-100 text-dark border font-semibold me-1" style="font-size: 10px;">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                            @endif
                                            ID: #{{ $siswa->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="fw-semibold text-dark" style="font-size: 13px;">NISN: {{ $siswa->nisn ?? '-' }}</div>
                                <div class="text-muted font-medium" style="font-size: 11px;">NIK: {{ $siswa->nik ?? '-' }}</div>
                            </td>
                            <td class="py-3">
                                @if($siswa->email)
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 font-semibold d-inline-flex align-items-center gap-1" style="font-size: 10.5px;">
                                        <iconify-icon icon="solar:link-bold"></iconify-icon> Terhubung
                                    </span>
                                    <div class="text-muted font-medium mt-0.5" style="font-size: 11px;">{{ $siswa->email }}</div>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1 font-semibold d-inline-flex align-items-center gap-1" style="font-size: 10.5px;">
                                        <iconify-icon icon="solar:link-broken-bold"></iconify-icon> Belum Terhubung
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-end pe-0">
                                <div class="d-flex justify-content-end gap-1.5 flex-wrap">
                                    <a href="/siswa/{{ $siswa->id }}" class="btn btn-sm btn-outline-primary rounded-3 px-2.5 py-1 font-semibold shadow-xs" style="font-size: 11.5px;">
                                        <iconify-icon icon="solar:eye-bold-duotone" class="align-middle me-1"></iconify-icon> Detail Profil
                                    </a>

                                    @if(auth()->user()->role !== 'kepsek')
                                    <form action="{{ route('kelas.remove_student', ['kelas_id' => $kelas->id, 'siswa_id' => $siswa->id]) }}" method="POST" class="m-0" onsubmit="return confirm('Keluarkan {{ $siswa->nama_lengkap }} dari kelas {{ $kelas->nama_kelas }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-2.5 py-1 font-semibold shadow-xs" style="font-size: 11.5px;" title="Keluarkan dari kelas ini">
                                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="align-middle me-1"></iconify-icon> Keluarkan
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                                        <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-2"></iconify-icon>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 14.5px;">Belum Ada Siswa di Kelas Ini</h6>
                                    <p class="text-muted small mb-0">Belum ada peserta didik yang ditempatkan pada kelas {{ $kelas->nama_kelas }}.</p>
                                    @if(auth()->user()->role !== 'kepsek')
                                        <small class="text-muted d-block mt-1" style="font-size: 11.5px;">Buka menu <strong>Data Siswa</strong> lalu gunakan fitur "Set Kelas" untuk menempatkan siswa.</small>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

{{-- MODAL PINDAH KELAS MASSAL --}}
@if(auth()->user()->role !== 'kepsek')
<div class="modal fade text-start" id="modalPindahMassal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('kelas.move', $kelas->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-slate-50 border-bottom p-3.5">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                        <iconify-icon icon="solar:transfer-horizontal-bold-duotone" class="text-warning fs-5"></iconify-icon> Pindah Kelas Massal
                    </h6>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning border border-warning-subtle rounded-3 small mb-4 py-2 px-3">
                        <iconify-icon icon="solar:info-circle-bold-duotone" class="align-middle me-1 fs-5 text-warning"></iconify-icon>
                        Aksi ini akan memindahkan <strong>semua siswa ({{ $siswas->count() }} anak)</strong> dari kelas <strong>{{ $kelas->nama_kelas }}</strong> ke kelas tujuan untuk semester ini.
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label font-semibold text-dark small">Pilih Kelas Tujuan <span class="text-danger">*</span></label>
                        <select name="target_kelas_id" class="form-select rounded-3 font-medium" required style="font-size: 13px;">
                            <option value="" disabled selected>-- Pilih Kelas Tujuan --</option>
                            @forelse($list_kelas as $lk)
                                <option value="{{ $lk->id }}">{{ $lk->nama_kelas }} (Tingkat: {{ $lk->tingkat }})</option>
                            @empty
                                <option value="" disabled>Belum ada kelas lain di sekolah ini.</option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top p-3.5">
                    <button type="button" class="btn btn-outline-secondary rounded-3 font-semibold px-4 py-2" data-bs-dismiss="modal" style="font-size: 12.5px;">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark rounded-3 font-semibold px-4 py-2 shadow-xs d-inline-flex align-items-center gap-1.5" style="font-size: 12.5px;">
                        <iconify-icon icon="solar:transfer-horizontal-bold-duotone" class="fs-5"></iconify-icon> Proses Pindah Massal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
function filterSiswaTable() {
    const val = document.getElementById('searchSiswaInput').value.toLowerCase();
    const rows = document.querySelectorAll('.row-siswa');
    rows.forEach(r => {
        const text = r.getAttribute('data-search') || '';
        if (text.includes(val)) {
            r.style.display = '';
        } else {
            r.style.display = 'none';
        }
    });
}
</script>
@endsection