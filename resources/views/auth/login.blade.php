<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#11569c">

    <title>Login Admin | Kalisawah Asset Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #11569c;
            --primary-dark: #0d477f;
            --primary-soft: rgba(17, 86, 156, .14);
            --bg: #f4f6f9;
            --surface: #ffffff;
            --border: #dde3ea;
            --text: #172033;
            --muted: #6b778a;
            --danger: #b42318;
            --danger-bg: #fef3f2;
            --danger-border: #fecdca;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body.login-page {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background: var(--bg);
            color: var(--text);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* ---------- Kartu login ---------- */

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px 36px 36px;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--surface);
            box-shadow: 0 10px 30px rgba(23, 32, 51, .06);
        }

        /* ---------- Brand ---------- */

        .brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 28px;
            text-align: center;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            margin-bottom: 16px;
            border-radius: 14px;
            background: var(--primary);
        }

        .brand-mark svg {
            width: 30px;
            height: 30px;
        }

        .brand h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 650;
            letter-spacing: -.01em;
            color: var(--text);
        }

        .brand p {
            margin: 6px 0 0;
            font-size: 14px;
            line-height: 1.5;
            color: var(--muted);
        }

        /* ---------- Pesan error ---------- */

        .login-error {
            margin-bottom: 20px;
            padding: 11px 14px;
            border: 1px solid var(--danger-border);
            border-radius: 10px;
            background: var(--danger-bg);
            color: var(--danger);
            font-size: 13px;
            line-height: 1.5;
        }

        /* ---------- Form ---------- */

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .field label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
            height: 46px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--surface);
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .input-wrap:hover {
            border-color: #c3cbd6;
        }

        .input-wrap:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-soft);
        }

        .input-wrap input {
            flex: 1;
            width: 100%;
            min-width: 0;
            height: 100%;
            padding: 0 14px;
            border: none;
            outline: none;
            background: transparent;
            color: var(--text);
            font-family: inherit;
            font-size: 14px;
        }

        .input-wrap input::placeholder {
            color: #9aa5b5;
        }

        /* ---------- Show / hide password ---------- */

        .password-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 44px;
            height: 100%;
            padding: 0;
            border: none;
            border-radius: 0 10px 10px 0;
            background: transparent;
            color: #8d99aa;
            cursor: pointer;
            transition: color .15s ease;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .password-toggle:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: -2px;
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
        }

        .eye-off-icon {
            display: none;
        }

        .password-toggle.is-visible .eye-icon {
            display: none;
        }

        .password-toggle.is-visible .eye-off-icon {
            display: block;
        }

        /* ---------- Ingat saya ---------- */

        .remember-me {
            display: inline-flex;
            align-items: center;
            align-self: flex-start;
            gap: 9px;
            font-size: 13px;
            color: var(--muted);
            cursor: pointer;
            user-select: none;
        }

        .remember-me input {
            width: 16px;
            height: 16px;
            margin: 0;
            accent-color: var(--primary);
            cursor: pointer;
        }

        /* ---------- Tombol ---------- */

        .login-button {
            width: 100%;
            height: 46px;
            margin-top: 4px;
            border: none;
            border-radius: 10px;
            background: var(--primary);
            color: #fff;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s ease;
        }

        .login-button:hover {
            background: var(--primary-dark);
        }

        .login-button:focus-visible {
            outline: 3px solid var(--primary-soft);
            outline-offset: 2px;
        }

        /* ---------- Footer ---------- */

        .copyright {
            margin: 22px 0 0;
            font-size: 12px;
            color: #8d99aa;
            text-align: center;
        }

        /* ---------- Layar kecil ---------- */

        @media (max-width: 480px) {
            body.login-page {
                padding: 16px 12px;
            }

            .login-card {
                padding: 32px 22px 28px;
                border-radius: 14px;
            }

            .brand h1 {
                font-size: 20px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
        }
    </style>
</head>

<body class="login-page">

    <main class="login-card">

        {{-- BRAND --}}
        <div class="brand">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none">
                    <path
                        d="M24 5 39 11v11c0 9.4-6.2 17.8-15 21C15.2 39.8 9 31.4 9 22V11l15-6Z"
                        fill="rgba(255,255,255,.22)"
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

            <h1>Login Admin</h1>
            <p>Masuk untuk mengelola aset Kalisawah.</p>
        </div>


        {{-- PESAN ERROR --}}
        @if (session('error') || $errors->any())
            <div class="login-error" role="alert">
                Email atau password salah. Silakan periksa kembali.
            </div>
        @endif


        {{-- FORM LOGIN --}}
        <form
            class="login-form"
            action="{{ route('auth.authenticate') }}"
            method="POST"
        >
            @csrf

            {{-- EMAIL --}}
            <div class="field">
                <label for="email">Email</label>

                <div class="input-wrap">
                    <input
                        id="email"
                        name="email"
                        type="email"
                        placeholder="nama@email.com"
                        autocomplete="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                </div>
            </div>


            {{-- PASSWORD --}}
            <div class="field">
                <label for="password">Password</label>

                <div class="input-wrap">
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
                        <svg class="eye-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path
                                d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linejoin="round"
                            />
                            <circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.8" />
                        </svg>

                        <svg class="eye-off-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
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


            {{-- INGAT SAYA --}}
            <label class="remember-me">
                <input type="checkbox" name="remember" value="1">
                <span>Ingat saya di perangkat ini</span>
            </label>


            {{-- TOMBOL LOGIN --}}
            <button type="submit" class="login-button">Masuk</button>
        </form>

    </main>

    <p class="copyright">
        © {{ date('Y') }} Kalisawah Asset Management
    </p>


    {{-- SCRIPT SHOW / HIDE PASSWORD --}}
    <script>
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');

        if (passwordInput && passwordToggle) {
            passwordToggle.addEventListener('click', () => {
                const isVisible = passwordInput.type === 'text';

                passwordInput.type = isVisible ? 'password' : 'text';

                passwordToggle.setAttribute(
                    'aria-label',
                    isVisible ? 'Tampilkan password' : 'Sembunyikan password'
                );
                passwordToggle.setAttribute('aria-pressed', String(!isVisible));
                passwordToggle.classList.toggle('is-visible', !isVisible);
            });
        }
    </script>

</body>
</html>