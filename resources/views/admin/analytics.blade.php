@extends('layouts.admin')
@section('title', 'Analytics - Admin')
@section('page-title', 'Analytics & Insights')

@section('content')
<div class="page-header">
    <h1><i class="fa-solid fa-arrow-trend-up" style="color: #1E7A5A; margin-right:6px;" ></i> Analytics & Insights</h1>
    <p>Platform performance and property distribution map.</p>
</div>

<div class="grid-3" style="margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-building-circle-check" style="color: #1E7A5A; margin-right:6px;"></i></div>
        <div class="stat-info">
            <h3>{{ \App\Models\Property::where('status','approved')->count() }}</h3>
            <p>Active Listings</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-calendar-days"; style="color:#1e7a5a; margin-right:6px;"></i></div>
        <div class="stat-info">
            <h3>{{ \App\Models\Booking::count() }}</h3>
            <p>Total Bookings</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-heart" style="color: rgba(230, 57, 70, 1);"></i></div>
        <div class="stat-info">
            <h3>{{ \App\Models\Favourite::count() }}</h3>
            <p>Saved Listings</p>
        </div>
    </div>
</div>

<!-- PROPERTY MAP -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-map-location-dot" style="margin-right:8px; color: #34a853;"></i> Property Distribution Map — Rongai Area</h3>
    </div>
    <div class="card-body">
        <div id="adminMap" style="height:500px; border-radius:10px; overflow:hidden;"></div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Initialize admin map centered on Rongai
const adminMap = L.map('adminMap').setView([-1.3978, 36.7565], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(adminMap);

// Properties with coordinates
const allProperties = [
    @foreach(\App\Models\Property::whereNotNull('latitude')->whereNotNull('longitude')->get() as $property)
    {
        id:       {{ $property->id }},
        title:    "{{ addslashes($property->title) }}",
        location: "{{ addslashes($property->location) }}",
        price:    "{{ number_format($property->price) }}",
        lat:      {{ $property->latitude }},
        lng:      {{ $property->longitude }},
        status:   "{{ $property->status }}"
    },
    @endforeach
];

allProperties.forEach(prop => {
    const color = prop.status === 'approved' ? '#1E7A5A'
                : prop.status === 'pending'  ? '#f59e0b'
                : '#ef4444';

    const icon = L.divIcon({
        html: `<div style="background:${color}; color:white; padding:5px 10px; border-radius:6px; font-size:11px; font-weight:600; white-space:nowrap; box-shadow:0 2px 6px rgba(0,0,0,0.3);"><i class="fa-solid fa-house" style="color:white; margin-right:6px;"></i> ${prop.title.substring(0,20)}</div>`,
        className: '',
        iconAnchor: [0, 0]
    });

    L.marker([prop.lat, prop.lng], {icon})
        .addTo(adminMap)
        .bindPopup(`
            <strong>${prop.title}</strong><br>
            <i class="fa-solid fa-map-pin" style="color: rgba(230, 57, 70, 1);"></i> ${prop.location}<br>
            <i class="fa-solid fa-coins" style="color: rgba(245, 158, 11, 1); margin-right:6px;"></i> KSh ${prop.price}/mo<br>
            Status: <strong style="color:${color}">${prop.status}</strong>
        `);
});

// Legend
const legend = L.control({position: 'bottomright'});
legend.onAdd = function() {
    const div = L.DomUtil.create('div');
    div.style.cssText = 'background:white; padding:12px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.15); font-size:13px;';
    div.innerHTML = `
        <strong style="display:block; margin-bottom:8px;">Legend</strong>
        <div style="display:flex; align-items:center; gap:6px; margin-bottom:4px;"><span style="width:12px; height:12px; background:#1E7A5A; border-radius:3px; display:inline-block;"></span> Approved</div>
        <div style="display:flex; align-items:center; gap:6px; margin-bottom:4px;"><span style="width:12px; height:12px; background:#f59e0b; border-radius:3px; display:inline-block;"></span> Pending</div>
        <div style="display:flex; align-items:center; gap:6px;"><span style="width:12px; height:12px; background:#ef4444; border-radius:3px; display:inline-block;"></span> Rejected</div>
    `;
    return div;
};
legend.addTo(adminMap);
</script>
@endsection