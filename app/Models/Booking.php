<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public function employee(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }


}
