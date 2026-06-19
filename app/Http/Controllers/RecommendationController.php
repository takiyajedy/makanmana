<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Collection;

class RecommendationController extends Controller
{
    /**
     * Laman utama / dashboard — peta penjelajah.
     * Guest: pilihan rawak. Pengguna log masuk dengan citarasa: dipersonalisasi.
     */
    public function landing()
    {
        $user = auth()->user();
        $restaurants = Restaurant::with('menus')->get();

        $personalised = false;
        if ($user && $user->hasTastePreferences()) {
            $restaurants = $this->applyPreferences($restaurants, $user)->values();
            $personalised = true;
        }

        return view('landing', compact('restaurants', 'personalised'));
    }

    public function index()
    {
        $user = auth()->user();
        $restaurants = Restaurant::with('menus')->get();

        $personalised = false;
        if ($user && $user->hasTastePreferences()) {
            $restaurants = $this->applyPreferences($restaurants, $user)
                ->sortBy([
                    ['is_blocked', 'asc'],     // yang ada alahan ke bawah
                    ['match_score', 'desc'],   // skor tertinggi di atas
                ])
                ->values();
            $personalised = true;
        }

        return view('recommendation.index', compact('restaurants', 'personalised'));
    }

    public function surprise()
    {
        return redirect()->route('recommendation.map');
    }

    public function mapRecommendation()
    {
        $user = auth()->user();
        $restaurants = Restaurant::with('menus')->get();

        $personalised = false;
        if ($user && $user->hasTastePreferences()) {
            $restaurants = $this->applyPreferences($restaurants, $user)->values();
            $personalised = true;
        }

        return view('recommendation.map', compact('restaurants', 'personalised'));
    }

    /**
     * Kira skor padanan setiap restoran berdasarkan citarasa pengguna.
     * Menambah atribut: match_score, is_match, is_blocked, match_reasons.
     */
    private function applyPreferences(Collection $restaurants, User $user): Collection
    {
        $favorites = collect($user->favorite_foods ?? [])->map(fn ($v) => mb_strtolower($v));
        $allergies = collect($user->food_allergies ?? [])->map(fn ($v) => mb_strtolower($v));
        $cuisines = collect($user->preferred_cuisines ?? [])->map(fn ($v) => mb_strtolower($v));
        $budget = $user->budget;

        return $restaurants->map(function ($r) use ($favorites, $allergies, $cuisines, $budget) {
            $cuisineText = mb_strtolower($r->cuisine_type ?? '');
            $menuText = mb_strtolower($r->menus->pluck('food_name')->implode(' | '));
            $haystack = $cuisineText.' '.$menuText;

            $score = 0;
            $reasons = [];
            $blocked = false;

            // Alahan — sekat terus
            foreach ($allergies as $a) {
                if ($a !== '' && str_contains($haystack, $a)) {
                    $blocked = true;
                    break;
                }
            }

            // Makanan kegemaran dalam menu
            foreach ($favorites as $f) {
                if ($f !== '' && str_contains($menuText, $f)) {
                    $score += 3;
                    $reasons[] = '🍴 '.ucfirst($f);
                }
            }

            // Jenis masakan kegemaran
            foreach ($cuisines as $c) {
                if ($c !== '' && str_contains($cuisineText, $c)) {
                    $score += 4;
                    $reasons[] = '🌏 '.ucfirst($c);
                    break;
                }
            }

            // Bajet — banding harga purata menu
            if ($budget && $r->menus->count()) {
                $avg = $r->menus->avg('price');
                $fit = match ($budget) {
                    'jimat' => $avg <= 15,
                    'sederhana' => $avg > 15 && $avg <= 40,
                    'mewah' => $avg > 40,
                    default => false,
                };
                if ($fit) {
                    $score += 2;
                    $reasons[] = '💰 Sesuai bajet';
                }
            }

            $r->match_score = $blocked ? -1 : $score;
            $r->is_blocked = $blocked;
            $r->is_match = ! $blocked && $score > 0;
            $r->match_reasons = array_slice(array_values(array_unique($reasons)), 0, 3);

            return $r;
        });
    }
}
