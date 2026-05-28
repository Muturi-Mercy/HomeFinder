<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property - HomeFinder</title>
    <link rel="stylesheet" href="{{ asset('css/homefinder.css') }}">
    <style>
        .form-group{margin-bottom:20px;}
        .form-group label{display:block;font-size:13px;font-weight:600;margin-bottom:6px;}
        .form-control{width:100%;padding:11px 14px;border:1px solid var(--border);border-radius:8px;font-size:14px;outline:none;}
        .form-control:focus{border-color:var(--primary);}
        textarea.form-control{resize:vertical;min-height:120px;}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
        .form-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;}
        .section-title{font-size:16px;font-weight:700;margin-bottom:16px;padding-bottom:8px;border-bottom:2px solid var(--border);color:var(--primary);}
        .amenity-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:10px;}
        .amenity-item{display:flex;align-items:center;gap:8px;padding:10px;border:1px solid var(--border);border-radius:8px;cursor:pointer;font-size:13px;}
    </style>
</head>
<body>
<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand">Home<span>Finder</span></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('landlord.dashboard') }}"><span class="icon">📊</span> Dashboard</a></li>
            <li><a href="{{ route('landlord.properties') }}" class="active"><span class="icon">🏠</span> My Listings</a></li>
            <li><a href="{{ route('landlord.properties.create') }}"><span class="icon">➕</span> Add Property</a></li>
            <li><a href="{{ route('landlord.bookings') }}"><span class="icon">📅</span> Bookings</a></li>
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
            <h1 style="font-size:24px; font-weight:700;">✏️ Edit Property</h1>
            <p style="color:var(--gray); font-size:14px;">Update your property details. It will be re-submitted for admin review.</p>
        </div>

        @if($errors->any())
            <div style="background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; padding:14px; border-radius:8px; margin-bottom:16px;">
                <ul style="margin:0; padding-left:20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('landlord.properties.update', $property) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="background:white; border-radius:10px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
                <div class="section-title">📋 Basic Information</div>
                <div class="form-group">
                    <label>Property Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $property->title) }}" required>
                </div>
                <div class="form-group">
                    <label>Description *</label>
                    <textarea name="description" class="form-control" required>{{ old('description', $property->description) }}</textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Location *</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $property->location) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Monthly Rent (KSh) *</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $property->price) }}" required>
                    </div>
                </div>
                <div class="form-row-3">
                    <div class="form-group">
                        <label>Property Type *</label>
                        <select name="property_type" class="form-control" required>
                            <option value="bedsitter" {{ $property->property_type === 'bedsitter' ? 'selected' : '' }}>Bedsitter</option>
                            <option value="studio" {{ $property->property_type === 'studio' ? 'selected' : '' }}>Studio</option>
                            <option value="1_bedroom" {{ $property->property_type === '1_bedroom' ? 'selected' : '' }}>1 Bedroom</option>
                            <option value="2_bedroom" {{ $property->property_type === '2_bedroom' ? 'selected' : '' }}>2 Bedrooms</option>
                            <option value="3_bedroom" {{ $property->property_type === '3_bedroom' ? 'selected' : '' }}>3 Bedrooms</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Bedrooms *</label>
                        <input type="number" name="bedrooms" class="form-control" min="1" value="{{ old('bedrooms', $property->bedrooms) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Bathrooms *</label>
                        <input type="number" name="bathrooms" class="form-control" min="1" value="{{ old('bathrooms', $property->bathrooms) }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:400;">
                        <input type="checkbox" name="is_furnished" style="accent-color:var(--primary); width:16px; height:16px;" {{ $property->is_furnished ? 'checked' : '' }}>
                        This property is furnished
                    </label>
                </div>
            </div>

            <div style="background:white; border-radius:10px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
                <div class="section-title">📍 Location Coordinates</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $property->latitude) }}" placeholder="e.g. -1.3978">
                    </div>
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $property->longitude) }}" placeholder="e.g. 36.7565">
                    </div>
                </div>
            </div>

            <div style="background:white; border-radius:10px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
                <div class="section-title">✅ Amenities</div>
                <div class="amenity-grid">
                    @foreach($amenities as $amenity)
                    <label class="amenity-item">
                        <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                            {{ in_array($amenity->id, $selectedAmenities) ? 'checked' : '' }}>
                        {{ $amenity->icon }} {{ $amenity->name }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="background:white; border-radius:10px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
                <div class="section-title">📸 Cover Image</div>
                @if($property->cover_image)
                    <img src="{{ asset('storage/'.$property->cover_image) }}" style="width:200px; height:130px; object-fit:cover; border-radius:8px; margin-bottom:12px; display:block;">
                    <p style="font-size:13px; color:var(--gray); margin-bottom:12px;">Current cover image. Upload a new one to replace it.</p>
                @endif
                <input type="file" name="cover_image" class="form-control" accept="image/*">
            </div>

            <div style="display:flex; gap:16px;">
                <button type="submit" class="btn btn-primary" style="padding:14px 36px;">💾 Save Changes</button>
                <a href="{{ route('landlord.properties') }}" class="btn btn-outline" style="padding:14px 36px;">Cancel</a>
            </div>
        </form>
    </main>
</div>
</body>
</html>