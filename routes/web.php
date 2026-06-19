<?php

use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RecommendationController::class, 'landing'])->name('landing');

Route::get('/dashboard', [RecommendationController::class, 'landing'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //citarasa / preferences
    Route::get('/preferences', [PreferenceController::class, 'edit'])->name('preferences.edit');
    Route::patch('/preferences', [PreferenceController::class, 'update'])->name('preferences.update');

    //surprise
    Route::get('/surprise', [RecommendationController::class, 'surprise'])->name('recommend.surprise');

    //recommendation 
    Route::get('/recommendation', [RecommendationController::class, 'index'])->name('recommend.index');
     Route::get('/recommendation-map', [RecommendationController::class, 'mapRecommendation'])->name('recommendation.map');

    //restaurant
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
    Route::get('/restaurants/{restaurant}/menu', [RestaurantController::class, 'showMenu'])->name('restaurants.menu');


    Route::get('/test-map', function () {
    return view('test-map');
});


});

require __DIR__.'/auth.php';

