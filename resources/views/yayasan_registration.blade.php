<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Formulir Pendaftaran Yayasan Baru — Kiddy School ERP</title>
    <meta name="description" content="Daftarkan yayasan TK/PAUD Anda ke platform Kiddy School ERP.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & Leaflet & Iconify -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F1F5F9;
            color: #1E293B;
            min-height: 100vh;
            margin: 0;
        }

        /* ── Top Bar ────────────────────────────────── */
        .registration-topbar {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 12px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 6px rgba(0,0,0,0.04);
        }
        .btn-back-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            padding: 7px 16px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-back-home:hover {
            background: #EFF6FF;
            border-color: #BFDBFE;
            color: #1D4ED8;
        }
        .brand-center {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #0F172A;
            font-weight: 800;
            font-size: 15.5px;
        }
        .brand-icon {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #2563EB, #3B82F6);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 16px;
        }
        .step-pill {
            font-size: 12px;
            font-weight: 700;
            color: #2563EB;
            background: #EFF6FF;
            border: 1px solid #DBEAFE;
            border-radius: 100px;
            padding: 5px 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* ── Main Form Layout ───────────────────────── */
        .form-wrapper {
            padding: 40px 20px 80px;
        }
        .form-card {
            max-width: 840px;
            margin: 0 auto;
            background: #FFFFFF;
            border-radius: 24px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.06);
            border: 1px solid #E2E8F0;
            overflow: hidden;
        }
        .form-card-header {
            background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%);
            padding: 34px 40px;
            color: #fff;
        }
        .form-card-header h1 {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 6px;
            letter-spacing: -0.02em;
        }
        .form-card-header p {
            font-size: 13.5px;
            color: #DBEAFE;
            margin: 0;
            line-height: 1.6;
        }
        .form-card-body {
            padding: 40px;
        }
        .section-form-title {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #2563EB;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1.5px solid #EFF6FF;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
        }
        .form-control, .form-select {
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            padding: 11px 16px;
            font-size: 14px;
            font-family: inherit;
            color: #0F172A;
            transition: all 0.2s;
            background: #F8FAFC;
        }
        .form-control:focus, .form-select:focus {
            background: #FFFFFF;
            border-color: #2563EB;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.12);
        }
        .map-container {
            height: 290px;
            border-radius: 14px;
            overflow: hidden;
            border: 1.5px solid #E2E8F0;
            margin-top: 8px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.04);
        }
        .koordinat-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .btn-submit {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
            color: #fff;
            border: none;
            border-radius: 14px;
            padding: 16px 36px;
            font-size: 16px;
            font-weight: 800;
            font-family: inherit;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            transition: all 0.25s;
            box-shadow: 0 8px 24px rgba(37,99,235,0.35);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(37,99,235,0.45);
            color: #fff;
        }
        .btn-submit:disabled {
            opacity: 0.65;
            transform: none;
        }
        .required-mark { color: #EF4444; font-weight: 800; }
        .form-hint {
            font-size: 11.5px;
            color: #64748B;
            margin-top: 4px;
        }

        /* ── Simple Footer ──────────────────────────── */
        .simple-footer {
            text-align: center;
            padding: 24px;
            font-size: 12.5px;
            color: #64748B;
            border-top: 1px solid #E2E8F0;
            background: #FFFFFF;
        }
        .simple-footer a {
            color: #2563EB;
            text-decoration: none;
        }

        @media (max-width: 600px) {
            .form-card-body { padding: 24px 18px; }
            .form-card-header { padding: 26px 20px; }
            .koordinat-box { grid-template-columns: 1fr; }
            .registration-topbar { padding: 10px 16px; }
            .step-pill { display: none; }
        }
    </style>
</head>
<body>

<!-- Top Navigation Bar -->
<div class="registration-topbar">
    <a href="/" class="btn-back-home">
        <iconify-icon icon="solar:arrow-left-bold-duotone" style="font-size:16px;"></iconify-icon>
        Kembali ke Beranda
    </a>

    <a href="/" class="brand-center">
        <div class="brand-icon">🏫</div>
        Kiddy School ERP
    </a>

    <span class="step-pill">
        <iconify-icon icon="solar:shield-check-bold" style="font-size:14px;"></iconify-icon>
        Pendaftaran Multi-Tenant
    </span>
</div>

<!-- Main Form Wrapper -->
<main class="form-wrapper">
    <div class="form-card">
        <div class="form-card-header">
            <h1>📋 Formulir Pendaftaran Yayasan Baru</h1>
            <p>Isi formulir di bawah ini dengan lengkap. Setelah pendaftaran dikirim, tim pusat akan memverifikasi dan mengaktifkan akun Super Admin yayasan Anda.</p>
        </div>

        <div class="form-card-body">

            {{-- Flash Message Success --}}
            @if(session('success'))
            <div class="alert alert-success border-success-subtle bg-success-subtle text-success rounded-4 p-4 mb-4 d-flex align-items-start gap-3">
                <iconify-icon icon="solar:check-circle-bold" style="font-size:26px; flex-shrink:0; margin-top:2px;"></iconify-icon>
                <div>
                    <h5 class="fw-bold mb-1">Pendaftaran Berhasil Terkirim!</h5>
                    <p class="mb-0 small leading-relaxed">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            {{-- Flash Message Errors --}}
            @if($errors->any())
            <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger rounded-4 p-4 mb-4">
                <div class="d-flex align-items-center gap-2 fw-bold mb-2">
                    <iconify-icon icon="solar:danger-triangle-bold" style="font-size:20px;"></iconify-icon>
                    Mohon lengkapi isian formulir berikut:
                </div>
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('yayasan.register.store') }}" method="POST" id="formYayasan">
                @csrf

                {{-- BAGIAN 1: Identitas & Kontak Yayasan --}}
                <div class="section-form-title">
                    <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
                    1. Identitas & Kontak Yayasan
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label">Nama Yayasan <span class="required-mark">*</span></label>
                        <input type="text" name="nama_yayasan" class="form-control" placeholder="Contoh: Yayasan Bina Insani Ceria" required value="{{ old('nama_yayasan') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Penanggung Jawab / Ketua <span class="required-mark">*</span></label>
                        <input type="text" name="penanggung_jawab" class="form-control" placeholder="Nama lengkap ketua yayasan" required value="{{ old('penanggung_jawab') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Yayasan / PIC <span class="required-mark">*</span></label>
                        <input type="email" name="email_kontak" class="form-control" placeholder="email@yayasan.org" required value="{{ old('email_kontak') }}">
                        <div class="form-hint">Email ini akan digunakan untuk mengirimkan akun Super Admin yayasan.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor Telepon / WhatsApp <span class="required-mark">*</span></label>
                        <input type="tel" name="telepon" class="form-control" placeholder="Contoh: 0812-3456-7890" required value="{{ old('telepon') }}">
                        <div class="form-hint">Nomor yang aktif untuk konfirmasi & koordinasi teknis.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NPWP Yayasan</label>
                        <input type="text" name="npwp_yayasan" class="form-control" placeholder="XX.XXX.XXX.X-XXX.XXX (opsional)" value="{{ old('npwp_yayasan') }}">
                    </div>
                </div>

                {{-- BAGIAN 2: Alamat & Titik Koordinat Peta --}}
                <div class="section-form-title">
                    <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon>
                    2. Alamat & Titik Koordinat Peta
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label">Alamat Lengkap Kantor Yayasan <span class="required-mark">*</span></label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Jl. Pendidikan No. 1, Kelurahan..., Kecamatan..., Kota/Kabupaten..." required>{{ old('alamat') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tentukan Titik Lokasi Yayasan di Peta</label>
                        <div class="form-hint mb-2">📍 Klik titik pada peta atau cari alamat di kotak pencarian peta untuk menetapkan koordinat lokasi.</div>
                        <div class="map-container" id="map"></div>
                    </div>
                    <div class="koordinat-box col-12">
                        <div>
                            <label class="form-label">Lintang (Latitude)</label>
                            <input type="text" name="lintang" id="lintang" class="form-control" placeholder="Otomatis dari peta" value="{{ old('lintang') }}" readonly style="background:#F1F5F9; font-weight:600;">
                        </div>
                        <div>
                            <label class="form-label">Bujur (Longitude)</label>
                            <input type="text" name="bujur" id="bujur" class="form-control" placeholder="Otomatis dari peta" value="{{ old('bujur') }}" readonly style="background:#F1F5F9; font-weight:600;">
                        </div>
                    </div>
                </div>

                {{-- BAGIAN 3: Legalitas Yayasan --}}
                <div class="section-form-title">
                    <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                    3. Legalitas Yayasan <span style="font-weight:400; font-size:11.5px; color:#94A3B8; text-transform:none;">(Opsional — dapat dilengkapi setelah akun aktif)</span>
                </div>
                <div class="row g-3 mb-5">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Akta Pendirian</label>
                        <input type="text" name="nomor_akta_pendirian" class="form-control" placeholder="Contoh: AHU-123456.AH.01.04" value="{{ old('nomor_akta_pendirian') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor SK Kemenkumham</label>
                        <input type="text" name="nomor_sk_kemenkumham" class="form-control" placeholder="Contoh: AHU-0012345.AH.01.12.2023" value="{{ old('nomor_sk_kemenkumham') }}">
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="btnSubmit">
                    🚀 Kirim Pendaftaran Yayasan Sekarang
                </button>
                <p style="text-align:center; font-size:12.5px; color:#64748B; margin-top:16px;">
                    Dengan mendaftar, Anda menyetujui 
                    <a href="{{ route('legal.privacy') }}" target="_blank" style="color:#2563EB; font-weight:600; text-decoration:none;">Kebijakan Privasi</a> dan 
                    <a href="{{ route('legal.terms') }}" target="_blank" style="color:#2563EB; font-weight:600; text-decoration:none;">Syarat & Ketentuan</a> Kiddy School ERP.
                </p>
            </form>
        </div>
    </div>
</main>

<!-- Simple Footer -->
<footer class="simple-footer">
    © {{ date('Y') }} Kiddy School ERP. Platform Manajemen Yayasan TK & PAUD Terpadu.
</footer>

<!-- Leaflet Map Scripts -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Init Peta Leaflet ──────────────────────────────────────────────
    const defaultLat = -6.2, defaultLng = 106.816;
    const map = L.map('map').setView([defaultLat, defaultLng], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Geocoder (search box)
    const geocoder = L.Control.geocoder({
        defaultMarkGeocode: false,
        placeholder: 'Cari alamat yayasan di peta...'
    })
    .on('markgeocode', function (e) {
        const { center } = e.geocode;
        placeMarker(center);
        map.setView(center, 16);
    })
    .addTo(map);

    let marker = null;

    function placeMarker(latlng) {
        if (marker) marker.remove();
        marker = L.marker(latlng, { draggable: true }).addTo(map);
        updateCoords(latlng.lat, latlng.lng);
        marker.on('dragend', function (e) {
            updateCoords(e.target.getLatLng().lat, e.target.getLatLng().lng);
        });
    }

    function updateCoords(lat, lng) {
        document.getElementById('lintang').value = lat.toFixed(7);
        document.getElementById('bujur').value   = lng.toFixed(7);
    }

    map.on('click', function (e) {
        placeMarker(e.latlng);
    });

    // Restore koordinat dari old() jika ada
    const oldLat = '{{ old('lintang') }}';
    const oldLng = '{{ old('bujur') }}';
    if (oldLat && oldLng) {
        const ll = L.latLng(parseFloat(oldLat), parseFloat(oldLng));
        placeMarker(ll);
        map.setView(ll, 15);
    }

    // ── Form Submit Loading ────────────────────────────────────────────
    document.getElementById('formYayasan').addEventListener('submit', function () {
        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '⏳ Sedang Mengirim Pendaftaran Yayasan...';
    });
});
</script>

</body>
</html>
