@extends('layouts.app') 

@section('konten_halaman')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card border shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center py-4 border-bottom-0">
                    <h4 class="mb-1 fw-bold">Formulir Pendaftaran & Inden Siswa Baru</h4>
                    <p class="mb-0 small opacity-75">{{ $school->nama_sekolah ?? 'Silakan Pilih Cabang Sekolah Terlebih Dahulu' }}</p> 
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    @if(session('success'))
                        <div class="alert alert-success border-success-subtle bg-success-subtle text-success rounded py-3 px-4 mb-4 fw-medium d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:check-circle-bold" class="fs-4"></iconify-icon> {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger rounded py-3 px-4 mb-4 fw-medium d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:danger-triangle-bold" class="fs-4"></iconify-icon> {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger rounded py-3 px-4 mb-4 fw-medium">
                            <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ======================================================== --}}
                    {{-- FORM 1: PILIH SEKOLAH (Metode GET) --}}
                    {{-- ======================================================== --}}
                    <div class="p-4 rounded-3 border border-light-subtle bg-light mb-4">
                        <form action="{{ route('pendaftaran') }}" method="GET" class="mb-0">
                            <label class="form-label fw-bold text-dark mb-2">1. Pilih Cabang TK / PAUD Tujuan <span class="text-danger">*</span></label>
                            @if(isset($is_locked) && $is_locked && $school)
                                <input type="text" class="form-control bg-white border shadow-none fw-medium text-muted" value="{{ $school->nama_sekolah }}" readonly>
                                <input type="hidden" name="school_id" value="{{ $school->id }}">
                            @else
                                <select name="school_id" class="form-select bg-white border-primary-subtle shadow-none fw-medium" onchange="this.form.submit()" required style="font-size: 14px;">
                                    <option value="">-- Pilih Cabang Sekolah Terlebih Dahulu --</option>
                                    @foreach($schools as $s)
                                        <option value="{{ $s->id }}" {{ (isset($selected_school_id) && $selected_school_id == $s->id) ? 'selected' : '' }}>
                                            {{ $s->nama_sekolah }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </form>
                    </div>

                    {{-- ======================================================== --}}
                    {{-- FORM 2: PENDAFTARAN LENGKAP --}}
                    {{-- ======================================================== --}}
                    @if($school)
                    <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="school_id" value="{{ $school->id }}">

                        <div class="row gx-3 mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">2. Pilih Tahun Ajaran <span class="text-danger">*</span></label>
                                <select name="tahun_inden" id="tahun_inden" class="form-select bg-white border shadow-none fw-medium" required style="font-size: 13px;">
                                    <option value="">-- Pilih Tahun Ajaran --</option>
                                    @if(isset($list_tahun_inden))
                                        @foreach($list_tahun_inden as $tahun)
                                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Program Kelas <span class="text-danger">*</span></label>
                                <select name="program_pilihan" id="program_pilihan" class="form-select bg-white border shadow-none fw-medium" required style="font-size: 13px;" disabled>
                                    <option value="">-- Pilih Tahun Ajaran Terlebih Dahulu --</option>
                                </select>
                            </div>
                        </div>

                        {{-- A. DATA PRIBADI --}}
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4 text-uppercase" style="letter-spacing: 0.5px;">A. Data Pribadi Siswa</h6>
                        <div class="row gx-3">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Nama Lengkap Anak <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control bg-white border shadow-none" style="font-size: 13px;" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">NIK Anak <span class="text-danger">*</span></label>
                                <input type="number" name="nik" class="form-control bg-white border shadow-none" style="font-size: 13px;" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="jenis_kelamin" class="form-select bg-white border shadow-none" style="font-size: 13px;" required>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" name="tempat_lahir" class="form-control bg-white border shadow-none" style="font-size: 13px;" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_lahir" class="form-control bg-white border shadow-none" style="font-size: 13px;" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Agama <span class="text-danger">*</span></label>
                                <select name="agama" class="form-select bg-white border shadow-none" style="font-size: 13px;" required>
                                    <option value="Islam">Islam</option><option value="Kristen">Kristen</option><option value="Katolik">Katolik</option><option value="Hindu">Hindu</option><option value="Budha">Budha</option><option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Anak ke-</label>
                                <input type="number" name="anak_ke" class="form-control bg-white border shadow-none" style="font-size: 13px;">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Jumlah Saudara</label>
                                <input type="number" name="jml_saudara" class="form-control bg-white border shadow-none" style="font-size: 13px;">
                            </div>
                        </div>

                        {{-- B. ALAMAT & KONTAK --}}
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4 text-uppercase" style="letter-spacing: 0.5px;">B. Alamat & Kontak</h6>
                        <div class="row gx-3">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Alamat Jalan <span class="text-danger">*</span></label>
                                <input type="text" name="alamat" class="form-control bg-white border shadow-none" style="font-size: 13px;" required placeholder="Jl. Raya Bangsa">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">RT <span class="text-danger">*</span></label>
                                <input type="text" name="rt" class="form-control bg-white border shadow-none" style="font-size: 13px;" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">RW <span class="text-danger">*</span></label>
                                <input type="text" name="rw" class="form-control bg-white border shadow-none" style="font-size: 13px;" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Dusun <span class="text-danger">*</span></label>
                                <input type="text" name="dusun" class="form-control bg-white border shadow-none" style="font-size: 13px;" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Desa/Kel. <span class="text-danger">*</span></label>
                                <input type="text" name="kelurahan" class="form-control bg-white border shadow-none" style="font-size: 13px;" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Kecamatan <span class="text-danger">*</span></label>
                                <input type="text" name="kecamatan" class="form-control bg-white border shadow-none" style="font-size: 13px;" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Kode Pos <span class="text-danger">*</span></label>
                                <input type="number" name="kode_pos" class="form-control bg-white border shadow-none" style="font-size: 13px;" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Jenis Tinggal</label>
                                <select name="jenis_tinggal" class="form-select bg-white border shadow-none" style="font-size: 13px;">
                                    <option value="Bersama orang tua">Bersama orang tua</option><option value="Wali">Wali</option><option value="Asrama">Asrama</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Transportasi ke Sekolah</label>
                                <input type="text" name="alat_transportasi" class="form-control bg-white border shadow-none" style="font-size: 13px;" placeholder="Jalan kaki, Motor, dll">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Jarak ke Sekolah (Km)</label>
                                <input type="number" name="jarak_rumah" class="form-control bg-white border shadow-none" style="font-size: 13px;" placeholder="1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">No HP / WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" name="hp" class="form-control bg-white border shadow-none" style="font-size: 13px;" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Email Orang Tua</label>
                                <input type="email" name="email" class="form-control bg-white border shadow-none" style="font-size: 13px;">
                            </div>
                        </div>

                        {{-- C. DATA ORANG TUA --}}
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4 text-uppercase" style="letter-spacing: 0.5px;">C. Data Orang Tua</h6>
                        <div class="row gx-3">
                            {{-- AYAH --}}
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded bg-light h-100">
                                    <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;">Data Ayah Kandung</h6>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Nama Ayah <span class="text-danger">*</span></label><input type="text" name="nama_ayah" class="form-control bg-white border shadow-none" style="font-size: 12px;" required></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">NIK Ayah</label><input type="number" name="nik_ayah" class="form-control bg-white border shadow-none" style="font-size: 12px;"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Tahun Lahir</label><input type="number" name="tahun_lahir_ayah" class="form-control bg-white border shadow-none" style="font-size: 12px;" placeholder="1980"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Pendidikan</label><input type="text" name="pendidikan_ayah" class="form-control bg-white border shadow-none" style="font-size: 12px;" placeholder="SMA / S1"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Pekerjaan</label><input type="text" name="pekerjaan_ayah" class="form-control bg-white border shadow-none" style="font-size: 12px;"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Penghasilan Bulanan</label><input type="text" name="penghasilan_ayah" class="form-control bg-white border shadow-none" style="font-size: 12px;" placeholder="Contoh: Rp. 2.000.000"></div>
                                </div>
                            </div>
                            {{-- IBU --}}
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded bg-light h-100">
                                    <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;">Data Ibu Kandung</h6>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Nama Ibu <span class="text-danger">*</span></label><input type="text" name="nama_ibu" class="form-control bg-white border shadow-none" style="font-size: 12px;" required></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">NIK Ibu</label><input type="number" name="nik_ibu" class="form-control bg-white border shadow-none" style="font-size: 12px;"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Tahun Lahir</label><input type="number" name="tahun_lahir_ibu" class="form-control bg-white border shadow-none" style="font-size: 12px;" placeholder="1985"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Pendidikan</label><input type="text" name="pendidikan_ibu" class="form-control bg-white border shadow-none" style="font-size: 12px;"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Pekerjaan</label><input type="text" name="pekerjaan_ibu" class="form-control bg-white border shadow-none" style="font-size: 12px;"></div>
                                    <div class="mb-2"><label class="form-label fw-semibold text-dark" style="font-size: 11px;">Penghasilan Bulanan</label><input type="text" name="penghasilan_ibu" class="form-control bg-white border shadow-none" style="font-size: 12px;"></div>
                                </div>
                            </div>
                        </div>

                        {{-- D. FISIK & DOKUMEN --}}
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4 text-uppercase" style="letter-spacing: 0.5px;">D. Fisik & Dokumen Upload</h6>
                        <div class="row gx-3">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Tinggi Badan (cm)</label>
                                <input type="number" name="tinggi_badan" class="form-control bg-white border shadow-none" style="font-size: 13px;">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Berat Badan (kg)</label>
                                <input type="number" name="berat_badan" class="form-control bg-white border shadow-none" style="font-size: 13px;">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 12px;">Lingkar Kepala (cm)</label>
                                <input type="number" name="lingkar_kepala" class="form-control bg-white border shadow-none" style="font-size: 13px;">
                            </div>
                            
                            {{-- FOTO ANAK --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Pas Foto Anak (Wajib) <span class="text-danger">*</span></label>
                                <input type="file" name="foto_anak" accept="image/*" class="form-control bg-white border-primary shadow-none" required>
                                <div class="form-text text-muted" style="font-size: 11px;">Format: JPG/PNG, Max: 2MB. Pas foto formal berlatar belakang.</div>
                            </div>

                            {{-- FOTO KK (BARU) --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Scan/Foto KK (Wajib) <span class="text-danger">*</span></label>
                                <input type="file" name="foto_kk" accept="image/*,.pdf" class="form-control bg-white border shadow-none" required>
                                <div class="form-text text-muted" style="font-size: 11px;">Format: JPG/PNG/PDF, Max: 2MB.</div>
                            </div>

                            {{-- FOTO AKTE (BARU) --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Scan/Foto Akta Kelahiran (Wajib) <span class="text-danger">*</span></label>
                                <input type="file" name="foto_akte" accept="image/*,.pdf" class="form-control bg-white border shadow-none" required>
                                <div class="form-text text-muted" style="font-size: 11px;">Format: JPG/PNG/PDF, Max: 2MB.</div>
                            </div>
                            
                            {{-- BUKTI PEMBAYARAN --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 13px;">Bukti Pembayaran (Wajib) <span class="text-danger">*</span></label>
                                <input type="file" name="bukti_pembayaran" accept="image/*" class="form-control bg-white border-success shadow-none" required>
                                <div class="form-text text-muted" style="font-size: 11px;">Format: JPG/PNG, Max: 2MB. Upload struk / nota transfer pendaftaran.</div>
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-none py-3 d-flex justify-content-center align-items-center gap-2">
                                <iconify-icon icon="solar:send-square-bold-duotone" class="fs-5"></iconify-icon> Kirim Formulir Pendaftaran
                            </button>
                        </div>
                    </form>
                    
                    {{-- SCRIPT SAKTI BUAT GANTI KUOTA OTOMATIS BERDASARKAN TAHUN YANG DIPILIH --}}
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const tahunSelect = document.getElementById('tahun_inden');
                            const programSelect = document.getElementById('program_pilihan');
                            const kuotaData = @json($kuota_per_tahun ?? []);

                            function updateProgramOptions() {
                                const tahun = tahunSelect.value;
                                programSelect.innerHTML = '<option value="">-- Pilih Program Kelas --</option>';

                                if(tahun && kuotaData[tahun]) {
                                    programSelect.disabled = false;
                                    const kuota = kuotaData[tahun];

                                    const addOption = (val, text, sisa) => {
                                        const opt = document.createElement('option');
                                        opt.value = val;
                                        opt.text = `${text} - Sisa Kuota: ${sisa}`;
                                        if(sisa <= 0) opt.disabled = true;
                                        programSelect.add(opt);
                                    };

                                    addOption('TK Pagi', 'Taman Kanak-Kanak (Pagi)', kuota.tk_pagi);
                                    addOption('TK Siang', 'Taman Kanak-Kanak (Siang)', kuota.tk_siang);
                                    addOption('PAUD', 'Kelompok Bermain / PAUD', kuota.paud);
                                } else {
                                    programSelect.disabled = true;
                                }
                            }

                            if(tahunSelect && programSelect) {
                                tahunSelect.addEventListener('change', updateProgramOptions);
                                updateProgramOptions(); // Eksekusi saat halaman pertama load
                            }
                        });
                    </script>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</div>

<style>
    footer, .footer, #footer {
        display: none !important;
    }
</style>

@endsection