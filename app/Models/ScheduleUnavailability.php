<?php

namespace App\Models;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScheduleUnavailability extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * @return mixed
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
