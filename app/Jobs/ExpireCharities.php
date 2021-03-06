<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Repositories\CharityTickerRepository;
use App\Models\CharityTicker;
use App\Models\User;

class ExpireCharities implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 300;

    // private $unittosec = [
    //     'sec' => 1,
    //     'mins' => 60,
    //     'hours' => 60 * 60,
    //     'days' => 60 * 60 * 24,
    // ];

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
    public function handle(CharityTickerRepository $charityTickerRepo)
    {
        $charities = \App\Models\CharityTicker::where('timer_start', '>', new \Carbon\Carbon(0))
            ->where(function($query){
                $query->whereNull('timer_completed_at')->orWhereRaw('(not ("timer_completed_at" > to_timestamp(0)))');
            })
            ->where('total_donation_amount','>',0)
            ->where('donation_amount','>',0)
            ->where('tick_frequency','>',0)
            ->whereIn('tick_frequency_unit', ['sec','mins','hours','days'])
            ->where(function($query) {
                $query->whereRaw('now() >= "timer_expiry_timestamp"')->orWhereRaw('now() >= ("timer_start" + interval \'6 days\')');
            })
            ->select('charity_code','user_id','charge','total_donation_amount')
                ->selectRaw('now() >= "timer_expiry_timestamp" expire')
                ->selectRaw('now() >= ("timer_start" + interval \'6 days\') capture') // running out of time to capture within 7 days
            ->get();

        $users = [];
        $userids = $charities->where('expire')->pluck('user_id')->all();
        if($userids){
            $users = \App\Models\User::whereIn('id', $userids)->get()->keyBy('id')->all();
        }

        $stripe = null;
        foreach ($charities as $charity) {
            if($charity->expire){
                $charityTickerRepo->stopUserCharity($charity->charity_code, $users[$charity->user_id]);
            } else if ($charity->capture) {
                if($stripe===null){
                    $stripe = \Cartalyst\Stripe\Stripe::make(config('services.stripe.secret'));
                }
                $charge = $stripe->charges()->capture($charity->charge, $charity->total_donation_amount, ['amount' => $charity->total_donation_amount]);
            }
        }
    }
    // public function handle(CharityTickerRepository $charityTickerRepo)
    // {
    //     ttgschedule($charityTickerRepo);
    // }

    // public function handle(CharityTickerRepository $charityTickerRepo)
    // {
    //     // $now = now();
    //     // $timezero = new \Carbon\Carbon(0);

    //     // CharityTicker::where('timer_start', '>', $timezero)
    //     //     ->where(function($query){
    //     //         $query->whereNull('timer_completed_at')->orWhereRaw('(not ("timer_completed_at" > :epoch0))', ['epoch0' => $timezero]);
    //     //     })
    //     //     ->where('total_donation_amount','>',0)
    //     //     ->where('donation_amount','>',0)
    //     //     ->where('tick_frequency','>',0)
    //     //     ->whereIn('tick_frequency_unit', ['sec','mins','hours','days'])
    //     //     ->whereRaw("(extract(epoch from timer_start) + ((total_donation_amount * 100) / (donation_amount * 100)) * (tick_frequency * (
    //     //             case
    //     //                 when tick_frequency_unit = 'sec' then 1
    //     //                 when tick_frequency_unit = 'mins' then 60
    //     //                 when tick_frequency_unit = 'hours' then 60 * 60
    //     //                 when tick_frequency_unit = 'days' then 60 * 60 * 24
    //     //             end
    //     //         ))) < extract(epoch from now())")->update([...................................])


    //     $charities = CharityTicker::where('timer_start', '>', new \Carbon\Carbon(0))
    //         ->where(function($query){
    //             $query->whereNull('timer_completed_at')->orWhereRaw('(not ("timer_completed_at" > to_timestamp(0)))');
    //         })
    //         ->where('total_donation_amount','>',0)
    //         ->where('donation_amount','>',0)
    //         ->where('tick_frequency','>',0)
    //         ->whereIn('tick_frequency_unit', ['sec','mins','hours','days'])
    //         ->where(function($query) {
    //             $query->whereRaw('now() >= "timer_expiry_timestamp"')->orWhereRaw('now() >= ("timer_start" + interval \'6 days\')');
    //         })
    //         ->select('charity_code','user_id','charge','total_donation_amount')
    //             ->selectRaw('now() >= "timer_expiry_timestamp" expire')
    //             ->selectRaw('now() >= ("timer_start" + interval \'6 days\') capture') // running out of time to capture within 7 days
    //         ->get();

    //     // $ids = array_keys($charities);
    //     // array_map([$charityTickerRepo, 'stopUserCharity'], array_values($charities), User::whereIn('id', $ids)->orderByRaw('case '.implode(' ',array_map(fn($id, $ord) => "when id = $id then $ord", $ids, range(0, count($ids)-1))).' end')->get()->all());
    
    //     // manually loading the "with('user')" relation conditionally based on 'expire'
    //     $users = [];
    //     $userids = $charities->where('expire')->pluck('user_id')->all();
    //     if($userids){
    //         $users = User::whereIn('id', $userids)->get()->keyBy('id')->all();
    //     }

    //     $stripe = null;
    //     foreach ($charities as $charity) {
    //         if($charity->expire){
    //             $charityTickerRepo->stopUserCharity($charity->charity_code, $users[$charity->user_id]);
    //         } else if ($charity->capture) {
    //             if($stripe===null){
    //                 $stripe = Stripe::make(config('services.stripe.secret'));
    //             }
    //             $charge = $stripe->charges()->capture($charity->charge, $charity->total_donation_amount, ['amount' => $charity->total_donation_amount]);
    //         }
    //     }


    //         // ->update(['timer_completed_at' => \DB::raw('"timer_expiry_timestamp"')]);
    //         // ->whereRaw("extract(epoch from now()) >= (extract(epoch from timer_start) + ((total_donation_amount * 100) / (donation_amount * 100)) * (tick_frequency * (
    //         //         case
    //         //             when tick_frequency_unit = 'sec' then 1
    //         //             when tick_frequency_unit = 'mins' then 60
    //         //             when tick_frequency_unit = 'hours' then 60 * 60
    //         //             when tick_frequency_unit = 'days' then 60 * 60 * 24
    //         //         end
    //         //     )))")

    // }
}
