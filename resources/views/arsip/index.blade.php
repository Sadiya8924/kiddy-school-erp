@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

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
                            <iconify-icon icon="solar:folder-with-files-bold-duotone"></iconify-icon>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h5 class="fw-bold mb-0 text-white" style="font-size: 18px; letter-spacing: -0.02em;">Arsip Dokumen Rapor</h5>
                        </div>
                        <p class="mb-0 text-white text-opacity-80 small d-flex align-items-center gap-2 flex-wrap" style="font-size: 12.5px;">
                            <span>Kelola dan unduh hasil scan rapor siswa.</span>
                        </p>
                    </div>
                </div>

                {{-- Status --}}
                <div class="d-flex align-items-center gap-2 flex-wrap ms-lg-auto">
                    <span class="badge bg-white text-primary font-semibold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1.5" style="font-size: 12px;">
                        <iconify-icon icon="solar:folder-with-files-bold-duotone" class="fs-5"></iconify-icon> Arsip tersusun rapi
                    </span>
                </div>

            </div>
        </div>
    </div>

    @include('partials.owner_stepped_filter')

    @if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))

    {{-- FILTER CARD --}}
    <div class="card border shadow-sm" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
        <div class="card-body p-3.5">
            <form action="{{ route('arsip.index') }}" method="GET" class="row g-2.5 align-items-center">
                @if(auth()->user()->role == 'admin')
                <div class="col-md-4">
                    <label class="form-label font-semibold text-dark small mb-1">Filter Kelas</label>
                    <select name="kelas_id" class="form-select rounded-3 font-medium shadow-none text-dark" onchange="this.form.submit()" style="font-size: 12.5px;">
                        <option value="all">-- Semua Kelas --</option>
                        @foreach($list_kelas as $k)
                            <option value="{{ $k->id }}" {{ $filter_kelas == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-{{ auth()->user()->role == 'admin' ? '4' : '6' }}">
                    <label class="form-label font-semibold text-dark small mb-1">Filter Semester</label>
                    <select name="semester_id" class="form-select rounded-3 font-medium shadow-none text-dark" onchange="this.form.submit()" style="font-size: 12.5px;">
                        @foreach($semesters as $smt)
                            <option value="{{ $smt->id }}" {{ $filter_semester == $smt->id ? 'selected' : '' }}>
                                {{ $smt->tahun_ajaran }} - {{ $smt->jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-{{ auth()->user()->role == 'admin' ? '4' : '6' }}">
                    <label class="form-label font-semibold text-dark small mb-1">Status Arsip</label>
                    <select name="status_arsip" class="form-select rounded-3 font-medium shadow-none text-dark" onchange="this.form.submit()" style="font-size: 12.5px;">
                        <option value="all" {{ $status_arsip == 'all' ? 'selected' : '' }}>-- Tampilkan Semua --</option>
                        <option value="sudah" {{ $status_arsip == 'sudah' ? 'selected' : '' }}>Sudah Diunggah</option>
                        <option value="belum" {{ $status_arsip == 'belum' ? 'selected' : '' }}>Belum Diunggah</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL ARSIP --}}
    <div class="card border shadow-sm" style="border-top: 3.5px solid #3b82f6 !important; border-radius: 16px !important;">
        <div class="card-body p-4 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
                <div>
                    <h6 class="fw-bold text-dark mb-0.5 d-flex align-items-center gap-2" style="font-size: 15px;">
                        <iconify-icon icon="solar:document-text-bold-duotone" class="text-primary fs-5"></iconify-icon> Data Arsip Rapor Siswa
                    </h6>
                    <span class="text-muted small" style="font-size: 11.5px;">Klik nama file untuk mengunduh atau melihat dokumen scan rapor</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr class="border-bottom border-light-subtle">
                            <th width="40" class="ps-0 pb-2 text-center">#</th>
                            <th class="pb-2">IDENTITAS SISWA</th>
                            <th class="pb-2">KELAS</th>
                            <th class="pb-2">FILE ARSIP (PDF/GAMBAR)</th>
                            @if(auth()->user()->role == 'admin')
                                <th class="text-center pe-0 pb-2" width="120">AKSI</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $index => $siswa)
                        <tr class="border-bottom border-light-subtle">
                            <td class="ps-0 py-3 text-center text-muted font-semibold" style="font-size: 13px;">{{ $index + 1 }}</td>
                            <td class="py-3">
                                <h6 class="fw-bold mb-0.5 text-dark" style="font-size: 14px;">{{ $siswa->nama_lengkap }}</h6>
                                <span class="text-muted font-medium" style="font-size: 11.5px;">NISN: {{ $siswa->nisn ?? '-' }} | No. Induk: {{ $siswa->no_induk ?? '-' }}</span>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1 font-semibold" style="font-size: 11px;">
                                    {{ $siswa->kelas->first()->nama_kelas ?? 'Belum ada kelas' }}
                                </span>
                            </td>
                            <td class="py-3">
                                @if($siswa->arsipRapors->count() > 0)
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($siswa->arsipRapors as $arsip)
                                            <div class="d-flex align-items-center justify-content-between bg-slate-50 border border-slate-100 p-2 rounded-3">
                                                <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                    <iconify-icon icon="solar:document-text-bold-duotone" class="text-danger fs-4 flex-shrink-0"></iconify-icon>
                                                    <div class="d-flex flex-column">
                                                        <a href="{{ asset('storage/' . $arsip->file_path) }}" target="_blank" class="text-primary font-semibold small text-truncate" style="max-width: 180px; font-size: 12px;" title="{{ $arsip->nama_file }}">
                                                            {{ $arsip->nama_file }}
                                                        </a>
                                                        <span class="text-muted" style="font-size: 10.5px;">Sem. {{ $arsip->semester->jenis }} ({{ $arsip->semester->tahun_ajaran }})</span>
                                                    </div>
                                                </div>

                                                @if(auth()->user()->role == 'admin')
                                                    <form action="{{ route('arsip.destroy', $arsip->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus file ini secara permanen?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-light text-danger p-1 rounded-3 border-0 shadow-none" title="Hapus File">
                                                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-5"></iconify-icon>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic" style="font-size: 11.5px;">Belum ada arsip rapor.</span>
                                @endif
                            </td>

                            @if(auth()->user()->role == 'admin')
                            <td class="text-center py-3 pe-0">
                                <button type="button" class="btn btn-sm btn-primary rounded-3 font-semibold px-3 py-1 shadow-xs" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $siswa->id }}">
                                    <iconify-icon icon="solar:upload-square-bold-duotone" class="fs-5 align-middle me-1"></iconify-icon> Upload
                                </button>

                                <div class="modal fade text-start" id="uploadModal{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow-lg">
                                            <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">

                                                <div class="modal-header bg-slate-50 border-bottom p-3.5">
                                                    <h6 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                                                        <iconify-icon icon="solar:upload-square-bold-duotone" class="text-primary fs-5"></iconify-icon> Upload Arsip Rapor
                                                    </h6>
                                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="alert alert-info border border-info-subtle rounded-3 small mb-3">
                                                        <strong>Siswa:</strong> {{ $siswa->nama_lengkap }}<br>
                                                        <span class="text-muted" style="font-size: 11px;">Pastikan file yang diunggah sudah benar (Maks 5MB. Format: PDF, JPG, PNG).</span>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label font-semibold text-dark small">Pilih Semester <span class="text-danger">*</span></label>
                                                        <select name="semester_id" class="form-select rounded-3 font-medium" required style="font-size: 13px;">
                                                            @foreach($semesters as $smt)
                                                                <option value="{{ $smt->id }}" {{ ($filter_semester == $smt->id) ? 'selected' : '' }}>
                                                                    {{ $smt->tahun_ajaran }} - {{ $smt->jenis }} {{ ($semesterAktif && $semesterAktif->id == $smt->id) ? '(Aktif)' : '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label font-semibold text-dark small">File Arsip <span class="text-danger">*</span></label>
                                                        <input type="file" name="file_arsip" class="form-control rounded-3" accept=".pdf,.jpg,.jpeg,.png" required style="font-size: 13px;">
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top p-3.5">
                                                    <button type="button" class="btn btn-outline-secondary rounded-3 font-semibold px-4 py-2" style="font-size: 12.5px;" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary rounded-3 font-semibold px-4 py-2 shadow-xs" style="font-size: 12.5px;">Unggah File</button>
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
                            <td colspan="5" class="text-center py-5">
                                <div class="py-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                                        <iconify-icon icon="solar:folder-with-files-bold-duotone" class="fs-2"></iconify-icon>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada Data Arsip</h6>
                                    <p class="text-muted small mb-0">Tidak ada data siswa / arsip ditemukan untuk kriteria filter ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ========================================================== --}}
    {{-- SUBTLE LOW-KEY FOOTER --}}
    {{-- ========================================================== --}}
    <footer class="text-center py-2 mt-2">
        <span class="text-muted" style="font-size: 11px;">© 2026 TK Ceria - Web TK Al-Hidayah v1.0</span>
    </footer>

</div>
@endsection