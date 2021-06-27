<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
        'end_time' => 'datetime',
        'start_time' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'client_name',
        'employee_id',
        'service_id',
        'client_email',
        'cancelled_at',
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
            $model->token = Str::random(32);
        });
    }

    /**
     * @return mixed
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function isCancelled()
    {
        return !is_null($this->cancelled_at);
    }

    /**
     * @param Builder $builder
     */
    public function scopeNotCancelled(Builder $builder)
    {
        $builder->whereNull('cancelled_at');
    }

    /**
     * @return mixed
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
