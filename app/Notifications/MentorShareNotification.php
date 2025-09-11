<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MentorShareNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly User $sharingUser)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('A user shared reports with you')
            ->line('The user ' . $this->sharingUser->name . ' shared his reports and wants to contact with you.')
            ->action('Open Mentor Page', url(route('mentor.index')))
            ->line('You can view the client list on your Mentor page.');
    }
}


