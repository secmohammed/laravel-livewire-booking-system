<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Service;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Appointment;

class CreateBooking extends Component
{
    /**
     * @var mixed
     */
    public $employees;

    /**
     * @var array
     */
    public $state = [
        'service' => '',
        'employee' => '',
        'time' => '',
        'email' => '',
        'name' => '',
    ];

    /**
     * @var array
     */
    protected $listeners = [
        'updated-booking-time' => 'setTime',
    ];

    public function clearTime()
    {
        $this->state['time'] = '';
    }

    public function createBooking()
    {
        $this->validate();
        $appointment = Appointment::create([
            'date' => $this->timeObject->toDateString(),
            'start_time' => $this->timeObject->toTimeString(),
            'end_time' => $this->timeObject->clone()->addMinutes($this->selectedService->duration)->toTimeString(),
            'client_email' => $this->state['email'],
            'client_name' => $this->state['name'],
            'service_id' => $this->selectedService->id,
            'employee_id' => $this->selectedEmployee->id,
        ]);

        return redirect()->to(route('bookings.show', $appointment) . '?token=' . $appointment->token);
    }

    /**
     * @return mixed
     */
    public function getHasDetailsToBookProperty()
    {
        return $this->state['service'] && $this->state['employee'] && $this->state['time'];
    }

    public function getSelectedEmployeeProperty()
    {
        if (!$this->state['employee']) {
            return null;
        }

        return Employee::find($this->state['employee']);
    }

    public function getSelectedServiceProperty()
    {
        if (!$this->state['service']) {
            return null;
        }

        return Service::find($this->state['service']);
    }

    public function getTimeObjectProperty()
    {
        return Carbon::createFromTimestamp($this->state['time']);
    }

    public function mount()
    {
        $this->employees = collect();
    }

    public function render()
    {
        $services = Service::get();

        return view('livewire.create-booking', [
            'services' => $services,
        ])->layout('layouts.guest');
    }

    /**
     * @param $time
     */
    public function setTime($time)
    {
        $this->state['time'] = $time;
    }

    public function updatedStateEmployee()
    {
        $this->clearTime();
    }

    /**
     * @param $serviceId
     */
    public function updatedStateService($serviceId)
    {
        $this->state['employee'] = '';
        if (!$serviceId) {
            $this->employees = collect();

            return;
        }
        $this->clearTime();
        $this->employees = $this->selectedService->employees;
    }

    protected function rules()
    {
        return [
            'state.name' => 'required|string',
            'state.email' => 'required|email',
            'state.service' => 'required|exists:services,id',
            'state.employee' => 'required|exists:employees,id',
            'state.time' => 'required|numeric',
        ];
    }
}
