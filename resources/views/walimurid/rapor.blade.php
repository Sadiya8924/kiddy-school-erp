@extends('layouts.admin')

@section('konten_halaman')
<div class="row">
    <div class="col-12 mb-4">
        <h2 class="fw-semibold text-dark mb-1">E-Rapor Anak</h2>
        <span class="text-muted fs-3">Pantau hasil belajar ananda dengan mudah.</span>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 text-center p-4">
            <div class="rounded-circle bg-primary-subtle mx-auto d-flex align-items-center justify-content-center mb-3" style="width:100px; height:100px;">
                <iconify-icon icon="solar:user-bold-duotone" class="fs-1 text-primary"></iconify-icon>
            </div>
            <h5 class="fw-bold mb-1">{{ $siswa->nama_lengkap }}</h5>
            <p class="text-muted small mb-3">NISN: {{ $siswa->nisn ?? '-' }}</p>
            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Status: Aktif</span>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                @if($semesterAktif)
                    <h5 class="fw-bold text-primary mb-3">Semester Aktif: {{ $semesterAktif->nama_semester }}</h5>
                    
                    @if($rapor && $rapor->status == 'permanen')
                        <i class="ti ti-check-badge text-success mb-2" style="font-size: 60px;"></i>
                        <h4 class="fw-bold">Rapor Sudah Tersedia!</h4>
                        <p class="text-muted mb-4">Pihak sekolah telah menerbitkan e-rapor untuk semester ini.</p>
                        <a href="{{ url('cetak-rapor?search='.$siswa->nik) }}" class="btn btn-primary rounded-pill px-4">
                            <i class="ti ti-printer me-2"></i> Lihat & Cetak Rapor
                        </a>
                    @else
                        <i class="ti ti-clock-hour-4 text-warning mb-2" style="font-size: 60px;"></i>
                        <h4 class="fw-bold">Rapor Sedang Diproses</h4>
                        <p class="text-muted mb-0">Wali kelas belum mem-permanenkan rapor ananda untuk semester ini. Harap bersabar ya, Ayah/Bunda.</p>
                    @endif
                @else
                    <i class="ti ti-calendar-cancel text-muted mb-2" style="font-size: 60px;"></i>
                    <h4 class="fw-bold">Belum Ada Semester Aktif</h4>
                    <p class="text-muted mb-0">Menunggu pengaturan dari pihak sekolah.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection