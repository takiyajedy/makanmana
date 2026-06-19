<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'location_lat',
        'location_lng',
        'favorite_foods',
        'food_allergies',
        'preferred_cuisines',
        'dietary',
        'spicy_level',
        'budget',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'favorite_foods' => 'array',
            'food_allergies' => 'array',
            'preferred_cuisines' => 'array',
            'dietary' => 'array',
            'spicy_level' => 'integer',
        ];
    }

    /**
     * Adakah pengguna sudah mengisi sekurang-kurangnya satu citarasa?
     */
    public function hasTastePreferences(): bool
    {
        return ! empty($this->favorite_foods)
            || ! empty($this->food_allergies)
            || ! empty($this->preferred_cuisines)
            || ! empty($this->dietary)
            || ! is_null($this->spicy_level)
            || ! empty($this->budget);
    }
}
