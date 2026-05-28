<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation - HomeFinder</title>
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
            <li><a href="{{ route('landlord.messages') }}" class="active"><span class="icon">💬</span> Messages</a></li>
        </ul>
        <div style="padding:24px;">
            <form method="POST" action="{{ route('landlord.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline" style="width:100%">🚪 Logout</button>
            </form>
        </div>
    </aside>

    <main class="dashboard-content">
        <a href="{{ route('landlord.messages') }}" style="display:inline-flex; align-items:center; gap:6px; color:var(--gray); font-size:14px; margin-bottom:20px;">← Back to Messages</a>

        <div style="background:white; border-radius:12px; box-shadow:var(--shadow); overflow:hidden;">

            <!-- HEADER -->
            <div style="padding:20px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:14px;">
                <div style="width:48px; height:48px; background:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:20px;">
                    {{ strtoupper(substr($tenant->name ?? 'T', 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:700; font-size:16px;">{{ $tenant->name ?? 'Tenant' }}</div>
                    <div style="font-size:13px; color:var(--gray);">
                        About: <strong>{{ $property->title }}</strong> •
                        📍 {{ $property->location }}
                    </div>
                </div>
            </div>

            <!-- MESSAGES -->
            <div style="padding:24px; min-height:400px; max-height:500px; overflow-y:auto; display:flex; flex-direction:column; gap:16px;" id="messageContainer">
                @if($messages->count() === 0)
                    <div style="text-align:center; color:var(--gray); padding:40px; font-size:14px;">
                        No messages yet.
                    </div>
                @else
                    @foreach($messages as $message)
                    <div style="display:flex; {{ $message->sender === 'landlord' ? 'justify-content:flex-end' : 'justify-content:flex-start' }};">
                        <div style="max-width:70%;">
                            <div style="
                                padding:12px 16px;
                                border-radius:{{ $message->sender === 'landlord' ? '18px 18px 4px 18px' : '18px 18px 18px 4px' }};
                                background:{{ $message->sender === 'landlord' ? 'var(--primary)' : 'var(--background)' }};
                                color:{{ $message->sender === 'landlord' ? 'white' : 'var(--text)' }};
                                font-size:14px;
                                line-height:1.6;
                            ">
                                {{ $message->message }}
                            </div>
                            <div style="font-size:11px; color:var(--gray); margin-top:4px; {{ $message->sender === 'landlord' ? 'text-align:right' : '' }}">
                                {{ $message->sender === 'landlord' ? 'You' : ($tenant->name ?? 'Tenant') }}
                                • {{ $message->created_at->format('d M, h:i A') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

            <!-- REPLY INPUT -->
            @if(session('success'))
                <div style="background:#d1fae5; color:#065f46; padding:10px 24px; font-size:13px;">{{ session('success') }}</div>
            @endif

            <div style="padding:20px 24px; border-top:1px solid var(--border);">
                <form method="POST" action="{{ route('landlord.reply', $property) }}" style="display:flex; gap:12px; align-items:flex-end;">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $tenant->id }}">
                    <textarea
                        name="message"
                        placeholder="Type your reply..."
                        required
                        rows="2"
                        style="flex:1; padding:12px 16px; border:1px solid var(--border); border-radius:10px; font-size:14px; outline:none; resize:none; font-family:inherit;"
                        onkeydown="if(event.ctrlKey && event.key==='Enter') this.form.submit()"></textarea>
                    <button type="submit" class="btn btn-primary" style="padding:12px 24px; align-self:flex-end;">
                        Reply 📤
                    </button>
                </form>
                <p style="font-size:12px; color:var(--gray); margin-top:8px;">Press Ctrl+Enter to send quickly</p>
            </div>
        </div>
    </main>
</div>

<script>
    const container = document.getElementById('messageContainer');
    if (container) container.scrollTop = container.scrollHeight;
</script>
</body>
</html>