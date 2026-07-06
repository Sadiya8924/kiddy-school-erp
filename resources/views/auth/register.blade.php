@extends('layouts.app')

@section('konten_halaman')
<div class="container mt-5 mb-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                
                <div class="card-header bg-soft-green text-center py-4 border-0">
                    <div class="bg-white text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 60px; height: 60px;">
                        <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-1"></iconify-icon>
                    </div>
                    <h4 class="mb-0 fw-bold text-dark-green">Buat Akun Wali Murid</h4>
                    <p class="text-muted small mb-0 mt-1">Satu akun untuk memantau perkembangan ananda</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="/register">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Nama Lengkap Orang Tua</label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan nama Anda" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Alamat Email</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-secondary small">Kata Sandi</label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold text-secondary small">Konfirmasi Sandi</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang sandi" required>
                            </div>
                        </div>

                        <input type="hidden" name="role" value="wali_murid">

                        <div class="d-grid">
                            <button type="submit" class="btn text-white btn-lg rounded-pill fw-bold shadow-sm" style="background-color: #0d5c46;">
                                Daftar Akun
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4 pt-4 border-top small">
                        <span class="text-secondary">Sudah punya akun?</span>
                        <a href="/login" class="text-dark-green fw-bold text-decoration-none">Masuk di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection