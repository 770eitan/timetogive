<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\User
     */
    public $user;
    public $plainPass;

    public $subject = 'Congratulations! Your TimeToGive Tzedoko is all set and ticking away.';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $plainPass)
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
