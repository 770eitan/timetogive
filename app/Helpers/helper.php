<?php
use Carbon\Carbon;
if (!function_exists('getSecondsFromTick')) {
    // this function return days, hours, mins to seconds (in javascript time)
    function getSecondsFromTick($tick_frequency, $tick_frequency_unit)
    {
        if ($tick_frequency_unit == 'kdei') {
            return $tick_frequency * 3;
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
    function calTotalDonationAmount($timer_start, $timer_completed_at, $donation_amount, $tick_frequency, $tick_frequency_unit)
    {
        $startDate = Carbon::parse($timer_start);
        $endDate = Carbon::parse($timer_completed_at);
        $data = [
            's' => $startDate->diffInSeconds($endDate),
            'h' => $startDate->diffInHours($endDate),
            'm' => $startDate->diffInMinutes($endDate),
            'd' => $startDate->diffInDays($endDate),
        ];
        if ($timer_start && $timer_completed_at) {
            if ($tick_frequency_unit == 'kdei') {
                // calculate total seconds spent and then multiply it by price
                return round(((($tick_frequency * 3) * $data['s']) * $donation_amount), 2, PHP_ROUND_HALF_UP);
            } else if ($tick_frequency_unit == 'mins') {
                return round($data['m'] * $donation_amount, 2, PHP_ROUND_HALF_UP);
            } else if ($tick_frequency_unit == 'hours') {
                return round($data['h'] * $donation_amount, 2, PHP_ROUND_HALF_UP);
            } else if ($tick_frequency_unit == 'days') {
                return round($data['d'] * $donation_amount, 2, PHP_ROUND_HALF_UP);
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
        $name = $charity_organization ? "for <b>{$charity_organization->name}</b>" : '';
        return "$ <i>{$donation_amount}</i> Every {$tick_frequency}{$tick_frequency_unit} {$name}";
    }
}

if (!function_exists('getRemainingTime')) {
    function getRemainingTime($timer_expiry,$rt=false)
    {
        $startDate = Carbon::parse($timer_expiry);
        $endDate = Carbon::parse(now());
        if ($startDate->gt($endDate)) {
            $data = [
                's' => $startDate->diffInSeconds($endDate),
                'h' => $startDate->diffInHours($endDate),
                'm' => $startDate->diffInMinutes($endDate),
                'd' => $startDate->diffInDays($endDate),
            ];
            if($rt==true){
              return $data['s'];
            }
            if ($data['d'] > 0) {
                return "{$data['d']} Day(s)";
            }
            if ($data['h'] > 0) {
                return "{$data['h']} Hour(s)";
            }
            if ($data['m'] > 0) {
                return "{$data['m']} Minute(s)";
            }
            if ($data['s'] > 0) {
                return "{$data['s']} Second(s)";
            }
            return "N/A";
        } else {
            return 0;
        }
    }
}
