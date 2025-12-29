<?php

namespace App\Helpers;

use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;

class DateHelper
{
    /**
     * Convert Carbon date to Persian Jalali date
     */
    public static function toPersian($date, $format = 'Y/m/d H:i')
    {
        if (!$date) {
            return '-';
        }

        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $verta = Verta::instance($date);
        return $verta->format($format);
    }

    /**
     * Convert Carbon date to Persian Jalali date (date only)
     */
    public static function toPersianDate($date, $format = 'Y/m/d')
    {
        return self::toPersian($date, $format);
    }

    /**
     * Convert Carbon date to Persian Jalali date with time
     */
    public static function toPersianDateTime($date, $format = 'Y/m/d H:i')
    {
        return self::toPersian($date, $format);
    }

    /**
     * Persian version of diffForHumans
     */
    public static function diffForHumans($date, $short = false)
    {
        if (!$date) {
            return '-';
        }

        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $now = Carbon::now();
        $diffInSeconds = abs($now->diffInSeconds($date, false));
        $diffInMinutes = abs($now->diffInMinutes($date, false));
        $diffInHours = abs($now->diffInHours($date, false));
        $diffInDays = abs($now->diffInDays($date, false));
        $diffInWeeks = abs($now->diffInWeeks($date, false));
        $diffInMonths = abs($now->diffInMonths($date, false));
        $diffInYears = abs($now->diffInYears($date, false));

        $isPast = $date->isPast();

        if ($diffInSeconds < 60) {
            return $isPast ? 'چند لحظه پیش' : 'چند لحظه دیگر';
        } elseif ($diffInMinutes < 60) {
            $minutes = (int) floor($diffInMinutes);
            if ($short) {
                return $isPast ? "{$minutes} دقیقه پیش" : "{$minutes} دقیقه دیگر";
            }
            return $isPast ? "{$minutes} دقیقه پیش" : "{$minutes} دقیقه دیگر";
        } elseif ($diffInHours < 24) {
            $hours = (int) floor($diffInHours);
            if ($short) {
                return $isPast ? "{$hours} ساعت پیش" : "{$hours} ساعت دیگر";
            }
            return $isPast ? "{$hours} ساعت پیش" : "{$hours} ساعت دیگر";
        } elseif ($diffInDays < 7) {
            $days = (int) floor($diffInDays);
            if ($short) {
                return $isPast ? "{$days} روز پیش" : "{$days} روز دیگر";
            }
            return $isPast ? "{$days} روز پیش" : "{$days} روز دیگر";
        } elseif ($diffInWeeks < 4) {
            $weeks = (int) floor($diffInWeeks);
            if ($short) {
                return $isPast ? "{$weeks} هفته پیش" : "{$weeks} هفته دیگر";
            }
            return $isPast ? "{$weeks} هفته پیش" : "{$weeks} هفته دیگر";
        } elseif ($diffInMonths < 12) {
            $months = (int) floor($diffInMonths);
            if ($short) {
                return $isPast ? "{$months} ماه پیش" : "{$months} ماه دیگر";
            }
            return $isPast ? "{$months} ماه پیش" : "{$months} ماه دیگر";
        } else {
            $years = (int) floor($diffInYears);
            if ($short) {
                return $isPast ? "{$years} سال پیش" : "{$years} سال دیگر";
            }
            return $isPast ? "{$years} سال پیش" : "{$years} سال دیگر";
        }
    }

    /**
     * Format date in Persian with custom format
     */
    public static function format($date, $format = 'Y/m/d H:i')
    {
        return self::toPersian($date, $format);
    }
}

