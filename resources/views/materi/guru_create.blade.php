@extends('layouts.admin')

@section('konten_halaman')
<div class="mb-4">
    <a href="{{ route('materi.guru.index') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-muted mb-2 hover-primary">
        <iconify-icon icon="solar:arrow-left-linear"></iconify-icon> Kembali ke Daftar Materi
    </a>
    <h3 class="fw-bold text-dark mb-1">Unggah Materi Baru</h3>
    <p class="text-muted mb-0">Materi yang diunggah akan masuk ke antrean persetujuan Kepala Sekolah.</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('materi.guru.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Materi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-secondary" name="judul" required placeholder="Contoh: Pengenalan Huruf Hijaiyah">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Mata Pelajaran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-secondary" name="mata_pelajaran" required placeholder="Contoh: Pendidikan Agama Islam">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kelas/Target <span class="text-danger">*</span></label>
                            <select class="form-select border-secondary" name="kelas" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($list_kelas as $kls)
                                    <option value="{{ $kls->nama_kelas }} (Kelompok {{ $kls->kelompok }})">
                                        Kelas {{ $kls->nama_kelas }} - Kelompok {{ $kls->kelompok }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Tambahan</label>
                        <textarea class="form-control border-secondary" name="deskripsi" rows="4" placeholder="Tuliskan petunjuk atau rangkuman singkat dari materi ini..."></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">File Materi <span class="text-muted fw-normal">(Opsional)</span></label>
                        <input type="file" class="form-control border-secondary" name="file">
                        <small class="text-muted mt-1 d-block">Format didukung: PDF, DOCX, PPTX, JPG, PNG, MP4. Maksimal 20MB.</small>
                    </div>
                    
                    <hr class="my-4 border-light">
                    
                    <div class="d-flex justify-content-end gap-3">
                        <button type="button" onclick="history.back()" class="btn btn-light rounded-pill px-4">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 d-flex align-items-center gap-2 shadow-sm">
                            <iconify-icon icon="solar:plain-bold-duotone"></iconify-icon> Kirim untuk Persetujuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card border-0 shadow-sm rounded-4 bg-primary-subtle bg-opacity-10">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center p-3">
                        <iconify-icon icon="solar:info-circle-bold-duotone" class="fs-3"></iconify-icon>
                    </div>
                    <h5 class="fw-bold text-dark mb-0">Informasi Alur</h5>
                </div>
                <ul class="text-muted small ps-3 mb-0 lh-lg">
                    <li>Setelah Anda klik Kirim, materi <b>tidak langsung dipublikasikan</b> ke siswa/orang tua.</li>
                    <li>Status materi menjadi <b>Pending</b>.</li>
                    <li>Kepala Sekolah akan mengecek materi tersebut.</li>
                    <li>Jika disetujui, materi akan muncul di aplikasi orang tua.</li>
                    <li>Jika ditolak, Anda akan menerima catatan revisi.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
