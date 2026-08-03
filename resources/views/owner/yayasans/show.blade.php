@extends('layouts.superadmin')
@section('title', 'Detail Yayasan - ' . $yayasan->nama_yayasan)

@section('content')
<div class="row mt-4 gy-4">

  @if(session('success'))
    <div class="col-12">
      <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
        <i class="ti ti-check me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  {{-- TOP HEADER BAR --}}
  <div class="col-12">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-3 rounded-3 border shadow-sm">
      <div class="d-flex align-items-center gap-3">
        <a href="{{ route('owner.yayasans.index') }}" class="btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
          <i class="ti ti-arrow-left fs-5"></i>
        </a>
        <div>
          <h5 class="fw-bold text-dark mb-0">{{ $yayasan->nama_yayasan }}</h5>
          <small class="text-muted">Kode: <span class="fw-semibold text-primary">{{ $yayasan->kode_yayasan ?? '-' }}</span> | Penanggung Jawab: {{ $yayasan->penanggung_jawab ?? '-' }}</small>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <form method="POST" action="{{ route('owner.switch_yayasan') }}">
          @csrf
          <input type="hidden" name="active_yayasan_id" value="{{ session('active_yayasan_id') == $yayasan->id ? 0 : $yayasan->id }}">
          <button type="submit" class="btn {{ session('active_yayasan_id') == $yayasan->id ? 'btn-success' : 'btn-outline-primary' }} rounded-pill px-3 py-2 btn-sm fw-medium d-flex align-items-center gap-1">
            <i class="ti ti-eye fs-5"></i> {{ session('active_yayasan_id') == $yayasan->id ? 'Sedang Dipantau' : 'Pantau Yayasan Ini' }}
          </button>
        </form>
        <a href="{{ route('superadmin.schools.create') }}?yayasan_id={{ $yayasan->id }}" class="btn btn-primary rounded-pill px-3 py-2 btn-sm fw-medium d-flex align-items-center gap-1">
          <i class="ti ti-plus fs-5"></i> Tambah TK Baru
        </a>
      </div>
    </div>
  </div>

  {{-- KIRI: PROFIL YAYASAN (Col-4) --}}
  <div class="col-lg-4">
    <div class="card border shadow-none mb-4 sticky-top" style="top: 90px;">
      <div class="card-body p-4">
        <div class="text-center pb-3 border-bottom mb-3">
          @if($yayasan->logo_yayasan)
            <img src="{{ asset('storage/' . $yayasan->logo_yayasan) }}" class="rounded-circle mb-3 border p-1 shadow-sm" width="90" height="90" style="object-fit: cover;">
          @else
            <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 90px; height: 90px;">
              <i class="ti ti-building fs-1"></i>
            </div>
          @endif
          <h5 class="fw-bold text-dark mb-1">{{ $yayasan->nama_yayasan }}</h5>
          <span class="badge {{ $yayasan->status == 'aktif' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3 py-1 fw-semibold">
            {{ strtoupper($yayasan->status) }}
          </span>
        </div>

        {{-- Ringkasan Statistik Yayasan --}}
        <div class="row text-center g-2 mb-4">
          <div class="col-4">
            <div class="p-2 rounded bg-light border">
              <h6 class="fw-bold mb-0 text-primary">{{ $schools->count() }}</h6>
              <small class="text-muted" style="font-size: 11px;">Total TK</small>
            </div>
          </div>
          <div class="col-4">
            <div class="p-2 rounded bg-light border">
              <h6 class="fw-bold mb-0 text-purple" style="color: #7e22ce;">{{ $totalKelas }}</h6>
              <small class="text-muted" style="font-size: 11px;">Total Kelas</small>
            </div>
          </div>
          <div class="col-4">
            <div class="p-2 rounded bg-light border">
              <h6 class="fw-bold mb-0 text-success">{{ $totalSiswa }}</h6>
              <small class="text-muted" style="font-size: 11px;">Total Siswa</small>
            </div>
          </div>
        </div>

        {{-- Detail Informasi --}}
        <h6 class="fw-bold text-dark mb-3">Informasi Yayasan</h6>
        <ul class="list-unstyled mb-4" style="font-size: 13px;">
          <li class="d-flex align-items-center gap-2 mb-2 text-muted">
            <i class="ti ti-id-badge fs-5 text-primary"></i>
            <span>Kode: <strong class="text-dark">{{ $yayasan->kode_yayasan ?? '-' }}</strong></span>
          </li>
          <li class="d-flex align-items-center gap-2 mb-2 text-muted">
            <i class="ti ti-user-check fs-5 text-primary"></i>
            <span>Ketua: <strong class="text-dark">{{ $yayasan->penanggung_jawab ?? '-' }}</strong></span>
          </li>
          <li class="d-flex align-items-center gap-2 mb-2 text-muted">
            <i class="ti ti-phone fs-5 text-primary"></i>
            <span>Telepon: <strong class="text-dark">{{ $yayasan->telepon ?? '-' }}</strong></span>
          </li>
          <li class="d-flex align-items-start gap-2 mb-2 text-muted">
            <i class="ti ti-map-pin fs-5 text-primary mt-1"></i>
            <span>Alamat: <strong class="text-dark">{{ $yayasan->alamat ?? '-' }}</strong></span>
          </li>
        </ul>

        {{-- Tombol Edit Profil Yayasan --}}
        <button type="button" class="btn btn-warning w-100 rounded-pill fw-medium d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#modalEditYayasan">
          <i class="ti ti-pencil"></i> Edit Profil Yayasan
        </button>

      </div>
    </div>
  </div>

  {{-- KANAN: LIST TK & DATA KELAS (Col-8) --}}
  <div class="col-lg-8">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="fw-bold text-dark mb-0 fs-5">Daftar TK & Data Kelas</h6>
      <span class="text-muted small">Menampilkan {{ $schools->count() }} TK di bawah {{ $yayasan->nama_yayasan }}</span>
    </div>

    @forelse($schools as $school)
      <div class="card border shadow-none mb-3">
        <div class="card-body p-4">
          
          {{-- Header Card TK --}}
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 border-bottom pb-3 mb-3">
            <div class="d-flex align-items-center gap-3">
              @if($school->logo_sekolah)
                <img src="{{ asset('storage/' . $school->logo_sekolah) }}" class="rounded" width="48" height="48" style="object-fit: cover;">
              @else
                <div class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                  <i class="ti ti-building-school fs-3"></i>
                </div>
              @endif
              <div>
                <h6 class="fw-bold text-dark mb-1 fs-5">{{ $school->nama_sekolah }}</h6>
                <div class="d-flex flex-wrap align-items-center gap-2 text-muted small">
                  <span>NPSN: <strong>{{ $school->npsn ?? '-' }}</strong></span>
                  <span>•</span>
                  <span>Kepsek: <strong>{{ $school->kepala_sekolah ?? '-' }}</strong></span>
                  <span>•</span>
                  <span>TA: <strong>{{ $school->tahun_ajaran_aktif ?? '-' }} ({{ $school->semester_aktif ?? '-' }})</strong></span>
                </div>
              </div>
            </div>
            <div class="d-flex align-items-center gap-1">
              <span class="badge {{ $school->status == 'aktif' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-2.5 py-1 me-2" style="font-size: 11px;">
                {{ ucfirst($school->status) }}
              </span>
              
              <a href="{{ route('superadmin.schools.modules', $school->id) }}" class="btn btn-sm btn-outline-info" title="Kelola Modul Fitur TK">
                <i class="ti ti-settings me-1"></i> Modul
              </a>
              <a href="{{ route('superadmin.schools.edit', $school->id) }}" class="btn btn-sm btn-outline-warning" title="Edit TK">
                <i class="ti ti-pencil"></i>
              </a>
              <form method="POST" action="{{ route('superadmin.schools.destroy', $school->id) }}" onsubmit="return confirm('Yakin hapus sekolah {{ $school->nama_sekolah }} beserta semua datanya?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" title="Hapus TK">
                  <i class="ti ti-trash"></i>
                </button>
              </form>
            </div>
          </div>

          {{-- Section Data Kelas di bawah TK ini --}}
          <div class="bg-light p-3 rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="fw-bold text-dark small text-uppercase"><i class="ti ti-chalkboard me-1 text-primary"></i> Data Kelas ({{ $school->kelases->count() }})</span>
              <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $school->siswas_count }} Siswa Terdaftar</span>
            </div>

            @if($school->kelases->count() > 0)
              <div class="table-responsive">
                <table class="table table-sm table-borderless align-middle mb-0 bg-white rounded border">
                  <thead class="table-light border-bottom">
                    <tr>
                      <th class="ps-3">Nama Kelas</th>
                      <th>Kelompok / Program</th>
                      <th>Guru / Wali Kelas</th>
                      <th class="text-center">Jumlah Siswa</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($school->kelases as $kelas)
                      <tr>
                        <td class="ps-3 fw-semibold text-dark">{{ $kelas->nama_kelas }}</td>
                        <td><span class="badge bg-light text-secondary border">{{ $kelas->kelompok }}</span></td>
                        <td>
                          @if($kelas->guru)
                            <small class="fw-medium text-dark"><i class="ti ti-user-check text-success me-1"></i> {{ $kelas->guru->name }}</small>
                          @else
                            <small class="text-muted italic">Belum ditentukan</small>
                          @endif
                        </td>
                        <td class="text-center">
                          <span class="badge bg-info-subtle text-info fw-bold rounded-pill px-2.5 py-1">{{ $kelas->total_siswa }} Siswa</span>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center text-muted py-3 border rounded bg-white" style="font-size: 13px;">
                <i class="ti ti-info-circle me-1"></i> Belum ada data kelas di TK ini.
              </div>
            @endif

          </div>

        </div>
      </div>
    @empty
      <div class="card border shadow-none text-center py-5">
        <div class="card-body">
          <i class="ti ti-building-school fs-1 text-muted mb-2 d-block"></i>
          <h6 class="fw-semibold text-dark">Belum Ada TK Terdaftar di Yayasan Ini</h6>
          <p class="text-muted small mb-3">Tambahkan TK baru yang dinaungi oleh {{ $yayasan->nama_yayasan }}.</p>
          <a href="{{ route('superadmin.schools.create') }}?yayasan_id={{ $yayasan->id }}" class="btn btn-primary btn-sm rounded-pill px-4">
            <i class="ti ti-plus me-1"></i> Tambah TK Pertama
          </a>
        </div>
      </div>
    @endforelse

  </div>

</div>

{{-- MODAL EDIT PROFIL YAYASAN --}}
<div class="modal fade" id="modalEditYayasan" tabindex="-1" aria-labelledby="modalEditYayasanLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold" id="modalEditYayasanLabel"><i class="ti ti-pencil text-warning me-1"></i> Edit Profil Yayasan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('owner.yayasans.update', $yayasan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label fw-semibold">Nama Yayasan <span class="text-danger">*</span></label>
              <input type="text" name="nama_yayasan" class="form-control" value="{{ old('nama_yayasan', $yayasan->nama_yayasan) }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Kode Yayasan</label>
              <input type="text" name="kode_yayasan" class="form-control" value="{{ old('kode_yayasan', $yayasan->kode_yayasan) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Penanggung Jawab / Ketua</label>
              <input type="text" name="penanggung_jawab" class="form-control" value="{{ old('penanggung_jawab', $yayasan->penanggung_jawab) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Telepon / Whatsapp</label>
              <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $yayasan->telepon) }}">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Alamat Lengkap</label>
              <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $yayasan->alamat) }}</textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Logo Yayasan</label>
              <input type="file" name="logo_yayasan" class="form-control" accept="image/*">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
              <select name="status" class="form-select" required>
                <option value="aktif" {{ old('status', $yayasan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status', $yayasan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
