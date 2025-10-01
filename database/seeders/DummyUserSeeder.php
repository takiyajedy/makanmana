<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'AZDI MUSTAFA',
                'password' => Hash::make('123456'),
                'location_lat' => 3.1390,
                'location_lng' => 101.6869,
                'favorite_foods' => ['nasi lemak', 'roti canai'],
                'food_allergies' => ['kacang', 'udang'],
            ]
        );
    }
}
