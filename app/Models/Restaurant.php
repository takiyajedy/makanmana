<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'address',
        'location_lat',
        'location_lng',
        'cuisine_type',
    ];

    public function menus()
{
    return $this->hasMany(Menu::class);
}

}
