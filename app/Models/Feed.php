<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;


    protected $fillable = [
        'feed_category_id',
        'text',
        'employee_id',
        'user',
    ];


    public function feed_category(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(FeedCategory::class, 'feed_category_id', 'id');
    }

    public function feed_employee(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

}
