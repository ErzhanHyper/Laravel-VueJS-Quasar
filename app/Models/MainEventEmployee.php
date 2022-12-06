<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainEventEmployee extends Model
{
    use HasFactory;

    public function event(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(MainEvent::class, 'id', 'event_id');
    }

}
