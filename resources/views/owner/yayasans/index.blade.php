@extends('layouts.superadmin')
@section('title', 'Data Yayasan')

@section('content')
<div class="row mt-4">
  <div class="col-12">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-check me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card shadow-none border">
      <div class="card-body p-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h5 class="fw-semibold mb-1">Data Yayasan</h5>
            <p class="text-muted small mb-0">Kelola semua yayasan yang terdaftar di platform ini.</p>
          </div>
          <a href="{{ route('owner.yayasans.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah Yayasan
          </a>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th width="40">#</th>
                <th>Yayasan</th>
                <th>Kode</th>
                <th>Penanggung Jawab</th>
                <th>Jumlah TK</th>
                <th>Total Siswa</th>
                <th>Status</th>
                <th width="160">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($yayasans as $i => $yayasan)
              <tr>
                <td>{{ $yayasans->firstItem() + $i }}</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    @if($yayasan->logo_yayasan)
                      <img src="{{ asset('storage/' . $yayasan->logo_yayasan) }}"
                           class="rounded" width="36" height="36" style="object-fit:cover;">
                    @else
                      <div class="rounded bg-primary-subtle d-flex align-items-center justify-content-center"
                           style="width:36px;height:36px;">
                        <i class="ti ti-building text-primary"></i>
                      </div>
                    @endif
                    <div>
                      <p class="fw-semibold mb-0">{{ $yayasan->nama_yayasan }}</p>
                      <small class="text-muted">{{ Str::limit($yayasan->alamat, 40) ?? '-' }}</small>
                    </div>
                  </div>
                </td>
                <td><span class="badge bg-light text-dark border">{{ $yayasan->kode_yayasan ?? '-' }}</span></td>
                <td>{{ $yayasan->penanggung_jawab ?? '-' }}</td>
                <td><span class="badge bg-info-subtle text-info fw-semibold">{{ $yayasan->schools_count }} TK</span></td>
                <td><span class="badge fw-semibold" style="color: #6b21a8; background-color: #f3e8ff; border: 1px solid #d8b4fe;">{{ $yayasan->total_siswa }} Siswa</span></td>
                <td>
                  @if($yayasan->status === 'aktif')
                    <span class="badge bg-success-subtle text-success fw-semibold">Aktif</span>
                  @else
                    <span class="badge bg-danger-subtle text-danger fw-semibold">Nonaktif</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex gap-1">
                    {{-- Detail Yayasan (Profil + List TK & Kelas) --}}
                    <a href="{{ route('owner.yayasans.show', $yayasan->id) }}"
                       class="btn btn-sm btn-info text-white" title="Lihat Profil Yayasan, TK & Kelas">
                      <i class="ti ti-building-community"></i> Detail
                    </a>

                    {{-- Switch Yayasan button --}}
                    <form method="POST" action="{{ route('owner.switch_yayasan') }}">
                      @csrf
                      <input type="hidden" name="active_yayasan_id" value="{{ session('active_yayasan_id') == $yayasan->id ? 0 : $yayasan->id }}">
                      <button type="submit" class="btn btn-sm {{ session('active_yayasan_id') == $yayasan->id ? 'btn-success' : 'btn-outline-primary' }}"
                              title="{{ session('active_yayasan_id') == $yayasan->id ? 'Sedang Dipantau' : 'Pantau Yayasan Ini' }}">
                        <i class="ti ti-eye"></i> {{ session('active_yayasan_id') == $yayasan->id ? 'Aktif' : 'Pantau' }}
                      </button>
                    </form>

                    <a href="{{ route('owner.yayasans.edit', $yayasan->id) }}"
                       class="btn btn-sm btn-warning" title="Edit">
                      <i class="ti ti-pencil"></i>
                    </a>

                    @if($yayasan->id !== 1)
                      <form method="POST" action="{{ route('owner.yayasans.destroy', $yayasan->id) }}"
                            onsubmit="return confirm('Yakin hapus yayasan {{ $yayasan->nama_yayasan }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="Hapus">
                          <i class="ti ti-trash"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center text-muted py-5">
                  <i class="ti ti-building fs-1 d-block mb-2 text-muted"></i>
                  Belum ada yayasan terdaftar.
                  <br>
                  <a href="{{ route('owner.yayasans.create') }}" class="btn btn-sm btn-primary mt-2">
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
          {{ $yayasans->links() }}
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
