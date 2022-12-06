<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocsRegulation extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'file_type_id',
    ];

    public function department(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function docs_type(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(DocsType::class, 'file_type_id', 'id');
    }

    public function docs_regulation_file(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(DocsRegulationFiles::class, 'docs_regulation_id', 'id')->orderBy('date_start', 'desc');
    }

    public function viewers(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(DocsRegulationViewer::class, 'docs_regulation_id', 'id');
    }

}
