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
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Presensi <span class="text-danger">*</span></label>
                                                            <select name="presensi" class="form-select border-primary" required>
                                                                <option value="Hadir" {{ ($jurnal->presensi ?? '') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                                                <option value="Izin" {{ ($jurnal->presensi ?? '') == 'Izin' ? 'selected' : '' }}>Izin</option>
                                                                <option value="Sakit" {{ ($jurnal->presensi ?? '') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                                                <option value="Alpa" {{ ($jurnal->presensi ?? '') == 'Alpa' ? 'selected' : '' }}>Tanpa Keterangan (Alpa)</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Kondisi / Mood <span class="text-muted fw-normal">(Opsional)</span></label>
                                                            <select name="mood" class="form-select border-primary">
                                                                <option value="">-- Pilih Mood --</option>
                                                                <option value="😊 Ceria/Senang" {{ ($jurnal->mood ?? '') == '😊 Ceria/Senang' ? 'selected' : '' }}>😊 Ceria/Senang</option>
                                                                <option value="😢 Sedih/Murung" {{ ($jurnal->mood ?? '') == '😢 Sedih/Murung' ? 'selected' : '' }}>😢 Sedih/Murung</option>
                                                                <option value="😡 Marah/Tantrum" {{ ($jurnal->mood ?? '') == '😡 Marah/Tantrum' ? 'selected' : '' }}>😡 Marah/Tantrum</option>
                                                                <option value="😴 Mengantuk/Lemas" {{ ($jurnal->mood ?? '') == '😴 Mengantuk/Lemas' ? 'selected' : '' }}>😴 Mengantuk/Lemas</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Deskripsi Kegiatan Anak <span class="text-danger">*</span></label>
                                                        <textarea name="kegiatan" class="form-control border-primary" rows="2" placeholder="Ceritakan bagaimana anak mengikuti materi ini..." required>{{ $jurnal->kegiatan ?? '' }}</textarea>
                                                    </div>
                                                    
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Perkembangan Individual <span class="text-muted fw-normal">(Opsional)</span></label>
                                                            <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan observasi perkembangan anak...">{{ $jurnal->catatan ?? '' }}</textarea>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Catatan Khusus <span class="text-muted fw-normal">(Opsional)</span></label>
                                                            <textarea name="catatan_khusus" class="form-control" rows="2" placeholder="Insiden, peringatan, atau hal penting lainnya...">{{ $jurnal->catatan_khusus ?? '' }}</textarea>
                                                        </div>
                                                    </div>

                                                    @if(auth()->user()->role == 'kepsek' || ($jurnal && $jurnal->catatan_kepsek))
                                                        <div class="mb-3 bg-warning-subtle p-3 rounded-3 border border-warning">
                                                            <label class="form-label fw-bold text-dark"><iconify-icon icon="solar:shield-star-bold-duotone"></iconify-icon> Catatan Pengawasan Kepala Sekolah</label>
                                                            @if(auth()->user()->role == 'kepsek')
                                                                <textarea name="catatan_kepsek" class="form-control border-warning" rows="2" placeholder="Tambahkan evaluasi atau arahan untuk guru...">{{ $jurnal->catatan_kepsek ?? '' }}</textarea>
                                                            @else
                                                                <p class="mb-0 text-dark fst-italic">{{ $jurnal->catatan_kepsek }}</p>
                                                            @endif
                                                        </div>
                                                    @endif

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

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-info text-white p-3 border-0 rounded-top-4 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:eye-scan-bold-duotone" class="fs-5"></iconify-icon>
                <h6 class="mb-0 fw-bold text-white">Pemantauan Wali Murid (Read Receipts)</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($readReceipts as $receipt)
                        <li class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:check-read-bold-duotone" class="fs-5"></iconify-icon>
                                </div>
                                <div>
                                    <span class="d-block fw-bold text-dark small">{{ $receipt->name }}</span>
                                    <span class="text-muted" style="font-size: 0.75rem;">Membaca pada: {{ \Carbon\Carbon::parse($receipt->read_at)->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                            </div>
                            <span class="badge bg-success rounded-pill">Dibaca</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center py-4 text-muted border-0">
                            <iconify-icon icon="solar:sleep-bold-duotone" class="fs-1 opacity-50 mb-2"></iconify-icon>
                            <p class="mb-0 small">Belum ada Wali Murid yang membaca materi ini.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-primary text-white p-3 border-0 rounded-top-4 d-flex align-items-center gap-2">
                <iconify-icon icon="solar:chat-round-dots-bold-duotone" class="fs-5"></iconify-icon>
                <h6 class="mb-0 fw-bold text-white">Ruang Diskusi & Komentar</h6>
            </div>
            <div class="card-body p-4 bg-light" style="max-height: 400px; overflow-y: auto;">
                @forelse($comments as $comment)
                    <div class="d-flex gap-3 mb-3 p-3 bg-white rounded-3 shadow-sm border border-light">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark small">{{ $comment->name }}</span>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0 small text-dark">{{ $comment->komentar }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <iconify-icon icon="solar:chat-line-broken" class="fs-1 opacity-50 mb-2"></iconify-icon>
                        <p class="mb-0 small">Belum ada diskusi atau pertanyaan dari Wali Murid.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection