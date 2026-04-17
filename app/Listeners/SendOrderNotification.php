<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendOrderNotification
{
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order->load(['store', 'items']);

        if (!$order->store) {
            return;
        }

        $itemLines = $order->items->map(function ($item) {
            return "  • {$item->product_name} x{$item->qty} = Rp "
                . number_format($item->subtotal, 0, ',', '.');
        })->implode("\n");

        Notification::create([
            'store_id' => $order->store_id,
            'type'     => 'order_placed',
            'title'    => 'Pesanan baru masuk!',
            'body'     => "Order #{$order->order_code} dari {$order->name} · {$order->getTotalFormatted()}",
            'data'     => [
                'order_id'    => $order->id,
                'order_code'  => $order->order_code,
                'buyer_name'  => $order->name,
                'buyer_phone' => $order->phone,
                'total'       => $order->total,
            ],
        ]);

        $merchantPhone = $order->store->phone ?? null;
        if (!$merchantPhone) {
            return;
        }

        $fonnte_token = config('services.fonnte.token');
        if (!$fonnte_token) {
            Log::warning('Fonnte token tidak dikonfigurasi. Isi FONNTE_TOKEN di .env');
            return;
        }

        $phone = preg_replace('/\D/', '', $merchantPhone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        $message = "🛒 *PESANAN BARU MASUK*\n"
            . "━━━━━━━━━━━━━━━━━━━\n"
            . "Kode   : *{$order->order_code}*\n"
            . "Pembeli: {$order->name}\n"
            . "HP/WA  : {$order->phone}\n"
            . "Alamat : {$order->address}\n"
            . ($order->note ? "Catatan: {$order->note}\n" : "")
            . "━━━━━━━━━━━━━━━━━━━\n"
            . "*Produk:*\n{$itemLines}\n"
            . "━━━━━━━━━━━━━━━━━━━\n"
            . "Total  : *{$order->getTotalFormatted()}*\n\n"
            . "Silakan konfirmasi di dashboard merchant kamu.";

        try {
            $response = Http::withHeaders([
                'Authorization' => $fonnte_token,
            ])->post('https://api.fonnte.com/send', [
                'target'  => $phone,
                'message' => $message,
            ]);

            if (!$response->successful()) {
                Log::error('Fonnte gagal', ['response' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::error('Fonnte exception: ' . $e->getMessage());
        }
    }
}