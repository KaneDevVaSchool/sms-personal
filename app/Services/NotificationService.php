<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * @param  array<int, mixed>  $notifiables
     * @param  array<string, mixed>  $data
     */
    public function notifyInApp(array $notifiables, string $type, array $data): void
    {
        Notification::send($notifiables, new class($type, $data) extends \Illuminate\Notifications\Notification
        {
            public function __construct(
                private readonly string $type,
                private readonly array $data,
            ) {}

            public function via(object $notifiable): array
            {
                return ['database'];
            }

            public function toArray(object $notifiable): array
            {
                return array_merge(['type' => $this->type], $this->data);
            }
        });
    }

    /**
     * @return Collection<int, DatabaseNotification>
     */
    public function unreadFor(User $user, int $limit = 20)
    {
        return $user->notifications()->limit($limit)->get();
    }
}
