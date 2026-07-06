@extends('layouts.admin')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    
    {{-- HEADER --}}
    <div class="col-12 mb-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <a href="{{ route('perkembangan.index') }}" class="btn btn-outline-light border shadow-none text-muted p-2 rounded d-inline-flex mb-3"><iconify-icon icon="solar:arrow-left-line-duotone" class="fs-5"></iconify-icon></a>
                <h4 class="fw-bold text-dark mb-1">Grafik & Data Perkembangan</h4>
                <p class="text-muted mb-0 small">Pantau pertumbuhan fisik <strong class="text-dark">{{ $siswa->nama_lengkap }}</strong> dari waktu ke waktu.</p>
            </div>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="col-12 mt-0">
            <div class="alert alert-success border border-success-subtle shadow-none rounded py-2 px-3 mb-0 small fw-medium">{{ session('success') }}</div>
        </div>
    @endif
    @if($errors->any())
        <div class="col-12 mt-0">
            <div class="alert alert-danger border border-danger-subtle shadow-none rounded py-2 px-3 mb-0 small fw-medium">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- BAGIAN GRAFIK APEXCHARTS --}}
    <div class="col-lg-8">
        <div class="card border shadow-none mb-0 h-100">
            <div class="card-header bg-light border-bottom p-3">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:chart-square-bold-duotone" class="text-primary fs-5"></iconify-icon> Grafik Pertumbuhan
                </h6>
            </div>
            <div class="card-body p-4">
                @if(count($riwayat) > 0)
                    <div id="chartPerkembangan"></div>
                @else
                    <div class="text-center py-5 text-muted small">
                        <iconify-icon icon="solar:chart-line-broken" class="fs-1 d-block mx-auto mb-2 opacity-50"></iconify-icon>
                        Belum ada data pemeriksaan untuk siswa ini.
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- BAGIAN FORM INPUT --}}
    <div class="col-lg-4">
        <div class="card border shadow-none mb-0 h-100">
            <div class="card-header bg-light border-bottom p-3">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:add-square-bold-duotone" class="text-primary fs-5"></iconify-icon> Input Data Baru
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('perkembangan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_periksa" class="form-control border-light-subtle shadow-none bg-light" style="font-size: 13px;" value="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Tinggi Badan (cm) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" name="tinggi_badan" class="form-control border-light-subtle shadow-none bg-light" style="font-size: 13px;" placeholder="Contoh: 110.5" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Berat Badan (kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" name="berat_badan" class="form-control border-light-subtle shadow-none bg-light" style="font-size: 13px;" placeholder="Contoh: 20.5" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Lingkar Kepala (cm)</label>
                        <input type="number" step="0.1" name="lingkar_kepala" class="form-control border-light-subtle shadow-none bg-light" style="font-size: 13px;" placeholder="Opsional">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Catatan Kesehatan</label>
                        <textarea name="catatan" class="form-control border-light-subtle shadow-none bg-light" rows="3" style="font-size: 13px;" placeholder="Contoh: Gigi bersih, sudah bisa melompat..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary rounded fw-bold shadow-none w-100" style="font-size: 13px; padding: 10px;">
                        Simpan Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT APEXCHARTS DIPASANG DI SINI AGAR LANGSUNG KEBACA --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var riwayat = @json($riwayat);
        
        if (riwayat.length > 0) {
            // Format data untuk grafik
            var tanggal = riwayat.map(item => {
                let d = new Date(item.tgl_periksa);
                return d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();
            });
            var tinggi = riwayat.map(item => item.tinggi_badan);
            var berat = riwayat.map(item => item.berat_badan);

            var options = {
                series: [
                    { name: 'Berat Badan (kg)', data: berat },
                    { name: 'Tinggi Badan (cm)', data: tinggi }
                ],
                chart: {
                    type: 'area', // Efek gradasi yang kamu mau
                    height: 350,
                    fontFamily: 'inherit',
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                colors: ['#13deb9', '#2d5cf7'], // Warna hijau dan biru
                stroke: {
                    curve: 'smooth',
                    width: 3,
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                markers: {
                    size: 4,
                    colors: ['#fff'],
                    strokeColors: ['#13deb9', '#2d5cf7'],
                    strokeWidth: 2,
                    hover: { size: 7 }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: tanggal,
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                grid: {
                    borderColor: 'rgba(0,0,0,0.1)',
                    strokeDashArray: 3,
                    xaxis: { lines: { show: true } },
                    yaxis: { lines: { show: true } },
                    padding: { top: 0, right: 0, bottom: 0, left: 0 }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right'
                }
            };

            var chart = new ApexCharts(document.querySelector("#chartPerkembangan"), options);
            chart.render();
        }
    });
</script>
@endsection