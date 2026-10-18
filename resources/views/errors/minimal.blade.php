<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Terjadi Kendala - Wandee')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #020806;
            --card-bg: rgba(14, 28, 20, 0.75);
            --card-border: rgba(57, 255, 120, 0.18);
            --accent: #39ff78;
            --accent-hover: #2ee066;
            --text-primary: #e8f0eb;
            --text-muted: #8ba594;
            --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: var(--font);
        }

        body {
            background-color: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient glow effects */
        .ambient-glow-1 {
            position: fixed;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 168, 77, 0.25) 0%, transparent 70%);
            top: -100px;
            left: -100px;
            pointer-events: none;
            z-index: 0;
        }

        .ambient-glow-2 {
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(57, 255, 120, 0.15) 0%, transparent 70%);
            bottom: -150px;
            right: -100px;
            pointer-events: none;
            z-index: 0;
        }

        .error-card {
            position: relative;
            z-index: 1;
            max-width: 560px;
            width: 100%;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 28px;
            padding: 48px 36px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.55), 0 0 30px rgba(57, 255, 120, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .logo-wrap {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 24px;
            text-decoration: none;
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .logo-wrap span {
            color: var(--accent);
        }

        .code-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: rgba(57, 255, 120, 0.1);
            border: 1px solid rgba(57, 255, 120, 0.25);
            border-radius: 999px;
            color: var(--accent);
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
        }

        .error-code {
            font-size: clamp(4.5rem, 14vw, 6.5rem);
            font-weight: 800;
            line-height: 1;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #ffffff 40%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -2px;
        }

        .error-title {
            font-size: clamp(1.25rem, 4vw, 1.6rem);
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .error-desc {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.65;
            margin-bottom: 32px;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 24px;
            border-radius: 999px;
            font-size: 0.92rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.25s ease;
            border: none;
        }

        .btn-primary {
            background: var(--accent);
            color: #031508;
            box-shadow: 0 4px 20px rgba(57, 255, 120, 0.3);
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(57, 255, 120, 0.45);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.22);
            transform: translateY(-2px);
        }

        .footer-note {
            margin-top: 32px;
            font-size: 0.8rem;
            color: rgba(232, 240, 235, 0.4);
            position: relative;
            z-index: 1;
        }

        .footer-note a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-note a:hover {
            color: var(--accent);
        }

        svg {
            display: inline-block;
            vertical-align: middle;
        }

        @media (max-width: 480px) {
            .error-card {
                padding: 36px 20px;
                border-radius: 22px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <main class="error-card">
        <a href="{{ url('/') }}" class="logo-wrap">
            Wandee<span>.</span>
        </a>

        <div>
            <span class="code-pill">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                Galat HTTP @yield('code', 'Error')
            </span>
        </div>

        <div class="error-code">@yield('code', '404')</div>

        <h1 class="error-title">@yield('message', 'Halaman Tidak Ditemukan')</h1>

        <p class="error-desc">
            @yield('description', 'Halaman atau petualangan yang kamu tuju saat ini tidak tersedia atau telah dipindahkan.')
        </p>

        <div class="btn-group">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Kembali ke Beranda
            </a>

            <button type="button" onclick="history.back()" class="btn btn-secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali
            </button>
        </div>
    </main>

    <p class="footer-note">
        &copy; {{ date('Y') }} Wandee Adventure. Butuh bantuan? <a href="{{ url('/tentang-kami') }}">Hubungi Tim Kami</a>
    </p>
</body>
</html>
