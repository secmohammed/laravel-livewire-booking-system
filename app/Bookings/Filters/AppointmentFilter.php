<?php

namespace App\Bookings\Filters;

use Carbon\CarbonPeriod;
use App\Bookings\TimeSlotGenerator;
use Illuminate\Database\Eloquent\Collection;

class AppointmentFilter implements Filter
{
    /**
     * @var mixed
     */
    private $appointments;

    /**
     * @param Collection $appointments
     */
    public function __construct(Collection $appointments)
    {
        $this->appointments = $appointments;
    }

    /**
     * @param TimeSlotGenerator $generator
     * @param CarbonPeriod $interval
     */
    public function apply(TimeSlotGenerator $generator, CarbonPeriod $interval)
    {
        $interval->addFilter(function ($slot) use ($generator) {
            foreach ($this->appointments as $appointment) {
                if ($slot->between(
                    $appointment->date->setTimeFrom(
                        $appointment->start_time->subMinutes(
                            $generator->service->duration
                        )
                    ),
                    $appointment->date->setTimeFrom(
                        $appointment->end_time
                    )

                )) {
                    return false;
                }
            }

            return true;
        });
    }
}
