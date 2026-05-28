@extends('layouts.admin')
@section('title', 'Listings Management - Admin')
@section('page-title', 'Listings Management')

@section('content')
<div class="page-header">
    <h1>🏠 Listings Management</h1>
    <p>View and manage all property listings on HomeFinder.</p>
</div>

<div class="card">
    <div class="card-header">
        <h3>All Listings ({{ $properties->total() }})</h3>
    </div>
    <div class="card-body">
        <form method="GET" class="table-toolbar">
            <input type="text" name="search" class="search-input" placeholder="🔍 Search by title or location..." value="{{ request('search') }}">
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.properties') }}" class="btn btn-outline">Clear</a>
        </form>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Landlord</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($properties as $property)
                    <tr>
                        <td>{{ $property->id }}</td>
                        <td><strong>{{ Str::limit($property->title, 25) }}</strong></td>
                        <td>{{ $property->landlord->name ?? 'N/A' }}</td>
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
                            <form method="POST" action="{{ route('admin.properties.delete', $property) }}" onsubmit="return confirm('Delete this property?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">🗑 Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align:center; padding:32px; color:var(--gray);">No listings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $properties->links() }}</div>
    </div>
</div>
@endsection