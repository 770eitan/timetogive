<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
// use App\Http\Repositories\CharityTickerRepository;
// use App\Models\CharityTicker;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // $schedule->call(function (CharityTickerRepository $charityTickerRepo) {

        //     ttgschedule($charityTickerRepo);

        //     \Log::notice("Kernel::schedule - done - dispatching ScheduleExpireCharities::dispatch");

        //     \App\Jobs\ScheduleExpireCharities::dispatch();
            
        //     \Log::notice("Kernel::schedule - dispatched ScheduleExpireCharities");
        // })->everyTenMinutes();


        
        // $schedule->call(function (CharityTickerRepository $charityTickerRepo) {

            
        //     // \Log::notice("Kernel::schedule - running...");
            
        //     // array_map([$charityTickerRepo, 'stopUserCharity'], CharityTicker::where('timer_start', '>', new \Carbon\Carbon(0))
        //     //     ->where(function($query){
        //     //         $query->whereNull('timer_completed_at')->orWhereRaw('(not ("timer_completed_at" > to_timestamp(0)))');
        //     //     })
        //     //     ->where('total_donation_amount','>',0)
        //     //     ->where('donation_amount','>',0)
        //     //     ->where('tick_frequency','>',0)
        //     //     ->whereIn('tick_frequency_unit', ['sec','mins','hours','days'])
        //     //     ->whereRaw('now() >= "timer_expiry_timestamp"')
        //     //     ->pluck('charity_code')
        //     //     ->all()
        //     // );

            
        //     // \Log::notice("Kernel::schedule - done - dispatching ScheduleExpireCharities::dispatch");

        //     // \App\Jobs\ScheduleExpireCharities::dispatch();
            
        //     // \Log::notice("Kernel::schedule - dispatched ScheduleExpireCharities");



        // })->everyTenMinutes();


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
