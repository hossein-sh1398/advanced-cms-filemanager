<?php

namespace App\View\Components;

use App\Models\Comment;
use App\Models\History;
use App\Models\Message;
use App\Models\Report;
use Illuminate\View\Component;

class HeaderNotificationComponent extends Component
{
    public $messages;
    public $comments;
    public $reports;
    public $histories;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->messages = Message::latest()->limit(20)->get();
        $this->comments = Comment::where('parent_id', 0)->latest()->limit(20)->get();
        $this->reports = Report::latest()->limit(20)->get();
        $this->histories = History::latest()->limit(20)->get();

        return view('components.header-notification-component');
    }
}
