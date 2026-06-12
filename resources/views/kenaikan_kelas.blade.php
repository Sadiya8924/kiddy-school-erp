@extends('layouts.admin')

@section('konten_halaman')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-semibold text-dark mb-1">Proses Kenaikan Kelas</h2>
        <span class="text-muted fs-3">Pindahkan siswa ke tingkat selanjutnya atau tentukan kelulusan.</span>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form action="{{ route('kenaikan.index') }}" method="GET" class="row align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-bold">Pilih Kelas Asal:</label>
                <select name="kelas_id" class="form-select border-primary">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($list_kelas as $k)
                        <option value="{{ $k->id }}" {{ $kelas_id == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Tampilkan Siswa</button>
            </div>
        </form>
    </div>
</div>

@if($kelas_id && $siswas->count() > 0)
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap align-items-center bg-light p-3 rounded mb-4 gap-3 border">
            <strong class="text-dark">Aksi Kolektif:</strong>
            <select id="bulk-keputusan" class="form-select" style="width: auto;">
                <option value="">-- Pilih Aksi --</option>
                <option value="naik">Naik Kelas</option>
                <option value="tinggal">Tinggal Kelas</option>
                <option value="lulus">Lulus</option>
            </select>
            <select id="bulk-tujuan" class="form-select d-none" style="width: auto;">
                <option value="">-- Pilih Kelas Tujuan --</option>
                @foreach($list_kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            <button type="button" id="btn-apply-bulk" class="btn btn-secondary rounded-pill">Terapkan Semua</button>
        </div>

        <form action="{{ route('kenaikan.proses') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Siswa</th>
                            <th>Keputusan</th>
                            <th>Kelas Tujuan (Jika Naik/Tinggal)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswas as $index => $siswa)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $siswa->nama_lengkap }}</td>
                            <td>
                                <select name="keputusan[{{ $siswa->id }}]" class="form-select border-primary select-keputusan" data-id="{{ $siswa->id }}" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="naik">Naik Kelas</option>
                                    <option value="tinggal">Tinggal Kelas</option>
                                    <option value="lulus">Lulus</option>
                                </select>
                            </td>
                            <td>
                                <select name="tujuan_kelas[{{ $siswa->id }}]" id="tujuan_{{ $siswa->id }}" class="form-select select-tujuan d-none">
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
                <button type="submit" class="btn btn-success btn-lg rounded-pill px-5">Simpan Perubahan</button>
            </div>
        </form>
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
@endif
@endsection