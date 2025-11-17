<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('userTime')) {
    /**
     * Convert time to user's timezone
     */
    function userTime($datetime, $format = 'd M Y, H:i')
    {
        if (!$datetime) {
            return '-';
        }

        // Get user's timezone from settings
        $timezone = Auth::check()
            ? Auth::user()->getSettings()->timezone ?? 'Asia/Jakarta'
            : 'Asia/Jakarta';

        // Convert to user timezone
        return \Carbon\Carbon::parse($datetime)
            ->timezone($timezone)
            ->translatedFormat($format);
    }
}

if (!function_exists('userTimeWithZone')) {
    /**
     * Convert time to user's timezone with timezone name
     */
    function userTimeWithZone($datetime, $format = 'd M Y, H:i')
    {
        if (!$datetime) {
            return '-';
        }

        $timezone = Auth::check()
            ? Auth::user()->getSettings()->timezone ?? 'Asia/Jakarta'
            : 'Asia/Jakarta';

        $time = \Carbon\Carbon::parse($datetime)->timezone($timezone);

        return $time->translatedFormat($format) . ' (' . $time->tzName . ')';
    }
}
