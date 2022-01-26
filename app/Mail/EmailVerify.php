<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerify extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\User
     */
    public $user;


    public $subject = 'Verify your email';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.email-verify');
    }
}
