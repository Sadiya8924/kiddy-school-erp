@extends('layouts.superadmin')
@section('title', 'Detail Sekolah')

@section('content')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

  <div class="row g-3.5">

    {{-- Info Sekolah --}}
    <div class="col-lg-4">
      <div class="card border shadow-sm text-center" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
        <div class="card-body p-4">
          <a href="{{ route('superadmin.schools.index') }}" class="btn btn-sm btn-light text-muted rounded-circle p-2 shadow-xs d-inline-flex align-items-center justify-content-center float-start" style="width: 36px; height: 36px;" title="Kembali">
            <iconify-icon icon="solar:alt-arrow-left-bold-duotone" class="fs-5"></iconify-icon>
          </a>
          <div class="clearfix mb-3"></div>

          @if($school->logo_sekolah)
            <img src="{{ asset('storage/' . $school->logo_sekolah) }}"
                 class="rounded-circle border shadow-xs" width="80" height="80" style="object-fit:cover;">
          @else
            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center mx-auto mb-0"
                 style="width:80px;height:80px;">
              <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-1"></iconify-icon>
            </div>
          @endif

          <h5 class="fw-bold text-dark mt-3 mb-0.5" style="font-size: 17px;">{{ $school->nama_sekolah }}</h5>
          <p class="text-muted font-medium mb-3" style="font-size: 12px;">{{ $school->alamat ?? '-' }}</p>

          @if($school->status === 'aktif')
            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 font-semibold" style="font-size: 11.5px;">Aktif</span>
          @else
            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1 font-semibold" style="font-size: 11.5px;">Nonaktif</span>
          @endif

          <hr class="my-3 border-light-subtle">

          <div class="text-start">
            <div class="d-flex justify-content-between py-1.5 border-bottom border-light-subtle">
              <span class="text-muted font-medium" style="font-size: 12px;">NPSN</span>
              <span class="fw-bold text-dark" style="font-size: 12.5px;">{{ $school->npsn ?? '-' }}</span>
            </div>
            <div class="d-flex justify-content-between py-1.5 border-bottom border-light-subtle">
              <span class="text-muted font-medium" style="font-size: 12px;">Kepala Sekolah</span>
              <span class="fw-bold text-dark" style="font-size: 12.5px;">{{ $school->kepala_sekolah ?? '-' }}</span>
            </div>
            <div class="d-flex justify-content-between py-1.5 border-bottom border-light-subtle">
              <span class="text-muted font-medium" style="font-size: 12px;">Telepon</span>
              <span class="fw-bold text-dark" style="font-size: 12.5px;">{{ $school->telepon ?? '-' }}</span>
            </div>
            <div class="d-flex justify-content-between py-1.5 border-bottom border-light-subtle">
              <span class="text-muted font-medium" style="font-size: 12px;">Tahun Ajaran</span>
              <span class="fw-bold text-dark" style="font-size: 12.5px;">{{ $school->tahun_ajaran_aktif ?? '-' }}</span>
            </div>
            <div class="d-flex justify-content-between py-1.5">
              <span class="text-muted font-medium" style="font-size: 12px;">Semester</span>
              <span class="fw-bold text-dark" style="font-size: 12.5px;">{{ $school->semester_aktif ?? '-' }}</span>
            </div>
          </div>

          <div class="d-flex flex-column gap-2 mt-3.5 pt-2 border-top">
            <a href="{{ route('superadmin.schools.modules', $school->id) }}" class="btn btn-primary rounded-3 font-semibold py-2 shadow-xs d-flex align-items-center justify-content-center gap-1.5" style="font-size: 12.5px;">
              <iconify-icon icon="solar:settings-bold-duotone" class="fs-5"></iconify-icon> Kelola Fitur Sekolah
            </a>
            
            <a href="{{ route('superadmin.schools.edit', $school->id) }}" class="btn btn-warning text-dark rounded-3 font-semibold py-2 shadow-xs d-flex align-items-center justify-content-center gap-1.5" style="font-size: 12.5px;">
              <iconify-icon icon="solar:pen-bold-duotone" class="fs-5"></iconify-icon> Edit Sekolah
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Daftar User --}}
    <div class="col-lg-8">
      <div class="card border shadow-sm h-100" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
        <div class="card-body p-4 d-flex flex-column">
          <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
            <div>
              <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15px;">
                <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="text-primary fs-5"></iconify-icon> User Sekolah Ini
              </h6>
              <span class="text-muted small" style="font-size: 11.5px;">Daftar akun pengguna yang ditugaskan di sekolah ini</span>
            </div>
            <a href="{{ route('superadmin.users.create') }}?school_id={{ $school->id }}"
               class="btn btn-primary btn-sm rounded-3 font-semibold px-3 py-1.5 shadow-xs d-flex align-items-center gap-1" style="font-size: 12px;">
              <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon> Tambah User
            </a>
          </div>

          <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
              <thead>
                <tr class="border-bottom border-light-subtle">
                  <th width="40" class="ps-0 pb-2 text-center">#</th>
                  <th class="pb-2">NAMA</th>
                  <th class="pb-2">EMAIL</th>
                  <th class="pb-2">ROLE</th>
                  <th class="text-center pe-0 pb-2" width="100">AKSI</th>
                </tr>
              </thead>
              <tbody>
                @forelse($users as $i => $user)
                <tr class="border-bottom border-light-subtle">
                  <td class="ps-0 py-3 text-center text-muted font-semibold" style="font-size: 13px;">{{ $i + 1 }}</td>
                  <td class="py-3">
                    <div class="d-flex align-items-center gap-2.5">
                      <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center font-bold"
                           style="width:34px;height:34px;font-size:13px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                      </div>
                      <span class="fw-bold text-dark" style="font-size: 13.5px;">{{ $user->name }}</span>
                    </div>
                  </td>
                  <td class="py-3 text-muted font-medium" style="font-size: 12.5px;">{{ $user->email }}</td>
                  <td class="py-3">
                    @php
                      $roleLabel = ['admin' => ['bg-primary-subtle text-primary', 'Admin'],
                                    'guru'  => ['bg-info-subtle text-info', 'Guru'],
                                    'wali_murid' => ['bg-warning-subtle text-warning', 'Wali Murid']];
                      $r = $roleLabel[$user->role] ?? ['bg-secondary-subtle text-secondary', $user->role];
                    @endphp
                    <span class="badge {{ $r[0] }} rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px;">{{ $r[1] }}</span>
                  </td>
                  <td class="text-center py-3 pe-0">
                    <div class="d-flex justify-content-center gap-1">
                      <a href="{{ route('superadmin.users.edit', $user->id) }}"
                         class="btn btn-sm btn-warning text-dark rounded-3 p-1.5 shadow-xs d-inline-flex align-items-center justify-content-center" style="width:30px; height:30px;" title="Edit User">
                        <iconify-icon icon="solar:pen-bold-duotone" class="fs-5"></iconify-icon>
                      </a>
                      <form method="POST" action="{{ route('superadmin.users.destroy', $user->id) }}" class="d-inline"
                            onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger rounded-3 p-1.5 shadow-xs d-inline-flex align-items-center justify-content-center" style="width:30px; height:30px;" title="Hapus User">
                          <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-5"></iconify-icon>
                        </button>
                      </form>
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
                      <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada User</h6>
                      <p class="text-muted small mb-0">Belum ada user yang terdaftar di sekolah ini.</p>
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

  </div>

  {{-- ========================================================== --}}
  {{-- SUBTLE LOW-KEY FOOTER --}}
  {{-- ========================================================== --}}
  <footer class="text-center py-2 mt-2">
      <span class="text-muted" style="font-size: 11px;">© 2026 TK Ceria - Web TK Al-Hidayah v1.0</span>
  </footer>

</div>
@endsection