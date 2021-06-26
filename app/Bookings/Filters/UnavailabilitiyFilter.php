<?php

namespace App\Bookings\Filters;

use Carbon\CarbonPeriod;
use App\Bookings\TimeSlotGenerator;
use Illuminate\Database\Eloquent\Collection;

class UnavailabilitiyFilter implements Filter
{
    /**
     * @var mixed
     */
    private $unavailabilities;

    /**
     * @param Collection $unavailabilities
     */
    public function __construct(Collection $unavailabilities)
    {
        $this->unavailabilities = $unavailabilities;
    }

    /**
     * @param TimeSlotGenerator $generator
     * @param CarbonPeriod $interval
     */
    public function apply(TimeSlotGenerator $generator, CarbonPeriod $interval)
    {
        $interval->addFilter(function ($slot) use ($generator) {
            foreach ($this->unavailabilities as $unavailability) {
                if (
                    $slot->between(
                        $unavailability->schedule->date->setTimeFrom(
                            $unavailability->start_time->subMinutes(
                                $generator->service->duration
                            )
                        ),
                        $unavailability->schedule->date->setTimeFrom(
                            $unavailability->end_time->subMinutes(
                                $generator->service->duration
                            )
                        )
                    )
                ) {
                    return false;
                }
            }

            return true;
        });
    }
}
