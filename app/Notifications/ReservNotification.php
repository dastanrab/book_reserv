<?php

namespace App\Notifications;

use App\Mail\ReservMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ReservNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private mixed $book;

    /**
     * Create a new notification instance.
     */

    public function __construct($book)
    {
        $this->book=$book;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): ReservMail
    {
        return (new ReservMail($this->book))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
