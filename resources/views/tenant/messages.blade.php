@extends('layouts.app')
@section('title', 'Messages - HomeFinder')

@section('content')
<div style="max-width:900px; margin:0 auto; padding:40px 24px;">
    <h1 style="font-size:26px; font-weight:700; margin-bottom:8px;">💬 Messages</h1>
    <p style="color:var(--gray); margin-bottom:32px;">Your conversations with landlords.</p>

    @if($conversations->count() === 0)
        <div style="text-align:center; padding:80px; background:white; border-radius:10px; box-shadow:var(--shadow);">
            <div style="font-size:48px; margin-bottom:16px;">💬</div>
            <h3>No messages yet</h3>
            <p style="color:var(--gray); margin-top:8px;">Browse properties and message a landlord to start a conversation.</p>
            <a href="/browse" class="btn btn-primary" style="margin-top:20px; display:inline-block;">Browse Houses</a>
        </div>
    @else
        <div style="background:white; border-radius:10px; box-shadow:var(--shadow); overflow:hidden;">
            @foreach($conversations as $message)
            <a href="{{ route('tenant.conversation', $message->property) }}"
                style="display:flex; align-items:center; gap:16px; padding:20px 24px; border-bottom:1px solid var(--border); text-decoration:none; color:inherit; transition:background 0.2s;"
                onmouseover="this.style.background='#f8f9fa'"
                onmouseout="this.style.background='white'">

                <!-- PROPERTY IMAGE -->
                <div style="width:56px; height:56px; border-radius:10px; overflow:hidden; flex-shrink:0; background:var(--background); display:flex; align-items:center; justify-content:center; font-size:24px;">
                    @if($message->property && $message->property->cover_image)
                        <img src="{{ asset('storage/'.$message->property->cover_image) }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        🏠
                    @endif
                </div>

                <!-- CONVERSATION INFO -->
                <div style="flex:1; min-width:0;">
                    <div style="font-weight:600; font-size:15px; margin-bottom:4px;">
                        {{ $message->property->title ?? 'Property' }}
                    </div>
                    <div style="font-size:13px; color:var(--gray); margin-bottom:4px;">
                        👤 {{ $message->property->landlord->name ?? 'Landlord' }}
                    </div>
                    <div style="font-size:13px; color:var(--gray); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $message->sender === 'user' ? 'You: ' : '' }}{{ Str::limit($message->message, 60) }}
                    </div>
                </div>

                <!-- TIME & UNREAD -->
                <div style="text-align:right; flex-shrink:0;">
                    <div style="font-size:12px; color:var(--gray);">
                        {{ $message->created_at->diffForHumans() }}
                    </div>
                    @if(!$message->is_read && $message->sender === 'landlord')
                        <span style="display:inline-block; width:10px; height:10px; background:var(--primary); border-radius:50%; margin-top:6px;"></span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection