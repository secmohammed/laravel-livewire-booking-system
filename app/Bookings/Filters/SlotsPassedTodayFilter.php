<?php

namespace App\Bookings\Filters;

use Carbon\CarbonPeriod;
use App\Bookings\TimeSlotGenerator;

class SlotsPassedTodayFilter implements Filter
{
    /**
     * @param TimeSlotGenerator $generator
     * @param CarbonPeriod $interval
     */
    public function apply(TimeSlotGenerator $generator, CarbonPeriod $interval)
    {
        $interval->addFilter(function ($slot) use ($generator) {
            if ($generator->schedule->date->isToday()) {
                if ($slot->lt(now())) {
                    return false;
                }
            }

            return true;
        });
    }
}
