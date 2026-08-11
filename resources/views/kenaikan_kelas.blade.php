@extends('layouts.admin')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    <div class="col-12">
        <div class="card border shadow-none mb-0" style="background-color: var(--primary-blue-soft); border-color: var(--primary-blue-border) !important;">
            <div class="card-body px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Proses Kenaikan Kelas</h4>
                    <p class="text-muted mb-0 small">Pindahkan siswa ke tingkat selanjutnya atau tentukan kelulusan.</p>
                </div>
                <div class="d-flex align-items-center gap-2 text-muted small fw-medium">
                    <iconify-icon icon="solar:arrow-up-bold-duotone" class="fs-5 text-primary"></iconify-icon>
                    <span>Aksi siswa massal</span>
                </div>
            </div>
        </div>
    </div>

    @include('partials.owner_stepped_filter')

    @if(!in_array(Auth::user()->role, ['owner', 'super_admin']) || session('active_school_id'))
    <div class="col-12">
        <div class="card border shadow-none mb-0">
            <div class="card-body p-4">
                <form action="{{ route('kenaikan.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold text-dark small mb-2">Pilih Kelas Asal</label>
                        <select name="kelas_id" class="form-select shadow-none border-light-subtle py-2">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($list_kelas as $k)
                                <option value="{{ $k->id }}" {{ $kelas_id == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }} ({{ $k->tingkat }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-primary rounded fw-bold shadow-none py-2">Tampilkan Siswa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($kelas_id)
        @if($siswas->count() > 0)
        <div class="col-12">
            <div class="card border shadow-none mb-0">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 bg-light p-3 rounded-4 border border-light-subtle mb-4">
                        <strong class="text-dark d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:layers-bold-duotone" class="text-primary fs-5"></iconify-icon> Aksi Kolektif
                        </strong>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <select id="bulk-keputusan" class="form-select shadow-none" style="width: auto;">
                                <option value="">-- Pilih Aksi --</option>
                                <option value="naik">Naik Kelas</option>
                                <option value="tinggal">Tinggal Kelas</option>
                                <option value="lulus">Lulus</option>
                            </select>
                            <select id="bulk-tujuan" class="form-select shadow-none d-none" style="width: auto;">
                                <option value="">-- Pilih Kelas Tujuan --</option>
                                @foreach($list_kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="btn-apply-bulk" class="btn btn-secondary rounded-pill fw-medium shadow-none px-3">Terapkan Semua</button>
                        </div>
                    </div>

                    <form action="{{ route('kenaikan.proses') }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-bottom border-light-subtle">
                                    <tr>
                                        <th class="text-center py-3 ps-4 text-muted fw-semibold small">No</th>
                                        <th class="py-3 text-dark fw-semibold small">Nama Siswa</th>
                                        <th class="py-3 text-dark fw-semibold small">Keputusan</th>
                                        <th class="py-3 text-dark fw-semibold small">Kelas Tujuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($siswas as $index => $siswa)
                                    <tr class="border-bottom border-light-subtle">
                                        <td class="text-center ps-4 text-muted fw-medium">{{ $index + 1 }}</td>
                                        <td class="fw-bold text-dark">{{ $siswa->nama_lengkap }}</td>
                                        <td>
                                            <select name="keputusan[{{ $siswa->id }}]" class="form-select shadow-none select-keputusan" data-id="{{ $siswa->id }}" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="naik">Naik Kelas</option>
                                                <option value="tinggal">Tinggal Kelas</option>
                                                <option value="lulus">Lulus</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="tujuan_kelas[{{ $siswa->id }}]" id="tujuan_{{ $siswa->id }}" class="form-select shadow-none select-tujuan d-none">
                                                <option value="">-- Pilih Kelas --</option>
                                                @foreach($list_kelas as $k)
                                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-none">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // JS Logic untuk show/hide dropdown tujuan kelas
            document.querySelectorAll('.select-keputusan').forEach(select => {
                select.addEventListener('change', function() {
                    const tujuan = document.getElementById('tujuan_' + this.dataset.id);
                    if (this.value === 'naik' || this.value === 'tinggal') {
                        tujuan.classList.remove('d-none');
                        tujuan.required = true;
                    } else {
                        tujuan.classList.add('d-none');
                        tujuan.required = false;
                    }
                });
            });

            // JS Logic untuk Aksi Massal
            const btnApply = document.getElementById('btn-apply-bulk');
            if(btnApply) {
                document.getElementById('bulk-keputusan').addEventListener('change', function() {
                    const bulkTujuan = document.getElementById('bulk-tujuan');
                    (this.value === 'naik' || this.value === 'tinggal') ? bulkTujuan.classList.remove('d-none') : bulkTujuan.classList.add('d-none');
                });

                btnApply.addEventListener('click', () => {
                    const aksi = document.getElementById('bulk-keputusan').value;
                    const tujuan = document.getElementById('bulk-tujuan').value;
                    if(!aksi) return alert('Pilih aksi dulu!');

                    document.querySelectorAll('.select-keputusan').forEach(s => {
                        s.value = aksi;
                        s.dispatchEvent(new Event('change'));
                    });
                    if(aksi === 'naik' || aksi === 'tinggal') {
                        document.querySelectorAll('.select-tujuan').forEach(s => s.value = tujuan);
                    }
                });
            }
        </script>
        @else
        <div class="col-12">
            <div class="card border shadow-none mb-0 text-center py-5">
                <div class="py-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-2"></iconify-icon>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada Siswa Aktif</h6>
                    <p class="text-muted small mb-0">Tidak ada siswa aktif terdaftar di kelas ini untuk semester aktif.</p>
                </div>
            </div>
        </div>
        @endif
    @endif
    @endif
</div>
@endsection