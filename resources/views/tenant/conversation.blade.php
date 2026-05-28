@extends('layouts.app')
@section('title', 'Conversation - HomeFinder')

@section('content')
<div style="max-width:800px; margin:0 auto; padding:40px 24px;">

    <!-- HEADER -->
    <a href="{{ route('tenant.messages') }}" style="display:inline-flex; align-items:center; gap:6px; color:var(--gray); font-size:14px; margin-bottom:20px;">← Back to Messages</a>

    <div style="background:white; border-radius:12px; box-shadow:var(--shadow); overflow:hidden;">

        <!-- CONVERSATION HEADER -->
        <div style="padding:20px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:14px;">
            <div style="width:48px; height:48px; border-radius:10px; overflow:hidden; background:var(--background); display:flex; align-items:center; justify-content:center; font-size:22px;">
                @if($property->cover_image)
                    <img src="{{ asset('storage/'.$property->cover_image) }}" style="width:100%; height:100%; object-fit:cover;">
                @else
                    🏠
                @endif
            </div>
            <div>
                <div style="font-weight:700; font-size:16px;">{{ $property->title }}</div>
                <div style="font-size:13px; color:var(--gray);">
                    💬 {{ $property->landlord->name ?? 'Landlord' }} •
                    📍 {{ $property->location }} •
                    <a href="/properties/{{ $property->id }}" style="color:var(--primary);">View Property</a>
                </div>
            </div>
        </div>

        <!-- MESSAGES -->
        <div style="padding:24px; min-height:400px; max-height:500px; overflow-y:auto; display:flex; flex-direction:column; gap:16px;" id="messageContainer">

            @if($messages->count() === 0)
                <div style="text-align:center; color:var(--gray); padding:40px; font-size:14px;">
                    No messages yet. Send your first message below!
                </div>
            @else
                @foreach($messages as $message)
                <div style="display:flex; {{ $message->sender === 'user' ? 'justify-content:flex-end' : 'justify-content:flex-start' }};">
                    <div style="max-width:70%;">
                        <div style="
                            padding:12px 16px;
                            border-radius:{{ $message->sender === 'user' ? '18px 18px 4px 18px' : '18px 18px 18px 4px' }};
                            background:{{ $message->sender === 'user' ? 'var(--primary)' : 'var(--background)' }};
                            color:{{ $message->sender === 'user' ? 'white' : 'var(--text)' }};
                            font-size:14px;
                            line-height:1.6;
                        ">
                            {{ $message->message }}
                        </div>
                        <div style="font-size:11px; color:var(--gray); margin-top:4px; {{ $message->sender === 'user' ? 'text-align:right' : '' }}">
                            {{ $message->sender === 'user' ? 'You' : $property->landlord->name }}
                            • {{ $message->created_at->format('d M, h:i A') }}
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <!-- MESSAGE INPUT -->
        @if(session('success'))
            <div style="background:#d1fae5; color:#065f46; padding:10px 24px; font-size:13px;">{{ session('success') }}</div>
        @endif

        <div style="padding:20px 24px; border-top:1px solid var(--border);">
            <form method="POST" action="{{ route('tenant.message.send', $property) }}" style="display:flex; gap:12px; align-items:flex-end;">
                @csrf
                <textarea
                    name="message"
                    placeholder="Type your message..."
                    required
                    rows="2"
                    style="flex:1; padding:12px 16px; border:1px solid var(--border); border-radius:10px; font-size:14px; outline:none; resize:none; font-family:inherit;"
                    onkeydown="if(event.ctrlKey && event.key==='Enter') this.form.submit()"></textarea>
                <button type="submit" class="btn btn-primary" style="padding:12px 24px; align-self:flex-end;">
                    Send 📤
                </button>
            </form>
            <p style="font-size:12px; color:var(--gray); margin-top:8px;">Press Ctrl+Enter to send quickly</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto scroll to bottom of messages
    const container = document.getElementById('messageContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
</script>
@endsection