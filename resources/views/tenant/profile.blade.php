@extends('layouts.app')
@section('title', 'My Profile - HomeFinder')

@section('content')
<div style="max-width:700px; margin:0 auto; padding:40px 24px;">
    <h1 style="font-size:26px; font-weight:700; margin-bottom:8px;"><i class="fa-solid fa-user" style="color:#1e7a5a"></i> My Profile</h1>
    <p style="color:var(--gray); margin-bottom:32px;">Manage your account details.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div style="background:white; border-radius:10px; padding:32px; box-shadow:var(--shadow);">

        <!-- AVATAR -->
        <div style="text-align:center; margin-bottom:28px;">
            <div style="width:80px; height:80px; background:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:32px; margin:0 auto 12px;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <h3 style="font-size:18px; font-weight:700;">{{ Auth::user()->name }}</h3>
            <p style="color:var(--gray); font-size:14px;">Tenant Account</p>
        </div>

        <!-- PROFILE INFO -->
        <div style="border-top:1px solid var(--border); padding-top:24px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label style="font-size:12px; font-weight:600; color:var(--gray); text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:6px;">Full Name</label>
                    <p style="font-size:15px; font-weight:500;">{{ Auth::user()->name }}</p>
                </div>
                <div>
                    <label style="font-size:12px; font-weight:600; color:var(--gray); text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:6px;">Email Address</label>
                    <p style="font-size:15px; font-weight:500;">{{ Auth::user()->email }}</p>
                </div>
                <div>
                    <label style="font-size:12px; font-weight:600; color:var(--gray); text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:6px;">Phone Number</label>
                    <p style="font-size:15px; font-weight:500;">{{ Auth::user()->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <label style="font-size:12px; font-weight:600; color:var(--gray); text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:6px;">Member Since</label>
                    <p style="font-size:15px; font-weight:500;">{{ Auth::user()->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <!-- QUICK STATS -->
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; background:var(--background); border-radius:10px; padding:20px; margin-bottom:24px;">
                <div style="text-align:center;">
                    <div style="font-size:24px; font-weight:700; color:var(--primary);">{{ \App\Models\Favourite::where('user_id', Auth::id())->count() }}</div>
                    <div style="font-size:13px; color:var(--gray);">Favourites</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:24px; font-weight:700; color:var(--accent);">{{ \App\Models\Booking::where('user_id', Auth::id())->count() }}</div>
                    <div style="font-size:13px; color:var(--gray);">Bookings</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:24px; font-weight:700; color:#d97706;">{{ \App\Models\Booking::where('user_id', Auth::id())->where('status','confirmed')->count() }}</div>
                    <div style="font-size:13px; color:var(--gray);">Confirmed</div>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
           
            <h4 style="font-size:15px; font-weight:700; margin-bottom:16px; padding-top:16px; border-top:1px solid var(--border);">Update Profile</h4>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:16px;">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('tenant.profile.update') }}">
                @csrf
                @method('PUT')
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div>
                        <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Full Name</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}"
                            style="width:100%; padding:10px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; outline:none;">
                    </div>
                    <div>
                        <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Phone Number</label>
                        <input type="text" name="phone" value="{{ Auth::user()->phone }}"
                            style="width:100%; padding:10px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; outline:none;">
                    </div>
                </div>
                <div style="margin-bottom:16px;">
                    <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">New Password <span style="font-weight:400; color:var(--gray);">(leave blank to keep current)</span></label>
                    <input type="password" name="password"
                        style="width:100%; padding:10px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; outline:none;"
                        placeholder="Enter new password">
                </div>
                <div style="display:flex; gap:12px;">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload" style="color: rgba(245, 158, 11, 1);"></i> Update</button>
                    <a href="/favourites" class="btn btn-outline"><i class="fa-solid fa-heart" style="color: rgb(230, 57, 70);margin-right:6px;"></i> Favourites</a>
                    <a href="/bookings" class="btn btn-outline"><i class="fa-solid fa-calendar-days"; style="color:#2563EB"></i>  Bookings</a>
                    <form method="POST" action="/logout" style="margin-left:auto;">
                        @csrf
                        <button type="submit" class="btn" style="background:#dc2626; color:white;"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection