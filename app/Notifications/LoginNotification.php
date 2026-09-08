<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ?string $ip,
        public ?string $userAgent,
        public string $loginAt,
        public string $location,
    ) {}

    public function via(object $notifiable): array
    {
        return [
            'mail',
            'database',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Hesabınıza giriş yapıldı')
            ->greeting("Merhaba {$notifiable->name}")
            ->line('Hesabınıza yeni bir giriş yapıldı.')
            ->line("Tarih: {$this->loginAt}")
            ->line('IP: '.($this->ip ?? 'Bilinmiyor'))
            ->line("Konum: {$this->location}")
            ->line('Cihaz / Tarayıcı: '.($this->userAgent ?? 'Bilinmiyor'))
            ->line('Eğer bu giriş size ait değilse hesabınızı kontrol edin.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Yeni giriş',
            'message' => 'Hesabınıza yeni bir giriş yapıldı.',
            'ip' => $this->ip,
            'location' => $this->location,
            'user_agent' => $this->userAgent,
            'login_at' => $this->loginAt,
        ];
    }
}
