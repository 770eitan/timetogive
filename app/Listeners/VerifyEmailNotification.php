<?php

namespace App\Listeners;
use App\Mail\EmailVerify;
use Illuminate\Support\Facades\Mail;
use App\Events\VerifyEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
class VerifyEmailNotification
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
     * @param  \App\Events\VerifyEmailEvent  $event
     * @return void
     */
    public function handle(VerifyEmailEvent $event)
    {
        $user = $event->user;
        Mail::to($user->email)->send(new EmailVerify($user));
    }
}
