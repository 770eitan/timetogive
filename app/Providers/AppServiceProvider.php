<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      if(config('app.env') !== 'local') { 
        $this->app['request']->server->set('HTTPS',true); 
      }
      
      // Validator::extend('checks_out', function($attribute, $value, $parameters, $validator) {
      //   // $min_field = $parameters[0];
      //   in_array($attribute, ['donation_amount', 'tick_frequency', 'tick_frequency_unit', 'total_donation_amount']);
      //   empty(array_diff($parameters, ['donation_amount', 'tick_frequency', 'tick_frequency_unit', 'total_donation_amount']));
      //   $data = $validator->getData();
      //   // $min_value = $data[$min_field];

      //   $data['donation_amount'] * 


      //   return $value > $min_value;
      // });
    }
}
