<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

/**
 * Seeder ini menarik data restoran SEBENAR (koordinat tepat) dari
 * OpenStreetMap melalui Overpass API — percuma, tiada API key.
 *
 * Jalankan:  php artisan db:seed --class=OsmRestaurantSeeder
 *
 * Tukar kawasan dengan env: OSM_LAT, OSM_LNG, OSM_RADIUS_KM, OSM_LIMIT
 * (default: tengah Petaling Jaya, radius 6 km).
 */
class OsmRestaurantSeeder extends Seeder
{
    /** Endpoint Overpass (utama + sandaran). */
    private array $endpoints = [
        'https://overpass.kumi.systems/api/interpreter',
        'https://overpass-api.de/api/interpreter',
    ];

    /** Menu contoh ikut jenis masakan (OSM tiada data menu). */
    private array $sampleMenus = [
        'melayu'     => [['Nasi Lemak Ayam', 9.0], ['Teh Tarik', 2.5]],
        'malaysian'  => [['Nasi Campur', 10.0], ['Teh Ais', 2.5]],
        'chinese'    => [['Wantan Mee', 9.0], ['Char Kuey Teow', 9.0]],
        'indian'     => [['Banana Leaf Rice', 12.0], ['Roti Canai', 2.0]],
        'mamak'      => [['Nasi Kandar', 10.0], ['Maggi Goreng', 7.0]],
        'japanese'   => [['Sushi Set', 30.0], ['Ramen', 25.0]],
        'korean'     => [['Bibimbap', 22.0], ['Korean BBQ Set', 40.0]],
        'thai'       => [['Tomyam Campur', 18.0], ['Green Curry', 16.0]],
        'western'    => [['Chicken Chop', 22.0], ['Spaghetti Bolognese', 18.0]],
        'cafe'       => [['Flat White', 12.0], ['Croissant', 8.0]],
        'coffee_shop'=> [['Kopi O', 2.5], ['Roti Bakar', 4.5]],
        'seafood'    => [['Butter Prawn', 35.0], ['Steamed Fish', 45.0]],
        'burger'     => [['Cheese Burger Set', 15.0], ['Fries', 7.0]],
        'pizza'      => [['Pizza Regular', 28.0], ['Garlic Bread', 10.0]],
        'fast_food'  => [['Burger Combo', 15.0], ['Fried Chicken', 12.0]],
        'default'    => [['Menu Istimewa', 12.0], ['Air Bancuhan', 3.0]],
    ];

    /** Hab utama seluruh Lembah Klang [nama, lat, lng]. */
    private array $klangValley = [
        ['Kuala Lumpur (Pusat)', 3.1516, 101.7120],
        ['Bukit Bintang', 3.1466, 101.7110],
        ['Petaling Jaya', 3.1073, 101.6067],
        ['Damansara / Kota Damansara', 3.1530, 101.5860],
        ['Shah Alam', 3.0733, 101.5185],
        ['Subang Jaya', 3.0436, 101.5810],
        ['Klang', 3.0449, 101.4455],
        ['Ampang', 3.1488, 101.7617],
        ['Cheras', 3.0833, 101.7450],
        ['Puchong', 3.0226, 101.6160],
        ['Kajang', 2.9930, 101.7870],
        ['Selayang / Gombak', 3.2470, 101.6500],
        ['Setapak / Wangsa Maju', 3.2050, 101.7300],
        ['Bukit Jalil / Sri Petaling', 3.0580, 101.6890],
        ['Putrajaya / Cyberjaya', 2.9264, 101.6964],
    ];

    public function run(): void
    {
        $radius = (float) env('OSM_RADIUS_KM', 5) * 1000;  // meter
        $perArea = (int) env('OSM_PER_AREA', 45);

        // Kawasan tunggal kalau OSM_LAT & OSM_LNG diset; jika tidak, seluruh Lembah Klang.
        if (env('OSM_LAT') && env('OSM_LNG')) {
            $centers = [['Kawasan tersuai', (float) env('OSM_LAT'), (float) env('OSM_LNG')]];
        } else {
            $centers = $this->klangValley;
        }

        $this->command->info('Menarik restoran sebenar dari OpenStreetMap untuk '.count($centers).' kawasan…');

        $total = 0;
        $skipped = 0;

        foreach ($centers as [$area, $lat, $lng]) {
            $elements = $this->fetch($this->buildQuery($lat, $lng, $radius));

            if (empty($elements)) {
                $this->command->warn("  ⚠️  {$area}: tiada data / endpoint sibuk");
                continue;
            }

            $createdHere = 0;
            foreach ($elements as $el) {
                if ($createdHere >= $perArea) {
                    break;
                }

                $tags = $el['tags'] ?? [];
                $name = trim($tags['name'] ?? '');
                if ($name === '') {
                    continue;
                }

                $rLat = $el['lat'] ?? ($el['center']['lat'] ?? null);
                $rLng = $el['lon'] ?? ($el['center']['lon'] ?? null);
                if ($rLat === null || $rLng === null) {
                    continue;
                }

                if (Restaurant::where('name', $name)->exists()) {
                    $skipped++;
                    continue;
                }

                $cuisineRaw = $tags['cuisine'] ?? ($tags['amenity'] ?? '');
                $firstCuisine = trim(explode(';', $cuisineRaw)[0] ?? '');

                $restaurant = Restaurant::create([
                    'name' => $name,
                    'address' => $this->buildAddress($tags),
                    'location_lat' => round((float) $rLat, 7),
                    'location_lng' => round((float) $rLng, 7),
                    'cuisine_type' => $this->prettyCuisine($firstCuisine),
                ]);

                foreach ($this->menusFor($firstCuisine, $tags['amenity'] ?? '') as [$food, $price]) {
                    Menu::create([
                        'restaurant_id' => $restaurant->id,
                        'food_name' => $food,
                        'price' => $price,
                    ]);
                }

                $createdHere++;
                $total++;
            }

            $this->command->info("  ✅ {$area}: +{$createdHere} restoran");
        }

        $this->command->info("🍽️  Selesai: {$total} restoran sebenar ditambah, {$skipped} dilangkau (pendua).");
    }

    /** Bina query Overpass untuk satu pusat + radius (meter). */
    private function buildQuery(float $lat, float $lng, float $radius): string
    {
        return <<<OVERPASS
        [out:json][timeout:60];
        (
          node["amenity"~"^(restaurant|cafe|fast_food|food_court)\$"]["name"](around:{$radius},{$lat},{$lng});
          way["amenity"~"^(restaurant|cafe|fast_food|food_court)\$"]["name"](around:{$radius},{$lat},{$lng});
        );
        out center tags;
        OVERPASS;
    }

    /** Cuba setiap endpoint sehingga berjaya. */
    private function fetch(string $query): array
    {
        foreach ($this->endpoints as $url) {
            try {
                $res = Http::withHeaders(['User-Agent' => 'MakanMana/1.0 (seeder)'])
                    ->timeout(90)
                    ->asForm()
                    ->post($url, ['data' => $query]);

                if ($res->successful() && is_array($res->json('elements'))) {
                    return $res->json('elements');
                }
            } catch (\Throwable $e) {
                $this->command->warn("Endpoint gagal ({$url}): {$e->getMessage()}");
            }
        }

        return [];
    }

    /** Bina alamat dari tag addr:* OSM. */
    private function buildAddress(array $tags): ?string
    {
        $parts = array_filter([
            trim(($tags['addr:housenumber'] ?? '').' '.($tags['addr:street'] ?? '')),
            $tags['addr:postcode'] ?? null,
            $tags['addr:city'] ?? null,
        ]);

        return $parts ? implode(', ', $parts) : null;
    }

    /** Kemas label jenis masakan. */
    private function prettyCuisine(string $cuisine): string
    {
        if ($cuisine === '' || $cuisine === 'restaurant') {
            return 'Restoran';
        }

        return ucwords(str_replace('_', ' ', $cuisine));
    }

    /** Pilih menu contoh ikut jenis masakan / jenis amenity. */
    private function menusFor(string $cuisine, string $amenity): array
    {
        $key = strtolower($cuisine);
        if (isset($this->sampleMenus[$key])) {
            return $this->sampleMenus[$key];
        }
        if (isset($this->sampleMenus[$amenity])) {
            return $this->sampleMenus[$amenity];
        }

        return $this->sampleMenus['default'];
    }
}
