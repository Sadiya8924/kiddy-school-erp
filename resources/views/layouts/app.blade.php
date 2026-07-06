<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yayasan Pendidikan</title>

    <!-- CSS Bawaan Template 2 -->
    <link rel="stylesheet" href="{{ asset('template2/css/styles.min.css') }}" />
    <!-- Iconify -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <!-- Custom Landing CSS -->
    <link rel="stylesheet" href="{{ asset('template2/css/landing.css') }}">

    @stack('styles')
</head>
<body>

    <!-- NAVBAR PUBLIK -->
    <nav class="navbar navbar-expand-lg bg-white sticky-top py-3 border-bottom shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <iconify-icon icon="solar:diploma-bold-duotone" class="fs-5"></iconify-icon>
                </div>
                <span class="fw-bold fs-5 text-dark" style="letter-spacing: -0.5px;">Yayasan Al-Hikmah</span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar">
                <iconify-icon icon="solar:hamburger-menu-linear" class="fs-2 text-dark"></iconify-icon>
            </button>

            <div class="collapse navbar-collapse" id="publicNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-2 gap-lg-4">
                    <li class="nav-item"><a class="nav-link fw-medium text-muted px-2" href="/">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium text-muted px-2" href="#profil-sekolah">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium text-muted px-2" href="#info-kuota">Pendaftaran</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium text-muted px-2" href="#kontak">Hubungi Kami</a></li>
                </ul>

                <div class="d-flex gap-3 mt-3 mt-lg-0 align-items-center">
                    <a href="/login" class="text-decoration-none text-dark fw-medium px-2">Login Portal</a>
                    <a href="#info-kuota" class="btn btn-primary fw-semibold rounded px-4 py-2 shadow-sm">Mulai Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('konten_halaman')
    </main>

    <!-- FOOTER PUBLIK -->
    <footer id="kontak" class="pt-5 pb-3 bg-dark text-white mt-5">
        <div class="container pt-4">
            <div class="row g-4 mb-5">
                
                <!-- Kolom 1 -->
                <div class="col-lg-5 pe-lg-5">
                    <h5 class="fw-bold text-white mb-2" style="letter-spacing: -0.5px;">Yayasan Al-Hikmah</h5>
                    <p class="text-white opacity-75 small mb-4">Ciptakan Generasi Cerdas, Ceria, & Berakhlak Mulia</p>
                    <div class="d-flex gap-3 mb-4 text-white opacity-75 small">
                        <iconify-icon icon="solar:map-point-bold-duotone" class="fs-5 mt-1 text-primary-custom"></iconify-icon>
                        <p class="mb-0 lh-lg">Jl. KH. Wahid Hasyim No. 45, Kecamatan Sukun, Kota Malang, Jawa Timur 65117</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="#" class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 text-white rounded" style="width: 36px; height: 36px;"><iconify-icon icon="mdi:instagram" class="fs-5"></iconify-icon></a>
                        <a href="#" class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 text-white rounded" style="width: 36px; height: 36px;"><iconify-icon icon="mdi:facebook" class="fs-5"></iconify-icon></a>
                        <a href="#" class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 text-white rounded" style="width: 36px; height: 36px;"><iconify-icon icon="mdi:twitter" class="fs-5"></iconify-icon></a>
                        <a href="#" class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 text-white rounded" style="width: 36px; height: 36px;"><iconify-icon icon="mdi:youtube" class="fs-5"></iconify-icon></a>
                    </div>
                </div>

                <!-- Kolom 2 -->
                <div class="col-lg-4 text-white opacity-75 small">
                    <h6 class="text-uppercase text-white fw-bold mb-4" style="font-size: 0.8rem; letter-spacing: 1px;">Pusat Bantuan & Kontak</h6>
                    <ul class="list-unstyled mb-4 lh-lg">
                        <li class="mb-2 d-flex align-items-center gap-3"><iconify-icon icon="solar:phone-bold-duotone" class="fs-5 text-primary-custom"></iconify-icon> (0341) 567-890</li>
                        <li class="mb-2 d-flex align-items-center gap-3"><iconify-icon icon="solar:chat-line-bold-duotone" class="fs-5 text-primary-custom"></iconify-icon> 0812-3456-7890 (WhatsApp)</li>
                        <li class="mb-2 d-flex align-items-center gap-3"><iconify-icon icon="solar:letter-bold-duotone" class="fs-5 text-primary-custom"></iconify-icon> admin@alhikmah.sch.id</li>
                        <li class="d-flex align-items-center gap-3"><iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-5 text-primary-custom"></iconify-icon> Senin–Sabtu, 07.00–15.00</li>
                    </ul>
                </div>

                <!-- Kolom 3 -->
                <div class="col-lg-3 text-white opacity-75 small">
                    <h6 class="text-uppercase text-white fw-bold mb-4" style="font-size: 0.8rem; letter-spacing: 1px;">Tautan Cepat</h6>
                    <ul class="list-unstyled mb-4 lh-lg">
                        <li class="mb-2 d-flex align-items-center gap-2"><iconify-icon icon="solar:alt-arrow-right-line-duotone" class="text-primary-custom"></iconify-icon> <a href="/" class="text-decoration-none text-white">Beranda</a></li>
                        <li class="mb-2 d-flex align-items-center gap-2"><iconify-icon icon="solar:alt-arrow-right-line-duotone" class="text-primary-custom"></iconify-icon> <a href="#profil-sekolah" class="text-decoration-none text-white">Profil Sekolah & Program</a></li>
                        <li class="mb-2 d-flex align-items-center gap-2"><iconify-icon icon="solar:alt-arrow-right-line-duotone" class="text-primary-custom"></iconify-icon> <a href="#info-kuota" class="text-decoration-none text-white">Cek Sisa Kuota Pendaftaran</a></li>
                        <li class="mb-2 d-flex align-items-center gap-2"><iconify-icon icon="solar:alt-arrow-right-line-duotone" class="text-primary-custom"></iconify-icon> <a href="/login" class="text-decoration-none text-white">Login Portal Orang Tua</a></li>
                    </ul>
                </div>

            </div>

            <div class="border-top pt-4 mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center text-white opacity-50" style="font-size: 0.8rem; border-color: rgba(255,255,255,0.1) !important;">
                <p class="mb-2 mb-md-0">© {{ date('Y') }} Yayasan Al-Hikmah. Seluruh hak cipta dilindungi.</p>
                <div class="d-flex gap-4">
                    <a href="#" class="text-white text-decoration-none">Kebijakan Privasi</a>
                    <a href="#" class="text-white text-decoration-none">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('template2/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template2/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>