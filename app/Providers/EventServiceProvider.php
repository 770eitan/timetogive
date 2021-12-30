<?php

namespace App\Providers;

use App\Events\UserPasswordEmailEvent;
use App\Events\VerifyEmailEvent;
use App\Listeners\UserPasswordEmailNotification;
use App\Listeners\VerifyEmailNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        VerifyEmailEvent::class => [
            VerifyEmailNotification::class,
        ],
        UserPasswordEmailEvent::class => [
            UserPasswordEmailNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
