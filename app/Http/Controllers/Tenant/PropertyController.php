<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Favourite;
use App\Models\Booking;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function browse(Request $request)
    {
        $query = Property::with(['landlord', 'amenities'])
                    ->where('status', 'approved');

        // Search by location
        if ($request->filled('location')) {
            $query->where('location', 'like', '%'.$request->location.'%');
        }

        // Filter by max price
        if ($request->filled('max_price') && $request->max_price < 100000) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by property type
        if ($request->filled('type')) {
            $query->where('property_type', $request->type);
        }

        // Filter by bedrooms
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }

        // Filter by furnished
        if ($request->filled('furnished')) {
            $query->where('is_furnished', true);
        }

        // Filter by amenities
        if ($request->filled('amenities')) {
            foreach ($request->amenities as $amenityId) {
                $query->whereHas('amenities', function($q) use ($amenityId) {
                    $q->where('amenities.id', $amenityId);
                });
            }
        }

        // Sort
        $sort = $request->sort ?? 'newest';
        match($sort) {
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        $properties  = $query->paginate(9)->withQueryString();
        $amenities   = \App\Models\Amenity::all();
        $totalCount  = $query->toBase()->getCountForPagination();

        return view('tenant.browse', compact('properties', 'amenities'));
    }

    // Property Details
    public function show(Property $property)
    {
        if ($property->status !== 'approved') {
            abort(404);
        }

        $property->load(['landlord', 'images', 'amenities']);

        $isFavourited = false;
        if (Auth::guard('web')->check()) {
            $isFavourited = Favourite::where('user_id', Auth::id())
                ->where('property_id', $property->id)->exists();
        }

        $relatedProperties = Property::where('status', 'approved')
            ->where('id', '!=', $property->id)
            ->where('location', 'like', '%'.explode(',', $property->location)[0].'%')
            ->take(3)->get();

        return view('tenant.show', compact('property', 'isFavourited', 'relatedProperties'));
    }

    // Toggle Favourite
    public function toggleFavourite(Property $property)
    {
        $user = Auth::guard('web')->user();
        $existing = Favourite::where('user_id', $user->id)
            ->where('property_id', $property->id)->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Removed from favourites.');
        }

        Favourite::create([
            'user_id'     => $user->id,
            'property_id' => $property->id,
        ]);

        return back()->with('success', 'Added to favourites!');
    }

    // My Favourites
    public function favourites()
    {
        $user = Auth::guard('web')->user();
        $favourites = Favourite::with('property.landlord')
            ->where('user_id', $user->id)
            ->latest()->paginate(9);
        return view('tenant.favourites', compact('favourites'));
    }

    // Book Viewing
    public function bookViewing(Request $request, Property $property)
    {
        $request->validate([
            'viewing_date' => 'required|date|after:today',
            'viewing_time' => 'required',
            'message'      => 'nullable|string|max:500',
        ]);

        Booking::create([
            'user_id'      => Auth::id(),
            'property_id'  => $property->id,
            'viewing_date' => $request->viewing_date,
            'viewing_time' => $request->viewing_time,
            'message'      => $request->message,
            'status'       => 'pending',
        ]);

        return back()->with('success', 'Viewing appointment booked! The landlord will confirm shortly.');
    }

    // My Bookings
    public function bookings()
    {
        $user = Auth::guard('web')->user();
        $bookings = Booking::with('property.landlord')
            ->where('user_id', $user->id)
            ->latest()->paginate(10);
        return view('tenant.bookings', compact('bookings'));
    }

    // Report a listing
    public function report(Request $request, Property $property)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        Report::create([
            'user_id'     => Auth::id(),
            'property_id' => $property->id,
            'reason'      => $request->reason,
            'description' => $request->description,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Report submitted. Our team will review it shortly.');
    }
}
