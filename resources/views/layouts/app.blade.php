<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HomeFinder - Find Your Perfect Rental Home')</title>
    <link rel="stylesheet" href="{{ asset('css/homefinder.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    @yield('styles')
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="/" class="navbar-brand">
        🏠 Home<span>Finder</span>
    </a>
    <div class="nav-links">
        <a href="/">Home</a>
        <a href="/browse">Browse Houses</a>
        <a href="#how-it-works">How It Works</a>
        <a href="#contact">Contact</a>
    </div>
    <div style="display:flex; gap:12px; align-items:center;">
        @auth
            <a href="/dashboard" class="btn btn-outline">Dashboard</a>
            <form method="POST" action="/logout" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-primary">Logout</button>
            </form>
        @else
            <a href="/login" class="btn btn-outline">Login</a>
            <a href="/register" class="btn btn-primary">Sign Up</a>
        @endauth
    </div>
</nav>

<!-- PAGE CONTENT -->
@yield('content')

<!-- FOOTER -->
<footer class="footer" id="contact">
    <div class="footer-grid">
        <div>
            <div class="footer-brand">Home<span>Finder</span></div>
            <p style="font-size:14px; line-height:1.7;">Connecting landlords and tenants seamlessly in Ongata Rongai and surrounding areas.</p>
        </div>
        <div>
            <h4>Quick Links</h4>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/browse">Browse Houses</a></li>
                <li><a href="/register">Register</a></li>
                <li><a href="/login">Login</a></li>
            </ul>
        </div>
        <div>
            <h4>For Landlords</h4>
            <ul>
                <li><a href="/landlord/register">List Your Property</a></li>
                <li><a href="/landlord/login">Landlord Login</a></li>
            </ul>
        </div>
        <div>
            <h4>Contact</h4>
            <ul>
                <li><a href="#">Ongata Rongai, Nairobi</a></li>
                <li><a href="#">info@homefinder.co.ke</a></li>
                <li><a href="#">+254 700 123 456</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} HomeFinder. All rights reserved. | Multimedia University of Kenya</p>
    </div>
</footer>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@yield('scripts')
</body>
</html>