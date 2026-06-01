@extends('layouts.app')

@section('title', 'My Dashboard - HomeFinder')

@section('content')
<div class="dashboard-layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">Home<span>Finder</span></div>
        <ul class="sidebar-menu">
            <li><a href="/dashboard" class="active"><span class="icon"><i class="fa-solid fa-house"></i></span> Dashboard</a></li>
            <li><a href="/browse"><span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span> Browse Houses</a></li>
            <li><a href="/favourites"><span class="icon"><i class="fa-solid fa-heart" style="color: rgb(230, 57, 70);"></i></span> Favourites</a></li>
            <li><a href="/bookings"><span class="icon"><i class="fa-solid fa-calendar-days"; style="color:#1e7a5a"></i></span> My Bookings</a></li>
            <li><a href="/messages"><span class="icon"><i class="fa-solid fa-comments" style="color:#2563EB;"></i></span> Messages</a></li>
            <li><a href="/profile"><span class="icon"><i class="fa-solid fa-user" style="color:#1e7a5a"></i></span> Profile</a></li>
        </ul>
        <div style="padding: 24px; margin-top: auto;">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="btn btn-outline" style="width:100%">Logout <i class="fa-solid fa-arrow-right-from-bracket"></i></button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <div class="dashboard-header">
            <h1>Welcome back, {{ Auth::user()->name }}!</h1>
            <p>Here's what's happening with your house search.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-icon green"><i class="fa-solid fa-magnifying-glass"></i></div>
                <div>
                    <h3>{{ \App\Models\Property::where('status','approved')->count() }}</h3>
                    <p>Available Houses</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon blue"><i class="fa-solid fa-heart" style="color: rgb(230, 57, 70);"></i></div>
                <div>
                    <h3>{{ \App\Models\Favourite::where('user_id', Auth::id())->count() }}</h3>
                    <p>Favourites</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon orange"><i class="fa-solid fa-calendar-days"; style="color:#1e7a5a"></i></div>
                <div>
                    <h3>{{ \App\Models\Booking::where('user_id', Auth::id())->count() }}</h3>
                    <p>Bookings</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon red"><i class="fa-solid fa-comments" style="color:#2563EB;"></i></div>
                <div>
                    <h3>{{ \App\Models\Booking::where('user_id', Auth::id())->where('status','confirmed')->count() }}</h3>
                    <p>Confirmed Bookings</p>
                </div>
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div style="background:white; border-radius:10px; padding:28px; box-shadow: var(--shadow);">
            <h3 style="margin-bottom:20px; font-size:18px;">Quick Actions</h3>
            <div style="display:flex; gap:16px; flex-wrap:wrap;">
                <a href="/browse" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass" style="margin-right:6px;"></i> Browse Houses</a>
                <a href="/browse?type=bedsitter" class="btn btn-outline"><i class="fa-solid fa-bed" style="color: rgba(245, 158, 11, 1); margin-right:6px;"></i> Find Bedsitter</a>
                <a href="/browse?type=1_bedroom" class="btn btn-outline"><i class="fa-solid fa-house" style="color: rgba(245, 158, 11, 1); margin-right:6px;"></i> 1 Bedroom</a>
                <a href="/browse?type=2_bedroom" class="btn btn-outline"><i class="fa-solid fa-house" style="color: rgba(245, 158, 11, 1) ; margin-right:6px;"></i> 2 Bedrooms</a>
            </div>
        </div>

    </main>
</div>
@endsection