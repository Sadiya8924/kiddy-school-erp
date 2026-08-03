@extends('layouts.superadmin')
@section('title', 'Super Admin Dashboard')

@section('content')
<div class="row gx-3 gy-3 mt-0">

  {{-- 1. KOTAK WELCOME (Icon Biru Solid & Tanggal di Kanan) --}}
  <div class="col-12">
    <div class="card border mb-0 shadow-none" style="background-color: var(--primary-blue-soft); border-color: var(--primary-blue-border) !important;">
      <div class="card-body px-4 py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
          <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; background-color: var(--primary-blue); color: white;">
            <iconify-icon icon="solar:display-bold-duotone" class="fs-3"></iconify-icon>
          </div>
          <div>
            <h6 class="fw-bold mb-1" style="color: var(--primary-blue-dark); font-size: 16px;">Selamat datang, {{ auth()->user()->name }}</h6>
            <p class="mb-0" style="color: var(--primary-blue); font-size: 13px;">Panel pusat — kelola semua sekolah dalam sistem TK Ceria</p>
          </div>
        </div>
        <div class="d-none d-md-flex align-items-center gap-2" style="color: var(--primary-blue); font-size: 13px; font-weight: 500;">
          <iconify-icon icon="solar:calendar-mark-line-duotone" class="fs-5"></iconify-icon>
          <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</span>
        </div>
      </div>
    </div>
  </div>

  {{-- 2. EMPAT STAT CARDS --}}
  <div class="col-md-6 col-lg-3">
    <a href="{{ auth()->user()->isOwner() ? route('owner.yayasans.index') : route('superadmin.schools.index') }}" class="card p-4 mb-0 h-100 d-flex flex-column justify-content-between border shadow-none card-clickable text-decoration-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: var(--primary-blue-soft); color: var(--primary-blue-border);">
          <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-4"></iconify-icon>
        </div>
        <span class="badge bg-light text-muted border rounded-pill px-2 py-1" style="font-size: 10px;">Total</span>
      </div>
      <div>
        <h2 class="fw-bold text-dark mb-0">{{ $totalSchools }}</h2>
        <span class="text-muted fw-medium" style="font-size: 13px;">Sekolah terdaftar</span>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-3">
    <a href="{{ auth()->user()->isOwner() ? route('owner.yayasans.index') : route('superadmin.schools.index') }}" class="card p-4 mb-0 h-100 d-flex flex-column justify-content-between border shadow-none card-clickable text-decoration-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #dcfce7; color: #86efac;">
          <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-4"></iconify-icon>
        </div>
        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 10px;">100%</span>
      </div>
      <div>
        <h2 class="fw-bold text-dark mb-0">{{ $activeSchools }}</h2>
        <span class="text-muted fw-medium" style="font-size: 13px;">Sekolah aktif</span>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-3">
    <a href="{{ route('superadmin.users.index') }}" class="card p-4 mb-0 h-100 d-flex flex-column justify-content-between border shadow-none card-clickable text-decoration-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #ffedd5; color: #fdba74;">
          <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-4"></iconify-icon>
        </div>
      </div>
      <div>
        <h2 class="fw-bold text-dark mb-0">{{ $totalUsers }}</h2>
        <span class="text-muted fw-medium" style="font-size: 13px;">Total pengguna</span>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-3">
    <a href="{{ route('superadmin.schools.index') }}" class="card p-4 mb-0 h-100 d-flex flex-column justify-content-between border shadow-none card-clickable text-decoration-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #f3e8ff; color: #d8b4fe;">
          <iconify-icon icon="solar:diploma-bold-duotone" class="fs-4"></iconify-icon>
        </div>
      </div>
      <div>
        <h2 class="fw-bold text-dark mb-0">{{ $totalSiswa }}</h2>
        <span class="text-muted fw-medium" style="font-size: 13px;">Total siswa</span>
      </div>
    </a>
  </div>

  {{-- 3. BAGIAN BAWAH: TABEL SEKOLAH & PENGUMUMAN --}}
  <div class="col-lg-8">
    <div class="card mb-0 h-100 border shadow-none">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
          <h6 class="fw-bold mb-0 text-dark" style="font-size: 15px;">Daftar sekolah</h6>
          {{-- Tombol Tambah Biru Solid --}}
          <a href="{{ route('superadmin.schools.create') }}" class="btn btn-primary rounded btn-sm d-flex align-items-center gap-1 px-3 py-1.5" style="font-size: 12px;">
            <iconify-icon icon="solar:square-alt-arrow-right-line-duotone" class="fs-6"></iconify-icon> Tambah
          </a>
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
                      <img src="{{ asset('storage/' . $school->logo_sekolah) }}" alt="Logo" class="rounded object-fit-cover shadow-sm" style="width: 38px; height: 38px;">
                    @else
                      <div class="rounded d-flex align-items-center justify-content-center fw-bold text-primary shadow-sm" style="width: 38px; height: 38px; background-color: var(--primary-blue-soft); font-size: 13px;">
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
                  <span class="badge {{ $school->status === 'aktif' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3 py-1.5" style="font-size: 11px;">
                    {{ ucfirst($school->status) }}
                  </span>
                </td>
                <td class="py-3 text-end pe-0">
                  <div class="d-flex justify-content-end gap-2">
                    {{-- Tombol Aksi Outline Kotak --}}
                    <a href="{{ route('superadmin.schools.show', $school->id) }}" class="btn btn-outline-light border rounded shadow-none text-muted p-1 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;"><iconify-icon icon="solar:eye-line-duotone" class="fs-5"></iconify-icon></a>
                    <a href="{{ route('superadmin.schools.edit', $school->id) }}" class="btn btn-outline-light border rounded shadow-none text-muted p-1 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;"><iconify-icon icon="solar:pen-line-duotone" class="fs-5"></iconify-icon></a>
                  </div>
                </td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center text-muted py-4 small">Belum ada sekolah terdaftar.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        <div class="text-center mt-3 pt-2">
            <a href="{{ route('superadmin.schools.index') }}" class="text-decoration-none fw-semibold" style="color: var(--primary-blue); font-size: 13px;">Lihat semua sekolah <iconify-icon icon="solar:alt-arrow-right-line-duotone" class="align-middle"></iconify-icon></a>
        </div>
      </div>
    </div>
  </div>

  {{-- 4. PANTAUAN PENGUMUMAN WIDGET --}}
  <div class="col-lg-4">
    <div class="card mb-0 h-100 border shadow-none">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-4 text-dark d-flex align-items-center gap-2 border-bottom pb-3" style="font-size: 15px;">
          <iconify-icon icon="solar:bell-line-duotone" class="text-warning fs-5"></iconify-icon> Pantauan pengumuman
        </h6>
        
        <div class="d-flex flex-column gap-3">
          @forelse($pengumuman_global as $p)
            <div class="d-flex flex-column border-bottom border-light-subtle pb-3">
              <div class="d-flex justify-content-between align-items-start mb-1">
                <span class="fw-semibold text-dark text-truncate" style="font-size: 13px; max-width: 70%;">{{ $p->judul }}</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size: 10px; font-weight: 600;">{{ $p->nama_sekolah }}</span>
              </div>
              <p class="text-muted mb-1 text-truncate" style="font-size: 12px;">{{ $p->isi }}</p>
              <div class="d-flex align-items-center gap-1 text-muted mt-1" style="font-size: 11px;">
                <iconify-icon icon="solar:clock-circle-line-duotone"></iconify-icon>
                <span>{{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}</span>
              </div>
            </div>
          @empty
            <div class="text-center text-muted py-4 small">Tidak ada aktivitas pengumuman.</div>
          @endforelse
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
        border-color: var(--primary-blue-border) !important;
    }
</style>
@endsection