@extends('layouts.app')
@section('title', $property->title . ' - HomeFinder')

@section('content')
<div style="max-width:1100px; margin:0 auto; padding:32px 24px;">

    <!-- BACK BUTTON -->
    <a href="/browse" style="display:inline-flex; align-items:center; gap:6px; color:var(--gray); font-size:18px; margin-bottom:20px;"><i class="fa-solid fa-angles-left"; style="color:#1e7a5a"></i>Back to Search</a>

    <div style="display:grid; grid-template-columns:1fr 340px; gap:28px;">

        <!-- LEFT COLUMN -->
        <div>
            <!-- MAIN IMAGE -->
            <div style="border-radius:12px; overflow:hidden; margin-bottom:16px;">
                @if($property->cover_image)
                    <img src="{{ asset('storage/'.$property->cover_image) }}" alt="{{ $property->title }}" style="width:100%; height:420px; object-fit:cover;">
                @else
                    <div style="width:100%; height:420px; background:var(--background); display:flex; align-items:center; justify-content:center; font-size:64px;"><img src="{{ asset('img/logohf.png') }}" alt="HomeFinder Logo" class="logo"></div>
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
                            <p style="color:var(--gray); font-size:15px;"><i class="fa-solid fa-map-pin" style="color: rgba(230, 57, 70, 1);"></i> {{ $property->location }}</p>
                            {{-- Rating summary --}}
                            @php
                                $avgRating   = $property->averageRating();
                                $reviewCount = $property->reviewCount();
                            @endphp
                            @if($reviewCount > 0)
                                <div style="display:flex; align-items:center; gap:8px; margin-top:8px;">
                                    <div style="display:flex; gap:2px;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span style="font-size:18px; color:{{ $i <= round($avgRating) ? '#f59e0b' : '#d1d5db' }};"><i class="fa-solid fa-star" style="color: rgba(245, 158, 11, 1);"></i></span>
                                        @endfor
                                    </div>
                                    <span style="font-size:16px; font-weight:700; color:#f59e0b;">{{ round($avgRating, 1) }}</span>
                                    <span style="font-size:13px; color:var(--gray);">({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
                                </div>
                            @endif
                    </div>
                    <span style="background:#d1fae5; color:#065f46; padding:4px 14px; border-radius:20px; font-size:13px; font-weight:600;"><i class="fa-solid fa-award"></i> Verified</span>
                </div>

                <div style="font-size:28px; font-weight:700; color:var(--primary); margin-bottom:20px;">
                    KSh {{ number_format($property->price) }}<span style="font-size:16px; font-weight:400; color:var(--gray);"> / month</span>
                </div>

                <!-- FEATURES -->
                <div style="display:flex; gap:24px; padding:20px; background:var(--background); border-radius:10px; margin-bottom:24px; flex-wrap:wrap;">
                    <div style="text-align:center;">
                        <div style="font-size:24px;"><i class="fa-solid fa-bed" style="color: rgba(37, 99, 235, 1);"></i></div>
                        <div style="font-size:16px; font-weight:700;">{{ $property->bedrooms }}</div>
                        <div style="font-size:12px; color:var(--gray);">Bedrooms</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:24px;"><i class="fa-solid fa-bath" style="color: rgba(14, 165, 233, 1);"></i></div>
                        <div style="font-size:16px; font-weight:700;">{{ $property->bathrooms }}</div>
                        <div style="font-size:12px; color:var(--gray);">Bathrooms</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:24px;"><i class="fa-solid fa-house" style="color: #1e7a5a"></i></div>
                        <div style="font-size:16px; font-weight:700;">{{ str_replace('_',' ', ucfirst($property->property_type)) }}</div>
                        <div style="font-size:12px; color:var(--gray);">Type</div>
                    </div>
                    @if($property->is_furnished)
                    <div style="text-align:center;">
                        <div style="font-size:24px;"><i class="fa-solid fa-couch" style="color: rgba(168,85,247,1);"></i></div>
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
                            <span style="
                                background:{{ $amenity->color }}15;
                                border:1px solid {{ $amenity->color }}40;
                                padding:8px 16px;
                                border-radius:20px;
                                font-size:13px;
                                font-weight:500;
                                display:inline-flex;
                                align-items:center;
                                gap:8px;">
                                <i class="{{ $amenity->icon }}" style="color:{{ $amenity->color }}; font-size:14px;"></i>
                                {{ $amenity->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- MAP SECTION -->
        @if($property->latitude && $property->longitude)
        <div style="background:white; border-radius:12px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
            <h3 style="font-size:17px; font-weight:700; margin-bottom:16px;"><i class="fa-solid fa-map-pin" style="color: rgba(230, 57, 70, 1);"></i> Property Location</h3>

            <!-- MAP CONTAINER -->
            <div id="propertyMap" style="height:350px; border-radius:10px; overflow:hidden; border:1px solid var(--border); z-index:1; position:relative;"></div>

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
                    <i class="fa-solid fa-map-pin" style="color: rgba(230, 57, 70, 1);"></i> Show Route From My Location
                </button>
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $property->latitude }},{{ $property->longitude }}"
                    target="_blank" class="btn btn-outline">
                    <i class="fa-solid fa-map-location-dot" style="margin-right:8px; color: #34a853;"></i> Open in Google Maps
                </a>
            </div>
        </div>
        @else
        <div style="background:white; border-radius:12px; padding:28px; box-shadow:var(--shadow); margin-bottom:24px;">
            <h3 style="font-size:17px; font-weight:700; margin-bottom:12px;"><i class="fa-solid fa-map-pin" style="color: rgba(230, 57, 70, 1);"></i> Property Location</h3>
            <p style="color:var(--gray); font-size:14px;"><i class="fa-solid fa-map-pin" style="color: rgba(230, 57, 70, 1);"></i> {{ $property->location }}</p>
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
                        <div style="font-size:12px; color:var(--gray);"><i class="fa-solid fa-award" style="color:#1e7a5a"></i> Verified Landlord</div>
                    </div>
                </div>
                <div style="font-size:13px; color:var(--gray); margin-bottom:16px;"><i class="fa-solid fa-envelope"  style="color: rgba(37, 99, 235, 1);"></i> {{ $property->landlord->phone }}</div>

                @auth
                    <!-- MESSAGE LANDLORD BUTTON -->
                        <a href="{{ route('tenant.conversation', $property) }}"
                            class="btn btn-primary"
                            style="width:100%; display:block; text-align:center; margin-bottom:10px;">
                            <i class="fa-solid fa-comments" ></i> Message Landlord
                        </a>
                @else
                        <a href="/login"
                            class="btn btn-primary"
                            style="width:100%; display:block; text-align:center; margin-bottom:10px;">
                            <i class="fa-solid fa-comments" ></i> Message Landlord
                        </a>
                @endauth

                @auth
                    <!-- FAVOURITE BUTTON -->
                    <form method="POST" action="{{ route('tenant.favourite.toggle', $property) }}" style="margin-bottom:10px;">
                        @csrf
                        <button type="submit" class="btn {{ $isFavourited ? 'btn-primary' : 'btn-outline' }}" style="width:100%;">
                            {{ $isFavourited ? ' Saved' : 'Save Property' }}
                        </button>
                    </form>
                @else
                    <a href="/login" class="btn btn-outline" style="width:100%; display:block; text-align:center; margin-bottom:10px;"><i class="fa-solid fa-heart" style="color: rgba(230, 57, 70, 1);"></i> Save Property</a>
                @endauth
            </div>

            <!-- BOOK VIEWING -->
            <div style="background:white; border-radius:12px; padding:24px; box-shadow:var(--shadow);">
                <h3 style="font-size:16px; font-weight:700; margin-bottom:16px;"><i class="fa-solid fa-calendar-days"; style="color:#1e7a5a"></i> Book a Viewing</h3>

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
                    <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fa-solid fa-calendar-days"></i>  Schedule Viewing</button>
                </form>
                @else
                    <p style="color:var(--gray); font-size:14px; text-align:center; margin-bottom:16px;">Please login to book a viewing.</p>
                    <a href="/login" class="btn btn-primary" style="width:100%; display:block; text-align:center;">Login to Book</a>
                @endauth
            </div>

            
          <!-- REPORT LISTING -->
            @auth
                <div style="margin-top:20px; background:#fff8f8; border:1px solid #fecaca; border-radius:10px; padding:16px;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <p style="font-size:13px; font-weight:600; color:#991b1b; margin-bottom:2px;"><i class="fa-solid fa-flag" style="color: rgba(230, 57, 70, 1);"></i> Report this Listing</p>
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
                                    <option value="Fake Listing">Fake Listing</option>
                                    <option value="Wrong Information">Wrong Information</option>
                                    <option value="Already Rented">Already Rented</option>
                                    <option value="Scam/Fraud">Scam / Fraud</option>
                                    <option value="Duplicate Listing">Duplicate Listing</option>
                                    <option value="Misleading Photos">Misleading Photos</option>
                                    <option value="Other">Other</option>
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
                                     Submit Report
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

    <!-- REVIEWS SECTION -->
    <div style="margin-top:40px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <div>
                <h2 style="font-size:22px; font-weight:700;">
                    <i class="fa-solid fa-star" style="color: rgba(245, 158, 11, 1)"></i> Reviews
                    <span style="font-size:16px; font-weight:400; color:var(--gray);">({{ $property->reviewCount() }})</span>
                </h2>
                @if($property->reviewCount() > 0)
                    <div style="display:flex; align-items:center; gap:8px; margin-top:6px;">
                        @php $avg = round($property->averageRating(), 1); @endphp
                        <div style="display:flex; gap:2px;">
                            @for($i = 1; $i <= 5; $i++)
                                <span style="font-size:20px; color:{{ $i <= round($avg) ? '#f59e0b' : '#d1d5db' }};"><i class="fa-solid fa-star"></i></span>
                            @endfor
                        </div>
                        <span style="font-size:18px; font-weight:700; color:#f59e0b;">{{ $avg }}</span>
                        <span style="color:var(--gray); font-size:14px;">out of 5</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- WRITE A REVIEW --}}
        @auth
            @php
                $hasBooked = \App\Models\Booking::where('user_id', Auth::id())
                    ->where('property_id', $property->id)->exists();
                $hasReviewed = \App\Models\Review::where('user_id', Auth::id())
                    ->where('property_id', $property->id)->exists();
            @endphp

            @if($hasBooked && !$hasReviewed)
                <div style="background:white; border-radius:12px; padding:28px; box-shadow:var(--shadow); margin-bottom:28px; border-left:4px solid var(--primary);">
                    <h3 style="font-size:17px; font-weight:700; margin-bottom:6px;"> Write a Review</h3>
                    <p style="font-size:13px; color:var(--gray); margin-bottom:20px;">Share your experience about this property.</p>

                    @if(session('error'))
                        <div style="background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; font-size:13px; margin-bottom:16px;">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('tenant.review.store', $property) }}">
                        @csrf

                        {{-- STAR RATING --}}
                        <div style="margin-bottom:20px;">
                            <label style="font-size:13px; font-weight:600; display:block; margin-bottom:10px;">Your Rating *</label>
                            <div class="star-rating" style="display:flex; gap:6px;">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}"
                                        style="display:none;"
                                        {{ old('rating') == $i ? 'checked' : '' }}>
                                    <label for="star{{ $i }}"
                                        style="font-size:36px; cursor:pointer; color:#d1d5db; transition:color 0.1s;"
                                        onmouseover="highlightStars({{ $i }})"
                                        onmouseout="resetStars()"
                                        onclick="selectStar({{ $i }})"><i class="fa-solid fa-star" style="color: rgba(245, 158, 11, 1);"></i></label>
                                @endfor
                            </div>
                            <p style="font-size:12px; color:var(--gray); margin-top:6px;" id="ratingText">Click a star to rate</p>
                        </div>

                        {{-- COMMENT --}}
                        <div style="margin-bottom:20px;">
                            <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Your Review <span style="font-weight:400; color:var(--gray);">(optional)</span></label>
                            <textarea name="comment"
                                placeholder="Describe your experience — location, condition, landlord responsiveness, value for money..."
                                style="width:100%; padding:12px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; outline:none; resize:vertical; min-height:100px; font-family:inherit; transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='var(--primary)'"
                                onblur="this.style.borderColor='var(--border)'">{{ old('comment') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="padding:12px 28px;">
                            <i class="fa-solid fa-star" style="color: rgba(245, 158, 11, 1)"></i> Submit Review
                        </button>
                    </form>
                </div>

            @elseif($hasReviewed)
                <div style="background:#f0faf5; border-radius:10px; padding:16px 20px; margin-bottom:28px; font-size:14px; color:var(--primary); border:1px solid #6ee7b7;">
                     You have already reviewed this property.
                </div>

            @elseif(!$hasBooked)
                <div style="background:#fef3c7; border-radius:10px; padding:16px 20px; margin-bottom:28px; font-size:14px; color:#92400e; border:1px solid #fcd34d;">
                     You need to <strong>book a viewing</strong> for this property before you can leave a review.
                </div>
            @endif
        @else
            <div style="background:var(--background); border-radius:10px; padding:16px 20px; margin-bottom:28px; font-size:14px; color:var(--gray); border:1px solid var(--border);">
                <a href="/login" style="color:var(--primary); font-weight:600;">Login</a> to leave a review.
            </div>
        @endauth

        {{-- DISPLAY REVIEWS --}}
        @php $reviews = $property->reviews()->with('user')->latest()->get(); @endphp

        @if($reviews->count() === 0)
            <div style="text-align:center; padding:48px; background:white; border-radius:12px; box-shadow:var(--shadow);">
                <div style="font-size:40px; margin-bottom:12px;"><i class="fa-solid fa-star" style="color: rgba(245, 158, 11, 1)"></i></div>
                <h3 style="font-size:17px; font-weight:600;">No reviews yet</h3>
                <p style="color:var(--gray); font-size:14px; margin-top:6px;">Be the first to review this property.</p>
            </div>
        @else
            <div style="display:flex; flex-direction:column; gap:16px;">
                @foreach($reviews as $review)
                <div style="background:white; border-radius:12px; padding:24px; box-shadow:var(--shadow);">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            {{-- AVATAR --}}
                            <div style="width:44px; height:44px; background:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:18px; flex-shrink:0;">
                                {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600; font-size:15px;">{{ $review->user->name ?? 'Anonymous' }}</div>
                                <div style="font-size:12px; color:var(--gray);">{{ $review->created_at->format('d M Y') }}</div>
                            </div>
                        </div>

                        <div style="display:flex; align-items:center; gap:8px;">
                            {{-- STARS --}}
                            <div style="display:flex; gap:2px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <span style="font-size:18px; color:{{ $i <= $review->rating ? '#f59e0b' : '#d1d5db' }};"><i class="fa-solid fa-star" style="color: rgba(245, 158, 11, 1);"></i></span>
                                @endfor
                            </div>
                            <span style="font-size:14px; font-weight:700; color:#f59e0b;">{{ $review->rating }}/5</span>

                            {{-- DELETE OWN REVIEW --}}
                            @auth
                                @if(Auth::id() === $review->user_id)
                                    <form method="POST" action="{{ route('tenant.review.destroy', $review) }}"
                                        onsubmit="return confirm('Delete your review?')"
                                        style="margin-left:8px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            style="background:none; border:none; color:#9ca3af; cursor:pointer; font-size:13px; padding:2px 6px; border-radius:4px;"
                                            onmouseover="this.style.color='#ef4444'"
                                            onmouseout="this.style.color='#9ca3af'">
                                            <i class="fa-regular fa-trash-can"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>

                    {{-- COMMENT --}}
                    @if($review->comment)
                        <p style="color:var(--text); font-size:14px; line-height:1.7; margin:0;">{{ $review->comment }}</p>
                    @else
                        <p style="color:var(--gray); font-size:13px; font-style:italic;">No written review.</p>
                    @endif
                </div>
                @endforeach
            </div>
        @endif
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
                    <div style="width:100%; height:200px; background:var(--background); display:flex; align-items:center; justify-content:center; font-size:48px;"><i class="fa-solid fa-house"></i></div>
                @endif
                <div class="property-card-body">
                    <div class="property-title">{{ $related->title }}</div>
                    <div class="property-location"><i class="fa-solid fa-map-pin" style="color: rgba(230, 57, 70, 1);"></i> {{ $related->location }}</div>
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
    html: '<div style=color:#2563EB; padding:8px 12px; border-radius:8px; font-size:13px; font-weight:600; white-space:nowrap; box-shadow:0 2px 8px rgba(0,0,0,0.3);"><i class="fa-solid fa-house"></i>' + propName + '</div>',
    className: '',
    iconAnchor: [0, 0]
});

// Add property marker
const propertyMarker = L.marker([propLat, propLng], {icon: propertyIcon})
    .addTo(map)
    .bindPopup('<strong>' + propName + '</strong><br><i class="fa-solid fa-map-pin" style="color: rgba(230, 57, 70, 1);"></i> {{ addslashes($property->location) }}<br><i class="fa-solid fa-coins" style="color:#34a853";></i> KSh {{ number_format($property->price) }}/month')
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
    btn.textContent = ' Getting your location...';
    btn.disabled = true;

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            // Remove old user marker
            if (userMarker) map.removeLayer(userMarker);

            // Add user marker
            const userIcon = L.divIcon({
                html: '<div color:#2563EB; padding:6px 10px; border-radius:8px; font-size:12px; font-weight:600; box-shadow:0 2px 8px rgba(0,0,0,0.3);"><i class="fa-solid fa-location-pin" style="color: rgb(230, 57, 70);"></i></div>',
                className: '',
                iconAnchor: [0, 0]
            });

            userMarker = L.marker([userLat, userLng], {icon: userIcon})
                .addTo(map)
                .bindPopup('<i class="fa-solid fa-map-pin" style="color: rgba(230, 57, 70, 1);"></i> Your Location')
                .openPopup();

            // Fit map to show both markers
            const bounds = L.latLngBounds(
                [userLat, userLng],
                [propLat, propLng]
            );
            map.fitBounds(bounds, {padding: [50, 50]});

            // Get route
            getRoute(userLat, userLng, propLat, propLng);

            btn.textContent = ' Update My Location';
            btn.disabled = false;
        },
        function(error) {
            alert('Could not get your location. Please allow location access.');
            btn.textContent = '<i class="fa-solid fa-map-pin" style="color: rgba(230, 57, 70, 1);"></i> Show Route From My Location';
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

// Star rating interaction
let selectedRating = {{ old('rating', 0) }};

function highlightStars(rating) {
    const labels = document.querySelectorAll('.star-rating label');
    labels.forEach((label, index) => {
        const starValue = 5 - index;
        label.style.color = starValue <= rating ? '#f59e0b' : '#d1d5db';
    });
    const texts = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
    const ratingText = document.getElementById('ratingText');
    if (ratingText) ratingText.textContent = texts[rating] + ' (' + rating + '/5)';
}

function resetStars() {
    highlightStars(selectedRating);
    const texts = ['Click a star to rate', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
    const ratingText = document.getElementById('ratingText');
    if (ratingText) ratingText.textContent = selectedRating ? texts[selectedRating] + ' (' + selectedRating + '/5)' : 'Click a star to rate';
}

function selectStar(rating) {
    selectedRating = rating;
    document.getElementById('star' + rating).checked = true;
    highlightStars(rating);
}

// Initialize stars if old value exists
if (selectedRating > 0) highlightStars(selectedRating);

</script>
@endsection