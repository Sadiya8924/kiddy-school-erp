@extends('layouts.superadmin')
@section('title', 'Manajemen User')

@section('content')
<div class="row mt-4">

  @if(session('success'))
    <div class="col-12 mb-3">
      <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
        <i class="ti ti-check me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  {{-- MODE 1: CARD PER-YAYASAN KHUSUS OWNER (JIKA BELUM PILIH YAYASAN) --}}
  @if(isset($showYayasanCards) && $showYayasanCards)
    <div class="col-12 mb-3">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border shadow-sm">
        <div>
          <h5 class="fw-bold text-dark mb-1">Manajemen User Per Yayasan</h5>
          <p class="text-muted small mb-0">Pilih salah satu Yayasan di bawah ini untuk mengelola user (Admin, Kepsek, Guru, & Wali Murid).</p>
        </div>
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary rounded-pill px-4">
          <i class="ti ti-user-plus me-1"></i> Tambah User Baru
        </a>
      </div>
    </div>

    <div class="col-12">
      <div class="row gy-4">
        @forelse($yayasanCards as $cardYayasan)
          <div class="col-md-6 col-lg-4">
            <div class="card border shadow-none h-100 card-clickable">
              <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-2">
                      @if($cardYayasan->logo_yayasan)
                        <img src="{{ asset('storage/' . $cardYayasan->logo_yayasan) }}" class="rounded-circle border" width="45" height="45" style="object-fit: cover;">
                      @else
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                          <i class="ti ti-building fs-4"></i>
                        </div>
                      @endif
                      <div>
                        <h6 class="fw-bold text-dark mb-0 fs-5">{{ $cardYayasan->nama_yayasan }}</h6>
                        <small class="text-muted">{{ $cardYayasan->kode_yayasan ?? '-' }}</small>
                      </div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1">{{ $cardYayasan->total_users }} Users</span>
                  </div>

                  {{-- Breakdown User Per Role --}}
                  <div class="p-3 bg-light rounded-3 mb-3" style="font-size: 13px;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <span class="text-muted"><i class="ti ti-user-star text-primary me-1"></i> Admin Sekolah:</span>
                      <span class="fw-bold text-dark">{{ $cardYayasan->count_admin }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <span class="text-muted"><i class="ti ti-school text-success me-1"></i> Kepala Sekolah:</span>
                      <span class="fw-bold text-dark">{{ $cardYayasan->count_kepsek }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <span class="text-muted"><i class="ti ti-chalkboard text-info me-1"></i> Guru:</span>
                      <span class="fw-bold text-dark">{{ $cardYayasan->count_guru }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="text-muted"><i class="ti ti-users text-warning me-1"></i> Wali Murid:</span>
                      <span class="fw-bold text-dark">{{ $cardYayasan->count_ortu }}</span>
                    </div>
                  </div>
                </div>

                <form method="POST" action="{{ route('owner.switch_yayasan') }}">
                  @csrf
                  <input type="hidden" name="active_yayasan_id" value="{{ $cardYayasan->id }}">
                  <button type="submit" class="btn btn-outline-primary w-100 rounded-pill fw-medium d-flex align-items-center justify-content-center gap-1">
                    <span>Kelola User Yayasan Ini</span>
                    <i class="ti ti-arrow-right"></i>
                  </button>
                </form>

              </div>
            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="card border shadow-none text-center py-5">
              <div class="card-body">
                <i class="ti ti-building fs-1 text-muted mb-2 d-block"></i>
                <h6 class="fw-semibold text-dark">Belum Ada Yayasan</h6>
                <p class="text-muted small">Tambahkan Yayasan baru terlebih dahulu untuk mengelola penggunanya.</p>
              </div>
            </div>
          </div>
        @endforelse
      </div>
    </div>

  {{-- MODE 2: TABEL USER DETAIL (DENGAN FILTER ROLE & YAYASAN) --}}
  @else
    <div class="col-12">
      <div class="card shadow-none border">
        <div class="card-body p-4">

          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 border-bottom pb-3">
            <div>
              <div class="d-flex align-items-center gap-2 flex-wrap">
                @if(auth()->user()->isOwner())
                  <form method="POST" action="{{ route('owner.switch_yayasan') }}" class="m-0">
                    @csrf
                    <input type="hidden" name="active_yayasan_id" value="0">
                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle me-1" title="Kembali ke Card Semua Yayasan">
                      <i class="ti ti-arrow-left"></i>
                    </button>
                  </form>
                @endif
                <h5 class="fw-semibold mb-0">Manajemen User</h5>
                @if(isset($selectedYayasan) && $selectedYayasan)
                  <span class="badge bg-purple-subtle text-purple rounded-pill px-3 py-1 fw-bold" style="background-color: #f3e8ff; color: #7e22ce;">Yayasan: {{ $selectedYayasan->nama_yayasan }}</span>
                @endif
                @if(session('active_school_id'))
                  @php $schAktif = \App\Models\School::find(session('active_school_id')); @endphp
                  @if($schAktif)
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold">TK: {{ $schAktif->nama_sekolah }}</span>
                  @endif
                @endif
              </div>
              <p class="text-muted small mb-0 mt-1">Kelola daftar user (Admin, Kepala Sekolah, Guru, Wali Murid).</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
              @if(auth()->user()->isOwner())
                @if(session('active_school_id'))
                  <form method="POST" action="{{ route('superadmin.switch_school') }}" class="m-0">
                    @csrf
                    <input type="hidden" name="active_school_id" value="0">
                    <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill fw-medium">
                      <i class="ti ti-arrow-left me-1"></i> Reset Filter TK
                    </button>
                  </form>
                @endif
                <form method="POST" action="{{ route('owner.switch_yayasan') }}" class="m-0">
                  @csrf
                  <input type="hidden" name="active_yayasan_id" value="0">
                  <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill fw-medium">
                    <i class="ti ti-grid-dots me-1"></i> Kembali ke Card Yayasan
                  </button>
                </form>
              @endif
              <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-medium">
                <i class="ti ti-plus me-1"></i> Tambah User
              </a>
            </div>
          </div>

          {{-- Filter Dropdown Yayasan & Role --}}
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            
            {{-- Filter by Role Pills --}}
            <div class="d-flex gap-2 flex-wrap">
              <a href="{{ route('superadmin.users.index', array_merge(request()->query(), ['role' => null])) }}" class="btn btn-sm {{ !request('role') ? 'btn-primary' : 'btn-light' }}">Semua Role</a>
              <a href="{{ route('superadmin.users.index', array_merge(request()->query(), ['role' => 'admin'])) }}" class="btn btn-sm {{ request('role') === 'admin' ? 'btn-primary' : 'btn-light' }}">Admin Sekolah</a>
              <a href="{{ route('superadmin.users.index', array_merge(request()->query(), ['role' => 'kepsek'])) }}" class="btn btn-sm {{ request('role') === 'kepsek' ? 'btn-primary' : 'btn-light' }}">Kepala Sekolah</a>
              <a href="{{ route('superadmin.users.index', array_merge(request()->query(), ['role' => 'guru'])) }}" class="btn btn-sm {{ request('role') === 'guru' ? 'btn-primary' : 'btn-light' }}">Guru</a>
              <a href="{{ route('superadmin.users.index', array_merge(request()->query(), ['role' => 'wali_murid'])) }}" class="btn btn-sm {{ request('role') === 'wali_murid' ? 'btn-primary' : 'btn-light' }}">Wali Murid</a>
              @if(auth()->user()->isOwner())
                <a href="{{ route('superadmin.users.index', array_merge(request()->query(), ['role' => 'super_admin'])) }}" class="btn btn-sm {{ request('role') === 'super_admin' ? 'btn-primary' : 'btn-light' }}">Super Admin (Yayasan)</a>
              @endif
            </div>

            {{-- Dropdown Switch Filter Yayasan untuk Owner --}}
            @if(auth()->user()->isOwner())
              <form method="POST" action="{{ route('owner.switch_yayasan') }}" class="d-flex align-items-center gap-2">
                @csrf
                <small class="text-muted fw-semibold whitespace-nowrap">Filter Yayasan:</small>
                <select name="active_yayasan_id" class="form-select form-select-sm border-purple-subtle shadow-none fw-semibold" onchange="this.form.submit()" style="min-width: 180px; background-color: #f3e8ff; color: #7e22ce;">
                  <option value="0">-- Semua Yayasan --</option>
                  @foreach(\App\Models\Yayasan::where('status', 'aktif')->get() as $yys)
                    <option value="{{ $yys->id }}" {{ session('active_yayasan_id') == $yys->id ? 'selected' : '' }}>
                      {{ $yys->nama_yayasan }}
                    </option>
                  @endforeach
                </select>
              </form>
            @endif

          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th width="40">#</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Yayasan / Sekolah</th>
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
                    @php
                      $roleMap = [
                        'owner'       => 'bg-danger-subtle text-danger fw-bold',
                        'super_admin' => 'bg-purple-subtle text-purple fw-bold',
                        'admin'       => 'bg-primary-subtle text-primary',
                        'guru'        => 'bg-info-subtle text-info',
                        'wali_murid'  => 'bg-warning-subtle text-warning',
                        'kepsek'      => 'bg-success-subtle text-success',
                      ];
                      $roleLabel = [
                        'owner'       => 'Owner Platform',
                        'super_admin' => 'Super Admin (Yayasan)',
                        'admin'       => 'Admin Sekolah', 
                        'guru'        => 'Guru', 
                        'wali_murid'  => 'Wali Murid', 
                        'kepsek'      => 'Kepala Sekolah'
                      ];
                    @endphp
                    <span class="badge {{ $roleMap[$user->role] ?? 'bg-secondary-subtle text-secondary' }}">
                      {{ $roleLabel[$user->role] ?? $user->role }}
                    </span>
                  </td>
                  <td>
                    @if($user->school)
                      <span class="small fw-semibold text-dark">{{ $user->school->nama_sekolah }}</span>
                      @if($user->school->yayasan)
                        <br><small class="text-muted">{{ $user->school->yayasan->nama_yayasan }}</small>
                      @endif
                    @elseif($user->yayasan)
                      <span class="small fw-semibold text-purple">{{ $user->yayasan->nama_yayasan }}</span>
                    @else
                      <span class="text-muted small">Platform Pusat</span>
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
                    Belum ada user terdaftar untuk filter ini.
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
  @endif

</div>
@endsection