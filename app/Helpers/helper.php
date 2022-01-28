<?php
use Carbon\Carbon;
if (!function_exists('isHomePage')) {
    // Check if current route is home page
    function isHomePage($custom = '', $with = 'or')
    {
        if ($custom && $with) {
            if ($with == 'or') {
                return Route::current()->getName() == 'home' or Route::current()->getName() == $custom;
            }
        }
        return Route::current()->getName() == 'home';
    }
}
if (!function_exists('getSecondsFromTick')) {
    // this function return days, hours, mins to seconds (in javascript time)
    function getSecondsFromTick($tick_frequency, $tick_frequency_unit)
    {
        if ($tick_frequency_unit == 'sec') {
            return $tick_frequency;// * 3
        } else if ($tick_frequency_unit == 'mins') {
            return $tick_frequency * 60;
        } else if ($tick_frequency_unit == 'hours') {
            return ($tick_frequency * 60) * 60;
        } else if ($tick_frequency_unit == 'days') {
            return $tick_frequency * 24 * 60 * 60;
        }
    }
}

if (!function_exists('calTotalDonationAmount')) {
    // Calculate total donation
    function calTotalDonationAmount(Carbon $timer_start, Carbon $timer_completed_at, $donation_amount, $tick_frequency, $tick_frequency_unit)
    {
        $data = [
            's' => $timer_start->diffInSeconds($timer_completed_at),
            'h' => $timer_start->diffInHours($timer_completed_at),
            'm' => $timer_start->diffInMinutes($timer_completed_at),
            'd' => $timer_start->diffInDays($timer_completed_at),
        ];
        if ($timer_start && $timer_completed_at) {
            if ($tick_frequency_unit == 'sec') {
                // calculate total seconds spent and then multiply it by price
                return (int) ($data['s'] / $tick_frequency) * $donation_amount; // ($tick_frequency * 3)
                //return round(((($tick_frequency * 3) * $data['s']) * $donation_amount), 2, PHP_ROUND_HALF_UP);
            } else if ($tick_frequency_unit == 'mins') {
                return (int) ($data['m'] / $tick_frequency) * $donation_amount;
                //return round($data['m'] * $donation_amount, 2, PHP_ROUND_HALF_UP);
            } else if ($tick_frequency_unit == 'hours') {
                return (int) ($data['h'] / $tick_frequency) * $donation_amount;
                //return round($data['h'] * $donation_amount, 2, PHP_ROUND_HALF_UP);
            } else if ($tick_frequency_unit == 'days') {
                return (int) ($data['d'] / $tick_frequency) * $donation_amount;
                //return round($data['d'] * $donation_amount, 2, PHP_ROUND_HALF_UP);
            }
        } else {
            return 0;
        }
    }
}

if (!function_exists('formatTimeT')) {
    function formatTimeT($time)
    {
        $startDate = Carbon::parse($time);
        return $startDate->toDayDateTimeString();
    }
}

if (!function_exists('formatDonationAmountText')) {
    function formatDonationAmountText($donation_amount, $tick_frequency, $tick_frequency_unit, $charity_organization)
    {
        $name = $charity_organization ? "for organization <b>{$charity_organization->name}</b>" : '';
        //$sec = $tick_frequency_unit == 'kdei' ? $tick_frequency*3 : $tick_frequency;
        // $fqText = $tick_frequency_unit == 'kdei' ? "{$sec}Sec({$tick_frequency}xKedei Dibur)" : $tick_frequency_unit; 
        // return "$ <i>{$donation_amount}</i> Every {$fqText} {$name}";
        return "$ <i>{$donation_amount}</i> Every {$tick_frequency} {$tick_frequency_unit} {$name}";
    }
}

// if (!function_exists('getRemainingTime')) {
//     function getRemainingTime($timer_expiry, $rt = false)
//     {
//         $startDate = Carbon::parse($timer_expiry);
//         $endDate = Carbon::parse(now());
//         if ($startDate->gt($endDate)) {
//             $data = [
//                 's' => $startDate->diffInSeconds($endDate),
//                 'h' => $startDate->diffInHours($endDate),
//                 'm' => $startDate->diffInMinutes($endDate),
//                 'd' => $startDate->diffInDays($endDate),
//             ];
//             if ($rt == true) {
//                 return $data['s'];
//             }
//             if ($data['d'] > 0) {
//                 return "{$data['d']} ".Str::plural('Day', $data['d']);
//             }
//             if ($data['h'] > 0) {
//                 return "{$data['h']} ".Str::plural('Hour', $data['h']);
//             }
//             if ($data['m'] > 0) {
//                 return "{$data['m']} ".Str::plural('Minute', $data['m']);
//             }
//             if ($data['s'] > 0) {
//                 return "{$data['s']} ".Str::plural('Second', $data['s']);
//             }
//             return "N/A";
//         } else {
//             return 0;
//         }
//     }
// }

// if (!function_exists('ttgschedule')) {
//     function ttgschedule($charityrepository)
//     {
//         $charities = \App\Models\CharityTicker::where('timer_start', '>', new \Carbon\Carbon(0))
//             ->where(function($query){
//                 $query->whereNull('timer_completed_at')->orWhereRaw('(not ("timer_completed_at" > to_timestamp(0)))');
//             })
//             ->where('total_donation_amount','>',0)
//             ->where('donation_amount','>',0)
//             ->where('tick_frequency','>',0)
//             ->whereIn('tick_frequency_unit', ['sec','mins','hours','days'])
//             ->where(function($query) {
//                 $query->whereRaw('now() >= "timer_expiry_timestamp"')->orWhereRaw('now() >= ("timer_start" + interval \'6 days\')');
//             })
//             ->select('charity_code','user_id','charge','total_donation_amount')
//                 ->selectRaw('now() >= "timer_expiry_timestamp" expire')
//                 ->selectRaw('now() >= ("timer_start" + interval \'6 days\') capture') // running out of time to capture within 7 days
//             ->get();

//         $users = [];
//         $userids = $charities->where('expire')->pluck('user_id')->all();
//         if($userids){
//             $users = \App\Models\User::whereIn('id', $userids)->get()->keyBy('id')->all();
//         }

//         $stripe = null;
//         foreach ($charities as $charity) {
//             if($charity->expire){
//                 $charityrepository->stopUserCharity($charity->charity_code, $users[$charity->user_id]);
//             } else if ($charity->capture) {
//                 if($stripe===null){
//                     $stripe = \Cartalyst\Stripe\Stripe::make(config('services.stripe.secret'));
//                 }
//                 $charge = $stripe->charges()->capture($charity->charge, $charity->total_donation_amount, ['amount' => $charity->total_donation_amount]);
//             }
//         }
//     }
// }