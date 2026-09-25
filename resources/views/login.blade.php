<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0d4f8b">
    <title>Login Admin | Kalisawah Asset Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- STYLE KHUSUS PESAN ERROR --}}
    <style>
        .login-error {
            margin-bottom: 18px;
            color: #dc2626 !important;
            font-family: Arial, Helvetica, sans-serif !important;
            font-size: 15px !important;
            font-weight: 600 !important;
            line-height: 1.5;
        }
    </style>
</head>

<body class="login-page">

    <main class="login-shell">

        {{-- =========================
             BAGIAN KIRI
        ========================== --}}
        <section class="login-showcase" aria-label="Informasi Kalisawah Asset Management">

            <div class="showcase-topline">

                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 48 48" fill="none">
                        <path
                            d="M24 5 39 11v11c0 9.4-6.2 17.8-15 21C15.2 39.8 9 31.4 9 22V11l15-6Z"
                            fill="currentColor"
                        />
                        <path
                            d="m16 24 5.2 5.2L32.5 18"
                            stroke="#fff"
                            stroke-width="3.2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </span>

                <span class="brand-name">KALISAWAH</span>

            </div>


            <div class="showcase-content">

                <p class="eyebrow">
                    Asset Management System
                </p>

                <h1>
                    Kelola aset,<br>
                    <em>lebih terarah.</em>
                </h1>

                <p class="showcase-copy">
                    Satu ruang untuk memantau, merawat, dan memastikan seluruh aset Kalisawah tetap siap digunakan.
                </p>

            </div>


            <div class="showcase-footer">

                <span class="footer-line"></span>

                <span>
                    ADMIN PORTAL
                </span>

            </div>


            <span class="showcase-shape shape-one" aria-hidden="true"></span>
            <span class="showcase-shape shape-two" aria-hidden="true"></span>

        </section>


        {{-- =========================
             BAGIAN KANAN
        ========================== --}}
        <section class="login-panel" aria-labelledby="login-title">

            <div class="login-card">


                {{-- MOBILE BRAND --}}
                <div class="mobile-brand">

                    <span class="brand-mark" aria-hidden="true">

                        <svg viewBox="0 0 48 48" fill="none">

                            <path
                                d="M24 5 39 11v11c0 9.4-6.2 17.8-15 21C15.2 39.8 9 31.4 9 22V11l15-6Z"
                                fill="currentColor"
                            />

                            <path
                                d="m16 24 5.2 5.2L32.5 18"
                                stroke="#fff"
                                stroke-width="3.2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                        </svg>

                    </span>

                    <span class="brand-name">
                        KALISAWAH
                    </span>

                </div>


                {{-- JUDUL LOGIN --}}
                <div class="login-heading">

                    <p class="eyebrow">
                        Selamat datang kembali
                    </p>

                    <h2 id="login-title">
                        Login Admin
                    </h2>

                    <p>
                        Masuk untuk mengelola data aset Kalisawah.
                    </p>

                </div>


                {{-- =========================
                     SATU PESAN ERROR SAJA
                ========================== --}}
                @if(session('error') || $errors->any())

                    <div class="login-error">
                        Email atau password salah.
                    </div>

                @endif


                {{-- FORM LOGIN --}}
                <form
                    class="login-form"
                    action="{{ route('login.process') }}"
                    method="POST"
                >

                    @csrf


                    {{-- EMAIL --}}
                    <div class="field-group">

                        <label for="email">
                            Email
                        </label>


                        <div class="input-wrap">

                            <span class="input-icon" aria-hidden="true">

                                <svg viewBox="0 0 24 24" fill="none">

                                    <path
                                        d="M4 6h16v12H4z"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linejoin="round"
                                    />

                                    <path
                                        d="m4 7 8 6 8-6"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />

                                </svg>

                            </span>


                            <input
                                id="email"
                                name="email"
                                type="email"
                                placeholder="Masukkan email"
                                autocomplete="email"
                                value="{{ old('email') }}"
                                required
                            >

                        </div>

                    </div>


                    {{-- PASSWORD --}}
                    <div class="field-group">

                        <div class="label-row">

                            <label for="password">
                                Password
                            </label>

                            <a href="#" class="forgot-link">
                                Lupa password?
                            </a>

                        </div>


                        <div class="input-wrap">

                            <span class="input-icon" aria-hidden="true">

                                <svg viewBox="0 0 24 24" fill="none">

                                    <rect
                                        x="5"
                                        y="10"
                                        width="14"
                                        height="10"
                                        rx="2"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    />

                                    <path
                                        d="M8 10V7a4 4 0 0 1 8 0v3M12 14v2"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />

                                </svg>

                            </span>


                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required
                            >


                            <button
                                type="button"
                                class="password-toggle"
                                id="password-toggle"
                                aria-label="Tampilkan password"
                                aria-pressed="false"
                            >

                                {{-- EYE --}}
                                <svg
                                    class="eye-icon"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linejoin="round"
                                    />

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="2.5"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    />

                                </svg>


                                {{-- EYE OFF --}}
                                <svg
                                    class="eye-off-icon"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="m3 3 18 18M10.6 6.7A9.9 9.9 0 0 1 12 6.5c6 0 9.5 5.5 9.5 5.5a16.7 16.7 0 0 1-3.1 3.7M6.5 6.8C4 8.4 2.5 12 2.5 12S6 17.5 12 17.5c.8 0 1.6-.1 2.3-.3M9.9 9.9a3 3 0 0 0 4.2 4.2"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />

                                </svg>

                            </button>

                        </div>

                    </div>


                    {{-- REMEMBER ME --}}
                    <label class="remember-me">

                        <input
                            type="checkbox"
                            name="remember"
                        >

                        <span
                            class="checkmark"
                            aria-hidden="true"
                        ></span>

                        Ingat saya di perangkat ini

                    </label>


                    {{-- TOMBOL LOGIN --}}
                    <button
                        type="submit"
                        class="login-button"
                    >

                        <span>
                            Masuk ke Dashboard
                        </span>


                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            aria-hidden="true"
                        >

                            <path
                                d="M5 12h13M13 6l6 6-6 6"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                        </svg>

                    </button>

                </form>


                <p class="login-note">
                    Akses ini khusus untuk administrator Kalisawah.
                </p>

            </div>


            <p class="copyright">
                © {{ date('Y') }} Kalisawah Asset Management
            </p>

        </section>

    </main>


    {{-- SCRIPT SHOW/HIDE PASSWORD --}}
    <script>

        const passwordInput =
            document.getElementById('password');

        const passwordToggle =
            document.getElementById('password-toggle');


        passwordToggle.addEventListener('click', () => {

            const isVisible =
                passwordInput.type === 'text';


            passwordInput.type =
                isVisible ? 'password' : 'text';


            passwordToggle.setAttribute(
                'aria-label',
                isVisible
                    ? 'Tampilkan password'
                    : 'Sembunyikan password'
            );


            passwordToggle.setAttribute(
                'aria-pressed',
                String(!isVisible)
            );


            passwordToggle.classList.toggle(
                'is-visible',
                !isVisible
            );

        });

    </script>

</body>
</html>