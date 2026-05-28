@extends('layouts.admin')
@section('title', 'Reports & Complaints - Admin')
@section('page-title', 'Reports & Complaints')

@section('content')
<div class="page-header">
    <h1>🚨 Reports & Complaints</h1>
    <p>Review fraud reports and complaints submitted by users.</p>
</div>

<div class="card">
    <div class="card-header">
        <h3>All Reports ({{ $reports->total() }})</h3>
    </div>
    <div class="card-body">
        <form method="GET" class="table-toolbar">
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="investigating" {{ request('status') === 'investigating' ? 'selected' : '' }}>Investigating</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.reports') }}" class="btn btn-outline">Clear</a>
        </form>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reported By</th>
                        <th>Property</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td><strong>{{ $report->user->name ?? 'N/A' }}</strong></td>
                        <td>{{ $report->property->title ?? 'N/A' }}</td>
                        <td>{{ $report->reason }}</td>
                        <td>
                            @if($report->status === 'resolved')
                                <span class="badge badge-success">Resolved</span>
                            @elseif($report->status === 'investigating')
                                <span class="badge badge-info">Investigating</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td style="font-size:13px; color:var(--gray);">{{ $report->created_at->format('d M Y') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.reports.update', $report) }}" style="display:flex; gap:4px;">
                                @csrf
                                <select name="status" class="filter-select" style="padding:4px 8px; font-size:12px;">
                                    <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="investigating" {{ $report->status === 'investigating' ? 'selected' : '' }}>Investigating</option>
                                    <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                </select>
                                <button class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center; padding:32px; color:var(--gray);">No reports found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $reports->links() }}</div>
    </div>
</div>
@endsection