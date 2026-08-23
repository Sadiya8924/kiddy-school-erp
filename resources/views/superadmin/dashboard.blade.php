@extends('layouts.superadmin')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex flex-column gap-3.5 mt-0">

  @if(session('success'))
    <div class="alert alert-success border border-success-subtle shadow-none rounded-3 py-2 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
      <div><i class="ti ti-check me-2"></i>{{ session('success') }}</div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- ========================================================== --}}
  {{-- 1. HERO WELCOME CARD WITH SOFT BLUE GRADIENT --}}
  {{-- ========================================================== --}}
  <div class="card border-0 shadow-sm overflow-hidden" style="background: var(--theme-hero-gradient, linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%)); border-radius: 16px !important;">
    <div class="card-body p-4 text-white">
      <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
        
        {{-- User Greeting & Context --}}
        <div class="d-flex align-items-center gap-3">
          <div class="position-relative">
            <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 20px; border: 2px solid rgba(255,255,255,0.4);">
              {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
              <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Selamat datang, {{ auth()->user()->name }} 👋</h5>
            </div>
            <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
              <span>Panel Pusat — Kelola seluruh sekolah dalam sistem TK Ceria</span>
              <span>•</span>
              <span><iconify-icon icon="solar:calendar-mark-line-duotone" class="align-middle text-warning me-1"></iconify-icon>{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</span>
            </p>
          </div>
        </div>

        {{-- Action Controls --}}
        @if(!auth()->user()->isOwner())
          <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
            <a href="{{ route('superadmin.schools.create') }}" class="btn btn-warning text-dark rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
              <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon> + Sekolah
            </a>
          </div>
        @endif

      </div>
    </div>
  </div>

  {{-- ========================================================== --}}
  {{-- 2. KPI STATISTICS CARDS WITH COLORED TOP BORDER --}}
  {{-- ========================================================== --}}
  <div class="row g-3">
    
    {{-- Total Sekolah Terdaftar --}}
    <div class="col-xl-3 col-md-6">
      <a href="{{ auth()->user()->isOwner() ? route('owner.yayasans.index') : route('superadmin.schools.index') }}" class="card h-100 border shadow-sm card-clickable text-decoration-none" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
        <div class="card-body p-3.5 d-flex justify-content-between align-items-center">
          <div>
            <span class="text-muted font-semibold d-block mb-1" style="font-size: 11.5px; letter-spacing: 0.02em;">TOTAL SEKOLAH TERDAFTAR</span>
            <h2 class="fw-bold text-dark mb-0" style="letter-spacing: -0.03em; font-size: 26px;">{{ $totalSchools }}</h2>
            <span class="text-muted small mt-1 d-block" style="font-size: 11px;">Sekolah terdaftar di sistem</span>
          </div>
          <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background-color: #eff6ff; color: #3b82f6; width: 54px; height: 54px;">
            <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-2"></iconify-icon>
          </div>
        </div>
      </a>
    </div>

    {{-- Sekolah Aktif --}}
    <div class="col-xl-3 col-md-6">
      <a href="{{ auth()->user()->isOwner() ? route('owner.yayasans.index') : route('superadmin.schools.index') }}" class="card h-100 border shadow-sm card-clickable text-decoration-none" style="border-top: 3.5px solid #10b981 !important; border-radius: 16px !important;">
        <div class="card-body p-3.5 d-flex justify-content-between align-items-center">
          <div>
            <span class="text-muted font-semibold d-block mb-1" style="font-size: 11.5px; letter-spacing: 0.02em;">SEKOLAH AKTIF</span>
            <h2 class="fw-bold text-dark mb-0" style="letter-spacing: -0.03em; font-size: 26px;">{{ $activeSchools }}</h2>
            <span class="text-muted small mt-1 d-block" style="font-size: 11px;">Status aktif &amp; beroperasi</span>
          </div>
          <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background-color: #ecfdf5; color: #10b981; width: 54px; height: 54px;">
            <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-2"></iconify-icon>
          </div>
        </div>
      </a>
    </div>

    <div class="col-xl-3 col-md-6">
      @php
        $dashYayasan = auth()->user()->yayasan_id ? \App\Models\Yayasan::find(auth()->user()->yayasan_id) : (\App\Models\Yayasan::find(session('active_yayasan_id')) ?? \App\Models\Yayasan::first());
      @endphp
      <a href="{{ $dashYayasan ? route('superadmin.users.encrypted', $dashYayasan->encrypted_token) : route('superadmin.users.index') }}" class="card h-100 border shadow-sm card-clickable text-decoration-none" style="border-top: 3.5px solid #f59e0b !important; border-radius: 16px !important;">
        <div class="card-body p-3.5 d-flex justify-content-between align-items-center">
          <div>
            <span class="text-muted font-semibold d-block mb-1" style="font-size: 11.5px; letter-spacing: 0.02em;">TOTAL PENGGUNA</span>
            <h2 class="fw-bold text-dark mb-0" style="letter-spacing: -0.03em; font-size: 26px;">{{ $totalUsers }}</h2>
            <span class="text-muted small mt-1 d-block" style="font-size: 11px;">Admin, guru &amp; wali murid</span>
          </div>
          <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background-color: #fffbe6; color: #f59e0b; width: 54px; height: 54px;">
            <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-2"></iconify-icon>
          </div>
        </div>
      </a>
    </div>

    {{-- Total Siswa --}}
    <div class="col-xl-3 col-md-6">
      <a href="{{ route('superadmin.schools.index') }}" class="card h-100 border shadow-sm card-clickable text-decoration-none" style="border-top: 3.5px solid #8b5cf6 !important; border-radius: 16px !important;">
        <div class="card-body p-3.5 d-flex justify-content-between align-items-center">
          <div>
            <span class="text-muted font-semibold d-block mb-1" style="font-size: 11.5px; letter-spacing: 0.02em;">TOTAL SISWA</span>
            <h2 class="fw-bold text-dark mb-0" style="letter-spacing: -0.03em; font-size: 26px;">{{ $totalSiswa }}</h2>
            <span class="text-muted small mt-1 d-block" style="font-size: 11px;">Peserta didik terdaftar</span>
          </div>
          <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background-color: #f3e8ff; color: #8b5cf6; width: 54px; height: 54px;">
            <iconify-icon icon="solar:diploma-bold-duotone" class="fs-2"></iconify-icon>
          </div>
        </div>
      </a>
    </div>

  </div>

  {{-- ========================================================== --}}
  {{-- 3. DAFTAR SEKOLAH & PANTAUAN PENGUMUMAN --}}
  {{-- ========================================================== --}}
  <div class="row g-4 mt-1">
    
    {{-- Daftar Sekolah Table --}}
    <div class="col-lg-8">
      <div class="card h-100 border shadow-sm" style="border-radius: 16px !important;">
        <div class="card-body p-4 d-flex flex-column">
          <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
            <div>
              <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15px;">
                <iconify-icon icon="solar:buildings-bold-duotone" class="text-primary fs-5"></iconify-icon> Daftar Sekolah
              </h6>
            </div>
            @if(!auth()->user()->isOwner())
              <a href="{{ route('superadmin.schools.create') }}" class="btn btn-primary btn-sm rounded-3 font-semibold px-3 py-1.5 d-flex align-items-center gap-1 shadow-xs" style="font-size: 12px;">
                <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon> Tambah
              </a>
            @endif
          </div>
          
          <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
              <thead>
                <tr class="border-bottom border-light-subtle">
                  <th class="ps-0 pb-2">NAMA SEKOLAH</th>
                  <th class="pb-2">TA AKTIF</th>
                  <th class="pb-2">STATUS</th>
                  <th class="text-end pe-0 pb-2">AKSI</th>
                </tr>
              </thead>
              <tbody>
                @forelse($schools->take(4) as $school)
                  <tr class="border-bottom border-light-subtle">
                    <td class="ps-0 py-3">
                      <div class="d-flex align-items-center gap-3">
                        @if($school->logo_sekolah)
                          <img src="{{ asset('storage/' . $school->logo_sekolah) }}" alt="Logo" class="rounded object-fit-cover shadow-sm flex-shrink-0" style="width: 40px; height: 40px;">
                        @else
                          <div class="rounded d-flex align-items-center justify-content-center fw-bold text-primary shadow-sm flex-shrink-0" style="width: 40px; height: 40px; background-color: #eff6ff; font-size: 14px;">
                            {{ strtoupper(substr($school->nama_sekolah, 0, 2)) }}
                          </div>
                        @endif
                        <div>
                          <div class="fw-bold text-dark" style="font-size: 14px;">{{ $school->nama_sekolah }}</div>
                          <div class="text-muted" style="font-size: 12px;">{{ $school->alamat ?? 'Surabaya, Jawa Timur' }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="py-3 text-muted fw-medium" style="font-size: 13px;">{{ $school->tahun_ajaran_aktif ?? '-' }}</td>
                    <td class="py-3">
                      <span class="badge {{ $school->status === 'aktif' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-2.5 py-1" style="font-size: 11px;">
                        {{ ucfirst($school->status) }}
                      </span>
                    </td>
                    <td class="py-3 text-end pe-0">
                      <div class="d-flex justify-content-end gap-1.5">
                        <a href="{{ route('superadmin.schools.show', $school->id) }}" class="btn btn-outline-light border rounded-3 shadow-none text-muted p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Detail"><iconify-icon icon="solar:eye-bold-duotone" class="fs-5 text-primary"></iconify-icon></a>
                        <a href="{{ route('superadmin.schools.edit', $school->id) }}" class="btn btn-outline-light border rounded-3 shadow-none text-muted p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit"><iconify-icon icon="solar:pen-bold-duotone" class="fs-5 text-warning"></iconify-icon></a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center py-4">
                      <div class="py-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                          <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-3"></iconify-icon>
                        </div>
                        <div class="text-muted small">Belum ada sekolah terdaftar.</div>
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          
          <div class="mt-auto pt-3 text-center border-top">
            <a href="{{ route('superadmin.schools.index') }}" class="text-decoration-none font-semibold text-primary small d-inline-flex align-items-center gap-1" style="font-size: 12.5px;">
              Lihat Semua Sekolah <iconify-icon icon="solar:alt-arrow-right-line-duotone" class="align-middle fs-5"></iconify-icon>
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Pantauan Pengumuman Widget --}}
    <div class="col-lg-4">
      <div class="card h-100 border shadow-sm" style="border-radius: 16px !important;">
        <div class="card-body p-4 d-flex flex-column">
          <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 15px;">
              <iconify-icon icon="solar:bell-bold-duotone" class="text-warning fs-5"></iconify-icon> Pantauan Pengumuman
            </h6>
          </div>
          
          @if($pengumuman_global->count() > 0)
            <div class="d-flex flex-column gap-2.5">
              @foreach($pengumuman_global as $p)
                <div class="p-3 rounded-3 border border-slate-100 bg-slate-50">
                  <div class="d-flex justify-content-between align-items-start mb-1 gap-2">
                    <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 13px; max-width: 170px;">{{ $p->judul }}</h6>
                    <span class="badge bg-primary-subtle text-primary rounded-pill flex-shrink-0" style="font-size: 10px; font-weight: 600;">{{ $p->nama_sekolah }}</span>
                  </div>
                  <p class="text-muted mb-2 text-truncate" style="font-size: 11.5px;">{{ $p->isi }}</p>
                  <div class="d-flex align-items-center gap-1 text-muted" style="font-size: 11px;">
                    <iconify-icon icon="solar:clock-circle-bold-duotone" class="text-warning"></iconify-icon>
                    <span>{{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            {{-- BEAUTIFUL EMPTY STATE --}}
            <div class="my-auto text-center py-4 px-2">
              <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center mb-3 shadow-xs" style="width: 58px; height: 58px;">
                <iconify-icon icon="solar:bell-off-bold-duotone" class="fs-2"></iconify-icon>
              </div>
              <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada Pengumuman</h6>
              <p class="text-muted small mb-0 mx-auto" style="max-width: 240px; font-size: 11.5px;">Belum ada aktivitas pengumuman dari sekolah mana pun.</p>
            </div>
          @endif
        </div>
      </div>
    </div>

  </div>

</div>

<style>
  .card-clickable {
    transition: all 0.2s ease-in-out !important;
    cursor: pointer;
  }
  .card-clickable:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08) !important;
    border-color: #bfdbfe !important;
  }
</style>
@endsection