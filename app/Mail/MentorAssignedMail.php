<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MentorAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly User $user)
    {
    }

    public function build()
    {
        return $this->subject('You have been assigned as a Mentor')
            ->view('emails.mentor_assigned')
            ->with([
                'user' => $this->user,
            ]);
    }
}





