<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - HomeFinder</title>
    <link rel="stylesheet" href="{{ asset('css/homefinder.css') }}">
</head>
<body>
<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand">Home<span>Finder</span></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('landlord.dashboard') }}"><span class="icon">📊</span> Dashboard</a></li>
            <li><a href="{{ route('landlord.properties') }}"><span class="icon">🏠</span> My Listings</a></li>
            <li><a href="{{ route('landlord.properties.create') }}"><span class="icon">➕</span> Add Property</a></li>
            <li><a href="{{ route('landlord.bookings') }}"><span class="icon">📅</span> Bookings</a></li>
            <li><a href="{{ route('landlord.messages') }}" class="active"><span class="icon">💬</span> Messages
                @if($unreadCount > 0)
                    <span style="background:#ef4444; color:white; font-size:11px; padding:2px 7px; border-radius:20px; margin-left:4px;">{{ $unreadCount }}</span>
                @endif
            </a></li>
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
            <h1 style="font-size:24px; font-weight:700;">💬 Messages</h1>
            <p style="color:var(--gray); font-size:14px;">Conversations with tenants about your properties.</p>
        </div>

        @if($conversations->count() === 0)
            <div style="text-align:center; padding:80px; background:white; border-radius:10px; box-shadow:var(--shadow);">
                <div style="font-size:48px; margin-bottom:16px;">💬</div>
                <h3>No messages yet</h3>
                <p style="color:var(--gray); margin-top:8px;">When tenants message you about your properties, they will appear here.</p>
            </div>
        @else
            <div style="background:white; border-radius:10px; box-shadow:var(--shadow); overflow:hidden;">
                @foreach($conversations as $message)
                <a href="{{ route('landlord.conversation', $message->property) }}?user_id={{ $message->user_id }}"
                    style="display:flex; align-items:center; gap:16px; padding:20px 24px; border-bottom:1px solid var(--border); text-decoration:none; color:inherit; transition:background 0.2s;"
                    onmouseover="this.style.background='#f8f9fa'"
                    onmouseout="this.style.background='white'">

                    <!-- TENANT AVATAR -->
                    <div style="width:48px; height:48px; background:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:18px; flex-shrink:0;">
                        {{ strtoupper(substr($message->user->name ?? 'T', 0, 1)) }}
                    </div>

                    <!-- INFO -->
                    <div style="flex:1; min-width:0;">
                        <div style="font-weight:600; font-size:15px; margin-bottom:2px;">
                            {{ $message->user->name ?? 'Tenant' }}
                        </div>
                        <div style="font-size:13px; color:var(--primary); margin-bottom:4px;">
                            🏠 {{ $message->property->title ?? 'Property' }}
                        </div>
                        <div style="font-size:13px; color:var(--gray); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $message->sender === 'landlord' ? 'You: ' : '' }}{{ Str::limit($message->message, 60) }}
                        </div>
                    </div>

                    <!-- TIME & UNREAD -->
                    <div style="text-align:right; flex-shrink:0;">
                        <div style="font-size:12px; color:var(--gray);">
                            {{ $message->created_at->diffForHumans() }}
                        </div>
                        @if(!$message->is_read && $message->sender === 'user')
                            <span style="display:inline-block; width:10px; height:10px; background:var(--primary); border-radius:50%; margin-top:6px;"></span>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </main>
</div>
</body>
</html>