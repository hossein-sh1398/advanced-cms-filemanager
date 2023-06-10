<?php

namespace App\Providers;

use App\Models\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class ViewShareProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('configs')) {
            View::share('favicon', Config::where('key', 'image_favicon')->value('value'));
            View::share('logo', Config::where('key', 'general_default_logo')->value('value'));
        }
    }
}
