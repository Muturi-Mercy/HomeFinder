<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HomeFinder Admin Panel')</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    @yield('styles')
</head>
<body>
<div class="admin-layout">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <h2>Home<span>Finder</span></h2>
            <p>ADMIN PANEL</p>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Dashboard
            </a>

            <div class="nav-section">Management</div>
            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <span class="nav-icon">👥</span> User Management
            </a>
            <a href="{{ route('admin.landlords') }}" class="{{ request()->routeIs('admin.landlords') ? 'active' : '' }}">
                <span class="nav-icon">🏢</span> Landlords
            </a>
            <a href="{{ route('admin.verification') }}" class="{{ request()->routeIs('admin.verification') ? 'active' : '' }}">
                <span class="nav-icon">✅</span> Property Verification
                @php $pending = \App\Models\Property::where('status','pending')->count(); @endphp
                @if($pending > 0)
                    <span class="badge-count">{{ $pending }}</span>
                @endif
            </a>
            <a href="{{ route('admin.properties') }}" class="{{ request()->routeIs('admin.properties') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span> Listings Management
            </a>

            <div class="nav-section">Reports</div>
            <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <span class="nav-icon">🚨</span> Reports & Complaints
                @php $pendingReports = \App\Models\Report::where('status','pending')->count(); @endphp
                @if($pendingReports > 0)
                    <span class="badge-count">{{ $pendingReports }}</span>
                @endif
            </a>
            <a href="{{ route('admin.analytics') }}" class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <span class="nav-icon">📈</span> Analytics & Insights
            </a>

            <div class="nav-section">System</div>
            <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span> System Settings
            </a>
        </nav>

        <div style="padding:20px; border-top:1px solid var(--border);">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline" style="width:100%; justify-content:center;">
                    🚪 Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="admin-main">

        <!-- TOP BAR -->
        <div class="admin-topbar">
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            <div class="topbar-right">
                <div style="text-align:right;">
                    <div class="admin-name">{{ Auth::guard('admin')->user()->name }}</div>
                    <div class="admin-role">Super Administrator</div>
                </div>
                <div class="avatar">{{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}</div>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="admin-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>

    </main>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@yield('scripts')
</body>
</html>