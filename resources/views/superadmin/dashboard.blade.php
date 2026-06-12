@extends('layouts.superadmin')
@section('title', 'Super Admin Dashboard')

@section('content')
<div class="row">

  {{-- Page Title --}}
  <div class="col-12 mt-4">
    <div class="card bg-success-subtle border-0 shadow-none">
      <div class="card-body py-3">
        <h5 class="mb-1 text-success fw-bold">
          👋 Selamat Datang, {{ auth()->user()->name }}!
        </h5>
        <p class="mb-0 text-muted small">Panel Super Admin — kelola semua sekolah dalam sistem TK Ceria.</p>
      </div>
    </div>
  </div>

  {{-- Stat Cards --}}
  <div class="col-sm-6 col-lg-3 mt-4">
    <div class="card overflow-hidden shadow-none border">
      <div class="card-body p-4">
        <h5 class="fw-semibold text-muted mb-3 text-uppercase" style="font-size:11px;letter-spacing:1px;">
          Total Sekolah
        </h5>
        <div class="d-flex align-items-center gap-3">
          <div class="rounded bg-primary-subtle p-2">
            <i class="ti ti-building-school text-primary fs-6"></i>
          </div>
          <h3 class="fw-bold mb-0 text-primary">{{ $totalSchools }}</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mt-4">
    <div class="card overflow-hidden shadow-none border">
      <div class="card-body p-4">
        <h5 class="fw-semibold text-muted mb-3 text-uppercase" style="font-size:11px;letter-spacing:1px;">
          Sekolah Aktif
        </h5>
        <div class="d-flex align-items-center gap-3">
          <div class="rounded bg-success-subtle p-2">
            <i class="ti ti-circle-check text-success fs-6"></i>
          </div>
          <h3 class="fw-bold mb-0 text-success">{{ $activeSchools }}</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mt-4">
    <div class="card overflow-hidden shadow-none border">
      <div class="card-body p-4">
        <h5 class="fw-semibold text-muted mb-3 text-uppercase" style="font-size:11px;letter-spacing:1px;">
          Total User
        </h5>
        <div class="d-flex align-items-center gap-3">
          <div class="rounded bg-warning-subtle p-2">
            <i class="ti ti-users text-warning fs-6"></i>
          </div>
          <h3 class="fw-bold mb-0 text-warning">{{ $totalUsers }}</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mt-4">
    <div class="card overflow-hidden shadow-none border">
      <div class="card-body p-4">
        <h5 class="fw-semibold text-muted mb-3 text-uppercase" style="font-size:11px;letter-spacing:1px;">
          Total Siswa
        </h5>
        <div class="d-flex align-items-center gap-3">
          <div class="rounded bg-info-subtle p-2">
            <i class="ti ti-school text-info fs-6"></i>
          </div>
          <h3 class="fw-bold mb-0 text-info">{{ $totalSiswa }}</h3>
        </div>
      </div>
    </div>
  </div>

  {{-- Tabel Sekolah Terbaru --}}
  <div class="col-12 mt-4">
    <div class="card shadow-none border">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="fw-semibold mb-0">Daftar Sekolah</h5>
          <a href="{{ route('superadmin.schools.create') }}" class="btn btn-primary btn-sm">
            <i class="ti ti-plus me-1"></i> Tambah Sekolah
          </a>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Nama Sekolah</th>
                <th>NPSN</th>
                <th>Kepala Sekolah</th>
                <th>TA Aktif</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($schools as $i => $school)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    @if($school->logo_sekolah)
                      <img src="{{ asset('storage/' . $school->logo_sekolah) }}"
                           class="rounded" width="32" height="32" style="object-fit:cover;">
                    @else
                      <div class="rounded bg-primary-subtle d-flex align-items-center justify-content-center"
                           style="width:32px;height:32px;">
                        <i class="ti ti-building-school text-primary small"></i>
                      </div>
                    @endif
                    <span class="fw-semibold">{{ $school->nama_sekolah }}</span>
                  </div>
                </td>
                <td>{{ $school->npsn ?? '-' }}</td>
                <td>{{ $school->kepala_sekolah ?? '-' }}</td>
                <td>{{ $school->tahun_ajaran_aktif ?? '-' }} {{ $school->semester_aktif ? '/ ' . $school->semester_aktif : '' }}</td>
                <td>
                  @if($school->status === 'aktif')
                    <span class="badge bg-success-subtle text-success">Aktif</span>
                  @else
                    <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex gap-1">
                    <a href="{{ route('superadmin.schools.show', $school->id) }}"
                       class="btn btn-sm btn-light" title="Detail">
                      <i class="ti ti-eye"></i>
                    </a>
                    <a href="{{ route('superadmin.schools.edit', $school->id) }}"
                       class="btn btn-sm btn-warning" title="Edit">
                      <i class="ti ti-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('superadmin.schools.destroy', $school->id) }}"
                          onsubmit="return confirm('Hapus sekolah ini? Semua data terkait akan ikut terhapus!')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" title="Hapus">
                        <i class="ti ti-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">Belum ada sekolah terdaftar.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection