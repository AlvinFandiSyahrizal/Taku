<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private function store()
    {
        return Auth::user()->store;
    }

    public function index()
    {
        $store = $this->store();
        $notifications = Notification::forStore($store->id)
            ->latest()
            ->paginate(20);

        Notification::forStore($store->id)->unread()->update(['read_at' => now()]);

        return view('merchant.notifications', compact('notifications'));
    }

    public function count()
    {
        $store = Auth::user()->store;
        $count = Notification::forStore($store->id)->unread()->count();
        return response()->json(['count' => $count]);
    }

    public function read(Notification $notification)
    {
        if ($notification->store_id !== $this->store()->id) {
            abort(403);
        }
        $notification->markAsRead();

        if ($orderId = $notification->data['order_id'] ?? null) {
            return redirect()->route('merchant.orders.show', $orderId);
        }
        return redirect()->route('merchant.notifications');
    }

    public function readAll()
    {
        Notification::forStore($this->store()->id)->unread()->update(['read_at' => now()]);
        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
