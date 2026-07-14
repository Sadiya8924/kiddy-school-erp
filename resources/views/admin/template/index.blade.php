@extends('layouts.admin')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    <div class="col-12">
        <div class="card border shadow-none mb-0" style="background-color: var(--primary-blue-soft); border-color: var(--primary-blue-border) !important;">
            <div class="card-body px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Manajemen Template Rapor</h4>
                    <p class="text-muted mb-0 small">Kelola struktur template yang dipakai saat pengisian dan cetak rapor.</p>
                </div>
                <button class="btn btn-primary rounded fw-bold shadow-none px-4" data-bs-toggle="modal" data-bs-target="#tambahTemplate">
                    <iconify-icon icon="solar:document-add-bold-duotone" class="align-middle me-1"></iconify-icon> Tambah Template
                </button>
            </div>
        </div>
    </div>

    @foreach($templates as $tpl)
    <div class="col-md-4">
        <div class="card border shadow-none h-100 {{ $tpl->is_active ? 'border-primary' : 'border-light-subtle' }}">
            <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: var(--primary-blue-soft); color: var(--primary-blue-dark);">
                            <iconify-icon icon="solar:document-text-bold-duotone" class="fs-4"></iconify-icon>
                        </div>
                        @if($tpl->is_active)
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">Sedang Aktif</span>
                        @endif
                    </div>

                    <h5 class="fw-bold text-dark mb-2">{{ $tpl->nama_template }}</h5>
                    <p class="text-muted small mb-0">Struktur utama rapor dan komponen isian untuk template ini.</p>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <a href="/admin/template/{{ $tpl->id }}" class="btn btn-sm btn-info text-white rounded fw-medium shadow-none px-3">Atur Indikator</a>
                    @if(!$tpl->is_active)
                        <form action="/admin/template/{{ $tpl->id }}/activate" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary rounded fw-medium shadow-none px-3">Aktifkan</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="modal fade" id="tambahTemplate" tabindex="-1" aria-labelledby="tambahTemplateLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
      <div class="modal-header bg-light border-bottom-0 px-4 py-3">
        <h5 class="modal-title fw-bold text-dark" id="tambahTemplateLabel">Tambah Template Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="/admin/template" method="POST">
          @csrf
          <div class="modal-body p-4">
              <div class="mb-3">
                  <label class="form-label fw-semibold text-dark">Nama Template</label>
                  <input type="text" name="nama_template" class="form-control shadow-none" placeholder="Misal: Kurikulum Merdeka 2026" required>
              </div>
          </div>
          <div class="modal-footer border-top-0 bg-light px-4 py-3">
              <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan Template</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection