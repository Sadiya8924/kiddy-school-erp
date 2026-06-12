@extends('layouts.app')

@section('konten_halaman')

    <div class="container mt-5">
        <div class="row align-items-center">
            <div class="col-md-5">
                <h1 class="fw-bold text-success">Belajar Bersama Kami</h1>
                <p class="lead text-secondary mt-3">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Pariatur architecto in ipsum quae labore.
                </p>
                <button type="button" class="btn btn-success text-white btn-lg rounded-pill mt-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#daftarModal">
                    Daftar Sekarang
                </button>
            </div>

            <div class="col-md-7 mt-4 mt-md-0 text-center">
                <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=600&auto=format&fit=crop" class="img-fluid w=1000 w-100 rounded-4 shadow-lg" alt="Anak TK Belajar">
            </div>
        </div>
    </div> 

    <div class="container mt-5 pt-5">

        <div class="text-center mb-5">
            <h6 class="text-success fw-bold text-uppercase">Kenapa Harus Kami?</h6>
            <h2 class="fw-bold">Keunggulan Kami</h2>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4">
                    <div class="card-body">
                        <div class="display-4 mb-3">Sekolah</div>
                        <h5 class="card-title fw-bold">Fasilitas Lengkap</h5>
                        <p class="card-text text-secondary">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, doloremque.
                        </p>
                    </div>
                </div>
            </div>

             <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4">
                    <div class="card-body">
                        <div class="display-4 mb-3">Guru</div>
                        <h5 class="card-title fw-bold">Guru Profesional</h5>
                        <p class="card-text text-secondary">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, doloremque.
                        </p>
                    </div>
                </div>
            </div>

             <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4">
                    <div class="card-body">
                        <div class="display-4 mb-3">Pembelajaran</div>
                        <h5 class="card-title fw-bold">Kurikulum Terbaru</h5>
                        <p class="card-text text-secondary">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, doloremque.
                        </p>
                    </div>
                </div>
            </div>
        </div> 
    </div>

    <div class="container mt-5 pt-5 mb-5">
        <div class="row align-items-center">

            <div class="col-md-5 mb-4 mb-md-0">
                <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=800&auto=format&fit=crop" class="img-fluid rounded-4 shadow-lg w-100" alt="Tentang Kami">
            </div>

            <div class="col-md-7 ps-md-5">
                <h6 class="text-warning fw-bold text-uppercase">Tentang Kami</h6>
                <h2 class="fw-bold text-success mb-4">Membangun Generasi</h2>

                <p class="lead text-secondary">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, doloremque. Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, doloremque.   
                </p>

                <p class="text-secondary mb-4">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Officiis maxime sit accusamus iste aliquid possimus obcaecati. Perspiciatis perferendis qui incidunt, sunt aliquam nulla, architecto nihil similique, minus nobis totam velit.
                </p>

                <div class="d-flex align-items-center bg-white p3 rounded-3 border-start border-4 border-warning shadow-sm">
                    <div class="fs-1 me-3 text-warning">✓</div>
                    <div>
                        <p class="mb-0 fst-italic text-secondary">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Magni quasi, reiciendis rem voluptatibus suscipit dolore hic pariatur optio nihil accusamus aspernatur tenetur, debitis explicabo distinctio praesentium ipsam sint delectus architecto.</p>
                        <small class="fw-bold text-success">Kepala Sekolah</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-success bg-opacity-10 py-5 mt-5">
        <div class="container my-4">

            <div class="text-center mb-5">
                <h6 class="text-warning fw-bold text-uppercase">PPDB Online</h6>
                <h2 class="fw-bold text-success">Syarat Pendaftaran</h2>
                <p class="text-secondary">Siapkan dokumen-dokumen berikut untuk pendaftaran:</p>
            </div>

            <div class="row g-4 justify-content-center">

                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-3 rounded-4 hover-menu">
                        <div class="display-5 mb-2">📄</div>
                        <h6 class="fw-bold text-dark">Kartu Keluarga</h6>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-3 rounded-4 hover-menu">
                        <div class="display-5 mb-2">📜</div>
                        <h6 class="fw-bold text-dark">Akta Kelahiran</h6>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-3 rounded-4 hover-menu">
                        <div class="display-5 mb-2">📍</div>
                        <h6 class="fw-bold text-dark">Titik Koordinat Rumah </h6>
                        <small class="text-muted">Untuk data zonasi</small>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-3 rounded-4 hover-menu">
                        <div class="display-5 mb-2">📸</div>
                        <h6 class="fw-bold text-dark">Data Fisik Anak</h6>
                        <small class="text-muted">Foto, BB & TB (Opsional)</small>
                    </div>
                </div>

            </div>

            <div class="text-center mt-5 mb-5 pb-5">
                <button type="button" class="btn btn-success text-white btn-lg rounded-pill fw-bold shadow px-5" data-bs-toggle="modal" data-bs-target="#daftarModal">
                    Mulai Daftar
                </button>
            </div>

        </div>
    </div>

    <!-- Modal Pendaftaran (Pilih Sekolah) -->
    <div class="modal fade" id="daftarModal" tabindex="-1" aria-labelledby="daftarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold text-success" id="daftarModalLabel">Pendaftaran Peserta Didik Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body pt-3">
            <p class="text-muted small mb-4">Silahkan pilih cabang sekolah tempat Anda ingin mendaftarkan putra/putri Anda.</p>
            <form action="/pendaftaran-siswa" method="GET">
                <div class="mb-4">
                    <label class="form-label fw-bold">Pilih Sekolah Tujuan</label>
                    <select name="school_id" class="form-select form-select-lg border-success rounded-3 shadow-sm" required>
                        <option value="" disabled selected>-- Pilih Cabang Sekolah --</option>
                        @isset($schools)
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->nama_sekolah }} - {{ $school->alamat ?? '' }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold py-2 shadow-sm">Lanjut Mengisi Formulir <i class="bi bi-arrow-right"></i></button>
            </form>
          </div>
        </div>
      </div>
    </div>

@endsection