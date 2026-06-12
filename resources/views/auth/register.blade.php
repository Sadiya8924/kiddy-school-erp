@extends('layouts.app')

@section('konten_halaman')
<div class="container mt-5 mb-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                
                <div class="card-header bg-warning text-dark text-center py-4 border-0">
                    <div class="display-4 mb-2">📝</div>
                    <h4 class="mb-0 fw-bold">Buat Akun Baru</h4>
                    <small>Khusus untuk pendaftaran Wali Murid</small>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form>
                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary small">Nama Lengkap</label>
                            <input type="text" class="form-control border-success focus-ring focus-ring-success" placeholder="Masukkan nama Anda">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary small">Alamat Email</label>
                            <input type="email" class="form-control border-success focus-ring focus-ring-success" placeholder="nama@email.com">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium text-secondary small">Kata Sandi</label>
                                <input type="password" class="form-control border-success focus-ring focus-ring-success" placeholder="Minimal 8 karakter">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-medium text-secondary small">Ulangi Sandi</label>
                                <input type="password" class="form-control border-success focus-ring focus-ring-success" placeholder="Ketik ulang sandi">
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="button" class="btn btn-success btn-lg rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-person-plus-fill me-2"></i> Daftar Akun
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top small">
                        <span class="text-secondary">Sudah punya akun?</span>
                        <a href="/login" class="text-warning fw-bold text-decoration-none">Masuk di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection