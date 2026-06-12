@extends('layouts.admin')

@section('konten_halaman')
<div class="mb-4">
    <a href="{{ route('jurnal.index') }}" class="btn btn-light text-primary border rounded-pill px-4 shadow-sm mb-3">
        <iconify-icon icon="solar:alt-arrow-left-bold-duotone" class="align-middle me-1"></iconify-icon> Kembali ke Daftar Materi
    </a>
    <div class="d-flex align-items-center gap-3 bg-white p-4 rounded-4 shadow-sm border-start border-5 border-primary">
        <div>
            <h3 class="fw-bold text-dark mb-1">{{ $materi->judul_materi }}</h3>
            <div class="d-flex gap-3 text-muted small">
                <span><iconify-icon icon="solar:calendar-bold-duotone" class="align-middle"></iconify-icon> {{ \Carbon\Carbon::parse($materi->tanggal_dibuat)->translatedFormat('d F Y') }}</span>
                <span><iconify-icon icon="solar:black-hole-bold-duotone" class="align-middle"></iconify-icon> Kelas: {{ $kelas->nama_kelas }} (Kelompok {{ $kelas->kelompok }})</span>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
@endif

<div class="card w-100 border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold text-dark mb-4 border-bottom pb-3">Daftar Siswa & Pengisian Jurnal</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="20%">Nama Siswa</th>
                        <th width="45%">Deskripsi Kegiatan & Media</th>
                        <th width="20%">Catatan Khusus</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $index => $siswa)
                        @php 
                            $jurnal = $jurnals[$siswa->id] ?? null; 
                        @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                            <td class="fw-bold text-dark">{{ $siswa->nama_lengkap }}</td>
                            <td>
                                @if($jurnal)
                                    <span class="d-block small text-dark mb-2">{{ $jurnal->kegiatan }}</span>
                                    
                                    {{-- TOMBOL LIHAT MEDIA --}}
                                    <div class="d-flex gap-2 mt-1">
                                        @if($jurnal->foto)
                                            <a href="javascript:void(0)" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalLihatFoto{{ $jurnal->id }}">
                                                <span class="badge bg-info-subtle text-info small py-1 px-2" style="cursor: pointer;">
                                                    <iconify-icon icon="solar:gallery-bold-duotone" class="align-middle"></iconify-icon> Lihat Foto
                                                </span>
                                            </a>
                                        @endif
                                        @if($jurnal->video)
                                            <a href="javascript:void(0)" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalLihatVideo{{ $jurnal->id }}">
                                                <span class="badge bg-danger-subtle text-danger small py-1 px-2" style="cursor: pointer;">
                                                    <iconify-icon icon="solar:video-frame-bold-duotone" class="align-middle"></iconify-icon> Lihat Video
                                                </span>
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning">Belum diisi</span>
                                @endif
                            </td>
                            <td>
                                <span class="small text-muted fst-italic">{{ $jurnal->catatan ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm {{ $jurnal ? 'btn-outline-primary' : 'btn-primary' }} rounded-pill shadow-none px-3" data-bs-toggle="modal" data-bs-target="#modalJurnal{{ $siswa->id }}">
                                    <iconify-icon icon="solar:pen-new-round-bold-duotone"></iconify-icon> {{ $jurnal ? 'Edit' : 'Isi' }}
                                </button>

                                {{-- MODAL ISI JURNAL --}}
                                <div class="modal fade text-start" id="modalJurnal{{ $siswa->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 shadow">
                                            <form action="{{ route('jurnal.siswa.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="materi_id" value="{{ $materi->id }}">
                                                <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                                <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                                                
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title fw-bold text-white">Jurnal: {{ $siswa->nama_lengkap }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Deskripsi Kegiatan Anak <span class="text-danger">*</span></label>
                                                        <textarea name="kegiatan" class="form-control border-primary" rows="4" placeholder="Ceritakan bagaimana anak mengikuti materi ini..." required>{{ $jurnal->kegiatan ?? '' }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Catatan Khusus <span class="text-muted fw-normal">(Opsional)</span></label>
                                                        <textarea name="catatan" class="form-control" rows="2" placeholder="Anak sangat antusias, atau sedang rewel hari ini...">{{ $jurnal->catatan ?? '' }}</textarea>
                                                    </div>

                                                    <div class="row mt-3 border-top pt-3">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-bold">Upload Foto <span class="text-muted fw-normal">(Ops. Max 5MB)</span></label>
                                                            <input type="file" name="foto" class="form-control" accept="image/*">
                                                            @if($jurnal && $jurnal->foto)
                                                                <small class="text-success d-block mt-1"><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Tersimpan</small>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-bold">Upload Video <span class="text-muted fw-normal">(Ops. Max 20MB)</span></label>
                                                            <input type="file" name="video" class="form-control" accept="video/mp4,video/*">
                                                            @if($jurnal && $jurnal->video)
                                                                <small class="text-success d-block mt-1"><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Tersimpan</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light border-0">
                                                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                @if($jurnal)
                                    {{-- MODAL LIHAT FOTO --}}
                                    @if($jurnal->foto)
                                    <div class="modal fade" id="modalLihatFoto{{ $jurnal->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content border-0 bg-transparent shadow-none">
                                                <div class="modal-header border-0 pb-0 justify-content-end">
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: invert(1);"></button>
                                                </div>
                                                <div class="modal-body text-center p-0">
                                                    <img src="{{ asset('storage/' . $jurnal->foto) }}" class="img-fluid rounded-3 shadow" style="max-height: 80vh; object-fit: contain;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- MODAL LIHAT VIDEO --}}
                                    @if($jurnal->video)
                                    <div class="modal fade" id="modalLihatVideo{{ $jurnal->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content border-0 bg-dark shadow">
                                                <div class="modal-header border-secondary">
                                                    <h6 class="modal-title text-white">Video Kegiatan</h6>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center p-0">
                                                    <video controls class="w-100 rounded-bottom-3" style="max-height: 75vh;">
                                                        <source src="{{ asset('storage/' . $jurnal->video) }}" type="video/mp4">
                                                        Browser Anda tidak mendukung tag video.
                                                    </video>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada siswa di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection