<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationFile extends Model
{
    use HasFactory;
    protected $table = 'application_files';

    protected $guarded = [];

    public function application(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Applications::class, 'application_file_id', 'id');
    }
}
