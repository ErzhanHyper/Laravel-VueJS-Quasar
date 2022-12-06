<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainEvent extends Model
{
    use HasFactory;

    protected $table = 'main_events';

    protected $guarded = [];

    public function employees(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(MainEventEmployee::class, 'event_id', 'id');
    }
}
