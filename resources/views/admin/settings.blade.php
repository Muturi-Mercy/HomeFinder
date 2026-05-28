@extends('layouts.admin')
@section('title', 'Settings - Admin')
@section('page-title', 'System Settings')

@section('content')
<div class="page-header">
    <h1>⚙️ System Settings</h1>
    <p>Configure HomeFinder platform settings.</p>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header"><h3>General Settings</h3></div>
        <div class="card-body">
            <div style="margin-bottom:16px;">
                <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Site Name</label>
                <input type="text" value="HomeFinder" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px; font-size:14px;">
            </div>
            <div style="margin-bottom:16px;">
                <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Site Tagline</label>
                <input type="text" value="Connecting Landlords & Tenants Seamlessly" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px; font-size:14px;">
            </div>
            <div style="margin-bottom:16px;">
                <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Admin Email</label>
                <input type="email" value="admin@homefinder.co.ke" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px; font-size:14px;">
            </div>
            <button class="btn btn-primary">Save Changes</button>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Contact Information</h3></div>
        <div class="card-body">
            <div style="margin-bottom:16px;">
                <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Contact Phone</label>
                <input type="text" value="+254 700 123 456" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px; font-size:14px;">
            </div>
            <div style="margin-bottom:16px;">
                <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Location</label>
                <input type="text" value="Ongata Rongai, Kajiado County" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px; font-size:14px;">
            </div>
            <button class="btn btn-primary">Save Changes</button>
        </div>
    </div>
</div>
@endsection