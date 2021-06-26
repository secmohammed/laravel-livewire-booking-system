<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Service;
use App\Models\Schedule;
use App\Bookings\TimeSlotGenerator;
use Illuminate\Database\Eloquent\Model;
use App\Bookings\Filters\AppointmentFilter;
use App\Bookings\Filters\UnavailabilitiyFilter;
use App\Bookings\Filters\SlotsPassedTodayFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    /**
     * @return mixed
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * @param Carbon $date
     * @return mixed
     */
    public function appointmentsForDate(Carbon $date)
    {
        return $this->appointments()->whereDate('date', $date)->get();
    }

    /**
     * @param Schedule $schedule
     * @param Service $service
     */
    public function availableTimeSlots(Schedule $schedule, Service $service)
    {
        return (new TimeSlotGenerator($schedule, $service))->applyFilters([
            new SlotsPassedTodayFilter,
            new UnavailabilitiyFilter($schedule->unavailabilities),
            new AppointmentFilter($this->appointmentsForDate($schedule->date)),
        ])->get();
    }

    /**
     * @return mixed
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * @return mixed
     */
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
}
