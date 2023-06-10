<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SMSReportEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
}
