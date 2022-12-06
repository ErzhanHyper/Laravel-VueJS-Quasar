<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationCategory extends Model
{
    use HasFactory;

    protected $table = 'application_categories';

    protected $guarded = [];

    public function application(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Application::class, 'application_category_id', 'id');
    }
}
