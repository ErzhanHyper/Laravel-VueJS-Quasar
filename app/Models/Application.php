<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $guarded = [];

    public function application_category(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(ApplicationCategory::class, 'application_category_id', 'id');
    }

    public function application_employee(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function application_file(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(ApplicationFile::class, 'application_id', 'id');
    }

    public function application_user(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function status(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(ApplicationComment::class, 'application_id', 'id');
    }

}
