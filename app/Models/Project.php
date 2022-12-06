<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function status(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(ProjectEmployee::class, 'project_id', 'id');
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(ProjectFile::class, 'project_id', 'id');
    }

}
