@extends('layouts.admin')

@section('konten_halaman')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h4 fw-bold mb-1" style="color:#0f172a;">Pendaftaran Yayasan Baru</h1>
            <p class="text-muted small mb-0">Tinjau dan proses permintaan bergabung yayasan ke platform Kiddy School ERP.</p>
        </div>
        @if($total_menunggu > 0)
        <span class="badge rounded-pill px-3 py-2 fs-6" style="background:#fef3c7; color:#92400e; border:1.5px solid #fde68a;">
            <iconify-icon icon="solar:bell-bold-duotone" class="me-1"></iconify-icon>
            {{ $total_menunggu }} menunggu persetujuan
        </span>
        @endif
    </div>

    {{-- Filter Tabs --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('owner.yayasan_reg.index') }}" class="btn btn-sm rounded-pill {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">Semua</a>
        <a href="{{ route('owner.yayasan_reg.index') }}?status=menunggu" class="btn btn-sm rounded-pill {{ request('status') == 'menunggu' ? 'btn-warning text-dark' : 'btn-outline-secondary' }}">⏳ Menunggu</a>
        <a href="{{ route('owner.yayasan_reg.index') }}?status=disetujui" class="btn btn-sm rounded-pill {{ request('status') == 'disetujui' ? 'btn-success' : 'btn-outline-secondary' }}">✅ Disetujui</a>
        <a href="{{ route('owner.yayasan_reg.index') }}?status=ditolak" class="btn btn-sm rounded-pill {{ request('status') == 'ditolak' ? 'btn-danger' : 'btn-outline-secondary' }}">❌ Ditolak</a>
    </div>

    {{-- Tabel Pendaftaran --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($registrations->isEmpty())
            <div class="text-center py-5">
                <div style="font-size:48px; margin-bottom:12px;">📋</div>
                <h5 class="fw-bold text-muted">Belum ada pendaftaran</h5>
                <p class="text-muted small">Pendaftaran yayasan baru akan muncul di sini.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="py-3">Yayasan</th>
                            <th class="py-3">Kontak</th>
                            <th class="py-3">Lokasi</th>
                            <th class="py-3">Tgl Daftar</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $reg)
                        <tr>
                            <td class="px-4 text-muted small">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold" style="color:#111827;">{{ $reg->nama_yayasan }}</div>
                                <div class="text-muted small">PJ: {{ $reg->penanggung_jawab }}</div>
                                @if($reg->npwp_yayasan)
                                    <div class="text-muted" style="font-size:11px;">NPWP: {{ $reg->npwp_yayasan }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="small"><iconify-icon icon="solar:letter-bold-duotone" class="text-primary me-1"></iconify-icon>{{ $reg->email_kontak }}</div>
                                <div class="small text-muted"><iconify-icon icon="solar:phone-bold-duotone" class="text-primary me-1"></iconify-icon>{{ $reg->telepon }}</div>
                            </td>
                            <td>
                                <div class="small text-muted" style="max-width:180px;">{{ Str::limit($reg->alamat, 60) }}</div>
                                @if($reg->lintang && $reg->bujur)
                                <a href="https://maps.google.com/?q={{ $reg->lintang }},{{ $reg->bujur }}" target="_blank" class="text-primary small" style="font-size:11px;">
                                    <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon> Lihat di Maps
                                </a>
                                @endif
                            </td>
                            <td>
                                <div class="small">{{ $reg->created_at->format('d M Y') }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $reg->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                @if($reg->status === 'menunggu')
                                    <span class="badge rounded-pill px-3 py-1" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a;">⏳ Menunggu</span>
                                @elseif($reg->status === 'disetujui')
                                    <span class="badge rounded-pill px-3 py-1 bg-success-subtle text-success border border-success-subtle">✅ Disetujui</span>
                                    @if($reg->diproses_at)
                                    <div class="text-muted" style="font-size:11px;">{{ $reg->diproses_at->format('d M Y') }}</div>
                                    @endif
                                @elseif($reg->status === 'ditolak')
                                    <span class="badge rounded-pill px-3 py-1 bg-danger-subtle text-danger border border-danger-subtle">❌ Ditolak</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                {{-- Detail button --}}
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalDetail{{ $reg->id }}">
                                    Detail
                                </button>

                                @if($reg->isMenunggu())
                                {{-- ACC Button --}}
                                <button type="button" class="btn btn-sm btn-success rounded-pill me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalAcc{{ $reg->id }}">
                                    <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1"></iconify-icon>ACC
                                </button>
                                {{-- Tolak Button --}}
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                                    data-bs-toggle="modal" data-bs-target="#modalTolak{{ $reg->id }}">
                                    Tolak
                                </button>
                                @endif
                            </td>
                        </tr>

                        {{-- ── Modal Detail ─────────────────────────────────────── --}}
                        <div class="modal fade" id="modalDetail{{ $reg->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow-lg">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold">Detail Pendaftaran — {{ $reg->nama_yayasan }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body pt-3">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="p-3 rounded-3" style="background:#f8fafc; border:1.5px solid #e2e8f0;">
                                                    <div class="text-muted small fw-semibold mb-2" style="text-transform:uppercase; font-size:11px; letter-spacing:.05em;">Identitas Yayasan</div>
                                                    <div class="mb-2"><strong>Nama:</strong> {{ $reg->nama_yayasan }}</div>
                                                    <div class="mb-2"><strong>Penanggung Jawab:</strong> {{ $reg->penanggung_jawab }}</div>
                                                    <div class="mb-2"><strong>Email:</strong> {{ $reg->email_kontak }}</div>
                                                    <div class="mb-2"><strong>Telepon:</strong> {{ $reg->telepon }}</div>
                                                    @if($reg->npwp_yayasan)<div class="mb-1"><strong>NPWP:</strong> {{ $reg->npwp_yayasan }}</div>@endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="p-3 rounded-3" style="background:#f8fafc; border:1.5px solid #e2e8f0;">
                                                    <div class="text-muted small fw-semibold mb-2" style="text-transform:uppercase; font-size:11px; letter-spacing:.05em;">Legalitas & Lokasi</div>
                                                    @if($reg->nomor_akta_pendirian)<div class="mb-2"><strong>No. Akta:</strong> {{ $reg->nomor_akta_pendirian }}</div>@endif
                                                    @if($reg->nomor_sk_kemenkumham)<div class="mb-2"><strong>SK Kemenkumham:</strong> {{ $reg->nomor_sk_kemenkumham }}</div>@endif
                                                    <div class="mb-2"><strong>Alamat:</strong> {{ $reg->alamat }}</div>
                                                    @if($reg->lintang && $reg->bujur)
                                                    <div><strong>Koordinat:</strong> {{ $reg->lintang }}, {{ $reg->bujur }}
                                                        <a href="https://maps.google.com/?q={{ $reg->lintang }},{{ $reg->bujur }}" target="_blank" class="text-primary ms-1">🗺 Buka Maps</a>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($reg->catatan_owner)
                                            <div class="col-12">
                                                <div class="alert alert-secondary small mb-0">
                                                    <strong>Catatan Owner:</strong> {{ $reg->catatan_owner }}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button class="btn btn-light rounded-pill" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($reg->isMenunggu())
                        {{-- ── Modal ACC ─────────────────────────────────────────── --}}
                        <div class="modal fade" id="modalAcc{{ $reg->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow-lg">
                                    <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#16a34a,#22c55e); border-radius:1rem 1rem 0 0;">
                                        <h5 class="modal-title fw-bold text-white">✅ ACC Pendaftaran Yayasan</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('owner.yayasan_reg.approve', $reg->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body py-4">
                                            <p class="text-muted small mb-3">Anda akan mengaktifkan yayasan <strong>{{ $reg->nama_yayasan }}</strong> dan membuat akun Super Admin secara otomatis.</p>

                                            <div class="alert alert-info small rounded-3 mb-3">
                                                <iconify-icon icon="solar:info-circle-bold-duotone" class="me-1"></iconify-icon>
                                                Setelah ACC, sistem akan otomatis membuat akun <strong>super_admin</strong> dengan email <strong>{{ $reg->email_kontak }}</strong>. 
                                                Password sementara akan ditampilkan di halaman ini — catat sebelum menutup halaman.
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Kode Yayasan <span class="text-danger">*</span></label>
                                                <input type="text" name="kode_yayasan" class="form-control rounded-3" 
                                                    placeholder="Contoh: YYS-003" required
                                                    style="text-transform:uppercase; font-weight:600; letter-spacing:.05em;">
                                                <div class="form-text">Kode unik untuk URL landing page: <em>example.com/y/KODE</em></div>
                                            </div>
                                            <div class="mb-1">
                                                <label class="form-label fw-semibold small">Catatan (Opsional)</label>
                                                <textarea name="catatan_owner" class="form-control rounded-3" rows="2" placeholder="Pesan kepada yayasan..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">
                                                <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1"></iconify-icon>ACC & Aktifkan Yayasan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- ── Modal Tolak ──────────────────────────────────────── --}}
                        <div class="modal fade" id="modalTolak{{ $reg->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow-lg">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold text-danger">❌ Tolak Pendaftaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('owner.yayasan_reg.reject', $reg->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body py-4">
                                            <p class="text-muted small mb-3">Tolak pendaftaran dari <strong>{{ $reg->nama_yayasan }}</strong>. Masukkan alasan penolakan.</p>
                                            <div class="mb-1">
                                                <label class="form-label fw-semibold small">Alasan Penolakan <span class="text-danger">*</span></label>
                                                <textarea name="catatan_owner" class="form-control rounded-3" rows="3" 
                                                    placeholder="Contoh: Dokumen legalitas tidak lengkap. Silakan lengkapi dan ajukan ulang." 
                                                    required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold">Tolak Pendaftaran</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Pagination --}}
    @if($registrations->hasPages())
    <div class="d-flex justify-content-end mt-3">
        {{ $registrations->withQueryString()->links() }}
    </div>
    @endif

</div>
@endsection
