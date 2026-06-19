<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MakanMana — Tak tahu nak makan apa? Biar kami pilih.</title>

    @include('partials.pwa')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />

    <style>
        :root {
            --brand: #ff5722;
            --brand-2: #ff8a3d;
            --brand-grad: linear-gradient(135deg, #ff8a3d 0%, #ff5722 55%, #f7411e 100%);
            --ink: #1c1714;
            --muted: #7c736d;
            --line: #efe9e4;
            --bg: #fbf7f3;
            --card: #ffffff;
            --green: #16a34a;
            --shadow-sm: 0 1px 2px rgba(28, 23, 20, .06), 0 4px 14px rgba(28, 23, 20, .05);
            --shadow-lg: 0 24px 60px -20px rgba(247, 65, 30, .35), 0 12px 30px rgba(28, 23, 20, .08);
            --radius: 20px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            color: var(--ink);
            background: var(--bg);
            -webkit-font-smoothing: antialiased;
            line-height: 1.5;
        }

        a { text-decoration: none; color: inherit; }

        .wrap { width: 100%; max-width: 1180px; margin-inline: auto; padding-inline: 22px; }

        /* ---------- Navbar ---------- */
        .nav {
            position: sticky; top: 0; z-index: 1000;
            backdrop-filter: saturate(180%) blur(14px);
            background: rgba(251, 247, 243, .82);
            border-bottom: 1px solid var(--line);
        }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; height: 68px; }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: 1.2rem; letter-spacing: -.02em; }
        .brand .logo {
            width: 38px; height: 38px; border-radius: 12px; display: grid; place-items: center;
            background: var(--brand-grad); box-shadow: 0 6px 16px -4px rgba(247, 65, 30, .6);
            font-size: 1.15rem;
        }
        .brand b { color: var(--brand); }
        .nav-actions { display: flex; align-items: center; gap: 10px; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            font-family: inherit; font-weight: 600; font-size: .92rem; cursor: pointer;
            border-radius: 12px; padding: 10px 18px; border: 1px solid transparent;
            transition: transform .15s ease, box-shadow .2s ease, background .2s ease, border-color .2s ease;
            white-space: nowrap;
        }
        .btn:active { transform: translateY(1px); }
        .btn-ghost { background: transparent; color: var(--ink); }
        .btn-ghost:hover { background: rgba(28, 23, 20, .05); }
        .btn-outline { background: #fff; border-color: var(--line); color: var(--ink); }
        .btn-outline:hover { border-color: #d8cfc8; }
        .btn-primary { background: var(--brand-grad); color: #fff; box-shadow: 0 10px 24px -10px rgba(247, 65, 30, .8); }
        .btn-primary:hover { box-shadow: 0 14px 30px -8px rgba(247, 65, 30, .9); transform: translateY(-1px); }
        .btn-lg { padding: 15px 28px; font-size: 1.02rem; border-radius: 14px; }

        /* ---------- Hero ---------- */
        .hero { position: relative; overflow: hidden; }
        .hero::before {
            content: ""; position: absolute; inset: 0; z-index: -1;
            background:
                radial-gradient(60% 60% at 85% 0%, rgba(255, 138, 61, .25), transparent 70%),
                radial-gradient(50% 50% at 5% 30%, rgba(255, 87, 34, .14), transparent 70%);
        }
        .hero-inner { padding: 70px 0 56px; text-align: center; display: grid; justify-items: center; gap: 22px; }
        .pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff; border: 1px solid var(--line); border-radius: 999px;
            padding: 7px 15px; font-size: .82rem; font-weight: 600; color: var(--muted);
            box-shadow: var(--shadow-sm);
        }
        .pill .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--green); box-shadow: 0 0 0 4px rgba(22, 163, 74, .15); }
        h1.title {
            font-size: clamp(2.1rem, 5.2vw, 3.7rem); font-weight: 800; letter-spacing: -.03em;
            line-height: 1.05; max-width: 16ch;
        }
        h1.title .hl { background: var(--brand-grad); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .lede { font-size: clamp(1rem, 2vw, 1.18rem); color: var(--muted); max-width: 56ch; }
        .hero-cta { display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; margin-top: 4px; }
        .trust { display: flex; flex-wrap: wrap; gap: 22px; justify-content: center; margin-top: 14px; color: var(--muted); font-size: .9rem; }
        .trust b { color: var(--ink); font-weight: 700; }

        /* ---------- App card ---------- */
        .app { padding-bottom: 80px; }
        .app-card {
            background: var(--card); border: 1px solid var(--line); border-radius: 26px;
            box-shadow: var(--shadow-lg); overflow: hidden;
            display: grid; grid-template-columns: 1.55fr 1fr;
        }
        #map { width: 100%; height: 100%; min-height: 560px; background: #e8e2dc; }
        .leaflet-routing-container { display: none; }

        .panel { padding: 26px; display: flex; flex-direction: column; gap: 18px; border-left: 1px solid var(--line); }
        .panel-head h2 { font-size: 1.3rem; font-weight: 800; letter-spacing: -.02em; }
        .panel-head p { color: var(--muted); font-size: .92rem; margin-top: 2px; }

        .slider-box { background: var(--bg); border: 1px solid var(--line); border-radius: 16px; padding: 16px 18px; }
        .slider-top { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 12px; }
        .slider-top label { font-weight: 600; font-size: .9rem; color: var(--muted); }
        .slider-top .val { font-weight: 800; font-size: 1.15rem; }
        .slider-top .val span { color: var(--brand); }
        input[type=range] {
            -webkit-appearance: none; appearance: none; width: 100%; height: 8px; border-radius: 999px;
            background: linear-gradient(var(--brand), var(--brand)) 0/var(--fill, 1%) 100% no-repeat, var(--line);
            cursor: pointer; outline: none;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; appearance: none; width: 22px; height: 22px; border-radius: 50%;
            background: #fff; border: 4px solid var(--brand); box-shadow: var(--shadow-sm); cursor: grab;
        }
        input[type=range]::-moz-range-thumb {
            width: 22px; height: 22px; border-radius: 50%; background: #fff; border: 4px solid var(--brand); cursor: grab;
        }

        .choose-btn { width: 100%; }
        .choose-btn .ic { font-size: 1.1rem; }

        .list-head { display: flex; align-items: center; justify-content: space-between; }
        .list-head h3 { font-size: .82rem; text-transform: uppercase; letter-spacing: .08em; color: var(--muted); font-weight: 700; }
        .count-badge { background: var(--bg); border: 1px solid var(--line); border-radius: 999px; padding: 3px 12px; font-size: .82rem; font-weight: 700; }

        .rlist { list-style: none; display: flex; flex-direction: column; gap: 10px; overflow-y: auto; max-height: 320px; padding-right: 4px; }
        .rlist::-webkit-scrollbar { width: 6px; }
        .rlist::-webkit-scrollbar-thumb { background: #e2d9d2; border-radius: 999px; }
        .ritem {
            display: flex; align-items: center; gap: 14px; padding: 13px 14px; border-radius: 14px;
            border: 1px solid var(--line); background: #fff; cursor: pointer;
            transition: border-color .15s, transform .12s, box-shadow .2s;
        }
        .ritem:hover { border-color: var(--brand-2); transform: translateX(2px); box-shadow: var(--shadow-sm); }
        .ritem.is-chosen { border-color: var(--brand); background: #fff7f3; }
        .ritem .ava { width: 42px; height: 42px; border-radius: 12px; flex-shrink: 0; display: grid; place-items: center; font-size: 1.25rem; background: linear-gradient(135deg, #fff1e9, #ffe2d2); }
        .ritem .info { min-width: 0; flex: 1; }
        .ritem .info .nm { font-weight: 700; font-size: .95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ritem .info .meta { color: var(--muted); font-size: .82rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ritem .dist { flex-shrink: 0; font-weight: 700; font-size: .82rem; color: var(--brand); background: #fff1e9; border-radius: 999px; padding: 4px 10px; }

        .empty { text-align: center; color: var(--muted); padding: 30px 10px; font-size: .92rem; }
        .empty .em { font-size: 2rem; display: block; margin-bottom: 8px; }

        /* ---------- How it works ---------- */
        .how { padding: 10px 0 90px; }
        .how h2 { text-align: center; font-size: clamp(1.6rem, 3.5vw, 2.3rem); font-weight: 800; letter-spacing: -.02em; }
        .how .sub { text-align: center; color: var(--muted); margin-top: 8px; }
        .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-top: 38px; }
        .step { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 26px; box-shadow: var(--shadow-sm); }
        .step .num { width: 44px; height: 44px; border-radius: 13px; display: grid; place-items: center; font-size: 1.4rem; background: linear-gradient(135deg, #fff1e9, #ffe2d2); margin-bottom: 14px; }
        .step h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 6px; }
        .step p { color: var(--muted); font-size: .92rem; }

        /* ---------- Footer ---------- */
        footer { border-top: 1px solid var(--line); background: #fff; }
        .foot { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 14px; padding: 26px 0; color: var(--muted); font-size: .9rem; }

        /* Tablet */
        @media (max-width: 880px) {
            .app-card { grid-template-columns: 1fr; }
            #map { min-height: 420px; }
            .panel { border-left: none; border-top: 1px solid var(--line); }
            .nav .hide-sm { display: none; }
        }
        /* Telefon */
        @media (max-width: 560px) {
            .wrap { padding-inline: 16px; }
            .hero-inner { padding: 48px 0 40px; }
            .steps { grid-template-columns: 1fr; }
            .hero-cta { width: 100%; }
            .hero-cta .btn { flex: 1; }
            #map { min-height: 340px; }
            .panel { padding: 20px; }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <header class="nav">
        <div class="wrap nav-inner">
            <a href="{{ route('landing') }}" class="brand">
                <span class="logo">🍽️</span> Makan<b>Mana</b>
            </a>
            <nav class="nav-actions">
                @auth
                    <a href="{{ route('recommend.index') }}" class="btn btn-ghost hide-sm">Cadangan</a>
                    <a href="{{ route('preferences.edit') }}" class="btn btn-outline">😋 Citarasa</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost">Log keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Log masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <!-- Hero -->
    <section class="hero">
        <div class="wrap hero-inner">
            @auth
                <span class="pill"><span class="dot"></span>
                    @if ($personalised) Pilihan ikut citarasa anda diaktifkan @else Selamat datang, {{ auth()->user()->name }}! @endif
                </span>
                <h1 class="title">Hari ni nak <span class="hl">makan apa</span>?</h1>
                <p class="lede">
                    @if ($personalised)
                        Buka peta dan tekan "Pilihkan untuk saya" — kami utamakan tempat makan yang <b>padan citarasa</b> anda dan elak alahan.
                    @else
                        Buka peta dan biar kami pilihkan. <b>Set citarasa</b> anda dahulu untuk cadangan yang lebih peribadi.
                    @endif
                </p>
                <div class="hero-cta">
                    <a href="#explore" class="btn btn-primary btn-lg">🎲 Pilihkan untuk saya</a>
                    <a href="{{ route('preferences.edit') }}" class="btn btn-outline btn-lg">😋 {{ $personalised ? 'Ubah' : 'Set' }} citarasa</a>
                </div>
                <div class="trust">
                    <span><b>{{ $restaurants->count() }}+</b> tempat makan</span>
                    <span><b>Live</b> lokasi GPS anda</span>
                    <span><b>{{ $personalised ? 'Peribadi' : 'Rawak' }}</b> cadangan</span>
                </div>
            @else
                <span class="pill"><span class="dot"></span> Tak perlu akaun — terus guna</span>
                <h1 class="title">Tak tahu nak <span class="hl">makan apa</span> hari ni?</h1>
                <p class="lede">
                    Buka peta, pilih jarak dari lokasi anda, dan biar MakanMana
                    <b>pilihkan tempat makan secara rawak</b>. Tamatkan drama "terserah" sekarang.
                </p>
                <div class="hero-cta">
                    <a href="#explore" class="btn btn-primary btn-lg">🎲 Pilihkan untuk saya</a>
                    <a href="#how" class="btn btn-outline btn-lg">Macam mana ia berfungsi?</a>
                </div>
                <div class="trust">
                    <span><b>{{ $restaurants->count() }}+</b> tempat makan</span>
                    <span><b>Live</b> lokasi GPS anda</span>
                    <span><b>0</b> kos &amp; tiada login</span>
                </div>
            @endauth
        </div>
    </section>

    <!-- Interactive app -->
    <section id="explore" class="app">
        <div class="wrap">
            <div class="app-card">
                <div id="map"></div>
                <aside class="panel">
                    <div class="panel-head">
                        <h2>Cari di sekeliling anda</h2>
                        @auth
                            @if ($personalised)
                                <p>✨ Pilihan diutamakan ikut citarasa anda (alahan dielak).</p>
                            @else
                                <p>Laraskan jarak, kemudian tekan butang ajaib.</p>
                            @endif
                        @else
                            <p>Laraskan jarak, kemudian tekan butang ajaib.</p>
                        @endauth
                    </div>

                    <div class="slider-box">
                        <div class="slider-top">
                            <label for="distanceSlider">Jarak maksimum</label>
                            <span class="val"><span id="distanceValue">5</span> km</span>
                        </div>
                        <input type="range" id="distanceSlider" min="1" max="100" step="1" value="5">
                    </div>

                    <button id="chooseBtn" class="btn btn-primary btn-lg choose-btn">
                        <span class="ic">🎲</span> Pilihkan untuk saya
                    </button>

                    <div class="list-head">
                        <h3>Berhampiran anda</h3>
                        <span class="count-badge" id="restaurantCount">0</span>
                    </div>

                    <ul class="rlist" id="restaurantList">
                        <li class="empty">
                            <span class="em">📍</span>
                            Benarkan akses lokasi untuk tunjuk tempat makan berhampiran…
                        </li>
                    </ul>
                </aside>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section id="how" class="how">
        <div class="wrap">
            <h2>Tiga langkah, satu keputusan</h2>
            <p class="sub">Tak payah fikir lama. Serahkan pada nasib (yang dah kami tapis).</p>
            <div class="steps">
                <div class="step">
                    <div class="num">📍</div>
                    <h3>1. Kongsi lokasi</h3>
                    <p>Kami guna GPS peranti anda untuk cari tempat makan yang betul-betul dekat.</p>
                </div>
                <div class="step">
                    <div class="num">🎚️</div>
                    <h3>2. Set jarak</h3>
                    <p>Tarik slider — nak yang dalam 1 km saja, atau jauh sikit pun boleh.</p>
                </div>
                <div class="step">
                    <div class="num">🎲</div>
                    <h3>3. Biar kami pilih</h3>
                    <p>Tekan "Pilihkan untuk saya" dan kami cadangkan satu tempat + tunjuk jalan.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="wrap foot">
            <span>🍽️ <b style="color:var(--ink)">MakanMana</b> — © {{ date('Y') }}</span>
            <span>Dibina dengan Laravel &amp; Leaflet</span>
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const restaurants = @json($restaurants);
        const personalised = @json($personalised ?? false);
        const DEFAULT_VIEW = [3.1390, 101.6869]; // Kuala Lumpur

        let map, userLat = null, userLng = null;
        let nearbyMarkers = [];
        let lastChosen = null;
        let routingControl = null;

        const slider = document.getElementById('distanceSlider');
        const distanceValue = document.getElementById('distanceValue');
        const listEl = document.getElementById('restaurantList');
        const countEl = document.getElementById('restaurantCount');

        function paintSlider() {
            const pct = (slider.value - slider.min) / (slider.max - slider.min) * 100;
            slider.style.setProperty('--fill', pct + '%');
        }

        function userIcon() {
            return L.divIcon({
                className: '',
                html: `<div style="width:20px;height:20px;border-radius:50%;background:#2563eb;border:3px solid #fff;box-shadow:0 0 0 6px rgba(37,99,235,.25)"></div>`,
                iconSize: [20, 20], iconAnchor: [10, 10]
            });
        }
        function pinIcon(active) {
            const color = active ? '#f7411e' : '#ff8a3d';
            return L.divIcon({
                className: '',
                html: `<div style="font-size:30px;line-height:1;filter:drop-shadow(0 3px 4px rgba(0,0,0,.3));transform:translateY(-4px)">📍</div>
                       <div style="position:absolute;top:5px;left:50%;transform:translateX(-50%);width:9px;height:9px;border-radius:50%;background:${color}"></div>`,
                iconSize: [30, 34], iconAnchor: [15, 30], popupAnchor: [0, -28]
            });
        }

        function initMap() {
            map = L.map('map', { zoomControl: true }).setView(DEFAULT_VIEW, 13);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap &copy; CARTO', maxZoom: 19
            }).addTo(map);

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(onLocation, onLocationError, {
                    enableHighAccuracy: true, timeout: 7000
                });
            } else {
                onLocationError();
            }
        }

        function onLocation(pos) {
            userLat = pos.coords.latitude;
            userLng = pos.coords.longitude;
            L.marker([userLat, userLng], { icon: userIcon() }).addTo(map).bindPopup('📍 Anda di sini');
            map.setView([userLat, userLng], 14);
            refresh();
        }

        function onLocationError() {
            Swal.fire({
                icon: 'info',
                title: 'Lokasi tidak tersedia',
                text: 'Kami guna kawasan KL sebagai contoh. Benarkan akses lokasi untuk hasil tepat.',
                confirmButtonColor: '#ff5722'
            });
            // Use default location so the demo still works
            userLat = DEFAULT_VIEW[0];
            userLng = DEFAULT_VIEW[1];
            refresh();
        }

        function haversine(lat1, lon1, lat2, lon2) {
            const R = 6371, toRad = d => d * Math.PI / 180;
            const dLat = toRad(lat2 - lat1), dLon = toRad(lon2 - lon1);
            const a = Math.sin(dLat / 2) ** 2 + Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon / 2) ** 2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        // URL universal Google Maps — buka app di telefon, web di PC.
        function googleMapsUrl(lat, lng) {
            if (userLat !== null && userLng !== null) {
                return `https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLng}&destination=${lat},${lng}&travelmode=driving`;
            }
            return `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
        }

        function refresh() {
            if (userLat === null) return;
            const maxDist = parseInt(slider.value, 10);

            nearbyMarkers.forEach(m => map.removeLayer(m.marker));
            nearbyMarkers = [];

            restaurants.forEach(r => {
                if (r.location_lat == null || r.location_lng == null) return;
                const d = haversine(userLat, userLng, +r.location_lat, +r.location_lng);
                if (d <= maxDist) {
                    const marker = L.marker([r.location_lat, r.location_lng], { icon: pinIcon(false) })
                        .addTo(map)
                        .bindPopup(`<strong>${r.name}</strong><br><small>${r.address ?? ''}</small><br><small>📏 ${d.toFixed(2)} km</small><br><a href="${googleMapsUrl(r.location_lat, r.location_lng)}" target="_blank" rel="noopener" style="display:inline-block;margin-top:6px;color:#ff5722;font-weight:700;text-decoration:none">📍 Buka di Google Maps</a>`);
                    nearbyMarkers.push({ marker, data: r, distance: d });
                }
            });

            nearbyMarkers.sort((a, b) => a.distance - b.distance);
            renderList();
        }

        function renderList() {
            countEl.textContent = nearbyMarkers.length;
            listEl.innerHTML = '';

            if (nearbyMarkers.length === 0) {
                listEl.innerHTML = `<li class="empty"><span class="em">🍽️</span>Tiada tempat makan dalam jarak ini. Cuba tambah jarak.</li>`;
                return;
            }

            nearbyMarkers.forEach((item, i) => {
                const r = item.data;
                let cuisine = r.cuisine_type ? r.cuisine_type : 'Tempat makan';
                if (personalised && r.is_blocked) cuisine = '⚠️ Mengandungi alahan anda';
                else if (personalised && r.is_match) cuisine = '✨ Padan citarasa · ' + cuisine;
                const li = document.createElement('li');
                li.className = 'ritem';
                li.dataset.idx = i;
                li.innerHTML = `
                    <div class="ava">${personalised && r.is_blocked ? '⚠️' : '🍴'}</div>
                    <div class="info">
                        <div class="nm">${r.name}</div>
                        <div class="meta">${cuisine}</div>
                    </div>
                    <span class="dist">${item.distance.toFixed(1)} km</span>`;
                li.addEventListener('click', () => focusMarker(item));
                listEl.appendChild(li);
            });
        }

        function focusMarker(item) {
            map.flyTo(item.marker.getLatLng(), 16);
            item.marker.openPopup();
        }

        function markChosen(item) {
            document.querySelectorAll('.ritem.is-chosen').forEach(el => el.classList.remove('is-chosen'));
            if (lastChosen) lastChosen.marker.setIcon(pinIcon(false));
            item.marker.setIcon(pinIcon(true));
            lastChosen = item;
            const idx = nearbyMarkers.indexOf(item);
            const li = listEl.querySelector(`[data-idx="${idx}"]`);
            if (li) { li.classList.add('is-chosen'); li.scrollIntoView({ block: 'nearest', behavior: 'smooth' }); }
        }

        slider.addEventListener('input', () => {
            distanceValue.textContent = slider.value;
            paintSlider();
            refresh();
        });

        document.getElementById('chooseBtn').addEventListener('click', () => {
            if (nearbyMarkers.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Alamak…', text: 'Tiada tempat makan berhampiran untuk dipilih!', confirmButtonColor: '#ff5722' });
                return;
            }

            // Elak alahan, utamakan padanan citarasa (bila log masuk)
            let pool = nearbyMarkers;
            if (personalised) {
                const safe = nearbyMarkers.filter(m => !m.data.is_blocked);
                if (safe.length === 0) {
                    Swal.fire({ icon: 'warning', title: 'Hmm…', text: 'Semua tempat berhampiran mengandungi bahan yang anda elak. Cuba tambah jarak.', confirmButtonColor: '#ff5722' });
                    return;
                }
                const matches = safe.filter(m => m.data.is_match);
                pool = matches.length ? matches : safe;
            }

            const chosen = pool[Math.floor(Math.random() * pool.length)];
            markChosen(chosen);
            map.flyTo(chosen.marker.getLatLng(), 16, { animate: true, duration: 1.4 });
            chosen.marker.openPopup();

            const menus = (chosen.data.menus || []).slice(0, 3)
                .map(m => `<li style="display:flex;justify-content:space-between;gap:12px;padding:4px 0;border-bottom:1px solid #f0eae5"><span>${m.food_name}</span><b>RM${(+m.price).toFixed(2)}</b></li>`)
                .join('');

            Swal.fire({
                title: '🍽️ Hari ni makan kat sini!',
                html: `
                    <h3 style="margin:2px 0 4px;font-size:1.25rem">${chosen.data.name}</h3>
                    <p style="color:#7c736d;margin-bottom:10px">${chosen.data.address ?? ''}</p>
                    <p style="color:#ff5722;font-weight:700;margin-bottom:8px">📏 ${chosen.distance.toFixed(2)} km dari anda</p>
                    ${menus ? `<ul style="text-align:left;list-style:none;padding:0;margin:0">${menus}</ul>` : ''}`,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: '🧭 Tunjuk jalan',
                denyButtonText: '📍 Google Maps',
                cancelButtonText: 'Pilih semula',
                denyButtonColor: '#1a73e8',
                confirmButtonColor: '#ff5722',
                cancelButtonColor: '#9a8f88'
            }).then(result => {
                if (result.isConfirmed && userLat !== null) {
                    if (routingControl) map.removeControl(routingControl);
                    routingControl = L.Routing.control({
                        waypoints: [L.latLng(userLat, userLng), L.latLng(chosen.data.location_lat, chosen.data.location_lng)],
                        routeWhileDragging: false, show: false, addWaypoints: false, fitSelectedRoutes: true,
                        lineOptions: { styles: [{ color: '#ff5722', weight: 5, opacity: .85 }] },
                        createMarker: () => null
                    }).addTo(map);
                } else if (result.isDenied) {
                    window.open(googleMapsUrl(chosen.data.location_lat, chosen.data.location_lng), '_blank', 'noopener');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    document.getElementById('chooseBtn').click();
                }
            });
        });

        paintSlider();
        initMap();
    </script>
</body>

</html>
