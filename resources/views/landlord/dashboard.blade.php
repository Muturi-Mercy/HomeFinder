<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Dashboard - HomeFinder</title>
    <link rel="stylesheet" href="{{ asset('css/homefinder.css') }}">
</head>
<body>
<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand">Home<span>Finder</span></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('landlord.dashboard') }}" class="active"><span class="icon">📊</span> Dashboard</a></li>
            <li><a href="{{ route('landlord.properties') }}"><span class="icon">🏠</span> My Listings</a></li>
            <li><a href="{{ route('landlord.properties.create') }}"><span class="icon">➕</span> Add Property</a></li>
            <li><a href="{{ route('landlord.bookings') }}"><span class="icon">📅</span> Bookings</a></li>
            <li><a href="{{ route('landlord.messages') }}"><span class="icon">💬</span> Messages</a></li>
        </ul>
        <div style="padding:24px; margin-top:auto;">
            <form method="POST" action="{{ route('landlord.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline" style="width:100%">🚪 Logout</button>
            </form>
        </div>
    </aside>

    <main class="dashboard-content">
        <div class="dashboard-header">
            <h1>Welcome, {{ Auth::guard('landlord')->user()->name }}! 👋</h1>
            <p>Manage your property listings from here.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-icon green">🏠</div>
                <div><h3>{{ $totalProperties }}</h3><p>Total Properties</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon blue">✅</div>
                <div><h3>{{ $approved }}</h3><p>Approved</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon orange">⏳</div>
                <div><h3>{{ $pending }}</h3><p>Pending Review</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon red">📅</div>
                <div><h3>{{ $totalBookings }}</h3><p>Viewing Requests</p></div>
            </div>
        </div>

        <div style="background:white; border-radius:10px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h3>My Recent Listings</h3>
                <a href="{{ route('landlord.properties.create') }}" class="btn btn-primary">➕ Add New Property</a>
            </div>
            @forelse($recentProperties as $property)
            <div style="display:flex; justify-content:space-between; align-items:center; padding:14px 0; border-bottom:1px solid var(--border);">
                <div>
                    <strong>{{ $property->title }}</strong>
                    <div style="font-size:13px; color:var(--gray);">📍 {{ $property->location }} • KSh {{ number_format($property->price) }}/mo</div>
                </div>
                <div style="display:flex; align-items:center; gap:12px;">
                    @if($property->status === 'approved')
                        <span class="badge badge-success">Approved</span>
                    @elseif($property->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @else
                        <span class="badge badge-danger">Rejected</span>
                    @endif
                    <a href="{{ route('landlord.properties.edit', $property) }}" class="btn btn-sm btn-outline">Edit</a>
                </div>
            </div>
            @empty
            <p style="text-align:center; color:var(--gray); padding:24px;">No properties yet. <a href="{{ route('landlord.properties.create') }}">Add your first property →</a></p>
            @endforelse
        </div>
    </main>
</div>

<style>
.badge { padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; }
.badge-success { background:#d1fae5; color:#065f46; }
.badge-warning { background:#fef3c7; color:#92400e; }
.badge-danger { background:#fee2e2; color:#991b1b; }
.alert { padding:14px 18px; border-radius:8px; margin-bottom:16px; font-size:14px; }
.alert-success { background:#d1fae5; color:#065f46; border:1px solid #6ee7b7; }
</style>
</body>
</html>