<?php
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
