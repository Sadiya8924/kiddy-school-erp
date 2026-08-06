<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings['site_title'] ?? 'Yayasan Al-Hikmah 2' }}</title>

    <script>
        window.APP_SETTINGS = @json($settings ?? []);
        window.AUTH_USER = @json(auth()->user());
    </script>

    <!-- Vite React Refresh & Entry Point -->
    @viteReactRefresh
    @vite(['resources/js/main.tsx'])
</head>
<body style="background-color: #F7F9FC; margin: 0; padding: 0;">
    <div id="root"></div>
</body>
</html>