@extends('layouts.superadmin')
@section('title', 'Data Yayasan')

@section('content')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

  @if(session('success'))
    <div class="alert alert-success border border-success-subtle shadow-none rounded-3 py-2.5 px-3 mb-0 small fw-semibold d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-5 text-success"></iconify-icon>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- ========================================================== --}}
  {{-- 1. HERO WELCOME CARD WITH SOFT BLUE GRADIENT --}}
  {{-- ========================================================== --}}
  <div class="card border-0 shadow-sm overflow-hidden hero-welcome-card" style="background: var(--theme-hero-gradient, linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%)); border-radius: 16px !important;">
    <div class="card-body p-4 text-white">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        
        {{-- User Greeting & Context --}}
        <div class="d-flex align-items-center gap-3">
          <div class="position-relative flex-shrink-0">
            <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 52px; height: 52px; font-size: 24px; border: 2px solid rgba(255,255,255,0.4);">
              <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
            </div>
            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
              <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Data Yayasan 👋</h5>
              <span class="badge rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px; background: rgba(255, 255, 255, 0.2); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3);">
                Total: {{ $yayasans->total() }} Yayasan
              </span>
            </div>
            <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
              <span>Kelola semua yayasan yang terdaftar di platform ini</span>
            </p>
          </div>
        </div>

        {{-- Action Controls --}}
        <div class="d-flex align-items-center gap-2 flex-wrap ms-md-auto">
          <a href="{{ route('owner.yayasans.create') }}" class="btn btn-warning text-dark rounded-3 btn-sm px-3.5 py-2 fw-bold d-flex align-items-center gap-1.5 shadow-sm hover-scale" style="font-size: 12.5px; transition: transform 0.2s;">
            <iconify-icon icon="solar:add-square-bold-duotone" class="fs-5"></iconify-icon> + Tambah Yayasan
          </a>
        </div>

      </div>
    </div>
  </div>

  {{-- ========================================================== --}}
  {{-- 2. MAIN CARD & TABLE CONTAINER --}}
  {{-- ========================================================== --}}
  <div class="card border shadow-sm" style="border-top: 3.5px solid var(--primary-blue, #2563eb) !important; border-radius: 16px !important;">
    <div class="card-body p-3 p-md-4 d-flex flex-column">
      
      {{-- Toolbar: Search & Filter Tabs --}}
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-3.5 pb-3 border-bottom">
        
        {{-- Search Input --}}
        <div class="position-relative flex-grow-1" style="max-width: 380px;">
          <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></iconify-icon>
          <input type="text" id="searchYayasan" class="form-control form-control-sm rounded-3 ps-5 pe-3 py-2 bg-light border-light-subtle" placeholder="Cari nama, kode, atau alamat yayasan..." style="font-size: 13px;">
        </div>

        {{-- Status Filter Tabs --}}
        <div class="d-flex align-items-center gap-2 flex-wrap justify-content-between justify-content-md-end">
          <div class="p-1 bg-light rounded-3 border d-inline-flex gap-1" role="group" id="statusFilterGroup">
            <button type="button" class="btn btn-sm btn-primary rounded-2 px-3 filter-btn active" data-status="all" style="font-size: 12px; font-weight: 600; border-radius: 8px !important;">Semua</button>
            <button type="button" class="btn btn-sm btn-light border-0 rounded-2 px-3 filter-btn" data-status="aktif" style="font-size: 12px; font-weight: 600; color: #475569 !important; border-radius: 8px !important;">Aktif</button>
            <button type="button" class="btn btn-sm btn-light border-0 rounded-2 px-3 filter-btn" data-status="nonaktif" style="font-size: 12px; font-weight: 600; color: #475569 !important; border-radius: 8px !important;">Nonaktif</button>
          </div>
          <span class="text-muted small ms-1 d-none d-lg-inline" style="font-size: 12px;" id="recordCounter">
            Menampilkan {{ $yayasans->count() }} data
          </span>
        </div>

      </div>

      {{-- Desktop Table View (Hidden on mobile) --}}
      <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0" id="yayasanTable">
          <thead>
            <tr class="bg-light-subtle border-bottom border-light-subtle text-muted text-uppercase" style="font-size: 11px; letter-spacing: 0.05em; font-weight: 700;">
              <th width="45" class="ps-3 py-2.5">#</th>
              <th class="py-2.5">YAYASAN</th>
              <th class="py-2.5 text-center" width="100">KODE</th>
              <th class="py-2.5">PENANGGUNG JAWAB</th>
              <th class="py-2.5 text-center">JUMLAH TK</th>
              <th class="py-2.5 text-center">TOTAL SISWA</th>
              <th class="py-2.5 text-center" width="120">STATUS</th>
              <th class="text-start ps-4 py-2.5" width="240">AKSI</th>
            </tr>
          </thead>
          <tbody class="border-top-0">
            @forelse($yayasans as $i => $yayasan)
            <tr class="yayasan-row border-bottom border-light-subtle" data-status="{{ strtolower($yayasan->status) }}" data-search="{{ strtolower($yayasan->nama_yayasan . ' ' . $yayasan->kode_yayasan . ' ' . $yayasan->alamat . ' ' . $yayasan->penanggung_jawab) }}">
              <td class="ps-3 py-3.5 text-muted fw-medium" style="font-size: 13px;">{{ $yayasans->firstItem() + $i }}</td>
              <td class="py-3.5">
                <div class="d-flex align-items-center gap-3">
                  @if($yayasan->logo_yayasan)
                    <img src="{{ asset('storage/' . $yayasan->logo_yayasan) }}"
                         class="rounded-3 object-fit-cover shadow-xs border flex-shrink-0" width="42" height="42">
                  @else
                    <div class="rounded-3 d-flex align-items-center justify-content-center font-bold text-primary shadow-xs border border-primary-subtle flex-shrink-0"
                         style="width:42px; height:42px; background-color: #eff6ff; font-size: 14px;">
                      {{ strtoupper(substr($yayasan->nama_yayasan, 0, 2)) }}
                    </div>
                  @endif
                  <div>
                    <a href="{{ route('owner.yayasans.show', $yayasan->id) }}" class="fw-bold text-dark text-decoration-none hover-primary d-block mb-0.5" style="font-size: 13.5px; line-height: 1.3;">
                      {{ $yayasan->nama_yayasan }}
                    </a>
                    @if($yayasan->alamat)
                      <div class="text-muted d-flex align-items-center gap-1" style="font-size: 11.5px;">
                        <iconify-icon icon="solar:map-point-line-duotone" class="text-secondary flex-shrink-0"></iconify-icon>
                        <span class="text-truncate" style="max-width: 260px;">{{ Str::limit($yayasan->alamat, 45) }}</span>
                      </div>
                    @else
                      <small class="text-muted" style="font-size: 11.5px;">-</small>
                    @endif
                  </div>
                </div>
              </td>
              <td class="py-3.5 text-center">
                <span class="badge bg-light text-dark border font-monospace px-2.5 py-1 rounded-2 d-inline-block" style="font-size: 11px; letter-spacing: 0.5px;">
                  {{ $yayasan->kode_yayasan ?? '-' }}
                </span>
                <div class="mt-1.5 d-flex align-items-center justify-content-center gap-1">
                  <a href="{{ $yayasan->encrypted_login_url }}" target="_blank"
                     class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-2 py-1 rounded-2 d-inline-flex align-items-center gap-1 shadow-xs"
                     style="font-size: 10.5px;" title="Portal Login Terenkripsi (Aman dari tebakan URL)">
                    <iconify-icon icon="solar:lock-keyhole-minimalistic-bold-duotone" class="fs-6"></iconify-icon>
                    <span>Login Link 🔒</span>
                  </a>
                  <button type="button" onclick="copyEncryptedLink('{{ $yayasan->encrypted_login_url }}', '{{ $yayasan->nama_yayasan }}')"
                          class="btn btn-xs btn-light border p-1 rounded-2 d-inline-flex align-items-center justify-content-center text-muted shadow-none"
                          style="width: 22px; height: 22px;" title="Salin Link Login Terenkripsi">
                    <iconify-icon icon="solar:copy-bold-duotone" class="fs-6"></iconify-icon>
                  </button>
                </div>
              </td>
              <td class="py-3.5 text-dark font-medium" style="font-size: 13px;">
                @if($yayasan->penanggung_jawab)
                  <div class="d-flex align-items-center gap-1.5">
                    <iconify-icon icon="solar:user-bold-duotone" class="text-muted fs-6"></iconify-icon>
                    <span>{{ $yayasan->penanggung_jawab }}</span>
                  </div>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td class="py-3.5 text-center">
                <span class="badge bg-primary-subtle text-primary font-semibold rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" style="font-size: 11px;">
                  <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-6"></iconify-icon>
                  {{ $yayasan->schools_count }} TK
                </span>
              </td>
              <td class="py-3.5 text-center">
                <span class="badge font-semibold rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1" style="color: #6b21a8; background-color: #f3e8ff; border: 1px solid #d8b4fe; font-size: 11px;">
                  <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-6"></iconify-icon>
                  {{ $yayasan->total_siswa }} Siswa
                </span>
              </td>
              <td class="py-3.5 text-center">
                @if($yayasan->status === 'aktif')
                  <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 font-semibold d-inline-flex align-items-center gap-1" style="font-size: 11px;">
                    <span class="rounded-circle bg-success d-inline-block" style="width:6px; height:6px;"></span>
                    Aktif
                  </span>
                @else
                  <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 font-semibold d-inline-flex align-items-center gap-1" style="font-size: 11px;">
                    <span class="rounded-circle bg-danger d-inline-block" style="width:6px; height:6px;"></span>
                    Nonaktif
                  </span>
                @endif
              </td>
              <td class="py-3.5 ps-4 pe-3">
                <div class="d-flex align-items-center justify-content-start gap-2 flex-nowrap">
                  {{-- Detail Yayasan --}}
                  <a href="{{ route('owner.yayasans.show', $yayasan->id) }}"
                     class="btn btn-primary btn-sm px-2.5 py-1.5 font-semibold d-inline-flex align-items-center gap-1 shadow-xs" style="font-size: 11.5px; border-radius: 8px !important;" title="Lihat Profil Yayasan, TK & Kelas">
                    <iconify-icon icon="solar:buildings-bold-duotone" class="fs-5"></iconify-icon>
                    <span>Detail</span>
                  </a>

                  {{-- Switch Yayasan button --}}
                  <form method="POST" action="{{ route('owner.switch_yayasan') }}" class="m-0">
                    @csrf
                    <input type="hidden" name="active_yayasan_id" value="{{ session('active_yayasan_id') == $yayasan->id ? 0 : $yayasan->id }}">
                    <button type="submit" class="btn btn-sm {{ session('active_yayasan_id') == $yayasan->id ? 'btn-success' : 'btn-outline-primary' }} px-2.5 py-1.5 font-semibold d-inline-flex align-items-center gap-1" style="font-size: 11.5px; border-radius: 8px !important;"
                            title="{{ session('active_yayasan_id') == $yayasan->id ? 'Sedang Dipantau' : 'Pantau Yayasan Ini' }}">
                      <iconify-icon icon="{{ session('active_yayasan_id') == $yayasan->id ? 'solar:eye-bold-duotone' : 'solar:eye-line-duotone' }}" class="fs-5"></iconify-icon>
                      <span>{{ session('active_yayasan_id') == $yayasan->id ? 'Aktif' : 'Pantau' }}</span>
                    </button>
                  </form>

                  {{-- Edit button --}}
                  <a href="{{ route('owner.yayasans.edit', $yayasan->id) }}"
                     class="btn btn-light border btn-sm p-1.5 d-inline-flex align-items-center justify-content-center shadow-none hover-bg-warning-subtle" style="width: 32px; height: 32px; border-radius: 8px !important; color: #d97706;" title="Edit Yayasan">
                    <iconify-icon icon="solar:pen-bold-duotone" class="fs-5"></iconify-icon>
                  </a>

                  {{-- Delete button (If not ID 1) --}}
                  @if($yayasan->id !== 1)
                    <form method="POST" action="{{ route('owner.yayasans.destroy', $yayasan->id) }}" class="m-0"
                          onsubmit="return confirm('Yakin hapus yayasan {{ $yayasan->nama_yayasan }}?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-light border btn-sm p-1.5 text-danger d-inline-flex align-items-center justify-content-center shadow-none hover-bg-danger-subtle" style="width: 32px; height: 32px; border-radius: 8px !important;" title="Hapus Yayasan">
                        <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-5"></iconify-icon>
                      </button>
                    </form>
                  @else
                    <div style="width: 32px; height: 32px; flex-shrink: 0;"></div>
                  @endif
                </div>
              </td>
            </tr>
            @empty
            <tr id="emptyTableState">
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

      {{-- Mobile Card List View (Visible only on mobile/tablet screens < 768px) --}}
      <div class="d-md-none" id="mobileYayasanList">
        <div class="row g-3">
          @forelse($yayasans as $yayasan)
            <div class="col-12 yayasan-card-item" data-status="{{ strtolower($yayasan->status) }}" data-search="{{ strtolower($yayasan->nama_yayasan . ' ' . $yayasan->kode_yayasan . ' ' . $yayasan->alamat . ' ' . $yayasan->penanggung_jawab) }}">
              <div class="card border shadow-xs rounded-3 overflow-hidden mb-0">
                <div class="card-body p-3">
                  
                  {{-- Card Header: Logo, Name, Code & Status --}}
                  <div class="d-flex align-items-start justify-content-between gap-2 mb-2.5">
                    <div class="d-flex align-items-center gap-2.5">
                      @if($yayasan->logo_yayasan)
                        <img src="{{ asset('storage/' . $yayasan->logo_yayasan) }}"
                             class="rounded-3 object-fit-cover border shadow-xs flex-shrink-0" width="40" height="40">
                      @else
                        <div class="rounded-3 d-flex align-items-center justify-content-center font-bold text-primary shadow-xs border border-primary-subtle flex-shrink-0"
                             style="width:40px; height:40px; background-color: #eff6ff; font-size: 13px;">
                          {{ strtoupper(substr($yayasan->nama_yayasan, 0, 2)) }}
                        </div>
                      @endif
                      <div>
                        <a href="{{ route('owner.yayasans.show', $yayasan->id) }}" class="fw-bold text-dark text-decoration-none d-block" style="font-size: 14px; line-height: 1.3;">
                          {{ $yayasan->nama_yayasan }}
                        </a>
                        <div class="d-flex align-items-center gap-1.5 mt-1 flex-wrap">
                          <span class="badge bg-light text-dark border font-monospace px-2 py-0.5 rounded-2" style="font-size: 10px;">
                            {{ $yayasan->kode_yayasan ?? '-' }}
                          </span>
                          <a href="{{ $yayasan->encrypted_login_url }}" target="_blank" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-2 py-0.5 rounded-2 d-inline-flex align-items-center gap-1" style="font-size: 10px;" title="Link Login Terenkripsi">
                            <iconify-icon icon="solar:lock-keyhole-minimalistic-bold-duotone"></iconify-icon> Login Link 🔒
                          </a>
                        </div>
                      </div>
                    </div>
                    <div>
                      @if($yayasan->status === 'aktif')
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 font-semibold" style="font-size: 10.5px;">
                          Aktif
                        </span>
                      @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1 font-semibold" style="font-size: 10.5px;">
                          Nonaktif
                        </span>
                      @endif
                    </div>
                  </div>

                  {{-- Address --}}
                  @if($yayasan->alamat)
                    <div class="text-muted d-flex align-items-center gap-1.5 mb-3 small" style="font-size: 12px;">
                      <iconify-icon icon="solar:map-point-line-duotone" class="text-secondary flex-shrink-0"></iconify-icon>
                      <span>{{ $yayasan->alamat }}</span>
                    </div>
                  @endif

                  {{-- Info Pills Grid --}}
                  <div class="row g-2 mb-3 bg-light p-2.5 rounded-3 text-center border">
                    <div class="col-4 border-end">
                      <span class="text-muted d-block small mb-0.5" style="font-size: 10.5px;">Penanggung Jawab</span>
                      <strong class="text-dark d-block text-truncate" style="font-size: 12px;">{{ $yayasan->penanggung_jawab ?? '-' }}</strong>
                    </div>
                    <div class="col-4 border-end">
                      <span class="text-muted d-block small mb-0.5" style="font-size: 10.5px;">Jumlah TK</span>
                      <strong class="text-primary d-block" style="font-size: 12px;">{{ $yayasan->schools_count }} TK</strong>
                    </div>
                    <div class="col-4">
                      <span class="text-muted d-block small mb-0.5" style="font-size: 10.5px;">Total Siswa</span>
                      <strong style="color: #6b21a8; font-size: 12px;">{{ $yayasan->total_siswa }} Siswa</strong>
                    </div>
                  </div>

                  {{-- Card Action Buttons --}}
                  <div class="d-flex align-items-center gap-2 pt-1 border-top">
                    <a href="{{ route('owner.yayasans.show', $yayasan->id) }}" class="btn btn-primary btn-sm rounded-3 flex-grow-1 font-semibold d-flex align-items-center justify-content-center gap-1 py-1.5" style="font-size: 12px;">
                      <iconify-icon icon="solar:buildings-bold-duotone" class="fs-5"></iconify-icon> Detail
                    </a>

                    <form method="POST" action="{{ route('owner.switch_yayasan') }}" class="m-0 flex-grow-1">
                      @csrf
                      <input type="hidden" name="active_yayasan_id" value="{{ session('active_yayasan_id') == $yayasan->id ? 0 : $yayasan->id }}">
                      <button type="submit" class="btn btn-sm {{ session('active_yayasan_id') == $yayasan->id ? 'btn-success' : 'btn-outline-primary' }} rounded-3 w-100 font-semibold d-flex align-items-center justify-content-center gap-1 py-1.5" style="font-size: 12px;">
                        <iconify-icon icon="{{ session('active_yayasan_id') == $yayasan->id ? 'solar:eye-bold-duotone' : 'solar:eye-line-duotone' }}" class="fs-5"></iconify-icon>
                        {{ session('active_yayasan_id') == $yayasan->id ? 'Aktif' : 'Pantau' }}
                      </button>
                    </form>

                    <a href="{{ route('owner.yayasans.edit', $yayasan->id) }}" class="btn btn-light border btn-sm rounded-3 p-1.5 text-warning d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;" title="Edit">
                      <iconify-icon icon="solar:pen-bold-duotone" class="fs-5"></iconify-icon>
                    </a>

                    @if($yayasan->id !== 1)
                      <form method="POST" action="{{ route('owner.yayasans.destroy', $yayasan->id) }}" class="m-0" onsubmit="return confirm('Yakin hapus yayasan {{ $yayasan->nama_yayasan }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-light border btn-sm rounded-3 p-1.5 text-danger d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;" title="Hapus">
                          <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-5"></iconify-icon>
                        </button>
                      </form>
                    @endif
                  </div>

                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="text-center py-4 bg-light rounded-3 border">
                <p class="text-muted mb-0 small">Belum ada data yayasan.</p>
              </div>
            </div>
          @endforelse
        </div>
      </div>

      {{-- Live Search Empty Result Message --}}
      <div id="noSearchResults" class="text-center py-5 d-none">
        <iconify-icon icon="solar:magnifer-bug-bold-duotone" class="fs-1 text-muted mb-2"></iconify-icon>
        <h6 class="fw-bold text-dark mb-1">Tidak ada yayasan yang cocok</h6>
        <p class="text-muted small mb-0">Coba ubah kata kunci pencarian atau filter status Anda.</p>
      </div>

      {{-- Pagination --}}
      <div class="mt-4 pt-2 border-top">
        {{ $yayasans->links() }}
      </div>

    </div>
  </div>

</div>

{{-- Interactive Client-side Search & Filter JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('searchYayasan');
  const filterBtns = document.querySelectorAll('.filter-btn');
  const tableRows = document.querySelectorAll('.yayasan-row');
  const cardItems = document.querySelectorAll('.yayasan-card-item');
  const noResults = document.getElementById('noSearchResults');
  const recordCounter = document.getElementById('recordCounter');

  let currentSearch = '';
  let currentStatus = 'all';

  function filterItems() {
    let visibleCount = 0;

    // Filter Table Rows
    tableRows.forEach(row => {
      const matchSearch = row.dataset.search.includes(currentSearch);
      const matchStatus = currentStatus === 'all' || row.dataset.status === currentStatus;

      if (matchSearch && matchStatus) {
        row.classList.remove('d-none');
        visibleCount++;
      } else {
        row.classList.add('d-none');
      }
    });

    // Filter Mobile Cards
    cardItems.forEach(card => {
      const matchSearch = card.dataset.search.includes(currentSearch);
      const matchStatus = currentStatus === 'all' || card.dataset.status === currentStatus;

      if (matchSearch && matchStatus) {
        card.classList.remove('d-none');
      } else {
        card.classList.add('d-none');
      }
    });

    // Toggle Empty State
    if (visibleCount === 0 && (tableRows.length > 0 || cardItems.length > 0)) {
      noResults.classList.remove('d-none');
    } else {
      noResults.classList.add('d-none');
    }

    if (recordCounter) {
      recordCounter.textContent = `Menampilkan ${visibleCount} data`;
    }
  }

  // Live Search Input Event
  if (searchInput) {
    searchInput.addEventListener('input', function (e) {
      currentSearch = e.target.value.toLowerCase().trim();
      filterItems();
    });
  }

  // Status Filter Button Event
  filterBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      filterBtns.forEach(b => {
        b.classList.remove('btn-primary', 'active');
        b.classList.add('btn-light');
        b.style.color = '#475569';
        b.style.backgroundColor = 'transparent';
      });
      this.classList.remove('btn-light');
      this.classList.add('btn-primary', 'active');
      this.style.color = '#ffffff';
      this.style.backgroundColor = '#2563eb';

      currentStatus = this.dataset.status;
      filterItems();
    });
  });
});

function copyEncryptedLink(url, yayasanName) {
  if (navigator.clipboard && window.isSecureContext) {
    navigator.clipboard.writeText(url).then(function() {
      alert('Link login terenkripsi untuk ' + yayasanName + ' berhasil disalin!\n\nURL:\n' + url);
    });
  } else {
    const textArea = document.createElement("textarea");
    textArea.value = url;
    document.body.appendChild(textArea);
    textArea.select();
    try {
      document.execCommand('copy');
      alert('Link login terenkripsi untuk ' + yayasanName + ' berhasil disalin!\n\nURL:\n' + url);
    } catch (err) {
      prompt('Salin link login terenkripsi berikut:', url);
    }
    document.body.removeChild(textArea);
  }
}
</script>
@endsection
