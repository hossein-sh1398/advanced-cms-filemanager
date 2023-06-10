<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpEmailNotification extends Notification
{
    use Queueable;

    public $code;
    public $subject;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($code, $subject)
    {
        $this->code = $code;
        $this->subject = $subject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $code = $this->code;
        return (new MailMessage)->subject($this->subject)->view('email.code', compact('code'));
    }
}
