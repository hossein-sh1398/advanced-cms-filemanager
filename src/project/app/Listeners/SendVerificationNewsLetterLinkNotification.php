<?php

namespace App\Listeners;

use App\Events\EmailReportEvent;
use App\Mail\NewsLetterLinkMail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationNewsLetterLinkNotification
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $url = URL::temporarySignedRoute(
            'news.letters.verify.email',
            now()->addMinutes(60),
            ['newsLetter' => $event->newsLetter->id]
        );


        $message = __('messages.verify-account-message', ['url' => $url]);
        $emailSubject = __('messages.subject-email-email-subject');

        Mail::to($event->newsLetter->email)->send(new NewsLetterLinkMail($message, $emailSubject));

        event(new EmailReportEvent([
            'model' =>$event->newsLetter,
            'moreData' => [
                'content' => $message,
                'email' => $event->newsLetter->email,
            ],
        ]));
    }
}
