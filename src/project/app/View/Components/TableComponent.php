<?php

namespace App\View\Components;

use App\Utility\Table;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class TableComponent extends Component
{
    public $array;
    public $model;
    public $url;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($array = null, $model, $url)
    {
        if (is_null($array)) {
            $array = (new Table([]))->toArray();
        }

        $this->array = $array;
        $this->model = $model;
        $this->url = $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table-component');
    }
}
