<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings['site_title'] ?? \App\Models\Setting::get('site_title', 'Kiddy School ERP') }}</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.APP_SETTINGS = @json($settings ?? []);
        window.AUTH_USER = @json(auth()->user());
        window.isAuth = {{ Auth::check() ? 'true' : 'false' }};
        window.csrfToken = "{{ csrf_token() }}";
        window.WRONG_YAYASAN = @json(session('wrong_yayasan'));
        window.YAYASANS = @json($yayasans ?? []);
    </script>

    <!-- Vite React Refresh & Entry Point -->
    @viteReactRefresh
    @vite(['resources/js/main.tsx'])
</head>
<body style="background-color: #F7F9FC; margin: 0; padding: 0;">
    <div id="root"></div>

    @if(session('wrong_yayasan'))
    @php $wy = session('wrong_yayasan'); @endphp
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Akses Portal Ditolak!',
                    html: `Akun <b>{{ $wy['user_email'] }}</b> terdaftar sebagai pengguna <b>{{ $wy['correct_yayasan_name'] }}</b>.<br><br>Halaman ini khusus untuk Owner / Pengelola Platform SaaS.<br>Silakan masuk melalui portal resmi yayasan Anda.`,
                    confirmButtonText: '➡️ Pindah ke Portal {{ $wy['correct_yayasan_name'] }}',
                    confirmButtonColor: '#2563EB',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ $wy['correct_yayasan_url'] }}";
                    }
                });
            }
        });
    </script>
    @endif
</body>
</html>