<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;

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

        
        \Queue::after(function (JobProcessed $event) {
            // print_r($event->job);
            \Log::notice('11111111111111122222222233333333333333333333');
            $payload = $event->job->payload();
            print_r($payload);
            // $event->connectionName
            // $event->job
            // $event->job->payload()
            if ( strpos( $payload['data']['commandName'], 'App\\Jobs\\ExpireCharities' ) === false ) {
                return;
            }


            \Log::notice("Queue::after {$payload['commandName']} - dispatching ScheduleExpireCharities::dispatch");

            \App\Jobs\ScheduleExpireCharities::dispatch();
            
            \Log::notice("Queue::after {$payload['commandName']} - dispatched ScheduleExpireCharities");

        });
    }
}
