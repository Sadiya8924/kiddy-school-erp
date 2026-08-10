{{-- TM-12: Footer publik dinamis — nama diambil dari Setting db, bukan hardcode --}}
<footer class="bg-dark text-white pt-5 pb-4 mt-5" id="kontak">
    @php
        $siteTitle = \App\Models\Setting::get('site_title', 'Kiddy School ERP');
        $footerAddress = \App\Models\Setting::get('footer_address', 'Jl. Pendidikan No. 1, Indonesia');
        $footerPhone = \App\Models\Setting::get('footer_phone', '(021) 000-0000');
        $footerEmail = \App\Models\Setting::get('footer_email', 'info@sekolah.sch.id');
        $footerHours = \App\Models\Setting::get('footer_hours', 'Senin–Jumat, 07.00–15.00');
    @endphp
    <div class="container">
        <div class="row g-4 mb-4">

            {{-- Kolom 1: Identitas --}}
            <div class="col-lg-5">
                <h5 class="fw-bold text-white mb-2" style="letter-spacing: -0.5px;">
                    {{ \App\Models\Setting::get('footer_title', $siteTitle) }}
                </h5>
                <p class="text-white opacity-75 small mb-3">
                    {{ \App\Models\Setting::get('footer_tagline', 'Sistem Informasi Sekolah Modern.') }}
                </p>
                <ul class="list-unstyled text-white opacity-75 small">
                    <li class="mb-2 d-flex align-items-start gap-2">
                        <iconify-icon icon="solar:map-point-bold-duotone" class="fs-5 mt-1 text-primary"></iconify-icon>
                        <span>{{ $footerAddress }}</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:phone-bold-duotone" class="fs-5 text-primary"></iconify-icon>
                        <span>{{ $footerPhone }}</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:letter-bold-duotone" class="fs-5 text-primary"></iconify-icon>
                        <span>{{ $footerEmail }}</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-5 text-primary"></iconify-icon>
                        <span>{{ $footerHours }}</span>
                    </li>
                </ul>
            </div>

            {{-- Kolom 2: Tautan Cepat --}}
            <div class="col-lg-3 offset-lg-1">
                <h6 class="text-uppercase text-white fw-bold mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Tautan Cepat</h6>
                <ul class="list-unstyled small lh-lg">
                    <li><a href="/" class="text-white opacity-75 text-decoration-none">Beranda</a></li>
                    <li><a href="#profil-sekolah" class="text-white opacity-75 text-decoration-none">Profil Sekolah</a></li>
                    <li><a href="#info-kuota" class="text-white opacity-75 text-decoration-none">Cek Kuota Pendaftaran</a></li>
                    <li><a href="/login" class="text-white opacity-75 text-decoration-none">Login Portal Orang Tua</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Sosial Media --}}
            <div class="col-lg-3">
                <h6 class="text-uppercase text-white fw-bold mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Ikuti Kami</h6>
                <div class="d-flex gap-2">
                    @if(\App\Models\Setting::get('footer_ig') && \App\Models\Setting::get('footer_ig') !== '#')
                    <a href="{{ \App\Models\Setting::get('footer_ig') }}" target="_blank" class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 text-white rounded" style="width: 36px; height: 36px;">
                        <iconify-icon icon="mdi:instagram" class="fs-5"></iconify-icon>
                    </a>
                    @endif
                    @if(\App\Models\Setting::get('footer_fb') && \App\Models\Setting::get('footer_fb') !== '#')
                    <a href="{{ \App\Models\Setting::get('footer_fb') }}" target="_blank" class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 text-white rounded" style="width: 36px; height: 36px;">
                        <iconify-icon icon="mdi:facebook" class="fs-5"></iconify-icon>
                    </a>
                    @endif
                    @if(\App\Models\Setting::get('footer_yt') && \App\Models\Setting::get('footer_yt') !== '#')
                    <a href="{{ \App\Models\Setting::get('footer_yt') }}" target="_blank" class="d-flex align-items-center justify-content-center bg-white bg-opacity-10 text-white rounded" style="width: 36px; height: 36px;">
                        <iconify-icon icon="mdi:youtube" class="fs-5"></iconify-icon>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Garis bawah + copyright dinamis --}}
        <div class="border-top pt-4 mt-2 d-flex flex-column flex-md-row justify-content-between align-items-center text-white opacity-50" style="font-size: 0.8rem; border-color: rgba(255,255,255,0.1) !important;">
            {{-- TM-12: Copyright dinamis dari database, bukan hardcode nama sekolah --}}
            <p class="mb-2 mb-md-0">© {{ date('Y') }} {{ \App\Models\Setting::get('footer_title', $siteTitle) }}. Seluruh hak cipta dilindungi.</p>
            <div class="d-flex gap-4">
                {{-- TM-35: Link ke halaman legal yang nyata (bukan href="#") --}}
                <a href="{{ route('legal.privacy') }}" class="text-white text-decoration-none">Kebijakan Privasi</a>
                <a href="{{ route('legal.terms') }}" class="text-white text-decoration-none">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>