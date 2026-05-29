<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bookings - HomeFinder</title>
    <link rel="stylesheet" href="{{ asset('css/homefinder.css') }}">
    <style>
        .badge{padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;}
        .badge-success{background:#d1fae5;color:#065f46;}
        .badge-warning{background:#fef3c7;color:#92400e;}
        .badge-danger{background:#fee2e2;color:#991b1b;}
        .alert{padding:14px 18px;border-radius:8px;margin-bottom:16px;font-size:14px;}
        .alert-success{background:#d1fae5;color:#065f46;border:1px solid #6ee7b7;}
        table{width:100%;border-collapse:collapse;font-size:14px;}
        th{padding:12px 16px;text-align:left;font-size:12px;font-weight:600;color:var(--gray);text-transform:uppercase;border-bottom:2px solid var(--border);background:var(--background);}
        td{padding:14px 16px;border-bottom:1px solid var(--border);vertical-align:middle;}
    </style>
</head>
<body>
<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand">Home<span>Finder</span></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('landlord.dashboard') }}"><span class="icon">📊</span> Dashboard</a></li>
            <li><a href="{{ route('landlord.properties') }}"><span class="icon">🏠</span> My Listings</a></li>
            <li><a href="{{ route('landlord.properties.create') }}"><span class="icon">➕</span> Add Property</a></li>
            <li><a href="{{ route('landlord.bookings') }}" class="active"><span class="icon">📅</span> Bookings</a></li>
            <li><a href="{{ route('landlord.profile') }}"><span class="icon">👤</span> Profile</a></li>
        </ul>
        <div style="padding:24px;">
            <form method="POST" action="{{ route('landlord.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline" style="width:100%">🚪 Logout</button>
            </form>
        </div>
    </aside>

    <main class="dashboard-content">
        <div style="margin-bottom:24px;">
            <h1 style="font-size:24px; font-weight:700;">📅 Viewing Requests</h1>
            <p style="color:var(--gray); font-size:14px;">Manage tenant viewing appointments.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div style="background:white; border-radius:10px; box-shadow:var(--shadow); overflow:hidden;">
            <table>
                <thead>
                    <tr>
                        <th>Tenant</th>
                        <th>Property</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td><strong>{{ $booking->user->name ?? 'N/A' }}</strong><div style="font-size:12px;color:var(--gray);">{{ $booking->user->email ?? '' }}</div></td>
                        <td>{{ $booking->property->title ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->viewing_date)->format('d M Y') }}</td>
                        <td>{{ $booking->viewing_time }}</td>
                        <td style="font-size:13px; color:var(--gray);">{{ $booking->message ?? '—' }}</td>
                        <td>
                            @if($booking->status === 'confirmed')
                                <span class="badge badge-success">Confirmed</span>
                            @elseif($booking->status === 'cancelled')
                                <span class="badge badge-danger">Cancelled</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('landlord.bookings.update', $booking) }}" style="display:flex; gap:4px;">
                                @csrf
                                <select name="status" style="padding:4px 8px; border:1px solid var(--border); border-radius:6px; font-size:12px;">
                                    <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center; padding:48px; color:var(--gray);">No booking requests yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding:16px;">{{ $bookings->links() }}</div>
        </div>
    </main>
</div>
</body>
</html>