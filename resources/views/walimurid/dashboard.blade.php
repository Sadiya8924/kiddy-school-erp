@extends('layouts.admin')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    
    {{-- HEADER WELCOME --}}
    <div class="col-12">
        <div class="card border mb-0 shadow-none" style="background-color: var(--primary-blue-soft); border-color: var(--primary-blue-border) !important;">
            <div class="card-body px-4 py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; background-color: var(--primary-blue); color: white;">
                        <iconify-icon icon="solar:user-heart-bold-duotone" class="fs-3"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: var(--primary-blue-dark); font-size: 16px;">Beranda Wali Murid</h6>
                        <p class="mb-0" style="color: var(--primary-blue); font-size: 13px;">Selamat datang, {{ Auth::user()->name }}! Pantau akademik ananda di sini.</p>
                    </div>
                </div>
                <div class="d-none d-md-flex align-items-center gap-2" style="color: var(--primary-blue); font-size: 13px; font-weight: 500;">
                    <iconify-icon icon="solar:calendar-mark-line-duotone" class="fs-5"></iconify-icon>
                    <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="col-12 mt-0">
            <div class="alert alert-success border border-success-subtle shadow-none rounded py-2 px-3 mb-0 small fw-medium">{{ session('success') }}</div>
        </div>
    @endif

    {{-- JIKA BELUM ADA ANAK --}}
    @if($anak->isEmpty())
    <div class="col-12 mt-3">
        <div class="card mb-0 text-center py-5 border shadow-none">
            <div class="card-body">
                <iconify-icon icon="solar:user-cross-bold-duotone" class="text-warning mb-3" style="font-size: 60px;"></iconify-icon>
                <h5 class="fw-bold text-dark mb-2">Belum Ada Data Anak</h5>
                <p class="text-muted fw-medium mb-4" style="font-size: 13px;">Sepertinya Anda belum mendaftarkan putra/putri Anda ke sistem kami.</p>
                <a href="/pendaftaran-siswa" class="btn btn-primary rounded px-4 py-2 fw-medium shadow-none" style="font-size: 13px;">
                    <iconify-icon icon="solar:user-plus-bold-duotone" class="align-middle me-1"></iconify-icon> Daftarkan Anak Sekarang
                </a>
            </div>
        </div>
    </div>
    
    {{-- JIKA SUDAH ADA ANAK --}}
    @else
    <div class="col-12 mt-3 d-flex justify-content-between align-items-center border-bottom pb-2">
        <h6 class="fw-bold text-dark mb-0" style="font-size: 15px;">Daftar Anak Anda</h6>
        <a href="/pendaftaran-siswa" class="btn btn-outline-light border rounded text-muted btn-sm px-3 py-1.5 fw-medium d-flex align-items-center gap-1" style="font-size: 12px;">
            <iconify-icon icon="solar:add-square-line-duotone"></iconify-icon> Tambah
        </a>
    </div>

    @foreach($anak as $a)
    <div class="col-md-6 mt-3">
        <div class="card mb-0 h-100 border shadow-none">
            <a href="/rapor-anak/{{ $a->id }}" class="card-body p-4 d-flex align-items-center gap-3 card-clickable text-decoration-none">
                {{-- FIX: LOGIKA FOTO PROFIL ANAK --}}
                @if($a->foto_anak)
                    <img src="{{ asset('storage/' . $a->foto_anak) }}" alt="Foto Anak" class="rounded object-fit-cover shadow-sm flex-shrink-0" style="width: 56px; height: 56px;">
                @else
                    <div class="rounded d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 56px; height: 56px; background-color: var(--primary-blue-soft);">
                        <iconify-icon icon="solar:user-bold-duotone" style="font-size: 28px; color: var(--primary-blue);"></iconify-icon>
                    </div>
                @endif
                
                <div class="w-100">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 15px;">{{ $a->nama_lengkap }}</h6>
                        <span class="badge {{ $a->status == 'aktif' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded px-2 py-1" style="font-size: 10px;">
                            {{ ucfirst($a->status) }}
                        </span>
                    </div>
                    <p class="text-muted fw-medium mb-0" style="font-size: 12px;">
                        Sekolah: <strong class="text-dark">{{ \App\Models\School::find($a->school_id)->nama_sekolah ?? '-' }}</strong>
                    </p>
                </div>
            </a>
            <div class="card-footer bg-light border-top pt-3 pb-3 px-4 d-flex gap-2 flex-wrap">
                {{-- Selipkan ID Anak ke URL-nya --}}
                <a href="/rapor-anak/{{ $a->id }}" class="btn btn-white border flex-grow-1 fw-semibold text-dark shadow-sm bg-white" style="font-size: 12px; padding: 8px;">Lihat Rapor</a>
                <a href="/perkembangan-anak/{{ $a->id }}" class="btn btn-white border flex-grow-1 fw-semibold text-dark shadow-sm bg-white" style="font-size: 12px; padding: 8px;">Cek Fisik</a>
                <a href="{{ route('walimurid.materi') }}" class="btn btn-primary border flex-grow-1 fw-semibold shadow-sm" style="font-size: 12px; padding: 8px;">Jurnal Harian</a>
                <a href="{{ route('materi.ortu.index') }}" class="btn btn-info border text-white flex-grow-1 fw-semibold shadow-sm" style="font-size: 12px; padding: 8px;">Materi Pelajaran</a>
            </div>
        </div>
    </div>
    @endforeach
    @endif

    {{-- WIDGET PENGUMUMAN SEKOLAH --}}
    @if($anak->isNotEmpty())
    <div class="col-12 mt-4">
        <div class="card border shadow-none mb-0">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2 border-bottom pb-3" style="font-size: 15px;">
                    <iconify-icon icon="solar:bell-line-duotone" class="text-warning fs-5"></iconify-icon> Pengumuman Sekolah
                </h6>
                <div class="row gx-3 gy-3">
                    @forelse($pengumuman as $p)
                    {{-- FIX: UBAH JADI COL-12 BIAR MEMANJANG FULL --}}
                    <div class="col-12">
                        <div class="p-3 rounded border border-light-subtle bg-light h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 13px;">{{ $p->judul }}</h6>
                                <div class="d-flex align-items-center gap-1 text-muted" style="font-size: 11px;">
                                    <iconify-icon icon="solar:calendar-mark-line-duotone"></iconify-icon>
                                    <span>{{ \Carbon\Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>
                            <p class="text-muted mb-0" style="font-size: 12px;">{{ $p->isi }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center text-muted fw-medium py-3 small">
                            Belum ada pengumuman terbaru.
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<style>
    .card-clickable {
        transition: all 0.2s ease-in-out !important;
        cursor: pointer;
    }
    .card-clickable:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05) !important;
        border-color: var(--primary-blue-border) !important;
        background-color: var(--primary-blue-soft) !important;
    }
</style>
@endsection