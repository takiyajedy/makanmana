<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;

class RecommendationController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with('menus')->get();

        return view('recommendation.index', compact('restaurants'));
    }
    public function surprise()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $favFoods = $user->favorite_foods ?? [];
        $allergies = $user->food_allergies ?? [];

        $radius = 20;

        $nearbyRestaurants = Restaurant::selectRaw(
            "*, (
        6371 * acos(
            cos(radians(?)) * cos(radians(location_lat)) *
            cos(radians(location_lng) - radians(?)) +
            sin(radians(?)) * sin(radians(location_lat))
        )
    ) AS distance",
            [$user->location_lat, $user->location_lng, $user->location_lat],
        )
            ->having('distance', '<', $radius)
            ->get();

        $filtered = [];

        foreach ($nearbyRestaurants as $restaurant) {
            foreach ($restaurant->menus as $menu) {
                $foodName = strtolower($menu->food_name);
                $matchFav = collect($favFoods)->filter(fn($fav) => str_contains($foodName, strtolower($fav)))->isNotEmpty();
                $hasAllergy = collect($allergies)->filter(fn($bad) => str_contains($foodName, strtolower($bad)))->isNotEmpty();

                if ($matchFav && !$hasAllergy) {
                    $filtered[] = [
                        'restaurant' => $restaurant,
                        'menu' => $menu,
                    ];
                }
            }
        }

        if (empty($filtered)) {
            return view('recommendation.surprise', [
                'result' => null,
                'allResults' => [],
            ]);
        }

        return view('recommendation.surprise', [
            'result' => collect($filtered)->random(),
            'allResults' => $filtered,
        ]);
    }

public function mapRecommendation()
{
    // Ambil semua restoran (dummy kalau DB kosong)
    $restaurants = Restaurant::with('menus')->get();

    if ($restaurants->isEmpty()) {
        $restaurants = collect([
            (object) ['name' => 'Restoran A', 'address' => 'Alamat A', 'location_lat' => 3.1400, 'location_lng' => 101.6900],
            (object) ['name' => 'Restoran B', 'address' => 'Alamat B', 'location_lat' => 3.1380, 'location_lng' => 101.6850],
            (object) ['name' => 'Restoran C', 'address' => 'Alamat C', 'location_lat' => 3.1375, 'location_lng' => 101.6870],
        ]);
    }

    return view('recommendation.map', compact('restaurants'));
}

}
