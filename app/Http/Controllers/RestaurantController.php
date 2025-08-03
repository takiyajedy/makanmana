<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with('menus')->get();
        return view('restaurants.index', compact('restaurants'));
    }

    public function showMenu(Restaurant $restaurant)
    {
        $restaurant->load('menus');
        return view('restaurants.menu', compact('restaurant'));
    }
}

