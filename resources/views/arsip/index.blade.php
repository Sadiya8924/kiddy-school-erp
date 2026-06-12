@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-semibold text-dark mb-1">Arsip Dokumen Rapor</h2>
        <span class="text-muted fs-3">Kelola dan unduh hasil scan rapor siswa.</span>
    </div>
</div>

<div class="card w-100 border-0 shadow-sm position-relative overflow-hidden mb-4">
    <div class="card-body p-4">
        
        {{-- FORM MULTI FILTER --}}
        <form action="{{ route('arsip.index') }}" method="GET" class="row mb-4 align-items-end p-3 bg-light rounded-4 mx-0 border">
            
            @if(auth()->user()->role == 'admin')
            <div class="col-md-4 mb-3 mb-md-0">
                <label class="form-label fw-bold text-dark small mb-2">1. Filter Kelas</label>
                <select name="kelas_id" class="form-select border-1 text-dark shadow-none py-2 border-primary" onchange="this.form.submit()">
                    <option value="all">-- Semua Kelas --</option>
                    @foreach($list_kelas as $k)
                        <option value="{{ $k->id }}" {{ $filter_kelas == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="col-md-{{ auth()->user()->role == 'admin' ? '4' : '6' }} mb-3 mb-md-0">
                <label class="form-label fw-bold text-dark small mb-2">{{ auth()->user()->role == 'admin' ? '2.' : '1.' }} Filter Semester</label>
                <select name="semester_id" class="form-select border-1 text-dark shadow-none py-2 border-primary" onchange="this.form.submit()">
                    @foreach($semesters as $smt)
                        <option value="{{ $smt->id }}" {{ $filter_semester == $smt->id ? 'selected' : '' }}>
                            {{ $smt->tahun_ajaran }} - {{ $smt->jenis }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-{{ auth()->user()->role == 'admin' ? '4' : '6' }}">
                <label class="form-label fw-bold text-dark small mb-2">{{ auth()->user()->role == 'admin' ? '3.' : '2.' }} Status Arsip</label>
                <select name="status_arsip" class="form-select border-1 text-dark shadow-none py-2 border-primary" onchange="this.form.submit()">
                    <option value="all" {{ $status_arsip == 'all' ? 'selected' : '' }}>-- Tampilkan Semua --</option>
                    <option value="sudah" {{ $status_arsip == 'sudah' ? 'selected' : '' }}>✅ Sudah Diunggah</option>
                    <option value="belum" {{ $status_arsip == 'belum' ? 'selected' : '' }}>❌ Belum Diunggah</option>
                </select>
            </div>
        </form>

        {{-- TABEL DATA SISWA & ARSIP --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle border text-nowrap">
                <thead class="table-light border-bottom border-primary border-3">
                    <tr>
                        <th width="5%" class="text-center py-3">No</th>
                        <th width="30%" class="py-3">Identitas Siswa</th>
                        <th width="15%" class="py-3">Kelas</th>
                        <th width="35%" class="py-3">File Arsip (PDF/Gambar)</th>
                        @if(auth()->user()->role == 'admin')
                            <th width="15%" class="text-center py-3">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $index => $siswa)
                    <tr>
                        <td class="text-center fw-medium text-muted">{{ $index + 1 }}</td>
                        <td>
                            <h6 class="fw-bold mb-1 text-dark">{{ $siswa->nama_lengkap }}</h6>
                            <span class="text-muted small">NISN: {{ $siswa->nisn ?? '-' }} | No. Induk: {{ $siswa->no_induk ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill">
                                {{ $siswa->kelas->first()->nama_kelas ?? 'Belum ada kelas' }}
                            </span>
                        </td>
                        
                        {{-- DAFTAR FILE ARSIP --}}
                        <td>
                            @if($siswa->arsipRapors->count() > 0)
                                <div class="d-flex flex-column gap-2">
                                    @foreach($siswa->arsipRapors as $arsip)
                                        <div class="d-flex align-items-center justify-content-between bg-light border p-2 rounded-3">
                                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                <iconify-icon icon="solar:document-text-bold-duotone" class="text-danger fs-4"></iconify-icon>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ asset('storage/' . $arsip->file_path) }}" target="_blank" class="text-primary fw-semibold small text-truncate" style="max-width: 150px;" title="{{ $arsip->nama_file }}">
                                                        {{ $arsip->nama_file }}
                                                    </a>
                                                    <span class="text-muted" style="font-size: 10px;">Sem. {{ $arsip->semester->jenis }} ({{ $arsip->semester->tahun_ajaran }})</span>
                                                </div>
                                            </div>
                                            
                                            {{-- TOMBOL HAPUS (KHUSUS ADMIN) --}}
                                            @if(auth()->user()->role == 'admin')
                                                <form action="{{ route('arsip.destroy', $arsip->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus file ini secara permanen?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light text-danger p-1 rounded border" title="Hapus File">
                                                        <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted small fst-italic">Belum ada arsip rapor.</span>
                            @endif
                        </td>

                        {{-- TOMBOL UPLOAD (KHUSUS ADMIN) --}}
                        @if(auth()->user()->role == 'admin')
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-medium" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $siswa->id }}">
                                <iconify-icon icon="solar:upload-square-bold-duotone" class="align-middle me-1"></iconify-icon> Upload
                            </button>

                            {{-- MODAL UPLOAD PER SISWA --}}
                            <div class="modal fade text-start" id="uploadModal{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                            
                                            <div class="modal-header bg-light border-bottom-0">
                                                <h5 class="modal-title fw-bold text-dark">Upload Arsip Rapor</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="alert alert-info border-0 rounded-3 small">
                                                    <strong>Siswa:</strong> {{ $siswa->nama_lengkap }}<br>
                                                    <span class="text-muted">Pastikan file yang diunggah sudah benar (Maks 5MB. Format: PDF, JPG, PNG).</span>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-dark">Pilih Semester <span class="text-danger">*</span></label>
                                                    <select name="semester_id" class="form-select border-primary" required>
                                                        @foreach($semesters as $smt)
                                                            <option value="{{ $smt->id }}" {{ ($filter_semester == $smt->id) ? 'selected' : '' }}>
                                                                {{ $smt->tahun_ajaran }} - {{ $smt->jenis }} {{ ($semesterAktif && $semesterAktif->id == $smt->id) ? '(Aktif)' : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-dark">File Arsip <span class="text-danger">*</span></label>
                                                    <input type="file" name="file_arsip" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 bg-light">
                                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Unggah File</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Tidak ada data siswa ditemukan untuk filter ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
</div>
@endsection