@extends('layouts.superadmin')
@section('title', 'Manajemen User')

@section('content')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

  @if(session('success'))
    <div class="alert alert-success border border-success-subtle shadow-none rounded-3 py-2 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
      <div><i class="ti ti-check me-2"></i>{{ session('success') }}</div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- ========================================================== --}}
  {{-- 1. HERO WELCOME CARD WITH SOFT BLUE GRADIENT --}}
  {{-- ========================================================== --}}
  <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%); border-radius: 16px !important;">
    <div class="card-body p-4 text-white">
      <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
        
        {{-- User Greeting & Context --}}
        <div class="d-flex align-items-center gap-3">
          <div class="position-relative">
            <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid rgba(255,255,255,0.4);">
              <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
            </div>
            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
              <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Manajemen User Per Yayasan</h5>
            </div>
            <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
              <span>Pilih salah satu yayasan dibawah ini untuk mengelola user (Admin, Kepsek, Guru, &amp; Wali Murid)</span>
            </p>
          </div>
        </div>

        {{-- Action Controls --}}
        <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
          <a href="{{ route('superadmin.users.create') }}" class="btn btn-warning text-dark rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
            <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-5"></iconify-icon> + Tambah User Baru
          </a>
        </div>

      </div>
    </div>
  </div>

  @if(isset($showYayasanCards) && $showYayasanCards)
   

        <div class="row g-4">
          @forelse($yayasanCards as $cardYayasan)
            <div class="col-md-6 col-lg-4">
              <div class="card border shadow-sm h-100 card-clickable" style="border-radius: 16px !important; border-top: 3px solid #2563eb !important;">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                  <div>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div class="d-flex align-items-center gap-3">
                        @if($cardYayasan->logo_yayasan)
                          <img src="{{ asset('storage/' . $cardYayasan->logo_yayasan) }}" class="rounded object-fit-cover shadow-xs flex-shrink-0" width="44" height="44">
                        @else
                          <div class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold shadow-xs flex-shrink-0" style="width: 44px; height: 44px; font-size: 14px;">
                            {{ strtoupper(substr($cardYayasan->nama_yayasan, 0, 2)) }}
                          </div>
                        @endif
                        <div>
                          <h6 class="fw-bold text-dark mb-0" style="font-size: 15px;">{{ $cardYayasan->nama_yayasan }}</h6>
                          <span class="badge bg-slate-100 text-dark border font-semibold mt-1" style="font-size: 10.5px;">{{ $cardYayasan->kode_yayasan ?? '-' }}</span>
                        </div>
                      </div>
                      <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px;">{{ $cardYayasan->total_users }} Users</span>
                    </div>

                    {{-- Breakdown User Per Role --}}
                    <div class="p-3 bg-slate-50 rounded-3 mb-3 border border-slate-100" style="font-size: 12.5px;">
                      <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-muted"><iconify-icon icon="solar:user-bold-duotone" class="text-primary me-1 align-middle fs-5"></iconify-icon> Admin Sekolah:</span>
                        <span class="fw-bold text-dark">{{ $cardYayasan->count_admin }}</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-muted"><iconify-icon icon="solar:user-speak-bold-duotone" class="text-success me-1 align-middle fs-5"></iconify-icon> Kepala Sekolah:</span>
                        <span class="fw-bold text-dark">{{ $cardYayasan->count_kepsek }}</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-muted"><iconify-icon icon="solar:notebook-bold-duotone" class="text-info me-1 align-middle fs-5"></iconify-icon> Guru:</span>
                        <span class="fw-bold text-dark">{{ $cardYayasan->count_guru }}</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted"><iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="text-warning me-1 align-middle fs-5"></iconify-icon> Wali Murid:</span>
                        <span class="fw-bold text-dark">{{ $cardYayasan->count_ortu }}</span>
                      </div>
                    </div>
                  </div>

                  <form method="POST" action="{{ route('owner.switch_yayasan') }}">
                    @csrf
                    <input type="hidden" name="active_yayasan_id" value="{{ $cardYayasan->id }}">
                    <button type="submit" class="btn btn-outline-primary w-100 rounded-3 font-semibold py-2 d-flex align-items-center justify-content-center gap-1.5" style="font-size: 12.5px;">
                      <span>Kelola User Yayasan Ini</span>
                      <iconify-icon icon="solar:alt-arrow-right-line-duotone" class="fs-5"></iconify-icon>
                    </button>
                  </form>

                </div>
              </div>
            </div>
          @empty
            <div class="col-12 text-center py-5">
              <div class="py-3">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                  <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-2"></iconify-icon>
                </div>
                <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada Yayasan Terdaftar</h6>
                <p class="text-muted small mb-0">Tambahkan Yayasan baru terlebih dahulu untuk mengelola penggunanya.</p>
              </div>
            </div>
          @endforelse
        </div>
      </div>
    </div>

  {{-- MODE 2: TABEL USER DETAIL (DENGAN FILTER ROLE & YAYASAN) --}}
  @else
    <div class="card border shadow-sm" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
      <div class="card-body p-4 d-flex flex-column">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3.5 border-bottom pb-3">
          <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
              @if(auth()->user()->isOwner())
                <form method="POST" action="{{ route('owner.switch_yayasan') }}" class="m-0">
                  @csrf
                  <input type="hidden" name="active_yayasan_id" value="0">
                  <button type="submit" class="btn btn-sm btn-outline-secondary rounded-3 me-1 p-1 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Kembali ke Card Semua Yayasan">
                    <iconify-icon icon="solar:arrow-left-line-duotone" class="fs-5"></iconify-icon>
                  </button>
                </form>
              @endif
              <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 15px;">
                <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-primary fs-5"></iconify-icon> Daftar Pengguna System
              </h6>
              @if(isset($selectedYayasan) && $selectedYayasan)
                <span class="badge rounded-pill px-3 py-1 font-semibold" style="background-color: #f3e8ff; color: #7e22ce; font-size: 11px;">Yayasan: {{ $selectedYayasan->nama_yayasan }}</span>
              @endif
              @if(session('active_school_id'))
                @php $schAktif = \App\Models\School::find(session('active_school_id')); @endphp
                @if($schAktif)
                  <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 font-semibold" style="font-size: 11px;">TK: {{ $schAktif->nama_sekolah }}</span>
                @endif
              @endif
            </div>
            <p class="text-muted small mb-0 mt-1" style="font-size: 11.5px;">Kelola akun pengguna (Admin, Kepala Sekolah, Guru, &amp; Wali Murid).</p>
          </div>
          <div class="d-flex gap-2 flex-wrap">
            @if(auth()->user()->isOwner())
              @if(session('active_school_id'))
                <form method="POST" action="{{ route('superadmin.switch_school') }}" class="m-0">
                  @csrf
                  <input type="hidden" name="active_school_id" value="0">
                  <button type="submit" class="btn btn-outline-primary btn-sm rounded-3 font-semibold px-3 py-1.5 d-flex align-items-center gap-1" style="font-size: 12px;">
                    <iconify-icon icon="solar:refresh-circle-bold-duotone" class="fs-5"></iconify-icon> Reset Filter TK
                  </button>
                </form>
              @endif
              <form method="POST" action="{{ route('owner.switch_yayasan') }}" class="m-0">
                @csrf
                <input type="hidden" name="active_yayasan_id" value="0">
                <button type="submit" class="btn btn-outline-secondary btn-sm rounded-3 font-semibold px-3 py-1.5 d-flex align-items-center gap-1" style="font-size: 12px;">
                  <iconify-icon icon="solar:widget-3-bold-duotone" class="fs-5"></iconify-icon> Card Yayasan
                </button>
              </form>
            @endif
            <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary btn-sm rounded-3 font-semibold px-3 py-1.5 d-flex align-items-center gap-1 shadow-xs" style="font-size: 12px;">
              <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-5"></iconify-icon> Tambah User
            </a>
          </div>
        </div>

        {{-- Filter Dropdown Yayasan & Role --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
          
          {{-- Filter by Role Pills --}}
          <div class="d-flex gap-1.5 flex-wrap">
            <a href="{{ route('superadmin.users.index', array_merge(request()->query(), ['role' => null])) }}" class="btn btn-xs rounded-pill px-3 py-1.5 font-semibold {{ !request('role') ? 'btn-primary' : 'btn-light border text-muted' }}" style="font-size: 11.5px;">Semua Role</a>
            <a href="{{ route('superadmin.users.index', array_merge(request()->query(), ['role' => 'admin'])) }}" class="btn btn-xs rounded-pill px-3 py-1.5 font-semibold {{ request('role') === 'admin' ? 'btn-primary' : 'btn-light border text-muted' }}" style="font-size: 11.5px;">Admin Sekolah</a>
            <a href="{{ route('superadmin.users.index', array_merge(request()->query(), ['role' => 'kepsek'])) }}" class="btn btn-xs rounded-pill px-3 py-1.5 font-semibold {{ request('role') === 'kepsek' ? 'btn-primary' : 'btn-light border text-muted' }}" style="font-size: 11.5px;">Kepala Sekolah</a>
            <a href="{{ route('superadmin.users.index', array_merge(request()->query(), ['role' => 'guru'])) }}" class="btn btn-xs rounded-pill px-3 py-1.5 font-semibold {{ request('role') === 'guru' ? 'btn-primary' : 'btn-light border text-muted' }}" style="font-size: 11.5px;">Guru</a>
            <a href="{{ route('superadmin.users.index', array_merge(request()->query(), ['role' => 'wali_murid'])) }}" class="btn btn-xs rounded-pill px-3 py-1.5 font-semibold {{ request('role') === 'wali_murid' ? 'btn-primary' : 'btn-light border text-muted' }}" style="font-size: 11.5px;">Wali Murid</a>
            @if(auth()->user()->isOwner())
              <a href="{{ route('superadmin.users.index', array_merge(request()->query(), ['role' => 'super_admin'])) }}" class="btn btn-xs rounded-pill px-3 py-1.5 font-semibold {{ request('role') === 'super_admin' ? 'btn-primary' : 'btn-light border text-muted' }}" style="font-size: 11.5px;">Super Admin</a>
            @endif
          </div>

          {{-- Dropdown Switch Filter Yayasan untuk Owner --}}
          @if(auth()->user()->isOwner())
            <form method="POST" action="{{ route('owner.switch_yayasan') }}" class="d-flex align-items-center gap-2 m-0">
              @csrf
              <small class="text-muted font-semibold whitespace-nowrap" style="font-size: 11.5px;">Filter Yayasan:</small>
              <select name="active_yayasan_id" class="form-select form-select-sm rounded-3 border-purple-subtle shadow-none font-semibold" onchange="this.form.submit()" style="min-width: 180px; background-color: #f3e8ff; color: #7e22ce; font-size: 12px;">
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
          <table class="table table-borderless align-middle mb-0">
            <thead>
              <tr class="border-bottom border-light-subtle">
                <th width="40" class="ps-0 pb-2">#</th>
                <th class="pb-2">NAMA PENGGUNA</th>
                <th class="pb-2">EMAIL</th>
                <th class="pb-2">HAK AKSES / ROLE</th>
                <th class="pb-2">YAYASAN / SEKOLAH</th>
                <th class="pb-2">TANGGAL DIBUAT</th>
                <th class="text-end pe-0 pb-2" width="100">AKSI</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $i => $user)
              <tr class="border-bottom border-light-subtle">
                <td class="ps-0 py-3 text-muted font-semibold" style="font-size: 13px;">{{ $users->firstItem() + $i }}</td>
                <td class="py-3">
                  <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center font-semibold text-primary shadow-xs flex-shrink-0"
                         style="width:38px;height:38px;font-size:14px;background-color:#eff6ff;">
                      {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <span class="fw-bold text-dark" style="font-size: 14px;">{{ $user->name }}</span>
                  </div>
                </td>
                <td class="py-3 text-muted font-medium" style="font-size: 13px;">{{ $user->email }}</td>
                <td class="py-3">
                  @php
                    $roleMap = [
                      'owner'       => 'bg-danger-subtle text-danger font-semibold',
                      'super_admin' => 'bg-purple-subtle text-purple font-semibold',
                      'admin'       => 'bg-primary-subtle text-primary font-semibold',
                      'guru'        => 'bg-info-subtle text-info font-semibold',
                      'wali_murid'  => 'bg-warning-subtle text-warning font-semibold',
                      'kepsek'      => 'bg-success-subtle text-success font-semibold',
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
                  <span class="badge rounded-pill px-2.5 py-1 {{ $roleMap[$user->role] ?? 'bg-secondary-subtle text-secondary' }}" style="font-size: 11px;">
                    {{ $roleLabel[$user->role] ?? $user->role }}
                  </span>
                </td>
                <td class="py-3">
                  @if($user->school)
                    <span class="fw-semibold text-dark d-block" style="font-size: 13px;">{{ $user->school->nama_sekolah }}</span>
                    @if($user->school->yayasan)
                      <small class="text-muted d-block" style="font-size: 11px;">{{ $user->school->yayasan->nama_yayasan }}</small>
                    @endif
                  @elseif($user->yayasan)
                    <span class="fw-semibold text-purple d-block" style="font-size: 13px;">{{ $user->yayasan->nama_yayasan }}</span>
                  @else
                    <span class="text-muted small" style="font-size: 12px;">Platform Pusat</span>
                  @endif
                </td>
                <td class="py-3 text-muted font-medium" style="font-size: 12.5px;">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                <td class="py-3 text-end pe-0">
                  <div class="d-flex justify-content-end gap-1.5">
                    <a href="{{ route('superadmin.users.edit', $user->id) }}"
                       class="btn btn-outline-light border rounded-3 shadow-none text-muted p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                      <iconify-icon icon="solar:pen-bold-duotone" class="fs-5 text-warning"></iconify-icon>
                    </a>
                    <form method="POST" action="{{ route('superadmin.users.destroy', $user->id) }}" class="m-0"
                          onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-outline-light border rounded-3 shadow-none text-muted p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Hapus">
                        <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-5 text-danger"></iconify-icon>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center py-5">
                  <div class="py-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                      <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-2"></iconify-icon>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Tidak Ada User</h6>
                    <p class="text-muted small mb-0">Belum ada user terdaftar untuk kriteria filter ini.</p>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-4 pt-2 border-top">
          {{ $users->appends(request()->query())->links() }}
        </div>

      </div>
    </div>
  @endif

  {{-- ========================================================== --}}
  {{-- 3. SUBTLE LOW-KEY FOOTER --}}
  {{-- ========================================================== --}}
  <footer class="text-center py-2 mt-2">
    <span class="text-muted" style="font-size: 11px;">© 2026 TK Ceria Educational Management Platform • Internal Web System v1.0</span>
  </footer>

</div>
@endsection