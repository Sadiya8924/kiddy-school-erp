@extends('layouts.app')

@section('konten_halaman')

@php
    $tahunMulai = date('m') <= 6 ? date('Y') : date('Y') + 1;
    $targetTahunHero = $tahunMulai . '/' . ($tahunMulai + 1);
@endphp

{{-- ========================================== --}}
{{-- 1. HERO SECTION                            --}}
{{-- ========================================== --}}
<section class="section-hero-bg pt-5 pb-5">
    <div class="container pt-4 pb-4">
        <div class="row align-items-center">
            
            {{-- KOLOM KIRI: TEKS --}}
            <div class="col-lg-6 mb-5 mb-lg-0 pe-lg-5">
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-4 fw-semibold small d-inline-flex align-items-center gap-1 border border-primary-subtle shadow-sm">
                    <iconify-icon icon="solar:notification-lines-remove-bold-duotone"></iconify-icon> Pendaftaran & Inden Dibuka
                </span>
                
                <h1 class="fw-bolder text-dark display-5 mb-3 hero-title">
                    {{ \App\Models\Setting::get('hero_title_1', 'Ciptakan Generasi') }} <br>
                    <span class="text-primary">{{ \App\Models\Setting::get('hero_title_2', 'Cerdas & Ceria') }}</span>
                </h1>
                
                <div class="text-muted mb-4 fs-6 fw-normal lh-lg hero-subtitle">
                    {!! \App\Models\Setting::get('hero_subtitle', 'Memberikan pendidikan terbaik untuk generasi masa depan dengan fasilitas lengkap dan tenaga pengajar profesional. Amankan kuota putra-putri Anda sekarang.') !!}
                </div>
                
                <div class="d-flex gap-3 mb-5">
                    <a href="#info-kuota" class="btn btn-primary rounded-3 px-4 py-2.5 fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                        <iconify-icon icon="solar:square-double-alt-arrow-right-bold-duotone"></iconify-icon> Daftar Sekarang
                    </a>
                    <a href="#profil-sekolah" class="btn bg-white text-dark border rounded-3 px-4 py-2.5 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                        <iconify-icon icon="solar:info-circle-line-duotone" class="text-muted"></iconify-icon> Kenali Kami
                    </a>
                </div>

                <div class="d-flex gap-5 hero-stats mt-4 border-top pt-4 border-dark-subtle opacity-75">
                    <div>
                        <h4>{{ $schools->count() }}</h4>
                        <p>Unit Sekolah</p>
                    </div>
                    <div>
                        <h4>320+</h4>
                        <p>Alumni</p>
                    </div>
                    <div>
                        <h4>15+</h4>
                        <p>Tahun Berdiri</p>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: WIDGET KUOTA (CARD PUTIH BESAR) --}}
            <div class="col-lg-6 ps-lg-4">
                <div class="hero-quota-card p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                        <h6 class="fw-bolder text-dark mb-0 fs-6">Sisa Kuota {{ $targetTahunHero }}</h6>
                        <span class="badge bg-success-subtle text-success-emphasis rounded-pill px-3 py-1 fw-semibold text-11 border border-success-subtle">Tersedia</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @foreach($schools->take(2) as $school)
                            @php
                                $terisiPagi = \App\Models\Siswa::where('school_id', $school->id)->where('program_pilihan', 'LIKE', 'TK Pagi%'.$targetTahunHero.'%')->where('status', '!=', 'lulus')->count();
                                $terisiSiang = \App\Models\Siswa::where('school_id', $school->id)->where('program_pilihan', 'LIKE', 'TK Siang%'.$targetTahunHero.'%')->where('status', '!=', 'lulus')->count();
                                $terisiPaud = \App\Models\Siswa::where('school_id', $school->id)->where('program_pilihan', 'LIKE', 'PAUD%'.$targetTahunHero.'%')->where('status', '!=', 'lulus')->count();

                                $sisaPagi = max(0, $school->kuota_tk_pagi - $terisiPagi);
                                $sisaSiang = max(0, $school->kuota_tk_siang - $terisiSiang);
                                $sisaPaud = max(0, $school->kuota_paud - $terisiPaud);
                            @endphp
                            
                            <div class="hero-quota-item p-4">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                    <span class="fw-bold text-dark text-13">{{ $school->nama_sekolah }}</span>
                                    <span class="text-muted text-11">TA {{ $targetTahunHero }}</span>
                                </div>
                                <div class="row text-start g-2">
                                    <div class="col-4 border-end ps-2">
                                        <span class="text-muted d-block mb-1 text-11">TK Pagi</span>
                                        <div class="d-flex align-items-baseline gap-1">
                                            <span class="fw-bold text-primary fs-6">{{ $sisaPagi }}</span>
                                            <span class="text-muted text-10">kursi</span>
                                        </div>
                                        <div class="progress-track"><div class="progress-fill" style="width: {{ ($sisaPagi/($school->kuota_tk_pagi > 0 ? $school->kuota_tk_pagi : 1))*100 }}%"></div></div>
                                    </div>
                                    <div class="col-4 border-end ps-3">
                                        <span class="text-muted d-block mb-1 text-11">TK Siang</span>
                                        <div class="d-flex align-items-baseline gap-1">
                                            <span class="fw-bold text-primary fs-6">{{ $sisaSiang }}</span>
                                            <span class="text-muted text-10">kursi</span>
                                        </div>
                                        <div class="progress-track"><div class="progress-fill" style="width: {{ ($sisaSiang/($school->kuota_tk_siang > 0 ? $school->kuota_tk_siang : 1))*100 }}%"></div></div>
                                    </div>
                                    <div class="col-4 ps-3">
                                        <span class="text-muted d-block mb-1 text-11">PAUD</span>
                                        <div class="d-flex align-items-baseline gap-1">
                                            <span class="fw-bold text-primary fs-6">{{ $sisaPaud }}</span>
                                            <span class="text-muted text-10">kursi</span>
                                        </div>
                                        <div class="progress-track"><div class="progress-fill" style="width: {{ ($sisaPaud/($school->kuota_paud > 0 ? $school->kuota_paud : 1))*100 }}%"></div></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

{{-- ========================================== --}}
{{-- 2. BAGIAN PROFIL & PROGRAM                 --}}
{{-- ========================================== --}}
<section class="section-white py-5">
    <div id="profil-sekolah" class="container py-5">
        <div class="mb-5 text-start">
            <p class="text-primary fw-bold text-uppercase mb-2 small section-eyebrow">PENDIDIKAN BERKUALITAS UNTUK ANANDA</p>
            <h2 class="fw-bolder text-dark mb-3 section-title display-6">{{ \App\Models\Setting::get('profil_title', 'Kenali Lebih Dekat') }}</h2>
            <div class="text-muted fw-normal section-desc fs-6">{!! \App\Models\Setting::get('profil_subtitle', 'Pelajari lebih lanjut tentang institusi dan program unggulan kami untuk putra-putri Anda.') !!}</div>
        </div>

        <ul class="nav nav-custom-underline mb-5 justify-content-start" id="profilTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fs-6" data-bs-toggle="pill" data-bs-target="#tab-yayasan" type="button" role="tab"><iconify-icon icon="solar:home-angle-bold-duotone" class="align-middle me-2"></iconify-icon> Profil Yayasan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fs-6" data-bs-toggle="pill" data-bs-target="#tab-tk" type="button" role="tab"><iconify-icon icon="solar:backpack-bold-duotone" class="align-middle me-2"></iconify-icon> Program TK</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fs-6" data-bs-toggle="pill" data-bs-target="#tab-paud" type="button" role="tab"><iconify-icon icon="solar:palette-round-bold-duotone" class="align-middle me-2"></iconify-icon> Program PAUD</button>
            </li>
        </ul>

        <div class="tab-content" id="profilTabContent">
            
            <div class="tab-pane fade show active" id="tab-yayasan" role="tabpanel">
                <div class="row g-5 align-items-stretch">
                    <div class="col-lg-3">
                        <div class="card-profil-left p-4 text-center h-100 d-flex flex-column justify-content-center">
                            <div class="avatar-wrapper rounded-circle overflow-hidden mb-4 mx-auto d-flex align-items-center justify-content-center">
                                @if(\App\Models\Setting::get('yayasan_image'))
                                    <img src="{{ \App\Models\Setting::get('yayasan_image') }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <iconify-icon icon="solar:user-bold-duotone" class="text-primary opacity-50 avatar-icon"></iconify-icon>
                                @endif
                            </div>
                            <h6 class="fw-bold text-dark mb-1 fs-6">{{ \App\Models\Setting::get('yayasan_profile_name', 'Dr. H. Fulan, M.Pd') }}</h6>
                            <p class="text-muted small mb-4">{{ \App\Models\Setting::get('yayasan_profile_title', 'Ketua Yayasan') }}</p>
                            
                            <ul class="list-unstyled text-start text-muted border-top pt-4 mb-0 d-flex flex-column gap-3 text-13">
                                @foreach(explode(',', \App\Models\Setting::get('yayasan_bullets', 'Berdiri sejak 2010, Akreditasi A, 2 Unit Sekolah, 320+ Alumni')) as $bullet)
                                <li><iconify-icon icon="solar:square-alt-arrow-right-line-duotone" class="text-primary me-2 fs-5 align-middle"></iconify-icon> {{ trim($bullet) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 ps-lg-4">
                        <h4 class="fw-bold text-dark mb-3">{{ \App\Models\Setting::get('yayasan_title', 'Yayasan Pendidikan Al-Hikmah') }}</h4>
                        <div class="text-muted fs-6 mb-4 lh-lg">
                            {!! \App\Models\Setting::get('yayasan_desc', 'Yayasan kami berdiri sejak tahun 2010 dengan komitmen penuh untuk mencetak generasi Islami yang unggul, berakhlak mulia, dan siap menghadapi tantangan masa depan. Kami menaungi berbagai cabang pendidikan dengan standar pengajaran terpadu, fasilitas belajar yang modern, dan tenaga pendidik profesional.') !!}
                        </div>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="soft-box">
                                    <h6 class="fw-bold text-primary mb-2 text-uppercase text-12"><iconify-icon icon="solar:target-bold-duotone" class="fs-5 align-middle me-2"></iconify-icon> Visi</h6>
                                    <div class="text-muted small mb-0 lh-lg">{!! \App\Models\Setting::get('yayasan_visi', 'Mewujudkan lembaga pendidikan yang berpijak pada penanaman aqidah Islam dan kemuliaan karakter.') !!}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="soft-box">
                                    <h6 class="fw-bold text-primary mb-2 text-uppercase text-12"><iconify-icon icon="solar:rocket-bold-duotone" class="fs-5 align-middle me-2"></iconify-icon> Misi</h6>
                                    <div class="text-muted small mb-0 lh-lg">{!! \App\Models\Setting::get('yayasan_misi', 'Menyelenggarakan proses pembelajaran yang berkualitas, kreatif, inovatif, dan bermakna.') !!}</div>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-3 fs-6">Program Unggulan</h6>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach(explode(',', \App\Models\Setting::get('yayasan_programs', 'Tahfidz Quran, Bahasa Arab & Inggris, Seni & Kreativitas, Pendidikan Karakter')) as $program)
                            <span class="pill-unggulan"><iconify-icon icon="solar:star-circle-bold-duotone" class="fs-5"></iconify-icon> {{ trim($program) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-tk" role="tabpanel">
                <div class="row g-5 align-items-stretch">
                    <div class="col-lg-3">
                        <div class="card-profil-left p-4 text-center h-100 d-flex flex-column justify-content-center">
                            <div class="avatar-wrapper rounded-circle overflow-hidden mb-4 mx-auto d-flex align-items-center justify-content-center">
                                @if(\App\Models\Setting::get('tk_image'))
                                    <img src="{{ \App\Models\Setting::get('tk_image') }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <iconify-icon icon="solar:user-bold-duotone" class="text-primary opacity-50 avatar-icon"></iconify-icon>
                                @endif
                            </div>
                            <h6 class="fw-bold text-dark mb-1 fs-6">{{ \App\Models\Setting::get('tk_profile_name', 'Bunda Fatimah, S.Pd') }}</h6>
                            <p class="text-muted small mb-4">{{ \App\Models\Setting::get('tk_profile_title', 'Kepala Sekolah TK') }}</p>
                            
                            <ul class="list-unstyled text-start text-muted border-top pt-4 mb-0 d-flex flex-column gap-3 text-13">
                                @foreach(explode(',', \App\Models\Setting::get('tk_bullets', 'Usia 4-6 Tahun, Kurikulum Tematik, Native Speaker')) as $bullet)
                                <li><iconify-icon icon="solar:square-alt-arrow-right-line-duotone" class="text-primary me-2 fs-5 align-middle"></iconify-icon> {{ trim($bullet) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 ps-lg-4">
                        <h4 class="fw-bold text-dark mb-3">{{ \App\Models\Setting::get('tk_title', 'Taman Kanak-Kanak (TK)') }}</h4>
                        <div class="text-muted fs-6 mb-4 lh-lg">
                            {!! \App\Models\Setting::get('tk_desc', 'Program Taman Kanak-Kanak dirancang untuk mengasah kognitif, motorik, dan sosio-emosional anak melalui pendekatan <i>learning by playing</i>. Tersedia pilihan jam belajar fleksibel: <b>TK Pagi</b> dan <b>TK Siang</b> untuk mendukung rutinitas keluarga Anda.') !!}
                        </div>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-md-12">
                                <div class="soft-box">
                                    <h6 class="fw-bold text-primary mb-2 text-uppercase text-12"><iconify-icon icon="solar:checklist-minimalistic-bold-duotone" class="fs-5 align-middle me-2"></iconify-icon> Fokus Pembelajaran</h6>
                                    <ul class="text-muted small mb-0 lh-lg ps-3">
                                        <li>Pengenalan dasar membaca, menulis, dan berhitung (Calistung) tanpa membebani anak.</li>
                                        <li>Penanaman nilai-nilai akhlak, adab makan, dan rutinitas ibadah harian.</li>
                                        <li>Pengembangan kreativitas melalui <i>art and craft</i>, musik, dan eksperimen sains sederhana.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-3 fs-6">Aktivitas Penunjang</h6>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach(explode(',', \App\Models\Setting::get('tk_programs', 'Renang Ceria, Field Trip Edutainment, Cek Kesehatan Rutin')) as $program)
                            <span class="pill-unggulan"><iconify-icon icon="solar:star-circle-bold-duotone" class="fs-5"></iconify-icon> {{ trim($program) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-paud" role="tabpanel">
                <div class="row g-5 align-items-stretch">
                    <div class="col-lg-3">
                        <div class="card-profil-left p-4 text-center h-100 d-flex flex-column justify-content-center">
                            <div class="avatar-wrapper rounded-circle overflow-hidden mb-4 mx-auto d-flex align-items-center justify-content-center">
                                @if(\App\Models\Setting::get('paud_image'))
                                    <img src="{{ \App\Models\Setting::get('paud_image') }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <iconify-icon icon="solar:user-bold-duotone" class="text-primary opacity-50 avatar-icon"></iconify-icon>
                                @endif
                            </div>
                            <h6 class="fw-bold text-dark mb-1 fs-6">{{ \App\Models\Setting::get('paud_profile_name', 'Bunda Aisyah, S.Psi') }}</h6>
                            <p class="text-muted small mb-4">{{ \App\Models\Setting::get('paud_profile_title', 'Koordinator PAUD') }}</p>
                            
                            <ul class="list-unstyled text-start text-muted border-top pt-4 mb-0 d-flex flex-column gap-3 text-13">
                                @foreach(explode(',', \App\Models\Setting::get('paud_bullets', 'Usia 2-4 Tahun, Toilet Training, Indoor Playground')) as $bullet)
                                <li><iconify-icon icon="solar:square-alt-arrow-right-line-duotone" class="text-primary me-2 fs-5 align-middle"></iconify-icon> {{ trim($bullet) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 ps-lg-4">
                        <h4 class="fw-bold text-dark mb-3">{{ \App\Models\Setting::get('paud_title', 'Pendidikan Anak Usia Dini (PAUD)') }}</h4>
                        <div class="text-muted fs-6 mb-4 lh-lg">
                            {!! \App\Models\Setting::get('paud_desc', 'Program PAUD/Kelompok Bermain kami memfasilitasi masa emas <i>(golden age)</i> anak. Lingkungan sekolah dibuat senyaman dan seaman mungkin seperti di rumah (<i>homely environment</i>) untuk merangsang rasa ingin tahu dan keberanian anak bersosialisasi.') !!}
                        </div>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-md-12">
                                <div class="soft-box">
                                    <h6 class="fw-bold text-primary mb-2 text-uppercase text-12"><iconify-icon icon="solar:target-bold-duotone" class="fs-5 align-middle me-2"></iconify-icon> Target Perkembangan</h6>
                                    <ul class="text-muted small mb-0 lh-lg ps-3">
                                        <li>Stimulasi komunikasi dan bahasa untuk memperkaya kosakata harian anak.</li>
                                        <li>Latihan kemandirian dasar seperti mencuci tangan, merapikan mainan, dan <i>toilet training</i>.</li>
                                        <li>Peningkatan sensorik dan motorik kasar melalui permainan edukatif yang diawasi langsung oleh guru.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-3 fs-6">Metode Stimulasi</h6>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach(explode(',', \App\Models\Setting::get('paud_programs', 'Bernyanyi & Menari, Story Telling, Sensoric Play')) as $program)
                            <span class="pill-unggulan"><iconify-icon icon="solar:star-circle-bold-duotone" class="fs-5"></iconify-icon> {{ trim($program) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ========================================== --}}
{{-- 3. BAGIAN SISA KUOTA BAWAH                 --}}
{{-- ========================================== --}}
<section class="section-soft py-5 border-top">
    <div class="container py-4">
        <div id="info-kuota" class="text-center mb-5">
            <p class="text-primary fw-bold text-uppercase mb-2 small section-eyebrow">INFO ONLINE</p>
            <h2 class="fw-bolder text-dark mb-3 section-title">Informasi Sisa Kuota Pendaftaran</h2>
            <p class="text-muted fw-normal">Pilih tahun ajaran untuk melihat sisa kuota yang tersedia di setiap cabang.</p>
        </div>

        @php
            $max_years = collect($schools)->max('max_indent_years') ?? 3;
            $list_tahun = [];
            for ($i = 0; $i < $max_years; $i++) {
                $list_tahun[] = ($tahunMulai + $i) . '/' . ($tahunMulai + $i + 1);
            }
        @endphp

        <ul class="nav nav-kuota-pills justify-content-center mb-5" id="kuotaTahunTab" role="tablist">
            @foreach($list_tahun as $index => $tahun)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $index == 0 ? 'active' : '' }}" data-bs-toggle="pill" data-bs-target="#tab-kuota-{{ $index }}" type="button" role="tab">Pendaftaran {{ $tahun }}</button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="kuotaTahunTabContent">
            @foreach($list_tahun as $index => $tahun)
                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="tab-kuota-{{ $index }}" role="tabpanel">
                    <div class="row gx-4 gy-4 justify-content-center">
                        @foreach($schools as $school)
                            @php
                                $terisiPagi = \App\Models\Siswa::where('school_id', $school->id)->where('program_pilihan', 'LIKE', 'TK Pagi%'.$tahun.'%')->where('status', '!=', 'lulus')->count();
                                $terisiSiang = \App\Models\Siswa::where('school_id', $school->id)->where('program_pilihan', 'LIKE', 'TK Siang%'.$tahun.'%')->where('status', '!=', 'lulus')->count();
                                $terisiPaud = \App\Models\Siswa::where('school_id', $school->id)->where('program_pilihan', 'LIKE', 'PAUD%'.$tahun.'%')->where('status', '!=', 'lulus')->count();

                                $sisaPagi = max(0, $school->kuota_tk_pagi - $terisiPagi);
                                $sisaSiang = max(0, $school->kuota_tk_siang - $terisiSiang);
                                $sisaPaud = max(0, $school->kuota_paud - $terisiPaud);
                            @endphp

                            <div class="col-md-6 col-lg-5">
                                <div class="card card-kuota border-0 shadow-sm h-100 overflow-hidden">
                                    <div class="bg-primary px-4 py-3 d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-0 text-white">{{ $school->nama_sekolah }}</h6>
                                        <span class="text-white opacity-75 text-12">TA {{ $tahun }}</span>
                                    </div>
                                    
                                    <div class="card-body p-4 bg-white">
                                        <ul class="list-group list-group-flush mb-4">
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light-subtle bg-transparent">
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1 text-14">TK Pagi</h6>
                                                    <small class="text-muted fw-normal text-12">Maksimal {{ $school->kuota_tk_pagi }} Anak</small>
                                                </div>
                                                <span class="badge bg-success-subtle text-success-emphasis rounded-pill px-3 py-2 fw-bold text-12">
                                                    Sisa {{ $sisaPagi }} Kursi
                                                </span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light-subtle bg-transparent">
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1 text-14">TK Siang</h6>
                                                    <small class="text-muted fw-normal text-12">Maksimal {{ $school->kuota_tk_siang }} Anak</small>
                                                </div>
                                                <span class="badge bg-success-subtle text-success-emphasis rounded-pill px-3 py-2 fw-bold text-12">
                                                    Sisa {{ $sisaSiang }} Kursi
                                                </span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light-subtle bg-transparent">
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1 text-14">PAUD (Kelompok Bermain)</h6>
                                                    <small class="text-muted fw-normal text-12">Maksimal {{ $school->kuota_paud }} Anak</small>
                                                </div>
                                                <span class="badge bg-success-subtle text-success-emphasis rounded-pill px-3 py-2 fw-bold text-12">
                                                    Sisa {{ $sisaPaud }} Kursi
                                                </span>
                                            </li>
                                        </ul>
                                        
                                        <a href="/pendaftaran-siswa?school_id={{ $school->id }}" class="btn btn-primary w-100 rounded-3 px-4 py-2.5 fw-semibold d-flex justify-content-center align-items-center gap-2">
                                            <iconify-icon icon="solar:square-double-alt-arrow-right-bold-duotone" class="fs-5"></iconify-icon> Daftar Tahun {{ $tahun }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================== --}}
{{--        4. BAGIAN SYARAT PENDAFTARAN        --}}
{{-- ========================================== --}}
<section class="section-white py-5 mb-5 border-top">
    <div class="container py-4">
        <div class="mb-5 text-center">
            <p class="text-primary fw-bold text-uppercase mb-1 small section-eyebrow">DAFTAR ONLINE</p>
            <h2 class="fw-bolder text-dark mb-2 section-title">Syarat Pendaftaran</h2>
            <p class="text-muted fw-normal">Siapkan dokumen-dokumen berikut sebelum mengisi formulir pendaftaran online.</p>
        </div>

        <div class="row justify-content-center g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="card-syarat p-4 text-center">
                    <div class="icon-syarat mb-3 mx-auto"><iconify-icon icon="solar:document-add-bold-duotone" class="fs-4"></iconify-icon></div>
                    <h6 class="fw-bold text-dark mb-2 text-13">Kartu Keluarga</h6>
                    <p class="text-muted small mb-0">Scan atau foto KK yang masih berlaku (JPG/PNG/PDF).</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-syarat p-4 text-center">
                    <div class="icon-syarat mb-3 mx-auto"><iconify-icon icon="solar:user-id-bold-duotone" class="fs-4"></iconify-icon></div>
                    <h6 class="fw-bold text-dark mb-2 text-13">Akta Kelahiran</h6>
                    <p class="text-muted small mb-0">Scan atau foto akta kelahiran anak (JPG/PNG/PDF).</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-syarat p-4 text-center">
                    <div class="icon-syarat mb-3 mx-auto"><iconify-icon icon="solar:map-point-bold-duotone" class="fs-4"></iconify-icon></div>
                    <h6 class="fw-bold text-dark mb-2 text-13">Titik Koordinat Rumah</h6>
                    <p class="text-muted small mb-0">Salin titik koordinat rumah dari Google Maps.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-syarat p-4 text-center">
                    <div class="icon-syarat mb-3 mx-auto"><iconify-icon icon="solar:pulse-bold-duotone" class="fs-4"></iconify-icon></div>
                    <h6 class="fw-bold text-dark mb-2 text-13">Data Fisik Anak</h6>
                    <p class="text-muted small mb-0">Tinggi & berat badan anak untuk keperluan medis.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="#info-kuota" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                <iconify-icon icon="solar:square-double-alt-arrow-right-bold-duotone" class="fs-5"></iconify-icon> Mulai Daftar Sekarang
            </a>
        </div>
    </div>
</section>

@endsection