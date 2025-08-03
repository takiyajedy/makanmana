<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function run()
    {
        $r1 = Restaurant::create([
            'name' => 'Restoran Nasi Lemak Power',
            'address' => 'Jalan Ampang, KL',
            'location_lat' => 3.1500,
            'location_lng' => 101.7100,
            'cuisine_type' => 'Melayu',
        ]);

        Menu::insert([
            ['restaurant_id' => $r1->id, 'food_name' => 'Nasi Lemak Ayam', 'price' => 7.50],
            ['restaurant_id' => $r1->id, 'food_name' => 'Teh Tarik', 'price' => 2.00],
        ]);
    }
}

