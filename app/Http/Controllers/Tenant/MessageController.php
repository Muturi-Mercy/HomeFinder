<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
     // Show all conversations for tenant
    public function index()
    {
        $user = Auth::guard('web')->user();

        // Get unique conversations (grouped by property)
        $conversations = Message::with(['property.landlord'])
            ->where('user_id', $user->id)
            ->select('property_id')
            ->distinct()
            ->get()
            ->map(function($msg) use ($user) {
                $lastMessage = Message::with('property.landlord')
                    ->where('user_id', $user->id)
                    ->where('property_id', $msg->property_id)
                    ->latest()
                    ->first();
                return $lastMessage;
            })
            ->filter();

        return view('tenant.messages', compact('conversations'));
    }

    // Show conversation thread
    public function show(Property $property)
    {
        $user = Auth::guard('web')->user();

        $messages = Message::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark landlord messages as read
        Message::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->where('sender', 'landlord')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('tenant.conversation', compact('messages', 'property'));
    }

    // Send message from tenant
    public function send(Request $request, Property $property)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::guard('web')->user();

        Message::create([
            'user_id'     => $user->id,
            'property_id' => $property->id,
            'landlord_id' => $property->landlord_id,
            'message'     => $request->message,
            'sender'      => 'user',
            'is_read'     => false,
        ]);

        return back()->with('success', 'Message sent!');
    }
}
