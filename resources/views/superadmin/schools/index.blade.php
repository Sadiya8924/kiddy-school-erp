@extends('layouts.superadmin')
@section('title', 'Data Sekolah')

@section('content')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

  {{-- ========================================================== --}}
  {{-- 1. HERO WELCOME CARD WITH SOFT BLUE GRADIENT --}}
  {{-- ========================================================== --}}
  <div class="card border-0 shadow-sm overflow-hidden hero-welcome-card" style="background: var(--theme-hero-gradient, linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%)); border-radius: 16px !important;">
    <div class="card-body p-4 text-white">
      <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
        
        {{-- User Greeting & Context --}}
        <div class="d-flex align-items-center gap-3">
          <div class="position-relative">
            <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid rgba(255,255,255,0.4);">
              <iconify-icon icon="solar:buildings-2-bold-duotone"></iconify-icon>
            </div>
            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
              <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Data Sekolah</h5>
            </div>
            <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
              <span>Kelola semua sekolah yang terdaftar di sistem .</span>
            </p>
          </div>
        </div>

        {{-- Action Button --}}
        @if(!auth()->user()->isOwner())
        <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
          <a href="{{ route('superadmin.schools.create') }}" class="btn btn-warning text-dark rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
            <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon> Tambah Sekolah
          </a>
        </div>
        @endif

      </div>
    </div>
  </div>

  {{-- ========================================================== --}}
  {{-- 2. TABEL DATA SEKOLAH --}}
  {{-- ========================================================== --}}
  <div class="card border shadow-sm" style="border-top: 3.5px solid var(--primary-blue, #3b82f6) !important; border-radius: 16px !important;">
    <div class="card-body p-4 d-flex flex-column">
      <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
        <div>
          <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15px;">
            <iconify-icon icon="solar:buildings-bold-duotone" class="text-primary fs-5"></iconify-icon> Daftar Unit Sekolah Terdaftar
          </h6>
          <span class="text-muted small" style="font-size: 11.5px;">Informasi profil, NPSN, tahun ajaran aktif, dan modul tiap sekolah</span>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-borderless align-middle mb-0">
          <thead>
            <tr class="border-bottom border-light-subtle">
              <th width="40" class="ps-0 pb-2 text-center">#</th>
              <th class="pb-2">SEKOLAH</th>
              <th class="pb-2">NPSN</th>
              <th class="pb-2">KEPALA SEKOLAH</th>
              <th class="pb-2">TAHUN AJARAN</th>
              <th class="pb-2">TELEPON</th>
              <th class="pb-2">STATUS</th>
              <th class="text-center pe-0 pb-2" width="160">AKSI</th>
            </tr>
          </thead>
          <tbody>
            @forelse($schools as $i => $school)
            <tr class="border-bottom border-light-subtle">
              <td class="ps-0 py-3 text-center text-muted font-semibold" style="font-size: 13px;">{{ $schools->firstItem() + $i }}</td>
              <td class="py-3">
                <div class="d-flex align-items-center gap-2.5">
                  @if($school->logo_sekolah)
                    <img src="{{ asset('storage/' . $school->logo_sekolah) }}"
                         class="rounded-3 shadow-xs border" width="38" height="38" style="object-fit:cover;">
                  @else
                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                         style="width:38px;height:38px;">
                      <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-4"></iconify-icon>
                    </div>
                  @endif
                  <div>
                    <h6 class="fw-bold text-dark mb-0.5" style="font-size: 14px;">{{ $school->nama_sekolah }}</h6>
                    <span class="text-muted font-medium" style="font-size: 11.5px;">{{ Str::limit($school->alamat, 40) ?? '-' }}</span>
                  </div>
                </div>
              </td>
              <td class="py-3 text-dark font-medium" style="font-size: 12.5px;">{{ $school->npsn ?? '-' }}</td>
              <td class="py-3 text-dark font-medium" style="font-size: 12.5px;">{{ $school->kepala_sekolah ?? '-' }}</td>
              <td class="py-3">
                @if($school->tahun_ajaran_aktif)
                  <span class="fw-bold text-dark d-block" style="font-size: 12.5px;">{{ $school->tahun_ajaran_aktif }}</span>
                  @if($school->semester_aktif)
                    <span class="text-muted font-medium" style="font-size: 11px;">Semester {{ $school->semester_aktif }}</span>
                  @endif
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>
              <td class="py-3 text-muted font-medium" style="font-size: 12.5px;">{{ $school->telepon ?? '-' }}</td>
              <td class="py-3">
                @if($school->status === 'aktif')
                  <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px;">Aktif</span>
                @else
                  <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px;">Nonaktif</span>
                @endif
              </td>
              <td class="text-center py-3 pe-0">
                <div class="d-flex justify-content-center gap-1">
                  <a href="{{ route('superadmin.schools.modules', $school->id) }}"
                     class="btn btn-sm btn-info text-white rounded-3 p-1.5 shadow-xs d-inline-flex align-items-center justify-content-center" style="width:30px; height:30px;" title="Kelola Fitur Sekolah">
                    <iconify-icon icon="solar:settings-bold-duotone" class="fs-5"></iconify-icon>
                  </a>

                  <a href="{{ route('superadmin.schools.show', $school->id) }}"
                     class="btn btn-sm btn-light text-primary rounded-3 p-1.5 border-0 shadow-xs d-inline-flex align-items-center justify-content-center" style="width:30px; height:30px;" title="Lihat Detail">
                    <iconify-icon icon="solar:eye-bold-duotone" class="fs-5"></iconify-icon>
                  </a>
                  <a href="{{ route('superadmin.schools.edit', $school->id) }}"
                     class="btn btn-sm btn-warning text-dark rounded-3 p-1.5 shadow-xs d-inline-flex align-items-center justify-content-center" style="width:30px; height:30px;" title="Edit">
                    <iconify-icon icon="solar:pen-bold-duotone" class="fs-5"></iconify-icon>
                  </a>
                  <form method="POST" action="{{ route('superadmin.schools.destroy', $school->id) }}" class="d-inline"
                        onsubmit="return confirm('Yakin hapus sekolah {{ $school->nama_sekolah }}? Semua data siswa, rapor, dll akan ikut terhapus!')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger rounded-3 p-1.5 shadow-xs d-inline-flex align-items-center justify-content-center" style="width:30px; height:30px;" title="Hapus">
                      <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-5"></iconify-icon>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center py-5">
                <div class="py-3">
                  <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                    <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-2"></iconify-icon>
                  </div>
                  <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada Sekolah Terdaftar</h6>
                  <p class="text-muted small mb-2">Klik tombol di bawah ini untuk menyoroti unit sekolah baru.</p>
                  @if(!auth()->user()->isOwner())
                  <a href="{{ route('superadmin.schools.create') }}" class="btn btn-sm btn-primary rounded-3 font-semibold px-3 py-1.5 shadow-xs">
                    Tambah Sekolah Sekarang
                  </a>
                  @endif
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if($schools->hasPages())
      <div class="mt-3.5 pt-3 border-top">
        {{ $schools->links() }}
      </div>
      @endif

    </div>
  </div>

  {{-- ========================================================== --}}
  {{-- SUBTLE LOW-KEY FOOTER --}}
  {{-- ========================================================== --}}
  <footer class="text-center py-2 mt-2">
      <span class="text-muted" style="font-size: 11px;">© 2026 TK Ceria - Web TK Al-Hidayah v1.0</span>
  </footer>

</div>
@endsection