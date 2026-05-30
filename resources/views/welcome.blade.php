@extends('layouts.app')

@section('title', 'HomeFinder - Find Your Perfect Rental Home')

@section('content')

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-content">
        <h1>Find Your <span>Perfect</span> Rental Home</h1>
        <p>Connect with verified landlords and find houses that match your needs and budget in Ongata Rongai.</p>

        <!-- SMART SEARCH BAR -->
        <form action="/browse" method="GET" id="heroSearchForm">
            <div class="search-bar">
                <div class="location-wrapper">
                    <i class="fa-solid fa-location-pin" style="color: rgb(230, 57, 70);"></i>
                    <input type="text"
                        name="location"
                        id="locationInput"
                        placeholder="Enter location (e.g., Rongai Town, Kiserian)"
                        value="{{ request('location') }}">
                </div>
                <select name="max_price">
                    <option value="">Max Price</option>
                    <option value="5000">KSh 5,000</option>
                    <option value="8000">KSh 8,000</option>
                    <option value="10000">KSh 10,000</option>
                    <option value="15000">KSh 15,000</option>
                    <option value="20000">KSh 20,000</option>
                    <option value="30000">KSh 30,000</option>
                    <option value="50000">KSh 50,000</option>
                </select>
                <select name="type">
                    <option value="">Property Type</option>
                    <option value="bedsitter">Bedsitter</option>
                    <option value="studio">Studio</option>
                    <option value="1_bedroom">1 Bedroom</option>
                    <option value="2_bedroom">2 Bedrooms</option>
                    <option value="3_bedroom">3 Bedrooms</option>
                </select>
                <!-- Hidden fields for location-based sorting -->
                <input type="hidden" name="user_lat" id="heroUserLat">
                <input type="hidden" name="user_lng" id="heroUserLng">
                <input type="hidden" name="sort" id="heroSort" value="newest">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <!-- DIVIDER -->
        <div style="display:flex; align-items:center; gap:12px; margin:14px 0 10px;">
            <div style="flex:1; height:1px; background:rgba(0,0,0,0.1);"></div>
            <span style="font-size:13px; color:var(--gray);">or</span>
            <div style="flex:1; height:1px; background:rgba(0,0,0,0.1);"></div>
        </div>

        <!-- USE MY LOCATION BUTTON -->
        <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap; margin-bottom:20px;">
            <button type="button"
                onclick="searchNearMe()"
                id="nearMeBtn"
                style="background:white; border:2px solid var(--primary); color:var(--primary); padding:11px 22px; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:8px; transition:all 0.2s;"
                onmouseover="this.style.background='var(--primary)'; this.style.color='white'"
                onmouseout="this.style.background='white'; this.style.color='var(--primary)'">
                  <i class="fa-solid fa-location-crosshairs" style="color: rgb(230, 57, 70);"></i>
                Find Houses Near Me
            </button>
            <span style="font-size:13px; color:var(--gray);">Finds and sorts listings closest to you</span>
        </div>

        <!-- STATS -->
        <div class="hero-stats">
            <div class="stat">
                <div class="stat-icon">
                   <i class="fa-solid fa-house"></i>
                </div>
                <div class="stat-text">
                    <strong>100+</strong>
                    <span>Active Listings</span>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon">
                    <i class="fa-solid fa-star" style="color: rgba(245, 158, 11, 1);"></i>
                </div>
                <div class="stat-text">
                    <strong>4.8/5</strong>
                    <span>Average Rating</span>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="stat-text">
                    <strong>100%</strong>
                    <span>Verified Landlords</span>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-image">
        <img src="{{ asset('img/hero2image.jpg') }}" alt="Modern House">
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-it-works" id="how-it-works">
    <h2 class="section-title">How HomeFinder Works</h2>
    <p class="section-subtitle">Find your perfect home in 3 simple steps</p>
    <div class="steps">
        <div class="step">
            <div class="step-icon">
            <i class="fa-solid fa-magnifying-glass-location" style="color:#1E7A5A;"></i>
            </div>
            <h3>1. Search</h3>
            <p>Filter houses by location, price, and amenities to find what suits you.</p>
        </div>

        <div class="step-arrow">
            <i class="fa-solid fa-chevron-right" style="color: rgba(148, 163, 184, 1);"></i>
        </div>

        <div class="step">
            <div class="step-icon">
                <i class="fa-solid fa-comments" style="color:#2563EB;"></i>
            </div>
            <h3>2. Connect</h3>
            <p>Chat directly with landlords or schedule a viewing appointment.</p>
        </div>

        <div class="step-arrow">
            <i class="fa-solid fa-chevron-right" style="color: rgba(148, 163, 184, 1);"></i>
        </div>

        <div class="step">
            <div class="step-icon">
                <i class="fa-solid fa-key" style="color:#1E7A5A;"></i>
            </div>
            <h3>3. Rent</h3>
            <p>Move into your new home easily after meeting the landlord.</p>
        </div>
    </div>
</section>

<!-- FEATURED PROPERTIES -->
<section class="section">
    <div class="section-header">
        <h2 class="section-title" style="margin-bottom:0">Featured Properties</h2>
        <a href="/browse" class="btn btn-outline">View All</a>
    </div>

    <div class="properties-grid">
        <div class="property-card">
            <img src="{{ asset('img/LR1.jpg') }}" alt="PROPERTY">
            <div class="property-card-body">
                <span class="property-badge">Featured</span>
                <div class="property-title">Modern 2BR Apartment</div>
                <div class="property-location">
                    <i class="fa-solid fa-map-pin" style="color: rgba(230, 57, 70, 1);"></i>
                    Rongai Town, Nairobi
                </div>
                <div class="property-price">KSh 35,000 <span>/ month</span></div>
                <div class="property-features">
                    <span><i class="fa-solid fa-bed" style="color: rgba(37, 99, 235, 1);"></i> 2 Beds</span>
                    <span><i class="fa-solid fa-bath" style="color: rgba(14, 165, 233, 1);"></i> 2 Baths</span>
                    <span><i class="fa-solid fa-car" style="color: rgba(34, 197, 94, 1);"></i> Parking</span>
                    <span><i class="fa-solid fa-wifi" style="color: rgba(168, 85, 247, 1);"></i> Wi-Fi</span>
                </div>
            </div>
        </div>

        <div class="property-card">
            <img src="{{ asset('img/LR2.jpg') }}" alt="PROPERTY">
            <div class="property-card-body">
                <span class="property-badge">Available</span>
                <div class="property-title">Spacious Bedsitter</div>
               <div class="property-location">
                    <i class="fa-solid fa-map-pin" style="color: rgba(230, 57, 70, 1);"></i>
                    Kiserian, Kajiado
                </div>
                <div class="property-price">KSh 8,000 <span>/ month</span></div>
                <div class="property-features">
                    <span><i class="fa-solid fa-bed" style="color: rgba(37, 99, 235, 1);"></i> 1 Bed</span>
                    <span><i class="fa-solid fa-bath" style="color: rgba(14, 165, 233, 1);"></i> 1 Bath</span>
                    <span><i class="fa-solid fa-droplet" style="color: rgba(59, 130, 246, 1);"></i> Water</span>
                </div>
            </div>
        </div>

        <div class="property-card">
            <img src="{{ asset('img/LR3.jpg') }}" alt="PROPERTY">
            <div class="property-card-body">
                <span class="property-badge">Featured</span>
                <div class="property-title">3BR House with Garden</div>
               <div class="property-location">
                    <i class="fa-solid fa-map-pin" style="color: rgba(230, 57, 70, 1);"></i>
                    Olkeri, Kajiado
                </div>
                <div class="property-price">KSh 45,000 <span>/ month</span></div>
                <div class="property-features">
                    <span><i class="fa-solid fa-bed" style="color: rgba(37, 99, 235, 1);"></i> 3 Beds</span>
                    <span><i class="fa-solid fa-bath" style="color: rgba(14, 165, 233, 1);"></i> 2 Baths</span>
                    <span><i class="fa-solid fa-car" style="color: rgba(34, 197, 94, 1);"></i> Parking</span>
                    <span><i class="fa-solid fa-shield-halved" style="color: rgba(245, 158, 11, 1);"></i> Security</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CALL TO ACTION -->
<section style="background: var(--primary); padding: 70px 40px; text-align: center; color: white;">
    <h2 style="font-size: 34px; font-weight: 700; margin-bottom: 16px;">Are You a Landlord?</h2>
    <p style="font-size: 17px; opacity: 0.9; margin-bottom: 32px;">List your property for free and connect with thousands of potential tenants.</p>
    <a href="/landlord/register" class="btn" style="background:white; color: var(--primary); font-size:16px; padding: 14px 36px;">List Your Property</a>
</section>

@endsection

@section('scripts')
<script>
// OPTION 2 — GPS Near Me
function searchNearMe() {
    const btn = document.getElementById('nearMeBtn');
    btn.innerHTML = 'Getting your location...';
    btn.disabled  = true;

    if (!navigator.geolocation) {
        alert('Geolocation is not supported by your browser.');
        btn.innerHTML = 'Find Houses Near Me';
        btn.disabled  = false;
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            document.getElementById('heroUserLat').value  = lat;
            document.getElementById('heroUserLng').value  = lng;
            document.getElementById('heroSort').value     = 'nearest';
            document.getElementById('locationInput').value = '';

            btn.innerHTML = 'Location found! Searching...';
            document.getElementById('heroSearchForm').submit();
        },
        function(error) {
            alert('Could not get your location. Please allow location access or type a location manually.');
            btn.innerHTML = 'Find Houses Near Me';
            btn.disabled  = false;
        }
    );
}

// OPTION 1 — Geocode typed location then sort by nearest
document.getElementById('heroSearchForm').addEventListener('submit', function(e) {
    const locationInput = document.getElementById('locationInput').value.trim();
    const userLat       = document.getElementById('heroUserLat').value;

    // If GPS coords already set (from nearMe button) just submit
    if (userLat) return;

    // If user typed a location geocode it
    if (locationInput) {
        e.preventDefault();

        const query = encodeURIComponent(locationInput + ', Rongai, Kenya');
        fetch(`https://nominatim.openstreetmap.org/search?q=${query}&format=json&limit=1`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    document.getElementById('heroUserLat').value = data[0].lat;
                    document.getElementById('heroUserLng').value = data[0].lon;
                    document.getElementById('heroSort').value    = 'nearest';
                }
                document.getElementById('heroSearchForm').submit();
            })
            .catch(() => {
                // Geocoding failed — just do normal text search
                document.getElementById('heroSearchForm').submit();
            });
    }
});
</script>
@endsection