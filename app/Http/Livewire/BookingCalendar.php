<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Carbon\CarbonInterval;

class BookingCalendar extends Component
{
    /**
     * @var mixed
     */
    public $calendarStartDate;

    /**
     * @var mixed
     */
    public $date;

    /**
     * @var mixed
     */
    public $employee;

    /**
     * @var mixed
     */
    public $service;

    /**
     * @var mixed
     */
    public $time;

    public function decrementCalendarWeek()
    {
        $this->calendarStartDate->subWeek()->subDay();
    }

    /**
     * @return mixed
     */
    public function getAvailableTimeSlotsProperty()
    {
        if (!$this->employee || !$this->employeeSchedule) {
            return collect();
        }

        return $this->employee->availableTimeSlots($this->employeeSchedule, $this->service);
    }

    public function getCalendarSelectedDateObjectProperty()
    {
        return Carbon::createFromTimestamp($this->date);
    }

    public function getCalendarWeekIntervalProperty()
    {
        return CarbonInterval::day(1)->toPeriod(
            $this->calendarStartDate,
            $this->calendarStartDate->clone()->addWeek()
        );
    }

    /**
     * @return mixed
     */
    public function getEmployeeScheduleProperty()
    {
        return $this->employee
            ->schedules()
            ->whereDate('date', $this->calendarSelectedDateObject)
            ->first();
    }

    /**
     * @return mixed
     */
    public function getWeekIsGreaterThanCurrentProperty()
    {
        return $this->calendarStartDate->gt(now());
    }

    public function incrementCalenddarWeek()
    {
        $this->calendarStartDate->addWeek()->addDay();
    }

    public function mount()
    {
        $this->calendarStartDate = now();
        $this->setDate(now()->timestamp);
    }

    public function render()
    {
        return view('livewire.booking-calendar');
    }

    /**
     * @param $timestamp
     */
    public function setDate($timestamp)
    {
        $this->date = $timestamp;
    }

    /**
     * @param $time
     */
    public function updatedTime($time)
    {
        $this->emitUp('updated-booking-time', $time);
    }
}
