@php
    $unreadCount = \App\Models\Notification::forAdmin()->unread()->count();
@endphp

<a href="{{ route('admin.notifications.index') }}"
   id="adminBellBtn"
   style="position:relative;display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;color:rgba(59,46,34,.45);text-decoration:none;transition:background .2s,color .2s;"
   onmouseover="this.style.background='rgba(59,46,34,.06)';this.style.color='#3b2e22'"
   onmouseout="this.style.background='transparent';this.style.color='rgba(59,46,34,.45)'"
   title="Notifikasi">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 01-3.46 0"/>
    </svg>
    <span id="adminBellBadge"
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
    function updateAdminBell() {
        fetch('{{ route('admin.notifications.count') }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('adminBellBadge');
            if (!badge) return;
            const count = (data.unread_notifs || 0);
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
                badge.style.transform = 'scale(1.2)';
                setTimeout(() => badge.style.transform = 'scale(1)', 200);
            } else {
                badge.style.display = 'none';
            }
        })
        .catch(() => {});
    }

    setTimeout(updateAdminBell, 5000);
    setInterval(updateAdminBell, 30000);
})();
</script>
