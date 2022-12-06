<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DocsTrust
 *
 * @property int $id
 * @property string $name
 */
class DocsTrust extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function employee(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function docs_trust_file(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(DocsTrustFile::class, 'docs_trust_id', 'id')->orderByDesc('date')->latest();
    }
}
