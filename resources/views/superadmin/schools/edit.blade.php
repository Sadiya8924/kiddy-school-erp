@extends('layouts.superadmin')
@section('title', 'Edit Sekolah')

@section('content')
<div class="row mt-4 justify-content-center">
  <div class="col-lg-8">
    <div class="card shadow-none border">
      <div class="card-body p-4">

        <div class="d-flex align-items-center gap-2 mb-4">
          <a href="{{ route('superadmin.schools.index') }}" class="btn btn-sm btn-light">
            <i class="ti ti-arrow-left"></i>
          </a>
          <div>
            <h5 class="fw-semibold mb-0">Edit Sekolah</h5>
            <p class="text-muted small mb-0">{{ $school->nama_sekolah }}</p>
          </div>
        </div>

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif

        <form method="POST" action="{{ route('superadmin.schools.update', $school->id) }}" enctype="multipart/form-data">
          @csrf @method('PUT')

          <div class="row g-3">

            <div class="col-md-8">
              <label class="form-label fw-semibold">Nama Sekolah <span class="text-danger">*</span></label>
              <input type="text" name="nama_sekolah" class="form-control @error('nama_sekolah') is-invalid @enderror"
                     value="{{ old('nama_sekolah', $school->nama_sekolah) }}" required>
              @error('nama_sekolah')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">NPSN</label>
              <input type="text" name="npsn" class="form-control" value="{{ old('npsn', $school->npsn) }}">
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Alamat</label>
              <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $school->alamat) }}</textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Kepala Sekolah</label>
              <input type="text" name="kepala_sekolah" class="form-control"
                     value="{{ old('kepala_sekolah', $school->kepala_sekolah) }}">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Telepon</label>
              <input type="text" name="telepon" class="form-control"
                     value="{{ old('telepon', $school->telepon) }}">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Tahun Ajaran Aktif</label>
              <input type="text" name="tahun_ajaran_aktif" class="form-control"
                     value="{{ old('tahun_ajaran_aktif', $school->tahun_ajaran_aktif) }}"
                     placeholder="Contoh: 2025/2026">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Semester Aktif</label>
              <select name="semester_aktif" class="form-select">
                <option value="">-- Pilih --</option>
                <option value="Ganjil" {{ old('semester_aktif', $school->semester_aktif) === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ old('semester_aktif', $school->semester_aktif) === 'Genap' ? 'selected' : '' }}>Genap</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" class="form-select" required>
                <option value="aktif" {{ old('status', $school->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status', $school->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Logo Sekolah</label>
              @if($school->logo_sekolah)
                <div class="mb-2">
                  <img src="{{ asset('storage/' . $school->logo_sekolah) }}" height="50" class="rounded border">
                  <small class="d-block text-muted mt-1">Logo saat ini. Upload baru untuk menggantinya.</small>
                </div>
              @endif
              <input type="file" name="logo_sekolah" class="form-control" accept="image/*">
              <small class="text-muted">Format: JPG/PNG, maks 2MB</small>
            </div>

          </div>

          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary px-4">
              <i class="ti ti-device-floppy me-1"></i> Update Sekolah
            </button>
            <a href="{{ route('superadmin.schools.index') }}" class="btn btn-light px-4">Batal</a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection