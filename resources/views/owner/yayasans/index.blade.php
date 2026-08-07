@extends('layouts.superadmin')
@section('title', 'Data Yayasan')

@section('content')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

  @if(session('success'))
    <div class="alert alert-success border border-success-subtle shadow-none rounded-3 py-2 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
      <div><i class="ti ti-check me-2"></i>{{ session('success') }}</div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- ========================================================== --}}
  {{-- 1. HERO WELCOME CARD WITH SOFT BLUE GRADIENT --}}
  {{-- ========================================================== --}}
  <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%); border-radius: 16px !important;">
    <div class="card-body p-4 text-white">
      <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
        
        {{-- User Greeting & Context --}}
        <div class="d-flex align-items-center gap-3">
          <div class="position-relative">
            <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 22px; border: 2px solid rgba(255,255,255,0.4);">
              <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
            </div>
            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
              <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Data Yayasan 👋</h5>
            </div>
            <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
              <span>Kelola semua yayasan yang terdaftar di platform ini</span>
            </p>
          </div>
        </div>

        {{-- Action Controls --}}
        <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
          <a href="{{ route('owner.yayasans.create') }}" class="btn btn-warning text-dark rounded-3 btn-sm px-3 py-2 fw-semibold d-flex align-items-center gap-1.5 shadow-sm" style="font-size: 12px;">
            <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon> + Tambah Yayasan
          </a>
        </div>

      </div>
    </div>
  </div>

  <div class="card border shadow-sm" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
    <div class="card-body p-4 d-flex flex-column">
      <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
      </div>

      {{-- Table --}}
      <div class="table-responsive">
        <table class="table table-borderless align-middle mb-0">
          <thead>
            <tr class="border-bottom border-light-subtle">
              <th width="40" class="ps-0 pb-2">#</th>
              <th class="pb-2">YAYASAN</th>
              <th class="pb-2">KODE</th>
              <th class="pb-2">PENANGGUNG JAWAB</th>
              <th class="pb-2">JUMLAH TK</th>
              <th class="pb-2">TOTAL SISWA</th>
              <th class="pb-2">STATUS</th>
              <th class="text-end pe-0 pb-2" width="180">AKSI</th>
            </tr>
          </thead>
          <tbody>
            @forelse($yayasans as $i => $yayasan)
            <tr class="border-bottom border-light-subtle">
              <td class="ps-0 py-3 text-muted font-semibold" style="font-size: 13px;">{{ $yayasans->firstItem() + $i }}</td>
              <td class="py-3">
                <div class="d-flex align-items-center gap-3">
                  @if($yayasan->logo_yayasan)
                    <img src="{{ asset('storage/' . $yayasan->logo_yayasan) }}"
                         class="rounded object-fit-cover shadow-sm flex-shrink-0" width="40" height="40">
                  @else
                    <div class="rounded d-flex align-items-center justify-content-center fw-bold text-primary shadow-sm flex-shrink-0"
                         style="width:40px; height:40px; background-color: #eff6ff; font-size: 14px;">
                      {{ strtoupper(substr($yayasan->nama_yayasan, 0, 2)) }}
                    </div>
                  @endif
                  <div>
                    <p class="fw-bold text-dark mb-0" style="font-size: 14px;">{{ $yayasan->nama_yayasan }}</p>
                    <small class="text-muted" style="font-size: 12px;">{{ Str::limit($yayasan->alamat, 40) ?? '-' }}</small>
                  </div>
                </div>
              </td>
              <td class="py-3"><span class="badge bg-slate-100 text-dark border font-semibold px-2.5 py-1" style="font-size: 11px;">{{ $yayasan->kode_yayasan ?? '-' }}</span></td>
              <td class="py-3 text-dark fw-medium" style="font-size: 13px;">{{ $yayasan->penanggung_jawab ?? '-' }}</td>
              <td class="py-3"><span class="badge bg-primary-subtle text-primary font-semibold rounded-pill px-2.5 py-1" style="font-size: 11px;">{{ $yayasan->schools_count }} TK</span></td>
              <td class="py-3"><span class="badge font-semibold rounded-pill px-2.5 py-1" style="color: #6b21a8; background-color: #f3e8ff; border: 1px solid #d8b4fe; font-size: 11px;">{{ $yayasan->total_siswa }} Siswa</span></td>
              <td class="py-3">
                @if($yayasan->status === 'aktif')
                  <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1" style="font-size: 11px;">Aktif</span>
                @else
                  <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1" style="font-size: 11px;">Nonaktif</span>
                @endif
              </td>
              <td class="py-3 text-end pe-0">
                <div class="d-flex justify-content-end gap-1.5 align-items-center">
                  {{-- Detail Yayasan --}}
                  <a href="{{ route('owner.yayasans.show', $yayasan->id) }}"
                     class="btn btn-primary btn-sm rounded-3 px-2.5 py-1 font-semibold d-flex align-items-center gap-1" style="font-size: 11.5px;" title="Lihat Profil Yayasan, TK & Kelas">
                    <iconify-icon icon="solar:buildings-bold-duotone" class="fs-5"></iconify-icon> Detail
                  </a>

                  {{-- Switch Yayasan button --}}
                  <form method="POST" action="{{ route('owner.switch_yayasan') }}" class="m-0">
                    @csrf
                    <input type="hidden" name="active_yayasan_id" value="{{ session('active_yayasan_id') == $yayasan->id ? 0 : $yayasan->id }}">
                    <button type="submit" class="btn btn-sm {{ session('active_yayasan_id') == $yayasan->id ? 'btn-success' : 'btn-outline-primary' }} rounded-3 px-2.5 py-1 font-semibold d-flex align-items-center gap-1" style="font-size: 11.5px;"
                            title="{{ session('active_yayasan_id') == $yayasan->id ? 'Sedang Dipantau' : 'Pantau Yayasan Ini' }}">
                      <iconify-icon icon="{{ session('active_yayasan_id') == $yayasan->id ? 'solar:eye-bold-duotone' : 'solar:eye-line-duotone' }}" class="fs-5"></iconify-icon>
                      {{ session('active_yayasan_id') == $yayasan->id ? 'Aktif' : 'Pantau' }}
                    </button>
                  </form>

                  <a href="{{ route('owner.yayasans.edit', $yayasan->id) }}"
                     class="btn btn-outline-light border rounded-3 shadow-none text-muted p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                    <iconify-icon icon="solar:pen-bold-duotone" class="fs-5 text-warning"></iconify-icon>
                  </a>

                  @if($yayasan->id !== 1)
                    <form method="POST" action="{{ route('owner.yayasans.destroy', $yayasan->id) }}" class="m-0"
                          onsubmit="return confirm('Yakin hapus yayasan {{ $yayasan->nama_yayasan }}?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-outline-light border rounded-3 shadow-none text-muted p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Hapus">
                        <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-5 text-danger"></iconify-icon>
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center py-5">
                <div class="py-3">
                  <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                    <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-2"></iconify-icon>
                  </div>
                  <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada Yayasan Terdaftar</h6>
                  <p class="text-muted small mb-3">Silakan tambahkan data yayasan baru terlebih dahulu.</p>
                  <a href="{{ route('owner.yayasans.create') }}" class="btn btn-primary rounded-3 font-semibold px-4 py-2 shadow-xs" style="font-size: 12.5px;">
                    <iconify-icon icon="solar:add-square-bold-duotone" class="align-middle me-1 fs-5"></iconify-icon> Tambah Sekarang
                  </a>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      <div class="mt-4 pt-2 border-top">
        {{ $yayasans->links() }}
      </div>

    </div>
  </div>

  {{-- ========================================================== --}}
  {{-- 3. SUBTLE LOW-KEY FOOTER --}}
  {{-- ========================================================== --}}
  <footer class="text-center py-2 mt-2">
    <span class="text-muted" style="font-size: 11px;">© 2026 TK Ceria Educational Management Platform • Internal Web System v1.0</span>
  </footer>

</div>
@endsection
