@extends('layouts.superadmin')
@section('title', 'Tambah Sekolah')

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
            <h5 class="fw-semibold mb-0">Tambah Sekolah Baru</h5>
            <p class="text-muted small mb-0">Isi data sekolah yang akan didaftarkan ke sistem.</p>
          </div>
        </div>

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif

        <form method="POST" action="{{ route('superadmin.schools.store') }}" enctype="multipart/form-data">
          @csrf

          <div class="row g-3">

            <div class="col-12">
              <label class="form-label fw-semibold">Yayasan <span class="text-danger">*</span></label>
              <select name="yayasan_id" class="form-select @error('yayasan_id') is-invalid @enderror" required>
                <option value="">-- Pilih Yayasan --</option>
                @foreach($yayasans as $yayasan)
                  <option value="{{ $yayasan->id }}" {{ (old('yayasan_id', session('active_yayasan_id')) == $yayasan->id) ? 'selected' : '' }}>
                    {{ $yayasan->nama_yayasan }}
                  </option>
                @endforeach
              </select>
              @error('yayasan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-8">
              <label class="form-label fw-semibold">Nama Sekolah <span class="text-danger">*</span></label>
              <input type="text" name="nama_sekolah" class="form-control @error('nama_sekolah') is-invalid @enderror"
                     value="{{ old('nama_sekolah') }}" placeholder="Contoh: TK Al-Hidayah" required>
              @error('nama_sekolah')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">NPSN</label>
              <input type="text" name="npsn" class="form-control" value="{{ old('npsn') }}"
                     placeholder="Nomor Pokok Sekolah">
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Alamat</label>
              <textarea name="alamat" class="form-control" rows="2"
                        placeholder="Alamat lengkap sekolah">{{ old('alamat') }}</textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Kepala Sekolah</label>
              <input type="text" name="kepala_sekolah" class="form-control" value="{{ old('kepala_sekolah') }}"
                     placeholder="Nama kepala sekolah">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Telepon</label>
              <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}"
                     placeholder="08xxxxxxxxxx">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Tahun Ajaran Aktif</label>
              <input type="text" name="tahun_ajaran_aktif" class="form-control" value="{{ old('tahun_ajaran_aktif') }}"
                     placeholder="Contoh: 2025/2026">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Semester Aktif</label>
              <select name="semester_aktif" class="form-select">
                <option value="">-- Pilih --</option>
                <option value="Ganjil" {{ old('semester_aktif') === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ old('semester_aktif') === 'Genap' ? 'selected' : '' }}>Genap</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Logo Sekolah</label>
              <input type="file" name="logo_sekolah" class="form-control @error('logo_sekolah') is-invalid @enderror"
                     accept="image/*">
              @error('logo_sekolah')<div class="invalid-feedback">{{ $message }}</div>@enderror
              <small class="text-muted">Format: JPG/PNG, maks 2MB</small>
            </div>

          </div>

          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary px-4">
              <i class="ti ti-device-floppy me-1"></i> Simpan Sekolah
            </button>
            <a href="{{ route('superadmin.schools.index') }}" class="btn btn-light px-4">Batal</a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection