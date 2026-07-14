@extends('layouts.admin')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor .note-editing-area { font-family: 'Inter', sans-serif; }
    .note-editor.note-frame { border-radius: 8px; overflow: hidden; border-color: #e2e8f0; }
    .note-toolbar { background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; }
</style>
@endpush

@section('konten_halaman')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h4 class="fw-bold text-dark mb-0"><iconify-icon icon="solar:pen-bold-duotone" class="align-middle text-primary me-2"></iconify-icon> Edit Landing Page</h4>
                    <p class="text-muted small mt-1">Gunakan editor di bawah ini untuk mengubah konten teks tanpa perlu memahami kode HTML.</p>
                </div>
                <div class="card-body p-4">
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('settings.landing.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <h5 class="fw-bold mb-3 border-bottom pb-2">0. Identitas & Logo Web</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Situs / Sekolah</label>
                                <input type="text" class="form-control" name="site_title" value="{{ $settings['site_title'] ?? 'Yayasan Al-Hikmah' }}" placeholder="Contoh: Yayasan Al-Hikmah">
                                <small class="text-muted">Nama ini akan muncul di pojok kiri atas dan halaman login/register.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Logo (Opsional)</label>
                                <input type="file" class="form-control" name="site_logo" accept="image/*">
                                @if(isset($settings['site_logo']))
                                    <div class="mt-2"><img src="{{ $settings['site_logo'] }}" width="50" class="rounded"></div>
                                @endif
                                <small class="text-muted">Upload logo (PNG direkomendasikan). Jika kosong, ikon default akan digunakan.</small>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-3 border-bottom pb-2 mt-4">1. Bagian Hero (Atas)</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Judul Hero Utama (Teks Hitam)</label>
                                <input type="text" class="form-control" name="hero_title_1" value="{{ $settings['hero_title_1'] ?? 'Ciptakan Generasi' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Judul Tambahan (Teks Biru Bawah)</label>
                                <input type="text" class="form-control" name="hero_title_2" value="{{ $settings['hero_title_2'] ?? 'Cerdas & Ceria' }}">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Subjudul Hero</label>
                            <textarea class="form-control summernote" name="hero_subtitle" rows="3">{{ $settings['hero_subtitle'] ?? 'Memberikan pendidikan terbaik untuk generasi masa depan dengan fasilitas lengkap dan tenaga pengajar profesional. Amankan kuota putra-putri Anda sekarang.' }}</textarea>
                        </div>

                        <h5 class="fw-bold mb-3 border-bottom pb-2">2. Bagian Profil & Program</h5>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Bagian Profil</label>
                            <input type="text" class="form-control" name="profil_title" value="{{ $settings['profil_title'] ?? 'Kenali Lebih Dekat' }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Subjudul Bagian Profil</label>
                            <textarea class="form-control summernote" name="profil_subtitle" rows="2">{{ $settings['profil_subtitle'] ?? 'Pelajari lebih lanjut tentang institusi dan program unggulan kami untuk putra-putri Anda.' }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="fw-bold mb-3 text-primary">Tab Yayasan</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nama Yayasan</label>
                                    <input type="text" class="form-control" name="yayasan_title" value="{{ $settings['yayasan_title'] ?? 'Yayasan Pendidikan Al-Hikmah' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Deskripsi Yayasan</label>
                                    <textarea class="form-control summernote" name="yayasan_desc" rows="4">{{ $settings['yayasan_desc'] ?? 'Yayasan kami berdiri sejak tahun 2010 dengan komitmen penuh untuk mencetak generasi Islami yang unggul, berakhlak mulia, dan siap menghadapi tantangan masa depan. Kami menaungi berbagai cabang pendidikan dengan standar pengajaran terpadu, fasilitas belajar yang modern, dan tenaga pendidik profesional.' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Visi</label>
                                    <textarea class="form-control summernote" name="yayasan_visi" rows="3">{{ $settings['yayasan_visi'] ?? 'Mewujudkan lembaga pendidikan yang berpijak pada penanaman aqidah Islam dan kemuliaan karakter.' }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Misi</label>
                                    <textarea class="form-control summernote" name="yayasan_misi" rows="3">{{ $settings['yayasan_misi'] ?? 'Menyelenggarakan proses pembelajaran yang berkualitas, kreatif, inovatif, dan bermakna.' }}</textarea>
                                </div>
                                <hr>
                                <h6 class="fw-bold mb-3 text-dark">Data Profil Yayasan</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Foto Profil</label>
                                    <input type="file" class="form-control form-control-sm" name="yayasan_image" accept="image/*">
                                    @if(isset($settings['yayasan_image']))
                                        <div class="mt-2"><img src="{{ $settings['yayasan_image'] }}" width="50" class="rounded-circle"></div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Nama Profil</label>
                                    <input type="text" class="form-control form-control-sm" name="yayasan_profile_name" value="{{ $settings['yayasan_profile_name'] ?? 'Dr. H. Fulan, M.Pd' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Jabatan</label>
                                    <input type="text" class="form-control form-control-sm" name="yayasan_profile_title" value="{{ $settings['yayasan_profile_title'] ?? 'Ketua Yayasan' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Poin Ringkasan (Pisahkan dg koma)</label>
                                    <textarea class="form-control form-control-sm" name="yayasan_bullets" rows="2" placeholder="Berdiri sejak 2010, Akreditasi A">{{ $settings['yayasan_bullets'] ?? 'Berdiri sejak 2010, Akreditasi A, 2 Unit Sekolah, 320+ Alumni' }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-13">Program Unggulan (Pisahkan dg koma)</label>
                                    <textarea class="form-control form-control-sm" name="yayasan_programs" rows="2" placeholder="Tahfidz Quran, Bahasa Arab">{{ $settings['yayasan_programs'] ?? 'Tahfidz Quran, Bahasa Arab & Inggris, Seni & Kreativitas, Pendidikan Karakter' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4 border-start border-end">
                                <h6 class="fw-bold mb-3 text-primary">Tab Program TK</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Judul Program TK</label>
                                    <input type="text" class="form-control" name="tk_title" value="{{ $settings['tk_title'] ?? 'Taman Kanak-Kanak (TK)' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Deskripsi Program TK</label>
                                    <textarea class="form-control summernote" name="tk_desc" rows="6">{{ $settings['tk_desc'] ?? 'Program Taman Kanak-Kanak dirancang untuk mengasah kognitif, motorik, dan sosio-emosional anak melalui pendekatan <i>learning by playing</i>. Tersedia pilihan jam belajar fleksibel: <b>TK Pagi</b> dan <b>TK Siang</b> untuk mendukung rutinitas keluarga Anda.' }}</textarea>
                                </div>
                                <hr>
                                <h6 class="fw-bold mb-3 text-dark">Data Profil TK</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Foto Profil</label>
                                    <input type="file" class="form-control form-control-sm" name="tk_image" accept="image/*">
                                    @if(isset($settings['tk_image']))
                                        <div class="mt-2"><img src="{{ $settings['tk_image'] }}" width="50" class="rounded-circle"></div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Nama Profil</label>
                                    <input type="text" class="form-control form-control-sm" name="tk_profile_name" value="{{ $settings['tk_profile_name'] ?? 'Bunda Fatimah, S.Pd' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Jabatan</label>
                                    <input type="text" class="form-control form-control-sm" name="tk_profile_title" value="{{ $settings['tk_profile_title'] ?? 'Kepala Sekolah TK' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Poin Ringkasan (Pisahkan dg koma)</label>
                                    <textarea class="form-control form-control-sm" name="tk_bullets" rows="2">{{ $settings['tk_bullets'] ?? 'Usia 4-6 Tahun, Kurikulum Tematik, Native Speaker' }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-13">Aktivitas Penunjang (Pisahkan dg koma)</label>
                                    <textarea class="form-control form-control-sm" name="tk_programs" rows="2">{{ $settings['tk_programs'] ?? 'Renang Ceria, Field Trip Edutainment, Cek Kesehatan Rutin' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-bold mb-3 text-primary">Tab Program PAUD</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Judul Program PAUD</label>
                                    <input type="text" class="form-control" name="paud_title" value="{{ $settings['paud_title'] ?? 'Pendidikan Anak Usia Dini (PAUD)' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Deskripsi Program PAUD</label>
                                    <textarea class="form-control summernote" name="paud_desc" rows="6">{{ $settings['paud_desc'] ?? 'Program PAUD/Kelompok Bermain kami memfasilitasi masa emas <i>(golden age)</i> anak. Lingkungan sekolah dibuat senyaman dan seaman mungkin seperti di rumah (<i>homely environment</i>) untuk merangsang rasa ingin tahu dan keberanian anak bersosialisasi.' }}</textarea>
                                </div>
                                <hr>
                                <h6 class="fw-bold mb-3 text-dark">Data Profil PAUD</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Foto Profil</label>
                                    <input type="file" class="form-control form-control-sm" name="paud_image" accept="image/*">
                                    @if(isset($settings['paud_image']))
                                        <div class="mt-2"><img src="{{ $settings['paud_image'] }}" width="50" class="rounded-circle"></div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Nama Profil</label>
                                    <input type="text" class="form-control form-control-sm" name="paud_profile_name" value="{{ $settings['paud_profile_name'] ?? 'Bunda Aisyah, S.Psi' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Jabatan</label>
                                    <input type="text" class="form-control form-control-sm" name="paud_profile_title" value="{{ $settings['paud_profile_title'] ?? 'Koordinator PAUD' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Poin Ringkasan (Pisahkan dg koma)</label>
                                    <textarea class="form-control form-control-sm" name="paud_bullets" rows="2">{{ $settings['paud_bullets'] ?? 'Usia 2-4 Tahun, Toilet Training, Indoor Playground' }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-13">Metode Stimulasi (Pisahkan dg koma)</label>
                                    <textarea class="form-control form-control-sm" name="paud_programs" rows="2">{{ $settings['paud_programs'] ?? 'Bernyanyi & Menari, Story Telling, Sensoric Play' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-3 border-bottom pb-2 mt-5">3. Bagian Footer</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 text-primary">Informasi Yayasan</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Nama Yayasan (Footer)</label>
                                    <input type="text" class="form-control form-control-sm" name="footer_title" value="{{ $settings['footer_title'] ?? 'Yayasan Al-Hikmah' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Tagline Footer</label>
                                    <input type="text" class="form-control form-control-sm" name="footer_tagline" value="{{ $settings['footer_tagline'] ?? 'Ciptakan Generasi Cerdas, Ceria, & Berakhlak Mulia' }}">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-13">Alamat Lengkap</label>
                                    <textarea class="form-control form-control-sm" name="footer_address" rows="3">{{ $settings['footer_address'] ?? 'Jl. KH. Wahid Hasyim No. 45, Kecamatan Sukun, Kota Malang, Jawa Timur 65117' }}</textarea>
                                </div>

                                <h6 class="fw-bold mb-3 text-primary">Media Sosial (Opsional, isi URL)</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Instagram URL</label>
                                    <input type="text" class="form-control form-control-sm" name="footer_ig" value="{{ $settings['footer_ig'] ?? '#' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Facebook URL</label>
                                    <input type="text" class="form-control form-control-sm" name="footer_fb" value="{{ $settings['footer_fb'] ?? '#' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Twitter URL</label>
                                    <input type="text" class="form-control form-control-sm" name="footer_tw" value="{{ $settings['footer_tw'] ?? '#' }}">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-13">YouTube URL</label>
                                    <input type="text" class="form-control form-control-sm" name="footer_yt" value="{{ $settings['footer_yt'] ?? '#' }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6 border-start">
                                <h6 class="fw-bold mb-3 text-primary">Pusat Bantuan & Kontak</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Nomor Telepon</label>
                                    <input type="text" class="form-control form-control-sm" name="footer_phone" value="{{ $settings['footer_phone'] ?? '(0341) 567-890' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">WhatsApp (Tampilkan Format)</label>
                                    <input type="text" class="form-control form-control-sm" name="footer_whatsapp" value="{{ $settings['footer_whatsapp'] ?? '0812-3456-7890' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-13">Email</label>
                                    <input type="text" class="form-control form-control-sm" name="footer_email" value="{{ $settings['footer_email'] ?? 'admin@alhikmah.sch.id' }}">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-13">Jam Operasional</label>
                                    <input type="text" class="form-control form-control-sm" name="footer_hours" value="{{ $settings['footer_hours'] ?? 'Senin–Sabtu, 07.00–15.00' }}">
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-5 fw-bold"><iconify-icon icon="solar:diskette-bold-duotone" class="align-middle me-2"></iconify-icon> Simpan Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
@endpush
