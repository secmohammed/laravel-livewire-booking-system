<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    /**
     * @return mixed
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }
}
