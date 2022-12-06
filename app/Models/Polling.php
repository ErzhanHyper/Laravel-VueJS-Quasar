<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polling extends Model
{
    use HasFactory;

    public function polling_choice(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(PollingChoice::class, 'polling_id', 'id');
    }
}
