<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Events\EmailReportEvent;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = __('messages.reset-password-link', ['url' => $this->url]);
        $emailSubject = __('messages.reset-password-email-subject');
        
        event(new EmailReportEvent([
            'model' => $notifiable,
            'moreData' => [
                'content' => $url,
                'email' => $notifiable->email,
            ],
        ]));

        return (new MailMessage)
                    ->subject($emailSubject)
                    ->view('email.reset-password', ['url' => $url]);
    }
}
