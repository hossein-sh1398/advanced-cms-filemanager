<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsLetterLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $subject)
    {
        $this->url = $url;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('email.verify-email', ['url' => $this->url]);
    }
}
