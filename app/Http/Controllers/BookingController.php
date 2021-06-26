<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Employee;
use App\Models\Schedule;

class BookingController extends Controller
{
    public function __invoke()
    {
        $schedule = Schedule::find(4); // 26th june
        $service = Service::find(1); // 1 hour coding session
        $employee = Employee::find(1);
        $slots = $employee->availableTimeSlots($schedule, $service);

        return view('bookings.create', compact('slots'));
    }
}
