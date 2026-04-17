
@php
    $unreadCount = 0;
    if (auth()->check() && auth()->user()->store) {
        $unreadCount = \App\Models\Notification::forStore(auth()->user()->store->id)
            ->unread()
            ->count();
    }
@endphp

<a href="{{ route('merchant.notifications') }}"
   id="bellBtn"
   style="position:relative;display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;color:rgba(11,42,74,.45);text-decoration:none;transition:background .2s,color .2s;"
   onmouseover="this.style.background='rgba(11,42,74,.06)';this.style.color='#0b2a4a'"
   onmouseout="this.style.background='transparent';this.style.color='rgba(11,42,74,.45)'"
   title="Notifikasi">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 01-3.46 0"/>
    </svg>
    <span id="bellBadge"
          style="position:absolute;top:2px;right:2px;
                 background:#c0392b;color:white;
                 font-size:9px;font-weight:600;font-family:'DM Sans',sans-serif;
                 min-width:16px;height:16px;border-radius:100px;
                 display:{{ $unreadCount > 0 ? 'flex' : 'none' }};
                 align-items:center;justify-content:center;padding:0 3px;
                 border:1.5px solid white;line-height:1;">
        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
    </span>
</a>

<script>

(function() {
    function updateBell() {
        fetch('{{ route('merchant.notifications.count') }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('bellBadge');
            if (!badge) return;
            if (data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : data.count;
                badge.style.display = 'flex';
                // Animasi kecil kalau ada notif baru
                badge.style.transform = 'scale(1.2)';
                setTimeout(() => badge.style.transform = 'scale(1)', 200);
            } else {
                badge.style.display = 'none';
            }
        })
        .catch(() => {}); // silent fail
    }

    setTimeout(updateBell, 5000);
    setInterval(updateBell, 30000);
})();
</script>
