<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 p-4">
    <div class="text-center" style="max-width: 480px;">
        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 96px; height: 96px;">
            <iconify-icon icon="solar:shield-warning-bold-duotone" style="font-size: 48px;"></iconify-icon>
        </div>
        <h1 class="display-4 fw-bold text-dark mb-2">403</h1>
        <h4 class="fw-bold text-dark mb-3">Akses Ditolak</h4>
        <p class="text-muted mb-4">{{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses untuk membuka halaman ini atau mengubah data sekolah lain.' }}</p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">Kembali</a>
            <a href="/dashboard" class="btn btn-primary rounded-pill px-4 fw-semibold">Ke Dashboard</a>
        </div>
    </div>
</body>
</html>
