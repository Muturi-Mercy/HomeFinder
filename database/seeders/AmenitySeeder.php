<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Amenity;

class AmenitySeeder extends Seeder
{
     public function run(): void
    {
        $amenities = [
            ['name' => 'Wi-Fi',       'icon' => '📶'],
            ['name' => 'Parking',     'icon' => '🚗'],
            ['name' => 'Water',       'icon' => '💧'],
            ['name' => 'Security',    'icon' => '🔒'],
            ['name' => 'Electricity', 'icon' => '⚡'],
            ['name' => 'CCTV',        'icon' => '📷'],
            ['name' => 'Borehole',    'icon' => '🚿'],
            ['name' => 'Backup Generator', 'icon' => '🔋'],
            ['name' => 'Garbage Collection', 'icon' => '🗑️'],
            ['name' => 'Garden',      'icon' => '🌿'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}
