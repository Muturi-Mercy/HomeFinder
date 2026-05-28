@extends('layouts.admin')
@section('title', 'Landlords - Admin')
@section('page-title', 'Landlord Management')

@section('content')
<div class="page-header">
    <h1>🏢 Landlord Management</h1>
    <p>Manage all registered landlords and agents.</p>
</div>

<div class="card">
    <div class="card-header">
        <h3>All Landlords ({{ $landlords->total() }})</h3>
    </div>
    <div class="card-body">
        <form method="GET" class="table-toolbar">
            <input type="text" name="search" class="search-input" placeholder="🔍 Search landlords..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('admin.landlords') }}" class="btn btn-outline">Clear</a>
        </form>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Verified</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($landlords as $landlord)
                    <tr>
                        <td>{{ $landlord->id }}</td>
                        <td><strong>{{ $landlord->name }}</strong></td>
                        <td>{{ $landlord->email }}</td>
                        <td>{{ $landlord->phone }}</td>
                        <td>
                            <span class="badge {{ $landlord->is_verified ? 'badge-success' : 'badge-gray' }}">
                                {{ $landlord->is_verified ? '✓ Verified' : 'Unverified' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $landlord->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                {{ ucfirst($landlord->status) }}
                            </span>
                        </td>
                        <td style="font-size:13px; color:var(--gray);">{{ $landlord->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                <form method="POST" action="{{ route('admin.landlords.toggle', $landlord) }}">
                                    @csrf
                                    <button class="btn btn-sm {{ $landlord->status === 'active' ? 'btn-danger' : 'btn-success' }}">
                                        {{ $landlord->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.landlords.verify', $landlord) }}">
                                    @csrf
                                    <button class="btn btn-sm {{ $landlord->is_verified ? 'btn-warning' : 'btn-primary' }}">
                                        {{ $landlord->is_verified ? '✓ Verified' : 'Verify' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align:center; padding:32px; color:var(--gray);">No landlords found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $landlords->links() }}</div>
    </div>
</div>
@endsection