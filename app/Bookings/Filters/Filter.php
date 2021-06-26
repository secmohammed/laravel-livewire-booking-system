<?php

namespace App\Bookings\Filters;

use Carbon\CarbonPeriod;
use App\Bookings\TimeSlotGenerator;

interface Filter
{
    /**
     * @param TimeSlotGenerator $generator
     * @param CarbonPeriod $interval
     */
    public function apply(TimeSlotGenerator $generator, CarbonPeriod $interval);
}
