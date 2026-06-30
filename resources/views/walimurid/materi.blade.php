@extends('layouts.admin')

@section('konten_halaman')
<div class="row">
    <div class="col-12 mb-4">
        <h2 class="fw-semibold text-dark mb-1">Materi & Jurnal Aktivitas Harian</h2>
        <span class="text-muted fs-3">Pantau kegiatan dan perkembangan ananda di kelas hari ini.</span>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 bg-primary text-white rounded-4">
            <div class="card-body p-4 bg-primary text-white rounded-4">
                <h5 class="fw-bold mb-1 text-white">Halo Bunda/Ayah {{ auth()->user()->name }}! 👋</h5>
                <p class="mb-0 opacity-75">Ananda <strong>{{ $siswa->nama_lengkap }}</strong> saat ini berada di <strong>Kelas {{ $kelasSiswa->nama_kelas }}</strong>.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @forelse($materis as $materi)
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header border-0 bg-light p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary text-white">Tema: {{ $materi->judul_materi }}</span>
                        <small class="text-muted fw-medium"><iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon> {{ \Carbon\Carbon::parse($materi->tanggal_dibuat)->translatedFormat('d F Y') }}</small>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if(!in_array($materi->id, $readReceipts))
                        <div class="alert alert-info border-0 shadow-sm d-flex justify-content-between align-items-center rounded-3 p-3 mb-4">
                            <div>
                                <span class="d-block fw-bold mb-1">📢 Materi Baru!</span>
                                <small>Ada materi baru dari Bu/Pak Guru untuk ananda hari ini!</small>
                            </div>
                            <form action="{{ route('walimurid.materi.read', $materi->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary rounded-pill"><iconify-icon icon="solar:check-read-bold-duotone" class="align-middle"></iconify-icon> Tandai Sudah Dibaca</button>
                            </form>
                        </div>
                    @else
                        <div class="d-flex align-items-center gap-2 mb-4 text-success small fw-bold">
                            <iconify-icon icon="solar:check-circle-bold" class="fs-5"></iconify-icon> Anda telah membaca materi ini.
                        </div>
                    @endif

                    <h6 class="fw-bold text-dark mb-2">Tujuan Pembelajaran:</h6>
                    <p class="text-muted small mb-3">{{ $materi->tujuan_pembelajaran ?: 'Tujuan pembelajaran terlampir dalam materi.' }}</p>

                    <h6 class="fw-bold text-dark mb-2">Deskripsi Materi:</h6>
                    <p class="text-muted small mb-4">{{ $materi->deskripsi ?: 'Tidak ada deskripsi materi.' }}</p>

                    @if($materi->file_materi)
                        <div class="mb-4">
                            <a href="{{ asset('storage/' . $materi->file_materi) }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4">
                                <iconify-icon icon="solar:file-download-bold-duotone" class="align-middle"></iconify-icon> Unduh / Buka File Materi
                            </a>
                        </div>
                    @endif

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Catatan Jurnal Ananda</h6>
                    @if(isset($jurnals[$materi->id]))
                        @php $jurnal = $jurnals[$materi->id]; @endphp
                        <div class="mb-3 bg-light p-3 rounded-3 border">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge {{ $jurnal->presensi == 'Hadir' ? 'bg-success' : ($jurnal->presensi == 'Sakit' ? 'bg-warning' : 'bg-danger') }} px-3 rounded-pill">Presensi: {{ $jurnal->presensi }}</span>
                                @if($jurnal->mood)
                                    <span class="badge bg-white text-dark border px-3 rounded-pill">Mood: {{ $jurnal->mood }}</span>
                                @endif
                            </div>
                            
                            <span class="fw-semibold text-dark d-block small mt-3">Kegiatan Kelas:</span>
                            <p class="mb-2 text-muted small">{{ $jurnal->kegiatan ?: '-' }}</p>
                            
                            @if($jurnal->catatan)
                            <span class="fw-semibold text-dark d-block small mt-2">Perkembangan Anak:</span>
                            <p class="mb-2 text-muted small fst-italic">{{ $jurnal->catatan }}</p>
                            @endif

                            @if($jurnal->catatan_khusus)
                            <span class="fw-semibold text-dark d-block small mt-2">Catatan Khusus:</span>
                            <p class="mb-0 text-muted small fst-italic">{{ $jurnal->catatan_khusus }}</p>
                            @endif
                        </div>

                        @if($jurnal->foto || $jurnal->video)
                            <h6 class="fw-bold text-dark border-bottom pb-2 mt-4 mb-3">Dokumentasi Media</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                @if($jurnal->foto)
                                    <a href="{{ asset('storage/' . $jurnal->foto) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <iconify-icon icon="solar:gallery-bold-duotone" class="align-middle"></iconify-icon> Lihat Foto
                                    </a>
                                @endif
                                @if($jurnal->video)
                                    <a href="{{ asset('storage/' . $jurnal->video) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill">
                                        <iconify-icon icon="solar:videocamera-record-bold-duotone" class="align-middle"></iconify-icon> Lihat Video
                                    </a>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="alert alert-light border border-warning border-dashed p-3 text-center mb-0 rounded-3">
                            <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-2 text-warning mb-2"></iconify-icon>
                            <p class="mb-0 small text-muted">Jurnal hari ini belum tersedia. Biasanya tersedia setelah jam sekolah selesai ya, Bunda/Ayah. 😊</p>
                        </div>
                    @endif

                    <!-- Section Komentar Materi -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold text-dark mb-3"><iconify-icon icon="solar:chat-round-dots-bold-duotone" class="align-middle text-primary"></iconify-icon> Ruang Diskusi / Pertanyaan</h6>
                        
                        <div class="mb-3">
                            @if(isset($comments[$materi->id]))
                                @foreach($comments[$materi->id] as $comment)
                                    <div class="d-flex gap-3 mb-3 p-3 {{ $comment->user_id == auth()->id() ? 'bg-primary-subtle' : 'bg-light' }} rounded-3">
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold text-dark small">{{ $comment->name }}</span>
                                                <small class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-0 small text-dark">{{ $comment->komentar }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted small fst-italic text-center my-3">Belum ada diskusi untuk materi ini.</p>
                            @endif
                        </div>

                        <form action="{{ route('walimurid.materi.comment', $materi->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="komentar" class="form-control rounded-start-pill border-primary" placeholder="Tulis pertanyaan atau tanggapan Bunda/Ayah..." required>
                                <button type="submit" class="btn btn-primary rounded-end-pill px-4"><iconify-icon icon="solar:plain-bold"></iconify-icon> Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <iconify-icon icon="solar:notebook-smile-bold-duotone" class="fs-1 text-muted opacity-50 mb-3"></iconify-icon>
            <h5 class="text-muted fw-bold">Belum ada materi pembelajaran</h5>
            <p class="text-muted small">Materi dan jurnal kegiatan kelas akan tampil di sini setelah disetujui oleh Kepala Sekolah.</p>
        </div>
    @endforelse
</div>
@endsection
