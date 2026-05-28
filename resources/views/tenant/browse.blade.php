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
            <form method="GET" action="/browse" style="display:flex; gap:10px; flex:1; flex-wrap:wrap;">
                @foreach(request()->except(['location','sort']) as $key => $value)
                    @if(is_array($value))
                        @foreach($value as $v)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <input type="text"
                    name="location"
                    placeholder="🔍 Search by location..."
                    value="{{ request('location') }}"
                    style="flex:1; min-width:200px; padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-size:14px; outline:none;">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>

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
                @endif
            </div>
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
                </select>
            </form>

            <button onclick="toggleMapView()"
                id="mapToggleBtn"
                class="btn btn-outline"
                style="white-space:nowrap;">
                🗺️ Map View
            </button>
        </div>

        <!-- MAP VIEW (hidden by default) -->
        <div id="browseMapContainer" style="display:none; margin-bottom:24px;">
            <div id="browseMap" style="height:450px; border-radius:10px; overflow:hidden; border:1px solid var(--border);"></div>
        </div>

        <!-- ACTIVE FILTERS DISPLAY -->
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
    // Sync price range slider with hidden input
    const slider = document.querySelector('input[name="max_price"]');
    if (slider) {
        slider.addEventListener('change', function() {
            document.getElementById('priceDisplay').textContent =
                'KSh ' + Number(this.value).toLocaleString();
        });
    }
</script>

<script>
    let browseMap     = null;
    let mapVisible    = false;
    let markersAdded  = false;

    // Properties data from PHP
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

            // Initialize map only once
            if (!browseMap) {
                // Center on Rongai
                browseMap = L.map('browseMap').setView([-1.3978, 36.7565], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(browseMap);
            }

            // Add markers if not added yet
            if (!markersAdded && properties.length > 0) {
                const bounds = [];

                properties.forEach(prop => {
                    const icon = L.divIcon({
                        html: `<div style="background:#1E7A5A; color:white; padding:5px 10px; border-radius:6px; font-size:12px; font-weight:600; white-space:nowrap; box-shadow:0 2px 6px rgba(0,0,0,0.3); cursor:pointer;">🏠 KSh ${prop.price}</div>`,
                        className: '',
                        iconAnchor: [0, 0]
                    });

                    const marker = L.marker([prop.lat, prop.lng], {icon})
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

                if (bounds.length > 0) {
                    browseMap.fitBounds(bounds, {padding: [40, 40]});
                }

                markersAdded = true;
            }

            // Fix map size issue when container was hidden
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