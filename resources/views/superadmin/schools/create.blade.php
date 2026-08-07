@extends('layouts.superadmin')
@section('title', 'Tambah Sekolah')

@section('content')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card border shadow-sm" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
        <div class="card-body p-4 p-md-4.5">

          <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <a href="{{ route('superadmin.schools.index') }}" class="btn btn-sm btn-light text-muted rounded-circle p-2 shadow-xs d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
              <iconify-icon icon="solar:alt-arrow-left-bold-duotone" class="fs-5"></iconify-icon>
            </a>
            <div>
              <h6 class="fw-bold text-dark mb-0.5" style="font-size: 16px;">Tambah Sekolah Baru</h6>
              <span class="text-muted font-medium" style="font-size: 12px;">Isi data sekolah yang akan didaftarkan ke sistem</span>
            </div>
          </div>

          @if($errors->any())
            <div class="alert alert-danger border border-danger-subtle rounded-3 small mb-4">
              <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
          @endif

          <form method="POST" action="{{ route('superadmin.schools.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">

              <div class="col-12">
                <label class="form-label font-semibold text-dark small mb-1">Yayasan <span class="text-danger">*</span></label>
                <select name="yayasan_id" class="form-select rounded-3 font-medium text-dark shadow-none @error('yayasan_id') is-invalid @enderror" required style="font-size: 13px;">
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
                <label class="form-label font-semibold text-dark small mb-1">Nama Sekolah <span class="text-danger">*</span></label>
                <input type="text" name="nama_sekolah" class="form-control rounded-3 font-medium text-dark shadow-none @error('nama_sekolah') is-invalid @enderror"
                       value="{{ old('nama_sekolah') }}" placeholder="Contoh: TK Al-Hidayah" required style="font-size: 13px;">
                @error('nama_sekolah')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-4">
                <label class="form-label font-semibold text-dark small mb-1">NPSN</label>
                <input type="text" name="npsn" class="form-control rounded-3 font-medium text-dark shadow-none" value="{{ old('npsn') }}"
                       placeholder="Nomor Pokok Sekolah" style="font-size: 13px;">
              </div>

              <div class="col-12">
                <label class="form-label font-semibold text-dark small mb-1">Alamat</label>
                <textarea name="alamat" class="form-control rounded-3 font-medium text-dark shadow-none" rows="2"
                          placeholder="Alamat lengkap sekolah" style="font-size: 13px;">{{ old('alamat') }}</textarea>
              </div>

              <div class="col-md-6">
                <label class="form-label font-semibold text-dark small mb-1">Kepala Sekolah</label>
                <input type="text" name="kepala_sekolah" class="form-control rounded-3 font-medium text-dark shadow-none" value="{{ old('kepala_sekolah') }}"
                       placeholder="Nama kepala sekolah" style="font-size: 13px;">
              </div>

              <div class="col-md-6">
                <label class="form-label font-semibold text-dark small mb-1">Telepon</label>
                <input type="text" name="telepon" class="form-control rounded-3 font-medium text-dark shadow-none" value="{{ old('telepon') }}"
                       placeholder="08xxxxxxxxxx" style="font-size: 13px;">
              </div>

              <div class="col-md-6">
                <label class="form-label font-semibold text-dark small mb-1">Tahun Ajaran Aktif</label>
                <input type="text" name="tahun_ajaran_aktif" class="form-control rounded-3 font-medium text-dark shadow-none" value="{{ old('tahun_ajaran_aktif') }}"
                       placeholder="Contoh: 2025/2026" style="font-size: 13px;">
              </div>

              <div class="col-md-6">
                <label class="form-label font-semibold text-dark small mb-1">Semester Aktif</label>
                <select name="semester_aktif" class="form-select rounded-3 font-medium text-dark shadow-none" style="font-size: 13px;">
                  <option value="">-- Pilih --</option>
                  <option value="Ganjil" {{ old('semester_aktif') === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                  <option value="Genap" {{ old('semester_aktif') === 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label font-semibold text-dark small mb-1">Status</label>
                <select name="status" class="form-select rounded-3 font-medium text-dark shadow-none @error('status') is-invalid @enderror" required style="font-size: 13px;">
                  <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                  <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label font-semibold text-dark small mb-1">Logo Sekolah</label>
                <input type="file" name="logo_sekolah" class="form-control rounded-3 font-medium text-dark shadow-none @error('logo_sekolah') is-invalid @enderror"
                       accept="image/*" style="font-size: 12.5px;">
                @error('logo_sekolah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <span class="text-muted" style="font-size: 11px;">Format: JPG/PNG, maks 2MB</span>
              </div>

            </div>

            <div class="d-flex align-items-center gap-2 mt-4 pt-2 border-top">
              <button type="submit" class="btn btn-primary rounded-3 font-semibold px-4 py-2 shadow-xs d-inline-flex align-items-center gap-1.5" style="font-size: 12.5px;">
                <iconify-icon icon="solar:diskette-bold-duotone" class="fs-5"></iconify-icon> Simpan Sekolah
              </button>
              <a href="{{ route('superadmin.schools.index') }}" class="btn btn-outline-secondary rounded-3 font-semibold px-4 py-2" style="font-size: 12.5px;">Batal</a>
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