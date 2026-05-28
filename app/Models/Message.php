<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
     protected $fillable = [
        'user_id', 'property_id', 'landlord_id',
        'message', 'sender', 'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }
}
