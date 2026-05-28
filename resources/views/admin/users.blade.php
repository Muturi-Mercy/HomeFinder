@extends('layouts.admin')
@section('title', 'User Management - Admin')
@section('page-title', 'User Management')

@section('content')
<div class="page-header">
    <h1>👥 User Management</h1>
    <p>Manage all registered tenants on HomeFinder.</p>
</div>

<div class="card">
    <div class="card-header">
        <h3>All Users ({{ $users->total() }})</h3>
    </div>
    <div class="card-body">
        <!-- SEARCH -->
        <form method="GET" class="table-toolbar">
            <input type="text" name="search" class="search-input" placeholder="🔍 Search by name or email..." value="{{ request('search') }}">
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.users') }}" class="btn btn-outline">Clear</a>
        </form>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td style="font-size:13px; color:var(--gray);">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                @csrf
                                <button class="btn btn-sm {{ $user->status === 'active' ? 'btn-danger' : 'btn-success' }}">
                                    {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center; padding:32px; color:var(--gray);">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $users->links() }}</div>
    </div>
</div>
@endsection