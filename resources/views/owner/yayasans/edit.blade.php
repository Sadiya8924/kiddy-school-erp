@extends('layouts.superadmin')
@section('title', 'Edit Yayasan')

@section('content')
<div class="row mt-4 justify-content-center">
  <div class="col-lg-8">
    <div class="card shadow-none border">
      <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
          <div>
            <h5 class="fw-semibold mb-1">Edit Data Yayasan</h5>
            <p class="text-muted small mb-0">Ubah informasi data yayasan {{ $yayasan->nama_yayasan }}.</p>
          </div>
          <a href="{{ route('owner.yayasans.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>

        <form action="{{ route('owner.yayasans.update', $yayasan->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label fw-medium">Nama Yayasan <span class="text-danger">*</span></label>
              <input type="text" name="nama_yayasan" class="form-control @error('nama_yayasan') is-invalid @enderror" 
                     value="{{ old('nama_yayasan', $yayasan->nama_yayasan) }}" required>
              @error('nama_yayasan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label class="form-label fw-medium">Kode Yayasan</label>
              <input type="text" name="kode_yayasan" class="form-control @error('kode_yayasan') is-invalid @enderror" 
                     value="{{ old('kode_yayasan', $yayasan->kode_yayasan) }}">
              @error('kode_yayasan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-medium">Penanggung Jawab / Ketua</label>
              <input type="text" name="penanggung_jawab" class="form-control @error('penanggung_jawab') is-invalid @enderror" 
                     value="{{ old('penanggung_jawab', $yayasan->penanggung_jawab) }}">
              @error('penanggung_jawab')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-medium">Telepon / Whatsapp</label>
              <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" 
                     value="{{ old('telepon', $yayasan->telepon) }}">
              @error('telepon')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12">
              <label class="form-label fw-medium">Alamat Lengkap</label>
              <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                        rows="3">{{ old('alamat', $yayasan->alamat) }}</textarea>
              @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-medium">Logo Yayasan</label>
              @if($yayasan->logo_yayasan)
                <div class="mb-2">
                  <img src="{{ asset('storage/' . $yayasan->logo_yayasan) }}" class="rounded border p-1" height="60">
                </div>
              @endif
              <input type="file" name="logo_yayasan" class="form-control @error('logo_yayasan') is-invalid @enderror" accept="image/*">
              <small class="text-muted">Upload file baru jika ingin mengganti logo.</small>
              @error('logo_yayasan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
              <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="aktif" {{ old('status', $yayasan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status', $yayasan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
              </select>
              @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('owner.yayasans.index') }}" class="btn btn-light">Batal</a>
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-device-floppy me-1"></i> Update Yayasan
            </button>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>
@endsection
