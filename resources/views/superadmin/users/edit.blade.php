@extends('layouts.superadmin')
@section('title', 'Edit User')

@section('content')
<div class="row mt-4 justify-content-center">
  <div class="col-lg-7">
    <div class="card shadow-none border">
      <div class="card-body p-4">

        <div class="d-flex align-items-center gap-2 mb-4">
          <a href="{{ route('superadmin.users.index') }}" class="btn btn-sm btn-light">
            <i class="ti ti-arrow-left"></i>
          </a>
          <div>
            <h5 class="fw-semibold mb-0">Edit User</h5>
            <p class="text-muted small mb-0">{{ $user->name }} — {{ $user->email }}</p>
          </div>
        </div>

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif

        <form method="POST" action="{{ route('superadmin.users.update', $user->id) }}">
          @csrf @method('PUT')

          <div class="row g-3">

            <div class="col-12">
              <label class="form-label fw-semibold">Sekolah <small class="text-muted">(Pilih jika role berkaitan dengan sekolah tertentu)</small></label>
              <select name="school_id" class="form-select @error('school_id') is-invalid @enderror">
                <option value="">-- Tanpa Sekolah / Mode Yayasan --</option>
                @foreach($schools as $school)
                  <option value="{{ $school->id }}"
                    {{ old('school_id', $user->school_id) == $school->id ? 'selected' : '' }}>
                    {{ $school->nama_sekolah }}
                  </option>
                @endforeach
              </select>
              @error('school_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Yayasan <small class="text-muted">(Pilih jika Super Admin Yayasan)</small></label>
              <select name="yayasan_id" class="form-select @error('yayasan_id') is-invalid @enderror">
                <option value="">-- Tanpa Yayasan / Mengikuti Sekolah --</option>
                @foreach($yayasans as $yayasan)
                  <option value="{{ $yayasan->id }}"
                    {{ old('yayasan_id', $user->yayasan_id) == $yayasan->id ? 'selected' : '' }}>
                    {{ $yayasan->nama_yayasan }}
                  </option>
                @endforeach
              </select>
              @error('yayasan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name', $user->name) }}" required>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email', $user->email) }}" required>
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
              <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                @if(auth()->user()->isOwner())
                  <option value="owner" {{ old('role', $user->role) === 'owner' ? 'selected' : '' }}>Owner (Platform Master Admin)</option>
                @endif
                <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin (Admin Yayasan)</option>
                <option value="admin"      {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin Sekolah</option>
                <option value="kepsek"     {{ old('role', $user->role) === 'kepsek' ? 'selected' : '' }}>Kepala Sekolah</option>
                <option value="guru"       {{ old('role', $user->role) === 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="wali_murid" {{ old('role', $user->role) === 'wali_murid' ? 'selected' : '' }}>Wali Murid</option>
              </select>
              @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Password Baru</label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                     placeholder="Kosongkan jika tidak ingin mengubah password">
              @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

          </div>

          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary px-4">
              <i class="ti ti-device-floppy me-1"></i> Update User
            </button>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-light px-4">Batal</a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection