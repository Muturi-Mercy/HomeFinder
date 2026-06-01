@extends('layouts.admin')
@section('title', 'Dashboard - HomeFinder Admin')
@section('page-title', 'Dashboard Overview')

@section('content')

<!-- STAT CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-users" style="color: #1E7A5A; margin-right:6px;"></i></div>
        <div class="stat-info">
            <h3>{{ $totalUsers }}</h3>
            <p>Total Users</p>
            <span class="trend up"><i class="fa-solid fa-arrow-up"></i> Tenants registered</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-building-circle-check" style="color: #1E7A5A; margin-right:6px;"></i></div>
        <div class="stat-info">
            <h3>{{ $approvedProps }}</h3>
            <p>Active Listings</p>
            <span class="trend up"><i class="fa-solid fa-arrow-up"></i> Approved properties</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-hourglass-half" style="color: rgba(245, 158, 11, 1); margin-right:6px;"></i></div>
        <div class="stat-info">
            <h3>{{ $pendingVerify }}</h3>
            <p>Pending Verification</p>
            <a href="{{ route('admin.verification') }}" style="font-size:12px; color:var(--accent);">Review now <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-solid fa-building-circle-exclamation" style="color:red; margin-right:6px;"></i></div>
        <div class="stat-info">
            <h3>{{ $totalReports }}</h3>
            <p>Fraud Reports</p>
            <a href="{{ route('admin.reports') }}" style="font-size:12px; color:var(--accent);">View reports <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</div>

<div class="grid-2">

    <!-- RECENT USERS -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-users" style="color: #1E7A5A; margin-right:6px;"></i> Recent Users</h3>
            <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline">View All</a>
        </div>
        <div class="card-body" style="padding:0;">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td style="color:var(--gray);">{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td style="color:var(--gray); font-size:12px;">{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center; color:var(--gray); padding:24px;">No users yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- PENDING VERIFICATION -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-hourglass-half" style="color: rgba(245, 158, 11, 1); margin-right:6px;"></i> Pending Verification</h3>
            <a href="{{ route('admin.verification') }}" class="btn btn-sm btn-outline">View All</a>
        </div>
        <div class="card-body" style="padding:0;">
            <table>
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Landlord</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingProperties as $property)
                    <tr>
                        <td><strong>{{ Str::limit($property->title, 20) }}</strong></td>
                        <td style="color:var(--gray);">{{ $property->landlord->name ?? 'N/A' }}</td>
                        <td style="color:var(--primary); font-weight:600;">KSh {{ number_format($property->price) }}</td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-success"><i class="fa-solid fa-check"></i></button>
                                </form>
                                <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-danger"><i class="fa-solid fa-xmark"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center; color:var(--gray); padding:24px;">No pending properties.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- RECENT LISTINGS -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-building" style="color: #1E7A5A; margin-right:6px;"></i> Recent Listings</h3>
        <a href="{{ route('admin.properties') }}" class="btn btn-sm btn-outline">View All</a>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Landlord</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentProperties as $property)
                    <tr>
                        <td><strong>{{ $property->title }}</strong></td>
                        <td>{{ $property->landlord->name ?? 'N/A' }}</td>
                        <td style="color:var(--gray);">{{ $property->location }}</td>
                        <td style="color:var(--primary); font-weight:600;">KSh {{ number_format($property->price) }}</td>
                        <td>{{ str_replace('_', ' ', ucfirst($property->property_type)) }}</td>
                        <td>
                            @if($property->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($property->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center; color:var(--gray); padding:24px;">No listings yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection