@extends('layouts.superadmin')
@section('title', 'Tambah Yayasan')

@section('content')
<div class="row mt-4 justify-content-center">
  <div class="col-lg-8">
    <div class="card shadow-none border">
      <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
          <div>
            <h5 class="fw-semibold mb-1">Tambah Yayasan Baru</h5>
            <p class="text-muted small mb-0">Isi formulir di bawah ini untuk menambahkan yayasan baru.</p>
          </div>
          <a href="{{ route('owner.yayasans.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>

        <form action="{{ route('owner.yayasans.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label fw-medium">Nama Yayasan <span class="text-danger">*</span></label>
              <input type="text" name="nama_yayasan" class="form-control @error('nama_yayasan') is-invalid @enderror" 
                     value="{{ old('nama_yayasan') }}" placeholder="Contoh: Yayasan Pendidikan Ceria" required>
              @error('nama_yayasan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label class="form-label fw-medium">Kode Yayasan</label>
              <input type="text" name="kode_yayasan" class="form-control @error('kode_yayasan') is-invalid @enderror" 
                     value="{{ old('kode_yayasan') }}" placeholder="Contoh: YYS-002">
              @error('kode_yayasan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-medium">Penanggung Jawab / Ketua</label>
              <input type="text" name="penanggung_jawab" class="form-control @error('penanggung_jawab') is-invalid @enderror" 
                     value="{{ old('penanggung_jawab') }}" placeholder="Nama Ketua Yayasan">
              @error('penanggung_jawab')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-medium">Telepon / Whatsapp</label>
              <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" 
                     value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx">
              @error('telepon')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12">
              <label class="form-label fw-medium">Alamat Lengkap</label>
              <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                        rows="3" placeholder="Alamat kantor sekretariat yayasan">{{ old('alamat') }}</textarea>
              @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-medium">Logo Yayasan</label>
              <input type="file" name="logo_yayasan" class="form-control @error('logo_yayasan') is-invalid @enderror" accept="image/*">
              <small class="text-muted">Format: PNG, JPG, JPEG (Max 2MB)</small>
              @error('logo_yayasan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
              <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
              </select>
              @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('owner.yayasans.index') }}" class="btn btn-light">Batal</a>
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-device-floppy me-1"></i> Simpan Yayasan
            </button>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>
@endsection
