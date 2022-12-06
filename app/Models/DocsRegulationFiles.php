<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocsRegulationFiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'file',
    ];

    public function employee(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function additionFile(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(DocsRegulationFileAddition::class, 'docs_regulation_file_id', 'id');
    }

    public function dynamic_file(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(DocsRegulationDynamicFile::class, 'docs_regulation_file_id', 'id');
    }

}
