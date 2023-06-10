<?php

namespace App\Listeners;

use App\Notifications\OtpEmailNotification;
use App\Utility\SmsCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCodeVerificationNotificationAccount
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
     * @param  object  $event
     * @return void
     */
    public function handle($event): void
    {
        $user = $event->user;

        SmsCode::generate($user);

        $message = __('messages.send-otp-message', ['code' => $user->sms_code]);
        $emailSubject = __('messages.subject-email-otp');

        $user->notify(new OtpEmailNotification($message, $emailSubject));
    }
}
