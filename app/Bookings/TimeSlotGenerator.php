<?php

namespace App\Bookings;

use App\Models\Service;
use App\Models\Schedule;
use Carbon\CarbonInterval;
use App\Bookings\Filters\Filter;

class TimeSlotGenerator
{
    const INCREMENT = 15;

    /**
     * @var mixed
     */
    public $schedule;

    /**
     * @var mixed
     */
    public $service;

    /**
     * @var mixed
     */
    protected $interval;

    /**
     * @param Schedule $schedule
     */
    public function __construct(Schedule $schedule, Service $service)
    {
        $this->schedule = $schedule;
        $this->service = $service;
        $this->interval = CarbonInterval::minutes(self::INCREMENT)
            ->toPeriod(
                $schedule->date->setTimeFrom($schedule->start_time),
                $schedule->date->setTimeFrom($schedule->end_time->subMinutes($service->duration)),
            );
    }

    /**
     * @param array $filters
     */
    public function applyFilters(array $filters)
    {
        foreach ($filters as $filter) {
            if (!$filter instanceof Filter) {
                continue;
            }
            $filter->apply($this, $this->interval);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->interval;
    }
}
