<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HomeFinder</title>
    <link rel="stylesheet" href="{{ asset('css/homefinder.css') }}">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <style>
        body { background: linear-gradient(135deg, #f0faf5 0%, #e8f5e9 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px; }
        .auth-card { background:white; border-radius:16px; box-shadow:0 10px 40px rgba(0,0,0,0.12); width:100%; max-width:440px; padding:40px; }
        .auth-logo { text-align:center; margin-bottom:28px; }
        .auth-logo h1 { font-size:28px; font-weight:800; color:var(--primary); }
        .auth-logo h1 span { color:var(--text); }
        .auth-logo p { color:var(--gray); font-size:14px; margin-top:4px; }
        .auth-title { font-size:22px; font-weight:700; margin-bottom:6px; }
        .auth-subtitle { color:var(--gray); font-size:14px; margin-bottom:28px; }
        .form-group { margin-bottom:18px; }
        .form-group label { display:block; font-size:13px; font-weight:600; margin-bottom:6px; color:var(--text); }
        .form-control { width:100%; padding:12px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; outline:none; transition:border-color 0.2s; font-family:inherit; }
        .form-control:focus { border-color:var(--primary); }
        .btn-block { width:100%; padding:13px; font-size:15px; margin-top:8px; }
        .auth-divider { text-align:center; color:var(--gray); font-size:13px; margin:20px 0; position:relative; }
        .auth-divider::before { content:''; position:absolute; top:50%; left:0; right:0; height:1px; background:var(--border); }
        .auth-divider span { background:white; padding:0 12px; position:relative; }
        .auth-links { text-align:center; font-size:14px; color:var(--gray); margin-top:20px; }
        .auth-links a { color:var(--primary); font-weight:600; }
        .role-tabs { display:flex; gap:8px; margin-bottom:24px; }
        .role-tab { flex:1; padding:10px; border:2px solid var(--border); border-radius:8px; text-align:center; font-size:13px; font-weight:600; cursor:pointer; color:var(--gray); text-decoration:none; transition:all 0.2s; }
        .role-tab.active { border-color:var(--primary); color:var(--primary); background:#f0faf5; }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="auth-logo">
        <img src="{{ asset('img/logohf.png') }}" alt="HomeFinder Logo" class="logo"> 
        <h1>Home<span>Finder</span></h1>
        <p>Connecting Landlords & Tenants Seamlessly</p>
    </div>

    <div class="role-tabs">
        <a href="/login" class="role-tab active"><i class="fa-solid fa-house"></i>I'm a Tenant</a>
        <a href="/landlord/login" class="role-tab"><i class="fa-solid fa-building"></i> I'm a Landlord</a>
    </div>

    <div class="auth-title">Welcome Back!</div>
    <p class="auth-subtitle">Login to your tenant account</p>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:16px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" style="margin-bottom:16px;">{{ session('error') }}</div>
    @endif

    <form method="POST" action="/login">
        @csrf
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-group">
            <label style="display:flex; justify-content:space-between;">
                Password
                <a href="#" style="color:var(--primary); font-weight:400; font-size:13px;">Forgot password?</a>
            </label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
            <input type="checkbox" name="remember" id="remember" style="accent-color:var(--primary); width:16px; height:16px;">
            <label for="remember" style="font-size:13px; color:var(--gray); cursor:pointer;">Remember me</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login <i class="fa-solid fa-arrow-right-from-bracket"></i></button>
    </form>

    <div class="auth-links">
        Don't have an account? <a href="/register">Create one free</a>
    </div>
</div>
</body>
</html>