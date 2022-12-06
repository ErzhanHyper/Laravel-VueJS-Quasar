<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainEventFile extends Model
{
    use HasFactory;

    /**
     * @var false|mixed|string
     */
    private mixed $event_ids;
}
