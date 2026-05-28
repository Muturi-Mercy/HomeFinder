@extends('layouts.app')
@section('title', 'My Favourites - HomeFinder')

@section('content')
<div style="max-width:1100px; margin:0 auto; padding:40px 24px;">
    <h1 style="font-size:26px; font-weight:700; margin-bottom:8px;">❤️ My Favourites</h1>
    <p style="color:var(--gray); margin-bottom:32px;">Properties you have saved.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($favourites->count() === 0)
        <div style="text-align:center; padding:80px; background:white; border-radius:10px; box-shadow:var(--shadow);">
            <div style="font-size:48px; margin-bottom:16px;">🤍</div>
            <h3>No saved properties yet</h3>
            <p style="color:var(--gray); margin-top:8px;">Browse houses and save the ones you like.</p>
            <a href="/browse" class="btn btn-primary" style="margin-top:20px; display:inline-block;">Browse Houses</a>
        </div>
    @else
        <div class="properties-grid">
            @foreach($favourites as $favourite)
                @if($favourite->property)
                <div class="property-card" onclick="window.location='/properties/{{ $favourite->property->id }}'">
                    @if($favourite->property->cover_image)
                        <img src="{{ asset('storage/'.$favourite->property->cover_image) }}" alt="{{ $favourite->property->title }}">
                    @else
                        <div style="width:100%; height:200px; background:var(--background); display:flex; align-items:center; justify-content:center; font-size:48px;">🏠</div>
                    @endif
                    <div class="property-card-body">
                        <div class="property-title">{{ $favourite->property->title }}</div>
                        <div class="property-location">📍 {{ $favourite->property->location }}</div>
                        <div class="property-price">KSh {{ number_format($favourite->property->price) }} <span>/ month</span></div>
                        <div class="property-features">
                            <span>🛏 {{ $favourite->property->bedrooms }} Bed</span>
                            <span>🚿 {{ $favourite->property->bathrooms }} Bath</span>
                        </div>
                        <form method="POST" action="{{ route('tenant.favourite.toggle', $favourite->property) }}" style="margin-top:12px;">
                            @csrf
                            <button type="submit" class="btn btn-danger" style="width:100%; font-size:13px;" onclick="event.stopPropagation()">❌ Remove from Favourites</button>
                        </form>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
        <div style="margin-top:32px;">{{ $favourites->links() }}</div>
    @endif
</div>
@endsection