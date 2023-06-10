<?php

namespace App\Events;

use App\Models\NewsLetter;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegisteredNewsLettersEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $newsLetter;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(NewsLetter $newsLetter)
    {
        $this->newsLetter = $newsLetter;
    }
}
