<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $pageInfo = [
        'name' => '',
        'description' => '',
        'icon' => ''
    ];

    public $table = [];

    public function __construct($enableAuthorize = false)
    {
         $this->pageInfo = (object)$this->pageInfo;
         view()->share('pageInfo', $this->pageInfo);
         if ($enableAuthorize) {
             $routeName = Route::currentRouteName();
             $this->middleware("can:$routeName");
         }
    }
}
