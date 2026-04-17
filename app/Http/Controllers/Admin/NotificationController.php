<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::forAdmin()
            ->latest()
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function read(Notification $notification)
    {
        $notification->update(['read_at' => now()]);

        if (isset($notification->data['order_id'])) {
            return redirect()->route('admin.orders.show', $notification->data['order_id']);
        }
        if (isset($notification->data['store_id'])) {
            return redirect()->route('admin.stores.index');
        }

        return redirect()->route('admin.notifications.index');
    }

    public function readAll()
    {
        Notification::forAdmin()->unread()->update(['read_at' => now()]);
        return back()->with('success', 'Semua notifikasi sudah ditandai dibaca.');
    }
}
