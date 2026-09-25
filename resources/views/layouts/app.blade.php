<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#0d4f8b">

    <title>@yield('title', 'Kalisawah Asset Management')</title>

    @vite([
        'resources/css/app.css',
        'resources/css/asset-management.css',
        'resources/js/app.js'
    ])

    @stack('styles')
</head>

<body class="@yield('body-class', 'asset-page')">

    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    @stack('scripts')
</body>
</html>