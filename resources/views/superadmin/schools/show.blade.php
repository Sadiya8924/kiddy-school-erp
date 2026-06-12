@extends('layouts.superadmin')
@section('title', 'Detail Sekolah')

@section('content')
<div class="row mt-4">

  {{-- Info Sekolah --}}
  <div class="col-lg-4">
    <div class="card shadow-none border text-center">
      <div class="card-body p-4">
        <a href="{{ route('superadmin.schools.index') }}" class="btn btn-sm btn-light float-start">
          <i class="ti ti-arrow-left"></i>
        </a>
        <div class="clearfix mb-3"></div>

        @if($school->logo_sekolah)
          <img src="{{ asset('storage/' . $school->logo_sekolah) }}"
               class="rounded-circle border" width="80" height="80" style="object-fit:cover;">
        @else
          <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center mx-auto mb-0"
               style="width:80px;height:80px;">
            <i class="ti ti-building-school text-primary fs-2"></i>
          </div>
        @endif

        <h5 class="fw-bold mt-3 mb-1">{{ $school->nama_sekolah }}</h5>
        <p class="text-muted small mb-3">{{ $school->alamat ?? '-' }}</p>

        @if($school->status === 'aktif')
          <span class="badge bg-success-subtle text-success fw-semibold px-3 py-2">Aktif</span>
        @else
          <span class="badge bg-danger-subtle text-danger fw-semibold px-3 py-2">Nonaktif</span>
        @endif

        <hr class="my-3">

        <div class="text-start">
          <div class="d-flex justify-content-between py-1 border-bottom">
            <span class="text-muted small">NPSN</span>
            <span class="fw-semibold small">{{ $school->npsn ?? '-' }}</span>
          </div>
          <div class="d-flex justify-content-between py-1 border-bottom">
            <span class="text-muted small">Kepala Sekolah</span>
            <span class="fw-semibold small">{{ $school->kepala_sekolah ?? '-' }}</span>
          </div>
          <div class="d-flex justify-content-between py-1 border-bottom">
            <span class="text-muted small">Telepon</span>
            <span class="fw-semibold small">{{ $school->telepon ?? '-' }}</span>
          </div>
          <div class="d-flex justify-content-between py-1 border-bottom">
            <span class="text-muted small">Tahun Ajaran</span>
            <span class="fw-semibold small">{{ $school->tahun_ajaran_aktif ?? '-' }}</span>
          </div>
          <div class="d-flex justify-content-between py-1">
            <span class="text-muted small">Semester</span>
            <span class="fw-semibold small">{{ $school->semester_aktif ?? '-' }}</span>
          </div>
        </div>

        <div class="d-grid gap-2 mt-3">
          <a href="{{ route('superadmin.schools.modules', $school->id) }}" class="btn btn-info btn-sm text-white">
            <i class="ti ti-settings me-1"></i> Kelola Fitur Sekolah
          </a>
          
          <a href="{{ route('superadmin.schools.edit', $school->id) }}" class="btn btn-warning btn-sm">
            <i class="ti ti-pencil me-1"></i> Edit Sekolah
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Daftar User --}}
  <div class="col-lg-8">
    <div class="card shadow-none border">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="fw-semibold mb-0">
            <i class="ti ti-users me-2"></i> User Sekolah Ini
          </h5>
          <a href="{{ route('superadmin.users.create') }}?school_id={{ $school->id }}"
             class="btn btn-primary btn-sm">
            <i class="ti ti-plus me-1"></i> Tambah User
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $i => $user)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                         style="width:32px;height:32px;font-size:12px;font-weight:600;color:#4f46e5;">
                      {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <span class="fw-semibold">{{ $user->name }}</span>
                  </div>
                </td>
                <td class="text-muted small">{{ $user->email }}</td>
                <td>
                  @php
                    $roleLabel = ['admin' => ['bg-primary-subtle text-primary', 'Admin'],
                                  'guru'  => ['bg-info-subtle text-info', 'Guru'],
                                  'wali_murid' => ['bg-warning-subtle text-warning', 'Wali Murid']];
                    $r = $roleLabel[$user->role] ?? ['bg-secondary-subtle text-secondary', $user->role];
                  @endphp
                  <span class="badge {{ $r[0] }}">{{ $r[1] }}</span>
                </td>
                <td>
                  <div class="d-flex gap-1">
                    <a href="{{ route('superadmin.users.edit', $user->id) }}"
                       class="btn btn-sm btn-warning"><i class="ti ti-pencil"></i></a>
                    <form method="POST" action="{{ route('superadmin.users.destroy', $user->id) }}"
                          onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">
                  Belum ada user di sekolah ini.
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
@endsection