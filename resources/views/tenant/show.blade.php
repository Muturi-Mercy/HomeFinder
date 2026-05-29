@extends('layouts.app')
@section('title', $property->title . ' - HomeFinder')

@section('content')
<div style="max-width:1100px; margin:0 auto; padding:32px 24px;">

    <!-- BACK BUTTON -->
    <a href="/browse" style="display:inline-flex; align-items:center; gap:6px; color:var(--gray); font-size:14px; margin-bottom:20px;">← Back to Search</a>

    <div style="display:grid; grid-template-columns:1fr 340px; gap:28px;">

        <!-- LEFT COLUMN -->
        <div>
            <!-- MAIN IMAGE -->
            <div style="border-radius:12px; overflow:hidden; margin-bottom:16px;">
                @if($property->cover_image)
                    <img src="{{ asset('storage/'.$property->cover_image) }}" alt="{{ $property->title }}" style="width:100%; height:420px; object-fit:cover;">
                @else
                    <div style="width:100%; height:420px; background:var(--background); display:flex; align-items:center; justify-content:center; font-size:64px;">🏠</div>
                @endif
            </div>

            <!-- THUMBNAIL IMAGES -->
            @if($property->images->count() > 0)
            <div style="display:flex; gap:10px; margin-bottom:24px; overflow-x:auto;">
                @foreach($property->images as $image)
                <img src="{{ asset('storage/'.$image->image_path) }}" style="width:100px; height:70px; object-fit:cover; border-radius:8px; cursor:pointer; flex-shrink:0;">
                @endforeach
            </div>
            @endif

            <!-- PROPERTY INFO -->
            <div style="background:white; border-radius:12px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
                    <div>
                        <h1 style="font-size:24px; font-weight:700; margin-bottom:8px;">{{ $property->title }}</h1>
                        <p style="color:var(--gray); font-size:15px;">📍 {{ $property->location }}</p>
                    </div>
                    <span style="background:#d1fae5; color:#065f46; padding:4px 14px; border-radius:20px; font-size:13px; font-weight:600;">✓ Verified</span>
                </div>

                <div style="font-size:28px; font-weight:700; color:var(--primary); margin-bottom:20px;">
                    KSh {{ number_format($property->price) }}<span style="font-size:16px; font-weight:400; color:var(--gray);"> / month</span>
                </div>

                <!-- FEATURES -->
                <div style="display:flex; gap:24px; padding:20px; background:var(--background); border-radius:10px; margin-bottom:24px; flex-wrap:wrap;">
                    <div style="text-align:center;">
                        <div style="font-size:24px;">🛏</div>
                        <div style="font-size:16px; font-weight:700;">{{ $property->bedrooms }}</div>
                        <div style="font-size:12px; color:var(--gray);">Bedrooms</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:24px;">🚿</div>
                        <div style="font-size:16px; font-weight:700;">{{ $property->bathrooms }}</div>
                        <div style="font-size:12px; color:var(--gray);">Bathrooms</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:24px;">🏠</div>
                        <div style="font-size:16px; font-weight:700;">{{ str_replace('_',' ', ucfirst($property->property_type)) }}</div>
                        <div style="font-size:12px; color:var(--gray);">Type</div>
                    </div>
                    @if($property->is_furnished)
                    <div style="text-align:center;">
                        <div style="font-size:24px;">🛋</div>
                        <div style="font-size:16px; font-weight:700;">Yes</div>
                        <div style="font-size:12px; color:var(--gray);">Furnished</div>
                    </div>
                    @endif
                </div>

                <!-- DESCRIPTION -->
                <h3 style="font-size:17px; font-weight:700; margin-bottom:12px;">Description</h3>
                <p style="color:var(--gray); line-height:1.8; font-size:15px;">{{ $property->description }}</p>

                <!-- AMENITIES -->
                @if($property->amenities->count() > 0)
                <h3 style="font-size:17px; font-weight:700; margin:24px 0 12px;">Amenities</h3>
                <div style="display:flex; flex-wrap:wrap; gap:10px;">
                    @foreach($property->amenities as $amenity)
                    <span style="background:var(--background); padding:8px 16px; border-radius:20px; font-size:13px;">{{ $amenity->icon }} {{ $amenity->name }}</span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- MAP SECTION -->
        @if($property->latitude && $property->longitude)
        <div style="background:white; border-radius:12px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
            <h3 style="font-size:17px; font-weight:700; margin-bottom:16px;">📍 Property Location</h3>

            <!-- MAP CONTAINER -->
            <div id="propertyMap" style="height:350px; border-radius:10px; overflow:hidden; border:1px solid var(--border);"></div>

            <!-- ROUTE INFO -->
            <div id="routeInfo" style="display:none; margin-top:16px; padding:16px; background:var(--background); border-radius:10px;">
                <div style="display:flex; gap:24px; flex-wrap:wrap;">
                    <div style="text-align:center;">
                        <div style="font-size:22px; font-weight:700; color:var(--primary);" id="routeDistance">--</div>
                        <div style="font-size:13px; color:var(--gray);">Distance</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:22px; font-weight:700; color:var(--accent);" id="routeDuration">--</div>
                        <div style="font-size:13px; color:var(--gray);">Drive Time</div>
                    </div>
                    <div style="flex:1; display:flex; align-items:center;">
                        <p style="font-size:13px; color:var(--gray);">Route shown from your current location to this property.</p>
                    </div>
                </div>
            </div>

            <!-- BUTTONS -->
            <div style="display:flex; gap:12px; margin-top:16px; flex-wrap:wrap;">
                <button onclick="getMyLocation()" class="btn btn-primary">
                    📍 Show Route From My Location
                </button>
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $property->latitude }},{{ $property->longitude }}"
                    target="_blank" class="btn btn-outline">
                    🗺️ Open in Google Maps
                </a>
            </div>
        </div>
        @else
        <div style="background:white; border-radius:12px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
            <h3 style="font-size:17px; font-weight:700; margin-bottom:12px;">📍 Property Location</h3>
            <p style="color:var(--gray); font-size:14px;">📍 {{ $property->location }}</p>
            <p style="color:var(--gray); font-size:13px; margin-top:8px;">Exact map location not available for this property.</p>
        </div>
        @endif

        <!-- RIGHT COLUMN -->
        <div>

            <!-- LANDLORD CARD -->
            <div style="background:white; border-radius:12px; padding:24px; box-shadow:var(--shadow); margin-bottom:20px;">
                <h3 style="font-size:16px; font-weight:700; margin-bottom:16px;">Landlord</h3>
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                    <div style="width:48px; height:48px; background:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:18px;">
                        {{ strtoupper(substr($property->landlord->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600;">{{ $property->landlord->name }}</div>
                        <div style="font-size:12px; color:var(--gray);">✓ Verified Landlord</div>
                    </div>
                </div>
                <div style="font-size:13px; color:var(--gray); margin-bottom:16px;">📞 {{ $property->landlord->phone }}</div>

                @auth
                    <!-- MESSAGE LANDLORD BUTTON -->
                        <a href="{{ route('tenant.conversation', $property) }}"
                            class="btn btn-primary"
                            style="width:100%; display:block; text-align:center; margin-bottom:10px;">
                            💬 Message Landlord
                        </a>
                @else
                        <a href="/login"
                            class="btn btn-primary"
                            style="width:100%; display:block; text-align:center; margin-bottom:10px;">
                            💬 Message Landlord
                        </a>
                @endauth

                @auth
                    <!-- FAVOURITE BUTTON -->
                    <form method="POST" action="{{ route('tenant.favourite.toggle', $property) }}" style="margin-bottom:10px;">
                        @csrf
                        <button type="submit" class="btn {{ $isFavourited ? 'btn-primary' : 'btn-outline' }}" style="width:100%;">
                            {{ $isFavourited ? '❤️ Saved' : '🤍 Save Property' }}
                        </button>
                    </form>
                @else
                    <a href="/login" class="btn btn-outline" style="width:100%; display:block; text-align:center; margin-bottom:10px;">🤍 Save Property</a>
                @endauth
            </div>

            <!-- BOOK VIEWING -->
            <div style="background:white; border-radius:12px; padding:24px; box-shadow:var(--shadow);">
                <h3 style="font-size:16px; font-weight:700; margin-bottom:16px;">📅 Book a Viewing</h3>

                @if(session('success'))
                    <div style="background:#d1fae5; color:#065f46; border:1px solid #6ee7b7; padding:12px; border-radius:8px; font-size:13px; margin-bottom:16px;">{{ session('success') }}</div>
                @endif

                @auth
                <form method="POST" action="{{ route('tenant.book', $property) }}">
                    @csrf
                    <div style="margin-bottom:14px;">
                        <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Preferred Date</label>
                        <input type="date" name="viewing_date" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px; font-size:14px; outline:none;" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    <div style="margin-bottom:14px;">
                        <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Preferred Time</label>
                        <select name="viewing_time" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px; font-size:14px; outline:none;" required>
                            <option value="">Select time</option>
                            <option value="09:00">9:00 AM</option>
                            <option value="10:00">10:00 AM</option>
                            <option value="11:00">11:00 AM</option>
                            <option value="14:00">2:00 PM</option>
                            <option value="15:00">3:00 PM</option>
                            <option value="16:00">4:00 PM</option>
                        </select>
                    </div>
                    <div style="margin-bottom:14px;">
                        <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Message (Optional)</label>
                        <textarea name="message" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px; font-size:14px; outline:none; resize:vertical; min-height:80px;" placeholder="Any specific questions for the landlord..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;">📅 Schedule Viewing</button>
                </form>
                @else
                    <p style="color:var(--gray); font-size:14px; text-align:center; margin-bottom:16px;">Please login to book a viewing.</p>
                    <a href="/login" class="btn btn-primary" style="width:100%; display:block; text-align:center;">Login to Book</a>
                @endauth
            </div>

            <!-- REPORT LISTING -->
          <!-- REPORT LISTING -->
            @auth
                <div style="margin-top:20px; background:#fff8f8; border:1px solid #fecaca; border-radius:10px; padding:16px;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <p style="font-size:13px; font-weight:600; color:#991b1b; margin-bottom:2px;">🚩 Report this Listing</p>
                            <p style="font-size:12px; color:var(--gray);">Seen something suspicious? Let us know.</p>
                        </div>
                        <button onclick="toggleReport()"
                            id="reportToggleBtn"
                            style="background:#ef4444; color:white; border:none; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                            Report
                        </button>
                    </div>

                    <div id="reportFormContainer" style="display:none; margin-top:16px; border-top:1px solid #fecaca; padding-top:16px;">
                        @if(session('success') && str_contains(session('success'), 'Report'))
                            <div style="background:#d1fae5; color:#065f46; padding:10px; border-radius:8px; font-size:13px; margin-bottom:12px;">{{ session('success') }}</div>
                        @endif
                        <form method="POST" action="{{ route('tenant.report', $property) }}">
                            @csrf
                            <div style="margin-bottom:10px;">
                                <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Reason *</label>
                                <select name="reason"
                                    style="width:100%; padding:10px; border:1px solid #fca5a5; border-radius:8px; font-size:13px; outline:none; background:white;"
                                    required>
                                    <option value="">Select a reason...</option>
                                    <option value="Fake Listing">🚫 Fake Listing</option>
                                    <option value="Wrong Information">❌ Wrong Information</option>
                                    <option value="Already Rented">🔒 Already Rented</option>
                                    <option value="Scam/Fraud">⚠️ Scam / Fraud</option>
                                    <option value="Duplicate Listing">📋 Duplicate Listing</option>
                                    <option value="Misleading Photos">📷 Misleading Photos</option>
                                    <option value="Other">💬 Other</option>
                                </select>
                            </div>
                            <div style="margin-bottom:12px;">
                                <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Description <span style="font-weight:400; color:var(--gray);">(optional)</span></label>
                                <textarea name="description"
                                    placeholder="Describe the issue in detail..."
                                    style="width:100%; padding:10px; border:1px solid #fca5a5; border-radius:8px; font-size:13px; resize:none; min-height:80px; outline:none; font-family:inherit;"></textarea>
                            </div>
                            <div style="display:flex; gap:8px;">
                                <button type="submit"
                                    style="flex:1; background:#ef4444; color:white; border:none; padding:10px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                                    🚩 Submit Report
                                </button>
                                <button type="button" onclick="toggleReport()"
                                    style="background:var(--background); border:1px solid var(--border); padding:10px 16px; border-radius:8px; font-size:13px; cursor:pointer;">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <!-- RELATED PROPERTIES -->
    @if($relatedProperties->count() > 0)
    <div style="margin-top:40px;">
        <h2 style="font-size:22px; font-weight:700; margin-bottom:20px;">Similar Properties Nearby</h2>
        <div class="properties-grid">
            @foreach($relatedProperties as $related)
            <div class="property-card" onclick="window.location='/properties/{{ $related->id }}'">
                @if($related->cover_image)
                    <img src="{{ asset('storage/'.$related->cover_image) }}" alt="{{ $related->title }}">
                @else
                    <div style="width:100%; height:200px; background:var(--background); display:flex; align-items:center; justify-content:center; font-size:48px;">🏠</div>
                @endif
                <div class="property-card-body">
                    <div class="property-title">{{ $related->title }}</div>
                    <div class="property-location">📍 {{ $related->location }}</div>
                    <div class="property-price">KSh {{ number_format($related->price) }} <span>/ month</span></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection



@section('scripts')
<script>
@if($property->latitude && $property->longitude)

const propLat  = {{ $property->latitude }};
const propLng  = {{ $property->longitude }};
const propName = "{{ addslashes($property->title) }}";
const orsKey   = "{{ config('services.ors.key') }}";

// Initialize map
const map = L.map('propertyMap').setView([propLat, propLng], 15);

// Add OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

// Custom property marker
const propertyIcon = L.divIcon({
    html: '<div style="background:#1E7A5A; color:white; padding:8px 12px; border-radius:8px; font-size:13px; font-weight:600; white-space:nowrap; box-shadow:0 2px 8px rgba(0,0,0,0.3);">🏠 ' + propName + '</div>',
    className: '',
    iconAnchor: [0, 0]
});

// Add property marker
const propertyMarker = L.marker([propLat, propLng], {icon: propertyIcon})
    .addTo(map)
    .bindPopup('<strong>' + propName + '</strong><br>📍 {{ addslashes($property->location) }}<br>💰 KSh {{ number_format($property->price) }}/month')
    .openPopup();

let userMarker  = null;
let routeLayer  = null;

// Get user location and draw route
function getMyLocation() {
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by your browser.');
        return;
    }

    const btn = event.target;
    btn.textContent = '⏳ Getting your location...';
    btn.disabled = true;

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            // Remove old user marker
            if (userMarker) map.removeLayer(userMarker);

            // Add user marker
            const userIcon = L.divIcon({
                html: '<div style="background:#2563EB; color:white; padding:6px 10px; border-radius:8px; font-size:12px; font-weight:600; box-shadow:0 2px 8px rgba(0,0,0,0.3);">📍 You</div>',
                className: '',
                iconAnchor: [0, 0]
            });

            userMarker = L.marker([userLat, userLng], {icon: userIcon})
                .addTo(map)
                .bindPopup('📍 Your Location')
                .openPopup();

            // Fit map to show both markers
            const bounds = L.latLngBounds(
                [userLat, userLng],
                [propLat, propLng]
            );
            map.fitBounds(bounds, {padding: [50, 50]});

            // Get route
            getRoute(userLat, userLng, propLat, propLng);

            btn.textContent = '🔄 Update My Location';
            btn.disabled = false;
        },
        function(error) {
            alert('Could not get your location. Please allow location access.');
            btn.textContent = '📍 Show Route From My Location';
            btn.disabled = false;
        }
    );
}

// Get route using OpenRouteService
function getRoute(fromLat, fromLng, toLat, toLng) {
    const url = `https://api.openrouteservice.org/v2/directions/driving-car?api_key=${orsKey}&start=${fromLng},${fromLat}&end=${toLng},${toLat}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.features && data.features.length > 0) {
                const feature = data.features[0];
                const coords  = feature.geometry.coordinates;
                const summary = feature.properties.summary;

                // Remove old route
                if (routeLayer) map.removeLayer(routeLayer);

                // Draw route line
                const latLngs = coords.map(c => [c[1], c[0]]);
                routeLayer = L.polyline(latLngs, {
                    color:   '#1E7A5A',
                    weight:  5,
                    opacity: 0.8
                }).addTo(map);

                // Show distance and time
                const distKm  = (summary.distance / 1000).toFixed(1);
                const distMin = Math.round(summary.duration / 60);
                const hours   = Math.floor(distMin / 60);
                const mins    = distMin % 60;
                const timeStr = hours > 0 ? `${hours}h ${mins}m` : `${distMin} min`;

                document.getElementById('routeDistance').textContent = distKm + ' km';
                document.getElementById('routeDuration').textContent = timeStr;
                document.getElementById('routeInfo').style.display   = 'block';
            }
        })
        .catch(error => {
            console.error('Route error:', error);
            if (routeLayer) map.removeLayer(routeLayer);
            routeLayer = L.polyline(
                [[fromLat, fromLng], [toLat, toLng]],
                {color: '#2563EB', weight: 3, dashArray: '8 8'}
            ).addTo(map);
        });
}

@endif

function toggleReport() {
    const container = document.getElementById('reportFormContainer');
    const btn       = document.getElementById('reportToggleBtn');
    const isHidden  = container.style.display === 'none';
    container.style.display = isHidden ? 'block' : 'none';
    btn.textContent         = isHidden ? 'Cancel' : 'Report';
    btn.style.background    = isHidden ? '#6b7280' : '#ef4444';
}
</script>
@endsection