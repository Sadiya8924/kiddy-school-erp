@extends('layouts.superadmin')
@section('title', 'Data Sekolah')

@section('content')
<div class="row mt-4">
  <div class="col-12">
    <div class="card shadow-none border">
      <div class="card-body p-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h5 class="fw-semibold mb-1">Data Sekolah</h5>
            <p class="text-muted small mb-0">Kelola semua sekolah yang terdaftar di sistem.</p>
          </div>
          <a href="{{ route('superadmin.schools.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah Sekolah
          </a>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th width="40">#</th>
                <th>Sekolah</th>
                <th>NPSN</th>
                <th>Kepala Sekolah</th>
                <th>Tahun Ajaran</th>
                <th>Telepon</th>
                <th>Status</th>
                <th width="120">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($schools as $i => $school)
              <tr>
                <td>{{ $schools->firstItem() + $i }}</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    @if($school->logo_sekolah)
                      <img src="{{ asset('storage/' . $school->logo_sekolah) }}"
                           class="rounded" width="36" height="36" style="object-fit:cover;">
                    @else
                      <div class="rounded bg-primary-subtle d-flex align-items-center justify-content-center"
                           style="width:36px;height:36px;">
                        <i class="ti ti-building-school text-primary"></i>
                      </div>
                    @endif
                    <div>
                      <p class="fw-semibold mb-0">{{ $school->nama_sekolah }}</p>
                      <small class="text-muted">{{ Str::limit($school->alamat, 40) ?? '-' }}</small>
                    </div>
                  </div>
                </td>
                <td>{{ $school->npsn ?? '-' }}</td>
                <td>{{ $school->kepala_sekolah ?? '-' }}</td>
                <td>
                  @if($school->tahun_ajaran_aktif)
                    {{ $school->tahun_ajaran_aktif }}
                    @if($school->semester_aktif)
                      <br><small class="text-muted">{{ $school->semester_aktif }}</small>
                    @endif
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>{{ $school->telepon ?? '-' }}</td>
                <td>
                  @if($school->status === 'aktif')
                    <span class="badge bg-success-subtle text-success fw-semibold">Aktif</span>
                  @else
                    <span class="badge bg-danger-subtle text-danger fw-semibold">Nonaktif</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex gap-1">
                    <a href="{{ route('superadmin.schools.modules', $school->id) }}"
                       class="btn btn-sm btn-info text-white" title="Kelola Fitur Sekolah">
                      <i class="ti ti-settings"></i>
                    </a>

                    <a href="{{ route('superadmin.schools.show', $school->id) }}"
                       class="btn btn-sm btn-light" title="Lihat Detail">
                      <i class="ti ti-eye"></i>
                    </a>
                    <a href="{{ route('superadmin.schools.edit', $school->id) }}"
                       class="btn btn-sm btn-warning" title="Edit">
                      <i class="ti ti-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('superadmin.schools.destroy', $school->id) }}"
                          onsubmit="return confirm('Yakin hapus sekolah {{ $school->nama_sekolah }}? Semua data siswa, rapor, dll akan ikut terhapus!')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" title="Hapus">
                        <i class="ti ti-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center text-muted py-5">
                  <i class="ti ti-building-school fs-1 d-block mb-2 text-muted"></i>
                  Belum ada sekolah terdaftar.
                  <br>
                  <a href="{{ route('superadmin.schools.create') }}" class="btn btn-sm btn-primary mt-2">
                    Tambah Sekarang
                  </a>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
          {{ $schools->links() }}
        </div>

      </div>
    </div>
  </div>
</div>
@endsection