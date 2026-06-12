@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex justify-content-between mb-4">
    <h3>Manajemen Template Rapor</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahTemplate">Tambah Template</button>
</div>

<div class="row">
    @foreach($templates as $tpl)
    <div class="col-md-4 mb-3">
        <div class="card {{ $tpl->is_active ? 'border-primary shadow' : 'border-light shadow-sm' }}">
            <div class="card-body">
                <h5>{{ $tpl->nama_template }}</h5>
                <div class="d-flex gap-2 mt-3">
                    <a href="/admin/template/{{ $tpl->id }}" class="btn btn-sm btn-info text-white">Atur Indikator</a>
                    
                    @if($tpl->is_active)
                        <span class="badge bg-success align-self-center px-3">Sedang Aktif</span>
                    @else
                        <form action="/admin/template/{{ $tpl->id }}/activate" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">Aktifkan</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="modal fade" id="tambahTemplate" tabindex="-1" aria-labelledby="tambahTemplateLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahTemplateLabel">Tambah Template Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="/admin/template" method="POST">
          @csrf
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label">Nama Template (Misal: Kurikulum Merdeka 2026)</label>
                  <input type="text" name="nama_template" class="form-control" required>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan Template</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection