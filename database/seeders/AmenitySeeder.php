<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenity;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            ['name' => 'Wi-Fi',              'icon' => 'fa-solid fa-wifi',          'color' => '#7c3aed'],
            ['name' => 'Parking',            'icon' => 'fa-solid fa-car',           'color' => '#16a34a'],
            ['name' => 'Water',              'icon' => 'fa-solid fa-droplet',       'color' => '#0ea5e9'],
            ['name' => 'Security',           'icon' => 'fa-solid fa-shield-halved', 'color' => '#d97706'],
            ['name' => 'Electricity',        'icon' => 'fa-solid fa-bolt',          'color' => '#eab308'],
            ['name' => 'CCTV',               'icon' => 'fa-solid fa-video',         'color' => '#b45309'],
            ['name' => 'Borehole',           'icon' => 'fa-solid fa-faucet-drip',   'color' => '#0284c7'],
            ['name' => 'Backup Generator',   'icon' => 'fa-solid fa-battery-full',  'color' => '#dc2626'],
            ['name' => 'Garbage Collection', 'icon' => 'fa-solid fa-trash',         'color' => '#6b7280'],
            ['name' => 'Garden',             'icon' => 'fa-solid fa-leaf',          'color' => '#15803d'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::updateOrCreate(
                ['name' => $amenity['name']],
                ['icon' => $amenity['icon'], 'color' => $amenity['color']]
            );
        }
    }
}