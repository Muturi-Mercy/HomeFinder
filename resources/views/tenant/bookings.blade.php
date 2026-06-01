@extends('layouts.app')
@section('title', 'My Bookings - HomeFinder')

@section('content')
<div style="max-width:1100px; margin:0 auto; padding:40px 24px;">
    <h1 style="font-size:26px; font-weight:700; margin-bottom:8px;"><i class="fa-solid fa-calendar-days"; style="color:#1e7a5a"></i> My Bookings</h1>
    <p style="color:var(--gray); margin-bottom:32px;">Your property viewing appointments.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($bookings->count() === 0)
        <div style="text-align:center; padding:80px; background:white; border-radius:10px; box-shadow:var(--shadow);">
            <div style="font-size:48px; margin-bottom:16px;"></div>
            <h3>No bookings yet</h3>
            <p style="color:var(--gray); margin-top:8px;">Browse houses and book a viewing appointment.</p>
            <a href="/browse" class="btn btn-primary" style="margin-top:20px; display:inline-block;">Browse Houses</a>
        </div>
    @else
        <div style="background:white; border-radius:10px; box-shadow:var(--shadow); overflow:hidden;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:var(--background);">
                        <th style="padding:14px 20px; text-align:left; font-size:12px; font-weight:600; color:var(--gray); text-transform:uppercase; border-bottom:2px solid var(--border);">Property</th>
                        <th style="padding:14px 20px; text-align:left; font-size:12px; font-weight:600; color:var(--gray); text-transform:uppercase; border-bottom:2px solid var(--border);">Landlord</th>
                        <th style="padding:14px 20px; text-align:left; font-size:12px; font-weight:600; color:var(--gray); text-transform:uppercase; border-bottom:2px solid var(--border);">Date</th>
                        <th style="padding:14px 20px; text-align:left; font-size:12px; font-weight:600; color:var(--gray); text-transform:uppercase; border-bottom:2px solid var(--border);">Time</th>
                        <th style="padding:14px 20px; text-align:left; font-size:12px; font-weight:600; color:var(--gray); text-transform:uppercase; border-bottom:2px solid var(--border);">Status</th>
                        <th style="padding:14px 20px; text-align:left; font-size:12px; font-weight:600; color:var(--gray); text-transform:uppercase; border-bottom:2px solid var(--border);">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:16px 20px;">
                            <strong>{{ $booking->property->title ?? 'N/A' }}</strong>
                            <div style="font-size:12px; color:var(--gray);"><i class="fa-solid fa-location-dot" style="color: rgba(230,57,70,1); margin-right:6px;"></i> {{ $booking->property->location ?? '' }}</div>
                        </td>
                        <td style="padding:16px 20px; color:var(--gray);">
                            {{ $booking->property->landlord->name ?? 'N/A' }}
                        </td>
                        <td style="padding:16px 20px;">
                            {{ \Carbon\Carbon::parse($booking->viewing_date)->format('d M Y') }}
                        </td>
                        <td style="padding:16px 20px;">
                            {{ \Carbon\Carbon::parse($booking->viewing_time)->format('h:i A') }}
                        </td>
                        <td style="padding:16px 20px;">
                            @if($booking->status === 'confirmed')
                                <span style="background:#d1fae5; color:#065f46; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;"><i class="fa-solid fa-check"></i> Confirmed</span>
                            @elseif($booking->status === 'cancelled')
                                <span style="background:#fee2e2; color:#991b1b; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;"><i class="fa-solid fa-x"></i> Cancelled</span>
                            @else
                                <span style="background:#fef3c7; color:#92400e; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;"><i class="fa-solid fa-hourglass-half"></i> Pending</span>
                            @endif
                        </td>
                        <td style="padding:16px 20px;">
                            <a href="/properties/{{ $booking->property->id }}" class="btn btn-sm btn-outline">View Property</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding:16px 20px;">{{ $bookings->links() }}</div>
        </div>
    @endif
</div>
@endsection