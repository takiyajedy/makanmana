<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Recommendation Map</h2>
    </x-slot>

    <div class="container py-4">
        <div id="map" style="height: 500px;" class="mb-3 border"></div>
        <button id="chooseBtn" class="btn btn-primary">Choose for me</button>
    </div>

    {{-- Load Leaflet CSS di head --}}
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <style>
            .leaflet-container {
                z-index: 1;
            }
        </style>
    @endpush

    {{-- JS di bawah --}}
    @push('js')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

        <script>
            const restaurants = @json($restaurants);
            let nearbyMarkers = [];

            // Inisialisasi map default di KL
            var map = L.map('map').setView([3.1390, 101.6869], 14);

            // Layer OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Cuba dapatkan lokasi user
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(successLocation, errorLocation, {
                    enableHighAccuracy: true,
                    timeout: 5000
                });
            } else {
                alert("GPS not supported by your browser.");
            }

            function successLocation(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                // Letak marker user
                const userMarker = L.marker([userLat, userLng]).addTo(map)
                    .bindPopup("📍 You are here").openPopup();

                map.setView([userLat, userLng], 15);

                // Cari kedai berhampiran (5km)
                restaurants.forEach(r => {
                    const dist = distance(userLat, userLng, r.location_lat, r.location_lng);
                    if (dist <= 5) {
                        const marker = L.marker([r.location_lat, r.location_lng]).addTo(map)
                            .bindPopup(`<strong>${r.name}</strong><br>${r.address}`);
                        nearbyMarkers.push({
                            marker: marker,
                            data: r
                        });
                    }
                });

                if (nearbyMarkers.length === 0) {
                    alert("No nearby restaurants found within 5km.");
                }
            }

            function errorLocation() {
                alert("Unable to retrieve your location. Showing default area.");
                map.setView([3.1390, 101.6869], 14);

                // Letak semua restoran dalam peta bila GPS gagal
                restaurants.forEach(r => {
                    const marker = L.marker([r.location_lat, r.location_lng]).addTo(map)
                        .bindPopup(`<strong>${r.name}</strong><br>${r.address}`);
                    nearbyMarkers.push({
                        marker: marker,
                        data: r
                    });
                });
            }

            // Function kira jarak Haversine (km)
            function distance(lat1, lon1, lat2, lon2) {
                var R = 6371; // km
                var dLat = (lat2 - lat1) * Math.PI / 180;
                var dLon = (lon2 - lon1) * Math.PI / 180;
                var a =
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            // Butang "Choose for me"
            document.getElementById('chooseBtn').addEventListener('click', function() {
                if (nearbyMarkers.length === 0) {
                    alert("No nearby restaurants to choose from.");
                    return;
                }

                const randomIndex = Math.floor(Math.random() * nearbyMarkers.length);
                const chosen = nearbyMarkers[randomIndex];

                // Zoom dan buka popup
                map.setView(chosen.marker.getLatLng(), 17);
                chosen.marker.openPopup();

                alert(`🍽️ Suggested: ${chosen.data.name}`);
            });
        </script>
    @endpush
</x-app-layout>
