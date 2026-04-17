@extends('merchant.layouts.sidebar')
@section('page-title', 'Notifikasi')
@section('content')

<style>
.notif-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;}
.btn-read-all{background:none;border:.5px solid rgba(11,42,74,.15);border-radius:8px;padding:8px 16px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.45);cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s;text-decoration:none;}
.btn-read-all:hover{color:#0b2a4a;border-color:rgba(11,42,74,.3);}

.notif-list{display:flex;flex-direction:column;gap:8px;}
.notif-item{
    display:flex;align-items:flex-start;gap:16px;
    background:white;border-radius:12px;
    border:.5px solid rgba(11,42,74,.08);
    padding:16px 20px;
    text-decoration:none;color:inherit;
    transition:background .15s,border-color .15s;
    position:relative;
}
.notif-item:hover{background:#fafaf8;border-color:rgba(11,42,74,.12);}
.notif-item.unread{border-left:3px solid #c9a96e;background:#fffdf9;}
.notif-item.unread:hover{background:#fff9f0;}

.notif-icon{
    width:40px;height:40px;border-radius:50%;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;
    background:rgba(201,169,110,.12);
}
.notif-icon svg{color:#c9a96e;}

.notif-body{flex:1;min-width:0;}
.notif-title{font-size:13px;font-weight:500;color:#0b2a4a;margin-bottom:3px;}
.notif-text{font-size:12px;color:rgba(11,42,74,.5);line-height:1.6;}
.notif-time{font-size:11px;color:rgba(11,42,74,.3);margin-top:6px;}

.notif-dot{
    width:8px;height:8px;border-radius:50%;
    background:#c9a96e;flex-shrink:0;margin-top:6px;
}

.empty-notif{text-align:center;padding:60px 20px;color:rgba(11,42,74,.3);}
.empty-notif svg{margin:0 auto 16px;display:block;opacity:.25;}
.empty-notif p{font-size:14px;}

.flash{background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:10px 16px;font-size:13px;color:#2d6a2d;margin-bottom:20px;}
</style>

@if(session('success'))
    <div class="flash">{{ session('success') }}</div>
@endif

<div class="notif-header">
    <div style="font-size:13px;color:rgba(11,42,74,.4);">
        {{ $notifications->total() }} notifikasi
    </div>
    <form action="{{ route('merchant.notifications.readAll') }}" method="POST">
        @csrf
        <button type="submit" class="btn-read-all">Tandai semua dibaca</button>
    </form>
</div>

<div class="notif-list">
    @forelse($notifications as $notif)
    <a href="{{ route('merchant.notifications.read', $notif) }}" class="notif-item {{ $notif->isRead() ? '' : 'unread' }}">

        <div class="notif-icon">
            @if($notif->type === 'order_placed')
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            @elseif($notif->type === 'order_cancelled')
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c0392b" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            @else
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            @endif
        </div>

        <div class="notif-body">
            <p class="notif-title">{{ $notif->title }}</p>
            @if($notif->body)
                <p class="notif-text">{{ $notif->body }}</p>
            @endif
            @if($data = $notif->data)
                @if(isset($data['order_code']))
                <p class="notif-text" style="margin-top:4px;">
                    Kode: <strong>{{ $data['order_code'] }}</strong>
                    @if(isset($data['buyer_name'])) · {{ $data['buyer_name'] }}@endif
                </p>
                @endif
            @endif
            <p class="notif-time">{{ $notif->created_at->diffForHumans() }}</p>
        </div>

        @if(!$notif->isRead())
            <div class="notif-dot"></div>
        @endif
    </a>
    @empty
    <div class="empty-notif">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
        <p>Belum ada notifikasi</p>
    </div>
    @endforelse
</div>

@if($notifications->hasPages())
<div style="margin-top:24px;">{{ $notifications->links() }}</div>
@endif

@endsection
