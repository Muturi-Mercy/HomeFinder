@extends('layouts.admin')
@section('title', 'Property Verification - Admin')
@section('page-title', 'Property Verification')

@section('content')
<div class="page-header">
    <h1>✅ Property Verification</h1>
    <p>Review and approve or reject property listings submitted by landlords.</p>
</div>

@if($properties->count() === 0)
    <div class="card">
        <div class="card-body" style="text-align:center; padding:60px;">
            <div style="font-size:48px; margin-bottom:16px;">✅</div>
            <h3>All caught up!</h3>
            <p style="color:var(--gray); margin-top:8px;">No properties pending verification.</p>
        </div>
    </div>
@else
    <div class="verification-grid">
        @foreach($properties as $property)
        <div class="verification-card">
            @if($property->cover_image)
                <img src="{{ asset('storage/'.$property->cover_image) }}" alt="{{ $property->title }}">
            @else
                <div style="width:100%; height:180px; background:var(--background); display:flex; align-items:center; justify-content:center; font-size:40px;">🏠</div>
            @endif
            <div class="verification-card-body">
                <h4>{{ $property->title }}</h4>
                <p>📍 {{ $property->location }}</p>
                <p>💰 KSh {{ number_format($property->price) }}/month</p>
                <p>👤 {{ $property->landlord->name ?? 'N/A' }}</p>
                <p style="font-size:12px; color:var(--gray);">🛏 {{ $property->bedrooms }} bed • 🚿 {{ $property->bathrooms }} bath • {{ str_replace('_',' ', ucfirst($property->property_type)) }}</p>
                <p style="font-size:12px; color:var(--gray); margin-top:6px;">{{ Str::limit($property->description, 80) }}</p>
                <div class="verification-actions">
                    <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                        @csrf
                        <button class="btn btn-success" style="width:100%;">✓ Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                        @csrf
                        <button class="btn btn-danger" style="width:100%;">✗ Reject</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div style="margin-top:24px;">{{ $properties->links() }}</div>
@endif
@endsection