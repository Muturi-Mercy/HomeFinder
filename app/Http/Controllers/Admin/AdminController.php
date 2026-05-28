<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $data = [
            'totalUsers'      => User::count(),
            'totalLandlords'  => Landlord::count(),
            'totalProperties' => Property::count(),
            'pendingVerify'   => Property::where('status', 'pending')->count(),
            'approvedProps'   => Property::where('status', 'approved')->count(),
            'totalBookings'   => Booking::count(),
            'totalReports'    => Report::count(),
            'pendingReports'  => Report::where('status', 'pending')->count(),
            'recentUsers'     => User::latest()->take(5)->get(),
            'recentProperties'=> Property::with('landlord')->latest()->take(5)->get(),
            'pendingProperties' => Property::with('landlord')
                                    ->where('status', 'pending')
                                    ->latest()->take(3)->get(),
        ];
        return view('admin.dashboard', $data);
    }

    // User Management
    public function users(Request $request)
    {
        $query = User::query();
        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $users = $query->latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    // Suspend/Activate User
    public function toggleUser(User $user)
    {
        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();
        return back()->with('success', 'User status updated.');
    }

    // Landlord Management
    public function landlords(Request $request)
    {
        $query = Landlord::query();
        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
        }
        $landlords = $query->latest()->paginate(10);
        return view('admin.landlords', compact('landlords'));
    }

    // Verify Landlord
    public function verifyLandlord(Landlord $landlord)
    {
        $landlord->is_verified = !$landlord->is_verified;
        $landlord->save();
        return back()->with('success', 'Landlord verification status updated.');
    }

    // Toggle Landlord Status
    public function toggleLandlord(Landlord $landlord)
    {
        $landlord->status = $landlord->status === 'active' ? 'suspended' : 'active';
        $landlord->save();
        return back()->with('success', 'Landlord status updated.');
    }

    // Property Verification
    public function verification()
    {
        $properties = Property::with('landlord')
            ->where('status', 'pending')
            ->latest()->paginate(9);
        return view('admin.verification', compact('properties'));
    }

    // Approve Property
    public function approveProperty(Property $property)
    {
        $property->status = 'approved';
        $property->save();
        return back()->with('success', 'Property approved successfully.');
    }

    // Reject Property
    public function rejectProperty(Property $property)
    {
        $property->status = 'rejected';
        $property->save();
        return back()->with('success', 'Property rejected.');
    }

    // All Listings
    public function properties(Request $request)
    {
        $query = Property::with('landlord');
        if ($request->search) {
            $query->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('location', 'like', '%'.$request->search.'%');
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $properties = $query->latest()->paginate(10);
        return view('admin.properties', compact('properties'));
    }

    // Delete Property
    public function deleteProperty(Property $property)
    {
        $property->delete();
        return back()->with('success', 'Property deleted.');
    }

    // Reports
    public function reports(Request $request)
    {
        $query = Report::with(['user', 'property']);
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $reports = $query->latest()->paginate(10);
        return view('admin.reports', compact('reports'));
    }

    // Update Report Status
    public function updateReport(Report $report, Request $request)
    {
        $report->status = $request->status;
        $report->save();
        return back()->with('success', 'Report updated.');
    }

    // Analytics
    public function analytics()
    {
        return view('admin.analytics');
    }

    // Settings
    public function settings()
    {
        return view('admin.settings');
    }
}
