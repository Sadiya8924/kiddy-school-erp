@extends('layouts.superadmin')
@section('title', 'Tambah User')

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
            <h5 class="fw-semibold mb-0">Tambah User Baru</h5>
            <p class="text-muted small mb-0">Assign user ke sekolah tertentu.</p>
          </div>
        </div>

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif

        <form method="POST" action="{{ route('superadmin.users.store') }}">
          @csrf

          <div class="row g-3">

            <div class="col-12">
              <label class="form-label fw-semibold">Sekolah <span class="text-danger">*</span></label>
              <select name="school_id" class="form-select @error('school_id') is-invalid @enderror" required>
                <option value="">-- Pilih Sekolah --</option>
                @foreach($schools as $school)
                  {{-- Logika otomatis memilih sekolah berdasarkan Session Switcher di Navbar --}}
                  <option value="{{ $school->id }}"
                    {{ old('school_id', session('active_school_id')) == $school->id ? 'selected' : '' }}>
                    {{ $school->nama_sekolah }}
                  </option>
                @endforeach
              </select>
              @error('school_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name') }}" required>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email') }}" required>
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
              <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="kepsek" {{ old('role') === 'kepsek' ? 'selected' : '' }}>Kepala Sekolah</option>
                <option value="guru" {{ old('role') === 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="wali_murid" {{ old('role') === 'wali_murid' ? 'selected' : '' }}>Wali Murid</option>
              </select>
              @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                     placeholder="Minimal 6 karakter" required>
              @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

          </div>

          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary px-4">
              <i class="ti ti-user-plus me-1"></i> Tambah User
            </button>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-light px-4">Batal</a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection