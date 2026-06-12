@extends('layouts.admin')

@section('konten_halaman')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-semibold text-dark mb-1">📈 Grafik & Data Perkembangan</h4>
        <span class="text-muted fs-3">Pantau pertumbuhan fisik <strong class="text-dark">{{ $siswa->nama_lengkap }}</strong> dari waktu ke waktu.</span>
    </div>
    <a href="{{ route('perkembangan.index') }}" class="btn btn-outline-primary rounded-pill fw-medium px-4">
        Kembali
    </a>
</div>

<div class="row">
    <!-- Bagian Grafik (ApexCharts) -->
    <div class="col-lg-8 d-flex align-items-stretch">
        <div class="card w-100 mb-4">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold text-dark mb-4">Grafik Pertumbuhan</h5>
                <!-- Ubah dari canvas menjadi div untuk ApexCharts -->
                <div id="chartPerkembangan"></div>
            </div>
        </div>
    </div>

    <!-- Bagian Form Input -->
    <div class="col-lg-4 d-flex align-items-stretch">
        <div class="card w-100 mb-4">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold text-dark mb-4">Input Data Baru</h5>
                <form action="{{ route('perkembangan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted fs-3 text-uppercase">Tanggal Pemeriksaan</label>
                        <input type="date" name="tgl_periksa" class="form-control text-dark" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted fs-3 text-uppercase">Berat Badan (KG)</label>
                        <input type="number" step="0.01" name="berat_badan" class="form-control text-dark" placeholder="Contoh: 15.5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted fs-3 text-uppercase">Tinggi Badan (CM)</label>
                        <input type="number" step="0.1" name="tinggi_badan" class="form-control text-dark" placeholder="Contoh: 105" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted fs-3 text-uppercase">Lingkar Kepala (CM) <span class="fw-normal text-muted text-capitalize">- Opsional</span></label>
                        <input type="number" step="0.1" name="lingkar_kepala" class="form-control text-dark">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium text-muted fs-3 text-uppercase">Catatan Kesehatan</label>
                        <textarea name="catatan" class="form-control text-dark" rows="3" placeholder="Contoh: Gigi bersih, sudah bisa melompat..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success fw-medium rounded-pill w-100 py-2">
                        Simpan Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Mengambil data dari controller
        var tanggal = {!! json_encode($riwayat->pluck('tgl_periksa')) !!};
        var berat = {!! json_encode($riwayat->pluck('berat_badan')) !!};
        var tinggi = {!! json_encode($riwayat->pluck('tinggi_badan')) !!};

        // Konfigurasi ApexCharts ala template SeoDash
        var chartOptions = {
            series: [
                {
                    name: 'Berat Badan (kg)',
                    data: berat
                },
                {
                    name: 'Tinggi Badan (cm)',
                    data: tinggi
                }
            ],
            chart: {
                type: 'area', // Area chart memberi efek gradasi di bawah garis
                height: 350,
                fontFamily: 'inherit',
                toolbar: { show: false }, // Sembunyikan tombol hamburger bawaan grafik
                zoom: { enabled: false }
            },
            colors: ['#13deb9', '#5d87ff'], // Hijau (Success) dan Biru (Primary)
            stroke: {
                curve: 'smooth', // Membuat garis melengkung, tidak kaku
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
                strokeColors: ['#13deb9', '#5d87ff'],
                strokeWidth: 2,
                hover: { size: 7 }
            },
            xaxis: {
                categories: tanggal,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#a1aab2' }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#a1aab2' }
                }
            },
            grid: {
                borderColor: 'rgba(0,0,0,0.1)',
                strokeDashArray: 3, // Garis grid putus-putus
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: true } },
                padding: { top: 0, right: 0, bottom: 0, left: 0 }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (val) {
                        return val
                    }
                }
            }
        };

        // Inisialisasi dan Render Grafik
        var chart = new ApexCharts(document.querySelector("#chartPerkembangan"), chartOptions);
        chart.render();
    });
</script>
@endpush