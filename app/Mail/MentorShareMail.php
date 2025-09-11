<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MentorShareMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly User $sharingUser)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A user shared reports with you',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.mentor-share-plain',
            with: [
                'sharingUser' => $this->sharingUser,
                'mentorUrl' => url(route('mentor.index')),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}


