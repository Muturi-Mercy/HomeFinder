@extends('layouts.app')

@section('title', 'HomeFinder - Find Your Perfect Rental Home')

@section('content')

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-content">
        <h1>Find Your <span>Perfect</span> Rental Home</h1>
        <p>Connect with verified landlords and find houses that match your needs and budget in Ongata Rongai.</p>

        <!-- SEARCH BAR -->
        <form action="/browse" method="GET">
            <div class="search-bar">
                <input type="text"
                    name="location"
                    placeholder="📍 Enter location (e.g., Rongai Town, Kiserian)"
                    value="{{ request('location') }}">
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
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <!-- STATS -->
        <div class="hero-stats">
            <div class="stat">
                <div class="stat-icon">🏠</div>
                <div class="stat-text">
                    <strong>500+</strong>
                    <span>Active Listings</span>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon">⭐</div>
                <div class="stat-text">
                    <strong>4.8/5</strong>
                    <span>Average Rating</span>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon">✅</div>
                <div class="stat-text">
                    <strong>100%</strong>
                    <span>Verified Landlords</span>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-image">
        <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=600&q=80" alt="Modern House">
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-it-works" id="how-it-works">
    <h2 class="section-title">How HomeFinder Works</h2>
    <p class="section-subtitle">Find your perfect home in 3 simple steps</p>
    <div class="steps">
        <div class="step">
            <div class="step-icon">🔍</div>
            <h3>1. Search</h3>
            <p>Filter houses by location, price, and amenities to find what suits you.</p>
        </div>
        <div class="step-arrow">→</div>
        <div class="step">
            <div class="step-icon">💬</div>
            <h3>2. Connect</h3>
            <p>Chat directly with landlords or schedule a viewing appointment.</p>
        </div>
        <div class="step-arrow">→</div>
        <div class="step">
            <div class="step-icon">🔑</div>
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
        <!-- Placeholder cards — we'll replace with real DB data in Phase 7 -->
        <div class="property-card">
            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400&q=80" alt="Property">
            <div class="property-card-body">
                <span class="property-badge">Featured</span>
                <div class="property-title">Modern 2BR Apartment</div>
                <div class="property-location">📍 Rongai Town, Nairobi</div>
                <div class="property-price">KSh 35,000 <span>/ month</span></div>
                <div class="property-features">
                    <span>🛏 2 Beds</span>
                    <span>🚿 2 Baths</span>
                    <span>🚗 Parking</span>
                    <span>📶 Wi-Fi</span>
                </div>
            </div>
        </div>

        <div class="property-card">
            <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=400&q=80" alt="Property">
            <div class="property-card-body">
                <span class="property-badge">Available</span>
                <div class="property-title">Spacious Bedsitter</div>
                <div class="property-location">📍 Kiserian, Kajiado</div>
                <div class="property-price">KSh 8,000 <span>/ month</span></div>
                <div class="property-features">
                    <span>🛏 1 Bed</span>
                    <span>🚿 1 Bath</span>
                    <span>💧 Water</span>
                </div>
            </div>
        </div>

        <div class="property-card">
            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400&q=80" alt="Property">
            <div class="property-card-body">
                <span class="property-badge">Featured</span>
                <div class="property-title">3BR House with Garden</div>
                <div class="property-location">📍 Olkeri, Kajiado</div>
                <div class="property-price">KSh 45,000 <span>/ month</span></div>
                <div class="property-features">
                    <span>🛏 3 Beds</span>
                    <span>🚿 2 Baths</span>
                    <span>🚗 Parking</span>
                    <span>🔒 Security</span>
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