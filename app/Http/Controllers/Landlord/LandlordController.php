<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LandlordController extends Controller
{
     // Dashboard
    public function dashboard()
    {
        $landlord = Auth::guard('landlord')->user();
        $data = [
            'totalProperties' => Property::where('landlord_id', $landlord->id)->count(),
            'approved'        => Property::where('landlord_id', $landlord->id)->where('status', 'approved')->count(),
            'pending'         => Property::where('landlord_id', $landlord->id)->where('status', 'pending')->count(),
            'totalBookings'   => Booking::whereHas('property', function($q) use ($landlord) {
                                    $q->where('landlord_id', $landlord->id);
                                 })->count(),
            'recentProperties' => Property::where('landlord_id', $landlord->id)->latest()->take(5)->get(),
            'recentBookings'   => Booking::with(['property', 'user'])
                                    ->whereHas('property', function($q) use ($landlord) {
                                        $q->where('landlord_id', $landlord->id);
                                    })->latest()->take(5)->get(),
        ];
        return view('landlord.dashboard', $data);
    }

    // My Listings
    public function properties()
    {
        $landlord = Auth::guard('landlord')->user();
        $properties = Property::where('landlord_id', $landlord->id)
                        ->latest()->paginate(10);
        return view('landlord.properties', compact('properties'));
    }

    // Show Create Form
    public function create()
    {
        $amenities = Amenity::all();
        return view('landlord.create', compact('amenities'));
    }

    // Store New Property
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'location'      => 'required|string',
            'price'         => 'required|numeric|min:0',
            'property_type' => 'required',
            'bedrooms'      => 'required|integer|min:1',
            'bathrooms'     => 'required|integer|min:1',
            'cover_image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $landlord = Auth::guard('landlord')->user();

        // Handle cover image upload
        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('properties', 'public');
        }

        // Create property
        $property = Property::create([
            'landlord_id'   => $landlord->id,
            'title'         => $request->title,
            'description'   => $request->description,
            'location'      => $request->location,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
            'price'         => $request->price,
            'property_type' => $request->property_type,
            'bedrooms'      => $request->bedrooms,
            'bathrooms'     => $request->bathrooms,
            'is_furnished'  => $request->has('is_furnished'),
            'cover_image'   => $coverImagePath,
            'status'        => 'pending',
        ]);

        // Attach amenities
        if ($request->amenities) {
            $property->amenities()->attach($request->amenities);
        }

        // Upload additional images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path'  => $path,
                ]);
            }
        }

        return redirect()->route('landlord.properties')
            ->with('success', 'Property submitted successfully! It will be reviewed by admin.');
    }

    // Show Edit Form
    public function edit(Property $property)
    {
        $landlord = Auth::guard('landlord')->user();

        // Make sure landlord owns this property
        if ($property->landlord_id !== $landlord->id) {
            abort(403, 'Unauthorized');
        }

        $amenities = Amenity::all();
        $selectedAmenities = $property->amenities->pluck('id')->toArray();
        return view('landlord.edit', compact('property', 'amenities', 'selectedAmenities'));
    }

    // Update Property
    public function update(Request $request, Property $property)
    {
        $landlord = Auth::guard('landlord')->user();

        if ($property->landlord_id !== $landlord->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'location'      => 'required|string',
            'price'         => 'required|numeric|min:0',
            'property_type' => 'required',
            'bedrooms'      => 'required|integer|min:1',
            'bathrooms'     => 'required|integer|min:1',
            'cover_image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle new cover image
        if ($request->hasFile('cover_image')) {
            if ($property->cover_image) {
                Storage::disk('public')->delete($property->cover_image);
            }
            $property->cover_image = $request->file('cover_image')->store('properties', 'public');
        }

        $property->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'location'      => $request->location,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
            'price'         => $request->price,
            'property_type' => $request->property_type,
            'bedrooms'      => $request->bedrooms,
            'bathrooms'     => $request->bathrooms,
            'is_furnished'  => $request->has('is_furnished'),
            'cover_image'   => $property->cover_image,
            'status'        => 'pending',
        ]);

        // Sync amenities
        if ($request->amenities) {
            $property->amenities()->sync($request->amenities);
        } else {
            $property->amenities()->detach();
        }

        return redirect()->route('landlord.properties')
            ->with('success', 'Property updated. It will be re-reviewed by admin.');
    }

    // Delete Property
    public function destroy(Property $property)
    {
        $landlord = Auth::guard('landlord')->user();

        if ($property->landlord_id !== $landlord->id) {
            abort(403, 'Unauthorized');
        }

        if ($property->cover_image) {
            Storage::disk('public')->delete($property->cover_image);
        }

        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $property->delete();

        return redirect()->route('landlord.properties')
            ->with('success', 'Property deleted successfully.');
    }

    // Manage Bookings
    public function bookings()
    {
        $landlord = Auth::guard('landlord')->user();
        $bookings = Booking::with(['property', 'user'])
            ->whereHas('property', function($q) use ($landlord) {
                $q->where('landlord_id', $landlord->id);
            })->latest()->paginate(10);
        return view('landlord.bookings', compact('bookings'));
    }

    // Update Booking Status
    public function updateBooking(Booking $booking, Request $request)
    {
        $booking->status = $request->status;
        $booking->save();
        return back()->with('success', 'Booking updated.');
    }

    // View all conversations
    public function messages()
    {
        $landlord = Auth::guard('landlord')->user();

        $conversations = Message::with(['user', 'property'])
            ->where('landlord_id', $landlord->id)
            ->select('property_id', 'user_id')
            ->distinct()
            ->get()
            ->map(function($msg) use ($landlord) {
                $lastMessage = Message::with(['user', 'property'])
                    ->where('landlord_id', $landlord->id)
                    ->where('property_id', $msg->property_id)
                    ->where('user_id', $msg->user_id)
                    ->latest()
                    ->first();
                return $lastMessage;
            })
            ->filter();

        $unreadCount = Message::where('landlord_id', $landlord->id)
            ->where('sender', 'user')
            ->where('is_read', false)
            ->count();

        return view('landlord.messages', compact('conversations', 'unreadCount'));
    }

    // Show conversation thread for landlord
    public function conversation(Property $property, Request $request)
    {
        $landlord = Auth::guard('landlord')->user();
        $userId = $request->user_id;

        $messages = Message::where('landlord_id', $landlord->id)
            ->where('property_id', $property->id)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark tenant messages as read
        Message::where('landlord_id', $landlord->id)
            ->where('property_id', $property->id)
            ->where('user_id', $userId)
            ->where('sender', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $tenant = \App\Models\User::find($userId);

        return view('landlord.conversation',
            compact('messages', 'property', 'tenant'));
    }

    // Landlord replies
    public function reply(Request $request, Property $property)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'user_id' => 'required|exists:users,id',
        ]);

        $landlord = Auth::guard('landlord')->user();

        Message::create([
            'user_id'     => $request->user_id,
            'property_id' => $property->id,
            'landlord_id' => $landlord->id,
            'message'     => $request->message,
            'sender'      => 'landlord',
            'is_read'     => false,
        ]);

        return back()->with('success', 'Reply sent!');
    }
}
