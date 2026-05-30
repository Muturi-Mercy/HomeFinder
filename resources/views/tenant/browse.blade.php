@extends('layouts.app')
@section('title', 'Browse Houses - HomeFinder')

@section('content')
<div class="browse-layout">

    <!-- FILTERS SIDEBAR -->
    <aside class="filters-sidebar">
        <div class="filters-card">
            <h3 style="display:flex; justify-content:space-between; align-items:center;">
                Filters
                <a href="/browse" style="font-size:13px; color:var(--primary); font-weight:500;">Clear All</a>
            </h3>

            <form method="GET" action="/browse" id="filterForm">

                <!-- Preserve user location through filters -->
                <input type="hidden" name="user_lat" value="{{ request('user_lat') }}">
                <input type="hidden" name="user_lng" value="{{ request('user_lng') }}">

                <!-- LOCATION -->
                <div class="filter-group">
                    <label>📍 Location</label>
                    <input type="text"
                        name="location"
                        placeholder="e.g. Rongai Town"
                        value="{{ request('location') }}"
                        style="width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:8px; font-size:13px; outline:none;">
                </div>

                <!-- PRICE RANGE -->
                <div class="filter-group">
                    <label>💰 Max Price: <strong id="priceDisplay">KSh {{ number_format(request('max_price', 100000)) }}</strong></label>
                    <input type="range"
                        name="max_price"
                        min="3000"
                        max="100000"
                        step="1000"
                        value="{{ request('max_price', 100000) }}"
                        oninput="document.getElementById('priceDisplay').textContent = 'KSh ' + Number(this.value).toLocaleString()">
                    <div class="price-range">
                        <span>KSh 3,000</span>
                        <span>KSh 100,000</span>
                    </div>
                </div>

                <!-- PROPERTY TYPE -->
                <div class="filter-group">
                    <label>🏠 Property Type</label>
                    <div class="checkbox-group">
                        <label>
                            <input type="radio" name="type" value="" {{ !request('type') ? 'checked' : '' }}> All Types
                        </label>
                        @foreach(['bedsitter' => 'Bedsitter', 'studio' => 'Studio', '1_bedroom' => '1 Bedroom', '2_bedroom' => '2 Bedrooms', '3_bedroom' => '3+ Bedrooms'] as $value => $label)
                        <label>
                            <input type="radio" name="type" value="{{ $value }}" {{ request('type') === $value ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- BEDROOMS -->
                <div class="filter-group">
                    <label>🛏 Bedrooms</label>
                    <select name="bedrooms" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px; font-size:13px; outline:none; background:white;">
                        <option value="">Any</option>
                        <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1 Bedroom</option>
                        <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2 Bedrooms</option>
                        <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3 Bedrooms</option>
                    </select>
                </div>

                <!-- FURNISHED -->
                <div class="filter-group">
                    <label>✨ Condition</label>
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="furnished" value="1" {{ request('furnished') ? 'checked' : '' }}>
                            Furnished only
                        </label>
                    </div>
                </div>

                <!-- AMENITIES -->
                <div class="filter-group">
                    <label>🏷️ Amenities</label>
                    <div class="checkbox-group">
                        @foreach($amenities as $amenity)
                        <label>
                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                {{ in_array($amenity->id, request('amenities', [])) ? 'checked' : '' }}>
                            {{ $amenity->icon }} {{ $amenity->name }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                    🔍 Apply Filters
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="browse-main">

        <!-- SEARCH BAR -->
        <div style="background:white; border-radius:10px; padding:16px 20px; box-shadow:var(--shadow); margin-bottom:20px; display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <form method="GET" action="/browse" style="display:flex; gap:10px; flex:1; flex-wrap:wrap;" id="browseSearchForm">
                @foreach(request()->except(['location','sort']) as $key => $value)
                    @if(is_array($value))
                        @foreach($value as $v)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <input type="hidden" name="user_lat" id="browseUserLat" value="{{ request('user_lat') }}">
                <input type="hidden" name="user_lng" id="browseUserLng" value="{{ request('user_lng') }}">
                <input type="hidden" name="sort" id="browseSort" value="{{ request('sort', 'newest') }}">
                <input type="text"
                    name="location"
                    id="browseLocationInput"
                    placeholder="🔍 Search by location..."
                    value="{{ request('location') }}"
                    style="flex:1; min-width:200px; padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-size:14px; outline:none;">
                <button type="submit" class="btn btn-primary">Search</button>
                <button type="button" onclick="browseNearMe()" id="browseNearMeBtn"
                    style="background:var(--accent); color:white; border:none; padding:10px 16px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap;">
                    🎯 Near Me
                </button>
            </form>
        </div>

        <!-- NEAR ME BANNER -->
        @if(request('user_lat') && request('user_lng') && request('sort') === 'nearest')
        <div style="background:#dbeafe; color:#1e40af; padding:12px 16px; border-radius:10px; font-size:13px; font-weight:500; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
            🎯 Showing listings sorted by distance from your location.
            <a href="/browse" style="margin-left:auto; color:#1e40af; font-size:12px; text-decoration:underline; white-space:nowrap;">Clear</a>
        </div>
        @endif

        <!-- RESULTS HEADER -->
        <div class="browse-header">
            <div>
                <h2 style="font-size:18px; font-weight:700;">
                    {{ $properties->total() }} {{ Str::plural('House', $properties->total()) }} Found
                </h2>
                @if(request('location'))
                    <p style="font-size:13px; color:var(--gray); margin-top:2px;">
                        Showing results for "<strong>{{ request('location') }}</strong>"
                    </p>
                @elseif(request('user_lat') && request('sort') === 'nearest')
                    <p style="font-size:13px; color:var(--gray); margin-top:2px;">
                        📍 Sorted by distance from your location
                    </p>
                @endif
            </div>

            <!-- SORT -->
            <form method="GET" action="/browse">
                @foreach(request()->except('sort') as $key => $value)
                    @if(is_array($value))
                        @foreach($value as $v)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <select name="sort" onchange="this.form.submit()"
                    style="padding:8px 14px; border:1px solid var(--border); border-radius:8px; font-size:14px; outline:none; cursor:pointer;">
                    <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    @if(request('user_lat') && request('user_lng'))
                    <option value="nearest" {{ request('sort') === 'nearest' ? 'selected' : '' }}>📍 Nearest to Me</option>
                    @endif
                </select>
            </form>

            <button onclick="toggleMapView()"
                id="mapToggleBtn"
                class="btn btn-outline"
                style="white-space:nowrap;">
                🗺️ Map View
            </button>
        </div>

        <!-- MAP VIEW -->
        <div id="browseMapContainer" style="display:none; margin-bottom:24px;">
            <div id="browseMap" style="height:450px; border-radius:10px; overflow:hidden; border:1px solid var(--border);"></div>
        </div>

        <!-- ACTIVE FILTERS -->
        @if(request('location') || request('type') || request('max_price') || request('furnished') || request('amenities'))
        <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:16px;">
            @if(request('location'))
                <span style="background:#dbeafe; color:#1e40af; padding:4px 12px; border-radius:20px; font-size:13px;">
                    📍 {{ request('location') }}
                    <a href="{{ url('/browse?'.http_build_query(request()->except('location'))) }}" style="margin-left:6px; color:#1e40af;">×</a>
                </span>
            @endif
            @if(request('type'))
                <span style="background:#d1fae5; color:#065f46; padding:4px 12px; border-radius:20px; font-size:13px;">
                    🏠 {{ str_replace('_',' ', ucfirst(request('type'))) }}
                </span>
            @endif
            @if(request('max_price') && request('max_price') < 100000)
                <span style="background:#fef3c7; color:#92400e; padding:4px 12px; border-radius:20px; font-size:13px;">
                    💰 Max KSh {{ number_format(request('max_price')) }}
                </span>
            @endif
            @if(request('furnished'))
                <span style="background:#ede9fe; color:#5b21b6; padding:4px 12px; border-radius:20px; font-size:13px;">
                    ✨ Furnished
                </span>
            @endif
        </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- PROPERTY GRID -->
        @if($properties->count() === 0)
            <div style="text-align:center; padding:80px 20px; background:white; border-radius:10px; box-shadow:var(--shadow);">
                <div style="font-size:56px; margin-bottom:16px;">🏠</div>
                <h3 style="font-size:20px; font-weight:700;">No properties found</h3>
                <p style="color:var(--gray); margin-top:8px; font-size:15px;">Try adjusting your filters or search in a different location.</p>
                <a href="/browse" class="btn btn-primary" style="margin-top:20px; display:inline-block;">Clear All Filters</a>
            </div>
        @else
            <div class="properties-grid">
                @foreach($properties as $property)
                <div class="property-card" onclick="window.location='/properties/{{ $property->id }}'">
                    @if($property->cover_image)
                        <img src="{{ asset('storage/'.$property->cover_image) }}" alt="{{ $property->title }}">
                    @else
                        <div style="width:100%; height:200px; background:var(--background); display:flex; align-items:center; justify-content:center; font-size:48px;">🏠</div>
                    @endif
                    <div class="property-card-body">

                        {{-- Distance badge --}}
                        @if(isset($userLat) && $userLat && $property->latitude && $property->longitude)
                            @php
                                $R    = 6371;
                                $dLat = deg2rad($property->latitude - $userLat);
                                $dLng = deg2rad($property->longitude - $userLng);
                                $a    = sin($dLat/2)*sin($dLat/2) + cos(deg2rad($userLat))*cos(deg2rad($property->latitude))*sin($dLng/2)*sin($dLng/2);
                                $dist = round($R * 2 * atan2(sqrt($a), sqrt(1-$a)), 1);
                            @endphp
                            <span style="background:#dbeafe; color:#1e40af; font-size:11px; font-weight:600; padding:3px 10px; border-radius:20px; display:inline-block; margin-bottom:6px;">
                                📍 {{ $dist }} km away
                            </span>
                        @endif

                        @if($property->is_featured)
                            <span class="property-badge">⭐ Featured</span>
                        @endif
                        <div class="property-title">{{ $property->title }}</div>
                        <div class="property-location">📍 {{ $property->location }}</div>
                        <div class="property-price">
                            KSh {{ number_format($property->price) }}
                            <span>/ month</span>
                        </div>
                        <div class="property-features">
                            <span>🛏 {{ $property->bedrooms }} Bed</span>
                            <span>🚿 {{ $property->bathrooms }} Bath</span>
                            @if($property->is_furnished)
                                <span>🛋 Furnished</span>
                            @endif
                            @foreach($property->amenities->take(2) as $amenity)
                                <span>{{ $amenity->icon }} {{ $amenity->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- PAGINATION -->
            <div style="margin-top:32px; display:flex; justify-content:center;">
                {{ $properties->links() }}
            </div>
        @endif

    </div>
</div>
@endsection

@section('scripts')
<script>
// Price slider sync
const slider = document.querySelector('input[name="max_price"]');
if (slider) {
    slider.addEventListener('change', function() {
        document.getElementById('priceDisplay').textContent =
            'KSh ' + Number(this.value).toLocaleString();
    });
}

// Near Me button on browse page
function browseNearMe() {
    const btn = document.getElementById('browseNearMeBtn');
    btn.textContent = '⏳ Getting location...';
    btn.disabled    = true;

    if (!navigator.geolocation) {
        alert('Geolocation not supported by your browser.');
        btn.textContent = '🎯 Near Me';
        btn.disabled    = false;
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            document.getElementById('browseUserLat').value    = position.coords.latitude;
            document.getElementById('browseUserLng').value    = position.coords.longitude;
            document.getElementById('browseSort').value       = 'nearest';
            document.getElementById('browseLocationInput').value = '';
            document.getElementById('browseSearchForm').submit();
        },
        function(error) {
            alert('Could not get your location. Please allow location access.');
            btn.textContent = '🎯 Near Me';
            btn.disabled    = false;
        }
    );
}

// Geocode typed location on browse search bar
document.getElementById('browseSearchForm').addEventListener('submit', function(e) {
    const locationInput = document.getElementById('browseLocationInput').value.trim();
    const userLat       = document.getElementById('browseUserLat').value;

    // If GPS already set just submit
    if (userLat) return;

    if (locationInput) {
        e.preventDefault();
        const query = encodeURIComponent(locationInput + ', Rongai, Kenya');
        fetch(`https://nominatim.openstreetmap.org/search?q=${query}&format=json&limit=1`)
            .then(r => r.json())
            .then(data => {
                if (data && data.length > 0) {
                    document.getElementById('browseUserLat').value = data[0].lat;
                    document.getElementById('browseUserLng').value = data[0].lon;
                    document.getElementById('browseSort').value    = 'nearest';
                }
                document.getElementById('browseSearchForm').submit();
            })
            .catch(() => document.getElementById('browseSearchForm').submit());
    }
});

// Map view toggle
let browseMap    = null;
let mapVisible   = false;
let markersAdded = false;

const properties = [
    @foreach($properties as $property)
    @if($property->latitude && $property->longitude)
    {
        id:       {{ $property->id }},
        title:    "{{ addslashes($property->title) }}",
        location: "{{ addslashes($property->location) }}",
        price:    "{{ number_format($property->price) }}",
        lat:      {{ $property->latitude }},
        lng:      {{ $property->longitude }},
        type:     "{{ str_replace('_',' ', ucfirst($property->property_type)) }}",
        beds:     {{ $property->bedrooms }},
        url:      "/properties/{{ $property->id }}"
    },
    @endif
    @endforeach
];

function toggleMapView() {
    const container = document.getElementById('browseMapContainer');
    const btn       = document.getElementById('mapToggleBtn');
    mapVisible      = !mapVisible;

    if (mapVisible) {
        container.style.display = 'block';
        btn.textContent         = '📋 List View';
        btn.style.background    = 'var(--primary)';
        btn.style.color         = 'white';

        if (!browseMap) {
            browseMap = L.map('browseMap').setView([-1.3978, 36.7565], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(browseMap);
        }

        if (!markersAdded && properties.length > 0) {
            const bounds = [];
            properties.forEach(prop => {
                const icon = L.divIcon({
                    html: `<div style="background:#1E7A5A; color:white; padding:5px 10px; border-radius:6px; font-size:12px; font-weight:600; white-space:nowrap; box-shadow:0 2px 6px rgba(0,0,0,0.3); cursor:pointer;">🏠 KSh ${prop.price}</div>`,
                    className: '',
                    iconAnchor: [0, 0]
                });
                L.marker([prop.lat, prop.lng], {icon})
                    .addTo(browseMap)
                    .bindPopup(`
                        <div style="min-width:200px;">
                            <strong style="font-size:14px;">${prop.title}</strong><br>
                            <span style="color:#6c757d; font-size:13px;">📍 ${prop.location}</span><br>
                            <span style="color:#1E7A5A; font-weight:700; font-size:15px;">KSh ${prop.price}/mo</span><br>
                            <span style="font-size:12px;">🛏 ${prop.beds} bed • ${prop.type}</span><br>
                            <a href="${prop.url}" style="display:inline-block; margin-top:8px; background:#1E7A5A; color:white; padding:6px 14px; border-radius:6px; font-size:12px; text-decoration:none;">View Details →</a>
                        </div>
                    `);
                bounds.push([prop.lat, prop.lng]);
            });
            if (bounds.length > 0) browseMap.fitBounds(bounds, {padding: [40, 40]});
            markersAdded = true;
        }

        setTimeout(() => browseMap.invalidateSize(), 100);

    } else {
        container.style.display = 'none';
        btn.textContent         = '🗺️ Map View';
        btn.style.background    = 'transparent';
        btn.style.color         = 'var(--primary)';
    }
}
</script>
@endsection