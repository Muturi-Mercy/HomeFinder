<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - HomeFinder</title>
    <link rel="stylesheet" href="{{ asset('css/homefinder.css') }}">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px; }
        .auth-card { background:white; border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,0.4); width:100%; max-width:420px; padding:40px; }
        .auth-logo { text-align:center; margin-bottom:28px; }
        .auth-logo h1 { font-size:26px; font-weight:800; color:var(--primary); }
        .auth-logo h1 span { color:var(--text); }
        .auth-logo .admin-badge { display:inline-block; background:#1a1a2e; color:white; font-size:11px; font-weight:700; padding:4px 12px; border-radius:20px; margin-top:8px; letter-spacing:1px; text-transform:uppercase; }
        .form-group { margin-bottom:18px; }
        .form-group label { display:block; font-size:13px; font-weight:600; margin-bottom:6px; }
        .form-control { width:100%; padding:12px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; outline:none; transition:border-color 0.2s; font-family:inherit; }
        .form-control:focus { border-color:var(--primary); }
        .btn-block { width:100%; padding:13px; font-size:15px; margin-top:8px; }
        .secure-note { text-align:center; font-size:12px; color:var(--gray); margin-top:16px; }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="auth-logo">
        <img src="{{ asset('img/logohf.png') }}" alt="HomeFinder Logo" class="logo"> 
        <h1> Home<span>Finder</span></h1>
        <div class="admin-badge"><i class="fa-solid fa-lock"  style="color: rgba(245, 158, 11, 1)"></i> Admin Panel</div>
    </div>

    <div style="font-size:20px; font-weight:700; margin-bottom:6px;">Admin Sign In</div>
    <p style="color:var(--gray); font-size:14px; margin-bottom:24px;">Restricted access — authorized personnel only</p>

    @if(session('error'))
        <div class="alert alert-error" style="margin-bottom:16px;">{{ session('error') }}</div>
    @endif

    <form method="POST" action="/admin/login">
        @csrf
        <div class="form-group">
            <label>Admin Email</label>
            <input type="email" name="email" class="form-control" placeholder="admin@homefinder.co.ke" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter admin password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Sign In <i class="fa-solid fa-arrow-right-from-bracket"></i></button>
    </form>

    <div class="secure-note"><i class="fa-solid fa-lock"  style="color: rgba(245, 158, 11, 1)"></i> This is a secure admin area. All actions are logged.</div>
</div>
</body>
</html>