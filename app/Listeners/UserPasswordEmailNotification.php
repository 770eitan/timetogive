<?php

namespace App\Listeners;

use App\Events\UserPasswordEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\UserPassword;
use Illuminate\Support\Facades\Mail;
class UserPasswordEmailNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserPasswordEmailEvent  $event
     * @return void
     */
    public function handle(UserPasswordEmailEvent $event)
    {
        $user = $event->user;
        $password = $event->password;
        Mail::to($user->email)->send(new UserPassword($user, $password));
    }
}
