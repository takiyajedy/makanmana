<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function run()
    {
        // 1. Village Park Restaurant
        $r1 = Restaurant::create([
            'name' => 'Village Park Restaurant',
            'address' => '5, Jalan SS21/37, Damansara Utama, 47400 Petaling Jaya',
            'location_lat' => 3.1314,
            'location_lng' => 101.6258,
            'cuisine_type' => 'Melayu',
        ]);

        Menu::insert([['restaurant_id' => $r1->id, 'food_name' => 'Nasi Lemak Ayam Goreng', 'price' => 10.0], ['restaurant_id' => $r1->id, 'food_name' => 'Teh Tarik', 'price' => 3.5]]);

        // 2. Genji Japanese Restaurant
        $r2 = Restaurant::create([
            'name' => 'Genji Japanese Restaurant',
            'address' => 'Hilton Petaling Jaya, Lorong Utara C, Seksyen 52, 46200 Petaling Jaya',
            'location_lat' => 3.1043,
            'location_lng' => 101.6435,
            'cuisine_type' => 'Japanese',
        ]);

        Menu::insert([['restaurant_id' => $r2->id, 'food_name' => 'Sushi Set', 'price' => 38.0], ['restaurant_id' => $r2->id, 'food_name' => 'Ramen', 'price' => 28.0]]);

        // 3. The Butcher’s Table
        $r3 = Restaurant::create([
            'name' => 'The Butcher’s Table',
            'address' => '26, Jalan SS2/63, SS2, 47300 Petaling Jaya',
            'location_lat' => 3.121,
            'location_lng' => 101.6275,
            'cuisine_type' => 'Western',
        ]);

        Menu::insert([['restaurant_id' => $r3->id, 'food_name' => 'Pork Steak', 'price' => 45.0], ['restaurant_id' => $r3->id, 'food_name' => 'German Sausage Platter', 'price' => 35.0]]);

        // 4. Secret of Louisiana
        $r4 = Restaurant::create([
            'name' => 'Secret of Louisiana',
            'address' => 'Block D-01-01, Plaza Kelana Jaya, SS7/13A, 47301 Petaling Jaya',
            'location_lat' => 3.1048,
            'location_lng' => 101.5954,
            'cuisine_type' => 'Cajun / Western',
        ]);

        Menu::insert([['restaurant_id' => $r4->id, 'food_name' => 'Seafood Gumbo', 'price' => 32.0], ['restaurant_id' => $r4->id, 'food_name' => 'Jambalaya Rice', 'price' => 28.0]]);

        // 5. Strangers at 47
        $r5 = Restaurant::create([
            'name' => 'Strangers at 47',
            'address' => '47, Jalan 17/45, Seksyen 17, 46400 Petaling Jaya',
            'location_lat' => 3.1146,
            'location_lng' => 101.6353,
            'cuisine_type' => 'Cafe / Western',
        ]);

        Menu::insert([['restaurant_id' => $r5->id, 'food_name' => 'Signature Crepe', 'price' => 18.0], ['restaurant_id' => $r5->id, 'food_name' => 'Flat White', 'price' => 12.0]]);

        // 6. Heng Kee Bak Kut Teh
        $r6 = Restaurant::create([
            'name' => 'Heng Kee Bak Kut Teh',
            'address' => '9, Jalan 1/10, Old Town, 46000 Petaling Jaya',
            'location_lat' => 3.0924,
            'location_lng' => 101.6456,
            'cuisine_type' => 'Chinese',
        ]);

        Menu::insert([['restaurant_id' => $r6->id, 'food_name' => 'Bak Kut Teh Claypot', 'price' => 15.0], ['restaurant_id' => $r6->id, 'food_name' => 'You Tiao', 'price' => 4.0]]);

        // 7. 123 Gasing
        $r7 = Restaurant::create([
            'name' => '123 Gasing',
            'address' => '123, Jalan Gasing, Seksyen 10, 46000 Petaling Jaya',
            'location_lat' => 3.103,
            'location_lng' => 101.6541,
            'cuisine_type' => 'Western / Local Fusion',
        ]);

        Menu::insert([['restaurant_id' => $r7->id, 'food_name' => 'Big Breakfast', 'price' => 28.0], ['restaurant_id' => $r7->id, 'food_name' => 'Iced Latte', 'price' => 13.0]]);

        // 8. Jam & Kaya Café
        $r8 = Restaurant::create([
            'name' => 'Jam & Kaya Café',
            'address' => 'Lot 15, PJ Palms Sport Centre, Jalan Sultan, Seksyen 52, 46200 Petaling Jaya',
            'location_lat' => 3.0949,
            'location_lng' => 101.6451,
            'cuisine_type' => 'Cafe',
        ]);

        Menu::insert([['restaurant_id' => $r8->id, 'food_name' => 'Pancake Stack', 'price' => 20.0], ['restaurant_id' => $r8->id, 'food_name' => 'Cappuccino', 'price' => 11.0]]);

        // 9. Restoran Kam Heong
        $r9 = Restaurant::create([
            'name' => 'Restoran Kam Heong',
            'address' => '62, Jalan SS2/10, SS2, 47300 Petaling Jaya',
            'location_lat' => 3.118,
            'location_lng' => 101.6278,
            'cuisine_type' => 'Chinese',
        ]);

        Menu::insert([['restaurant_id' => $r9->id, 'food_name' => 'Kam Heong Lala', 'price' => 22.0], ['restaurant_id' => $r9->id, 'food_name' => 'Sweet Sour Pork Rice', 'price' => 12.0]]);

        // 10. Chan Meng Kee
        $r10 = Restaurant::create([
            'name' => 'Restoran Chan Meng Kee',
            'address' => '14, Jalan SS2/66, SS2, 47300 Petaling Jaya',
            'location_lat' => 3.1218,
            'location_lng' => 101.627,
            'cuisine_type' => 'Chinese',
        ]);

        Menu::insert([['restaurant_id' => $r10->id, 'food_name' => 'Wantan Mee Char Siew', 'price' => 10.0], ['restaurant_id' => $r10->id, 'food_name' => 'Sui Kow Soup', 'price' => 8.0]]);

        // 11. A Pie Thing
        $r11 = Restaurant::create([
            'name' => 'A Pie Thing',
            'address' => 'Jalan 21/7, Sea Park, 46300 Petaling Jaya',
            'location_lat' => 3.1086,
            'location_lng' => 101.622,
            'cuisine_type' => 'Bakery / Cafe',
        ]);

        Menu::insert([['restaurant_id' => $r11->id, 'food_name' => 'Chicken Mushroom Pie', 'price' => 13.0], ['restaurant_id' => $r11->id, 'food_name' => 'Pulled Lamb Pie', 'price' => 15.0]]);

        // 12. Grub by Ahong & Friends
        $r12 = Restaurant::create([
            'name' => 'Grub by Ahong & Friends',
            'address' => '608, Jalan 17/10, Seksyen 17, 46400 Petaling Jaya',
            'location_lat' => 3.1152,
            'location_lng' => 101.6332,
            'cuisine_type' => 'Western',
        ]);

        Menu::insert([['restaurant_id' => $r12->id, 'food_name' => 'Beef Burger', 'price' => 22.0], ['restaurant_id' => $r12->id, 'food_name' => 'Lamb Chop', 'price' => 32.0]]);

        // 13. Thong Kee Cafe (Sea Park)
        $r13 = Restaurant::create([
            'name' => 'Thong Kee Cafe',
            'address' => '33, Jalan 21/1, Sea Park, 46300 Petaling Jaya',
            'location_lat' => 3.1088,
            'location_lng' => 101.6213,
            'cuisine_type' => 'Kopitiam',
        ]);

        Menu::insert([['restaurant_id' => $r13->id, 'food_name' => 'TK 1+1 Coffee', 'price' => 4.5], ['restaurant_id' => $r13->id, 'food_name' => 'Croissant Egg Ham', 'price' => 8.0]]);

        // 14. Antipodean (Atria)
        $r14 = Restaurant::create([
            'name' => 'Antipodean Cafe',
            'address' => 'G.08, Atria Shopping Gallery, Jalan SS22/23, Damansara Jaya, 47400 Petaling Jaya',
            'location_lat' => 3.1345,
            'location_lng' => 101.622,
            'cuisine_type' => 'Cafe / Western',
        ]);

        Menu::insert([['restaurant_id' => $r14->id, 'food_name' => 'Big Breakfast', 'price' => 32.0], ['restaurant_id' => $r14->id, 'food_name' => 'Flat White', 'price' => 12.0]]);

        // 15. Bistro à Table
        $r15 = Restaurant::create([
            'name' => 'Bistro à Table',
            'address' => '6, Jalan 17/54, Seksyen 17, 46400 Petaling Jaya',
            'location_lat' => 3.113,
            'location_lng' => 101.6338,
            'cuisine_type' => 'French / Bistro',
        ]);

        Menu::insert([['restaurant_id' => $r15->id, 'food_name' => 'Duck Confit', 'price' => 58.0], ['restaurant_id' => $r15->id, 'food_name' => 'Escargot', 'price' => 42.0]]);

        // 16. Signature by The Hill
        $r16 = Restaurant::create([
            'name' => 'Signature by The Hill',
            'address' => 'Level 1, The Roof, 1 First Avenue, Bandar Utama, 47800 Petaling Jaya',
            'location_lat' => 3.1489,
            'location_lng' => 101.6155,
            'cuisine_type' => 'Fusion / Lounge',
        ]);

        Menu::insert([['restaurant_id' => $r16->id, 'food_name' => 'Truffle Pasta', 'price' => 38.0], ['restaurant_id' => $r16->id, 'food_name' => 'Cocktail Signature', 'price' => 28.0]]);

        // 17. SS2 Selera Malam (Food Court)
        $r17 = Restaurant::create([
            'name' => 'SS2 Selera Malam Food Court',
            'address' => 'Jalan SS2/63, SS2, 47300 Petaling Jaya',
            'location_lat' => 3.1224,
            'location_lng' => 101.6266,
            'cuisine_type' => 'Street Food / Hawker',
        ]);

        Menu::insert([['restaurant_id' => $r17->id, 'food_name' => 'Char Kuey Teow', 'price' => 8.0], ['restaurant_id' => $r17->id, 'food_name' => 'Satay', 'price' => 1.5]]);

        // 18. Paya Serai (Buffet @ Hilton PJ)
        $r18 = Restaurant::create([
            'name' => 'Paya Serai',
            'address' => 'Hilton Petaling Jaya, Lorong Utara C, Seksyen 52, 46200 Petaling Jaya',
            'location_lat' => 3.104,
            'location_lng' => 101.6432,
            'cuisine_type' => 'International Buffet',
        ]);

        Menu::insert([['restaurant_id' => $r18->id, 'food_name' => 'Ramadan Buffet', 'price' => 188.0], ['restaurant_id' => $r18->id, 'food_name' => 'Weekend High Tea', 'price' => 118.0]]);

        // 19. Sala Bar (Rooftop @ Sheraton PJ)
        $r19 = Restaurant::create([
            'name' => 'Sala Bar',
            'address' => 'Sheraton Petaling Jaya, Jalan Utara C, Seksyen 52, 46200 Petaling Jaya',
            'location_lat' => 3.1045,
            'location_lng' => 101.6437,
            'cuisine_type' => 'Bar / Fusion',
        ]);

        Menu::insert([['restaurant_id' => $r19->id, 'food_name' => 'Craft Cocktail', 'price' => 35.0], ['restaurant_id' => $r19->id, 'food_name' => 'Tapas Platter', 'price' => 48.0]]);

        // 20. Yue (Sheraton PJ)
        $r20 = Restaurant::create([
            'name' => 'Yue Chinese Restaurant',
            'address' => 'Level 3, Sheraton Petaling Jaya, Jalan Utara C, 46200 Petaling Jaya',
            'location_lat' => 3.1047,
            'location_lng' => 101.6439,
            'cuisine_type' => 'Chinese Fine Dining',
        ]);

        Menu::insert([['restaurant_id' => $r20->id, 'food_name' => 'Dim Sum Set', 'price' => 68.0], ['restaurant_id' => $r20->id, 'food_name' => 'Peking Duck', 'price' => 158.0]]);

        // 21. Miyabi Japanese Restaurant
        $r21 = Restaurant::create([
            'name' => 'Miyabi Japanese Restaurant',
            'address' => 'Sheraton Petaling Jaya, Jalan Utara C, Seksyen 52, 46200 Petaling Jaya',
            'location_lat' => 3.1046,
            'location_lng' => 101.6436,
            'cuisine_type' => 'Japanese',
        ]);

        Menu::insert([['restaurant_id' => $r21->id, 'food_name' => 'Sashimi Platter', 'price' => 98.0], ['restaurant_id' => $r21->id, 'food_name' => 'Tempura Set', 'price' => 55.0]]);

        // 22. Feast (All Day Dining @ Sheraton PJ)
        $r22 = Restaurant::create([
            'name' => 'Feast All Day Dining',
            'address' => 'Sheraton Petaling Jaya, Jalan Utara C, 46200 Petaling Jaya',
            'location_lat' => 3.1044,
            'location_lng' => 101.6438,
            'cuisine_type' => 'International',
        ]);

        Menu::insert([['restaurant_id' => $r22->id, 'food_name' => 'Lunch Buffet', 'price' => 128.0], ['restaurant_id' => $r22->id, 'food_name' => 'Seafood Night Buffet', 'price' => 188.0]]);

        // 23. Fatty Crab
        $r23 = Restaurant::create([
            'name' => 'Fatty Crab',
            'address' => '2, Jalan SS24/13, Taman Megah, 47301 Petaling Jaya',
            'location_lat' => 3.1152,
            'location_lng' => 101.6105,
            'cuisine_type' => 'Seafood',
        ]);

        Menu::insert([['restaurant_id' => $r23->id, 'food_name' => 'Sweet Sour Crab', 'price' => 95.0], ['restaurant_id' => $r23->id, 'food_name' => 'Fried Rice', 'price' => 12.0]]);

        // 24. Nasi Kandar Pelita (PJ)
        $r24 = Restaurant::create([
            'name' => 'Nasi Kandar Pelita',
            'address' => 'No. 2, Jalan 222, Seksyen 51A, 46100 Petaling Jaya',
            'location_lat' => 3.0947,
            'location_lng' => 101.6359,
            'cuisine_type' => 'Mamak',
        ]);

        Menu::insert([['restaurant_id' => $r24->id, 'food_name' => 'Nasi Kandar Ayam Madu', 'price' => 10.0], ['restaurant_id' => $r24->id, 'food_name' => 'Teh Tarik', 'price' => 2.5]]);

        // 25. Kanna Curry House
        $r25 = Restaurant::create([
            'name' => 'Kanna Curry House',
            'address' => '29, Jalan 17/45, Seksyen 17, 46400 Petaling Jaya',
            'location_lat' => 3.1145,
            'location_lng' => 101.6352,
            'cuisine_type' => 'Indian',
        ]);

        Menu::insert([['restaurant_id' => $r25->id, 'food_name' => 'Banana Leaf Rice', 'price' => 14.0], ['restaurant_id' => $r25->id, 'food_name' => 'Mutton Curry', 'price' => 18.0]]);

        // 26. NZE Cafe
        $r26 = Restaurant::create([
            'name' => 'NZE Cafe',
            'address' => '159, A115, Kampung Tengah, 34100 Selama, Perak',
            'location_lat' => 5.13,
            'location_lng' => 100.77,
            'cuisine_type' => 'Kampung / Western / Masakan Panas',
        ]);

        Menu::insert([['restaurant_id' => $r26->id, 'food_name' => 'Nasi Goreng Kampung', 'price' => 8.5], ['restaurant_id' => $r26->id, 'food_name' => 'Satay Ayam', 'price' => 10.0]]);

        // 27. Refuel Cafe
        $r27 = Restaurant::create([
            'name' => 'Refuel Cafe',
            'address' => 'Lot 5863, Jalan Sir Chulan, 34100 Selama, Perak',
            'location_lat' => 5.1292,
            'location_lng' => 100.7685,
            'cuisine_type' => 'Western / Cafe',
        ]);

        Menu::insert([['restaurant_id' => $r27->id, 'food_name' => 'Pasta Aglio Olio', 'price' => 14.0], ['restaurant_id' => $r27->id, 'food_name' => 'Iced Latte', 'price' => 7.0]]);

        // 28. Selama Cafe
        $r28 = Restaurant::create([
            'name' => 'Selama Cafe',
            'address' => 'Jalan Besar, Pekan Selama, 34100 Selama, Perak',
            'location_lat' => 5.132,
            'location_lng' => 100.772,
            'cuisine_type' => 'Melayu / Kopitiam',
        ]);

        Menu::insert([['restaurant_id' => $r28->id, 'food_name' => 'Mee Kari', 'price' => 6.5], ['restaurant_id' => $r28->id, 'food_name' => 'Nasi Lemak', 'price' => 5.0]]);

        // 29. Sira Rimau Cafe
        $r29 = Restaurant::create([
            'name' => 'Sira Rimau Cafe',
            'address' => 'Selama Inn @ Rumah Rehat Selama, Jalan Kolam Air, 34100 Selama, Perak',
            'location_lat' => 5.1315,
            'location_lng' => 100.7725,
            'cuisine_type' => 'Masakan Panas / Western / Grill',
        ]);

        Menu::insert([['restaurant_id' => $r29->id, 'food_name' => 'Kambing Grill', 'price' => 25.0], ['restaurant_id' => $r29->id, 'food_name' => 'Ikan Grill', 'price' => 22.0]]);

        // 30. Selama Steamboat
        $r30 = Restaurant::create([
            'name' => 'Selama Steamboat',
            'address' => 'Kampung Baru Sungai Terap, 34100 Selama, Perak',
            'location_lat' => 5.1285,
            'location_lng' => 100.775,
            'cuisine_type' => 'Steamboat / Hotpot',
        ]);

        Menu::insert([['restaurant_id' => $r30->id, 'food_name' => 'Set Steamboat Seafood', 'price' => 35.0], ['restaurant_id' => $r30->id, 'food_name' => 'Sup Daging + Sayur', 'price' => 18.0]]);

        // 31. Islamiah Ikan Bakar
        $r31 = Restaurant::create([
            'name' => 'Islamiah Ikan Bakar',
            'address' => 'Bersebelahan MAIPk, Pekan Selama, 34100 Selama, Perak',
            'location_lat' => 5.133,
            'location_lng' => 100.7718,
            'cuisine_type' => 'Masakan Kampung / Ikan Bakar',
        ]);

        Menu::insert([['restaurant_id' => $r31->id, 'food_name' => 'Ikan Bakar', 'price' => 20.0], ['restaurant_id' => $r31->id, 'food_name' => 'Gulai Kawah', 'price' => 15.0]]);

        // 32. Ain Mee Rebus
        $r32 = Restaurant::create([
            'name' => 'Ain Mee Rebus',
            'address' => '42, Jalan Kubu Gajah, 34100 Selama, Perak',
            'location_lat' => 5.135,
            'location_lng' => 100.7705,
            'cuisine_type' => 'Mee Rebus / Kopitiam',
        ]);

        Menu::insert([['restaurant_id' => $r32->id, 'food_name' => 'Mee Rebus Special', 'price' => 7.5], ['restaurant_id' => $r32->id, 'food_name' => 'Teh Tarik', 'price' => 2.5]]);

        // 33. D’Laman Corner
        $r33 = Restaurant::create([
            'name' => 'D\'Laman Corner',
            'address' => 'Selama, Perak',
            'location_lat' => 5.1308,
            'location_lng' => 100.7732,
            'cuisine_type' => 'Western / Sup / Masakan Campur',
        ]);

        Menu::insert([['restaurant_id' => $r33->id, 'food_name' => 'Sup Tulang', 'price' => 12.0], ['restaurant_id' => $r33->id, 'food_name' => 'Spaghetti Bolognese', 'price' => 16.0]]);

        // 34. D’Laman Corner
$r34 = Restaurant::create([
    'name' => "D'Laman Corner",
    'address' => "Selama, Perak",
    'location_lat' => 5.1308,
    'location_lng' => 100.7732,
    'cuisine_type' => 'Western / Masakan Campur / Sup',
]);

Menu::insert([
    ['restaurant_id' => $r34->id, 'food_name' => 'Sup Tulang', 'price' => 12.00],
    ['restaurant_id' => $r34->id, 'food_name' => 'Spaghetti Bolognese', 'price' => 16.00],
]);

// 35. Restoran Hong Yuen
$r35 = Restaurant::create([
    'name' => 'Restoran Hong Yuen',
    'address' => 'Selama, Perak',
    'location_lat' => 5.1310,
    'location_lng' => 100.7740,
    'cuisine_type' => 'Chinese',
]);

Menu::insert([
    ['restaurant_id' => $r35->id, 'food_name' => 'Steamed Fish', 'price' => 30.00],
    ['restaurant_id' => $r35->id, 'food_name' => 'Prawn Curry', 'price' => 28.00],
]);

// 36. Restoran Abu Khalid Al Makawwi
$r36 = Restaurant::create([
    'name' => 'Restoran Abu Khalid Al Makawwi',
    'address' => 'Selama, Perak',
    'location_lat' => 5.1325,
    'location_lng' => 100.7725,
    'cuisine_type' => 'Masakan Melayu / Kampung',
]);

Menu::insert([
    ['restaurant_id' => $r36->id, 'food_name' => 'Nasi Campur', 'price' => 10.00],
    ['restaurant_id' => $r36->id, 'food_name' => 'Ikan Goreng', 'price' => 12.00],
]);

// 37. AOB Cafe
$r37 = Restaurant::create([
    'name' => 'AOB Cafe',
    'address' => '159, A115, Kampung Tengah, 34100 Selama, Perak',
    'location_lat' => 5.1305,
    'location_lng' => 100.7705,
    'cuisine_type' => 'Cafe / Western / Masakan Campur',
]);

Menu::insert([
    ['restaurant_id' => $r37->id, 'food_name' => 'Nasi Goreng', 'price' => 8.50],
    ['restaurant_id' => $r37->id, 'food_name' => 'Ice Blended', 'price' => 7.00],
]);

// 38. Restoran Nasi Ayam Hainan Gulai Kawah
$r38 = Restaurant::create([
    'name' => 'Restoran Nasi Ayam Hainan Gulai Kawah',
    'address' => 'Jalan Sir Chulan, Sungai Relau, 34100 Selama, Perak',
    'location_lat' => 5.0740,
    'location_lng' => 100.7800,
    'cuisine_type' => 'Melayu / Masakan Kampung',
]);

Menu::insert([
    ['restaurant_id' => $r38->id, 'food_name' => 'Nasi Ayam Hainan', 'price' => 7.50],
    ['restaurant_id' => $r38->id, 'food_name' => 'Set Makanan Kampung', 'price' => 15.00],
]);

// 39. Restaurant Country (家乡饭店)
$r39 = Restaurant::create([
    'name' => 'Restaurant Country (家乡饭店)',
    'address' => 'Selama, Perak',
    'location_lat' => 5.1312,
    'location_lng' => 100.7710,
    'cuisine_type' => 'Chinese / SeaFood / Masakan Cina Kampung',
]);

Menu::insert([
    ['restaurant_id' => $r39->id, 'food_name' => 'Wat Tan Hor', 'price' => 12.00],
    ['restaurant_id' => $r39->id, 'food_name' => 'Fried Rice', 'price' => 10.00],
]);

// 40. Nasi Kandar Bistro Nana Tanjong
$r40 = Restaurant::create([
    'name' => 'Nasi Kandar Bistro Nana Tanjong',
    'address' => 'Selama, Perak',
    'location_lat' => 5.1300,
    'location_lng' => 100.7745,
    'cuisine_type' => 'Indian / Nasi Kandar',
]);

Menu::insert([
    ['restaurant_id' => $r40->id, 'food_name' => 'Nasi Kandar', 'price' => 9.00],
    ['restaurant_id' => $r40->id, 'food_name' => 'Roti Canai', 'price' => 2.00],
]);

    }
}
