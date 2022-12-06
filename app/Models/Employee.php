<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function employee_status(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(EmployeeStatus::class, 'employee_status_id', 'id');
    }

    public function profession(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Profession::class, 'profession_id', 'id');
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function employee_file(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(EmployeeFile::class, 'employee_id', 'id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
