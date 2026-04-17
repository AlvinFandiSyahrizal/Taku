<?php
namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Password Akun Taku')
            ->greeting('Halo!')
            ->line('Kami menerima permintaan reset password untuk akun kamu.')
            ->action('Reset Password Sekarang', $url)
            ->line('Link ini berlaku selama 60 menit.')
            ->line('Jika kamu tidak meminta reset password, abaikan email ini. Password kamu tidak akan berubah.')
            ->salutation('Salam, Tim Taku');
    }
}
