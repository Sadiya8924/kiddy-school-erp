@extends('layouts.superadmin')
@section('title', 'Manajemen User')

@section('content')
<div class="row mt-4">
  <div class="col-12">
    <div class="card shadow-none border">
      <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h5 class="fw-semibold mb-1">Manajemen User</h5>
            <p class="text-muted small mb-0">Kelola semua user (admin, guru, wali murid) di semua sekolah.</p>
          </div>
          <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah User
          </a>
        </div>

        {{-- Filter by Role (SUDAH DIREVISI MENJADI 'kepsek') --}}
        <div class="d-flex gap-2 mb-3 flex-wrap">
          <a href="{{ route('superadmin.users.index') }}" class="btn btn-sm {{ !request('role') ? 'btn-primary' : 'btn-light' }}">Semua</a>
          <a href="{{ route('superadmin.users.index', ['role' => 'admin']) }}" class="btn btn-sm {{ request('role') === 'admin' ? 'btn-primary' : 'btn-light' }}">Admin</a>
          <a href="{{ route('superadmin.users.index', ['role' => 'kepsek']) }}" class="btn btn-sm {{ request('role') === 'kepsek' ? 'btn-primary' : 'btn-light' }}">Kepala Sekolah</a>
          <a href="{{ route('superadmin.users.index', ['role' => 'guru']) }}" class="btn btn-sm {{ request('role') === 'guru' ? 'btn-primary' : 'btn-light' }}">Guru</a>
          <a href="{{ route('superadmin.users.index', ['role' => 'wali_murid']) }}" class="btn btn-sm {{ request('role') === 'wali_murid' ? 'btn-primary' : 'btn-light' }}">Wali Murid</a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th width="40">#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Sekolah</th>
                <th>Dibuat</th>
                <th width="100">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $i => $user)
              <tr>
                <td>{{ $users->firstItem() + $i }}</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center fw-bold"
                         style="width:34px;height:34px;font-size:13px;color:#4f46e5;">
                      {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <span class="fw-semibold">{{ $user->name }}</span>
                  </div>
                </td>
                <td class="text-muted small">{{ $user->email }}</td>
                <td>
                  {{-- MAPPING ROLE (SUDAH DIREVISI) --}}
                  @php
                    $roleMap = [
                      'admin'      => 'bg-primary-subtle text-primary',
                      'guru'       => 'bg-info-subtle text-info',
                      'wali_murid' => 'bg-warning-subtle text-warning',
                      'kepsek'     => 'bg-success-subtle text-success',
                    ];
                    $roleLabel = [
                        'admin' => 'Admin', 
                        'guru' => 'Guru', 
                        'wali_murid' => 'Wali Murid', 
                        'kepsek' => 'Kepala Sekolah'
                    ];
                  @endphp
                  <span class="badge {{ $roleMap[$user->role] ?? 'bg-secondary-subtle text-secondary' }}">
                    {{ $roleLabel[$user->role] ?? $user->role }}
                  </span>
                </td>
                <td>
                  @if($user->school)
                    <span class="small fw-semibold">{{ $user->school->nama_sekolah }}</span>
                  @else
                    <span class="text-muted small">-</span>
                  @endif
                </td>
                <td class="text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                  <div class="d-flex gap-1">
                    <a href="{{ route('superadmin.users.edit', $user->id) }}"
                       class="btn btn-sm btn-warning" title="Edit"><i class="ti ti-pencil"></i></a>
                    <form method="POST" action="{{ route('superadmin.users.destroy', $user->id) }}"
                          onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" title="Hapus"><i class="ti ti-trash"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-5">
                  <i class="ti ti-users fs-1 d-block mb-2 text-muted"></i>
                  Belum ada user terdaftar.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $users->appends(request()->query())->links() }}
        </div>

      </div>
    </div>
  </div>
</div>
@endsection