@extends('layouts.app') 

@section('konten_halaman')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4 py-3">
                    <h4 class="mb-0 fw-bold">Formulir Pendaftaran Siswa Baru (Format Dapodik)</h4>
                    <small>{{ $school->nama_sekolah }}</small> 
                </div>

                <div class="card-body p-4 p-md-5">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="school_id" value="{{ $school->id }}">
                        
                        {{-- A. DATA PRIBADI --}}
                        <h5 class="text-primary fw-bold mb-3 border-bottom border-primary border-opacity-25 pb-2">A. Data Pribadi Siswa</h5>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-medium text-secondary">Nama Lengkap Anak <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">NIK Anak <span class="text-danger">*</span></label>
                                <input type="number" name="nik" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" name="tempat_lahir" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_lahir" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">Agama <span class="text-danger">*</span></label>
                                <select name="agama" class="form-select" required>
                                    <option value="Islam">Islam</option><option value="Kristen">Kristen</option><option value="Katolik">Katolik</option><option value="Hindu">Hindu</option><option value="Budha">Budha</option><option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">Anak ke-</label>
                                <input type="number" name="anak_ke" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">Jumlah Saudara</label>
                                <input type="number" name="jml_saudara" class="form-control">
                            </div>
                        </div>

                        {{-- B. ALAMAT & KONTAK --}}
                        <h5 class="text-primary fw-bold mt-4 mb-3 border-bottom border-primary border-opacity-25 pb-2">B. Alamat & Kontak</h5>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-medium text-secondary">Alamat Jalan <span class="text-danger">*</span></label>
                                <input type="text" name="alamat" class="form-control" required placeholder="Jl. Raya Bangsa">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-medium text-secondary">RT <span class="text-danger">*</span></label>
                                <input type="text" name="rt" class="form-control" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-medium text-secondary">RW <span class="text-danger">*</span></label>
                                <input type="text" name="rw" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">Dusun <span class="text-danger">*</span></label>
                                <input type="text" name="dusun" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-medium text-secondary">Desa/Kel. <span class="text-danger">*</span></label>
                                <input type="text" name="kelurahan" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-medium text-secondary">Kecamatan <span class="text-danger">*</span></label>
                                <input type="text" name="kecamatan" class="form-control" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-medium text-secondary">Kode Pos <span class="text-danger">*</span></label>
                                <input type="number" name="kode_pos" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">Jenis Tinggal</label>
                                <select name="jenis_tinggal" class="form-select">
                                    <option value="Bersama orang tua">Bersama orang tua</option><option value="Wali">Wali</option><option value="Asrama">Asrama</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">Transportasi ke Sekolah</label>
                                <input type="text" name="alat_transportasi" class="form-control" placeholder="Jalan kaki, Motor, dll">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">Jarak ke Sekolah (Km)</label>
                                <input type="number" name="jarak_rumah" class="form-control" placeholder="1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium text-secondary">No HP / WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" name="hp" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium text-secondary">Email Orang Tua</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>

                        {{-- C. DATA ORANG TUA --}}
                        <h5 class="text-primary fw-bold mt-4 mb-3 border-bottom border-primary border-opacity-25 pb-2">C. Data Orang Tua</h5>
                        <div class="row">
                            {{-- AYAH --}}
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded-3 border mb-3">
                                    <h6 class="fw-bold text-dark">Data Ayah Kandung</h6>
                                    <div class="mb-2"><label class="form-label small">Nama Ayah <span class="text-danger">*</span></label><input type="text" name="nama_ayah" class="form-control form-control-sm" required></div>
                                    <div class="mb-2"><label class="form-label small">NIK Ayah</label><input type="number" name="nik_ayah" class="form-control form-control-sm"></div>
                                    <div class="mb-2"><label class="form-label small">Tahun Lahir</label><input type="number" name="tahun_lahir_ayah" class="form-control form-control-sm" placeholder="1980"></div>
                                    <div class="mb-2"><label class="form-label small">Pendidikan</label><input type="text" name="pendidikan_ayah" class="form-control form-control-sm" placeholder="SMA / S1"></div>
                                    <div class="mb-2"><label class="form-label small">Pekerjaan</label><input type="text" name="pekerjaan_ayah" class="form-control form-control-sm"></div>
                                    <div class="mb-2"><label class="form-label small">Penghasilan Bulanan</label><input type="text" name="penghasilan_ayah" class="form-control form-control-sm" placeholder="Contoh: Rp. 2.000.000"></div>
                                </div>
                            </div>
                            {{-- IBU --}}
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded-3 border mb-3">
                                    <h6 class="fw-bold text-dark">Data Ibu Kandung</h6>
                                    <div class="mb-2"><label class="form-label small">Nama Ibu <span class="text-danger">*</span></label><input type="text" name="nama_ibu" class="form-control form-control-sm" required></div>
                                    <div class="mb-2"><label class="form-label small">NIK Ibu</label><input type="number" name="nik_ibu" class="form-control form-control-sm"></div>
                                    <div class="mb-2"><label class="form-label small">Tahun Lahir</label><input type="number" name="tahun_lahir_ibu" class="form-control form-control-sm" placeholder="1985"></div>
                                    <div class="mb-2"><label class="form-label small">Pendidikan</label><input type="text" name="pendidikan_ibu" class="form-control form-control-sm"></div>
                                    <div class="mb-2"><label class="form-label small">Pekerjaan</label><input type="text" name="pekerjaan_ibu" class="form-control form-control-sm"></div>
                                    <div class="mb-2"><label class="form-label small">Penghasilan Bulanan</label><input type="text" name="penghasilan_ibu" class="form-control form-control-sm"></div>
                                </div>
                            </div>
                        </div>

                        {{-- D. FISIK & DOKUMEN --}}
                        <h5 class="text-primary fw-bold mt-4 mb-3 border-bottom border-primary border-opacity-25 pb-2">D. Fisik & Dokumen Upload</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">Tinggi Badan (cm)</label>
                                <input type="number" name="tinggi_badan" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">Berat Badan (kg)</label>
                                <input type="number" name="berat_badan" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium text-secondary">Lingkar Kepala (cm)</label>
                                <input type="number" name="lingkar_kepala" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-medium text-secondary">Pas Foto Anak (Wajib) <span class="text-danger">*</span></label>
                                <input type="file" name="foto_anak" accept="image/*" class="form-control" required>
                                <div class="form-text text-muted">Format: JPG/PNG, Max: 2MB.</div>
                            </div>
                        </div>
                        
                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-send-fill me-2"></i> Kirim Formulir Pendaftaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection