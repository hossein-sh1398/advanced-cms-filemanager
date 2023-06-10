<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProfileComponent extends Component
{
    public ?\Illuminate\Contracts\Auth\Authenticatable $user;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|string|Closure
    {
        return view('components.profile-component');
    }
}
