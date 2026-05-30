<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Property - HomeFinder</title>
    <link rel="stylesheet" href="{{ asset('css/homefinder.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        .form-group{margin-bottom:20px;}
        .form-group label{display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--text);}
        .form-control{width:100%;padding:11px 14px;border:1px solid var(--border);border-radius:8px;font-size:14px;outline:none;transition:border-color 0.2s;}
        .form-control:focus{border-color:var(--primary);}
        textarea.form-control{resize:vertical;min-height:120px;}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
        .form-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;}
        .section-title{font-size:16px;font-weight:700;margin-bottom:16px;padding-bottom:8px;border-bottom:2px solid var(--border);color:var(--primary);}
        .amenity-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:10px;}
        .amenity-item{display:flex;align-items:center;gap:8px;padding:10px;border:1px solid var(--border);border-radius:8px;cursor:pointer;font-size:13px;}
        .amenity-item input{accent-color:var(--primary);}
        .alert{padding:14px 18px;border-radius:8px;margin-bottom:16px;font-size:14px;}
        .alert-error{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;}
        .alert ul{margin:0;padding-left:20px;}
    </style>
</head>
<body>
<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand">Home<span>Finder</span></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('landlord.dashboard') }}"><span class="icon">📊</span> Dashboard</a></li>
            <li><a href="{{ route('landlord.properties') }}"><span class="icon">🏠</span> My Listings</a></li>
            <li><a href="{{ route('landlord.properties.create') }}" class="active"><span class="icon">➕</span> Add Property</a></li>
            <li><a href="{{ route('landlord.bookings') }}"><span class="icon">📅</span> Bookings</a></li>
            <li><a href="{{ route('landlord.messages') }}"><span class="icon">💬</span> Messages</a></li>
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
            <h1 style="font-size:24px; font-weight:700;">➕ Add New Property</h1>
            <p style="color:var(--gray); font-size:14px;">Fill in the details below. Your listing will be reviewed by admin before going live.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('landlord.properties.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- BASIC INFO --}}
            <div style="background:white; border-radius:10px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
                <div class="section-title">📋 Basic Information</div>
                <div class="form-group">
                    <label>Property Title *</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Modern 2BR Apartment in Rongai Town" value="{{ old('title') }}" required>
                </div>
                <div class="form-group">
                    <label>Description *</label>
                    <textarea name="description" class="form-control" placeholder="Describe the property, nearby facilities, etc." required>{{ old('description') }}</textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Location *</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. Rongai Town, Nairobi" value="{{ old('location') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Monthly Rent (KSh) *</label>
                        <input type="number" name="price" class="form-control" placeholder="e.g. 15000" value="{{ old('price') }}" required>
                    </div>
                </div>
                <div class="form-row-3">
                    <div class="form-group">
                        <label>Property Type *</label>
                        <select name="property_type" class="form-control" required>
                            <option value="">Select type</option>
                            <option value="bedsitter" {{ old('property_type') === 'bedsitter' ? 'selected' : '' }}>Bedsitter</option>
                            <option value="studio" {{ old('property_type') === 'studio' ? 'selected' : '' }}>Studio</option>
                            <option value="1_bedroom" {{ old('property_type') === '1_bedroom' ? 'selected' : '' }}>1 Bedroom</option>
                            <option value="2_bedroom" {{ old('property_type') === '2_bedroom' ? 'selected' : '' }}>2 Bedrooms</option>
                            <option value="3_bedroom" {{ old('property_type') === '3_bedroom' ? 'selected' : '' }}>3 Bedrooms</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Bedrooms *</label>
                        <input type="number" name="bedrooms" class="form-control" min="1" value="{{ old('bedrooms', 1) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Bathrooms *</label>
                        <input type="number" name="bathrooms" class="form-control" min="1" value="{{ old('bathrooms', 1) }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:400;">
                        <input type="checkbox" name="is_furnished" style="accent-color:var(--primary); width:16px; height:16px;" {{ old('is_furnished') ? 'checked' : '' }}>
                        This property is furnished
                    </label>
                </div>
            </div>

            {{-- MAP PIN --}}
            <div style="background:white; border-radius:10px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
                <div class="section-title">📍 Property Location on Map</div>
                <p style="font-size:13px; color:var(--gray); margin-bottom:16px;">
                    Pin your property location using the map. You can click on the map manually or use your current location if you are on site.
                </p>

                <div id="pinMap" style="height:300px; border-radius:10px; overflow:hidden; border:1px solid var(--border); margin-bottom:16px; cursor:crosshair;"></div>

                <div style="background:#f0faf5; border-radius:8px; padding:12px 16px; margin-bottom:16px; font-size:13px; color:var(--primary);" id="pinStatus">
                    📍 Click on the map above to pin your property location
                </div>

                <div style="display:flex; gap:10px; margin-bottom:16px; flex-wrap:wrap;">
                    <button type="button" onclick="useMyLocation()"
                        id="useLocationBtn"
                        style="background:var(--accent); color:white; border:none; padding:10px 18px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px;">
                        📍 Use My Current Location
                    </button>
                    
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Latitude (auto-filled)</label>
                        <input type="text" name="latitude" id="latInput" class="form-control"
                            placeholder="Click map or use location button"
                            value="{{ old('latitude') }}"
                            readonly style="background:#f8f9fa;">
                    </div>
                    <div class="form-group">
                        <label>Longitude (auto-filled)</label>
                        <input type="text" name="longitude" id="lngInput" class="form-control"
                            placeholder="Click map or use location button"
                            value="{{ old('longitude') }}"
                            readonly style="background:#f8f9fa;">
                    </div>
                </div>
            </div>

        
            {{-- AMENITIES --}}
            <div style="background:white; border-radius:10px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
                <div class="section-title">✅ Amenities</div>
                <div class="amenity-grid">
                    @foreach($amenities as $amenity)
                    <label class="amenity-item" style="transition:all 0.2s;">
                        <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                            {{ in_array($amenity->id, old('amenities', [])) ? 'checked' : '' }}
                            style="accent-color:{{ $amenity->color }};">
                        <i class="{{ $amenity->icon }}" style="color:{{ $amenity->color }}; font-size:15px;"></i>
                        {{ $amenity->name }}
                    </label>
                    @endforeach
                </div>
            </div>


            {{-- IMAGES --}}
            <div style="background:white; border-radius:10px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
                <div class="section-title">📸 Property Images</div>
                <div class="form-group">
                    <label>Cover Image (Main Photo)</label>
                    <input type="file" name="cover_image" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Additional Images (Up to 5)</label>
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                </div>
            </div>

            <div style="display:flex; gap:16px;">
                <button type="submit" class="btn btn-primary" style="padding:14px 36px; font-size:15px;">🚀 Submit Listing</button>
                <a href="{{ route('landlord.properties') }}" class="btn btn-outline" style="padding:14px 36px;">Cancel</a>
            </div>
        </form>
    </main>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pinMap = L.map('pinMap').setView([-1.3978, 36.7565], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(pinMap);

    let marker = null;

    // If old values exist place marker
    const oldLat = document.getElementById('latInput').value;
    const oldLng = document.getElementById('lngInput').value;
    if (oldLat && oldLng) {
        marker = L.marker([parseFloat(oldLat), parseFloat(oldLng)]).addTo(pinMap);
        pinMap.setView([parseFloat(oldLat), parseFloat(oldLng)], 16);
        document.getElementById('pinStatus').textContent = '✅ Location pinned! Click again to change it.';
    }

    // Click to pin
    pinMap.on('click', function(e) {
        const lat = e.latlng.lat.toFixed(7);
        const lng = e.latlng.lng.toFixed(7);

        if (marker) pinMap.removeLayer(marker);

        const icon = L.divIcon({
            html: '<div style="background:#1E7A5A; color:white; padding:6px 10px; border-radius:8px; font-size:12px; font-weight:600; box-shadow:0 2px 8px rgba(0,0,0,0.3); white-space:nowrap;">📍 Your Property</div>',
            className: '',
            iconAnchor: [0, 0]
        });

        marker = L.marker([lat, lng], {icon}).addTo(pinMap);
        document.getElementById('latInput').value = lat;
        document.getElementById('lngInput').value = lng;

        const status = document.getElementById('pinStatus');
        status.innerHTML = `✅ Location pinned at <strong>${lat}, ${lng}</strong>. Click again to change.`;
        status.style.background = '#d1fae5';
        status.style.color      = '#065f46';
    });

    // Use current location
    window.useMyLocation = function() {
        const btn = document.getElementById('useLocationBtn');
        btn.textContent = '⏳ Getting location...';
        btn.disabled    = true;

        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            btn.textContent = '📍 Use My Current Location';
            btn.disabled    = false;
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude.toFixed(7);
                const lng = position.coords.longitude.toFixed(7);

                if (marker) pinMap.removeLayer(marker);

                const icon = L.divIcon({
                    html: '<div style="background:#2563EB; color:white; padding:6px 10px; border-radius:8px; font-size:12px; font-weight:600; box-shadow:0 2px 8px rgba(0,0,0,0.3); white-space:nowrap;">📍 Your Location</div>',
                    className: '',
                    iconAnchor: [0, 0]
                });

                marker = L.marker([lat, lng], {icon}).addTo(pinMap);
                pinMap.setView([lat, lng], 17);

                document.getElementById('latInput').value = lat;
                document.getElementById('lngInput').value = lng;

                const status = document.getElementById('pinStatus');
                status.innerHTML = `✅ Set to your current location: <strong>${lat}, ${lng}</strong>. Click map to adjust.`;
                status.style.background = '#dbeafe';
                status.style.color      = '#1e40af';

                btn.textContent = '📍 Use My Current Location';
                btn.disabled    = false;
            },
            function(error) {
                alert('Could not get your location. Please allow location access or pin manually.');
                btn.textContent = '📍 Use My Current Location';
                btn.disabled    = false;
            }
        );
    };
});
</script>
</body>
</html>