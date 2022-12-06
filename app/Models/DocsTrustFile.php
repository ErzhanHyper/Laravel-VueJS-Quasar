<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DocsTrust
 *
 * @property int $id
 * @property string $file
 * @property int $employee_id
 * @property int $warrant_id
 * @property string $direction
 * @property string $entrust
 * @property int $date
 * @property int $department_id
 * @property int $docs_trust_id
 * @property string $agent
 * @property int $profession_id
 * @property string $docs_type
 * @property string $date_expiration_start
 * @property int $date_expiration_end

 */
class DocsTrustFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'file',
        'department_id',
        'employee_id',
        'warrant_id',
        'direction',
        'entrust',
        'date',
        'date_expiration',
        'agent'
    ];

    public function employee(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function profession(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Profession::class, 'profession_id', 'id');
    }

}
