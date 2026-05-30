<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Property;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Submit a review
    public function store(Request $request, Property $property)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = Auth::guard('web')->user();

        // Check if tenant has booked this property
        $hasBooked = Booking::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->exists();

        if (!$hasBooked) {
            return back()->with('error', 'You can only review properties you have booked a viewing for.');
        }

        // Check if already reviewed
        $alreadyReviewed = Review::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'You have already reviewed this property.');
        }

        Review::create([
            'user_id'     => $user->id,
            'property_id' => $property->id,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }

    // Delete own review
    public function destroy(Review $review)
    {
        $user = Auth::guard('web')->user();

        if ($review->user_id !== $user->id) {
            abort(403);
        }

        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}