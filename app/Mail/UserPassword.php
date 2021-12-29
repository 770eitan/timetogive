<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\User
     */
    public $user;
    public $plainPass;

    public $subject = 'Congratulations! you are all set for TimeToGive.';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,$plainPass)
    {
        $this->user = $user;
        $this->plainPass = $plainPass;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user-password');
    }
}
