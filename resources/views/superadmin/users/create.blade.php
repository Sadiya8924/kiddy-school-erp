@extends('layouts.superadmin')
@section('title', 'Tambah User')

@section('content')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="card border shadow-sm" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
        <div class="card-body p-4 p-md-4.5">

          <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-sm btn-light text-muted rounded-circle p-2 shadow-xs d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
              <iconify-icon icon="solar:alt-arrow-left-bold-duotone" class="fs-5"></iconify-icon>
            </a>
            <div>
              <h6 class="fw-bold text-dark mb-0.5" style="font-size: 16px;">Tambah User Baru</h6>
              <span class="text-muted font-medium" style="font-size: 12px;">Assign user ke sekolah atau yayasan tertentu</span>
            </div>
          </div>

          @if($errors->any())
            <div class="alert alert-danger border border-danger-subtle rounded-3 small mb-4">
              <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
          @endif

          <form method="POST" action="{{ route('superadmin.users.store') }}">
            @csrf

            <div class="row g-3">

              <div class="col-12">
                <label class="form-label font-semibold text-dark small mb-1">Sekolah <span class="text-muted font-normal" style="font-size: 11px;">(Pilih jika role berkaitan dengan sekolah tertentu)</span></label>
                <select name="school_id" class="form-select rounded-3 font-medium text-dark shadow-none @error('school_id') is-invalid @enderror" style="font-size: 13px;">
                  <option value="">-- Tanpa Sekolah / Mode Yayasan --</option>
                  @foreach($schools as $school)
                    <option value="{{ $school->id }}"
                      {{ old('school_id', session('active_school_id')) == $school->id ? 'selected' : '' }}>
                      {{ $school->nama_sekolah }}
                    </option>
                  @endforeach
                </select>
                @error('school_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label font-semibold text-dark small mb-1">Yayasan <span class="text-muted font-normal" style="font-size: 11px;">(Pilih jika Super Admin Yayasan)</span></label>
                <select name="yayasan_id" class="form-select rounded-3 font-medium text-dark shadow-none @error('yayasan_id') is-invalid @enderror" style="font-size: 13px;">
                  <option value="">-- Tanpa Yayasan / Mengikuti Sekolah --</option>
                  @foreach($yayasans as $yayasan)
                    <option value="{{ $yayasan->id }}"
                      {{ old('yayasan_id', session('active_yayasan_id')) == $yayasan->id ? 'selected' : '' }}>
                      {{ $yayasan->nama_yayasan }}
                    </option>
                  @endforeach
                </select>
                @error('yayasan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label font-semibold text-dark small mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control rounded-3 font-medium text-dark shadow-none @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required style="font-size: 13px;">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-6">
                <label class="form-label font-semibold text-dark small mb-1">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control rounded-3 font-medium text-dark shadow-none @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required style="font-size: 13px;">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-6">
                <label class="form-label font-semibold text-dark small mb-1">Role <span class="text-danger">*</span></label>
                <select name="role" class="form-select rounded-3 font-medium text-dark shadow-none @error('role') is-invalid @enderror" required style="font-size: 13px;">
                  <option value="">-- Pilih Role --</option>
                  @if(auth()->user()->isOwner())
                    <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Owner (Platform Master Admin)</option>
                  @endif
                  <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin (Admin Yayasan)</option>
                  <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin Sekolah</option>
                  <option value="kepsek" {{ old('role') === 'kepsek' ? 'selected' : '' }}>Kepala Sekolah</option>
                  <option value="guru" {{ old('role') === 'guru' ? 'selected' : '' }}>Guru</option>
                  <option value="wali_murid" {{ old('role') === 'wali_murid' ? 'selected' : '' }}>Wali Murid</option>
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label font-semibold text-dark small mb-1">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control rounded-3 font-medium text-dark shadow-none @error('password') is-invalid @enderror"
                       placeholder="Minimal 6 karakter" required style="font-size: 13px;">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

            </div>

            <div class="d-flex align-items-center gap-2 mt-4 pt-2 border-top">
              <button type="submit" class="btn btn-primary rounded-3 font-semibold px-4 py-2 shadow-xs d-inline-flex align-items-center gap-1.5" style="font-size: 12.5px;">
                <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-5"></iconify-icon> Tambah User
              </button>
              <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary rounded-3 font-semibold px-4 py-2" style="font-size: 12.5px;">Batal</a>
            </div>

          </form>
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