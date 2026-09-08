<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Notifications\LoginNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;

class SendLoginNotification implements ShouldQueue
{
    public function handle(UserLoggedIn $event): void
    {
        $location = 'Bilinmiyor';

        if ($event->ip && ! in_array($event->ip, ['127.0.0.1', '::1'])) {
            try {
                $response = Http::timeout(5)
                    ->get("https://ipapi.co/{$event->ip}/json/");

                if ($response->successful()) {
                    $data = $response->json();

                    $city = $data['city'] ?? null;
                    $country = $data['country_name'] ?? null;

                    $location = collect([
                        $city,
                        $country,
                    ])
                        ->filter()
                        ->implode(', ');
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $event->user->notify(
            new LoginNotification(
                $event->ip,
                $event->userAgent,
                $event->loginAt,
                $location,
            )
        );
    }
}
