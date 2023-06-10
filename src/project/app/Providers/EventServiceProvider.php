<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use App\Events\RegisteredNewsLettersEvent;
use App\Listeners\SendCodeVerificationNotificationAccount;
use App\Listeners\SendVerificationNewsLetterLinkNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendCodeVerificationNotificationAccount::class,
        ],
        RegisteredNewsLettersEvent::class => [
            SendVerificationNewsLetterLinkNotification::class,
        ],
        \App\Events\SMSReportEvent::class => [
            \App\Listeners\SMSReportListener::class
        ],
        \App\Events\EmailReportEvent::class => [
            \App\Listeners\EmailReportListener::class
        ]

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

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
