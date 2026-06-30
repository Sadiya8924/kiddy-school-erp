@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        @if(isset($pageType) && $pageType === 'lms')
            <h2 class="fw-semibold text-dark mb-1">Manajemen LMS</h2>
            <span class="text-muted fs-3">Kelola materi pembelajaran dan modul untuk siswa.</span>
        @else
            <h2 class="fw-semibold text-dark mb-1">Jurnal Harian</h2>
            <span class="text-muted fs-3">Pilih materi yang tersedia untuk mengisi jurnal perkembangan anak.</span>
        @endif
    </div>
    
    <div class="d-flex gap-2">
        @if(isset($pageType) && $pageType === 'lms' && in_array(auth()->user()->role, ['admin', 'super_admin', 'guru', 'kepsek']))
            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahMateri">
                <iconify-icon icon="solar:folder-with-files-bold-duotone" class="align-middle fs-5 me-1"></iconify-icon> Buat Materi Baru
            </button>
        @endif
        @if(!isset($pageType) || $pageType === 'jurnal')
        <a href="{{ route('jurnal.zip') }}" class="btn btn-warning rounded-pill px-4 shadow-sm">
            <iconify-icon icon="solar:archive-bold-duotone" class="align-middle fs-5 me-1"></iconify-icon> Unduh Arsip (ZIP)
        </a>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
@endif

<div class="row">
    @forelse($materis as $materi)
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header border-0 
                    {{ $materi->kelompok == 'A' ? 'bg-primary' : ($materi->kelompok == 'B' ? 'bg-success' : 'bg-info') }} text-white p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge bg-white text-dark mb-2">Kelompok {{ $materi->kelompok }}</span>
                        <div class="d-flex gap-1">
                            @if($materi->status == 'Menunggu ACC')
                                <span class="badge bg-warning text-dark"><iconify-icon icon="solar:clock-circle-bold"></iconify-icon> Menunggu ACC</span>
                            @elseif($materi->status == 'Disetujui')
                                <span class="badge bg-white text-success"><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Disetujui</span>
                            @else
                                <span class="badge bg-danger text-white"><iconify-icon icon="solar:close-circle-bold"></iconify-icon> Ditolak</span>
                            @endif

                            @if((!isset($pageType) || $pageType === 'lms') && in_array(auth()->user()->role, ['admin', 'super_admin', 'guru', 'kepsek']) && (auth()->user()->id == $materi->user_id || in_array(auth()->user()->role, ['admin', 'super_admin'])))
                                <form action="{{ route('jurnal.materi.destroy', $materi->id) }}" method="POST" class="form-delete">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger p-1 rounded-circle"><iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon></button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <h5 class="fw-bold text-white mb-1">{{ $materi->judul_materi }}</h5>
                    <small class="opacity-75"><iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon> {{ \Carbon\Carbon::parse($materi->tanggal_dibuat)->translatedFormat('d F Y') }}</small>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-4">{{ $materi->deskripsi ?: 'Tidak ada deskripsi tambahan untuk materi ini.' }}</p>
                    
                    @if($materi->status == 'Ditolak')
                        <div class="alert alert-danger p-3 rounded-3 small mb-4 border-0">
                            <strong>Alasan Ditolak:</strong> {{ $materi->catatan_penolakan }}
                            <br><span class="text-muted">Silakan hapus materi ini dan buat materi baru yang sudah direvisi.</span>
                        </div>
                    @endif

                    @if((!isset($pageType) || $pageType === 'lms') && in_array(auth()->user()->role, ['kepsek', 'admin', 'super_admin']) && $materi->status == 'Menunggu ACC')
                        <h6 class="fw-bold text-dark small mb-3">Tindakan Kepala Sekolah:</h6>
                        <div class="d-flex gap-2 mb-4">
                            <form action="{{ route('jurnal.materi.acc', $materi->id) }}" method="POST" class="flex-fill form-acc">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 rounded-pill"><iconify-icon icon="solar:check-circle-bold"></iconify-icon> Setujui</button>
                            </form>
                            <button type="button" class="btn btn-danger flex-fill rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $materi->id }}">
                                <iconify-icon icon="solar:close-circle-bold"></iconify-icon> Tolak
                            </button>
                        </div>

                        <!-- Modal Tolak -->
                        <div class="modal fade" id="modalTolak{{ $materi->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow rounded-4">
                                    <form action="{{ route('jurnal.materi.tolak', $materi->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-danger text-white rounded-top-4">
                                            <h5 class="modal-title fw-bold text-white">Tolak Materi</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                                <textarea name="catatan_penolakan" class="form-control" rows="3" required placeholder="Jelaskan alasan materi ini ditolak agar guru dapat merevisinya..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 bg-light rounded-bottom-4">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Kirim Penolakan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if((!isset($pageType) || $pageType === 'jurnal') && ($materi->status == 'Disetujui' || auth()->user()->role != 'kepsek'))
                        <h6 class="fw-bold text-dark small mb-3">Pilih Kelas untuk Isi Jurnal:</h6>
                        <div class="d-grid gap-2">
                            @php
                                // Cari kelas yang sesuai dengan kelompok materi ini
                                $kelas_match = $list_kelas->where('kelompok', $materi->kelompok);
                            @endphp
                            
                            @forelse($kelas_match as $kelas)
                                <a href="{{ route('jurnal.kelas.show', ['materi_id' => $materi->id, 'kelas_id' => $kelas->id]) }}" class="btn btn-outline-primary rounded-pill d-flex justify-content-between align-items-center px-4">
                                    <span>Kelas {{ $kelas->nama_kelas }}</span>
                                    <iconify-icon icon="solar:alt-arrow-right-bold-duotone"></iconify-icon>
                                </a>
                            @empty
                                <span class="text-danger small fst-italic">Belum ada kelas di kelompok ini.</span>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <iconify-icon icon="solar:file-smile-bold-duotone" class="fs-1 text-muted opacity-50 mb-3"></iconify-icon>
            <h5 class="text-muted fw-bold">Belum ada materi pembelajaran</h5>
            <p class="text-muted small">Admin perlu membuat materi jurnal terlebih dahulu.</p>
        </div>
    @endforelse
</div>

{{-- MODAL TAMBAH MATERI --}}
@if((!isset($pageType) || $pageType === 'lms') && in_array(auth()->user()->role, ['admin', 'super_admin', 'guru', 'kepsek']))
<div class="modal fade" id="modalTambahMateri" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('jurnal.materi.store') }}" method="POST" enctype="multipart/form-data" id="formBuatMateri">
                @csrf
                <input type="hidden" name="status_action" id="statusAction" value="DRAFT">
                
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-bold text-white">Buat Materi Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Judul Materi / Tema <span class="text-danger">*</span></label>
                            <input type="text" name="judul_materi" class="form-control border-primary" placeholder="Contoh: Mewarnai Panca Indera" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Rencana Tayang <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="date" name="tanggal_tayang" class="form-control border-primary" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kelas Tujuan <span class="text-danger">*</span></label>
                        <div class="border rounded p-3 bg-light">
                            <div class="row">
                                @foreach($list_kelas as $kelas)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input kelas-checkbox" type="checkbox" name="kelas_ids[]" value="{{ $kelas->id }}" id="kelas{{ $kelas->id }}" data-nama="Kelas {{ $kelas->nama_kelas }}" {{ auth()->user()->kelas_id == $kelas->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="kelas{{ $kelas->id }}">
                                            Kelas {{ $kelas->nama_kelas }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1">Anda dapat memilih lebih dari satu kelas.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Singkat <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Garis besar kegiatan..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tujuan Pembelajaran <span class="text-danger">*</span></label>
                        <textarea name="tujuan_pembelajaran" class="form-control" rows="2" placeholder="Siswa diharapkan dapat..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">File / Konten Materi <span class="text-muted fw-normal">(Opsional)</span></label>
                        <input type="file" name="file_materi" class="form-control border-primary" accept=".pdf,.jpg,.jpeg,.png,.mp4">
                        <small class="text-muted d-block mt-1">Format: PDF, Gambar, Video.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning rounded-pill px-4 fw-bold" onclick="submitMateri('DRAFT')">Simpan Draft</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" onclick="submitMateri('Menunggu ACC')">Simpan & Kirim ke Kepsek</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const userKelasId = "{{ auth()->user()->kelas_id }}";
    
    function submitMateri(action) {
        document.getElementById('statusAction').value = action;
        
        const form = document.getElementById('formBuatMateri');
        if(!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Cek jika memilih kelas di luar kelasnya sendiri
        const selectedClasses = document.querySelectorAll('.kelas-checkbox:checked');
        if (selectedClasses.length === 0) {
            alert('Pilih minimal satu kelas tujuan!');
            return;
        }

        let crossClassNames = [];
        selectedClasses.forEach(cb => {
            if (cb.value !== userKelasId) {
                crossClassNames.push(cb.getAttribute('data-nama'));
            }
        });

        if (crossClassNames.length > 0) {
            const confirmMsg = "Kamu akan mengirim materi ini ke " + crossClassNames.join(', ') + ". Lanjutkan?";
            Swal.fire({
                title: 'Konfirmasi Lintas Kelas',
                text: confirmMsg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2952ff',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return;
        }
        
        form.submit();
    }
</script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SweetAlert untuk tombol Hapus Materi
        const deleteForms = document.querySelectorAll('.form-delete');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Materi?',
                    text: 'Semua jurnal siswa yang terhubung ke materi ini akan ikut terhapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6e7881',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        // SweetAlert untuk tombol Setujui (ACC)
        const accForms = document.querySelectorAll('.form-acc');
        accForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Setujui Materi?',
                    text: 'Materi ini akan segera terpublikasi dan bisa diakses oleh Wali Murid.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#13deb9',
                    cancelButtonColor: '#6e7881',
                    confirmButtonText: 'Ya, Setujui!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
@endif
@endsection