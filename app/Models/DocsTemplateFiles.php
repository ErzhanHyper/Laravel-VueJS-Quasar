<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\DocsTemplateFiles
 *
 * @property int $id
 * @property int $docs_template_id
 * @property int employee_id
 * @property string $file
 **/

class DocsTemplateFiles extends Model
{
    use HasFactory;


    public function employee(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
