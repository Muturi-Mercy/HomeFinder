<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
     protected $fillable = [
        'landlord_id', 'title', 'description', 'location',
        'latitude', 'longitude', 'price', 'property_type',
        'bedrooms', 'bathrooms', 'status', 'is_featured',
        'is_furnished', 'cover_image',
    ];

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenity');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
