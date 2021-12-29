<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MyAppHelpServiceProvider extends ServiceProvider
{ 

    public $customHelpers = [
      'helper'
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->customHelpers as $helper) {
            require_once app_path() . '/Helpers/' . $helper . '.php';
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
