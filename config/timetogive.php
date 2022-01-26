<?php

use Illuminate\Support\Arr;

return [

    'mode' => env('TIMETOGIVE_MODE', 'deposit'), // 'deposit', 'countup'
    'capture' => env('TIMETOGIVE_CAPTURE', false), // false, true

    // 'timezones' => array_map(fn($tz) => "$tz (".(new \Carbon\CarbonTimeZone($tz))->toOffsetName().')', timezone_identifiers_list())
    // 'timezones' => (function(){
    //     $tzcache = [];
    //     $p1 = [];
    //     $result = [];
    //     foreach (timezone_identifiers_list() as $tz) {
    //         $znm = Arr::get($tzcache, $tz, null);
    //         if(!$znm){
    //             $znm = $tzcache[$tz] = (new \Carbon\CarbonTimeZone($tz))->toOffsetName();
    //         }
    //         $k = (int)preg_replace('/[^\d+-]/' , '', $znm);
    //         if(!array_key_exists($k, $p1)){
    //             $p1[$k] = [];
    //         }
    //         $p1[$k][] = $tz;
    //     }
    //     ksort($p1);
    //     foreach ($p1 as $ord => $tzs) {
    //         sort($tzs);
    //         foreach ($tzs as $tz) {
    //             $result['('.$tzcache[$tz].") $tz"] = $tz;
    //         }
    //     }
    //     return $result;
    // })(),
];
