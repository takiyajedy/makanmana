<x-app-layout>
    @push('styles')
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
                --green: #16a34a;
                --shadow-sm: 0 1px 2px rgba(28, 23, 20, .06), 0 4px 14px rgba(28, 23, 20, .05);
                --shadow-lg: 0 24px 60px -20px rgba(247, 65, 30, .3), 0 12px 30px rgba(28, 23, 20, .08);
            }

            body { background: var(--bg); font-family: 'Plus Jakarta Sans', 'Figtree', ui-sans-serif, system-ui, sans-serif; }

            .map-wrap { max-width: 1180px; margin-inline: auto; padding: 28px 22px 70px; }

            /* Hero */
            .map-hero {
                position: relative; overflow: hidden;
                background: var(--brand-grad); color: #fff;
                border-radius: 24px; padding: 32px 36px; margin-bottom: 24px;
                box-shadow: 0 24px 50px -22px rgba(247, 65, 30, .55);
                display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;
            }
            .map-hero::after { content: ""; position: absolute; width: 300px; height: 300px; border-radius: 50%; background: rgba(255,255,255,.12); top: -120px; right: -80px; }
            .map-hero .txt { position: relative; z-index: 1; }
            .map-hero h1 { font-size: clamp(1.6rem, 3.2vw, 2.2rem); font-weight: 800; letter-spacing: -.02em; }
            .map-hero p { opacity: .92; margin-top: 6px; max-width: 48ch; }
            .map-hero .back { position: relative; z-index: 1; display: inline-flex; align-items: center; gap: 8px; font-weight: 700; font-size: .92rem; text-decoration: none; padding: 11px 18px; border-radius: 12px; background: rgba(255,255,255,.16); color: #fff; transition: background .2s; }
            .map-hero .back:hover { background: rgba(255,255,255,.28); }

            /* App card */
            .map-card {
                background: #fff; border: 1px solid var(--line); border-radius: 24px; overflow: hidden;
                box-shadow: var(--shadow-lg); display: grid; grid-template-columns: 1.55fr 1fr;
            }
            #map { width: 100%; height: 100%; min-height: 580px; background: #e8e2dc; }
            .leaflet-routing-container { display: none; }

            .panel { padding: 26px; display: flex; flex-direction: column; gap: 18px; border-left: 1px solid var(--line); }
            .panel-head h2 { font-size: 1.25rem; font-weight: 800; letter-spacing: -.02em; }
            .panel-head p { color: var(--muted); font-size: .9rem; margin-top: 2px; }

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
            input[type=range]::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 22px; height: 22px; border-radius: 50%; background: #fff; border: 4px solid var(--brand); box-shadow: var(--shadow-sm); cursor: grab; }
            input[type=range]::-moz-range-thumb { width: 22px; height: 22px; border-radius: 50%; background: #fff; border: 4px solid var(--brand); cursor: grab; }

            .choose-btn {
                width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
                font-family: inherit; font-weight: 700; font-size: 1.02rem; cursor: pointer; color: #fff;
                border: none; border-radius: 14px; padding: 15px 20px; background: var(--brand-grad);
                box-shadow: 0 12px 26px -10px rgba(247, 65, 30, .8); transition: transform .15s, box-shadow .2s;
            }
            .choose-btn:hover { transform: translateY(-1px); box-shadow: 0 16px 32px -8px rgba(247, 65, 30, .9); }
            .choose-btn:active { transform: translateY(1px); }

            .list-head { display: flex; align-items: center; justify-content: space-between; }
            .list-head h3 { font-size: .78rem; text-transform: uppercase; letter-spacing: .08em; color: var(--muted); font-weight: 700; }
            .count-badge { background: var(--bg); border: 1px solid var(--line); border-radius: 999px; padding: 3px 12px; font-size: .82rem; font-weight: 700; }

            .rlist { list-style: none; display: flex; flex-direction: column; gap: 10px; overflow-y: auto; max-height: 340px; padding-right: 4px; margin: 0; }
            .rlist::-webkit-scrollbar { width: 6px; }
            .rlist::-webkit-scrollbar-thumb { background: #e2d9d2; border-radius: 999px; }
            .ritem { display: flex; align-items: center; gap: 14px; padding: 13px 14px; border-radius: 14px; border: 1px solid var(--line); background: #fff; cursor: pointer; transition: border-color .15s, transform .12s, box-shadow .2s; }
            .ritem:hover { border-color: var(--brand-2); transform: translateX(2px); box-shadow: var(--shadow-sm); }
            .ritem.is-chosen { border-color: var(--brand); background: #fff7f3; }
            .ritem .ava { width: 42px; height: 42px; border-radius: 12px; flex-shrink: 0; display: grid; place-items: center; font-size: 1.25rem; background: linear-gradient(135deg, #fff1e9, #ffe2d2); }
            .ritem .info { min-width: 0; flex: 1; }
            .ritem .info .nm { font-weight: 700; font-size: .95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .ritem .info .meta { color: var(--muted); font-size: .82rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .ritem .dist { flex-shrink: 0; font-weight: 700; font-size: .82rem; color: var(--brand); background: #fff1e9; border-radius: 999px; padding: 4px 10px; }

            .empty { text-align: center; color: var(--muted); padding: 30px 10px; font-size: .92rem; }
            .empty .em { font-size: 2rem; display: block; margin-bottom: 8px; }

            @media (max-width: 880px) {
                .map-card { grid-template-columns: 1fr; }
                #map { min-height: 380px; }
                .panel { border-left: none; border-top: 1px solid var(--line); }
            }
        </style>
    @endpush

    <div class="map-wrap">
        <section class="map-hero">
            <div class="txt">
                <h1>Peta tempat makan 🗺️</h1>
                <p>Laras jarak dari lokasi anda, kemudian tekan "Pilihkan untuk saya" untuk cadangan rawak.</p>
            </div>
            <a href="{{ route('recommend.index') }}" class="back">← Senarai restoran</a>
        </section>

        <div class="map-card">
            <div id="map"></div>
            <aside class="panel">
                <div class="panel-head">
                    <h2>Cari di sekeliling anda</h2>
                    @if ($personalised)
                        <p>✨ Pilihan diutamakan ikut <a href="{{ route('preferences.edit') }}" style="color:var(--brand);font-weight:700">citarasa anda</a> (alahan dielak).</p>
                    @else
                        <p>Laraskan jarak, kemudian tekan butang ajaib. <a href="{{ route('preferences.edit') }}" style="color:var(--brand);font-weight:700">Set citarasa</a> untuk hasil peribadi.</p>
                    @endif
                </div>

                <div class="slider-box">
                    <div class="slider-top">
                        <label for="distanceSlider">Jarak maksimum</label>
                        <span class="val"><span id="distanceValue">5</span> km</span>
                    </div>
                    <input type="range" id="distanceSlider" min="1" max="100" step="1" value="5">
                </div>

                <button id="chooseBtn" class="choose-btn"><span>🎲</span> Pilihkan untuk saya</button>

                <div class="list-head">
                    <h3>Berhampiran anda</h3>
                    <span class="count-badge" id="restaurantCount">0</span>
                </div>

                <ul class="rlist" id="restaurantList">
                    <li class="empty"><span class="em">📍</span>Benarkan akses lokasi untuk tunjuk tempat makan berhampiran…</li>
                </ul>
            </aside>
        </div>
    </div>

    @push('js')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
        <script>
            const restaurants = @json($restaurants);
            const personalised = @json($personalised);
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
                    navigator.geolocation.getCurrentPosition(onLocation, onLocationError, { enableHighAccuracy: true, timeout: 7000 });
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
                    icon: 'info', title: 'Lokasi tidak tersedia',
                    text: 'Kami guna kawasan KL sebagai contoh. Benarkan akses lokasi untuk hasil tepat.',
                    confirmButtonColor: '#ff5722'
                });
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

                // Tentukan kumpulan calon: elak alahan, utamakan yang padan citarasa
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
                    confirmButtonColor: '#ff5722',
                    denyButtonColor: '#1a73e8',
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
    @endpush
</x-app-layout>
