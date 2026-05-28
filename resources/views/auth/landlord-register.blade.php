<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Register - HomeFinder</title>
    <link rel="stylesheet" href="{{ asset('css/homefinder.css') }}">
    <style>
        body { background: linear-gradient(135deg, #f0faf5 0%, #e8f5e9 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px; }
        .auth-card { background:white; border-radius:16px; box-shadow:0 10px 40px rgba(0,0,0,0.12); width:100%; max-width:480px; padding:40px; }
        .auth-logo { text-align:center; margin-bottom:24px; }
        .auth-logo h1 { font-size:28px; font-weight:800; color:var(--primary); }
        .auth-logo h1 span { color:var(--text); }
        .form-group { margin-bottom:16px; }
        .form-group label { display:block; font-size:13px; font-weight:600; margin-bottom:6px; }
        .form-control { width:100%; padding:12px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; outline:none; transition:border-color 0.2s; font-family:inherit; }
        .form-control:focus { border-color:var(--primary); }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .btn-block { width:100%; padding:13px; font-size:15px; margin-top:8px; }
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
        <h1>🏠 Home<span>Finder</span></h1>
        <p style="color:var(--gray); font-size:14px;">List your property for free</p>
    </div>

    <div class="role-tabs">
        <a href="/register" class="role-tab">🏠 I'm a Tenant</a>
        <a href="/landlord/register" class="role-tab active">🏢 I'm a Landlord</a>
    </div>

    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:16px;">
            <ul style="margin:0; padding-left:20px;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/landlord/register">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="John Doe" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" placeholder="07XX XXX XXX" value="{{ old('phone') }}" required>
            </div>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label>ID Number (for verification)</label>
            <input type="text" name="id_number" class="form-control" placeholder="National ID number" value="{{ old('id_number') }}">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Create Landlord Account →</button>
    </form>

    <div class="auth-links">
        Already have an account? <a href="/landlord/login">Login here</a>
    </div>
</div>
</body>
</html>