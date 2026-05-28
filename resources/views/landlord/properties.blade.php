<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Listings - HomeFinder</title>
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
        tr:hover{background:#f8f9fa;}
    </style>
</head>
<body>
<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand">Home<span>Finder</span></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('landlord.dashboard') }}"><span class="icon">📊</span> Dashboard</a></li>
            <li><a href="{{ route('landlord.properties') }}" class="active"><span class="icon">🏠</span> My Listings</a></li>
            <li><a href="{{ route('landlord.properties.create') }}"><span class="icon">➕</span> Add Property</a></li>
            <li><a href="{{ route('landlord.bookings') }}"><span class="icon">📅</span> Bookings</a></li>
        </ul>
        <div style="padding:24px;">
            <form method="POST" action="{{ route('landlord.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline" style="width:100%">🚪 Logout</button>
            </form>
        </div>
    </aside>

    <main class="dashboard-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <div>
                <h1 style="font-size:24px; font-weight:700;">My Listings</h1>
                <p style="color:var(--gray); font-size:14px;">Manage all your property listings.</p>
            </div>
            <a href="{{ route('landlord.properties.create') }}" class="btn btn-primary">➕ Add New Property</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div style="background:white; border-radius:10px; box-shadow:var(--shadow); overflow:hidden;">
            <table>
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($properties as $property)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:12px;">
                                @if($property->cover_image)
                                    <img src="{{ asset('storage/'.$property->cover_image) }}" style="width:48px; height:48px; border-radius:8px; object-fit:cover;">
                                @else
                                    <div style="width:48px; height:48px; background:var(--background); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:20px;">🏠</div>
                                @endif
                                <div>
                                    <strong>{{ $property->title }}</strong>
                                    <div style="font-size:12px; color:var(--gray);">{{ $property->bedrooms }} bed • {{ $property->bathrooms }} bath</div>
                                </div>
                            </div>
                        </td>
                        <td style="color:var(--gray);">{{ $property->location }}</td>
                        <td style="color:var(--primary); font-weight:600;">KSh {{ number_format($property->price) }}</td>
                        <td>{{ str_replace('_',' ', ucfirst($property->property_type)) }}</td>
                        <td>
                            @if($property->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($property->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex; gap:8px;">
                                <a href="{{ route('landlord.properties.edit', $property) }}" class="btn btn-sm btn-outline">✏️ Edit</a>
                                <form method="POST" action="{{ route('landlord.properties.destroy', $property) }}" onsubmit="return confirm('Delete this property?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">🗑</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:48px; color:var(--gray);">
                            No properties yet. <a href="{{ route('landlord.properties.create') }}" style="color:var(--primary);">Add your first property →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding:16px;">{{ $properties->links() }}</div>
        </div>
    </main>
</div>
</body>
</html>