<?php

use Illuminate\Support\Facades\Route;
use App\Models\Property;
use App\Models\User;

// Public API endpoints
Route::get('/properties', function () {
    $properties = Property::with(['landlord', 'amenities'])
        ->where('status', 'approved')
        ->latest()
        ->paginate(10);
    return response()->json($properties);
});

Route::get('/properties/{property}', function (Property $property) {
    $property->load(['landlord', 'images', 'amenities', 'reviews.user']);
    return response()->json($property);
});

Route::get('/properties/search', function () {
    $query = Property::with(['landlord', 'amenities'])
        ->where('status', 'approved');

    if (request('location')) {
        $query->where('location', 'like', '%'.request('location').'%');
    }
    if (request('max_price')) {
        $query->where('price', '<=', request('max_price'));
    }
    if (request('type')) {
        $query->where('property_type', request('type'));
    }

    return response()->json($query->paginate(10));
})->name('api.properties.search');

Route::get('/stats', function () {
    return response()->json([
        'total_properties' => Property::where('status', 'approved')->count(),
        'total_users'      => User::count(),
    ]);
});