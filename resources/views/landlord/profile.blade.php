<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - HomeFinder</title>
    <link rel="stylesheet" href="{{ asset('css/homefinder.css') }}">
     <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="shortcut icon" href="{{ asset('img/logohf.png') }}">
</head>
<body>
<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand">Home<span>Finder</span></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('landlord.dashboard') }}"><span class="icon"><i class="fa-solid fa-house" style="color: #1E7A5A; margin-right:6px;"></i></span> Dashboard</a></li>
            <li><a href="{{ route('landlord.properties') }}"><span class="icon"><i class="fa-solid fa-building-user" style="color: #1E7A5A; margin-right:6px;"></i></span> My Listings</a></li>
            <li><a href="{{ route('landlord.properties.create') }}"><span class="icon"><i class="fa-solid fa-circle-plus"style="color: #1E7A5A; margin-right:6px;"></i></span> Add Property</a></li>
            <li><a href="{{ route('landlord.bookings') }}"><span class="icon"><i class="fa-solid fa-calendar-days"; style="color:#1e7a5a; margin-right:6px;"></i> </span> Bookings</a></li>
            <li><a href="{{ route('landlord.messages') }}" ><span class="icon"><i class="fa-solid fa-comments" style="color:#1e7a5a; margin-right:6px;"></i></span> Messages
            <li><a href="{{ route('landlord.profile') }}"class="active"><span class="icon"><i class="fa-solid fa-user" style="color:#1e7a5a"></i></span> Profile</a></li>
        </ul>
        <div style="padding:24px;">
            <form method="POST" action="{{ route('landlord.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline" style="width:100%"><i class="fa-solid fa-arrow-right-from-bracket" style="margin-right: 6px;"></i>  Logout</button>
            </form>
        </div>
    </aside>

    <main class="dashboard-content">
        <div style="margin-bottom:24px;">
            <h1 style="font-size:24px; font-weight:700;"><i class="fa-solid fa-user" style="color:#1e7a5a"></i>  My Profile</h1>
            <p style="color:var(--gray); font-size:14px;">Manage your landlord account details.</p>
        </div>

        @if(session('success'))
            <div style="background:#d1fae5; color:#065f46; border:1px solid #6ee7b7; padding:14px; border-radius:8px; margin-bottom:20px;">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div style="background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; padding:14px; border-radius:8px; margin-bottom:20px;">
                <ul style="margin:0; padding-left:20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div style="display:grid; grid-template-columns:280px 1fr; gap:24px;">

            <!-- LEFT — PROFILE CARD -->
            <div>
                <div style="background:white; border-radius:12px; padding:28px; box-shadow:var(--shadow); text-align:center;">
                    <div style="width:80px; height:80px; background:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:32px; margin:0 auto 16px;">
                        {{ strtoupper(substr(Auth::guard('landlord')->user()->name, 0, 1)) }}
                    </div>
                    <h3 style="font-size:18px; font-weight:700;">{{ Auth::guard('landlord')->user()->name }}</h3>
                    <p style="color:var(--gray); font-size:13px; margin-top:4px;">Landlord Account</p>

                    @if(Auth::guard('landlord')->user()->is_verified)
                        <span style="display:inline-block; background:#d1fae5; color:#065f46; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:600; margin-top:10px;"><i class="fa-solid fa-award"></i> Verified Landlord</span>
                    @else
                        <span style="display:inline-block; background:#fef3c7; color:#92400e; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:600; margin-top:10px;"><i class="fa-solid fa-hourglass-half" style="color: rgba(245, 158, 11, 1);"></i> Pending Verification</span>
                    @endif

                    <div style="margin-top:20px; padding-top:20px; border-top:1px solid var(--border);">
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13px;">
                            <span style="color:var(--gray);">Total Listings</span>
                            <strong>{{ $totalProperties }}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13px;">
                            <span style="color:var(--gray);">Approved</span>
                            <strong style="color:var(--primary);">{{ $approved }}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:13px;">
                            <span style="color:var(--gray);">Total Bookings</span>
                            <strong style="color:var(--accent);">{{ $totalBookings }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT — UPDATE FORM -->
            <div style="background:white; border-radius:12px; padding:28px; box-shadow:var(--shadow);">
                <h3 style="font-size:16px; font-weight:700; margin-bottom:20px; padding-bottom:12px; border-bottom:1px solid var(--border);">Update Profile</h3>

                <form method="POST" action="{{ route('landlord.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div>
                            <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Full Name</label>
                            <input type="text" name="name"
                                value="{{ Auth::guard('landlord')->user()->name }}"
                                style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; outline:none;"
                                required>
                        </div>
                        <div>
                            <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Phone Number</label>
                            <input type="text" name="phone"
                                value="{{ Auth::guard('landlord')->user()->phone }}"
                                style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; outline:none;">
                        </div>
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Email Address</label>
                        <input type="email" name="email"
                            value="{{ Auth::guard('landlord')->user()->email }}"
                            style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; outline:none;"
                            required>
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">ID Number</label>
                        <input type="text" name="id_number"
                            value="{{ Auth::guard('landlord')->user()->id_number }}"
                            style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; outline:none;"
                            placeholder="National ID number">
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
                        <div>
                            <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">New Password <span style="font-weight:400; color:var(--gray);">(optional)</span></label>
                            <input type="password" name="password"
                                style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; outline:none;"
                                placeholder="Leave blank to keep current">
                        </div>
                        <div>
                            <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                style="width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; outline:none;"
                                placeholder="Repeat new password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="padding:12px 32px;"><i class="fa-solid fa-upload" style="color: rgba(245, 158, 11, 1);"></i> Save Changes</button>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>