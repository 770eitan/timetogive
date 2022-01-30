<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScheduleExpireCharities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        $none = \App\Models\CharityTicker::where(function($query){
                $query->whereNull('timer_completed_at')->orWhereRaw('(not ("timer_completed_at" > to_timestamp(0)))');
            })
            ->where('timer_start', '>', new \Carbon\Carbon(0))
            ->whereColumn('timer_expiry_timestamp', '>', 'timer_start')
            ->where('total_donation_amount','>',0)
            ->where('donation_amount','>',0)
            ->where('tick_frequency','>',0)
            ->whereIn('tick_frequency_unit', ['sec','mins','hours','days'])
            ->doesntExist();

        if($none){
            return;
        }
        
        // \Log::notice("ScheduleExpireCharities::handle - dispatching ExpireCharities delayed...");
        \App\Jobs\ExpireCharities::dispatch()->delay(now()->addMinutes(1));
        // \Log::notice("ScheduleExpireCharities::handle - dispatched ExpireCharities");
    }
}
