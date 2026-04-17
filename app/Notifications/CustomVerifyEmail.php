<?php
namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('Verifikasi Email Akun Taku Kamu')
            ->greeting('Halo!')
            ->line('Terima kasih sudah mendaftar di Taku.')
            ->line('Klik tombol di bawah untuk memverifikasi email kamu.')
            ->action('Verifikasi Email Sekarang', $url)
            ->line('Link ini berlaku selama 60 menit.')
            ->line('Jika kamu tidak merasa mendaftar di Taku, abaikan email ini.')
            ->salutation('Salam, Tim Taku');
    }
}
