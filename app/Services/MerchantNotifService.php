<?php
namespace App\Services;

use App\Models\Store;
use Illuminate\Support\Facades\Mail;

class MerchantNotifService
{
    /**
     * Kirim notifikasi approve toko
     */
    public function sendApproved(Store $store): void
    {
        $phone   = $store->phone;
        $name    = $store->name;
        $owner   = $store->user->name;
        $dashUrl = url('/merchant/dashboard');

        // ── Email ─────────────────────────────────────────────────
        if (config('notif.email_enabled', true) && $store->user->email) {
            try {
                Mail::send([], [], function ($m) use ($store, $owner, $dashUrl) {
                    $m->to($store->user->email, $owner)
                      ->subject('🎉 Toko Kamu Disetujui — Taku')
                      ->html($this->emailHtml(
                          "Selamat, {$owner}!",
                          "Toko <strong>{$store->name}</strong> kamu sudah disetujui dan kini aktif di Taku Marketplace.",
                          'Buka Dashboard Merchant',
                          $dashUrl,
                          '#27ae60'
                      ));
                });
            } catch (\Exception $e) {}
        }

        // ── WA link (buka di server — hanya log URL, user perlu klik manual) ──
        // Untuk notif WA otomatis, perlu WA Business API / Fonnte
        if (config('notif.wa_enabled', false) && $phone) {
            $waNum  = preg_replace('/[^0-9]/', '', $phone);
            $msg    = urlencode(
                "Halo {$owner}! 🎉\n\n" .
                "Toko *{$name}* kamu di Taku sudah *DISETUJUI* dan kini aktif.\n\n" .
                "Mulai kelola toko kamu di:\n{$dashUrl}\n\n" .
                "_Taku Marketplace_"
            );
            // Log URL WA — bisa dikirim via Fonnte API di sini
            \Illuminate\Support\Facades\Log::info("WA Notif Approve: https://wa.me/{$waNum}?text={$msg}");
        }
    }

    public function sendRejected(Store $store, string $reason): void
    {
        $owner   = $store->user->name;
        $resubUrl = url('/store/register');

        if (config('notif.email_enabled', true) && $store->user->email) {
            try {
                Mail::send([], [], function ($m) use ($store, $owner, $reason, $resubUrl) {
                    $m->to($store->user->email, $owner)
                      ->subject('Pengajuan Toko Ditolak — Taku')
                      ->html($this->emailHtml(
                          "Halo, {$owner}",
                          "Pengajuan toko <strong>{$store->name}</strong> belum bisa kami setujui.<br><br>" .
                          "<strong>Alasan:</strong> {$reason}<br><br>" .
                          "Kamu bisa mengajukan kembali setelah 7 hari dengan memperbaiki kekurangan di atas.",
                          'Ajukan Ulang',
                          $resubUrl,
                          '#e67e22'
                      ));
                });
            } catch (\Exception $e) {}
        }

        if (config('notif.wa_enabled', false) && $store->phone) {
            $waNum = preg_replace('/[^0-9]/', '', $store->phone);
            $msg   = urlencode(
                "Halo {$owner},\n\n" .
                "Pengajuan toko *{$store->name}* di Taku belum bisa disetujui.\n\n" .
                "Alasan: {$reason}\n\n" .
                "Kamu bisa mengajukan ulang setelah 7 hari di:\n{$resubUrl}\n\n" .
                "_Taku Marketplace_"
            );
            \Illuminate\Support\Facades\Log::info("WA Notif Reject: https://wa.me/{$waNum}?text={$msg}");
        }
    }

    public function sendBanned(Store $store): void
    {
        $owner = $store->user->name;

        if (config('notif.email_enabled', true) && $store->user->email) {
            try {
                Mail::send([], [], function ($m) use ($store, $owner) {
                    $m->to($store->user->email, $owner)
                      ->subject('Toko Dinonaktifkan — Taku')
                      ->html($this->emailHtml(
                          "Halo, {$owner}",
                          "Toko <strong>{$store->name}</strong> kamu di Taku telah dinonaktifkan karena pelanggaran ketentuan layanan.",
                          'Hubungi Support',
                          'https://wa.me/' . config('app.cs_wa', ''),
                          '#c0392b'
                      ));
                });
            } catch (\Exception $e) {}
        }
    }

    private function emailHtml(string $heading, string $body, string $btnText, string $btnUrl, string $btnColor): string
    {
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <body style="margin:0;padding:0;background:#f5f1e8;font-family:'DM Sans',Arial,sans-serif;">
            <div style="max-width:520px;margin:40px auto;background:white;border-radius:16px;overflow:hidden;border:0.5px solid rgba(44,24,16,0.08);">
                <div style="background:#2e2318;padding:24px 32px;">
                    <p style="font-family:Georgia,serif;font-size:24px;color:#f5f0e8;letter-spacing:0.2em;margin:0;">TAKU</p>
                </div>
                <div style="padding:32px;">
                    <h2 style="font-size:20px;color:#2e2318;margin:0 0 16px;">{$heading}</h2>
                    <p style="font-size:14px;color:rgba(44,24,16,0.65);line-height:1.7;margin:0 0 24px;">{$body}</p>
                    <a href="{$btnUrl}"
                       style="display:inline-block;padding:12px 28px;background:{$btnColor};color:white;border-radius:8px;text-decoration:none;font-size:13px;font-weight:500;letter-spacing:0.05em;">
                        {$btnText}
                    </a>
                </div>
                <div style="padding:16px 32px;border-top:0.5px solid rgba(44,24,16,0.06);text-align:center;">
                    <p style="font-size:11px;color:rgba(44,24,16,0.35);margin:0;">© 2026 Taku Marketplace · <a href="https://taku.web.id" style="color:rgba(44,24,16,0.35);">taku.web.id</a></p>
                </div>
            </div>
        </body>
        </html>
        HTML;
    }
}
