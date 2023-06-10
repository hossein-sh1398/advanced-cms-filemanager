<?php

namespace App\Listeners;

use App\Models\Report;
use App\Enums\ReportType;
use App\Events\EmailReportEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailReportListener
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
     * @param  \App\Events\EmailReportEvent  $event
     * @return void
     */
    public function handle(EmailReportEvent $event)
    {
        $data = $event->data;

        $data['model']->reports()->create([
            'type' => ReportType::Email,
            'moreData' => $data['moreData'],
        ]);
    }
}
