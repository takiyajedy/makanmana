<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function run()
    {
        // 1. Restoran asal
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

        // 2. Restoran berhampiran Universiti Malaya
        $r2 = Restaurant::create([
            'name' => 'Warung Pak Ali',
            'address' => 'Pantai Dalam, Kuala Lumpur',
            'location_lat' => 3.1175,
            'location_lng' => 101.6630,
            'cuisine_type' => 'Melayu',
        ]);

        Menu::insert([
            ['restaurant_id' => $r2->id, 'food_name' => 'Roti Canai', 'price' => 1.80],
            ['restaurant_id' => $r2->id, 'food_name' => 'Nasi Goreng Kampung', 'price' => 6.50],
        ]);

        $r3 = Restaurant::create([
            'name' => 'Mamak Corner UM',
            'address' => 'Seksyen 16, Petaling Jaya',
            'location_lat' => 3.1208,
            'location_lng' => 101.6535,
            'cuisine_type' => 'Mamak',
        ]);

        Menu::insert([
            ['restaurant_id' => $r3->id, 'food_name' => 'Maggie Goreng', 'price' => 5.50],
            ['restaurant_id' => $r3->id, 'food_name' => 'Teh Ais', 'price' => 2.20],
        ]);

        $r4 = Restaurant::create([
            'name' => 'Kafe Siswazah UM',
            'address' => 'Kampus Universiti Malaya',
            'location_lat' => 3.1190,
            'location_lng' => 101.6550,
            'cuisine_type' => 'Kafe',
        ]);

        Menu::insert([
            ['restaurant_id' => $r4->id, 'food_name' => 'Nasi Campur', 'price' => 6.00],
            ['restaurant_id' => $r4->id, 'food_name' => 'Air Sirap', 'price' => 1.50],
        ]);

        $r5 = Restaurant::create([
            'name' => 'Restoran Ayam Penyet Best',
            'address' => 'Jalan Universiti, Petaling Jaya',
            'location_lat' => 3.1212,
            'location_lng' => 101.6615,
            'cuisine_type' => 'Indonesia',
        ]);

        Menu::insert([
            ['restaurant_id' => $r5->id, 'food_name' => 'Ayam Penyet', 'price' => 8.90],
            ['restaurant_id' => $r5->id, 'food_name' => 'Jus Alpukat', 'price' => 4.50],
        ]);

        $r6 = Restaurant::create([
            'name' => 'Western Delight Café',
            'address' => 'Seksyen 17, Petaling Jaya',
            'location_lat' => 3.1245,
            'location_lng' => 101.6640,
            'cuisine_type' => 'Western',
        ]);

        Menu::insert([
            ['restaurant_id' => $r6->id, 'food_name' => 'Chicken Chop', 'price' => 12.50],
            ['restaurant_id' => $r6->id, 'food_name' => 'Spaghetti Bolognese', 'price' => 10.00],
        ]);
    }
}
