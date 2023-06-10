<?php

namespace App\Listeners;

use App\Enums\ReportType;
use App\Events\SMSReportEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SMSReportListener
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
     * @param  \App\Events\SMSReportEvent  $event
     * @return void
     */
    public function handle(SMSReportEvent $event)
    {
        $data = $event->data;

        $data['model']->reports()->create([
            'moreData' => $data['moreData'],
            'type' => ReportType::Mobile,
            'delivery' => $data['delivery'],
        ]);
    }
}
