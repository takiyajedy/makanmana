<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PreferenceController extends Controller
{
    /** Pilihan tetap yang dipaparkan sebagai butang/checkbox. */
    public const FOODS = ['Nasi', 'Mee', 'Roti', 'Ayam', 'Daging', 'Ikan', 'Sayur', 'Telur', 'Sup', 'Burger', 'Pizza', 'Sushi'];
    public const ALLERGIES = ['Seafood', 'Kacang', 'Telur', 'Susu', 'Gluten', 'Soya', 'Kerang', 'Udang'];
    public const CUISINES = ['Melayu', 'Chinese', 'Indian', 'Japanese', 'Western', 'Cafe', 'Seafood', 'Mamak', 'Thai', 'Korean', 'Fusion', 'Kopitiam'];
    public const DIETARY = ['Halal', 'Vegetarian', 'Vegan', 'Tiada Babi', 'Tiada Daging Lembu'];
    public const BUDGETS = ['jimat', 'sederhana', 'mewah'];

    /**
     * Papar borang citarasa pengguna.
     */
    public function edit(Request $request): View
    {
        return view('profile.preferences', [
            'user' => $request->user(),
            'foods' => self::FOODS,
            'allergies' => self::ALLERGIES,
            'cuisines' => self::CUISINES,
            'dietary' => self::DIETARY,
            'budgets' => self::BUDGETS,
        ]);
    }

    /**
     * Simpan citarasa pengguna.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'favorite_foods' => ['nullable', 'array'],
            'favorite_foods.*' => ['string', 'max:50'],
            'favorite_custom' => ['nullable', 'string', 'max:255'],

            'food_allergies' => ['nullable', 'array'],
            'food_allergies.*' => ['string', 'max:50'],
            'allergy_custom' => ['nullable', 'string', 'max:255'],

            'preferred_cuisines' => ['nullable', 'array'],
            'preferred_cuisines.*' => ['string', 'max:50'],

            'dietary' => ['nullable', 'array'],
            'dietary.*' => ['string', 'max:50'],

            'spicy_level' => ['nullable', 'integer', 'between:0,3'],
            'budget' => ['nullable', Rule::in(self::BUDGETS)],
        ]);

        $user = $request->user();

        $user->favorite_foods = $this->mergeCustom($validated['favorite_foods'] ?? [], $request->input('favorite_custom'));
        $user->food_allergies = $this->mergeCustom($validated['food_allergies'] ?? [], $request->input('allergy_custom'));
        $user->preferred_cuisines = array_values($validated['preferred_cuisines'] ?? []);
        $user->dietary = array_values($validated['dietary'] ?? []);
        $user->spicy_level = $validated['spicy_level'] ?? null;
        $user->budget = $validated['budget'] ?? null;

        $user->save();

        return Redirect::route('preferences.edit')->with('status', 'preferences-updated');
    }

    /**
     * Gabung pilihan checkbox dengan teks bebas (dipisah koma), buang pendua.
     */
    private function mergeCustom(array $selected, ?string $custom): array
    {
        $extra = collect(explode(',', (string) $custom))
            ->map(fn ($v) => trim($v))
            ->filter()
            ->all();

        return collect($selected)->merge($extra)
            ->map(fn ($v) => trim($v))
            ->filter()
            ->unique(fn ($v) => mb_strtolower($v))
            ->values()
            ->all();
    }
}
