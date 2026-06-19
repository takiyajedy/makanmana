<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MakanMana') }}</title>

    @include('partials.pwa')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        :root {
            --brand: #ff5722;
            --brand-2: #ff8a3d;
            --brand-grad: linear-gradient(135deg, #ff8a3d 0%, #ff5722 55%, #f7411e 100%);
            --ink: #1c1714;
            --muted: #7c736d;
            --line: #efe9e4;
            --bg: #fbf7f3;
            --green: #16a34a;
            --red: #e11d48;
            --shadow-lg: 0 24px 60px -20px rgba(247, 65, 30, .28), 0 12px 30px rgba(28, 23, 20, .08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            color: var(--ink);
            background: var(--bg);
            -webkit-font-smoothing: antialiased;
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
        }

        a { text-decoration: none; color: inherit; }

        /* ---------- Brand panel ---------- */
        .auth-brand {
            position: relative; overflow: hidden;
            flex: 0 0 44%; max-width: 560px;
            background: var(--brand-grad); color: #fff;
            padding: 48px 52px; display: flex; flex-direction: column; justify-content: space-between;
        }
        .auth-brand::before,
        .auth-brand::after {
            content: ""; position: absolute; border-radius: 50%;
            background: rgba(255, 255, 255, .12);
        }
        .auth-brand::before { width: 360px; height: 360px; top: -120px; right: -120px; }
        .auth-brand::after { width: 240px; height: 240px; bottom: -90px; left: -70px; background: rgba(255, 255, 255, .08); }

        .auth-brand .brand { display: flex; align-items: center; gap: 11px; font-weight: 800; font-size: 1.3rem; position: relative; z-index: 1; }
        .auth-brand .brand .logo {
            width: 42px; height: 42px; border-radius: 13px; display: grid; place-items: center;
            background: rgba(255, 255, 255, .2); backdrop-filter: blur(4px); font-size: 1.3rem;
        }

        .auth-brand .pitch { position: relative; z-index: 1; }
        .auth-brand .pitch h2 { font-size: clamp(1.9rem, 3vw, 2.6rem); font-weight: 800; letter-spacing: -.03em; line-height: 1.1; }
        .auth-brand .pitch p { margin-top: 14px; font-size: 1.05rem; opacity: .92; max-width: 34ch; }
        .auth-brand .perks { list-style: none; margin-top: 28px; display: flex; flex-direction: column; gap: 14px; }
        .auth-brand .perks li { display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .auth-brand .perks .ic {
            width: 32px; height: 32px; border-radius: 9px; display: grid; place-items: center;
            background: rgba(255, 255, 255, .18); flex-shrink: 0;
        }
        .auth-brand .foot-note { position: relative; z-index: 1; font-size: .85rem; opacity: .8; }

        /* ---------- Form side ---------- */
        .auth-main { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 24px; }
        .auth-card { width: 100%; max-width: 420px; }

        .auth-mobile-brand { display: none; align-items: center; gap: 10px; font-weight: 800; font-size: 1.25rem; margin-bottom: 26px; justify-content: center; }
        .auth-mobile-brand .logo { width: 38px; height: 38px; border-radius: 12px; display: grid; place-items: center; background: var(--brand-grad); font-size: 1.2rem; }
        .auth-mobile-brand b { color: var(--brand); }

        .auth-head h1 { font-size: 1.85rem; font-weight: 800; letter-spacing: -.02em; }
        .auth-head p { color: var(--muted); margin-top: 6px; margin-bottom: 28px; }

        .auth-status {
            background: #ecfdf3; border: 1px solid #bbf7d0; color: #15803d;
            border-radius: 12px; padding: 12px 14px; font-size: .9rem; font-weight: 600; margin-bottom: 20px;
        }

        .auth-field { margin-bottom: 18px; }
        .auth-label { display: block; font-weight: 600; font-size: .88rem; margin-bottom: 7px; }
        .auth-input {
            width: 100%; font-family: inherit; font-size: .98rem; color: var(--ink);
            background: #fff; border: 1.5px solid var(--line); border-radius: 13px;
            padding: 13px 15px; transition: border-color .15s, box-shadow .15s; outline: none;
        }
        .auth-input::placeholder { color: #b8aea7; }
        .auth-input:focus { border-color: var(--brand-2); box-shadow: 0 0 0 4px rgba(255, 138, 61, .18); }
        .auth-input.has-error { border-color: var(--red); }
        .auth-error { color: var(--red); font-size: .82rem; font-weight: 500; margin-top: 6px; }

        .auth-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 22px; flex-wrap: wrap; }
        .auth-check { display: inline-flex; align-items: center; gap: 9px; cursor: pointer; font-size: .9rem; color: var(--muted); }
        .auth-check input {
            width: 18px; height: 18px; border-radius: 6px; border: 1.5px solid #d8cfc8;
            accent-color: var(--brand); cursor: pointer;
        }
        .auth-link { font-size: .9rem; font-weight: 600; color: var(--brand); }
        .auth-link:hover { text-decoration: underline; }

        .auth-btn {
            width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            font-family: inherit; font-weight: 700; font-size: 1.02rem; cursor: pointer;
            border: none; border-radius: 14px; padding: 15px 20px; color: #fff;
            background: var(--brand-grad); box-shadow: 0 12px 26px -10px rgba(247, 65, 30, .8);
            transition: transform .15s, box-shadow .2s;
        }
        .auth-btn:hover { transform: translateY(-1px); box-shadow: 0 16px 32px -8px rgba(247, 65, 30, .9); }
        .auth-btn:active { transform: translateY(1px); }

        .auth-foot { text-align: center; margin-top: 24px; color: var(--muted); font-size: .92rem; }
        .auth-foot a { color: var(--brand); font-weight: 700; }
        .auth-foot a:hover { text-decoration: underline; }

        .auth-back { display: inline-flex; align-items: center; gap: 6px; color: var(--muted); font-size: .88rem; font-weight: 600; margin-bottom: 26px; }
        .auth-back:hover { color: var(--ink); }

        @media (max-width: 900px) {
            .auth-brand { display: none; }
            .auth-mobile-brand { display: flex; }
        }
    </style>
</head>

<body>
    <!-- Brand / pitch panel -->
    <aside class="auth-brand">
        <a href="{{ route('landing') }}" class="brand">
            <span class="logo">🍽️</span> MakanMana
        </a>

        <div class="pitch">
            <h2>Berhenti fikir, mula makan.</h2>
            <p>Sertai MakanMana dan biar kami pilihkan tempat makan terbaik berdekatan anda.</p>
            <ul class="perks">
                <li><span class="ic">🎲</span> Cadangan tempat makan secara rawak</li>
                <li><span class="ic">📍</span> Ikut lokasi GPS sebenar anda</li>
                <li><span class="ic">❤️</span> Simpan citarasa &amp; elak alahan makanan</li>
            </ul>
        </div>

        <p class="foot-note">© {{ date('Y') }} MakanMana — Dibina dengan Laravel &amp; Leaflet</p>
    </aside>

    <!-- Form side -->
    <main class="auth-main">
        <div class="auth-card">
            <a href="{{ route('landing') }}" class="auth-mobile-brand">
                <span class="logo">🍽️</span> Makan<b>Mana</b>
            </a>
            {{ $slot }}
        </div>
    </main>
</body>

</html>
