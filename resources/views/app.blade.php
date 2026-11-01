<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | Kalisawah Asset Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg: #f0f3f8;
            --surface: #ffffff;
            --border: #e6e9ee;
            --text: #1f2937;
            --muted: #6b7280;
            --primary: #2a5d93;
            --hover: #f0f2f5;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* =====================================================
           NAVBAR
        ===================================================== */

        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .topbar-inner {
            display: flex;
            align-items: center;
            gap: 28px;
            max-width: 1180px;
            height: 60px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            color: var(--text);
            font-size: 15px;
            font-weight: 650;
            text-decoration: none;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: var(--primary);
        }

        .brand-mark svg {
            width: 18px;
            height: 18px;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 2px;
            flex: 1;
            min-width: 0;
        }

        .nav a {
            display: inline-flex;
            align-items: center;
            height: 34px;
            padding: 0 12px;
            border-radius: 8px;
            color: var(--muted);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            white-space: nowrap;
            transition: background .15s ease, color .15s ease;
        }

        .nav a:hover {
            background: var(--hover);
            color: var(--text);
        }

        .nav a.is-active {
            background: var(--hover);
            color: var(--text);
            font-weight: 600;
        }

        .logout-form {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            margin: 0;
        }

        .logout-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 36px;
            padding: 0 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            color: var(--text);
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            line-height: 1;
            cursor: pointer;
            transition: background .15s ease;
        }

        .logout-button:hover {
            background: var(--hover);
        }

        .logout-button svg {
            display: block;
            width: 16px;
            height: 16px;
            color: var(--muted);
        }

        .nav a:focus-visible,
        .logout-button:focus-visible,
        .brand:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* =====================================================
           KONTEN
        ===================================================== */

        .page {
            max-width: 1180px;
            margin: 0 auto;
            padding: 32px 24px 48px;
        }

        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 760px) {
            .topbar-inner {
                flex-wrap: wrap;
                gap: 0;
                height: auto;
                padding: 0 16px;
            }

            .brand {
                height: 56px;
            }

            .logout-form {
                margin-left: auto;
                height: 56px;
            }

            .nav {
                order: 3;
                width: 100%;
                flex: 1 1 100%;
                padding-bottom: 10px;
                overflow-x: auto;
                scrollbar-width: none;
            }

            .nav::-webkit-scrollbar {
                display: none;
            }

            .page {
                padding: 24px 16px 40px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    @php
        $logoutRoute = Route::has('auth.logout')
            ? route('auth.logout')
            : (Route::has('logout') ? route('logout') : url('/logout'));

        $dashboardUrl = Route::has('dashboard') ? route('dashboard') : url('/');
    @endphp

    <header class="topbar">
        <div class="topbar-inner">

            <a href="{{ $dashboardUrl }}" class="brand">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 48 48" fill="none">
                        <path
                            d="M24 5 39 11v11c0 9.4-6.2 17.8-15 21C15.2 39.8 9 31.4 9 22V11l15-6Z"
                            fill="rgba(255,255,255,.22)"
                        />
                        <path
                            d="m16 24 5.2 5.2L32.5 18"
                            stroke="#fff"
                            stroke-width="3.4"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </span>
                Kalisawah
            </a>

            <nav class="nav" aria-label="Menu utama">
                <a href="{{ $dashboardUrl }}"
                   class="{{ request()->is('/') || request()->routeIs('dashboard') ? 'is-active' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('aset.index') }}"
                   class="{{ request()->routeIs('aset.*') ? 'is-active' : '' }}">
                    Data Aset
                </a>

                <a href="{{ route('kategori.index') }}"
                   class="{{ request()->routeIs('kategori.*') ? 'is-active' : '' }}">
                    Kategori
                </a>

                <a href="{{ route('jenis-aset.index') }}"
                   class="{{ request()->routeIs('jenis-aset.*') ? 'is-active' : '' }}">
                    Jenis Aset
                </a>

                <a href="{{ route('lokasi.index') }}"
                   class="{{ request()->routeIs('lokasi.*') ? 'is-active' : '' }}">
                    Lokasi
                </a>
            </nav>

            <form class="logout-form" action="{{ $logoutRoute }}" method="POST">
                @csrf

                <button type="submit" class="logout-button">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h3"/>
                        <path d="M16 8l4 4-4 4M20 12H9"/>
                    </svg>
                    Keluar
                </button>
            </form>

        </div>
    </header>

    <main class="page">
        @yield('content')
    </main>

    @stack('scripts')

</body>
</html>