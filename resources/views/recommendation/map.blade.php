<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Recommendation Map</h2>
    </x-slot>

    <div class="container py-4">
        <div id="map" style="height: 500px;" class="mb-3 border"></div>

        <div class="mb-3">
            <label for="distanceSlider" class="form-label fw-bold">
                Max Distance: <span id="distanceValue">5</span> km
            </label>
            <input type="range" class="form-range" id="distanceSlider" min="1" max="500" step="1" value="5">
        </div>

        <button id="chooseBtn" class="btn btn-primary mb-3">Choose for me</button>

        <h5>📌 Senarai Restoran Berhampiran</h5>
        <div id="restaurantCount" class="mb-2 text-muted"></div>
        <ul id="restaurantList" class="list-group"></ul>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    @endpush

    @push('js')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            const restaurants = @json($restaurants);
            let nearbyMarkers = [];
            let lastChosen = null;
            let routingControl = null;
            let userLat = null;
            let userLng = null;
            let map;

            initMap();

            function initMap() {
                map = L.map('map').setView([3.1390, 101.6869], 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(successLocation, errorLocation, {
                        enableHighAccuracy: true,
                        timeout: 5000
                    });
                } else {
                    alert("GPS not supported by your browser.");
                }
            }

            function successLocation(position) {
                userLat = position.coords.latitude;
                userLng = position.coords.longitude;

                L.marker([userLat, userLng], {
                    icon: L.icon({
                        iconUrl: 'https://cdn-icons-png.flaticon.com/512/149/149071.png',
                        iconSize: [40, 40],
                        iconAnchor: [20, 40],
                        popupAnchor: [0, -35]
                    })
                }).addTo(map).bindPopup("📍 You are here").openPopup();

                map.setView([userLat, userLng], 15);
                refreshRestaurants();
            }

            function errorLocation() {
                alert("Unable to retrieve your location. Using default area.");
                map.setView([3.1390, 101.6869], 14);
            }

            function refreshRestaurants() {
                const maxDistance = parseInt(document.getElementById('distanceSlider').value);
                nearbyMarkers.forEach(item => map.removeLayer(item.marker));
                nearbyMarkers = [];

                restaurants.forEach(r => {
                    const dist = distance(userLat, userLng, r.location_lat, r.location_lng);
                    if (dist <= maxDistance) {
                        const marker = L.marker([r.location_lat, r.location_lng]).addTo(map)
                            .bindPopup(`<strong>${r.name}</strong><br>${r.address}<br><small>Jarak: ${dist.toFixed(2)} km</small>`);
                        nearbyMarkers.push({ marker, data: r, distance: dist });
                    }
                });

                nearbyMarkers.sort((a, b) => a.distance - b.distance);
                updateList();
            }

            function updateList() {
                const list = document.getElementById('restaurantList');
                const count = document.getElementById('restaurantCount');

                list.innerHTML = "";
                count.innerHTML = `Jumlah restoran ditemui: <strong>${nearbyMarkers.length}</strong>`;

                nearbyMarkers.forEach(item => {
                    let li = document.createElement('li');
                    li.className = "list-group-item";
                    li.innerHTML = `<strong>${item.data.name}</strong> - ${item.distance.toFixed(2)} km`;
                    li.addEventListener('click', () => {
                        map.flyTo(item.marker.getLatLng(), 17);
                        item.marker.openPopup();
                    });
                    list.appendChild(li);
                });
            }

            document.getElementById('distanceSlider').addEventListener('input', function() {
                document.getElementById('distanceValue').textContent = this.value;
                refreshRestaurants();
            });

            function distance(lat1, lon1, lat2, lon2) {
                var R = 6371;
                var dLat = (lat2 - lat1) * Math.PI / 180;
                var dLon = (lon2 - lon1) * Math.PI / 180;
                var a =
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            document.getElementById('chooseBtn').addEventListener('click', function() {
                if (nearbyMarkers.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'No nearby restaurants to choose from!'
                    });
                    return;
                }

                const randomIndex = Math.floor(Math.random() * nearbyMarkers.length);
                const chosen = nearbyMarkers[randomIndex];

                if (lastChosen) lastChosen.marker.setIcon(defaultIcon());
                chosen.marker.setIcon(highlightIcon());
                lastChosen = chosen;

                map.flyTo(chosen.marker.getLatLng(), 17, { animate: true, duration: 2 });
                chosen.marker.openPopup();

                Swal.fire({
                    icon: 'success',
                    title: '🍽️ Suggested!',
                    html: `<strong>${chosen.data.name}</strong><br>${chosen.data.address || ''}<br><small>Jarak: ${chosen.distance.toFixed(2)} km</small>`,
                    confirmButtonText: 'Let’s go!'
                }).then((result) => {
                    if (result.isConfirmed && userLat && userLng) {
                        if (routingControl) map.removeControl(routingControl);

                        routingControl = L.Routing.control({
                            waypoints: [
                                L.latLng(userLat, userLng),
                                L.latLng(chosen.data.location_lat, chosen.data.location_lng)
                            ],
                            routeWhileDragging: false,
                            show: false,
                            addWaypoints: false
                        }).addTo(map);
                    }
                });
            });

            function defaultIcon() {
                return L.icon({
                    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                    shadowSize: [41, 41]
                });
            }

            function highlightIcon() {
                return L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                    shadowSize: [41, 41]
                });
            }
        </script>
    @endpush
</x-app-layout>
